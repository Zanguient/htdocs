@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22170.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/22170.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

  
    
    @include('ppcp._22170.index.panel-destaque')
    @include('ppcp._22170.index.form-filtro')

 
    
    
    <br/>
    <fieldset>
        <legend>
            Indicadores de Eficiência
        </legend>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Meta</th>
                    <th>Qtd. Prod.</th>
                    <th>Efic.</th>
                    <th>Qtd. Def.</th>
                    <th>% Def.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    <br/>
    <fieldset>
        <legend>Talões a Produzir</legend>
        <div class="estacao" style="
        background: rgb(236, 236, 236);
        padding: 4px 10px 11px 10px;
        margin-bottom: 10px;
        ">
            <div class="titulo" style="
                background: rgb(51, 122, 183);
                color: rgb(255, 255, 255);
                box-shadow: 0px 4px 8px 1px rgb(202, 202, 202);
                margin-bottom: 7px;
                text-align: center;
                padding: 3px;
                width: calc(100% + 20px);
                margin-left: -10px;
                margin-top: -4px;
                font-weight: bold;                 
                 ">
                @{{ vm.ConsultaUp.UP_DESCRICAO }} / @{{ vm.ConsultaEstacao.ESTACAO_DESCRICAO }}
            </div>
            <div class="card-container">                      
                <div 
                    ng-click="vm.Talao.pick(talao,'modal-open')"
                    ng-repeat="talao in vm.Talao.FILTERED = (vm.TalaoProduzir.DADOS  | limitTo: 7 | orderBy : ['DATAHORA_INICIO'])"
                    class="card" 
                    data-talao-id="@{{ talao.TALAO_ID }}"
                    tabindex="0"
                    >
                    <div 
                        class="col-1 programacao-status-@{{ talao.PROGRAMACAO_STATUS }}">
                        <div class="modelo">
                            @{{ talao.MODELO_DESCRICAO }}
                        </div>
                        <div class="cor">
                            @{{ talao.COR_DESCRICAO }}
                        </div>
                        <div class="talao">
                            @{{ talao.REMESSA }} / @{{ talao.REMESSA_TALAO_ID }}
                        </div>      
                    </div>
                    <div class="col-2">     
                        <div ng-if="talao.CONSUMO_STATUS == '0'" style="
                            color: red;
                            font-size: 14px;
                            font-weight: bold;
                        "
                        ttitle="Talão com o consumo de <b>COLA</b> pendente">C</div>                        
                        <div ng-if="talao.ESTOQUE_STATUS == '0'" style="
                            color: red;
                            font-size: 14px;
                            font-weight: bold;
                        "
                        ttitle="Talão sem estoque de para consumo">E</div>                        
                        <div ng-if="talao.REMESSA_LIBERADA == '0'" style="
                            color: red;
                            font-size: 14px;
                            font-weight: bold;
                        "
                        ttitle="Remessa bloqueada para produção">B</div>                        
                    </div>
                </div>
            </div>
        </div>
        <ul class="legenda">
            <li>
                <div class="texto-legenda">Fonte:</div>
            </li>
            <li>
                <div class="cor-legenda programacao-status-0"></div>
                <div class="texto-legenda">Não Iniciado</div>
            </li>
            <li>
                <div class="cor-legenda programacao-status-1"></div>
                <div class="texto-legenda">Iniciado/Parado</div>
            </li>
            <li>
                <div class="cor-legenda programacao-status-2"></div>
                <div class="texto-legenda">Em Andamento</div>
            </li>
        </ul>            
        <ul class="legenda">
            <li>
                <div class="texto-legenda">Letra:</div>
            </li>
            <li>
                <div class="cor-legenda" style="
                    color: rgb(255, 0, 0);
                    font-size: 14px;
                    font-weight: bold;
                    margin-top: -1px;     
                ">C</div>
                <div class="texto-legenda"> - Consumo de Cola Pendente</div>
            </li>
            <li>
                <div class="cor-legenda" style="
                    color: rgb(255, 0, 0);
                    font-size: 14px;
                    font-weight: bold;
                    margin-top: -1px;     
                ">E</div>
                <div class="texto-legenda"> - Estoque de Consumo Indisponível</div>
            <li>
                <div class="cor-legenda" style="
                    color: rgb(255, 0, 0);
                    font-size: 14px;
                    font-weight: bold;
                    margin-top: -1px;     
                ">B</div>
                <div class="texto-legenda"> - Remessa bloqueada para produção</div>
            </li>                
            </li>
        </ul>            
    </fieldset>        

    
    @include('ppcp._22170.index.modal-talao')
    @include('ppcp._22170.index.modal-operador')
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_22170.js') }}"></script>
@append
