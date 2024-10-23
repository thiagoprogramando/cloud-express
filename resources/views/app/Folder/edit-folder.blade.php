@extends('app.layout')
@section('title') {{ $folder->name }} @endsection
@section('conteudo')

    <div class="pagetitle">
        <h1>{{ $folder->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">Área de Trabalho</a></li>
                <li class="breadcrumb-item active">{{ $folder->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card p-5">
                <form method="POST" class="row" id="folder">
                    @csrf
                    <input type="hidden" name="id" value="{{ $folder->id }}">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" value="{{ $folder->name }}">
                            <label for="name">Nome</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <textarea type="text" name="description" class="form-control" id="description" placeholder="Descrição:" style="height: 100px;">{{ $folder->name }}</textarea>
                            <label for="description">Descrição</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <input type="text" name="password" class="form-control" id="password" placeholder="Senha">
                            <label for="password">Senha</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12 mb-2">
                        <select name="user_access[]" id="shareSelect" multiple>
                            <option selected value="">Compartilhar com:</option>
                            @foreach ($usersCompany as $user)
                                @php $isSelected = $usersFolder->contains('user_id', $user->id); @endphp
                                <option value="{{ $user->id }}" {{ $isSelected ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 offset-md-4 col-md-4 offset-lg-4 col-lg-4 btn-group">
                        <button type="submit" name="action" value="delete" class="btn btn-outline-danger">Excluir</button>
                        <button type="submit" name="action" value="save" class="btn btn-dark">Salvar</button>
                    </div>                   
                </form>
            </div>
        </div>
    </div>

    <script>
        var plan = new TomSelect("#shareSelect", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            maxItems: 1000,
        });

        document.getElementById('folder').addEventListener('submit', function(event) {
            const action = event.submitter.value;
            
            if (action === 'delete') {
                this.action = '{{ route('delete-folder') }}';
            } else {
                this.action = '{{ route('update-folder') }}';
            }
        });
    </script>
@endsection