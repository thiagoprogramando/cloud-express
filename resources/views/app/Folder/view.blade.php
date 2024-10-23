@extends('app.layout')
@section('title') {{ $folder->name }} @endsection
@section('conteudo')

    <link rel="stylesheet" href="{{ asset('template/css/dropzone.min.css') }}" type="text/css" />
    <style>
        #dropzone {
            background-color: transparent;
            border: none;

            margin: 0;
            padding: 0;
        }
    </style>

    <div class="pagetitle">
        <h1>{{ $folder->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">Área de Trabalho</a></li>
                <li class="breadcrumb-item active">{{ $folder->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row dropzone" id="dropzone">
        <div class="col-12 mb-3">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-dark" onclick="history.back()"><i class="ri-arrow-left-s-line"></i></button>
                <button type="button" title="Filtros" class="btn btn-outline-dark modal-swal" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bx bx-folder-plus"></i> Nova Pasta
                </button>
                <button type="button" title="Filtros" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-filter-circle"></i> Filtros
                </button>
                <a href="{{ route('folder', ['id' => $folder->id]) }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>

            <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ route('create-folder') }}" method="POST" class="modal-content">
                        @csrf
                        <input type="hidden" name="company_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="plan_id" value="{{ Auth::user()->plan_id }}">
                        <input type="hidden" name="folder_id" value="{{ $folder->id }}">
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
                        <div class="modal-footer btn-group">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-dark">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ route('folder', ['id' => $folder->id]) }}" method="GET" class="modal-content">
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
                    @if($folders->count() > 0)
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
                    @endif
                    
                    @if($files->count() > 0)
                        @foreach ($files as $file)
                            <div class="col-6 col-sm-6 col-md-3 col-lg-2">
                                <a class="file-link" data-file-id="{{ $file->id }}">
                                    <div class="folder text-center">
                                        {!! $file->iconLabel() !!}
                                        <small>{{ $file->name }}</small>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('app.Components.menu-folder')

    <script src="{{ asset('template/js/dropzone.min.js') }}"></script>
    <script src="{{ asset('template/js/menu-folder.js') }}"></script>
    <script src="{{ asset('template/js/menu-file.js') }}"></script>
    <script>
        $('.modal-swal').click( function(){
            var plan = new TomSelect("#shareSelect", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1000,
            });
        });

        Dropzone.options.dropzone = {
            dictDefaultMessage: 'Solte os arquivos aqui para iniciar o upload',
            paramName: "file",
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip",
            init: function() {
                this.on("sending", function(file, xhr, formData) {
                    formData.append('user_id', "{{ auth()->id() }}");
                    formData.append('folder_id', "{{ $folder->id }}");
                });
                this.on("success", function(file, response) {
                    let newFileHtml = `
                        <div class="col-6 col-sm-6 col-md-3 col-lg-2">
                            <a class="file-link" data-file-id="${response.id}">
                                <div class="folder text-center">
                                    ${response.iconLabel}
                                    <small>${response.name}</small>
                                </div>
                            </a>
                        </div>
                    `;
                    
                    $('.card .row').append(newFileHtml);
                });
                this.on("error", function(file, response) {
                    Swal.fire({
                        title: 'Error!',
                        text: response,
                        icon: 'error',
                        timer: 3000,
                    });
                });
            },
            url: "{{ route('upload-file') }}",
        };
    </script>
@endsection