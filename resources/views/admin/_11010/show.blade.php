@extends('master')

@section('titulo')
{{ Lang::get('admin/_11010.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/11010.css') }}" />
@endsection

@section('conteudo')

	<form class="form-inline popup-form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
        @include('admin._11010.show.body')
	</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/_11010.js') }}"></script>
@append
