<?php

namespace App\Livewire;

use App\Jobs\GoogleVisionLabelImage;
use App\Jobs\GoogleVisionSafeSearch;
use App\Jobs\ResizeImage;
use File;
use Livewire\Component;
use Livewire\Attributes\Validate; 
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use Livewire\WithFileUploads;
use App\Jobs\RemoveFaces;

class CreateArticleForm extends Component
{
    
    use WithFileUploads;

    public $images=[];
    public $temporary_images;


    #[Validate('required|min:5')] 
    public $title;
    #[Validate('required|min:10')]
    public $description;
    #[Validate('required|numeric')]
    public $price;
    #[Validate('required')]
    public $category;
    public $article;

    public function store()
{
    $this->validate();
    $this->article = Article::create([
        'title' => $this->title,
        'description' => $this->description,
        'price' => $this->price,
        'category_id' => $this->category,
        'user_id' => Auth::id()
    ]);

    if (count($this->images) > 0) {
        foreach ($this->images as $image) {
            $newFileName = "articles/{$this->article->id}"; 
            $newImage = $this->article->images()->create(['path' => $image->store($newFileName, 'public') ]);

            RemoveFaces::withChain([
                new ResizeImage($newImage->path, 300, 300),
                new GoogleVisionSafeSearch($newImage->id),
                new GoogleVisionLabelImage($newImage->id),

            ])->dispatch($newImage->id);
           
        }
        File::deleteDirectory(storage_path('/app/livewire-tmp'));
    }

    session()->flash('success', 'Articolo creato correttamente');
    $this->cleanForm();
}

    protected function cleanForm()
    {
        $this->title='';
        $this->description='';
        $this->category='';
        $this->price='';
        $this->images=[];

        $this->cleanForm();
    }

    public function updatedTemporaryImages()
{
    if ($this->validate([ // Fixed: { → [
        'temporary_images.*' => 'image|max:1024',
        'temporary_images' => 'max:6'
    ])) { // Fixed: Added closing ]) and )
        foreach ($this->temporary_images as $image) {
            $this->images[] = $image;
        }
    }
}

public function removeImage($key)
{
    if(in_array($key,array_keys($this->images))){
        unset($this->images[$key]);
    }
}
}
