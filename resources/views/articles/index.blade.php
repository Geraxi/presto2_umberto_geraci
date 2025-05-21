<x-layout>
    <div class="container-fluid">
        <div class="row py-5 justify-content-center text-center">
            <div class="col-12">
                <h1 class="display-1">Tutti gli articoli</h1>
            </div>
        </div>
        <div class="row justify-content-center py-5">
            @forelse ($articles as $article)
                <div class="col-12 col-md-3 mb-4">
                    <x-card :article="$article" />
                </div>
            @empty
                <div class="col-12">
                    <h3 class="text-center text-muted">
                        Non sono ancora stati creati articoli.
                    </h3>
                </div>
            @endforelse
        </div>
        @if ($articles->hasPages())
            <div class="d-flex justify-content-center">
                <div>
                    {{ $articles->links() }}
                </div>
            </div>
        @endif
    </div>
</x-layout>
