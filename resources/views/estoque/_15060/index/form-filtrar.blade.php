@php $def_produto_id = isset($_GET['PRODUTO_ID']) ? $_GET['PRODUTO_ID'] : '""'
@php $def_localizacao_id = isset($_GET['LOCALIZACAO_ID']) ? $_GET['LOCALIZACAO_ID'] : '""'

<div id="programacao-filtro" class="table-filter collapse in" aria-expanded="true">   
    <form class="form-inline" ng-submit="vm.filtrar()">    
        <input type="hidden" ng-init="vm.DEF_PRODUTO_ID     = {{ $def_produto_id        }};"  ng-model="vm.DEF_PRODUTO_ID       " ng-update-hidden value="{{ $def_produto_id        }}" />
        <input type="hidden" ng-init="vm.DEF_LOCALIZACAO_ID = {{ $def_localizacao_id    }};"  ng-model="vm.DEF_LOCALIZACAO_ID   " ng-update-hidden value="{{ $def_localizacao_id    }}" />
        {{-- Produto --}}
        <div style="display: inline;" class="consulta-produto"></div>

        <div class="form-group">
            <label title="Data para produção da remessa">Data Inicio:</label>
            <div class="input-group">
                <input type="date" ng-model="vm.FILTRO.DATA_1" toDate id="data-prod" class="form-control" required />
                <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                    <span class="fa fa-close"></span>
                </button>
            </div>
        </div>
        <div class="form-group">
            <label title="Data para produção da remessa">Data Fim:</label>
            <div class="input-group">
                <input type="date" ng-model="vm.FILTRO.DATA_2" toDate id="data-prod" class="form-control" required />
                <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1">
                    <span class="fa fa-close"></span>
                </button>
            </div>
        </div>
        
        <button type="submit" class="btn btn-xs btn-primary btn-filtrar btn-table-filter" data-hotkey="alt+f">
            <span class="glyphicon glyphicon-filter"></span>
            {{ Lang::get('master.filtrar') }}
        </button>
    </form>
</div>

<!--<button type="button" class="btn btn-xs btn-default" id="filtrar-toggle" data-toggle="collapse" data-target="#programacao-filtro" aria-expanded="true" aria-controls="programacao-filtro">
    Filtro
    <span class="caret"></span>
</button>  -->