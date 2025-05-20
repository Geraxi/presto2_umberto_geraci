<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class PublicController extends Controller
{
   public function homepage() {


        $articles=Article::where('is_accepted',true)->orderBy('created_at','desc')->take(6)->get();
        return view('welcome', compact('articles'));
    }

    public function setLanguage($lang)
    {
        session()->put('locale',$lang);
        return redirect()->back();
    }

    public function searchArticles(Request $request)
{
    // Recupera il termine di ricerca dalla query string
    $searchTerm = $request->query('q');

    // Cerca nei campi 'title' e 'description'
    $articles = Article::where('title', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%')
                        ->where('is_accepted', true)
                        ->paginate(10); // Paginazione

    // Passa il termine di ricerca e gli articoli alla vista
    return view('articles.searched', [ // Aggiorna il percorso della vista
        'articles' => $articles,
        'query' => $searchTerm,
    ]);
}

    


}
