<nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('homepage') }}">Presto.it</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <!-- Home Links -->
        <li class="nav-item">
          <a class="nav-link active" href="{{ route('homepage') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('article.index') }}">Tutti gli articoli</a>
        </li>

        <!-- Language Switcher -->
        <x-_locale lang="it" />
        <x-_locale lang="en" />
        <x-_locale lang="es" />

        <!-- Categories Dropdown -->
        @isset($categories)
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categorie
          </a>
          <ul class="dropdown-menu">
            @foreach ($categories as $category)
            <li>
              <a class="dropdown-item text-capitalize" href="{{ route('byCategory', ['category' => $category]) }}">
                {{ $category->name }}
              </a>
            </li>
            @if(!$loop->last)<hr class="dropdown-divider">@endif
            @endforeach
          </ul>
        </li>
        @endisset

        <!-- Search Form -->
        <li class="nav-item ms-auto">
          <form action="{{ route('article.search') }}" class="d-flex" role="search" method="GET">
            <div class="input-group">
              <input type="search" name="query" class="form-control" placeholder="Search" aria-label="search">
              <button type="submit" class="input-group-text btn btn-outline-success">Search</button>
            </div>
          </form>
        </li>

        <!-- Auth Section -->
        @auth
          @if(Auth::user()->is_revisor)
          <li class="nav-item">
            <a class="nav-link btn btn-outline-success btn-sm position-relative" 
               href="{{ route('revisor.index') }}">
              Zona revisore
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ App\Models\Article::toBeRevisedCount() }}
              </span>
            </a>
          </li>
          @endif

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Ciao, {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ route('create.article') }}">Crea</a></li>
              <li>
                <a class="dropdown-item" href="#"
                   onclick="event.preventDefault(); document.querySelector('#form-logout').submit();">
                  Logout
                </a>
              </li>
            </ul>
          </li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Ciao, utente!
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ route('login') }}">Accedi</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="{{ route('register') }}">Registrati</a></li>
            </ul>
          </li>
        @endauth
      </ul>
    </div>
  </div>
  <!-- Logout Form -->
  <form action="{{ route('logout') }}" method="POST" class="d-none" id="form-logout">
    @csrf
  </form>
</nav>