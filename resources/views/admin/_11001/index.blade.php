@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11001.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11001.css') }}" />
@endsection

@section('conteudo')

    <div class="log-list">
    <ul>
        
    @foreach ( $arquivos as $arquivo )
    @if ($arquivo->TIPO === 0)
        <li><a href="#" class="list-tipo" data-file-name="{{ $arquivo->FILE_NAME }}" data-file-dir="{{ $arquivo->FILE_DIR }}" data-tipo="{{ $arquivo->TIPO }}">{{ $arquivo->FILE_NAME }}</a></li>
    @else
        <li><a href="#" data-file-name="{{ $arquivo->FILE_NAME }}" data-file-dir="{{ $arquivo->FILE_DIR }}" data-tipo="{{ $arquivo->TIPO }}">{{ $arquivo->FILE_NAME }}</a></li>
    @endif
    @endforeach
    
    </ul>
    </div>
    <div class="log-show">
        <textarea class="form-control normal-case" placeholder="Selecione um log..." id="log" rows="32" wrap="off" readonly></textarea> 
        <button type="button" class="btn btn-warning btn-circle bottom" data-hotkey="alt+a" title="Atualizar (Alt+A)"><span class="fa fa-refresh"></span></button>
    </div>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11001.js') }}"></script>
@endsection
