@extends('helper.include.view.modal', ['id' => 'modal-cota', 'class_size' => 'modal-big'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Cota.gravarAlteracao()">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    Detalhamento da Cota
</h4>

@overwrite

@section('modal-header-right')

    <button ng-if="vm.Cota.ALTERANDO" type="submit" class="btn btn-success" data-hotkey="F10">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>
    
    <button ng-if="vm.Cota.ALTERANDO" ng-click="vm.Cota.cancelar()" type="button" class="btn btn-danger" data-confirm="yes" data-hotkey="F11">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

    <button ng-if="vm.Cota.SELECTED.TIPO == 'COTA' && !vm.Cota.ALTERANDO" ng-click="vm.Cota.alterar()" type="button" class="btn btn-primary" data-hotkey="F9">
        <span class="glyphicon glyphicon-edit"></span> Alterar
    </button>

    <button ng-if="vm.Cota.SELECTED.TIPO == 'COTA' && !vm.Cota.ALTERANDO" ng-click="vm.Cota.excluir()" type="button" class="btn btn-danger" data-hotkey="F12">
        <span class="glyphicon glyphicon-trash"></span> Excluir
    </button>

    <button ng-if="vm.Cota.SELECTED.TIPO == 'COTA'" data-consulta-historico data-tabela="TBCCUSTO_COTA" data-tabela-id="@{{ vm.Cota.SELECTED.ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
        <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    </button>

    <button ng-if="!vm.Cota.ALTERANDO" type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')


    <ul class="nav nav-tabs">
        <li class="active">
            <a 
                data-toggle="tab" 
                href="#cota-home">
                Informações Gerais
            </a>
        </li>
        <li>
            <a 
                data-toggle="tab" 
                href="#cota-lancamentos">
                Lançamentos
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="cota-home" class="tab-pane fade in active">
                <div class="row">              
                    <div ng-if="vm.Cota.SELECTED.TIPO == 'COTA'" class="form-group">
                        <label>Id:</label>
                        <input type="text" name="ccusto_descricao" class="form-control input-menor" readonly value="@{{ vm.Cota.SELECTED.ID }}" required/>
                    </div>                
                    <div class="form-group">
                        <label ttitle="Centro de Custo">C. Custo:</label>
                        <input type="text" name="ccusto_descricao" class="form-control input-maior" readonly value="@{{ vm.Cota.SELECTED.CCUSTO_MASK + ' - ' + vm.Cota.SELECTED.CCUSTO_DESCRICAO }}" required/>
                    </div>
                    <div class="form-group">
                        <label ttitle="Conta Contábil">C. Contábil:</label>
                        <input type="text" name="ccontabil_descricao" class="form-control input-maior" readonly value="@{{ vm.Cota.SELECTED.CCONTABIL_MASK + ' - ' + vm.Cota.SELECTED.CCONTABIL_DESCRICAO }}" required/>
                    </div>
                    <div class="form-group">
                        <label>Período:</label>
                        <input type="text" name="periodo" class="form-control" readonly value="@{{ vm.Cota.SELECTED.PERIODO_DESCRICAO }}" required/>
                    </div>
                </div>
                <div ng-if="vm.Cota.SELECTED.TIPO == 'COTA'" class="row">
                    <div class="form-group">
                        <input type="checkbox" name="bloqueio" id="bloqueia" class="form-control" ng-checked="vm.Cota.SELECTED.BLOQUEIA == 1" ng-disabled="!vm.Cota.ALTERANDO" ng-click="vm.Cota.SELECTED.BLOQUEIA = vm.Cota.SELECTED.BLOQUEIA == 1 ? 0 : 1" />
                        <label for="bloqueia" data-toggle="tooltip" title="{{ Lang::get('compras/_13030.bloqueio-desc') }}" ng-disabled="!vm.Cota.ALTERANDO" >{{ Lang::get('compras/_13030.bloqueio') }}</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="notificacao" id="notifica" class="form-control" ng-checked="vm.Cota.SELECTED.NOTIFICA == 1" ng-disabled="!vm.Cota.ALTERANDO" ng-click="vm.Cota.SELECTED.NOTIFICA = vm.Cota.SELECTED.NOTIFICA == 1 ? 0 : 1" />
                        <label for="notifica"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.notificacao-desc') }}" ng-disabled="!vm.Cota.ALTERANDO" >{{ Lang::get('compras/_13030.notificacao') }}</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="destaque" id="destaque" class="form-control" ng-checked="vm.Cota.SELECTED.DESTAQUE == 1" ng-disabled="!vm.Cota.ALTERANDO" ng-click="vm.Cota.SELECTED.DESTAQUE = vm.Cota.SELECTED.DESTAQUE == 1 ? 0 : 1" />
                        <label for="destaque"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.destaque-desc') }}" ng-disabled="!vm.Cota.ALTERANDO" >{{ Lang::get('compras/_13030.destaque') }}</label>
                    </div>		    
                    <div class="form-group">
                        <input type="checkbox" name="totaliza" id="totaliza" class="form-control" ng-checked="vm.Cota.SELECTED.TOTALIZA == 1" ng-disabled="!vm.Cota.ALTERANDO" ng-click="vm.Cota.SELECTED.TOTALIZA = vm.Cota.SELECTED.TOTALIZA == 1 ? 0 : 1" />
                        <label for="totaliza"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.totaliza-desc') }}" ng-disabled="!vm.Cota.ALTERANDO" >{{ Lang::get('compras/_13030.totaliza') }}</label>
                    </div>		                
                </div>
                <div class="row">
                    <div class="form-group">
                        <label>Cota:</label>
                        <div class="input-group dinheiro">
                            <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                            <input 
                                type="number" 
                                step="0.01" 
                                class="form-control" 
                                form-validade="true"
                                required 
                                ng-readonly="!vm.Cota.ALTERANDO"
                                ng-model="vm.Cota.SELECTED.VALOR"
                                />                    
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Extra (+):</label>
                        <div class="input-group dinheiro">
                            <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                            <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="@{{ vm.Cota.SELECTED.EXTRA | number : 2 }}" required readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Subtotal:</label>
                        <div class="input-group dinheiro">
                            <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                            <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="@{{ vm.Cota.SELECTED.TOTAL | number : 2 }}" required readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Reduções (-):</label>
                        <div class="input-group dinheiro">
                            <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                            <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="@{{ vm.Cota.SELECTED.OUTROS | number : 2 }}" required readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Utilizado:</label>
                        <div class="input-group dinheiro">
                            <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                            <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="@{{ vm.Cota.SELECTED.UTIL | number : 2 }}" required readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Utilizado %:</label>
                        <div class="input-group dinheiro">
                            <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                            <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="@{{ vm.Cota.SELECTED.PERC_UTIL | number : 2 }}" required readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Saldo:</label>
                        <div class="input-group dinheiro">
                            <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                            <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="@{{ vm.Cota.SELECTED.SALDO | number : 2 }}" required readonly/>
                        </div>
                    </div>
                </div>
                <div ng-if="vm.Cota.SELECTED.TIPO == 'COTA'" class="row">
                    <div class="form-group" style="width: 50%;">
                        <label>Observação Cota:</label>
                        <textarea name="cota_observacao" rows="6" style="width: 100% !important;" class="form-control" ng-readonly="!vm.Cota.ALTERANDO" ng-model="vm.Cota.SELECTED.OBSERVACAO_GERAL"></textarea>
                    </div>
                </div>

        </form>

            <fieldset class="cota-extra" ng-if="vm.Cota.SELECTED.TIPO == 'COTA'">
                <legend>Cota Extra</legend>
                <form ng-if="!vm.Cota.ALTERANDO" ng-submit="vm.CotaExtra.gravar()" class="form-inline">
                    <div class="form-group" style="width: 150px;">
                        <label>Valor:</label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                            <input ng-model="vm.CotaExtra.DADOS.VALOR" type="number" class="form-control" min="0.01" step="0.01" required style="width: 100% !important;"/>
                        </div>
                    </div>            
                    <div class="form-group">
                        <label>Observação:</label>
                        <div class="input-group">
                            <input ng-model="vm.CotaExtra.DADOS.OBSERVACAO" type="text" class="input-control input-maior" required/>
                        </div>
                    </div>    
                    <div class="input-group">       
                        <button ttitle="Incluir Cota Extra" type="submit" class="btn btn-success btn-sm btn-confirm" style="top:10px; display: inline-block;">
                            <span class="glyphicon glyphicon-ok"></span> Incluir
                        </button>
                    </div>     
                </form>
                <div class="table-ec"
                    style="
                        max-height: 300px;
                        min-height: 74px;
                        overflow: scroll;
                        height: auto;            
                    "             
                    >
                    <table class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Usuário</th>
                            <th class="text-right">Valor</th>
                            <th class="text-center">Data/Hora</th>
                            <th>Observção</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        </thead>
                        <tbody class="t-body">
                        <tr ng-repeat="extra in vm.Cota.SELECTED.EXTRAS" data-id="@{{ extra.ID }}">
                            <td>@{{ extra.USUARIO_NOME }}</td>
                            <td class="text-right no-break">R$ @{{ extra.VALOR | number: 2 }}</td>
                            <td class="text-center">@{{ extra.DATAHORA | toDate | date:'dd/MM/yy HH:mm:ss' }}</td>
                            <td>@{{ extra.OBSERVACAO }}</td>
                            <td class="text-center">
                                <button 
                                    type="button" 
                                    class="btn btn-danger btn-sm btn-excluir btn-xs" 
                                    ttitle="Excluir"
                                    ng-click="vm.CotaExtra.excluir(extra)"
                                >
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>                        
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>

            <fieldset class="cota-reducao" ng-if="vm.Cota.SELECTED.TIPO == 'COTA'">
                <legend>Redução de Cota</legend>
                <form ng-if="!vm.Cota.ALTERANDO" ng-submit="vm.CotaReducao.gravar()" class="form-inline">
                    <div class="form-group" style="width: 150px;">
                        <label>Valor:</label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                            <input ng-model="vm.CotaReducao.DADOS.VALOR" type="number" class="form-control" min="0.01" step="0.01" required style="width: 100% !important;"/>
                        </div>
                    </div>            
                    <div class="form-group">
                        <label>Observação:</label>
                        <div class="input-group">
                            <input ng-model="vm.CotaReducao.DADOS.OBSERVACAO" type="text" class="input-control input-maior" required/>
                        </div>
                    </div>    
                    <div class="input-group">       
                        <button ttitle="Incluir Cota Redução" type="submit" class="btn btn-success btn-sm btn-confirm" style="top:10px; display: inline-block;">
                            <span class="glyphicon glyphicon-ok"></span> Incluir
                        </button>
                    </div>     
                </form>
                <div class="table-ec"
                    style="
                        max-height: 300px;
                        min-height: 74px;
                        overflow: scroll;
                        height: auto;             
                    "             
                    >
                    <table class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Usuário</th>
                            <th class="text-right">Valor</th>
                            <th class="text-center">Data/Hora</th>
                            <th>Observção</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        </thead>
                        <tbody class="t-body">
                        <tr ng-repeat="reducao in vm.Cota.SELECTED.REDUCOES" data-id="@{{ reducao.ID }}">
                            <td>@{{ reducao.USUARIO_NOME }}</td>
                            <td class="text-right no-break">R$ @{{ reducao.VALOR | number: 2 }}</td>
                            <td class="text-center">@{{ reducao.DATAHORA | toDate | date:'dd/MM/yy HH:mm:ss' }}</td>
                            <td>@{{ reducao.OBSERVACAO }}</td>
                            <td class="text-center">
                                <button 
                                    type="button" 
                                    class="btn btn-danger btn-sm btn-excluir btn-xs" 
                                    ttitle="Excluir"
                                    ng-click="vm.CotaReducao.excluir(reducao)"
                                >
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>                        
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>            
        </div>
        <div id="cota-lancamentos" class="tab-pane fade">

            <div ng-if="vm.Cota.SELECTED.TIPO == 'COTA'"
                class="table-ec"
                style="
                    max-height: calc(100vh - 189px);
                    min-height: 74px;
                    overflow: scroll;
                    height: auto;                 
                "
                 >
                <table class="table table-bordered table-hover table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Descrição</th>
                        <th class="text-right">Valor</th>
                        <th class="text-right" ttitle="Abatimentos">Abat.</th>
                        <th class="text-right" ttitle="Valor - Abatimentos">Subtotal</th>
                        <th class="text-center">Nat.</th>
                        <th class="text-center">Data</th>
                    </tr>
                    </thead>
                    <tbody class="itens">
                    <tr ng-repeat="item in vm.Cota.SELECTED.LANCAMENTOS" data-id="@{{ item.ID }}">
                        <td style="min-width: 350px" autotitle>@{{ item.DESCRICAO }}</td>           
                        <td class="text-right no-break">R$ @{{ item.VALOR | number: 2 }}</td>
                        <td class="text-right no-break">R$ @{{ item.DESCONTO_IMPOSTO | number: 2 }}</td>
                        <td class="text-right no-break">R$ @{{ item.VALOR_SUBTOTAL | number: 2 }}</td>
                        <td class="text-center @{{ item.NATUREZA == 'D' ? 'nat-debito' : 'nat-credit' }}">@{{ item.NATUREZA }}</td>
                        <td class="text-center">@{{ item.DATA | toDate | date:'dd/MM/yy' : '+0' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div ng-if="vm.Cota.SELECTED.TIPO == 'GGF'"
                class="table-ec"
                style="
                    max-height: calc(100vh - 189px);
                    min-height: 74px;
                    overflow: scroll;
                    height: auto;           
                "
                 >
                <table class="table table-bordered table-hover table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Descrição</th>
                        <th class="text-right">Cota</th>
                        <th class="text-right" ttitle="Crédito">Créd.</th>
                        <th class="text-right" ttitle="Valor utilizado">Util.</th>
                        <th class="text-right" ttitle="Percentual utlizado">% Util.</th>
                        <th class="text-right" ttitle="Valor - Utilizado">Saldo</th>
                        <th class="text-center">Acões</th>
                    </tr>
                    </thead>
                    <tbody class="itens">
                    <tr ng-repeat="item in vm.Cota.SELECTED.COTA_GGF" data-id="@{{ item.ID }}">
                        <td style="min-width: 350px" autotitle>@{{ item.DESCRICAO }}</td>           
                        <td class="text-right no-break">R$ @{{ item.VALOR_COTA | number: 2 }}</td>
                        <td class="text-right no-break">R$ @{{ item.VALOR_CREDITO | number: 2 }}</td>
                        <td class="text-right no-break">R$ @{{ item.VALOR_UTILIZADO | number: 2 }}</td>
                        <td class="text-right no-break">@{{ item.PERCENTUAL_UTILIZADO | number: 2 }}%</td>
                        <td class="text-right no-break">R$ @{{ item.SALDO | number: 2 }}</td>
                        <td class="text-center">

                            <button 
                                type="button" 
                                class="btn btn-primary btn-xs" 
                                ng-click="vm.CotaGgf.consultarDetalhe(item)"
                            >
                                <span class="glyphicon glyphicon-info-sign"></span> Detalhar
                            </button>                          
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            
            <div ng-if="vm.Cota.SELECTED.TIPO == 'INV'"
                class="table-ec"
                style="
                    max-height: calc(100vh - 189px);
                    min-height: 74px;
                    overflow: scroll;
                    height: auto;           
                "
                 >
                <table class="table table-bordered table-hover table-striped table-condensed">
                    <thead>
                        <tr>
                            <th ttitle="Centro de Custo do lançamento">C. Custo Lançamento</th>
                            <th class="text-right">Família</th>
                            <th class="text-right" ttitle="Valor utilizado">Valor</th>
                            <th class="text-center">Acões</th>
                        </tr>
                    </thead>
                    <tbody class="itens">
                        <tr ng-repeat="item in vm.Cota.SELECTED.COTA_AJUSTE_INVENTARIO">
                            <td>@{{ item.CCUSTO_MASK }} - @{{ item.CCUSTO_DESCRICAO }}</td>
                            <td>@{{ item.FAMILIA_ID | lpad : [3,0] }} - @{{ item.FAMILIA_DESCRICAO }}</td>
                            <td class="text-right">R$ @{{ item.VALOR | number : 2 }}</td>
                            <td class="text-center">

                                <button 
                                    type="button" 
                                    class="btn btn-primary btn-xs" 
                                    ng-click="vm.CotaGgf.consultarDetalhe(item,'inv')"
                                >
                                    <span class="glyphicon glyphicon-info-sign"></span> Detalhar
                                </button>                          
                            </td>                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

@overwrite
