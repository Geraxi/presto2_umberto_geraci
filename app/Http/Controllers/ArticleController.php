<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;

class ArticleController extends Controller
{
    /**
     * Display a listing of all accepted articles
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $articles = Article::where('is_accepted', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('article.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('article.create');
    }

    /**
     * Display the specified article
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\View\View
     */
    public function show(Article $article)
    {
        return view('article.show', compact('article'));
    }

    /**
     * Display articles filtered by category
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function byCategory(Category $category)
    {
        $articles = Article::where('category_id', $category->id)
            ->where('is_accepted', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('article.index', compact('articles', 'category'));
    }

    /**
     * Accept an article (for revisors)
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Article $article)
    {
        $article->setAccepted(true);
        return redirect()
            ->back()
            ->with('message', "Hai accettato l'articolo $article->title");
    }

    /**
     * Reject an article (for revisors)
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Article $article)
    {
        $article->setAccepted(false);
        return redirect()
            ->back()
            ->with('message', "Hai rifiutato l'articolo $article->title");
    }
}