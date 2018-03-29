@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11005.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11005.css') }}" />
@endsection

@section('conteudo')

<div ng-controller="Ctrl as vm" ng-cloak>

	<ul class="list-inline acoes">    
		<li>
            <button 
                type="button" 
                class="btn btn-primary btn-incluir" 
                data-hotkey="f6"
                ng-disabled="!{{ userMenu($menu)->INCLUIR }}" 
                ng-click="vm.Parametro.incluir()">
                <span class="glyphicon glyphicon-plus"></span> Incluir
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-warning" 
                data-hotkey="f7"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }} || vm.Parametro.SELECTED.TIPO_ID == undefined || vm.Parametro.ALTERANDO" 
                ng-click="vm.Parametro.alterar()">
                <span class="glyphicon glyphicon-edit"></span>Alterar
            </button>
        </li>                       
		<li>
            <button 
                type="button" 
                class="btn btn-danger" 
                data-hotkey="f8"
                ng-disabled="!{{ userMenu($menu)->EXCLUIR }} || vm.Parametro.SELECTED.UM_ID == undefined || vm.Parametro.ALTERANDO" 
                ng-click="vm.Parametro.excluir()">
                <span class="glyphicon glyphicon-trash"></span> Excluir
            </button>
        </li>                                          
		<li>
            <button 
                type="button" 
                class="btn btn-primary btn-success" 
                data-hotkey="f10"
                ng-disabled="!{{ userMenu($menu)->ALTERAR }}" 
                ng-click="vm.Parametro.confirmar()">
                <span class="glyphicon glyphicon-ok"></span> Confirmar Alterações
            </button>
        </li>                       
	</ul>  
    <form class="form-inline">
        <div class="consulta-parametro-tabela" style="display: inline;"></div>
        
        <button type="button" 
                ng-disabled="vm.Parametro.TABELA == ''"
                ng-if="vm.Parametro.TABELA != 'SISTEMA'" 
                ng-click="vm.Parametro.DADOS = []; vm.ParametroDetalhe.DADOS = []; vm.Parametro.SELECTED = {}; vm.ParametroDetalhe.SELECTED = {}; vm.Parametro.VISUALIZACAO = vm.Parametro.VISUALIZACAO == 2 ? 1 : 2; vm.Parametro.VISUALIZACAO == 2 ? vm.ParametroDetalhe.consultar() : vm.Parametro.consultar()"
                class="btn btn-default" 
                data-hotkey="alt+v" 
                style="display: inline;top: 10px;"
                >
            <span class="fa fa-random"></span> Alternar Visualização
        </button>   
        
    </form>
    
    <div style="height: calc(100vh - 205px); margin-right: 10px;"
         ng-style="{
            width: vm.Parametro.TABELA == 'SISTEMA' ? '100%' : 'calc(100vw - 35vw - 20px)',
            float: vm.Parametro.VISUALIZACAO == 2 ? 'right' : 'left'
        }"
        >
    
        <input type="text" class="form-control input-filter-table" ng-model="vm.Parametro.FILTRO" placeholder="Filtragem rápida..." style="height: 21px;margin-bottom: 2px;" />
        
        <div class="table-ec table-scroll" style="height: calc(100% - 80px);">
            <table class="table table-striped table-bordered table-condensed table-middle table-low">
                <thead>
                    <tr ng-init="vm.Parametro.ORDER_BY = ['TABELA','CONTROLE']" gc-order-by="vm.Parametro.ORDER_BY">
                        <th field="DESCRICAO">Parametro</th>                    
                        <th field="CONTROLE">Ctrl</th>                    
                        <th field="CODIGO">Código</th>                   
                        <th field="GRUPO">Grupo</th>                         
                        <th field="VALOR">Valor Padrão</th>                
                        <th field="VALOR_DEFINIDO">Valor</th>                
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        ng-repeat="item in vm.Parametro.DADOS_RENDER = ( vm.Parametro.DADOS
                            | filter : { EXCLUIDO : false } 
                            | find: {
                                model : vm.Parametro.FILTRO,
                                fields : [    
                                    'ID',
                                    'DESCRICAO',
                                    'CONTROLE',
                                    'CODIGO',                                    
                                    'GRUPO',
                                    'VALOR',
                                    'VALOR_DEFINIDO',
                                    'OBSERVACAO'
                                ]
                            }                    
                            | orderBy : vm.Parametro.ORDER_BY 
                            )"
                        ng-click=" vm.Parametro.VISUALIZACAO != 2 ? vm.Parametro.SELECTED = item : ''; vm.Parametro.TABELA != 'SISTEMA' && vm.Parametro.VISUALIZACAO != 2 ? vm.ParametroDetalhe.consultar(item.ID) : ''"
                        ng-focus="vm.Parametro.SELECTED = item"
                        ng-class="{ 'selected' : vm.Parametro.SELECTED == item }"
                        tabindex="0"
                        >
                        <td>
                            <span>
                                @{{ item.ID || 0 | lpad: [4,0] }} - @{{ item.DESCRICAO }}
                            </span>
                        </td>
                        <td>
                            <span>
                                @{{ item.CONTROLE | lpad: [4,0] }}
                            </span>
                        </td>
                        <td>
                            <span>
                                @{{ item.CODIGO }}
                            </span>
                        </td>
                        <td>
                            <span>
                                @{{ item.GRUPO }}
                            </span>
                        </td>
                        <td class="text-normal">
                            <span>
                                @{{ item.VALOR }}
                            </span>
                        </td>
                        <td class="text-normal">
                            <span>
                                @{{ item.VALOR_DEFINIDO }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="height: 60px">
            <textarea class="form-control" style="width: 100%;height: 100%; resize: none" readonly>Comentário: @{{ vm.Parametro.SELECTED.OBSERVACAO }}</textarea>
        </div>
    </div>
    
    <div style="height: calc(100vh - 205px); width: calc(35vw - 30px); float: left"
         ng-if="vm.Parametro.TABELA !=  'SISTEMA'"
       >
        
        <input type="text" class="form-control input-filter-table" ng-model="vm.ParametroDetalhe.FILTRO" placeholder="Filtragem rápida..." style="height: 21px;margin-bottom: 2px;" />

        <div class="table-ec table-scroll" 
             style="height: calc(100% - 21px);"
             >
            <table class="table table-striped table-bordered table-condensed table-middle table-low">
                <thead>
                    <tr ng-init="vm.ParametroDetalhe.ORDER_BY = ['TABELA_DESCRICAO']" gc-order-by="vm.ParametroDetalhe.ORDER_BY">
                        <th field="TABELA_DESCRICAO">Tabela Descrição</th>                               
                        <th ng-show="vm.Parametro.VISUALIZACAO != 2" field="VALOR">Valor</th>                
                    </tr>
                </thead>
                <tbody>
                    <!--vm.Parametro.TABELA != 'SISTEMA' ? vm.Parametro.consultarDetalhe(item.ID) : ''-->
                    <tr 
                        ng-repeat="item in vm.ParametroDetalhe.DADOS_RENDER = ( vm.ParametroDetalhe.DADOS 
                        | find: {
                            model : vm.ParametroDetalhe.FILTRO,
                            fields : [    
                                'TABELA_ID',
                                'TABELA_DESCRICAO',
                                'VALOR'
                            ]
                        }                                
                        | orderBy : vm.ParametroDetalhe.ORDER_BY )"
                        ng-click="vm.ParametroDetalhe.SELECTED = item; vm.Parametro.VISUALIZACAO == 2 ? vm.Parametro.consultar(item.TABELA_ID) : ''"
                        ng-focus="vm.ParametroDetalhe.SELECTED = item"
                        ng-class="{ 'selected' : vm.ParametroDetalhe.SELECTED == item }"
                        ng-dblclick="{{ userMenu($menu)->INCLUIR }} == 1 && vm.Parametro.alterar()"
                        tabindex="0"
                        >
                        <td>
                            <span>
                                @{{ item.TABELA_ID || 0 | lpad: [4,0] }} - @{{ item.TABELA_DESCRICAO }}
                            </span>
                        </td>
                        <td ng-if="vm.Parametro.VISUALIZACAO != 2" class="text-normal">
                            <span>
                                @{{ item.VALOR }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
    
    @include('admin._11005.index.modal-parametro')
    @include('admin._11005.index.modal-parametro-detalhe')
</div>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11005.js') }}"></script>
@append
