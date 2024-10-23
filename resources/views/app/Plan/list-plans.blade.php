@extends('app.layout')
@section('title') Gestão de Planos @endsection
@section('conteudo')
    <div class="pagetitle">
        <h1>Gestão de Planos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">Área de Trabalho</a></li>
                <li class="breadcrumb-item active">Gestão de Planos</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        
        <div class="col-12 mb-3">
            <div class="btn-group" role="group">
                <button type="button" title="Filtros" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bx bx-cart"></i> Novo
                </button>
                <button type="button" title="Filtros" class="btn btn-outline-dark modal-swal" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-filter-circle"></i> Filtros
                </button>
                {{-- <a href="{{ route('plan-excel', request()->query()) }}" class="btn btn-outline-dark" title="Excel">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </a>                    
                <a href="{{ route('usuarios') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a> --}}
            </div>

            <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ route('create-plan') }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Cadastro de Planos</h5>
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
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <textarea name="description" class="form-control" id="description" placeholder="Descrição"></textarea>
                                        <label for="description">Descrição</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="value" class="form-control" id="value" placeholder="Valor:">
                                        <label for="value">Valor</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-floating mb-2">
                                        <input type="number" name="space_disk" class="form-control" id="space_disk" placeholder="Espaço (GB):">
                                        <label for="space_disk">Espaço (GB)</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-floating mb-2">
                                        <input type="number" name="space_user" class="form-control" id="space_user" placeholder="N° Usuários:">
                                        <label for="space_user">Qtd. Usuários</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 mb-2">
                                    <div class="form-floating">
                                        <select name="validate" class="form-select" id="floatingValidate">
                                            <option disabled>Validade:</option>
                                            <option value="month">Mês</option>
                                            <option value="year">Ano</option>
                                            <option value="lifetime">Vitalício</option>
                                        </select>
                                        <label for="floatingValidate">Tempo para renovação</label>
                                    </div>
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
                    <form action="{{ route('list-plans') }}" method="GET" class="modal-content">
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
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="description" class="form-control" id="description" placeholder="Descrição:">
                                        <label for="description">Descrição</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="min_value" class="form-control" id="min_value" placeholder="Valor Mín:">
                                        <label for="min_value">Valor Mín</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="max_value" class="form-control" id="max_value" placeholder="Valor Máx:">
                                        <label for="max_value">Valor Máx</label>
                                    </div>
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
                            <th scope="col">Plano</th>
                            <th scope="col">Valor</th>
                            <th scope="col" class="text-center">Espaço (GB)</th>
                            <th scope="col" class="text-center">Usuários (QTD)</th>
                            <th scope="col" class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plans as $plan)
                            <tr>
                                <th>{{ $plan->id }}</th>
                                <td>
                                    {{ $plan->name }}<br>
                                    <span class="badge bg-dark">{{ $plan->description }}</span>
                                </td>
                                <td>R$ {{ number_format($plan->value, 2, ',', '.') }}</td>
                                <td class="text-center">{{ $plan->space_disk }}</td>
                                <td class="text-center">{{ $plan->space_user }}</td>
                                <td class="text-center">
                                    <form action="{{ route('delete-plan') }}" method="POST" class="btn-group delete">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $plan->id }}">
                                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#updateModal{{ $plan->id }}"><i class="ri-edit-line"></i></button>
                                    </form> 
                                </td>
                            </tr>

                            <div class="modal fade" id="updateModal{{ $plan->id }}" tabindex="-1" aria-hidden="true" style="display: none;">
                                <form action="{{ route('update-plan') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $plan->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Atualizar Plano: {{ $plan->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-floating mb-2">
                                                            <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" value="{{ $plan->name }}">
                                                            <label for="name">Nome</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-floating mb-2">
                                                            <textarea name="description" class="form-control" id="description" placeholder="Descrição">{{ $plan->description }}</textarea>
                                                            <label for="description">Descrição</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                        <div class="form-floating mb-2">
                                                            <input type="text" name="value" class="form-control" id="value" placeholder="Valor:" value="{{ $plan->value }}">
                                                            <label for="value">Valor</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                        <div class="form-floating mb-2">
                                                            <input type="number" name="space_disk" class="form-control" id="space_disk" placeholder="Espaço (GB):" value="{{ $plan->space_disk }}">
                                                            <label for="space_disk">Espaço (GB)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                        <div class="form-floating mb-2">
                                                            <input type="number" name="space_user" class="form-control" id="space_user" placeholder="N° Usuários:" value="{{ $plan->space_user }}">
                                                            <label for="space_user">Qtd. Usuários</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-12 col-lg-12 mb-2">
                                                        <div class="form-floating">
                                                            <select name="validate" class="form-select" id="floatingValidate">
                                                                <option disabled>Validade:</option>
                                                                <option value="month" @selected($plan->validate == 'month')>Mês</option>
                                                                <option value="year" @selected($plan->validate == 'year')>Ano</option>
                                                                <option value="lifetime" @selected($plan->validate == 'lifetime')>Vitalício</option>
                                                            </select>
                                                            <label for="floatingValidate">Tempo para renovação</label>
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
@endsection