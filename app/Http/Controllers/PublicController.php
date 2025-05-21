<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class PublicController extends Controller
{
    /**
     * Mostra la homepage con gli ultimi 6 articoli accettati.
     *
     * @return \Illuminate\View\View
     */
    public function homepage()
    {
        // Recupera gli ultimi 6 articoli accettati
        $articles = Article::where('is_accepted', true)
                           ->orderBy('created_at', 'desc')
                           ->take(6)
                           ->get();

        // Restituisce la vista della homepage con gli articoli
        return view('articles.welcome', compact('articles'));
    }

    /**
     * Imposta la lingua preferita dall'utente nella sessione.
     *
     * @param string $lang
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLanguage($lang)
    {
        // Memorizza la lingua scelta dall'utente nella sessione
        session()->put('locale', $lang);

        // Ritorna alla pagina precedente
        return redirect()->back();
    }

    /**
     * Cerca articoli basandosi sul titolo o sulla descrizione.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function searchArticles(Request $request)
    {
        // Recupera il termine di ricerca dalla query string
        $searchTerm = $request->query('q');

        // Validazione: verifica che il termine di ricerca non sia vuoto
        if (!$searchTerm || strlen(trim($searchTerm)) === 0) {
            return redirect()->route('homepage')->with('message', 'Inserisci un termine di ricerca valido.');
        }

        // Cerca articoli nei campi 'title' e 'description' e filtra quelli accettati
        $articles = Article::where('is_accepted', true)
                           ->where(function ($query) use ($searchTerm) {
                               $query->where('title', 'like', '%' . $searchTerm . '%')
                                     ->orWhere('description', 'like', '%' . $searchTerm . '%');
                           })
                           ->paginate(10); // Paginazione

        // Passa i risultati della ricerca alla vista
        return view('articles.searched', [
            'articles' => $articles,
            'query' => $searchTerm,
        ]);
    }
}
