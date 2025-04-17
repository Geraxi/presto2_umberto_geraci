<?php

namespace App\Http\Controllers;

use App\Mail\BecomeRevisor;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;


class RevisorController extends Controller
{
    public function index()
    {
        
        $article_to_check = Article::where('is_accepted', null)->first();
        return view('revisor.index', compact('article_to_check'));
    }

    public function accept(Article $article)
    {
        $article->setAccepted(true);
        return redirect()
        ->back()
        ->with('message',"Hai accettato l'articolo $article->title");
    }

    public function reject(Article $article)
    {
        $article->setAccepted(false);
        return redirect()
        ->back()
        ->with('message',"Hai rifiutato l'articolo $article->title");
    }
    public function becomeRevisor()
    {
        // Verifica che l'utente sia loggato
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Devi essere autenticato per richiedere di diventare revisore');
        }
    
        // Ottieni l'utente autenticato
        $user = auth()->user();
    
        // Invia l'email
        Mail::to('admin@presto.it')->send(new BecomeRevisor($user));
    
        return redirect()->route('homepage')->with('message', 'Complimenti, Hai richiesto di diventare revisore');
    }
    
    
    
    public function makeRevisor(User $user)
    {
        Artisan::call('app:make-user-revisor',['email'=>$user->email]);
        return redirect()->back();
    }
}