@extends('master')

@section('titulo')
    {{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/27020.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>  
    <fieldset>
        <legend>Modelos Cadastrados</legend>
            <form class="form-inline" ng-submit="vm.Modelo.consultar(true)"> 
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
                            ng-model="vm.Modelo.FILTRO.FILTRO" 
                            ng-change="vm.Modelo.virifyChange(vm.Modelo.FILTRO.FILTRO,'@{{ vm.Modelo.FILTRO.FILTRO }}')" 
                            class="form-control input-maior" 
                            form-validate="true"
                            placeholder="Nome Fantasia; Razão Social; CPNJ/CPF; UF; Cidade Id" />
                    </div>
                    <div class="form-group">
                        <label>Status:</label>
                        <select ng-model="vm.Modelo.FILTRO.STATUS" ng-change="vm.Modelo.virifyChange(vm.Modelo.FILTRO.STATUS,'@{{ vm.Modelo.FILTRO.STATUS }}')" class="form-control">
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
                    <thead>
                        <tr>
                            <th ttitle="Status Geral da Empresa" class="text-center">Stts</th>
                            <th>Modelo</th>
                            <th>Família</th>
                            <th>Matriz</th>
                            <th>Modelo Pai</th>
                            <th class="text-center" ttitle="Visualizar ficha técnica">F. Téc.</th>
                        </tr>
                    </thead>
                    <tbody infinite-scroll="vm.Modelo.consultarMais()" infinite-scroll-container='".table-ec"' infinite-scroll-disabled='vm.Modelo.AJAX_LOCKED'  infinite-scroll-distance='3'>
                        <tr 
                            ng-repeat="item in vm.Modelo.DADOS"
                            ng-focus="vm.Modelo.pick(item)"
                            ng-click="vm.Modelo.pick(item); vm.Empresa.open()"
                            ng-class="{'selected' : vm.Modelo.SELECTED == item }"  
                            ng-keydown="$event.key == 'Enter' && vm.Empresa.open()"
                            tabindex="0"
                            >
                            <td class="t-status status-@{{ item.STATUS }}" title="@{{ item.STATUS_DESCRICAO }}"></td>
                            <td>@{{ item.ID }} - @{{ item.DESCRICAO }}</td>
                            <td>@{{ item.FAMILIA_ID }} - @{{ item.FAMILIA_DESCRICAO }}</td>
                            <td>@{{ item.MATRIZ_ID }} - @{{ item.MATRIZ_DESCRICAO }}</td>
                            <td>@{{ item.MODELO_PAI }} - @{{ item.MODELO_PAI_DESCRICAO }}</td>
                            <td class="text-center">
                                <button 
                                    ng-if="item.PDF_FICHA > 0"
                                    ng-click="vm.Modelo.viewPdf(item.PDF_FICHA)"
                                    type="button" 
                                    class="btn btn-info btn-xs">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
            </div>
        </form>  
    </fieldset>  

</div>

@include('helper.include.view.pdf-imprimir')

@endsection

@section('script')

    <script src="{{ elixir('assets/js/pdf.js') }}"></script>
    <script src="{{ elixir('assets/js/_27020.js') }}"></script>
@endsection