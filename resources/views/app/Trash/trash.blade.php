@extends('app.layout')
@section('title') Lixeira @endsection
@section('conteudo')

    <style>
        .trash-deleted_at {
            font-size: 10px;
        }

        label {
            font-size: 10px;
        }
    </style>

    <div class="pagetitle">
        <h1>Lixeira</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">Área de Trabalho</a></li>
                <li class="breadcrumb-item active">Lixeira</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-dark" onclick="history.back()"><i class="ri-arrow-left-s-line"></i></button>
                <button type="button" class="btn btn-outline-dark" id="clearTrashButton"><i class="bi bi-trash"></i> Limpar lixeira</button>
                <button type="button" title="Filtros" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-filter-circle"></i> Filtros
                </button>
                <a href="{{ route('trash') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>

            <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ route('trash') }}" method="GET" class="modal-content">
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
                                        <label for="created_at">Data exclusão</label>
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
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <span class="badge bg-danger">Dê um duplo click para restaurar uma pasta ou arquivo</span>
                        <span class="badge bg-dark">Foram localizados {{ $folders->count() + $files->count() }} itens na lixeira</span>
                        <hr>
                    </div>

                    @if($folders->count() > 0)
                        @foreach ($folders as $folder)
                            <div class="col-6 col-sm-6 col-md-3 col-lg-2">
                                <a class="folder-link" data-folder-id="{{ $folder->id }}">
                                    <div class="folder text-center">
                                        {!! $folder->iconLabel() !!}
                                        <small>{{ $folder->name }}</small>
                                        <sub>Excluído em: {{ $folder->deleted_at->format('d/m/Y') }}</sub>
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
                                        <small class="trash-deleted_at">Excluído em: {{ $file->deleted_at->format('d/m/Y') }}</small>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif

                    @if($users->count() > 0)
                        <div class="mt-5 table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Dados</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col" class="text-center">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <th scope="row">
                                                <a href="#"><img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('template/img/components/profile.png') }}" width="30" class="rounded-circle"></a> <br>
                                                {{ $user->name }}
                                            </th>
                                            <td>
                                                {{ $user->cpfcnpj }}<br>
                                                <span class="badge bg-dark">{{ $user->email }}</span>
                                            </td>
                                            <td>{{ $user->typeLabel() }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('restore-user', ['id' => $user->id]) }}" class="btn btn-dark"><i class="bi bi-arrow-counterclockwise"></i> Restaurar</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    
                    
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-5 card p-5 d-none" id="trash">
                        <form action="{{ route('trash-clear') }}" method="POST" class="d-grid gap-2 mt-3">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="">
                                <label class="form-check-label" for="flexSwitchCheckChecked">
                                    Ao optar por limpar a lixeira, você concorda que todos os arquivos, 
                                    pastas e dados contidos na aplicação serão eliminados de forma permanente. 
                                    Esta ação é irreversível, e não será possível restaurar ou recuperar os itens excluídos. 
                                    Recomendamos que você verifique cuidadosamente os itens presentes na lixeira 
                                    antes de proceder com a limpeza, pois todos os dados serão apagados definitivamente, 
                                    sem a possibilidade de recuperação.
                                </label>
                            </div>
                            <button class="btn btn-sm btn-danger">Limpar lixeira</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.folder-link').forEach(item => {
            item.addEventListener('dblclick', function() {
                const folderId = this.getAttribute('data-folder-id');
                window.location.href = `/restore-folder/${folderId}`;
            });
        });

        document.querySelectorAll('.file-link').forEach(item => {
            item.addEventListener('dblclick', function() {
                const fileId = this.getAttribute('data-file-id');
                window.location.href = `/restore-file/${fileId}`;
            });
        });

        document.getElementById('clearTrashButton').addEventListener('click', function() {
            const trashDiv = document.getElementById('trash');
            trashDiv.classList.toggle('d-none');
        });
    </script>
@endsection