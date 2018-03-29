@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/29010.css') }}" />
@endsection

@section('conteudo')

	<workflow-index-29010></workflow-index-29010>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_29010.js') }}"></script>
@endsection
