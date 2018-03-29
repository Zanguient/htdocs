@extends('helper.include.view.modal', ['id' => 'modal-imobilizado-item', 'class_size' => 'modal-large'])

@section('modal-start')
    <form class="form-inline" ng-submit="vm.ImobilizadoItem.confirmar()">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    Incluir Imobilizado
</h4>

@overwrite

@section('modal-header-right')
    <button 
        type="submit" 
        class="btn btn-success" 
        data-hotkey="f4" 
        data-loading-text="Confirmando...">
        <span class="glyphicon glyphicon-ok"></span> 
        Confirmar
    </button>

    <button 
        ng-click="vm.ImobilizadoItem.cancelar()" 
        type="button" 
        class="btn btn-danger btn-cancelar"
        data-hotkey="esc" >
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>


@overwrite

@section('modal-body')

<div style="height: calc(100vh - 150px);">
    <div class="row">

        <div class="ii-consulta-produto" ng-show="vm.ImobilizadoItem.PRODUTO_READONLY == false"></div>
        
        <div class="form-group" ng-if="vm.ImobilizadoItem.PRODUTO_READONLY == true">
            <label>Produto:</label>
            <div class="input-group">
                <input 
                    type="search" 
                    class="form-control input-maior"
                    readonly="true" 
                    required="required"
                    value="@{{ vm.ImobilizadoItem.SELECTED.PRODUTO_ID }} - @{{ vm.ImobilizadoItem.SELECTED.PRODUTO_DESCRICAO }}"/>
                <button 
                    type="button" 
                    class="input-group-addon btn-filtro search-button" 
                    tabindex="-1" 
                    style="display: block !important;" 
                    disabled="true">
                    <span class="fa fa-close"></span>
                </button>                
            </div>
        </div>
        
        <div class="form-group">
            <label>Valor:</label>
            <div class="input-group dinheiro">
                <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                <input style="text-align: right;  width: 100% !important;"
                    type="number" 
                    decimal="4" min="0.0001" 
                    step="0.0001"
                    string-to-number
                    class="form-control input-medio" 
                    ng-model="vm.ImobilizadoItem.SELECTED.VALOR_UNITARIO"
                    required />
            </div>
        </div>

        <div class="form-group">
            <label>Frete:</label>
            <div class="input-group dinheiro">
                <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                <input style="text-align: right;  width: 100% !important;"
                    type="number" 
                    decimal="4" min="0.0000" 
                    step="0.0001"
                    string-to-number
                    class="form-control input-medio" 
                    ng-model="vm.ImobilizadoItem.SELECTED.FRETE_UNITARIO"
                    required />
            </div>
        </div> 

        <div class="form-group">
            <label>
                Quantidade:
                <span 
                    style="margin-left: 5px;" 
                    class="glyphicon glyphicon-info-sign" 
                    ttitle="Taxa de depreciação ao ano" ></span>            
            </label>
            
            <input style="text-align: right;"
                    type="number" 
                    decimal="4" min="0.0001" 
                    step="0.0001"
                    string-to-number
                    class="form-control input-menor" 
                    ng-model="vm.ImobilizadoItem.SELECTED.QUANTIDADE"
                    required />
        </div>

        <div class="form-group">
            <label>
                Data início Depreciação:
                <span 
                    style="margin-left: 5px;" 
                    class="glyphicon glyphicon-info-sign" 
                    ttitle="Data de início da depreciação dos itens"></span>            
            </label>
            
            <input 
                type="date"
                class="form-control" 
                required
                ng-readonly="!vm.Imobilizado.ALTERANDO"
                ng-model="vm.ImobilizadoItem.SELECTED.DATA_ENTRADA">
        </div> 
  

    </div>
    <div class="row"  style="width: 100% !important;" >
        <div class="form-group" style="width: 100% !important;" >
            <label>Observação:</label>
            <textarea 
                rows="3" 
                style="width: 100%!important;" 
                class="form-control ng-pristine ng-valid ng-empty ng-touched" 
                ng-model="vm.ImobilizadoItem.SELECTED.OBSERVACAO"></textarea>
        </div>    
    </div>
    
</div>
@overwrite

@section('modal-end')
    </form>
@overwrite