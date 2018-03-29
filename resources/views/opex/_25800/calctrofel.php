@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/25800.css') }}" />
    @include('helper.include.css.clock')
@endsection

@section('titulo')
    {{ Lang::get('opex/_25800.edit-titulo') }}

@endsection

@section('conteudo')

   @include('opex._25800.include.menu')
   
@endsection

        
@section('script')
    @include('helper.include.js.clock')
    @include('helper.include.js.termometro')
    @include('helper.include.js.screen')
    <script src="{{ elixir('assets/js/_25800.js') }}"></script>
   
    <script src="http://192.168.0.179:8091/js/index.js"></script>
    
@append

