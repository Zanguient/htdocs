@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/29013.css') }}">
@endsection

@section('conteudo')
	
	<index-29013></index-29013>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_29013.js') }}"></script>
@append
