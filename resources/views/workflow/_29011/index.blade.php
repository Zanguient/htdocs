@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/29011.css') }}">
@endsection

@section('conteudo')
	
	<index-29011></index-29011>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_29011.js') }}"></script>
@append
