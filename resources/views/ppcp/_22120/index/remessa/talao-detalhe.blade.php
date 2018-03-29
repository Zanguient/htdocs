<div class="talao-detalhe panel panel-default">
    <div class="panel-heading" role="tab" id="heading@{{ remessa.REMESSA_ID }}-talao-detalhe">
        <a class="accordion" ng-click="$emit('vsRepeatTrigger')" role="button" data-toggle="collapse" href="#collapse@{{ remessa.REMESSA_ID }}-talao-detalhe" aria-controls="collapse@{{ remessa.REMESSA_ID }}-talao-detalhe">
            <span class="descricao">
                Detalhamento do Talão
            </span>
        </a>
        <div class="dropup acoes">
            <button title="Ações" class="btn btn-default toggle" data-toggle="dropdown">
                <span class="fa fa-ellipsis-v"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li class="dropdown-header">Ações do detalhamento</li>
                <li ng-if="{{ $permissaoMenu->ALTERAR }}">
                      <a href ng-click="vm.Acao('desmembrar','Confirma o desmembramento dos talões selecionados?'     ,remessa,'REMESSA_ID','DETALHE')">Desmembrar</a>
                </li>
                <li ng-if="remessa.REMESSA_WEB == 3 && {{ $permissaoMenu->ALTERAR }}">
                      <a href ng-click="vm.Acao('reabrir'   ,'Deseja realmente reabrir os talões selecionados?',remessa,'REMESSA_ID','DETALHE')">Reabrir</a>
                </li>
                <li ng-if="{{ $permissaoMenu->EXCLUIR }}">
                      <a href ng-click="vm.Acao('remover','Confirma a exclusão dos talões detalhados selecionados?',remessa,'REMESSA_ID','DETALHE')">Excluir</a>
                </li>
            </ul>
        </div>
    </div>
    <div id="collapse@{{ remessa.REMESSA_ID }}-talao-detalhe" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading@{{ remessa.REMESSA_ID }}-talao-detalhe">
        <div class="panel-body">

            {{-- Table Talão Detalhado --}}
            <div class="recebe-puxador-comum">
                <div class="talao-consumo table-container">
                    <table class="table table-bordered table-header">
                        <thead>
                            <tr>
                                <th class="wid-status"></th>
                                <th class="wid-status"></th>
                                <th class="wid-talao" title="Código do talão">Talão</th>
                                <th class="wid-talao-detalhe" title="Código do talão detalhado">Id</th>
                                <th class="wid-produto">Produto</th>
                                <th class="wid-tamanho">Tam.</th>
                                <th class="wid-quantidade text-right" title="Quantidade projetada à produzir">Qtd. Proj.</th>
                                <th class="wid-quantidade text-right" title="Quantidade produzida na unidade de medida padrão do produto">Qtd. Prod.</th>
                                <th class="wid-quantidade text-right" title="Quantidade projetada à produzir na unidade de medida padrão do produto">Qtd. Proj. Alt.</th>
                                <th class="wid-quantidade text-right" title="Quantidade produzida na unidade de medida padrão do produto">Qtd. Prod. Alt.</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="scroll-table">
                        <table class="table table-striped table-bordered table-hover table-body">
                            <col class="wid-status"/>
                            <col class="wid-status"/>
                            <col class="wid-talao"/>
                            <col class="wid-talao-detalhe"/>
                            <col class="wid-produto"/>                
                            <col class="wid-tamanho"/>                
                            <col class="wid-quantidade"/>                      
                            <col class="wid-quantidade"/>                      
                            <col class="wid-quantidade"/>                      
                            <col class="wid-quantidade"/>                      
                            <tbody vs-repeat vs-autoresize vs-scroll-parent=".table-container">
                                <tr 
                                    ng-repeat="detalhe in remessa.TALOES_DETALHE | filter: vm.FiltrarTalaoDetalhe"
                                    tabindex="0"
                                    ng-keypress="vm.RemessaKeypress(detalhe,'DETALHE' $event)"
                                    >
                                    <td class="chk" ng-click="vm.selectItemAcao(detalhe,'DETALHE','ID')"><input tabindex="-1" readonly type="checkbox" ng-checked="vm.selectedItemAcao(detalhe,'DETALHE','ID')"></td>
                                    <td class="t-status talao-detalhe-status-@{{ detalhe.STATUS }}"
                                        title="@{{ detalhe.STATUS_DESCRICAO }}"></td>
                                    <td>@{{ detalhe.REMESSA_TALAO_ID }}</td>
                                    <td>@{{ detalhe.ID }}</td>
                                    <td class="cor-amostra"
                                        title="@{{ detalhe.PRODUTO_ID }} - @{{ detalhe.PRODUTO_DESCRICAO }}">
                                        <span
                                            ng-class="{'disabled' : (detalhe.COR_AMOSTRA <= 0)}"
                                            style="background-image: linear-gradient(to right top, @{{ detalhe.COR_AMOSTRA | toColor }} 45% , @{{ detalhe.COR_AMOSTRA2 | toColor }} 55%);"></span>
                                        <span class="descricao">
                                            
                                            <div id="info-talao-detalhe-@{{ detalhe.ID }}" style="display: none">
                                                
                                                <a tabindex="-1" href="{{ url('/_28000/72?MODELO_ID=') }}@{{ detalhe.MODELO_ID }}&COR_ID=@{{ detalhe.COR_ID }}&TAMANHO_DESCRICAO=@{{ detalhe.TAMANHO_DESCRICAO }}&AUTO=1" target="_blank">Ficha Técnica</a><br/>
                                                <a tabindex="-1" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ detalhe.PRODUTO_ID }}" target="_blank">Estoque</a>
                                            </div>                                             
                                            <a 
                                                href
                                                target="_blank"
                                                data-toggle="popover" 
                                                data-placement="top" 
                                                title="Atalhos do Talão"
                                                data-element-content="#info-talao-detalhe-@{{ detalhe.ID }}"
                                                >
                                                    @{{ detalhe.PRODUTO_ID }}
                                            </a> -                                              
                                            @{{ detalhe.PRODUTO_DESCRICAO }}
                                        </span>
                                    </td>
                                    <td title="Cod. Tam.: @{{ detalhe.TAMANHO }} Grade: @{{ detalhe.GRADE_ID }}">@{{ detalhe.TAMANHO_DESCRICAO }}</td>
                                    <td class="text-right">@{{ detalhe.QUANTIDADE | number: 4 }} @{{ detalhe.UM }}</td>
                                    <td class="text-right">@{{ detalhe.QUANTIDADE_PRODUCAO | number: 4 }} @{{ detalhe.UM }}</td>
                                    <td class="text-right">@{{ detalhe.QUANTIDADE_ALTERN | number: 4 }} @{{ detalhe.UM_ALTERNATIVA }}</td>
                                    <td class="text-right">@{{ detalhe.QUANTIDADE_ALTERN_PRODUCAO | number: 4 }} @{{ detalhe.UM_ALTERNATIVA }}</td>
                                </tr>                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>	