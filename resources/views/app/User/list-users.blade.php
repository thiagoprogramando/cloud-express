@extends('app.layout')
@section('title') Gestão de Usuários @endsection
@section('conteudo')
    <div class="pagetitle">
        <h1>Gestão de Usuários</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">Área de Trabalho</a></li>
                <li class="breadcrumb-item active">Gestão de Usuários</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        
        <div class="col-12 mb-3">
            <div class="btn-group" role="group">
                <button type="button" title="Filtros" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="ri-user-add-line"></i> Novo
                </button>
                <button type="button" title="Filtros" class="btn btn-outline-dark modal-swal" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-filter-circle"></i> Filtros
                </button>
                {{-- <a href="{{ route('user-excel', request()->query()) }}" class="btn btn-outline-dark" title="Excel">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </a>                    
                <a href="{{ route('usuarios') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a> --}}
            </div>

            <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ route('create-user') }}" method="POST" class="modal-content">
                        @csrf
                        <input type="hidden" name="company_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="plan_id" value="{{ Auth::user()->plan_id }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Cadastro de Usuário</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" required>
                                        <label for="name">Nome</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating mb-2">
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Email:" required>
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating mb-2">
                                        <input type="number" name="cpfcnpj" class="form-control" id="cpfcnpj" placeholder="CPF ou CNPJ:" required>
                                        <label for="cpfcnpj">CPF ou CNPJ</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 mb-2">
                                    <div class="form-floating">
                                        <select name="role" class="form-select" id="floatingSelect">
                                            <option disabled>Tipos:</option>
                                            <option value="admin">Administrador</option>
                                            <option value="user">Colaborador</option>
                                        </select>
                                        <label for="floatingSelect">Tipo de usuário</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <span class="badge bg-warning text-light">A primeira senha será o CPF ou CNPJ (Apenas números)!</span>
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
                    <form action="{{ route('list-users') }}" method="GET" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pesquisar</h5>
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
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating mb-2">
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Email:">
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating mb-2">
                                        <input type="number" name="cpfcnpj" class="form-control" id="cpfcnpj" placeholder="CPF ou CNPJ:">
                                        <label for="cpfcnpj">CPF ou CNPJ</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <select id="swal-plan" name="plans[]" placeholder="Escolha de Planos">
                                        <option value="" selected>Planos</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer btn-group">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-dark">Pesquisar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card p-5">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Dados</th>
                            <th scope="col">Plano</th>
                            <th scope="col">Tipo</th>
                            <th scope="col" class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">
                                    <a href="#"><img src="{{ $user->photo ? asset($user->photo) : asset('template/img/components/profile.png') }}" width="30" class="rounded-circle"></a> <br>
                                    {{ $user->name }}
                                </th>
                                <td>
                                    {{ $user->cpfcnpj }}<br>
                                    <span class="badge bg-dark">{{ $user->email }}</span>
                                </td>
                                <td>{{ $user->plan->name ?? '---' }}</td>
                                <td>{{ $user->typeLabel() }}</td>
                                <td class="text-center">
                                    <form action="{{ route('delete-user') }}" method="POST" class="btn-group delete">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#updateModal{{ $user->id }}"><i class="ri-edit-line"></i></button>
                                    </form> 
                                </td>
                            </tr>

                            <div class="modal fade" id="updateModal{{ $user->id }}" tabindex="-1" aria-hidden="true" style="display: none;">
                                <form action="{{ route('update-user') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Pesquisar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-floating mb-2">
                                                            <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" value="{{ $user->name }}">
                                                            <label for="name">Nome</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <div class="form-floating mb-2">
                                                            <input type="email" name="email" class="form-control" id="email" placeholder="Email:" value="{{ $user->email }}">
                                                            <label for="email">Email</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <div class="form-floating mb-2">
                                                            <input type="number" name="phone" class="form-control" id="phone" placeholder="Telefone:" value="{{ $user->phone }}">
                                                            <label for="phone">Telefone</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <div class="form-floating mb-2">
                                                            <input type="number" name="cpfcnpj" class="form-control" id="cpfcnpj" placeholder="CPF ou CNPJ:" value="{{ $user->cpfcnpj }}">
                                                            <label for="cpfcnpj">CPF ou CNPJ</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <div class="form-floating mb-2">
                                                            <select name="type" class="form-select" id="floatingSelect">
                                                                <option disabled>Tipos:</option>
                                                                <option value="admin" @selected($user->type == 'user')>Administrador</option>
                                                                <option value="user"  @selected($user->type == 'user')>Colaborador</option>
                                                            </select>
                                                            <label for="floatingSelect">Tipo de usuário</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer btn-group">
                                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                                <button type="submit" class="btn btn-dark">Salvar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('.modal-swal').click( function(){

            var plan = new TomSelect("#swal-plan", {
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