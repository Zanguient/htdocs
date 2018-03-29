@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/25600.css') }}" />
@endsection

@section('titulo')
{{ Lang::get('opex/_25600.titulo') }}
@endsection

@section('conteudo')
<ul class="list-inline acoes">
    <li>
        <a href="{{ $permissaoMenu->INCLUIR ? url('/_25600/create') : '#' }}" class="btn btn-primary btn-incluir" data-hotkey="f6">
			<span class="glyphicon glyphicon-plus"></span>
			 {{ Lang::get('master.incluir') }}
		</a>
    </li>   
</ul>

<form class="form-inline info-container">
    <fieldset>
        <legend>Informações gerais</legend>
        
        @include('helper.include.view.ccustoIndicador',['CLASSE'  => 'Atualizar-Tela'])
        @include('helper.include.view.edit-data',['CLASSE'  => 'data-filtro Atualizar-Tela'])
        @include('helper.include.view.botao-filtrar',['CLASSE'  => 'filtrar-indicador'])

        @include('helper.include.view.status-plano-de-acao',['CLASSE'  => ''])

        <legend>Indicadores</legend>
        
        @include('helper.include.view.carregando-pagina',['CLASSE'  => ''])

            <div class="tabela-25600-1">
            <table class="table table-bordered table-striped table-hover tabulado1 table-selectable">
                <thead>
                    <tr>
                        <th class="coll-id">ID</th>
                        <th class="coll-descricao">Descrição</th>
                        <th class="coll-data">Data/Hora</th>
                        <th class="coll-turno">Turno</th>
                    </tr>
                </thead>
                <tbody class="corpo-tabela-1">
                    
                    <tr><td cellspacing="4" colspan="4">
                            <div class="tabela-vazia">
                                Sem registros
                            </div> 
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            </div>
  
            <div class="tabela-25600-2">
            <table class="table table-bordered table-striped table-hover tabulado2 table-selectable">
                <thead>
                    <tr>
                        <th class="coll-flag"></th>
                        <th class="coll-sequncia">Seq.</th>
                        <th class="coll-descricao">Descrição</th>
                        <th class="coll-valor">Valor</th>
                        <th class="coll-editar"></th>
                    </tr>
                </thead>
                <tbody class="corpo-tabela-2">

                    <tr><td cellspacing="4" colspan="5">
                            <div class="tabela-vazia">
                                Sem registros
                            </div> 
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            </div>
 
        <div id="modal-editar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">

		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Alterar</h4>
                    <button type="button" class="btn btn-primary btn-more-info"><span class="glyphicon glyphicon-info-sign"></span></button>
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
                        
                    </div>

				</div>
				<div class="modal-footer">
                    <button type="button" class="btn gravar btn-success editar-item alterar-nota" data-loading-text="Gravando..."><span class="glyphicon glyphicon-ok"></span> Gravar</button>
                    <button type="button" class="btn btn-default fechar-modal" data-dismiss="modal"><span class="glyphicon glyphicon-chevron-left"></span> Cancelar</button>
                </div>
			</div>
		</div>
	</div>
        
    </fieldset>
</fieldset>
</form>
@endsection

@section('script')

	<link href="{{ asset('assets/keyboard/docs/css/jquery-ui.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/keyboard/css/keyboard.css') }}" rel="stylesheet">
	<script src="{{ asset('assets/keyboard/js/jquery.keyboard.js') }}"></script>
   
    <script src="{{ elixir('assets/js/_25200-filtro.js') }}"></script>
    <script src="{{ elixir('assets/js/_20030indicador-filtro.js') }}"></script>
    <script src="{{ elixir('assets/js/turno-filtro.js') }}"></script>
    <script src="{{ elixir('assets/js/_25600.js') }}"></script>
    
    
@endsection
