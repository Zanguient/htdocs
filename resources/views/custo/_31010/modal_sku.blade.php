@extends('helper.include.view.modal', ['id' => 'modal-sku'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Gp.consultar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Modelo / Cor / Tamanho
	</h4>

@overwrite

@section('modal-header-right')
    <button type="button" class="btn btn-danger" data-dismiss="modal">
        <span class="glyphicon glyphicon-ban-circle"></span>
        Cancelar
    </button>

    <button type="button" class="btn btn-success" ng-click="vm.Modelo.close()">
        <span class="glyphicon glyphicon-ok"></span>
        Confirmar
    </button>
@overwrite

@section('modal-body')

	<div class="consulta-modelo"></div>

    <div style="width: 100%;display: inline-flex;height: 370px;">

        <div class="item-tabela" style="width: 65%; height: 330px; margin-right: 15px;">
            <div class="input-group" style="width: 100%;">
                <input type="text" style= "border-radius: 4px;" id="filtro-cor" class="form-control filtro input-resize" placeholder="Pesquise uma Cor..." autocomplete="off" ng-model="vm.Cor.FILTRO">
            </div>

            <div class="table-ec">
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>COR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                class="item_modelo_@{{iten.ID}}"
                                tabindex ="0"
                                ng-repeat="iten in vm.Lista.COR = ( vm.Cor.ITENS | filter:vm.Cor.FILTRO | orderBy:vm.Cor.ORDEM )"
                                
                                ng-focus ="vm.Cor.Selectionar(iten)"
                                ng-class ="{'selected' : vm.Cor.SELECTED == iten}"
                                >
                                <td auto-title >@{{iten.ID}}</td>
                                <td auto-title >@{{iten.DESCRICAO}} <span ng-if="iten.PADRAO == 1" title="Cor padrão" class="glyphicon glyphicon-star" aria-hidden="true"></span> </td>
                            </tr>               
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="item-tabela"  style="width: 32%; height: 330px;">
            <div class="input-group"  style="width: 100%;">
                <input type="text" id="filtro-cor"  style= "border-radius: 4px;" class="form-control filtro input-resize" placeholder="Pesquise uma Tamanho..." autocomplete="off" ng-model="vm.Tamanho.FILTRO">
            </div>

            <div class="table-ec">
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>TAMANHO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                class="item_modelo_@{{iten.ID}}"
                                tabindex ="0"
                                ng-repeat="iten in vm.Lista.TAMANHO = ( vm.Tamanho.ITENS | filter:vm.Tamanho.FILTRO | orderBy:vm.Tamanho.ORDEM )"
                                
                                ng-focus ="vm.Tamanho.Selectionar(iten)"
                                ng-class ="{'selected' : vm.Tamanho.SELECTED == iten}"
                                >
                                <td auto-title >@{{iten.ID}}</td>
                                <td auto-title >@{{iten.DESCRICAO}} <span ng-if="iten.PADRAO == 1" title="Tamanho padrão" class="glyphicon glyphicon-star" aria-hidden="true"></span> </td>
                            </tr>               
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@overwrite

@section('modal-end')
    </form>
@overwrite