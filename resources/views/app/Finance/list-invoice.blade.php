@extends('app.layout')
@section('title') Faturas @endsection
@section('conteudo')
    <div class="pagetitle">
        <h1>Faturas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">Área de Trabalho</a></li>
                <li class="breadcrumb-item active">Faturas</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        
        <div class="col-12 mb-3">
            <div class="btn-group" role="group">
                <button type="button" title="Filtros" class="btn btn-dark modal-swal" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-filter-circle"></i> Filtros
                </button>                   
                <a href="{{ route('invoices', request()->query()) }}" title="Recarregar" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>                
            </div>

            <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ route('invoices') }}" method="GET" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pesquisar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @if(Auth::user()->type == 'admin')
                                    <div class="col-12 col-md-12 col-lg-12 mb-2">
                                        <select name="user_id" id="users">
                                            <option selected value=" ">Usuário:</option>
                                            @foreach (Auth::user()->myUsers() as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-12 col-md-12 col-lg-12 mb-2">
                                    <select name="plan_id" id="plans">
                                        <option selected value=" ">Planos:</option>
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 mb-2">
                                    <select name="status_payment" id="status">
                                        <option selected value=" ">Status:</option>
                                        <option value="00">Pendente</option>
                                        <option value="1">Aprovado</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="token_payment" class="form-control" id="token_payment" placeholder="Valor Mín:">
                                        <label for="token_payment">Código da Fatura</label>
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
                            <th scope="col" class="text-center">Vencimento</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <th>{{ $invoice->id }}</th>
                                <td>
                                    {{ $invoice->name }}<br>
                                    <span class="badge bg-dark">{{ $invoice->description }}</span>
                                </td>
                                <td>R$ {{ number_format($invoice->value, 2, ',', '.') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($invoice->due_date_payment)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $invoice->statusLabel() }}</td>
                                <td class="text-center">
                                    <form action="{{ route('delete-invoice') }}" method="POST" class="btn-group delete">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $invoice->id }}">
                                        @if (Auth::user()->type == 'admin')
                                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            @if($invoice->status_payment != 1)
                                                <a href="{{ route('confirm-invoice', ['id' => $invoice->id]) }}" class="btn btn-dark"><i class="bi bi-check-circle"></i> Confirmar</a>
                                            @endif
                                        @endif
                                        <a href="{{ $invoice->url_payment }}" target="_blank" class="btn btn-outline-success"><i class="bi bi-share"></i> Acessar</a>
                                    </form> 
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('.modal-swal').click( function() {

            var users = new TomSelect("#status", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1,
            });

            var plans = new TomSelect("#plans", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1,
            });

            var users = new TomSelect("#users", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1,
            });
            
        });
    </script>
@endsection