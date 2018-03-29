<div role="tabpanel" class="tab-pane fade" id="tab-container-liberar">
    <ul class="">
        <li style="display: inline-flex;">
            <button ng-disabled="!(vm.Talao.TALOES_LIBERAR_LISTA.length > 0)" ng-click="vm.Talao.liberar()" type="button" class="btn btn-success">
                <span class="glyphicon glyphicon-ok"></span>
                Confirmar
            </button>
        </li>
        <li style="display: inline-flex;">
            <button ng-disabled="!(vm.Talao.TALOES_LIBERAR.length > 0)" ng-click="vm.Talao.liberarLimpar()" type="button" class="btn btn-default">
                <span class="fa fa-trash"></span>
                Limpar Lista
            </button>
        </li>
    </ul>

    <form class="form-inline" ng-submit="vm.Talao.consultarBarras()">
        <div class="form-group">
            <label for="consulta-descricao">Código de Barras:</label>
            <div class="input-group">
                <input 
                    type="password" 
                    class="form-control input-maior input-codigo-barras" 
                    form-validade="true"
                    autocomplete="new-password"
                    pattern=".{12,12}"
                    required
                    autofocus
                    ng-model="vm.Talao.BARRAS"/>
                <button 
                    type="submit" 
                    class="input-group-addon btn-filtro search-button" 
                    tabindex="-1" 
                    >
                    <span class="fa fa-search"></span>
                </button>          
            </div>        
        </div>    
    </form>
    <style>
        #table-itens td {
            font-size: 16px;
        }

        #table-itens2 td {
            font-size: 12px;
        }

        .item-stts {
            height: 15px;
            width: 15px;
            border-radius: 8px;
            border: 1px solid;
        }

        .t-status:before {
            border-radius: 10px !important;
            width: 20px !important;
            height: 20px !important;
        }
        
        .item-stts-1:before { 
            background-color: rgb(217, 83, 79) !important;
        }

        .item-stts-2:before {
            background-color: rgb(68, 157, 68) !important;
        }
        
        .item-stts-3:before {
            background-color: rgb(51, 122, 183) !important;
        }

    </style>
    <div id="table-itens" class="table-ec table-scroll" style="height: calc(100vh - 355px);">
        <table class="table table-bordered table-hover table-striped table-middle">
            <thead>
                <tr>
                    <th class="text-center"><span ttitle="Status da Conferencia">Stts</span></th>
                    <th>Remessa</th>
                    <th>Talão</th>
                    <th>Produto</th>
                    <th class="text-center">Tam.</th>
                    <th class="text-right">Qtd.</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    tabindex="0"
                    ng-repeat="item in vm.Talao.TALOES_LIBERAR"
                    >
                    <td class="t-status item-stts-@{{ item.STATUS }}"></td>
                    <td>@{{ item.REMESSA }}</td>
                    <td>@{{ item.REMESSA_TALAO_ID }}</td>
                    <td>
                        <a tabindex="-1" title="Clique aqui para consultar o estoque deste produto" href="{{ url('/_15060?PRODUTO_ID=') }}@{{ item.PRODUTO_ID }}&LOCALIZACAO_ID=@{{ item.LOCALIZACAO_ID }}" target="_blank">@{{ item.PRODUTO_ID }}</a>
                        - 
                        @{{ item.PRODUTO_DESCRICAO }}
                    </td>
                    <td class="text-center">@{{ item.TAMANHO_DESCRICAO }}</td>
                    <td class="text-right text-lowercase">@{{ item.QUANTIDADE_PROJETADA | number : 5 }} @{{ item.UM }}</td>
                </tr>                
            </tbody>
        </table>
    </div>
</div>