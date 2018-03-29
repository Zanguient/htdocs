	@extends('helper.include.view.modal', ['id' => 'modal-blok', 'class_size' => 'modal-lg'])

	@section('modal-header-left')

	<h4 class="modal-title">
		Blok - @{{vm.modal.iten.ID}}
	</h4>

	@overwrite

	@section('modal-header-right')
		@php /*
		
		<button ng-if="vm.status_tela == 0" ng-click="vm.Acoes.gravarCaso()" ng-disabled="vm.btnGravar.disabled == true" type="submit" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando...">
			<span class="glyphicon glyphicon-ok"></span> 
			Gravar
		</button>

		<button  ng-click="vm.Acoes.Canselar()" ng-if="vm.status_tela == 0"  type="button" class="btn btn-danger btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="f11">
		  <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
		</button>

		<button ng-disabled="(vm.user.FINALIZAR == 0 && vm.user.CODIGO != vm.CasoIten.USUARIO_ID) || vm.CasoIten.FECHADO == 1" ng-if="vm.status_tela == 1" ng-click="vm.Acoes.finalizarCaso()" ng-disabled="vm.btnAlterar.disabled == true" type="submit" class="btn btn-warning" data-hotkey="f10">
			<span class="glyphicon glyphicon-saved"></span> 
			Finalizar
		</button>

		<button ng-disabled="vm.user.ALTERAR == 0 && vm.user.CODIGO != vm.CasoIten.USUARIO_ID" ng-if="vm.status_tela == 1" ng-click="vm.Acoes.alterarCaso({{$caso_id}})" ng-disabled="vm.btnAlterar.disabled == true" type="submit" class="btn btn-primary" data-hotkey="f9">
			<span class="glyphicon glyphicon-edit"></span> 
			Alterar
		</button>


		<button ng-disabled="vm.user.EXCLUIR == 0" ng-if="vm.status_tela == 1" ng-click="vm.Acoes.excluirCaso({{$caso_id}})" ng-disabled="vm.btnExcluir.disabled == true" type="submit" class="btn btn-danger" data-hotkey="f12">
			<span class="glyphicon glyphicon-trash"></span> 
			Excluir
		</button>


		<button  ng-if="vm.status_tela == 1" ng-click="vm.Acoes.Canselar()"  type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
		  <span class="glyphicon glyphicon-chevron-left"></span> Voltar
		</button>

		<button ng-if="vm.status_tela == 2 || vm.status_tela == 3" ng-click="vm.Acoes.gravarCaso()" ng-disabled="vm.btnGravar.disabled == true" type="submit" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando...">
			<span class="glyphicon glyphicon-ok"></span> 
			Gravar
		</button>

		<a ng-if="vm.status_tela == 2" ng-click="vm.Acoes.canselarAlteracaoCaso()"  class="btn btn-danger btn-cancelar" data-hotkey="f11">
			<span class="glyphicon glyphicon-ban-circle"></span> 
			Cancelar
		</a>
		@php */

		<button  ng-if="vm.modal.acao == 2" ng-click="vm.Acoes.Alterar()" type="submit" class="btn btn-primary" data-hotkey="f9">
			<span class="glyphicon glyphicon-edit"></span> 
			Alterar
		</button>

		<button  ng-if="vm.modal.acao == 2" ng-click="vm.Acoes.excluir()" ng-disabled="vm.btnExcluir.disabled == true" type="submit" class="btn btn-danger" data-hotkey="f12">
			<span class="glyphicon glyphicon-trash"></span> 
			Excluir
		</button>

		<button ng-if="vm.modal.acao == 1 || vm.modal.acao == 3" ng-click="vm.Acoes.gravar()" type="submit" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando...">
			<span class="glyphicon glyphicon-ok"></span> 
			Gravar
		</button>

		<a ng-if="vm.modal.acao == 1" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="esc">
			<span class="glyphicon glyphicon-ban-circle"></span> 
			Cancelar
		</a>

		<a ng-if="vm.modal.acao == 3" ng-click="vm.Acoes.AlterarNO()" class="btn btn-danger btn-cancelar">
			<span class="glyphicon glyphicon-ban-circle"></span> 
			Cancelar
		</a>

		<button ng-if="vm.modal.acao == 2" ng-click="vm.Acoes.Canselar()"  type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
		  <span class="glyphicon glyphicon-chevron-left"></span> Voltar
		</button>

	@overwrite

	@section('modal-body')

	<ul id="tab" class="nav nav-tabs" role="tablist"> 
        <li role="presentation" class="active tab-detalhamento">
            <a href="#tab-user-container" id="tab-user" role="tab" data-toggle="tab" aria-controls="tab-user-container" aria-expanded="false">
                Usuári<span style="text-decoration: underline;">o</span>
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" ng-if="vm.modal.acao == 2">
            <a ng-click="vm.Acoes.atualizarURL()" href="#tab-url-container" id="tab-url" role="tab" data-toggle="tab" aria-controls="tab-url-container" aria-expanded="false">
                UR<span style="text-decoration: underline;">L</span>
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" ng-if="vm.modal.acao == 2">
            <a ng-click="vm.Acoes.atualizarJANELA()" href="#tab-janelas-container" id="tab-janelas" role="tab" data-toggle="tab" aria-controls="tab-janelas-container" aria-expanded="false">
                JANEL<span style="text-decoration: underline;">A</span>
            </a>
        </li>
    </ul>

	<div role="tabpanel" class="tab-pane fade active in" id="tab-user-container" aria-labelledby="tab-user">
		<div class="itens-inputs ng-scope">
			<div class="form-group">
				<label>Nome:</label>
				<input ng-model="vm.modal.iten.NOME" ng-disabled="vm.modal.acao == 2" type="text" class="form-control input-maior" value="" autocomplete="off">
			</div>
		</div>

		<div class="itens-inputs ng-scope">
			<div class="form-group">
				<label>PERFIL:</label>
				<input ng-model="vm.modal.iten.GRUPO" ng-disabled="vm.modal.acao == 2" type="text" class="form-control input-maior" value="" autocomplete="off">
			</div>
		</div>

		<div class="itens-inputs ng-scope">
			<div class="form-group">
				<label>Bloquear o que está na lista de URLs:</label>
				<span ng-click="vm.modal.iten.INVERT_URL = 0" ng-if="vm.modal.iten.INVERT_URL == 1 && vm.modal.acao != 2" class="glyphicon glyphicon-ok" style="color: green; border: 1px solid; padding: 3px;"></span>
	            <span ng-click="vm.modal.iten.INVERT_URL = 1" ng-if="vm.modal.iten.INVERT_URL == 0 && vm.modal.acao != 2" class="glyphicon glyphicon-remove" style="color: red; border: 1px solid; padding: 3px;"></span>
				<span ng-if="vm.modal.iten.INVERT_URL   == 1 && vm.modal.acao == 2" class="glyphicon glyphicon-ok" style="color: green; border: 1px solid; padding: 3px; background-color: #eeeeee;"></span>
	            <span ng-if="vm.modal.iten.INVERT_URL   == 0 && vm.modal.acao == 2" class="glyphicon glyphicon-remove" style="color: red; border: 1px solid; padding: 3px; background-color: #eeeeee;"></span>
			</div>
		</div>

		<div class="itens-inputs ng-scope">
			<div class="form-group">
				<label>Usar USB:</label>
				<span ng-click="vm.modal.iten.USB = 1" ng-if="vm.modal.iten.USB == 0 && vm.modal.acao != 2" class="glyphicon glyphicon-ok" style="color: green; border: 1px solid; padding: 3px;"></span>
	            <span ng-click="vm.modal.iten.USB = 0" ng-if="vm.modal.iten.USB == 1 && vm.modal.acao != 2" class="glyphicon glyphicon-remove" style="color: red; border: 1px solid; padding: 3px;"></span>
				<span ng-if="vm.modal.iten.USB   == 0 && vm.modal.acao == 2" class="glyphicon glyphicon-ok" style="color: green; border: 1px solid; padding: 3px; background-color: #eeeeee;"></span>
	            <span ng-if="vm.modal.iten.USB   == 1 && vm.modal.acao == 2" class="glyphicon glyphicon-remove" style="color: red; border: 1px solid; padding: 3px; background-color: #eeeeee;"></span>
			</div>
		</div>

		<div class="itens-inputs ng-scope">
			<div class="form-group">
				<label>Usar CD\DVD:</label>
				<span ng-click="vm.modal.iten.CDDVD = 1" ng-if="vm.modal.iten.CDDVD == 0 && vm.modal.acao != 2" class="glyphicon glyphicon-ok" style="color: green; border: 1px solid; padding: 3px;"></span>
	            <span ng-click="vm.modal.iten.CDDVD = 0" ng-if="vm.modal.iten.CDDVD == 1 && vm.modal.acao != 2" class="glyphicon glyphicon-remove" style="color: red; border: 1px solid; padding: 3px;"></span>
				<span ng-if="vm.modal.iten.CDDVD   == 0 && vm.modal.acao == 2" class="glyphicon glyphicon-ok" style="color: green; border: 1px solid; padding: 3px; background-color: #eeeeee;"></span>
	            <span ng-if="vm.modal.iten.CDDVD   == 1 && vm.modal.acao == 2" class="glyphicon glyphicon-remove" style="color: red; border: 1px solid; padding: 3px; background-color: #eeeeee;"></span>
			
			</div>
		</div>
	</div>

	<div role="tabpanel" class="tab-pane fade" id="tab-url-container" aria-labelledby="tab-url">
		<div style="max-height: calc(100vh - 186px);" class="table-ec">
		    <div class="scroll-table">
		        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
		            <thead>
			            <tr>
			            	<th>Url</th>
			            </tr>
			        </thead>
		            <tbody>
		                <tr tabindex="0" ng-repeat="iten in vm.modal.URL.DADOS" >
		                  	<td auto-title >@{{iten.PALAVRA}}</td>
		                </tr>               
		            </tbody>
		        </table>
		    </div>
		</div>
	</div>

	<div role="tabpanel" class="tab-pane fade" id="tab-janelas-container" aria-labelledby="tab-janelas">
		<div style="max-height: calc(100vh - 186px);" class="table-ec">
		    <div class="scroll-table">
		        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
		            <thead>
			            <tr>
			            	<th>Url</th>
			            </tr>
			        </thead>
		            <tbody>
		                <tr tabindex="0" ng-repeat="iten in vm.modal.JANELA.DADOS" >
		                  	<td auto-title >@{{iten.PALAVRA}}</td>
		                </tr>               
		            </tbody>
		        </table>
		    </div>
		</div>
	</div>


@overwrite