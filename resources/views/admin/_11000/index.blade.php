@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11000.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('admin/_11000.titulo') }}
@endsection

@section('conteudo')

<div class="container-env">
    <textarea class="form-control normal-case" placeholder="Selecione um log..." id="env" rows="32" wrap="off" spellcheck="false">{{ $arquivo }}</textarea> 
    <button type="button" id="gravar" class="btn btn-success btn-circle bottom" data-hotkey="alt+a" title="Atualizar (Alt+A)"><span class="glyphicon glyphicon-ok"></span></button>
</div>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11000.js') }}"></script>
@endsection
