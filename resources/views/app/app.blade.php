@extends('app.layout')
@section('title') {{ $title }} @endsection
@section('conteudo')

    <div class="pagetitle">
        <h1>{{ $title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">{{ $title }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="btn-group" role="group">
                <button type="button" title="Filtros" class="btn btn-dark modal-swal" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bx bx-folder-plus"></i> Nova Pasta
                </button>
                <button type="button" title="Filtros" class="btn btn-outline-dark modal-swal" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-filter-circle"></i> Filtros
                </button>
            </div>

            <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ route('create-folder') }}" method="POST" class="modal-content">
                        @csrf
                        <input type="hidden" name="company_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="plan_id" value="{{ Auth::user()->plan_id }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Nova Pasta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Nome:">
                                        <label for="name">Nome</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="description" class="form-control" id="description" placeholder="Descrição:" style="height: 100px;">
                                        <label for="description">Descrição</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="password" class="form-control" id="password" placeholder="Senha (Opcional)">
                                        <label for="password">Senha (Opcional)</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 mb-2">
                                    <select name="user_access[]" id="shareSelect">
                                        <option selected value=" ">Compartilhar com:</option>
                                        @foreach ($usersCompany as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer btn-group btn-group">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-dark">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ url()->current() }}" method="GET" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Pesquisar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Nome:">
                                        <label for="name">Pasta ou arquivo</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="description" class="form-control" id="description" placeholder="Descrição:" style="height: 100px;">
                                        <label for="description">Descrição</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="date" name="created_at" class="form-control" id="created_at" placeholder="Cadastro:">
                                        <label for="created_at">Cadastro</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer btn-group btn-group">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-dark">Pesquisar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card p-5">
                <div class="row">
                    @foreach ($folders as $folder)
                        <div class="col-6 col-sm-6 col-md-3 col-lg-2">
                            <a class="folder-link" data-folder-id="{{ $folder->id }}" data-password="{{ $folder->passwordRequere() ? 'true' : 'false' }}" data-user-id="{{ Auth::user()->id }}">
                                <div class="folder text-center">
                                    {!! $folder->iconLabel() !!}
                                    <small>{{ $folder->name }}</small>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    @include('app.Components.menu-folder')

    <script src="{{ asset('template/js/menu-folder.js') }}"></script>
    <script>
        $('.modal-swal').click( function(){
            var shares = new TomSelect("#shareSelect", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1000,
            });

            var users = new TomSelect("#usersSelect", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1000,
            });
        });
    </script>
@endsection