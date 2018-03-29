@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11070.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11070.css') }}" />
@endsection

@section('conteudo')
    @php /* phpinfo() */
    
	@php /*
    <my-app>Loading...</my-app>
    @php */
@endsection

@section('script')
    @php /*
	<script src="es6-shim/es6-shim.min.js"></script>
	<script src="zone.js/dist/zone.js"></script>
	<script src="reflect-metadata/Reflect.js"></script>
	<script src="systemjs/dist/system.src.js"></script>
	<script src="systemjs.config.js"></script>
	
	<script>
		System.import('js/admin/11070/main.js').catch(function(err){ console.error(err); });
	</script>
	
    <script src="{{ elixir('assets/js/_11070.js') }}"></script>
    @php */
@endsection
