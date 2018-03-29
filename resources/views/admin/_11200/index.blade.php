@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11200.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11200.css') }}" />
@endsection

@section('conteudo')


<center>Em desenvolvimento</center>
@php /*
<div ng-controller="Ctrl as vm" ng-cloak>

  
    
    @include('admin._11200.index.panel-destaque')

    

    <fieldset class="tab-container" style="margin-top: 10px;">

        <ul id="tab" class="nav nav-tabs" role="tablist"> 

            <li>
                <a href="#tab-container-liberar" id="tab-liberar" role="tab" data-toggle="tab" aria-controls="tab-container-consulta" aria-expanded="false">
                    Liberação de Talões
                </a>
            </li> 
     
            <li class="active">
                <a href="#tab-container-consulta" id="tab-consulta" role="tab" data-toggle="tab" aria-controls="tab-container-consulta" aria-expanded="true">
                    Consulta
                </a>
            </li>
        </ul>

        <div id="tab-content" class="tab-content">
            
            @include('admin._11200.index.tab.tab-container-liberar')
            @include('admin._11200.index.tab.tab-container-consulta')
        </div>

        <div class="legenda-container" style="display: inline-block;">
            <label class="legenda-label" style="float: left; margin-bottom: 0; font-size: 11px;">Legenda de cores do status</label>
            <ul class="legenda talao" style="clear: left; margin-top: 0;">
                <li>
                    <div class="cor-legenda btn-danger"></div>
                    <div class="texto-legenda">Não Cortado  |</div>
                </li>
                <li>
                    <div class="cor-legenda btn-success"></div>
                    <div class="texto-legenda">Cortado |</div>
                </li>
                <li>
                    <div class="cor-legenda btn-primary"></div>
                    <div class="texto-legenda">Liberado</div>
                </li>
            </ul>
        </div>         
     </fieldset>
    

    
    @include('admin._11200.index.modal-operador')
    @include('admin._11200.index.modal-gp')
</div>

@php */
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11200.js') }}"></script>
@append
