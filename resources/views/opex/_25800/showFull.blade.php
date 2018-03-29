@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/25800.css') }}" />
    <link rel="stylesheet" href="{{ elixir('assets/css/25800full.css') }}" />
    @include('helper.include.css.clock')
@endsection

@section('titulo')
    {{ Lang::get('opex/_25800.edit-titulo') }}
@endsection

@section('topo')
    <h4 class="navbar-left">{{ Lang::get('opex/_25800.edit-titulo') }}</h4>	
@endsection 

@section('conteudo')
      
    @include('opex._25800.include.padrao')
        
@endsection

@section('script')
	<script src="{{ elixir('assets/js/_25800.js') }}"></script>
    @include('helper.include.js.clock')
@endsection
