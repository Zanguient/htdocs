@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11090.titulo') }}
@endsection

@section('estilo')
	<link rel="stylesheet" href="{{ elixir('assets/css/11090.css') }}" />.
@endsection

@section('conteudo')

	<div class="conteiner-tela" ng-controller="Ctrl as vm" ng-cloak>
		Usuários:

		<div ng-repeat="online in vm.ONLINE_CHAT track by $index">
			@{{online}} <button ng-click="vm.Acoes.SEND_CHAT(online.ID)" type="button">Enviar OI</button>
		</div>
		
		<br>
		<br>
		<br>
		<br>
		
		<div>
			Mensagem:
			<div>
				@{{vm.MENSAGE}}
			</div>
		</div>
		
	</div>


@php $arr = array(1, 2, 3, 4);
@php print_r($arr);
@php echo "<br>";

@php foreach ($arr as $key => $value) {
@php    echo "{$key} => {$value}<br>";
@php }

@php echo "<br>";
@php echo "<br>";
@php echo "<br>";

@php $arr = array(1, 2, 3, 4);
@php foreach ($arr as &$value) {
@php     print_r($value);
@php     echo " ";
@php     $value = $value * 2;
@php     print_r($value);
@php     echo " ";
@php echo "<br>";
@php }
@php     print_r($arr);


@php echo "<br>";
@php echo "<br>";
@php echo "<br>";

@php foreach ($arr as $key => $value) {
@php     echo "{$key} => {$value} ";
@php     print_r($arr);
@php     echo "<br>";
@php }

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11090.js') }}"></script>
@endsection
