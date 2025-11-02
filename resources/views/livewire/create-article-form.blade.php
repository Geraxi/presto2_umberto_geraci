<form class="bg-body-tertiary shadow rounded p-5 my-5" wire:submit.prevent="store">
    <div class="mb-3">
        <label for="title" class="form-label">Titolo:</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model="title">
        @error('title')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Descrizione:</label>
        <textarea id="description" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror"
            wire:model="description"></textarea>
        @error('description')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Prezzo:</label>
        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" wire:model="price">
        @error('price')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    













    <!-- Preview delle immagini caricate -->
    <div class="mb-3">
        <label for="temporary_images" class="form-label">Immagini:</label>
        <input type="file" wire:model="temporary_images" multiple class="form-control shadow @error('temporary_images.*') is-invalid @enderror">
        @error('temporary_images.*')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
        @error('temporary_images')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror

        @if (count($images) > 0)
            <p>Anteprima delle immagini:</p>
            <div class="row border border-4 border-success rounded shadow py-4">
                @foreach ($images as $key => $image)
                    <div class="col d-flex flex-column align-items-center my-3">
                        <div class="img-preview mx-auto shadow rounded" style="background-image: url({{ $image->temporaryUrl() }});"></div>
                        <button type="button" class="btn mt-1 btn-danger" wire:click="removeImage({{ $key }})">×</button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mb-3">
        <select id="category" wire:model="category" class="form-control @error('category') is-invalid @enderror">
            <option label disabled>Seleziona una categoria</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-dark">Crea</button>
    </div>
</form>
