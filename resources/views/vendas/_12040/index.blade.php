@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/12040.css') }}" />
@append

@section('conteudo')
	
	<div class="visualizar-arquivo">

		<a class="btn btn-default download-arquivo" href="" download="" data-hotkey="alt+b">
			<span class="glyphicon glyphicon-download"></span>
			Baixar
		</a>
		
		<button type="button" class="btn btn-default esconder-arquivo" data-hotkey="f11" >
			<span class="glyphicon glyphicon-chevron-left"></span>
			Voltar
		</button>

		<object style="width: 100%; height: 100%;" data="" ></object>

	</div>

	@php $representante = isset($_GET['REPRESENTANTE']) ? $_GET['REPRESENTANTE'] : 0
	@php $cliente       = isset($_GET['CLEINTE']) ? $_GET['CLEINTE'] : 0
	@php $pedido        = isset($_GET['PEDIDO']) ? $_GET['PEDIDO'] : 0

	<input type="hidden" class="_representante" value="{{$representante}}">
	<input type="hidden" class="_cliente"       value="{{$cliente}}">
	<input type="hidden" class="_pedido"         value="{{$pedido}}">

	<pedido-index-12040></pedido-index-12040>

	<chat></chat>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_12040.js') }}"></script>
@append