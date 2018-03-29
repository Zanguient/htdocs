@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/25600.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('opex/_25600.titulo') }}
@endsection

@section('conteudo')

	    <ul class="list-inline acoes">
			<li><button type="submit" class="btn btn-success btn-gravar gravar-notas-indicador" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}"><span class="glyphicon glyphicon-ok"></span> {{ Lang::get('master.gravar') }}</button></li>
            <li><a href="{{ url('_25600') }}" class="btn btn-danger btn-cancelar" data-hotkey="f11"><span class="glyphicon glyphicon-ban-circle"></span> {{ Lang::get('master.cancelar') }}</a></li>
		</ul>

    <form class="form-inline info-container">
        <fieldset>
            <legend>Informações gerais</legend>

            @include('helper.include.view.ccustoIndicador',['CLASSE'  => 'atualiza-lista ccusto-indicador'])
            @include('helper.include.view.indicadores',['CLASSE'  => 'atualiza-lista'])
            @include('helper.include.view.turno',['CLASSE'  => 'atualiza-lista turno-indicador'])
            @include('helper.include.view.edit-data',['CLASSE'  => 'data-indicador'])

            <legend>Indicadores</legend>

            @include('helper.include.view.carregando-pagina',['CLASSE'  => ''])

                <div class="tabela-25600-3">
                    <table class="table table-bordered table-striped table-hover tabulado2 table-selectable">
                        <thead>
                            <tr>
                                <th class="coll-flag"></th>
                                <th class="coll-descricao">Descrição</th>
                                <th class="coll-sequncia">Peso</th>
                                <th class="coll-valor">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="corpo-tabela-3">

                            <tr><td cellspacing="4" colspan="5">
                                    <div class="tabela-vazia">
                                        Sem registros
                                    </div> 
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        
        @include('helper.include.view.status-plano-de-acao',['CLASSE'  => ''])

        <div id="modal-editar" class="modal fade modal-editar" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Plano de ação</h4>
                    </div>
                    <div class="modal-body" align="center">

                        <div class="modal-corpo">

                            <div class="carregando-pagina">
                                <div class="progress">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                    <span class="sr-only">0% Complete</span>
                                  </div>
                                </div>
                            </div>
                            
                            <div class="panel panel-primary orcamento area-plano-acao textArea-plano-acao">
                                
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn gravar btn-success adicionar-desc-plano" data-dismiss="modal" data-loading-text="Adicionando..."><span class="glyphicon glyphicon-ok"></span> Adicionar</button>
                        <button type="button" class="btn btn-default cancelar-validacao fechar-modal" data-dismiss="modal"><span class="glyphicon glyphicon-chevron-left"></span> Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="ativar-modal" data-toggle="modal" data-target="#modal-editar"></div>

        </fieldset>
    </fieldset>
    </form>

@endsection

@section('script')
    <!-- jQuery (required) & jQuery UI + theme (optional) -->
	<link href="{{ asset('assets/keyboard/docs/css/jquery-ui.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/keyboard/css/keyboard.css') }}" rel="stylesheet">
	<script src="{{ asset('assets/keyboard/js/jquery.keyboard.js') }}"></script>
    
    <script src="{{ elixir('assets/js/turno-filtro.js') }}"></script>
    <script src="{{ elixir('assets/js/_25600.js') }}"></script>
    <script src="{{ elixir('assets/js/_25200-filtro.js') }}"></script>
    <script src="{{ elixir('assets/js/_20030indicador-filtro.js') }}"></script>
    
@endsection