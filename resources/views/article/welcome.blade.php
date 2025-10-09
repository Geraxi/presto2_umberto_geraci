<x-layout>
    <div class="container-fluid text-center">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12">
                <h1 class="display-1">Presto.it</h1>
                <div class="my-3">
                    @auth
                        <a class="btn btn-dark" href="{{ route('create.article') }}">{{ __('ui.publishArticle') }}</a>
                    @endauth
                </div>

               

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