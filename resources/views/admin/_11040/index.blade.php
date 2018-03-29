@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11040.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('admin/_11040.titulo') }}
@endsection

@section('conteudo')
    <div class="log-list">
        <ul>
        @foreach ( $arquivos as $arquivo )
        <li><a href="#" data-file-name="{{ $arquivo->FILE_NAME }}">{{ $arquivo->FILE_NAME }}</a></li>
        @endforeach
        </ul>
    </div>
    <div class="log-show">
        <textarea class="form-control normal-case" placeholder="Selecione um log..." id="log" rows="32" wrap="off" readonly></textarea> 
        <button type="button" class="btn btn-warning btn-circle bottom" data-hotkey="alt+a" title="Atualizar (Alt+A)"><span class="fa fa-refresh"></span></button>
    </div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11040.js') }}"></script>
@endsection