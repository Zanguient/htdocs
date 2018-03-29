@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/29012.css') }}">
@endsection

@section('conteudo')

	<index-29012></index-29012>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_29012.js') }}"></script>
@append
