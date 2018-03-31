@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/erro.css') }}" />
@endsection

@section('titulo')
Ops...
@endsection

@section('topo')
    <h4 class="navbar-left">Ocorreu algum problema...</h4>
@endsection

@section('conteudo')

        <div class="container">
            <div class="content">
                <div class="title">Erro <div>:(</div></div>
                <div class="msg">Contacte o administrador do sistema.</div>
                <div class="msg-erro">{{ $erro }}</div>
            </div>
        </div>

@endsection
