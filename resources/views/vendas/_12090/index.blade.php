@extends('master')

@section('titulo')
    {{ Lang::get('vendas/_12090.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/12090.css') }}" />
@endsection

@section('conteudo')
<div class="main-ctrl" style="display : none" ng-controller="Ctrl as vm" ng-cloak>
    
    
@php $empresa_id = isset($_GET['EMPRESA_ID']) ? $_GET['EMPRESA_ID'] : ''
 
    <input type="hidden" ng-init="vm.Empresa.SELECTED.EMPRESA_ID = '{{ $empresa_id }}'" />
    
	<ul class="list-inline acoes">    
		<li>
            <a href="" class="btn btn-primary btn-incluir" data-hotkey="f6" disabled>
                <span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.incluir') }}
            </a>
        </li>  
	</ul>    
        
    <fieldset>
        <legend>Clientes Cadastrados</legend>
            <form class="form-inline" ng-submit="vm.Empresas.consultar(true)"> 
                <style>
                    #form-filtro {
                        background: rgba(221,221,221,.33);
                        padding: 2px 10px 7px;
                        border-radius: 5px;
                    }
                </style>
                <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">

                    <div class="consulta-representante"></div>
                    <div class="form-group">
                        <label>Filtro</label>
                        <input 
                            type="text" 
                            ng-model="vm.Empresas.FILTRO.FILTRO" 
                            ng-change="vm.Empresas.virifyChange(vm.Empresas.FILTRO.FILTRO,'@{{ vm.Empresas.FILTRO.FILTRO }}')" 
                            class="form-control input-maior" 
                            form-validade="true"
                            placeholder="Nome Fantasia; Razão Social; CPNJ/CPF; UF; Cidade Id" />
                    </div>
                    <div class="form-group">
                        <label>Status:</label>
                        <select ng-model="vm.Empresas.FILTRO.STATUS" ng-change="vm.Empresas.virifyChange(vm.Empresas.FILTRO.STATUS,'@{{ vm.Empresas.FILTRO.STATUS }}')" class="form-control">
                            <option value="">Todos</option>
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div> 
                    <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter" style="vertical-align: text-top;top: 2px;">
                        <span class="glyphicon glyphicon-filter"></span> Filtrar
                    </button>                    
                </div>
            </form>
            <style>
                
                .t-status.status-0:before {
                    background-color: rgb(217, 83, 79)!important;
                }
                
                .t-status.status-1:before {
                    background-color: rgb(68, 157, 68)!important;
                }
            </style>
            <div class="table-ec" style="height: calc(100vh - 265px); min-height: 270px;">
                <table class="table table-bordered table-striped table-hover table-scroll table-middle table-condensed">
                    <col width="0.5%">
                    <col width="0.5%">
                    <col width="0.5%">
                    <col width="1%">
                    <col width="1%">
                    <col width="7%">
                    <col width="5%">
                    <col width="3%">
                    <col width="3%">
                    <col width="1%">
                    <col width="3%">
                    <col width="5%">
                    <thead>
                        <tr>
                            <th ttitle="Status Geral da Empresa" class="text-center">Stts</th>
                            <th ttitle="Status para Novos Pedidos" class="text-center">Ped.</th>
                            <th ttitle="Status para emissão de Documentos Fiscais" class="text-center">NF</th>
                            <th>Sup.</th>
                            <th>Rep.</th>
                            <th>Empresa</th>
                            <th>Nome Fantasia</th>
                            <th>CNPJ/CPF</th>
                            <th>Ins. Estadual</th>
                            <th>UF</th>
                            <th>Cidade</th>
                            <th>Conta Principal</th>
                        </tr>
                    </thead>
                    <tbody infinite-scroll="vm.Empresas.getMoreData()" infinite-scroll-container='".table-ec"' infinite-scroll-disabled='vm.Empresas.AJAX_LOCKED'  infinite-scroll-distance='3'>
                        <tr 
                            ng-repeat="item in vm.Empresas.DADOS"
                            ng-focus="vm.Empresas.pick(item)"
                            ng-click="vm.Empresas.pick(item); vm.Empresa.open()"
                            ng-class="{'selected' : vm.Empresas.SELECTED == item }"  
                            ng-keydown="$event.key == 'Enter' && vm.Empresa.open()"
                            tabindex="0"
                            >
                            <td class="t-status status-@{{ item.EMPRESA_STATUS }}" title="@{{ item.EMPRESA_STATUS_DESCRICAO }}"></td>
                            <td class="t-status status-@{{ item.EMPRESA_BLOQUEIA_PEDIDO == '1' ? '0' : '1' }}"></td>
                            <td class="t-status status-@{{ item.EMPRESA_BLOQUEIA_NOTAFISCAL == '1' ? '0' : '1' }}"></td>
                            <td>@{{ item.EMPRESA_SUPERVISOR_ID | lpad : [3,0] }}</td>
                            <td>@{{ item.EMPRESA_REPRESENTANTE_ID | lpad : [5,0] }}</td>
                            <td>@{{ item.EMPRESA_ID }} - @{{ item.EMPRESA_RAZAO_SOCIAL }}</td>
                            <td>@{{ item.EMPRESA_NOMEFANTASIA }}</td>
                            <td>@{{ item.EMPRESA_CNPJ_MASK }}</td>
                            <td>@{{ item.EMPRESA_IE }}</td>
                            <td>@{{ item.EMPRESA_UF }}</td>
                            <td>@{{ item.EMPRESA_CIDADE }}</td>
                            <td>@{{ item.CONTA_PRINCIPAL_ID | lpad: [5,0] }} - @{{ item.CONTA_PRINCIPAL_NOMEFANTASIA }}</td>
                        </tr>                        
                    </tbody>
                </table>
            </div>
        </form>  
    </fieldset>  
    @include('vendas._12090.modal-empresa.body') 
</div>


    
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_12090.js') }}"></script>
    <script src="../build/assets/images/html2canvas.min.js"></script> 
    <script src="../build/assets/images/jspdf.min.js"></script>    
@append
