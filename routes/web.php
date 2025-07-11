<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\RevisorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [PublicController::class,'homepage'])->name('homepage');

//creare un articolo per un utente auth
Route::get('/create/article', [ArticleController::class,'create'])->name('create.article');

Route::get('/article/index', [ArticleController::class,'index'])->name('article.index');

Route::get('/show/article/{article}', [ArticleController::class,'show'])->name('article.show');

Route::get('/category/{category}', [ArticleController::class,'byCategory'])->name('byCategory');

//isrevisor
Route::get('revisor/index', [RevisorController::class,'index'])->middleware('isRevisor')->name('revisor.index');

Route::get('/revisor/request', [RevisorController::class,'becomeRevisor'])->middleware('auth')->name('become.revisor');

Route::get('/make/revisor/{user}', [RevisorController::class,'makeRevisor'])->name('make.revisor');

Route::get('/search/article', [PublicController::class,'searchArticles'])->name('article.search');
Route::post('/lingua/{lang}', [PublicController::class,'setLanguage'])->name('setLocale');

Route::get('/articles', [ArticleController::class, 'index'])->name('article.index');

Route::patch('/accept/{article}', [ArticleController::class, 'accept'])->name('accept');
Route::patch('/reject/{article}', [ArticleController::class, 'reject'])->name('reject');