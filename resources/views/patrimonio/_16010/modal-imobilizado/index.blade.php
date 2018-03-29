@extends('helper.include.view.modal', ['id' => 'modal-imobilizado', 'class_size' => 'modal-full'])

@section('modal-start')
    
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    Imobilizado
</h4>

@overwrite

@section('modal-header-right')

@overwrite

@section('modal-body')

<form class="form-inline" ng-submit="vm.Imobilizado.confirmar()">

    <style>
        .modal.master-modal-full .modal-dialog form {
            height: initial;
        }
    </style>
    <div class="modal-header-right" style="
            position: fixed;
            top: 55px;
            right: 16px;         
        ">
					
        <div class="acoes-modal">    
            <button ng-if="!vm.Imobilizado.ALTERANDO" ng-click="vm.Imobilizado.depreciar()" type="button" class="btn btn-info" data-hotkey="alt+c">
                <span class="fa fa-thumbs-o-up"></span> Concluir
            </button>

            <button ng-if="!vm.Imobilizado.ALTERANDO" data-toggle="modal" data-target="#modal-imobilizado-encerrar" type="button" class="btn btn-info" data-hotkey="alt+e">
                <span class="fa fa-thumbs-o-down"></span> Encerrar
            </button>

            <button ng-if="!vm.Imobilizado.ALTERANDO" ng-click="vm.Imobilizado.copiar()" type="button" class="btn btn-warning" data-hotkey="alt+c">
                <span class="glyphicon glyphicon-copy"></span> Copiar
            </button>

            <button ng-if="vm.Imobilizado.ALTERANDO"  type="submit" ng-click="vm.Imobilizado.Flag = 2" type="button" class="btn btn-info" data-hotkey="alt+c">
                <span class="fa fa-thumbs-o-up"></span> Gravar e Concluir
            </button>

            <button ng-if="vm.Imobilizado.ALTERANDO"  type="submit"  ng-click="vm.Imobilizado.Flag = 1" class="btn btn-success" data-hotkey="f10">
                <span class="glyphicon glyphicon-ok"></span> Gravar
            </button>

            <button ng-if="vm.Imobilizado.ALTERANDO" ng-click="vm.Imobilizado.cancelar()" type="button" class="btn btn-danger" data-confirm="yes" data-hotkey="f11">
                <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
            </button>

            <button ng-if="!vm.Imobilizado.ALTERANDO" ng-click="vm.Imobilizado.alterar()" type="button" class="btn btn-primary" data-hotkey="f9">
                <span class="glyphicon glyphicon-edit"></span> Alterar
            </button>

            <button ng-if="!vm.Imobilizado.ALTERANDO" ng-click="vm.Imobilizado.excluir()" type="button" class="btn btn-danger" data-hotkey="f12">
                <span class="glyphicon glyphicon-trash"></span> Excluir
            </button>

            <button ng-if="!vm.Imobilizado.INCLUINDO" data-consulta-historico data-tabela="TBIMOBILIZADO" data-tabela-id="@{{ vm.Imobilizado.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
                <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
            </button>

            <button ng-if="!vm.Imobilizado.ALTERANDO" type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
              <span class="glyphicon glyphicon-chevron-left"></span> Voltar
            </button>
        </div>
    </div>


    <div class="row">
        
        <div class="form-group">
            <label>ID:</label>
            <input 
                type="text"
                class="form-control input-menor" 
                ng-model="vm.Imobilizado.SELECTED.ID"
                required
                disabled />
        </div>    
        
        <div class="form-group">
            <label>Descrição:</label>
            <input 
                type="text"
                class="form-control input-maior" 
                ng-model="vm.Imobilizado.SELECTED.DESCRICAO"
                required
                ng-readonly="!vm.Imobilizado.ALTERANDO"
                form-validate="true">
        </div>    
        
        <div class="consulta-imobilizado-tipo" style="display: inline-block;"></div>
        
        <div class="form-group">
            <label>
                Taxa:
                <span 
                    style="margin-left: 5px;" 
                    class="glyphicon glyphicon-info-sign" 
                    ttitle="Taxa de depreciação ao ano" ></span>            
            </label>
            
            <input 
                type="text"
                class="form-control input-menor" 
                required
                readonly
                value="@{{ vm.Imobilizado.SELECTED.TAXA * 100 | number: 2 }}%">
        </div>    
        
        <div class="form-group">
            <label>
                Vida Útil:
                <span 
                    style="margin-left: 5px;" 
                    class="glyphicon glyphicon-info-sign" 
                    ttitle="Tempo para depreciação total do imobilizado"></span>            
            </label>
            
            <input 
                type="text"
                class="form-control" 
                required
                readonly
                value="@{{ (1 / vm.Imobilizado.SELECTED.TAXA) * 12 | number }} meses">
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
                ng-model="vm.Imobilizado.SELECTED.DATA_DEPRECIACAO">
        </div>

        <div class="form-group">
            <label>
                Replicar:
                <span 
                    style="margin-left: 5px;" 
                    class="glyphicon glyphicon-info-sign" 
                    ttitle="Quantidade de vezes que este item sera criado" ></span>            
            </label>
            
            <input 
                type="number" 
                decimal="0" min="1" 
                step="1"
                string-to-number
                class="form-control input-menor" 
                required
                ng-readonly="vm.Imobilizado.SELECTED.REPLICAR_READONLY == true || !vm.Imobilizado.ALTERANDO"
                ng-model="vm.Imobilizado.SELECTED.REPLICAR">
        </div>   
        
        <div class="i-consulta-ccusto" style="display: inline-block;"></div>
        
    </div>
    <div class="row">
        <div class="form-group">
            <label>Observação:</label>
            <textarea 
                rows="3" 
                style="width: 648px;" 
                class="form-control ng-pristine ng-valid ng-empty ng-touched" 
                ng-model="vm.Imobilizado.SELECTED.OBSERVACAO"
                ng-readonly="!vm.Imobilizado.ALTERANDO"></textarea>
        </div>    
    </div>
    
</form>

    <ul class="nav nav-tabs">
        <li class="active">
            <a 
                data-toggle="tab" 
                href="#tab-itens"
                ng-click="vm.Filtro.TAB_ACTIVE = 'ITENS';">
                Componentes
            </a>
        </li>
    </ul>    
    <div class="tab-content">
        <div id="tab-itens" class="tab-pane fade in active" style="height: calc(100vh - 433px);">
            
            <div class="button-container" style="margin-bottom: 5px;">
                <form class="form-inline" ng-submit="vm.ImobilizadoItem.importarDocumentoFiscal()"  style="display: inline-block;">
                <div class="form-group">
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control"
                            placeholder="Importar Doc. Fiscal"
                            ng-model="vm.ImobilizadoItem.NFE"
                            form-validate="true"
                            required
                            ng-disabled="!vm.Imobilizado.ALTERANDO">
                        
                        <button 
                            type="submit" 
                            class="input-group-addon btn-filtro" 
                            tabindex="-1"
                            ng-disabled="!vm.Imobilizado.ALTERANDO"
                            >
                            <span class="glyphicon glyphicon-triangle-right"></span>
                        </button>
                    </div>
                    
                    <div ng-if="vm.Empresas.length > 1"
                        style="
                            display: block;
                            position: absolute;
                            z-index: 9999;
                            padding: 1px;
                            background-color: #3479b7;
                            box-shadow: 0px 1px 3px #a90f0f;
                            border-radius: 0 0 5px 5px;
                            transition: 0.5s;
                        "

                        >

                        <table class="table table-bordered table-hover table-striped table-condensed table-middle">
                            <thead>
                                <tr>
                                    <th>NFE</th>
                                    <th>Série</th>
                                    <th>Empresa</th>
                                    <th>Entrada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="itemEmp in vm.Empresas" ng-click="vm.AddItensEmpresa(itemEmp.ITENS)" tabindex="0">
                                    <td>@{{ itemEmp.NOTA }}</td>
                                    <td>@{{ itemEmp.SERIE }}</td>
                                    <td>@{{ itemEmp.EMPRESA }}</td>
                                    <td>@{{ itemEmp.DESC_DATA_ENTRADA }}</td>
                                </tr>
                            </tbody>

                        </table> 
                        <button ng-click="vm.Empresas = []" type="button" class="btn btn-danger btn-xs">
                            <span class="glyphicon glyphicon-ban-circle"></span> Fechar
                        </button>
                    </div>

                </div>
                </form>
                
                <button 
                    type="button"
                    class="btn btn-primary"
                    data-hotkey="f1"
                    ttitle="Incluir item de imobilizado avulso"
                    ng-click="vm.ImobilizadoItem.incluir()"
                    ng-disabled="!vm.Imobilizado.ALTERANDO"
                    >
                    <span class="glyphicon glyphicon-plus"></span>
                    Incluir
                </button>
                
                <button 
                    type="button"
                    class="btn btn-warning"
                    data-hotkey="f2"
                    ttitle="Incluir item de imobilizado avulso"
                    ng-click="vm.ImobilizadoItem.alterar()"
                    ng-disabled="!vm.Imobilizado.ALTERANDO || !(vm.ImobilizadoItem.SELECTEDS.length == 1)"
                    >
                    <span class="fa fa-pencil-square-o"></span>
                    Alterar
                </button>
                
                <button 
                    type="button"
                    class="btn btn-danger"
                    data-hotkey="f3"
                    ng-click="vm.ImobilizadoItem.remover()"
                    ng-disabled="!vm.Imobilizado.ALTERANDO || !(vm.ImobilizadoItem.SELECTEDS.length > 0)"
                    >
                    <span class="glyphicon glyphicon-trash"></span>
                    Excluir
                </button>
                
                <button 
                    type="button"
                    class="btn btn-default"
                    ng-click="vm.ImobilizadoItem.pickReverse()"
                    ng-disabled="!(vm.Imobilizado.SELECTED.ITENS.length > 0)"
                    >
                    Inverter Marcação
                </button>
                
                <button 
                    type="button"
                    class="btn btn-default"
                    ng-click="vm.ImobilizadoItem.SELECTEDS.length > 0 ? vm.ImobilizadoItem.unpickAll() : vm.ImobilizadoItem.pickAll()"
                    >
                    <span ng-if="vm.ImobilizadoItem.SELECTEDS.length > 0">Desmarcar Todos</span>
                    <span ng-if="!(vm.ImobilizadoItem.SELECTEDS.length > 0)">Marcar Todos</span>
                    
                </button>

                
                
                
                <button 
                    type="button"
                    class="btn btn-info"
                    ng-click="vm.Imobilizado.consultarParcelas()"
                    >
                    Detalhar Parcelas
                </button>
                
                <button 
                    type="button"   
                    class="btn btn-warning"
                    ng-if="vm.Imobilizado.SELECTED.FRETES.length > 0"
                    data-toggle="popover" 
                    data-placement="top" 
                    title="Frete"
                    data-element-content="#info-frete"
                    >
                    Detalhamento Frete
                </button>
                <div id="info-frete" style="display: none">
                    <table class="table table-striped table-bordered" ng-repeat="f in vm.Imobilizado.SELECTED.FRETES">
                        <tbody>
                            <tr>
                                <td>Doc. Fiscal</td>
                                <td>@{{ f.NUMERO_NOTAFISCAL }}-@{{ f.SERIE | lpad : [3,0] }}</td>
                            </tr>
                            <tr>
                                <td>Empresa</td>
                                <td>@{{ f.EMPRESA_ID | lpad : [4,0] }} - @{{ f.EMPRESA_RAZAOSOCIAL }}</td>
                            </tr>
                            <tr>
                                <td>Data Entrada</td>
                                <td>@{{ f.DATA_ENTRADA_TEXT }}</td>
                            </tr>
                            <tr>
                                <td>Valor Total</td>
                                <td>R$ @{{ f.VALOR_TOTAL | number : 2 }}</td>
                            </tr>
                        </tbody>
                    </table>         
                </div>                

                
            </div>
            
            <div class="table-ec" style="height: calc(100% - 50px);">
                <table class="table table-bordered table-hover table-striped table-condensed table-middle table-no-break">
                    <thead>
                        <tr>
                            <th ttitle="Id do item do imobilizado">Id</th>
                            <th>NFE</th>
                            <th class="text-center" ttitle="Sequencia da Nota Fiscal">Seq.</th>
                            <th>Produto</th>
                            <th class="text-center" ttitle="Data inicial da depreciação">Data Inicial</th>
                            <th class="text-center" ttitle="Data final da depreciação">Data Final</th>
                            <th ttitle="Parcela do mês atual">Parcela</th>
                            <th class="text-right" ttitle="Quantidade">Qtd.</th>
                            <th class="text-right" ttitle="Valor unitário (sem acréscimos ou descontos)">Valor Uni.</th>
                            <th class="text-right" ttitle="Valor unitário * Quantidade">Sub Total</th>
                            <th class="text-right" ttitle="Valor do acrescimo">Acrescimo</th>
                            <th class="text-right" ttitle="Valor do desconto">Desconto</th>
                            <th class="text-right" ttitle="Valor do frete">Frete</th>
                            <th class="text-right" ttitle="Valor Unitário do Imposto Sobre Circulação de Mercadorias e Serviços">ICMS</th>
                            <th class="text-right" ttitle="Valor total: Subtotal + Acrescimo - Desconto + Frete - ICMS ">Valor Tot.</th>
                            <th class="text-right" ttitle="Valor restante à depreciar">Saldo Depreciar</th>
                            <th>Observação</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            ng-repeat="item in vm.Imobilizado.SELECTED.ITENS | orderBy : ['NFE','NFE_SEQUENCIA','PRODUTO_DESCRICAO']"
                            ng-class="{ 'selected': vm.ImobilizadoItem.picked(item) }"
                            ng-focus="vm.Imobilizado.ALTERANDO ? '' : vm.ImobilizadoItem.SELECTEDS = []; vm.Imobilizado.ALTERANDO ? '' : vm.ImobilizadoItem.pickToggle(item)"
                            ng-click="vm.Imobilizado.ALTERANDO ? '' : vm.ImobilizadoItem.SELECTEDS = []; vm.ImobilizadoItem.pickToggle(item)"
                            ng-dblclick="vm.Imobilizado.ALTERANDO ? vm.ImobilizadoItem.dblClick(item) : (item.PARCELAS > 0 ? vm.ImobilizadoItem.consultarParcelas(item) : '') "
                            ng-keypress="vm.ImobilizadoItem.keypress(item,$event)"
                            ng-if="item.EXCLUIDO == undefined"
                            tabindex="0"
                            >
                            <td>@{{ item.ID }}</td>
                            <td>@{{ item.NFE }}</td>
                            <td class="text-center">@{{ item.NFE_SEQUENCIA || 0 | lpad : [2,0] }}</td>
                            <td>@{{ item.PRODUTO_ID }} - @{{ item.PRODUTO_DESCRICAO }}</td>
                            <td class="text-center">@{{ item.DATA_DEPRECIACAO_INICIO_TEXT }}</td>
                            <td class="text-center">@{{ item.DATA_DEPRECIACAO_FIM_TEXT }}</td>
                            <td>@{{ item.PARCELA || 0 | lpad: [3,0] }}/@{{ item.PARCELAS || 0 | lpad: [3,0] }} (R$ @{{ item.VALOR_PARCELA || 0 | number : 2 }})</td>
                            <td class="text-right">@{{ item.QUANTIDADE | number : 2 }}</td>
                            <td class="text-right">R$ @{{ item.VALOR_UNITARIO_SEM_DESC || item.VALOR_UNITARIO | number : 2 }}</td>
                            <td class="text-right">R$ @{{ ( item.VALOR_UNITARIO_SEM_DESC || item.VALOR_UNITARIO ) * item.QUANTIDADE | number : 2 }}</td>
                            <td class="text-right">R$ @{{ item.VALOR_ACRESCIMO || 0 | number : 2 }}</td>
                            <td class="text-right">R$ @{{ item.VALOR_DESCONTO || 0 | number : 2 }}</td>
                            <td class="text-right">R$ @{{ item.FRETE_UNITARIO | number : 2 }}</td>
                            <td class="text-right">R$ @{{ item.ICMS_UNITARIO || 0 | number : 2 }}</td>
                            <td class="text-right">R$ @{{ item.VALOR_TOTAL | number : 2 }}</td>
                            <td class="text-right">R$ @{{ item.SALDO | number : 2 }}</td>
                            <td>@{{ item.OBSERVACAO }}</td>
                            <td class="text-center">
                                <button 
                                    type="button" 
                                    class="btn btn-default btn-xs" 
                                    ng-click="vm.ImobilizadoItem.consultarParcelas(item)"
                                    ng-disabled="vm.Imobilizado.ALTERANDO || !(item.PARCELAS > 0)"
                                    tabindex="-1"
                                    >
                                    Detalhar Parcelas
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">Totalizador</td>
                            <td class="text-right">R$ @{{ vm.ImobilizadoItem.TOTAL_PARCELA | number : 2 }}</td>
                            <td class="text-right">@{{ vm.ImobilizadoItem.TOTAL_QUANTIDADE | number : 2 }}</td>
                            <td class="text-right">R$ @{{ vm.ImobilizadoItem.TOTAL_VALOR_UNITARIO_SEM_DESC | number : 2 }}</td>
                            <td class="text-right">R$ @{{ vm.ImobilizadoItem.TOTAL_SUB | number : 2 }}</td>
                            <td class="text-right">R$ @{{ vm.ImobilizadoItem.TOTAL_VALOR_ACRESCIMO | number : 2 }}</td>
                            <td class="text-right">R$ @{{ vm.ImobilizadoItem.TOTAL_VALOR_DESCONTO | number : 2 }}</td>
                            <td class="text-right">R$ @{{ vm.ImobilizadoItem.TOTAL_FRETE | number : 2 }}</td>
                            <td class="text-right">R$ @{{ vm.ImobilizadoItem.TOTAL_ICMS | number : 2 }}</td>
                            <td class="text-right">R$ @{{ vm.ImobilizadoItem.TOTAL_GERAL | number : 2 }}</td>
                            <td class="text-right">R$ @{{ vm.ImobilizadoItem.TOTAL_SALDO | number : 2 }}</td>
                            <td colspan="2"></td>
                        </tr>  
                    </tfoot>

                </table>
            </div>
            
        </div>
    </div>
@overwrite

@section('modal-end')

@overwrite