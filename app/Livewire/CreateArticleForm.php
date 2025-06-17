<?php

namespace App\Livewire;

use App\Jobs\GoogleVisionLabelImage;
use App\Jobs\GoogleVisionSafeSearch;
use App\Jobs\ResizeImage;
use File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Models\Category;
use App\Jobs\RemoveFaces;

class CreateArticleForm extends Component
{
    use WithFileUploads;

    public $images = [];
    public $temporary_images;
    public $categories = [];

    public $title;
    public $description;
    public $price;
    public $category;
    public $article;

    // Metodo di montaggio per caricare le categorie
    public function mount()
    {
        $this->categories = Category::all();
    }

    // Metodo per salvare l'articolo
    public function store()
    {
        // Validazione dei dati del form
        $this->validate([
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'category' => 'required',
            'temporary_images.*' => 'image|max:1024',
            'temporary_images' => 'max:6', // Fino a 6 immagini
        ]);

        // Creazione dell'articolo
        $this->article = Article::create([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category,
            'user_id' => Auth::id(),
        ]);

        // Salvataggio delle immagini
        if (count($this->images) > 0) {
            foreach ($this->images as $image) {
                $newFileName = "articles/{$this->article->id}"; // Nome dinamico basato sull'articolo
                $newImage = $this->article->images()->create([
                    'path' => $image->store($newFileName, 'public'),
                ]);

                // Coda per processare le immagini (es. Resize, SafeSearch, Label)
                RemoveFaces::withChain([
                    new ResizeImage($newImage->path, 300, 300),
                    new GoogleVisionSafeSearch($newImage->id),
                    new GoogleVisionLabelImage($newImage->id),
                ])->dispatch($newImage->id);
            }

            // Pulizia della directory temporanea
            File::deleteDirectory(storage_path('/app/livewire-tmp'));
        }

        // Messaggio di successo
        session()->flash('success', 'Articolo creato correttamente');
        
        // Pulizia del form
        $this->cleanForm();
    }

    // Metodo per pulire il form
    protected function cleanForm()
    {
        $this->title = '';
        $this->description = '';
        $this->category = '';
        $this->price = '';
        $this->images = [];
    }

    // Metodo per gestire l'aggiornamento delle immagini temporanee
    public function updatedTemporaryImages()
    {
        // Validazione delle immagini temporanee
        $this->validate([
            'temporary_images.*' => 'image|max:1024',
            'temporary_images' => 'max:6', // Limita il numero di immagini a 6
        ]);

        // Aggiungi le immagini selezionate all'array delle immagini
        foreach ($this->temporary_images as $image) {
            $this->images[] = $image;
        }
    }

    // Metodo per rimuovere un'immagine
    public function removeImage($key)
    {
        if (in_array($key, array_keys($this->images))) {
            unset($this->images[$key]);
        }
    }

    // Render per visualizzare il componente
    public function render()
    {
        return view('livewire.create-article-form');
    }
}
