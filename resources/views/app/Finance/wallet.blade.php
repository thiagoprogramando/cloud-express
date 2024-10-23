@extends('app.layout')
@section('title') Carteira @endsection
@section('conteudo')
    <div class="pagetitle">
        <h1>Carteira</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">Área de Trabalho</a></li>
                <li class="breadcrumb-item active">Carteira</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
            <div class="card bg-dark">
                <div class="card-body">
                    <h5 class="card-title text-white">SALDO EM CONTA</h5>
                    <h4 class="text-white">R$ {{ number_format($balance, 2, ',', '.') }}</h4>   
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
            <div class="btn-group" role="group">
                <button type="button" title="Filtros" class="btn btn-dark modal-swal" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-filter-circle"></i> Filtros
                </button>
                <button type="button" title="Retirar Valor" class="btn btn-outline-dark modal-swal" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                    <i class="bi bi-reply-all"></i> Retirar Valor
                </button>                  
                <a href="{{ route('wallet', request()->query()) }}" title="Recarregar" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>                
            </div>

            <div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ route('withdraw-send') }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Dados da transação:</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span class="badge bg-danger mb-3 w-100">Confira os dados antes de confirmar a transação</span>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="value" class="form-control" id="value" placeholder="Valor" oninput="mascaraReal(this)" required>
                                        <label for="value">Valor</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6 mb-1">
                                    <div class="form-floating">
                                        <select name="type" class="form-select" id="floatingType" required>
                                            <option selected="" value="">Tipo:</option>
                                            <option value="CPF">CPF</option>
                                            <option value="CNPJ">CNPJ</option>
                                            <option value="EMAIL">EMAIL</option>
                                            <option value="PHONE">Telefone:</option>
                                        </select>
                                        <label for="floatingType">Tipo da chave</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-12 mb-1">
                                    <div class="form-floating">
                                        <input type="text" name="key" class="form-control" id="floatingKey" placeholder="Informe a Chave Pix:" required>
                                        <label for="floatingKey">Chave Pix:</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Confirme sua senha:" required>
                                        <label for="password">Confirme sua senha:</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer btn-group">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-dark">Retirar valor</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ route('wallet') }}" method="GET" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pesquisar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating mb-2">
                                        <input type="date" name="startDate" class="form-control" id="startDate" placeholder="Data Inicial">
                                        <label for="startDate">Data Inicial</label>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating mb-2">
                                        <input type="date" name="finishDate" class="form-control" id="finishDate" placeholder="Data Final">
                                        <label for="finishDate">Data Final</label>
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
                <div class="table-responsive">
                    <table class="table table-responsive table-hover" id="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Data</th>
                                <th>Descrição</th>  
                                <th class="text-center">Valor</th>
                                <th class="text-center">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($extracts as $extract)
                                <tr>
                                    <td>
                                        <a href="{{ $extract['paymentId'] }}">{{ $extract['id'] }}</a>
                                    </td>
                                    <td>
                                        @if($extract['value'] < 0)
                                            Saída
                                        @else
                                            Entrada
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($extract['date'])->format('d/m/Y') }}</td>
                                    <td>{{ $extract['description'] }}</td>
                                    <td class="text-justify">R$ {{ number_format($extract['value'], 2, ',', '.') }}</td>
                                    <td class="text-center">R$ {{ number_format($extract['balance'], 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection