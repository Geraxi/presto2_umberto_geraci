<h1 class="display-1">{{ __('ui.allArticles') }}</h1>

<x-layout>
    <div class="container-fluid pt-5">
        {{-- Titolo dashboard --}}
        <div class="row">
            <div class="col-3">
                <div class="rounded shadow bg-body-secondary">
                    <h1 class="display-5 text-center pt-2">Revisor Dashboard</h1>
                </div>
            </div>
        </div>

        @if ($article_to_check)
            {{-- Contenuto dashboard --}}
            <div class="row pt-5">
                {{-- Sezione immagini --}}
                <div class="col-md-8">
                    <div class="row">
                        @if ($article_to_check->images->count())
                            @foreach ($article_to_check->images as $key => $image)
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="{{ $image->getUrl(300, 300) }}"
                                                     class="img-fluid rounded-start"
                                                     alt="Immagine {{ $key + 1 }} dell'articolo '{{ $article_to_check->title }}'">
                                            </div>

                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5>Etichette</h5>
                                                    @if ($image->labels)
                                                        <p>
                                                            @foreach ($image->labels as $label)
                                                                <span class="badge bg-primary me-1">{{ $label }}</span>
                                                            @endforeach
                                                        </p>
                                                    @else
                                                        <p class="fst-italic">Nessuna etichetta disponibile</p>
                                                    @endif

                                                    <h5 class="mt-3">Ratings</h5>
                                                    @php
                                                        $ratings = ['adult', 'violence', 'spoof', 'racy', 'medical'];
                                                    @endphp
                                                    @foreach ($ratings as $rating)
                                                        <div class="d-flex justify-content-between">
                                                            <span>{{ ucfirst($rating) }}</span>
                                                            <span class="badge bg-secondary">{{ $image->$rating }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Immagini placeholder --}}
                            @for ($i = 0; $i < 6; $i++)
                                <div class="col-md-4 mb-4 text-center">
                                    <img src="https://picsum.photos/300"
                                         alt="Immagine segnaposto"
                                         class="img-fluid rounded shadow">
                                </div>
                            @endfor
                        @endif
                    </div>
                </div>

                {{-- Dettagli articolo + azioni --}}
                <div class="col-md-4 d-flex flex-column justify-content-between ps-md-4">
                    <div>
                        <h1>{{ $article_to_check->title }}</h1>
                        <h4>Autore: {{ $article_to_check->user->name }}</h4>
                        <h4>Prezzo: €{{ number_format($article_to_check->price, 2) }}</h4>
                        <h5 class="fst-italic text-muted">{{ $article_to_check->category->name }}</h5>
                        <p class="mt-3">{{ $article_to_check->description }}</p>
                    </div>

                    <div class="d-flex justify-content-around pb-4">
                        {{-- Bottone rifiuta --}}
                        <form action="{{ route('reject', ['article' => $article_to_check]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-danger py-2 px-5 fw-bold">Rifiuta</button>
                        </form>

                        {{-- Bottone accetta --}}
                        <form action="{{ route('accept', ['article' => $article_to_check]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success py-2 px-5 fw-bold">Accetta</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Messaggio sessione --}}
            @if (session()->has('message'))
                <div class="row justify-content-center mt-4">
                    <div class="col-md-5 alert alert-success text-center shadow rounded">
                        {{ session('message') }}
                    </div>
                </div>
            @endif
        @else
            {{-- Nessun articolo da revisionare --}}
            <div class="row justify-content-center align-items-center text-center mt-5">
                <div class="col-12">
                    <h1 class="fst-italic display-4">Nessun articolo da revisionare</h1>
                    <a href="{{ route('homepage') }}" class="btn btn-success mt-4">Torna all'homepage</a>
                </div>
            </div>
        @endif
    </div>
</x-layout>
