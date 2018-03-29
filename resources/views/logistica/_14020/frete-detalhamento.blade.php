<input type="hidden" ng-init="vm.FRETE_ID = '{{ isset($id) ? $id : '' }}'"/>
<input type="hidden" ng-init="vm.Frete.ORIGEM = '{{ isset($origem) ? $origem : '' }}'"/>
<input type="hidden" ng-init="vm.Frete.ORIGEM_ID = '{{ isset($origem_id) ? $origem_id : '' }}'"/>
<input type="hidden" ng-init="vm.Frete.CALCULAR = '{{ isset($calcular) ? $calcular : '' }}'"/>
<div class="row">
        <div class="form-group">
            <label>Id:</label>
            <input 
                type="text"
                class="form-control input-menor"
                readonly
                value="@{{ vm.Frete.DADOS.ID }}"
                disabled>
        </div>         
        
        <div class="form-group">
            <label>Origem:</label>
            <input 
                type="text"
                class="form-control"
                disabled
                value="@{{ vm.Frete.ORIGEM }} - @{{ vm.Frete.ORIGEM_ID }}">
        </div>         
        
        <script>
            $(document).on('click', '#frete-comparar', function(){
                var url = $(this).attr('data-url');
                winPopUp(url,'frete-comparar',{width:1115,height:815});
            });
        </script>
     
        <div style="display: inline" class="consulta-frete-transportadora-simulador"></div>       
        
        <div class="form-group">
            <label>Classificação:</label>
            <input 
                type="text"
                class="form-control"
                disabled
                value="@{{ vm.Frete.DADOS.TRANSPORTADORA_CLASSIFICACAO }}">
        </div>          
        
        <div class="form-group">
            <label>Prazo Entrega:</label>
            <input 
                style="width: 100px !important;"
                type="text"
                class="form-control input-menor text-right"
                disabled
                value="@{{ vm.Frete.DADOS.CIDADE_PRAZO_ENTREGA || 0 | number : 2 }} dias">
        </div>          
        
        <a style="margin-top: 20px;" 
           class="btn btn-primary" 
           id="frete-comparar"
           data-url="/_14020/comparar?CIDADE_ID=@{{ vm.Frete.DADOS.CIDADE_ID }}&ITENS=@{{ vm.Frete.DADOS.ITENS }}"
           href>Comparar Transportadoras</a>
    </div>
    <div class="row">
             
        <div class="form-group">
            <label>Cliente:</label>
            <input style="width: 500px !important;" 
                type="text"
                class="form-control"
                readonly
                value="@{{ vm.Frete.DADOS.CLIENTE_ID }} - @{{ vm.Frete.DADOS.CLIENTE_RAZAOSOCIAL }} / @{{ vm.Frete.DADOS.UF }}">
        </div>         
        
     
        <div class="form-group">
            <label>Cidade:</label>
            <input
                type="text"
                class="form-control"
                readonly
                style="width: 500PX;"
                value="@{{ vm.Frete.DADOS.CIDADE_AGRUPAMENTO_DESCRICAO ==  vm.Frete.DADOS.CIDADE_DESCRICAO ?  vm.Frete.DADOS.CIDADE_DESCRICAO : vm.Frete.DADOS.CIDADE_AGRUPAMENTO_DESCRICAO + ' / ' + vm.Frete.DADOS.CIDADE_DESCRICAO }}">
        </div>         
     
        <div class="form-group">
            <label>Período da Fonte de Dados:</label>
            <input
                type="text"
                class="form-control"
                readonly
                value="@{{ vm.Frete.DADOS.PERIODO }}">
        </div>         
           
        
    </div>
    <div class="row">
        <div class="form-group">
            <label>Valor Simulação (R$):</label>
            <input
                type="text"
                class="form-control text-right"
                readonly
                value="@{{ vm.Frete.DADOS.VALOR_FINAL | number : 4 }}">
        </div>         
        
        <div class="form-group">
            <label ttitle="Percentual do frete simulado em relação ao valor da carga">% Simulação:</label>
            <input
                type="text"
                class="form-control text-right input-menor"
                readonly
                value="@{{ vm.Frete.DADOS.VALOR_FINAL / vm.Frete.DADOS.VALOR_TOTAL * 100 | number : 4 }}">
        </div>         
        
        <div ng-if="vm.Frete.DADOS.ORIGEM == 'CTE' && vm.Frete.DADOS.CTE_TRANSPORTADORA_ID == vm.Frete.DADOS.TRANSPORTADORA_ID" class="form-group">
            <label>Valor CTE (R$):</label>
            <input
                type="text"
                class="form-control text-right"
                readonly
                value="@{{ vm.Frete.DADOS.VALOR_DOC | number : 4 }}">
        </div> 
        
        <div ng-if="vm.Frete.DADOS.ORIGEM == 'CTE' && vm.Frete.DADOS.CTE_TRANSPORTADORA_ID == vm.Frete.DADOS.TRANSPORTADORA_ID" class="form-group">
            <label ttitle="Percentual do CTE em relação ao valor da carga">% CTE:</label>
            <input
                type="text"
                class="form-control text-right input-menor"
                readonly
                value="@{{ vm.Frete.DADOS.VALOR_DOC / vm.Frete.DADOS.VALOR_TOTAL * 100 | number : 4 }}">
        </div>           
    </div>
    
    <div class="row">
        <fieldset style="float: left;margin-right: 10px;" ng-repeat="item in vm.Frete.DADOS.COMPOSICOES">
            <legend>@{{ item.DESCRICAO }}</legend>

            <div class="table-ec">
                <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th class="text-right" ttitle="Valor gerado a partir da simulação">Val.Sim.</th>
                            <th ng-if="vm.Frete.DADOS.ORIGEM == 'CTE' && vm.Frete.DADOS.CTE_TRANSPORTADORA_ID == vm.Frete.DADOS.TRANSPORTADORA_ID" class="text-right" ttitle="Valor do Conhecimento de Transporte Eletrônico">Valor CTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="detalhe in item.DADOS">
                            <td>
                                @{{ detalhe.DESCRICAO }}

                                <span
                                    ng-if="(detalhe.EXPRESSAO != undefined && detalhe.EXPRESSAO.length > 0) || (detalhe.OBSERVACAO != undefined && detalhe.OBSERVACAO.length > 0)"
                                    style="float: right; margin-left: 5px"
                                    class="item-popover glyphicon glyphicon-info-sign" 
                                    data-toggle="popover" 
                                    data-placement="right" 
                                    title="Mais Informações"
                                    data-element-content="#info-detalhe-@{{ detalhe.SEQUENCIA }}"
                                ></span>
                                <div id="info-detalhe-@{{ detalhe.SEQUENCIA }}" style="display: none"> 
                                    <span ng-if="detalhe.OBSERVACAO != undefined && detalhe.OBSERVACAO.length > 0">
                                        Observação: @{{ detalhe.OBSERVACAO }}
                                        <br/><br/>
                                    </span>
                                    <span ng-if="detalhe.EXPRESSAO != undefined && detalhe.EXPRESSAO.length > 0">
                                        Expressão Amigável: <br/><span ng-bind-html="vm.trustedHtml(detalhe.EXPRESSAO)"></span>
                                        <br/><br/>
                                        Expressão numérica: <br/><span ng-bind-html="vm.trustedHtml(detalhe.EXPRESSAO_VALOR)"></span>
                                        <br/>
                                    </span>
                                </div>

                            </td>
                            <td class="text-right">@{{ detalhe.VALOR | number : 4 }}</td>
                            <td ng-if="vm.Frete.DADOS.ORIGEM == 'CTE' && vm.Frete.DADOS.CTE_TRANSPORTADORA_ID == vm.Frete.DADOS.TRANSPORTADORA_ID" class="text-right">@{{ detalhe.VALOR_DOC | number : 4 }}</td>            
                        </tr>
                    </tbody>
                </table>
            </div>               
        </fieldset>
    </div>
    <div class="row">
        <fieldset>
            <legend>Detalhamento dos Itens</legend>

            <div class="table-ec" style="height: calc(100vh - 620px); min-height: 230px;">
                <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                    <thead>
                        <tr gc-order-by="vm.Frete.ITENS_ORDER_BY">
                            <th field="PRODUTO_DESCRICAO">Produto</th>
                            <th field="TAMANHO_DESCRICAO" class="text-center">Tam.</th>
                            <th field="QUANTIDADE*1" class="text-right">Quantidade</th>
                            <th field="VALOR_UNITARIO*1" class="text-right">Valor Unitário</th>
                            <th field="QUANTIDADE*VALOR_UNITARIO*1" class="text-right" ttitle="Valor Unitário * Quantidade">Valor Total</th>
                            <th field="COTA_EMBALAGEM*1" class="text-right" ttitle="Quantidade de itens por volume">Cota Embalagem</th>
                            <th field="QUANTIDADE_VOLUME*1" class="text-right">Qtd. Volumes</th>
                            <th field="CUBAGEM*1" class="text-right" ttitle="Cubagem por Volume">Cubagem</th>
                            <th field="CUBAGEM_TOTAL*1" class="text-right" ttitle="Cubagem * Qtd. Volumes">Cubagem Total</th>
                            <th field="PESO_LIQUIDO*1" class="text-right" ttitle="Peso unitário do produto">Peso Liquido</th>
                            <th field="PESO_LIQUIDO_TOTAL*1" class="text-right" ttitle="Peso Liquido * Quantidade">Peso Liquido Total</th>
                            <th field="PESO_EMBALAGEM*1" class="text-right">Peso Embalagem</th>
                            <th field="PESO_BRUTO*1" class="text-right" ttitle="Peso Liquido Total + Peso Embalagem">Peso Bruto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="detalhe in vm.Frete.DADOS.DETALHES | orderBy: vm.Frete.ITENS_ORDER_BY">
                            <td title="Modelo Id: @{{ detalhe.MODELO_ID }} - Cor Id: @{{ detalhe.COR_ID }}">
                                @{{ detalhe.PRODUTO_ID | lpad: [6,0] }} - @{{ detalhe.PRODUTO_DESCRICAO }}

                                <span
                                    style="float: right; margin-left: 5px"
                                    class="item-popover glyphicon glyphicon-info-sign" 
                                    data-toggle="popover" 
                                    data-placement="top" 
                                    title="Mais Informações"
                                    data-element-content="#info-item-@{{ detalhe.MODELO_ID }}-@{{ detalhe.COR_ID }}-@{{ detalhe.TAMANHO }}"
                                ></span>
                                <div id="info-item-@{{ detalhe.MODELO_ID }}-@{{ detalhe.COR_ID }}-@{{ detalhe.TAMANHO }}" style="display: none"> 

                                    <fieldset>
                                        <legend>Composição da Cubagem</legend>
                                            
                                        <div class="table-ec">
                                            <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                                                <thead>
                                                    <tr>
                                                        <th>Produto</th>
                                                        <th class="text-right">Altura</th>
                                                        <th class="text-right">Largura</th>
                                                        <th class="text-right">Comprimento</th>
                                                        <th class="text-right">Cubagem Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="cubagem in detalhe.DADOS_CUBAGEM">
                                                        <td >@{{ cubagem.CUBAGEM_PRODUTO_ID | lpad: [6,0] }} - @{{ cubagem.CUBAGEM_PRODUTO_DESCRICAO }}</td>                         
                                                        <td class="text-right text-lowercase">@{{ cubagem.CUBAGEM_ALTURA | number : 6 }} m</td>
                                                        <td class="text-right text-lowercase">@{{ cubagem.CUBAGEM_LARGURA | number : 6 }} m</td>
                                                        <td class="text-right text-lowercase">@{{ cubagem.CUBAGEM_COMPRIMENTO | number : 6 }} m</td>
                                                        <td class="text-right text-lowercase">@{{ cubagem.CUBAGEM_TOTAL | number : 6 }} m³</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>   
                                    </fieldset>

                                    <fieldset>
                                        <legend>Composição do Peso da Embalagem</legend>
                                            
                                        <div class="table-ec">
                                            <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                                                <thead>
                                                    <tr>
                                                        <th>Produto</th>
                                                        <th class="text-right">Peso</th>
                                                        <th class="text-right">Fator Calculo</th>
                                                        <th>Fator Calculo Tipo</th>
                                                        <th class="text-right">Peso Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="peso in detalhe.DADOS_PESO">
                                                        <td >@{{ peso.PESO_PRODUTO_ID | lpad: [6,0] }} - @{{ peso.PESO_PRODUTO_DESCRICAO }}</td>                         
                                                        <td class="text-right text-lowercase">@{{ peso.PESO | number : 6 }} kg</td>
                                                        <td class="text-right text-lowercase">@{{ peso.PESO_FATOR_CALCULO | number : 6 }}</td>
                                                        <td>@{{ peso.PESO_FATOR_CALCULO_TIPO }}-@{{ peso.PESO_FATOR_CALCULO_DESCRICAO }}</td>
                                                        <td class="text-right text-lowercase">@{{ peso.PESO_TOTAL | number : 6 }} kg</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>   
                                    </fieldset>
                                    
                                </div>

                            </td>
                            <td class="text-center" title="Tamanho Id: @{{ detalhe.TAMANHO }}">@{{ detalhe.TAMANHO_DESCRICAO }}</td>
                            <td class="text-right text-lowercase" ng-style="{'color': detalhe.QUANTIDADE == 0 ? 'red' : 'initial'}">@{{ detalhe.QUANTIDADE | number : 4 }} @{{ detalhe.UM }}</td>
                            <td class="text-right" ng-style="{'color': detalhe.VALOR_UNITARIO == 0 ? 'red' : 'initial'}">R$ @{{ detalhe.VALOR_UNITARIO | number : 4 }}</td>            
                            <td class="text-right" ng-style="{'color': detalhe.detalhe.VALOR_UNITARIO * detalhe.QUANTIDADE == 0 ? 'red' : 'initial'}">R$ @{{ detalhe.VALOR_UNITARIO * detalhe.QUANTIDADE | number : 4 }}</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': detalhe.COTA_EMBALAGEM == 0 ? 'red' : 'initial'}">@{{ detalhe.COTA_EMBALAGEM | number : 4 }} @{{ detalhe.UM }}</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': detalhe.QUANTIDADE_VOLUME == 0 ? 'red' : 'initial'}">@{{ detalhe.QUANTIDADE_VOLUME | number : 4 }} un</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': detalhe.CUBAGEM == 0 ? 'red' : 'initial'}">@{{ detalhe.CUBAGEM | number : 4 }} m³</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': detalhe.CUBAGEM_TOTAL == 0 ? 'red' : 'initial'}">@{{ detalhe.CUBAGEM_TOTAL | number : 4 }} m³</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': detalhe.PESO_LIQUIDO == 0 ? 'red' : 'initial'}">@{{ detalhe.PESO_LIQUIDO | number : 4 }} kg</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': detalhe.PESO_LIQUIDO_TOTAL == 0 ? 'red' : 'initial'}">@{{ detalhe.PESO_LIQUIDO_TOTAL | number : 4 }} kg</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': detalhe.PESO_EMBALAGEM == 0 ? 'red' : 'initial'}">@{{ detalhe.PESO_EMBALAGEM | number : 4 }} kg</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': detalhe.PESO_BRUTO == 0 ? 'red' : 'initial'}">@{{ detalhe.PESO_BRUTO | number : 4 }} kg</td>            
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Totalizador</td>
                            <td class="text-right text-lowercase" ng-style="{'color': vm.Frete.DADOS.QUANTIDADE == 0 ? 'red' : 'initial'}">@{{ vm.Frete.DADOS.QUANTIDADE | number : 4 }} @{{ vm.Frete.DADOS.UM }}</td>
                            <td class="text-right" ng-style="{'color': vm.Frete.DADOS.VALOR_UNITARIO == 0 ? 'red' : 'initial'}"></td>            
                            <td class="text-right" ng-style="{'color': vm.Frete.DADOS.vm.Frete.DADOS.VALOR_UNITARIO * vm.Frete.DADOS.QUANTIDADE == 0 ? 'red' : 'initial'}">R$ @{{ vm.Frete.DADOS.VALOR_TOTAL | number : 4 }}</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': vm.Frete.DADOS.COTA_EMBALAGEM == 0 ? 'red' : 'initial'}">@{{ vm.Frete.DADOS.COTA_EMBALAGEM | number : 4 }} @{{ vm.Frete.DADOS.UM }}</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': vm.Frete.DADOS.QUANTIDADE_VOLUME == 0 ? 'red' : 'initial'}">@{{ vm.Frete.DADOS.QUANTIDADE_VOLUME | number : 4 }} un</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': vm.Frete.DADOS.CUBAGEM == 0 ? 'red' : 'initial'}"></td>            
                            <td class="text-right text-lowercase" ng-style="{'color': vm.Frete.DADOS.CUBAGEM_TOTAL == 0 ? 'red' : 'initial'}">@{{ vm.Frete.DADOS.CUBAGEM_TOTAL | number : 4 }} m³</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': vm.Frete.DADOS.PESO_LIQUIDO == 0 ? 'red' : 'initial'}"></td>            
                            <td class="text-right text-lowercase" ng-style="{'color': vm.Frete.DADOS.PESO_LIQUIDO_TOTAL == 0 ? 'red' : 'initial'}">@{{ vm.Frete.DADOS.PESO_LIQUIDO_TOTAL | number : 4 }} kg</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': vm.Frete.DADOS.PESO_EMBALAGEM == 0 ? 'red' : 'initial'}">@{{ vm.Frete.DADOS.PESO_EMBALAGEM | number : 4 }} kg</td>            
                            <td class="text-right text-lowercase" ng-style="{'color': vm.Frete.DADOS.PESO_BRUTO == 0 ? 'red' : 'initial'}">@{{ vm.Frete.DADOS.PESO_BRUTO | number : 4 }} kg</td>            
                        </tr>
                    </tfoot>
                </table>
            </div>   
        </fieldset>
<!--        
        <ul class="nav nav-tabs">
            <li ng-click="vm.Cte.FILTRO.TIPO = 'SIMULADOR'">
                <a data-toggle="tab" href="#tab-simulador">Deta</a>
            </li>
            <li class="active" ng-click="vm.Cte.FILTRO.TIPO = 'CTE'">
                <a data-toggle="tab" href="#tab-cte">CT-e</a>
            </li>
            <li ng-click="vm.Cte.FILTRO.TIPO = 'NF'">
                <a data-toggle="tab" href="#tab-nf">NF</a>
            </li>
            <li ng-click="vm.Cte.FILTRO.TIPO = 'PEDIDO'">
                <a data-toggle="tab" href="#tab-pedido">Pedido</a>
            </li>
        </ul>            
        <div class="tab-content">
            <div id="tab-simulador" class="tab-pane fade">
                s
            </div>
            <div id="tab-cte" class="tab-pane fade in active">


                <style>
                    #form-filtro {
                        background: rgba(221,221,221,.33);
                        padding: 2px 10px 7px;
                        border-radius: 5px
                    }

                    #form-filtro .consulta-container {
                        margin-right: initial;
                        margin-bottom: initial
                    }

                    #form-filtro input {
                        width: calc(100% - 27px)!important
                    }

                    #form-filtro .label-checkbox {
                        top: 9px
                    }

                    #form-filtro [type=submit] {
                        margin-top: 16px
                    }    

                    #form-filtro .check-group {
                        padding: 0 0 4px 10px;
                        border-radius: 6px;
                        background: rgb(226, 226, 226);
                        margin-top: -1px;
                    }

                    #form-filtro .check-group .lbl {
                        display: inline-block;
                        margin-right: 10px;
                    }

                    #form-filtro .check-group .lbl input[type="checkbox"], 
                    #form-filtro .check-group .lbl input[type="radio"] {
                        margin-top: 0;
                        margin-bottom: 0;
                        top: 5px;
                        position: relative;
                        width: 20px!important;
                        height: 20px;
                        vertical-align: baseline;
                        box-shadow: none;
                    }

                    #form-filtro .check-group .lbl [checked] ~ span {
                        font-weight: bold;
                    }

                </style>
                <form class="form-inline" ng-submit="vm.Cte.consultar()">
                    <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">


                        <div class="consulta-frete-transportadora"></div>    

                        <div class="form-group">
                            <label>Documento:</label>
                            <div class="input-group check-group" style="width: calc(100%);padding: 0 0 3px 3px;">
                                <input type="text" class="input-control"
                                       style="width: calc(100% - 69px) !important;"
                                       form-validate="true"
                                       ng-model="vm.Cte.FILTRO.DOCUMENTO" 
                                       ng-change="vm.Cte.FILTRO.DOCUMENTO_TODOS = vm.Cte.FILTRO.DOCUMENTO.length > 0 ? false : true"
                                       ng-required="vm.Cte.FILTRO.DOCUMENTO.length > 0"
                                       >

                                <label class="lbl">
                                    <input type="checkbox" 
                                           ng-model="vm.Cte.FILTRO.DOCUMENTO_TODOS" 
                                           ng-init="vm.Cte.FILTRO.DOCUMENTO_TODOS = true">
                                    <span>Todos</span>
                                </label>

                            </div>
                        </div>                    

                        <div class="form-group">
                            <label>Tipo:</label>             
                            <div class="check-group">
                                <label class="lbl">
                                    <input 
                                        type="radio" 
                                        ng-click="vm.Cte.FILTRO.CONSUMO_PERCENTUAL = '< 1'; vm.Cte.FILTRO.DATA_1 = '01.01.1989'; vm.Cte.FILTRO.DATA_2 = '01.01.2500';"
                                        ng-checked="vm.Cte.FILTRO.CONSUMO_PERCENTUAL == '< 1'">
                                    <span>Pendente</span>
                                </label>
                                <label class="lbl">
                                    <input 
                                        type="radio" 
                                        ng-click="vm.Cte.FILTRO.CONSUMO_PERCENTUAL = '>= 1'"
                                        ng-checked="vm.Cte.FILTRO.CONSUMO_PERCENTUAL == '>= 1'">
                                    <span>Completa</span>
                                </label>
                            </div>
                        </div>             


                        <div class="form-group">
                            <label title="Data para produção da remessa">Data Entrada:</label>
                            <div class="check-group" style="width: calc(100%);padding: 3px;">
                                <div class="input-group">
                                    <input type="date" 
                                           class="form-control"
                                           toDate 
                                           max="@{{ vm.Cte.FILTRO.DATA_2 | date: 'yyyy-MM-dd' }}"  required
                                           ng-model="vm.Cte.FILTRO.DATA_1" 
                                           ng-change="vm.Cte.FILTRO.DATA_TODOS = false" 
                                           ng-required="!vm.Cte.FILTRO.DATA_TODOS"
                                           />
                                    <button type="button" class="input-group-addon btn-filtro" tabindex="-1">
                                        <span class="fa fa-close"></span>
                                    </button>
                                </div>
                                <div class="input-group">
                                    <input type="date" 
                                           class="form-control" 
                                           toDate 
                                           required
                                           ng-model="vm.Cte.FILTRO.DATA_2" 
                                           ng-change="vm.Cte.FILTRO.DATA_TODOS = false" 
                                           ng-required="!vm.Cte.FILTRO.DATA_TODOS"
                                           />
                                    <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                                        <span class="fa fa-close"></span>
                                    </button>
                                </div>  
          
                                <label class="lbl" style="margin-top: -1px;">
                                    <input type="checkbox" ng-model="vm.Cte.FILTRO.DATA_TODOS" ng-init="vm.Cte.FILTRO.DATA_TODOS = true">
                                    <span>Todos</span>
                                </label>                            
                            </div>
                        </div>


                        <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
                            <span class="glyphicon glyphicon-filter"></span> Filtrar
                        </button>
                    </div>                
                </form>            

                <div class="table-ec table-scroll" style="height: calc(100vh - 267px); min-height: 250px;">
                    <table class="table table-striped table-bordered table-condensed table-no-break table-middle table-low">
                        <thead>
                            <tr gc-order-by="vm.Cte.ORDER_BY">
                                <th field="NFE*1,NFE_SERIE*1">NF-e</th>
                                <th field="TRANSPORTADORA_RAZAOSOCIAL">Transportadora</th>
                                <th field="DATA_ENTRADA_JS">Dt. Entrada</th>
                                <th field="DATA_EMISSAO_JS">Dt. Emissão</th>
                                <th field="VALOR_TOTAL*1"  class="text-right">Valor Total</th>
                            </tr>
                        </thead>
                        <tbody vs-repeat vs-scroll-parent=".table-ec">
                            <tr ng-repeat="item in vm.Cte.DADOS | orderBy: vm.Cte.ORDER_BY"
                                ng-click="vm.Frete.calcular('CTE',item.TRANSPORTADORA_ID+'|'+item.NFE+'|'+item.NFE_SERIE)"
                                ng-class="{ 'selected' : vm.Cte.SELECTED == item }"
                                ng-focus="vm.Cte.SELECTED = item"
                                tabindex="0">
                                <td title="NF-e Id: @{{ item.ID }}">@{{ item.NFE }}-@{{ item.NFE_SERIE }}</td>
                                <td title="Transportadora Id: @{{ item.TRANSPORTADORA_ID }}">@{{ item.TRANSPORTADORA_CNPJ_MASK }} - @{{ item.TRANSPORTADORA_RAZAOSOCIAL }}</td>
                                <td>@{{ item.DATA_ENTRADA_JS | date: 'dd/MM/yy' }}</td>                            
                                <td>@{{ item.DATA_EMISSAO_JS | date: 'dd/MM/yy' }}</td>                            
                                <td class="text-right">R$ @{{ item.VALOR_TOTAL | number : 2 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>         

            </div>
            <div id="tab-nf" class="tab-pane fade">
                @{{ vm.Frete }}
            </div>
            <div id="tab-pedido" class="tab-pane fade">
                p
            </div>
        </div>        
        -->
    </div>