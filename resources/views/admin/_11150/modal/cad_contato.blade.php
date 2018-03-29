	@extends('helper.include.view.modal', ['id' => 'modal-cad-contato', 'class_size' => 'modal-lg'])

	@section('modal-header-left')

	<h4 class="modal-title">
		Cadastro de Contatos
	</h4>

	@overwrite

	@section('modal-header-right')
		
		<button type="button" ng-click="vm.Acoes.btnGravar()" class="btn btn-success">
		  <span class="glyphicon glyphicon-chevron-left"></span> Gravar
		</button>

		<button type="button" ng-click="vm.Acoes.btnVoltar()" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
		  <span class="glyphicon glyphicon-chevron-left"></span> Voltar
		</button>

	@overwrite

	@section('modal-body')

		<ul id="tab" class="nav nav-tabs" role="tablist"> 
            <li role="presentation" class="active tab-detalhamento">
                <a ng-click="vm.tabCaso.btn.click()" href="#tab-cadastro-container" id="tab-cadastro" role="tab" data-toggle="tab" aria-controls="tab-cadastro-container" aria-expanded="false">
                    Ca<span style="text-decoration: underline;">d</span>astro
                </a>
            </li>
            <li role="presentation" class="tab-detalhamento">
                <a ng-click="vm.Acoes.openListaContato()" href="#tab-contatos-container" id="tab-contatos" role="tab" data-toggle="tab" aria-controls="tab-contatos-container" aria-expanded="false">
                    Co<span style="text-decoration: underline;">n</span>tatos
                </a>
            </li>
        </ul>

        <div role="tabpanel" class="tab-pane fade active in" id="tab-cadastro-container" aria-labelledby="tab-cadastro">
            <div class="imput-itens-cad-contato">
			
			</div>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab-contatos-container" aria-labelledby="tab-contatos">
			<div class="input-group input-group-pesquisa" style="margin: 10px;">
				<input style="width: calc(100% - 20px);" type="search" ng-model="vm.filtroContato" name="filtro_pesquisa" class="form-control filtro-obj" id="filter-btn-find" placeholder="Pesquise..." autocomplete="off" autofocus="">
			</div>

            <div class="table-container">
                <table class="table table-bordered table-header">
                    <thead>
                        <tr>
                            <th class="campo-contato" ng-repeat="iten in vm.ConfConato">@{{iten.DESCRICAO}}</th>
                        </tr>
                    </thead>
                </table>
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover table-body">
                        <tbody>
                            <tr  ng-dblclick="vm.Acoes.selectContato(contato.ID)" tabindex="-1" ng-repeat="contato in vm.ListaConato | filter:vm.filtroContato">
                                <td class="campo-contato td-campo-contato" autotitle ng-repeat="iten in vm.ConfConato">
									<span ng-if="iten.TIPO == 1 || iten.TIPO == 2 || iten.TIPO == 6 || iten.TIPO == 10">
	                                	@{{contato[iten.ID].VALOR}}
									</span>
									<span ng-if="iten.TIPO == 3">
	                                	@{{contato[iten.ID].VALOR | date: 'dd/MM/yyyy' }}
									</span>
									<span ng-if="(iten.TIPO == 9 || iten.TIPO == 5) && selec.VALOR == contato[iten.ID].VALOR" ng-repeat="selec in iten.ITENS">
										@{{selec.TEXTO}}
									</span>
									<span ng-if="iten.TIPO == 4 && iten.ID == selec.CAMPO_ID && selec.SELECTED" ng-repeat="selec in contato[iten.ID].ITENS track by $index">
										@{{selec.TEXTO + ', '}}
									</span>
									<span ng-if="iten.TIPO == 8">
	                                	@{{contato[iten.ID].VALOR | date: 'hh:mm:ss' }}
									</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

	@overwrite