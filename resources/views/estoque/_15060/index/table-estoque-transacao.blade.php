<fieldset>
    <legend>Transações de Estoque</legend>

    <div class="resize resize-transacao table-lc">
        <div class="table-ec">
            <table class="table table-striped table-bordered table-hover table-condensed table-transacao arrow-nav">
                <thead>
                    <tr>
                        <th class="" title="Id da Transação">Transação</th>
                        <th class="">Documento</th>
                        <th class="">Talão</th>
                        <th class="" t-title="Data do Estoque">Data</th>
                        <th class="" t-title="Data/Hora em que a operação foi realizada">Data/Hora</th>
                        <th class="" title="Tamanho">Tam.</th>
                        <th class="text-right" title="Quantidade">Qtd.</th>
                        <th class="">Tipo</th>
                        <th class="" title="Operação">Op.</th>
                        <th class="">Usuário</th>
                        <th class="" ttitle="Centro de Custo">C. Custo</th>
                        <th class="">Observações</th>
                        <th class="" ttitle="Vínculo Origem">Vinc. Orig.</th>
                    </tr>
                </thead>                    
                <tbody vs-repeat vs-scroll-parent=".table-ec">
                    <tr
                        ng-repeat="item in vm.EstoqueLocalizacao.selected.ESTOQUE_TRANSACOES">
                        <td class="wid-transacao">@{{ item.ID }}</td>
                        <td class="wid-documento">@{{ item.DOCUMENTO }}</td>
                        <td class="wid-talao">@{{ item.TALAO_ID }}</td>
                        <td class="wid-data">@{{ item.DATA | parseDate | date:'dd/MM/yy' : '+0' }}</td>
                        <td class="wid-quantidade">@{{ item.DATAHORA | parseDate | date:'dd/MM/yy HH:mm:ss' : '-3' }}</td>
                        <td class="wid-tamanho" title="Grade @{{ item.GRADE_ID }} Id @{{ item.TAMANHO }}">@{{ item.TAMANHO_DESCRICAO }}</td>
                        <td class="wid-quantidade text-right">@{{ item.QUANTIDADE || 0 | number: 5 }} @{{ item.UM }}</td>
                        <td class="wid-tipo" title="@{{ item.TIPO == 'E' ? 'Entrada' : '' }}@{{ item.TIPO == 'S' ? 'Saída' : '' }}">@{{ item.TIPO }}</td>
                        <td class="wid-operacao" title="@{{ item.OPERACAO }} - @{{ item.OPERACAO_DESCRICAO }}">@{{ item.OPERACAO }}</td>
                        <td class="wid-usuario ellipsis" autotitle>@{{ item.USUARIO_ID + ' - ' + item.USUARIO_DESCRICAO }}</td>
                        <td class="wid-observacao ellipsis" autotitle>
                            <span style="float: left; width: 65px;" class="ng-binding">@{{ item.CCUSTO_MASK }}</span>
                            @{{ item.CCUSTO_DESCRICAO }}
                        </td>
                        <td class="wid-observacao ellipsis" autotitle>
                            <b style="color: red">@{{ item.CONFERENCIA == 1 ? item.CONFERENCIA_DESCRICAO : '' }}</b> 
                            <b style="color: green">@{{ item.CONFERENCIA == 2 ? item.CONFERENCIA_DESCRICAO : '' }}</b> 
                            @{{ item.OBSERVACAO }}
                        </td>
                        <td class="wid-vinculo ellipsis" autotitle>
                            <span ng-if="item.TABELA_ID > 0">
                                @{{ item.TABELA_ID }} - @{{ item.TABELA_DESCRICAO }}
                            </span>
                        </td>
                        
                    </tr>  
                </tbody>
            </table>
        </div>
    </div>
    
</fieldset>