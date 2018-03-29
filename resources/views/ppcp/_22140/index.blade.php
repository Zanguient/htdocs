@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22140.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/22140.css') }}" />
@endsection

@section('conteudo')


<div id="AppCtrl" ng-controller="Ctrl as vm" ng-cloak>
    
    <ul class="nav nav-tabs">
        <li class="active">
            <a 
                data-toggle="tab" 
                href="#tab-pane-home">
                Painel Principal
            </a>
        </li>
        <li>
            <a 
                data-toggle="tab" 
                href="#tab-pane-calendario"
                ng-click="vm.Gp.consultar()">
                Calandário
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="tab-pane-home" class="tab-pane fade in active">

            <form class="form-inline" ng-submit="vm.Estacao.reprocessar()">
                <ul class="list-inline acoes">
                    <li>
                        <button 
                            type="submit" 
                            class="btn btn-success" 
                            ng-disabled="( vm.Estacao.SELECTEDS.length <= 0)"
                            >
                            <span class="glyphicon glyphicon-ok"></span>
                            Reprocessar
                        </button>
                    </li>
                </ul>

                <div class="form-group">
                    <label for="data">Data Base para Reprocessamento:</label>
                    <input 
                        type="datetime-local" 
                        class="form-control input-datetime" 
                        min="2000-01-01T00:00:00" 
                        max="3000-12-31T00:00:00"
                        ng-model="vm.Filtro.DATAHORA" 
                        ng-required="vm.Filtro.AGORA == false"
                        ng-disabled="vm.Filtro.AGORA == true"
                        >
                </div> 
                <div class="form-group">
                    <label for="data">Data Base Agora?</label>
                    <input type="checkbox" class="check-now" ng-model="vm.Filtro.AGORA" />
                </div>
                
                <div class="form-group">
                    <label for="data">Ordenar por data da remessa?</label>
                    <input type="checkbox" class="check-now" ng-init="vm.Filtro.ORDEM_DATA_REMESSA = false" ng-model="vm.Filtro.ORDEM_DATA_REMESSA" />
                </div>

                <div class="form-group">
                    <label for="data">Reprocessar Talões Em Produção?</label>
                    <input type="checkbox" class="check-now" ng-model="vm.Filtro.EM_PRODUCAO" />
                </div>        

                <fieldset>
                    <div class="agrupamento-familia">


                        <div class="panel panel-primary panel-table">                                                              
                            <div class="panel-heading panel-title">                                                                      
                                <div class="titulo-lista">  
                                    <span>Reprocessamento de Tempos</span>
                                </div>                                                                                           
                            </div>                                                                                               
                            <div class="panel-body">           
                                <div class="panel-group accordion" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div
                                        ng-repeat="familia in vm.Familia.DADOS | orderBy: 'FAMILIA_DESCRICAO'"
                                        class="panel panel-default">
                                        <div 
                                            class="panel-heading" 
                                            role="tab" 
                                            id="heading@{{ $index }}">
                                            <div class="check"  ng-click="vm.Familia.select(familia)">
                                                <i class="check fa" ng-class="familia.SELECTED ? 'fa-check-square-o' : 'fa-square-o'"></i>
                                            </div>
                                            <a role="button" data-toggle="collapse" href="#collapse@{{ $index }}" aria-controls="collapse@{{ $index }}">

                                                <span class="descricao familia">
                                                    FAMÍLIA: @{{ familia.FAMILIA_ID | lpad : [3,'0'] }} - @{{ familia.FAMILIA_DESCRICAO }}
                                                </span>
                                            </a>
                                        </div>
                                        <div id="collapse@{{ $index }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading@{{ $index }}">
                                            <div class="panel-body">

                                                {{-- Nível Gp - INÍCIO --}}
                                                <div class="gp-container">
                                                    <div class="panel-group accordion@{{ familia.FAMILIA_ID }}" id="accordion@{{ familia.FAMILIA_ID }}" role="tablist" aria-multiselectable="true">
                                                        <div
                                                            ng-repeat="gp in familia.GP | orderBy:'GP_DESCRICAO'" 
                                                            class="panel panel-default">
                                                            <div
                                                                class="panel-heading" 
                                                                role="tab" 
                                                                id="heading@{{ familia.FAMILIA_ID }}-@{{ $index }}">
                                                                <div class="check"  ng-click="vm.Gp.select(gp)">
                                                                    <i class="check fa" ng-class="gp.SELECTED ? 'fa-check-square-o' : 'fa-square-o'"></i>
                                                                </div>
                                                                <a class="accordion" role="button" data-toggle="collapse" href="#collapse@{{ familia.FAMILIA_ID }}-@{{ $index }}" aria-controls="collapse@{{ familia.FAMILIA_ID }}-@{{ $index }}">
                                                                    <span class="descricao">
                                                                        GP: @{{ gp.GP_DESCRICAO }}
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <div id="collapse@{{ familia.FAMILIA_ID }}-@{{ $index }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading@{{ familia.FAMILIA_ID }}-@{{ $index }}">
                                                                <div class="panel-body">


                                                                    {{-- Nível Estação - INÍCIO --}}
                                                                    <div class="estacao-container">
                                                                        <div class="panel-group accordion@{{ gp.GP_ID }}" id="accordion@{{ gp.GP_ID }}" role="tablist" aria-multiselectable="true">
                                                                            <div
                                                                                ng-repeat="estacao in gp.ESTACAO | orderBy:'ESTACAO_DESCRICAO'" 
                                                                                class="panel panel-default">
                                                                                <div
                                                                                    class="panel-heading" 
                                                                                    role="tab" 
                                                                                    id="heading@{{ gp.GP_ID }}-@{{ $index }}">
                                                                                    <div class="check"  ng-click="vm.Estacao.select(estacao)">
                                                                                        <i class="check fa" ng-class="estacao.SELECTED ? 'fa-check-square-o' : 'fa-square-o'"></i>
                                                                                    </div>
                                                                                    <a class="accordion" role="button" data-toggle="collapse" href="#collapse@{{ gp.GP_ID }}-@{{ $index }}" aria-controls="collapse@{{ gp.GP_ID }}-@{{ $index }}">
                                                                                        <span class="descricao">
                                                                                            Estação: @{{ estacao.ESTACAO_DESCRICAO }}
                                                                                        </span>
                                                                                    </a>
                                                                                </div>
                                                                            </div>	
                                                                        </div>
                                                                    </div>
                                                                    {{-- Nível Estação - FIM --}}                                                        


                                                                </div>
                                                            </div>
                                                        </div>	
                                                    </div>
                                                </div>
                                                {{-- Nível GP - FIM --}}

                                            </div>
                                        </div>
                                    </div>	
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>      
        </div>
        <div id="tab-pane-calendario" class="tab-pane fade">

            <form class="form-inline" ng-submit="vm.Gp.calendarioAtualizar()">    

                <ul class="list-inline acoes">
                    <li>
                        <button 
                            type="submit" 
                            class="btn btn-success" 
                            >
                            <span class="glyphicon glyphicon-ok"></span>
                            Alterar Canlendário
                        </button>
                    </li>
                </ul>
                
                <style>
                    #form-filtro {
                        background: rgba(221,221,221,.33);
                        padding: 2px 10px 7px;
                        border-radius: 5px;
                    }
                </style>
                <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">
      
                    <div class="form-group">
                        <label title="Data para produção da remessa">Data:</label>
                        <div class="input-group">
                            <input type="date" ng-model="vm.Gp.DATA" toDate class="form-control" required />
                            <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                                <span class="fa fa-close"></span>
                            </button>
                        </div>
                    </div>
                    
                    <div 
                        class="form-group"
                        style="
                            margin-top: 2px;
                            margin-bottom: 0;
                        ">
                        <label>Horario:</label>        
                        <div style="
                            padding: 0 0 0 10px;
                            border-radius: 6px;
                            background: rgb(226, 226, 226);
                        ">
                            
                            <label style="margin-right: 10px; display: inline-block; position: relative">
                                <input 
                                    ng-init="vm.Gp.HORARIO = '00:00-01:30;02:31-11:30;12:31-16:48;21:51-23:59;'"
                                    type="radio" 
                                    style="top: 5px; position: initial" 
                                    ng-model="vm.Gp.HORARIO" 
                                    value="00:00-01:30;02:31-11:30;12:31-16:48;21:51-23:59;">
                                <span style="vertical-align: super;"><b>Expediente completo</b> - 00:00-01:30;02:31-11:30;12:31-16:48;21:51-23:59;</span>
                            </label>
                            
                            <label style="margin-right: 10px; display: inline-block; position: relative">
                                <input 
                                    type="radio" 
                                    style="top: 5px; position: initial" 
                                    ng-model="vm.Gp.HORARIO" 
                                    value="07:20-11:30;12:31-16:48;21:51-23:59;">
                                <span style="vertical-align: super;"><b>Segunda normal</b> - 07:20-11:30;12:31-16:48;21:51-23:59;</span>
                            </label>
                            
                            <label style="margin-right: 10px; display: inline-block; position: relative">
                                <input 
                                    type="radio" 
                                    style="top: 5px; position: initial" 
                                    ng-model="vm.Gp.HORARIO" 
                                    value="00:00-01:30;02:31-07:00;">
                                <span style="vertical-align: super;"><b>Sábado normal</b> - 00:00-01:30;02:31-07:00;</span>
                            </label>
                            
                            <label style="margin-right: 10px; display: inline-block; position: relative">
                                <input 
                                    type="radio" 
                                    style="top: 5px; position: initial" 
                                    ng-model="vm.Gp.HORARIO" 
                                    value="personalizado">
                                <span style="vertical-align: super;"><b>Personalizado</b></span>
                            </label>
                              
                              
                        </div>
                    </div>          
                    
                    <div ng-if="vm.Gp.HORARIO == 'personalizado'" class="form-group">
                        <label>Horário Personalizado:</label>

                        <input type="text" ng-model="vm.Gp.HORARIO_PERSONALIZADO" class="form-control" />
                    </div>                    
                </div>
                
            </form>
            
            <div class="table-ec">
                <table class="table table-middle table-bordered table-no-break table-striped table-scroll">
                    <thead>
                        <tr>
                            <th></th>
                            <th>GP</th>
                            <th>Família</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="gp in vm.Gp.GPS">
                            <td class="text-center" ng-click="gp.CHECKED = gp.CHECKED == '1' ? '0' : '1'">
                                <i 
                                    class="check fa" ng-class="gp.CHECKED == '1' ? 'fa-check-square-o' : 'fa-square-o'"
                                    style="
                                        font-size: 17px;
                                        top: 2px;
                                        left: 4px;
                                    "></i>
                            </td>
                            <td>@{{ gp.GP_ID | lpad: [3,0] }} - @{{ gp.GP_DESCRICAO }}</td>
                            <td>@{{ gp.FAMILIA_ID | lpad: [4,0] }} - @{{ gp.FAMILIA_DESCRICAO }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>    

    
</div>
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_22140.app.js') }}"></script>
@append