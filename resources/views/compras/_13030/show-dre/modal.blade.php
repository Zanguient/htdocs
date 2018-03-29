@extends('helper.include.view.modal', ['id' => 'dre-modal', 'class_size' => 'modal-full'])

@section('modal-header-left')

	<h4 class="modal-title">
		Consumo Mensal de Cotas
	</h4>

@overwrite

@section('modal-header-right')

            <button type="button" class="btn btn-warning btn-imprimir btn-print" data-hotkey="alt+i" data-loading-text="{{ Lang::get('master.imprimindo') }}">
                <span class="glyphicon glyphicon-print"></span> 
                {{ Lang::get('master.imprimir') }}
            </button>

            <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
                <span class="glyphicon glyphicon-chevron-left"></span> 
                Voltar
            </button>
@overwrite

@section('modal-body')

<form class="form-inline">
	<fieldset>   
		<legend>Filtros</legend>

            <div class="pesquisa-obj-container">
                <div class="input-group input-group-filtro-obj">
                    <input type="search" name="filtro" class="form-control filtro-obj btn-dre-filtro" placeholder="Pesquise..." data-loading-text="{{ Lang::get('master.pesquisando') }}" autocomplete="off"/>
                    <button type="button" class="input-group-addon btn-filtro btn-filtro-obj"><span class="fa fa-search"></span></button>
                </div>
            </div>   
        <div id="dre-filtro-hidden" class="table-filter collapse in" aria-expanded="true">   
            
            <div class="row">
                <div class="form-group">
                    <label>Mês Inicial:</label>
                    <select name="mes_1" class="form-control" required="" >
                        <option disabled>Mês</option>
                        @for ($i = 1; $i < 13; $i++)
                         <option value="{{ $i }}" {{ 1 == $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label>Mês Final:</label>
                    <select name="mes_2" class="form-control" required>
                        <option disabled>Mês</option>
                        @for ($i = 1; $i < 13; $i++)
                        <option value="{{ $i }}" {{ date('n')== $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label>Ano:</label>
                    <select name="ano_1" class="form-control" required>
                        <option disabled>Ano</option>
                        @for ($i = 2000; $i < 2041; $i++)
                        <option value="{{ $i }}" {{ date('Y')== $i ? 'selected' : ''}}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <input type="checkbox" name="cota_zerada" id="cota_zerada" class="form-control" checked/>
                    <label for="cota_zerada"  ttitle="Exibe cotas com valor igual a 0 (zero)">Cotas Zeradas</label>
                </div>
                <div class="form-group">
                    <input type="checkbox" name="cota_valida" id="cota_valida" class="form-control"  checked/>
                    <label for="cota_valida"  ttitle="Exibe cotas com valor maior que 0 (zero)">Cotas Válidas</label>
                </div>	
                <div class="form-group">
                    <input type="checkbox" name="cota_totaliza" id="cota_totaliza" class="form-control cota-totaliza"/>
                    <label for="cota_totaliza"  ttitle="Totaliza todas as cotas">Totaliza Cotas</label>
                </div>	    
                <div class="form-group">
                    <input type="checkbox" id="meses_toggle" class="form-control" checked/>
                    <label for="meses_toggle"  ttitle="Exibe/Oculta Meses">Exibir Meses</label>
                </div>	  
			    <div class="form-group">
			    	<input type="checkbox" name="filtro_ggf" id="filtro_ggf" class="form-control filtro-ggf" />
			    	<label for="filtro_ggf"  ttitle="Exibir os Gastos Gerais de Fabricação / Gastos Gerais Administrativos">Exibir G.G.F./G.G.A.</label>
			    </div>	 
			    <div class="form-group">
			    	<input type="checkbox" name="filtro_ajuste_inventario" id="filtro_ajuste_inventario" class="form-control filtro-ajuste_inventario" />
			    	<label for="filtro_ajuste_inventario"  ttitle="Exibir os ajustes de estoque realizados durante o período em reais">Exibir Ajustes de Inventário</label>
			    </div>	 
			    <div class="form-group">
			    	<input type="checkbox" name="filtro_faturamento" id="filtro_faturamento" class="form-control filtro-faturamento" />
			    	<label for="filtro_faturamento"  data-toggle="tooltip">Exibir Faturamento</label>
			    </div>	                 
                <div class="form-group">
                    <button type="button" class="btn btn-inline btn-primary btn-dre-filtrar" data-hotkey="alt+f" data-loading-text="{{ Lang::get('master.filtrando') }}">
                        <span class="glyphicon glyphicon-filter"></span> 
                        {{ Lang::get('master.filtrar') }}
                    </button>    
                </div>	 
            </div>	  
        </div>
        <button type="button" class="btn btn-xs btn-default filtrar-toggle" data-toggle="collapse" data-target="#dre-filtro-hidden" aria-expanded="true" aria-controls="dre-filtro-hidden">
            Filtro
            <span class="fa fa-caret-down"></span>
        </button>
        <legend>Consumo Mensal de Cotas</legend> 
		
		{{-- Tabela --}}
        <style>
            .meses-ocultar {
                display: none;
            }
        </style>
        <div class="dre">
            
        </div>

	</fieldset>
</form>

@overwrite