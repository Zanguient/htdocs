@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/erro.css') }}" />
@endsection

@section('titulo')
Ops...
@endsection

@section('topo')
    <h4 class="navbar-left">Página não encontrada.</h4>
@endsection

@section('conteudo')

        <div class="container">
            <div class="content">
                <div class="title">404 <div>:(</div></div>
                <div class="msg">Página não encontrada.</div>
            </div>
        </div>

@endsection
