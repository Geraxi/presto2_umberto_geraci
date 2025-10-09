<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;


class SetLocaleMiddleware{
    public function handle(Request $request, Closure $next) : Response{
        $localLanguage = session('locale', 'it');
        App::setLocale($localeLanguage);
        return $next($request);
    }
}



class IsRevisor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_revisor){
            return $next($request);

        }
        return redirect()->route('homepage')->with('errorMessage','Zona riservata ai revisori');
    }
}

