@extends('app.layout')
@section('title') Perfil @endsection
@section('conteudo')
    <div class="pagetitle">
        <h1>Perfil</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">Área de Trabalho</a></li>
                <li class="breadcrumb-item active">Perfil</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <form action="{{ route('update-user') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                            <input type="file" id="photoInput" name="photo" style="display: none;" accept="image/*" onchange="this.form.submit()">
                            <label for="photoInput">
                                <img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('template/img/components/profile.png') }}" alt="{{ Auth::user()->name }}" class="rounded-circle">
                            </label>
                        </form>
                        <h2>{{ Auth::user()->name }}</h2>
                        <h3>{{ Auth::user()->typeLabel() }}</h3>
                        {{-- <div class="social-links mt-2">
                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div> --}}
                    </div>
                </div>
            </div>
  
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview" aria-selected="true" role="tab">Perfil</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit" aria-selected="false" tabindex="-1" role="tab">Log de Atividades</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password" aria-selected="false" tabindex="-1" role="tab">Alteração de senha</button>
                            </li>
                        </ul>

                        <div class="tab-content pt-2">
  
                            <div class="tab-pane fade show active profile-overview" id="profile-overview" role="tabpanel">
                                <h5 class="card-title">Dados</h5>
                                
                                <form action="{{ route('update-user') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 label">Nome</div>
                                        <div class="col-lg-10 col-md-10">
                                            <input type="text" name="name" class="form-control" placeholder="Nome:" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 label">Email</div>
                                        <div class="col-lg-10 col-md-10">
                                            <input type="email" name="email" class="form-control"placeholder="Email:" value="{{ Auth::user()->email }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 label">CPF ou CNPJ</div>
                                        <div class="col-lg-10 col-md-10">
                                            <input type="number" name="cpfcnpj" class="form-control" placeholder="CPF ou CNPJ:" value="{{ Auth::user()->cpfcnpj }}">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-block btn-dark">Atualizar</button>
                                </form>
                            </div>
    
                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit" role="tabpanel">
    
                            </div>
    
                            <div class="tab-pane fade pt-3" id="profile-change-password" role="tabpanel">
                                <form action="{{ route('update-user') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                                    <div class="row mb-2">
                                        <div class="col-lg-3 col-md-3 label">Nova senha</div>
                                        <div class="col-lg-9 col-md-9">
                                            <input type="text" name="password" class="form-control" placeholder="Nova senha:">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-3 col-md-3 label">Confirme a senha</div>
                                        <div class="col-lg-9 col-md-9">
                                            <input type="text" name="confirmpassword" class="form-control" placeholder="Confirme a senha:">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-block btn-dark">Atualizar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection