@php $auto_load              = isset($_GET['AUTO_LOAD'])       ? 1 : 0
@php $def_talao_id           = isset($_GET['TALAO_ID'          ]) ? $_GET['TALAO_ID'          ] : ''
@php $def_estabelecimento_id = isset($_GET['ESTABELECIMENTO_ID']) ? $_GET['ESTABELECIMENTO_ID'] : ''
@php $def_gp_id              = isset($_GET['GP_ID'             ]) ? $_GET['GP_ID'             ] : ''
@php $def_up_id              = isset($_GET['UP_ID'             ]) ? $_GET['UP_ID'             ] : ''
@php $def_estacao            = isset($_GET['ESTACAO'           ]) ? $_GET['ESTACAO'           ] : ''

<button type="button" class="btn btn-xs btn-default btn-toggle-filter" id="filtrar-toggle" data-toggle="collapse" data-target="#form-filtro" aria-expanded="true" aria-controls="form-filtro">
    Filtro<span class="caret"></span>
</button>

<form 
    class="form-inline"
    ng-submit="vm.Filtro.consultar()" 
>
    <input type="hidden" ng-init="vm.TalaoProduzir.FILTRO.ESTABELECIMENTO_ID = '{{ $def_estabelecimento_id }}';"  ng-model="vm.TalaoProduzir.FILTRO.ESTABELECIMENTO_ID" ng-update-hidden value="{{ $def_estabelecimento_id }}" />
    <input type="hidden" ng-init="vm.TalaoProduzir.FILTRO.GP_ID              = '{{ $def_gp_id              }}';"  ng-model="vm.TalaoProduzir.FILTRO.GP_ID             " ng-update-hidden value="{{ $def_gp_id              }}" />
    <input type="hidden" ng-init="vm.TalaoProduzir.FILTRO.UP_ID              = '{{ $def_up_id              }}';"  ng-model="vm.TalaoProduzir.FILTRO.UP_ID             " ng-update-hidden value="{{ $def_up_id              }}" />
    <input type="hidden" ng-init="vm.TalaoProduzir.FILTRO.ESTACAO            = '{{ $def_estacao            }}';"  ng-model="vm.TalaoProduzir.FILTRO.ESTACAO           " ng-update-hidden value="{{ $def_estacao            }}" />
    <input type="hidden" ng-init="vm.TalaoProduzir.FILTRO.TALAO_ID           = '{{ $def_talao_id           }}';"  ng-model="vm.TalaoProduzir.FILTRO.TALAO_ID          " ng-update-hidden value="{{ $def_talao_id           }}" />
    
 
    <div id="form-filtro" class="table-filter collapse in" aria-expanded="true">

        <div class="consulta-estabelecimento"></div>
        <div class="consulta-gp"></div>  
        <div class="consulta-up"></div>  
        <div class="consulta-estacao"></div>  
  
		
		<div class="form-group filtro-periodo">
			<label>Período Produção/Meta:</label>
            <div class="input-group">
                <input 
                    ng-model="vm.Filtro.DATA_1" 
                    ng-disabled="vm.Filtro.DATA_TODOS"
                    ng-required="!vm.Filtro.DATA_TODOS"
                    toDate
                    type="date" 
                    class="form-control" 
                    required />
                <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                    <span class="fa fa-close"></span>
                </button>
            </div>      
            à
            <div class="input-group">
                <input 
                    ng-model="vm.Filtro.DATA_2" 
                    ng-disabled="vm.Filtro.DATA_TODOS"
                    ng-required="!vm.Filtro.DATA_TODOS"
                    toDate
                    type="date" 
                    class="form-control" 
                    required />                
                <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                    <span class="fa fa-close"></span>
                </button>
            </div>            
            
            <input ng-model="vm.Filtro.DATA_TODOS" ng-disabled="vm.Filtro.DATA_TODOS_DISABLED" style="top: -2px; margin-left: 7px; vertical-align: middle; width: 20px !important;" type="checkbox" id="periodo-todos" class="form-control periodo-todos" title="Só é utilizado em Talões à Produzir" checked="">            
            <label style="position: relative; top: 2px; margin-left: -1px; vertical-align: middle" for="periodo-todos" title="Só é utilizado em Talões à Produzir">Todos</label>
<!--			<input type="date" ng-disabled="vm.FILTRO.FLAG_DATA" ng-model="vm.FILTRO.DATA_INICIAL" class="form-control data-ini" value="{{ date('Y-m-d') }}" />
			<label class="periodo-a"> {{ Lang::get('master.periodo-a') }} </label>
			<input type="date" ng-disabled="vm.FILTRO.FLAG_DATA" ng-model="vm.FILTRO.DATA_FINAL" class="form-control data-fim" value="{{ date('Y-m-d') }}" />
			<input type="checkbox" ng-model="vm.FILTRO.FLAG_DATA" id="periodo-todos" class="form-control periodo-todos" title="{{ Lang::get($menu.'.periodo-todos-title') }}" checked />
			<label style="margin-left: 4px;" for="periodo-todos" title="{{ Lang::get($menu.'.periodo-todos-title') }}"> Hoje</label>-->
		</div>

		
		<button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
		</button>
		

	</div>
</form>	

