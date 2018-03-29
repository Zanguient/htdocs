@extends('master')

@section('titulo')
{{ Lang::get('compras/_13030.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13030.css') }}">
@endsection

@section('conteudo')		
	<ul class="list-inline acoes">    
		<li>
            <a href="{{ $permissaoMenu->INCLUIR ? url('/_13030/create') : '#' }}" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} class="btn btn-primary btn-incluir" data-hotkey="f6">
                <span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.incluir') }}
            </a>
        </li>
		<li>
            <a href="{{ $permissaoMenu->INCLUIR ? url('/_13030/replicar') : '#' }}" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }} class="btn btn-default btn-replicar" data-hotkey="alt+r">
                <span class="glyphicon glyphicon-plus"></span> Replicar Cotas
            </a>
        </li>   
        @if ( $ctrl_198 ) {{-- // 198 - PERMITE GERENCIAR FATURAM. NAS COTAS ORÇAMENTÁRIAS --}}
        <li>
            <a href="{{ url('_13030/faturamento') }}" class="btn btn-default btn-ger-fat" data-hotkey="alt+g">
                <span class="glyphicon glyphicon-plus"></span> Gerenciar Faturamento
            </a>
        </li> 
        @endif   
        <li>
            <button class="btn btn-default btn-consumo" data-action="show-dre" data-hotkey="alt+c">
                <span class="glyphicon glyphicon-new-window"></span> Consumo Mensal de Cotas
            </button>
        </li>    
        <li>
            <a href="{{ url('_13030/ng') }}" class="btn btn-warning">
                <span class="fa fa-external-link"></span> Cotas Orçamentárias - Versão 2.0
            </a>
        </li>    
	</ul>
	
	<div class="pesquisa-obj-container">
		<div class="input-group input-group-filtro-obj">
			<input type="search" name="filtro_obj" class="form-control filtro-obj btn-cotas-filtro" placeholder="Pesquise..." autocomplete="off" autofocus />
			<button type="button" class="input-group-addon btn-filtro btn-filtro-obj">
				<span class="fa fa-search"></span>
			</button>
		</div>
	</div>
	
	<form class="form-inline edit js-gravar">

	<fieldset>           
		<legend>Filtros</legend>
        <div id="programacao-filtro" class="table-filter collapse in" aria-expanded="true">   
			<div class="row">
                <div class="form-group">
                    <label>Data Inicial:</label>
                    <select name="filtro_mes_inicial" class="form-control filtro-mes-inicial" required="" >
                        <option disabled>Mês</option>
                        @for ($i = 1; $i < 13; $i++)
                         <option value="{{ $i }}" {{ date('n',strtotime('-1 Month'))== $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
                        @endfor
                    </select>
                    <select name="filtro_ano_inicial" class="form-control filtro-ano-inicial" required="" >
                        <option disabled>Ano</option>
                        @for ($i = 2000; $i < 2041; $i++)
                        <option value="{{ $i }}" {{ date('Y',strtotime('-1 Month'))== $i ? 'selected' : ''}}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label>Data Final:</label>
                    <select name="filtro_mes_final" class="form-control filtro-mes-final" required>
                        <option disabled>Mês</option>
                        @for ($i = 1; $i < 13; $i++)
                        <option value="{{ $i }}" {{ date('n')== $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
                        @endfor
                    </select>
                    <select name="filtro_ano_final" class="form-control filtro-ano-final" required>
                        <option disabled>Ano</option>
                        @for ($i = 2000; $i < 2041; $i++)
                        <option value="{{ $i }}" {{ date('Y')== $i ? 'selected' : ''}}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
			    <div class="form-group">
			    	<input type="checkbox" name="filtro_cota_zerada" id="filtro_cota_zerada" class="form-control filtro-cota-zerada" {{ $cota_zerada ? 'checked' : '' }}/>
			    	<label for="filtro_cota_zerada"  data-toggle="tooltip" title="Exibe cotas com valor igual a 0 (zero)">Cotas Zeradas</label>
			    </div>
			    <div class="form-group">
			    	<input type="checkbox" name="filtro_cota_valida" id="filtro_cota_valida" class="form-control filtro-cota-valida" {{ $cota_valida ? 'checked' : '' }}/>
			    	<label for="filtro_cota_valida"  data-toggle="tooltip" title="Exibe cotas com valor maior que 0 (zero)">Cotas Válidas</label>
			    </div>	    
			    <div class="form-group">
			    	<input type="checkbox" name="filtro_totaliza" id="filtro_totaliza" class="form-control filtro-totaliza" {{ $totaliza ? 'checked' : '' }}/>
			    	<label for="filtro_totaliza"  data-toggle="tooltip" title="Totaliza todas as cotas">Totaliza Cotas</label>
			    </div>	 
			    <div class="form-group">
			    	<input type="checkbox" name="filtro_ggf" id="filtro_ggf" class="form-control filtro-ggf" {{ $ggf ? 'checked' : '' }}/>
			    	<label for="filtro_ggf"  data-toggle="tooltip" title="Exibir os Gastos Gerais de Fabricação / Gastos Gerais Administrativos">Exibir G.G.F./G.G.A.</label>
			    </div>	 
			    <div class="form-group">
			    	<input type="checkbox" name="filtro_faturamento" id="filtro_faturamento" class="form-control filtro-faturamento" {{ $faturamento ? 'checked' : '' }}/>
			    	<label for="filtro_faturamento"  data-toggle="tooltip">Exibir Faturamento</label>
			    </div>	 
                <div class="form-group">
                    <button type="button" class="btn btn-inline btn-primary btn-filtrar btn-cotas-filtrar" data-hotkey="alt+f" data-loading-text="{{ Lang::get('master.filtrando') }}">
                        <span class="glyphicon glyphicon-filter"></span> 
                        {{ Lang::get('master.filtrar') }}
                    </button>    
                </div>	                
            </div>		
        </div>
        <button type="button" class="btn btn-xs btn-default filtrar-toggle" data-toggle="collapse" data-target="#programacao-filtro" aria-expanded="true" aria-controls="programacao-filtro">
            Filtro
            <span class="fa fa-caret-down"></span>
        </button>

        
		<legend>Cotas Cadastradas</legend>      
        <section class="cotas">
            <div class="panel panel-primary cotas">                                                              
            <div class="panel-heading">                                                                      
                <div class="panel-title titulo-lista">                                                           
                    <div class="col">C. Custo / Período / C. Contábil</div>                                      
                    <div class="col">Cota/Fat.</div>                                                                  
                    <div class="col">Extra (+)</div> 
                    <div class="col">Subtotal</div>     
                    <div class="col">Reduções/Dev. (-)</div>                                                      
                    <div class="col">Utiliz.</div>                                                             
                    <div class="col" data-toggle="tooltip" title="% Utilizado: ( ( Utilizado + Reduções ) / Cota ) * 100">% Utiliz.</div>  
                    <div class="col">Saldo</div>      
                    <div class="col">Custo Setor</div>                                                                  
                </div>                                                                                           
            </div>                                                                                               
            <div class="panel-body">                                                                             
                <div class="panel-group Area-Acordion" id="accordion" role="tablist" aria-multiselectable="true">

                </div>
            </div>
            </div>
        </section>
	</fieldset>
	</form>

@include('helper.include.view.delete-confirm')

    @include('compras._13030.show-dre.modal')
    @include('compras._13030.show.modal')
    @include('compras._13030.show-ggf.modal')
    @include('compras._13030.show-ggf-detalhe.modal')
    @include('compras._13030.modal')

@include('helper.include.view.historico',[
	'tabela'	=> 'TBCCUSTO_COTA', 
	'id'		=> 23068, 
	'no_button'	=> 'true'
])

@endsection

@include('helper.include.view.pdf-imprimir')

@section('script')
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
	<script src="{{ elixir('assets/js/file.js') }}"></script>
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/input.js') }}"></script>
	<script src="{{ elixir('assets/js/pdf.js') }}"></script>
	<script src="{{ elixir('assets/js/_13030.js') }}"></script>
    <script src="{{ elixir('assets/js/_25700.js') }}"></script>
@append
