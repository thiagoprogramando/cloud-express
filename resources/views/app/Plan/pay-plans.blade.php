@extends('app.layout')
@section('title') Planos @endsection
@section('conteudo')
    <div class="pagetitle">
        <h1>Planos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">Área de Trabalho</a></li>
                <li class="breadcrumb-item active">Planos</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        @foreach ($plans as $plan)
            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                <div class="card card-price m-3 mb-4 box-shadow">
                    <div class="card-header text-center">
                        <h4 class="my-0 font-weight-normal">{{ $plan->name }}</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title text-center">R$ {{ number_format($plan->value, 2, ',', '.') }} <small class="text-muted">/ {{ $plan->validateLabel() }}</small></h1>
                        <p class="text-justify mb-5">
                            {!! strlen($plan->description) > 150 ? substr($plan->description, 0, 150) . '...' : $plan->description !!}
                        </p>
                        @if(Auth::user()->plan_id != $plan->id)
                            <form action="{{ route('pay-plan') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <button type="submit" class="btn btn-outline-dark mt-2 w-100">Comprar Plano</button>
                            </form>
                        @endif                                    
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection