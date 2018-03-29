@extends('helper.include.view.modal', ['id' => 'modal-trocar-produto', 'class_size' => ''])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Gp.consultar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Trocar Produto
	</h4>

@overwrite

@section('modal-header-right')
    <button type="button" class="btn btn-danger  btn-cancelar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span>
        Cancelar
    </button>
@overwrite

@section('modal-body')

    <div class="row break large">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#tab-densidade">Densidade e Espessura</a>
            </li>
            <li>
                <a data-toggle="tab" href="#tab-produto">Produto</a>
            </li>                   
        </ul>
        <div class="tab-content">
            <div id="tab-densidade" class="tab-pane fade in active" style="overflow: hidden;">
                <div style="height: 600px;">

                    <div class="form-group ">
                        <label  title="Densidade">Densidade:</label>
                        <div class="input-group">
                            <input style="border-radius: 5px;" type="number" ng-min="1" min="1" class="form-control input-menor" ng-model="vm.Item.Ficha.DENSIDADE">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label  title="Densidade">Espessura:</label>
                        <div class="input-group">
                            <input style="border-radius: 5px;" type="number" ng-min="1" min="1" class="form-control input-menor" ng-model="vm.Item.Ficha.ESPESSURA">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label  title="Densidade" style="opacity: 0;">:</label>
                        <div class="input-group">
                            <button ng-disabled="vm.Item.Ficha.ESPESSURA <= 0 || vm.Item.Ficha.DENSIDADE <= 0" type="button" class="btn btn-success" ng-click="vm.Item.Ficha.consultarDensidade();">
                                <span class="glyphicon glyphicon-ok"></span>
                                Filtrar
                            </button> 
                        </div>
                    </div>

                    <div class="form-group ">
                        <label  title="Densidade">Produto:</label>
                        <div class="input-group">
                            <input style="border-radius: 5px 0px 0px 5px; width: 445px !important;" type="text" readonly="" class="form-control input-maior" ng-model="vm.Item.Ficha.LISTA_DENSIDADE.ITEM">
                            
                            <button style="border-radius: 0px 5px 5px 0px; height: 34px;" ng-disabled="vm.Item.Ficha.LISTA_DENSIDADE.SELECTED != true" type="button" class="btn btn-danger" ng-click="vm.Item.Ficha.limparDensidade()">
                                <span class="glyphicon glyphicon-remove"></span>
                                Limpar
                            </button> 

                            <div ng-if="vm.Item.Ficha.LISTA_DENSIDADE.VISIVEL ==  true" style="width:530px; max-height: 300px; position: fixed; background-color: #3479b7;" class="pesquisa-res-container ativo lista-consulta-container ">
                                <div class="pesquisa-res lista-consulta table-ec">
                                    <table style="margin-left: 1px;" class="table table-condensed table-striped table-bordered table-hover selectable">
                                        <thead>
                                            <tr>
                                                <th>Modelo</th>
                                                <th>Densidade</th>
                                                <th>Espessura</th>                                        
                                                <th>Tipo</th>
                                            </tr>
                                        </thead>
                                            <tr ng-click="vm.Item.Ficha.limparDensidade()" ng-if="vm.Item.Ficha.LISTA_DENSIDADE.DADOS.length == 0">
                                                <td colspan="4">SEM REGISTROS PARA LISTAR</td>
                                            </tr>

                                            <tr tabindex="0" ng-click="vm.Item.Ficha.selectDensidade(item)" ng-repeat="item in vm.Item.Ficha.LISTA_DENSIDADE.DADOS">
                                                <td>@{{item.MODELO_DESCRICAO}}</td>
                                                <td>@{{item.DENSIDADE}}</td>
                                                <td>@{{item.ESPESSURA}}</td>
                                                <td>@{{item.TIPO}}</td>
                                            </tr>
                                    </table>
                                </div>
                                <button ng-click="vm.Item.Ficha.LISTA_DENSIDADE.VISIVEL = false" type="button" class="btn btn-danger btn-xs">
                                    <span class="glyphicon glyphicon-ban-circle"></span> Fechar
                                </button>
                            </div>

                        </div>
                    </div>

    
                    <div style="
                        margin-top: 20px;
                        padding: 5px;
                        font-size: 16px;
                        font-weight: bold;
                        text-align: center;">

                        <div>@{{vm.Item.Ficha.OLD_PRODUTO.PRODUTO_DESCRICAO}}</div>
                        <div>Tamanho:@{{vm.Item.Ficha.OLD_PRODUTO.DESC_TAMANHO}}</div>

                        <span style="font-size: 36px; margin: 15px;" class="glyphicon glyphicon-refresh"></span>

                        <div>@{{vm.Item.Ficha.NEW_PRODUTO.DESCRICAO}}</div>
                        <div>Tamanho:@{{vm.Item.Ficha.NEW_PRODUTO.DESC_TAMANHO}}</div>

                        <button ng-disabled="vm.Item.Ficha.LISTA_DENSIDADE.SELECTED != true" style="margin: 15px;" type="button" class="btn btn-success" ng-click="vm.Item.Ficha.ConfirmarTroca();">
                            <span class="glyphicon glyphicon-ok"></span>
                            Confirmar Troca
                        </button>
                    </div>
                </div> 
            </div>
            <div id="tab-produto" class="tab-pane fade" style="overflow: hidden;">
                <div style="height: 600px;">
                    <div class="consulta-produto">
                    </div>

                    <div class="consulta-tamanho">
                    </div>        

                    <div style="
                        margin-top: 20px;
                        padding: 5px;
                        font-size: 16px;
                        font-weight: bold;
                        text-align: center;">

                        <div>@{{vm.Item.Ficha.OLD_PRODUTO.PRODUTO_DESCRICAO}}</div>
                        <div>Tamanho:@{{vm.Item.Ficha.OLD_PRODUTO.DESC_TAMANHO}}</div>

                        <span style="font-size: 36px; margin: 15px;" class="glyphicon glyphicon-refresh"></span>

                        <div>@{{vm.Item.Ficha.NEW_PRODUTO.DESCRICAO}}</div>
                        <div>Tamanho:@{{vm.Item.Ficha.NEW_PRODUTO.DESC_TAMANHO}}</div>

                        <button ng-disabled="vm.ConsultaProduto.item.selected == false" style="margin: 15px;" type="button" class="btn btn-success" ng-click="vm.Item.Ficha.ConfirmarTroca();">
                            <span class="glyphicon glyphicon-ok"></span>
                            Confirmar Troca
                        </button>
                    </div>
                </div> 
            </div>
        </div>
    </div>  

@overwrite

@section('modal-end')
    </form>
@overwrite