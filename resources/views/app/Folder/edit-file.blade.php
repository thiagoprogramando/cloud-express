@extends('app.layout')
@section('title') {{ $file->name }} @endsection
@section('conteudo')

    <div class="pagetitle">
        <h1>{{ $file->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app') }}">Área de Trabalho</a></li>
                <li class="breadcrumb-item">{{ $folder->name }}</li>
                <li class="breadcrumb-item active">{{ $file->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 col-sm-12 col-md-7 col-lg-7">
            <div class="card p-5">
                <form method="POST" class="row" id="file">
                    @csrf
                    <input type="hidden" name="id" value="{{ $file->id }}">
                    <input type="hidden" name="desktop" value="true">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" value="{{ $file->name }}">
                            <label for="name">Nome</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <textarea type="text" name="description" class="form-control" id="description" placeholder="Descrição:" style="height: 100px;">{{ $file->description }}</textarea>
                            <label for="description">Descrição</label>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="extension" placeholder="Extensão:" value="{{ $file->extension }}" disabled>
                            <label for="extension">Extensão</label>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="space_disk" placeholder="Espaço Utilizado(MB):" value="{{ $file->space_disk }}" disabled>
                            <label for="space_disk">Espaço Utilizado (MB)</label>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="created_at" placeholder="Cadastrado em:" value="{{ $file->created_at->format('d/m/Y') }}" disabled>
                            <label for="created_at">Cadastrado em:</label>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="updated_at" placeholder="Última alteração:" value="{{ $file->updated_at->format('d/m/Y') }}" disabled>
                            <label for="updated_at">Última alteração:</label>
                        </div>
                    </div>                    
                    <div class="col-12 col-sm-12 offset-md-2 col-md-8 offset-lg-2 col-lg-8 btn-group">
                        <button type="submit" name="action" value="delete" class="btn btn-outline-danger">Excluir</button>
                        <button type="submit" name="action" value="save" class="btn btn-dark">Salvar</button>
                    </div>                   
                </form>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-5 col-lg-5">
            <div class="card">
                @switch($file->extension)
                    @case('zip')
                        <a href="{{ route('file', ['id' => $file->id, 'donwload' => true]) }}" class="btn btn-dark btn-block m-3">Baixar Arquivo</a>
                        @break
                    @case('doc')
                        <a href="{{ route('file', ['id' => $file->id, 'donwload' => true]) }}" class="btn btn-dark btn-block m-3">Baixar Arquivo</a>
                        @break
                    @case('docx')
                        <a href="{{ route('file', ['id' => $file->id, 'donwload' => true]) }}" class="btn btn-dark btn-block m-3">Baixar Arquivo</a>
                        @break
                    @case('xls')
                        <a href="{{ route('file', ['id' => $file->id, 'donwload' => true]) }}" class="btn btn-dark btn-block m-3">Baixar Arquivo</a>
                        @break
                    @case('xlsx')
                        <a href="{{ route('file', ['id' => $file->id, 'donwload' => true]) }}" class="btn btn-dark btn-block m-3">Baixar Arquivo</a>
                        @break
                    @case('pdf')
                        <embed src="{{ asset('storage/' . $file->file) }}" type="application/pdf" width="100%" height="600px" />
                        <a href="{{ route('file', ['id' => $file->id, 'download' => true]) }}" class="btn btn-dark btn-block m-3">Baixar Arquivo</a>
                        @break
                    @default
                        <img src="{{ asset('storage/' . $file->file) }}" alt="{{ $file->name }}" class="img-fluid">
                        <a href="{{ route('file', ['id' => $file->id, 'donwload' => true]) }}" class="btn btn-dark btn-block m-3">Baixar Arquivo</a>
                        @break
                @endswitch
            </div>
        </div>
    </div>

    <script>
        document.getElementById('file').addEventListener('submit', function(event) {
            const action = event.submitter.value;
            
            if (action === 'delete') {
                this.action = '{{ route('delete-file') }}';
            } else {
                this.action = '{{ route('update-file') }}';
            }
        });
    </script>
@endsection