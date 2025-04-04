<x-layout>
    <div class="container-fluid text-center">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12">
                <h1 class="display-1">Presto.it</h1>
                <div class="my-3">
                    @auth
                        <a class="btn btn-dark" href="{{ route('create.article') }}">Pubblica un articolo</a>
                    @endauth
                </div>

                <nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="{{ route('homepage') }}">Presto.it</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page"
                                        href="{{ route('homepage') }}">Home</a>
                                </li>
                                @auth
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            Ciao, {{ Auth::user()->name }}
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('create.article') }}">Crea</a>
                                                <!-- Fixed route name -->
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#"
                                                    onclick="event.preventDefault(); document.querySelector('#form-logout').submit();">
                                                    Logout
                                                </a>
                                            </li>
                                        </ul>
                                        <!-- Moved form outside dropdown-menu -->
                                        <form action="{{ route('logout') }}" method="POST" class="d-none" id="form-logout">
                                            @csrf
                                        </form>
                                    </li>
                                @else
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            Ciao, utente!
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('login') }}">Accedi</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="{{ route('register') }}">Registrati</a></li>
                                        </ul>
                                    </li>
                                @endauth

                                @if (session()->has('errorMessage'))
                                <div class="alert alert-danger text-center shadow rounded w-50">
                                    {{ session('errorMessage') }}
                                </div>  
                                @endif

                                @if (session()->has('message'))
                                <div class="alert alert-success text-center shadow rounded w-50">
                                    {{ session('message') }}
                                </div>
                                
                                @endif
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</x-layout>