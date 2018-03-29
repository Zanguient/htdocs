@extends('master') 

@section('estilo')
	<link rel="stylesheet" href="{{ elixir('assets/css/index.css') }}">
@endsection

@section('titulo') 
Início
@endsection 

@section('conteudo') 
	
    @if ( $rev != null )
        <div 
            class="alert alert-warning version-of-system"
            style="
                position: fixed;
                right: 4px;
                bottom: -15px;
                z-index: 99999;
                background-color: rgb(0, 0, 0);
                border-color: rgb(255, 255, 255);
                color: rgb(255, 255, 255);
                padding: 1px 6px 0 6px;
                font-size: 10px;
            "
            > Vs.{{ date('Y.n.j-H:i',strtotime($rev->DATE)) }} / {{ $rev->REVISION }}</div>
    @endif
@endsection

@section('script')

	<script src="{{ elixir('assets/js/index.js') }}"></script>

@endsection

