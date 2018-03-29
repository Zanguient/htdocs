@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/25900.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('opex/_25900.titulo') }}
@endsection

@section('conteudo')
    
   @include('opex._25900.include.padrao')
   @include('opex._25900.include.menu')
   @include('opex._25900.include.modal')
   
   @include('helper.include.view.progress')
   @include('helper.include.view.progress2')

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_25900.js') }}"></script>
@append
