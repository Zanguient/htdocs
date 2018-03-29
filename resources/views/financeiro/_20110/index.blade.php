@extends('master')

@section('titulo')
    {{ Lang::get('financeiro/_20110.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/20110.css') }}" type="text/css" media="all" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak style="margin-top: -48px;">

	<fieldset class="programacao">
        
        <button type="button" class="btn btn-xs btn-default" id="filtrar-toggle" data-toggle="collapse" data-target="#relatorio-filtro" aria-expanded="true" aria-controls="programacao-filtro">
			{{ Lang::get('master.filtro-toggle') }}
			<span class="caret"></span>
		</button>
        
        <a href="{{ url('home') }}" class="btn btn-xs btn-default" id="filtrar-toggle">
            <span class="glyphicon glyphicon-chevron-left"></span> 
            Voltar
        </a>

        <imput type="hidden" class="auto-filtro-relatorio" data-valor="{{isset($_GET['AUTO']) ? $_GET['AUTO'] : 0}}" ></imput>
  
		<div style="margin-top: 10px;" id="relatorio-filtro" class="table-filter collapse in relatorio-filtro" aria-expanded="true">
			@php /*
				<div class="consulta_banco">
				</div>
			@php */

				<div class="form-group" style="display: inline-flex; background-color: antiquewhite; padding: 5px; border-radius: 6px;">
					<div class="form-group" style="margin-left: 5px;  margin-bottom: 0px;">
						<label for="data">Data:</label>
						<input type="date"  ng-disabled="vm.dataTodas == true" name="data" id="data1" class="form-control" ng-model="vm.DATA1" value="" required/>
					</div>

					<div class="form-group"  style="margin-left: 5px;  margin-bottom: 0px;">
						<label for="data">Data:</label>
						<input type="date"  ng-disabled="vm.dataTodas == true" name="data" id="data2" class="form-control"  ng-model="vm.DATA2" value="" required/>
					</div>
					
					<div class="form-group"  style="margin-left: 5px;  margin-bottom: 0px;">
						<div class="checkbox-group" style=" display: inline-table; padding: 3px;border-radius: 4px;">
			                <input style="top: -4px;" type="checkbox" ng-model="vm.dataTodas"> <label style="top: -4px;"> Todos</label>
			            </div>
			        </div> 

				</div>
			
			@php /*
				<div class="form-group">
					<label for="data">Perfil (FIN):</label>
					<input style="top: -4px;" ype="text" name="text"  class="form-control input-menor"  ng-model="vm.PERFIL" value="" required/>
				</div>
			@php */

					<div><input style="top: 8px;" type="checkbox" ng-model="vm.detalhar.bancos"> <label style="top: 8px;" > Detalhe Saldo dos Bancos</label></div>
		            <div><input style="top: 8px;" type="checkbox" ng-model="vm.detalhar.provisoes"> <label style="top: 8px;" > Detalhe provisões</label></div>
		            <div><input style="top: 8px;" type="checkbox" ng-model="vm.detalhar.pagar"> <label style="top: 8px;" > Detalhe Conta a Pagar</label></div>
		            <div><input style="top: 8px;" type="checkbox" ng-model="vm.detalhar.receber"> <label style="top: 8px;" > Detalhe Conta a Receber</label></div>
		            <div><input style="top: 8px;" type="checkbox" ng-model="vm.detalhar.compra"> <label style="top: 8px;" > Detalhe Ordens de Compra</label></div>

				
                <div class="form-group" tyle="padding-top: 28px;">
                    <button type="button" ng-click="vm.Acoes.filtrar()" class="btn btn-info relatorio-filtrar" data-loading-text="Filtrando..." data-toggle="collapse" data-target="#relatorio-filtro" aria-expanded="true" aria-controls="programacao-filtro"><span class="glyphicon glyphicon-filter"></span>Filtrar</button>                                      
                </div>

        </div>

    </fieldset>
	
	<div style="margin-top: 33px; margin-bottom: 3px;">
		<button type="button" style="margin-bottom: 5px;" class="btn btn-primary" ng-click="vm.Acoes.export1()">
			<span class="glyphicon glyphicon-save"></span> 
			Exportar para CSV
		</button>
		<button type="button" style="margin-bottom: 5px;" class="btn btn-primary" ng-click="vm.Acoes.export2()">
			<span class="glyphicon glyphicon-save"></span> 
			Exportar para XLS
		</button>
		<button type="button" style="margin-bottom: 5px;" class="btn btn-primary" ng-click="vm.Acoes.imprimir()">
			<span class="glyphicon glyphicon-print"></span> 
			Imprimir
		</button>
	</div>
	
	<div class="" id="container-registros">

		<div class="table-ec spand div-table" style="height: calc(100vh - 350px); min-height: 270px;">
	        <table style="width: 99.999% !important;" class="table table-striped table-bordered table-hover table-condensed table-middle tabela-registros" id="tabela-registros">
	            <thead>
	                <tr style="background-color: #3479b7;">
	                    <th colspan="3" style="text-align: center;">Data</th>
	                    <th rowspan="2" style="text-align: center;">Negociado</th>
	                    <th colspan="3" style="text-align: center;">Provisões</th>
	                    <th rowspan="2" style="text-align: center;">Cta. a Pagar</th>
	                    <th rowspan="2" style="text-align: center;">Cta. a Receber</th>
	                    <th rowspan="2" style="text-align: center;">Sal. Diário</th>
	                    <th rowspan="2" style="text-align: center;">Saldo</th>
	                </tr>
	                <tr style="background-color: #3479b7;">
	                    <th style="text-align: center; top: 30px;">Descrição</th>
	                    <th style="text-align: center; top: 30px;">Cobrança</th>
	                    <th style="text-align: center; top: 30px;">Data</th>

	                    <th style="text-align: center; top: 30px;">Pagamentos</th>
	                    <th style="text-align: center; top: 30px;">O. Compras</th>
	                    <th style="text-align: center; top: 30px;">Recebimentos</th>
	                </tr>
	        </thead>
	        <tbody>
	                <tr ng-repeat="banco in vm.DADOS.Bancos"
	                    tabindex="-1"     
	                    class=""
	                    style="background: #f9efca !important; font-weight: bold;" 
	                    >

			            <td class="text-left "  colspan="3" autotitle>@{{banco.NOME}}</td>
			            <td class="text-right"  autotitle></td>
			            <td class="text-right"  autotitle></td>
			            <td class="text-right"  autotitle></td>
			            <td class="text-right"  autotitle></td>
			            <td class="text-right"  autotitle></td>
			            <td class="text-right"  autotitle></td>
			            <td class="text-right"  autotitle></td>
			            <td class="text-right"  autotitle>R$ @{{banco.SALDO  | number : 2 }}</td>
	                </tr>

	                <tr ng-repeat-start="fluxo in vm.DADOS.FLUXO"
	                    tabindex="-1"     
	                    class=""
	                    style="background: #c9ffc3 !important; font-weight: bold;" 
	                    >

			            <td class="text-left "  colspan="3" autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ fluxo.DATA_FLUXO2}} - @{{ fluxo.DIA_SEMANA}}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ fluxo.NEGOCIADO   | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ fluxo.PAGAMENTO   | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ fluxo.COMPRA      | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ fluxo.RECEBIMENTO | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ fluxo.PAGAR  	   | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ fluxo.RECEBER     | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterios' : fluxo.FLAG == true}"> @{{ fluxo.SALDODIA    | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ fluxo.SALDO  	   | number : 2 }}</td>
	                </tr>

	                <tr ng-repeat="receber in fluxo.ContaReceber"
	                    tabindex="-1"     
	                    class=""
	                    style="background: #ffffff !important;" 
	                    >

			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ receber.NUMERO_NOTAFISCAL}} - @{{ receber.EMPRESA_RAZAOSOCIAL}}</td>
			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ receber.COBRANCA_DESCRICAO}}</td>
			            <td class="text-left "  style="text-align: center;" autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ receber.DATA_D}}</td>
			            
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ receber.VALOR_SALDO | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterios' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
	                </tr>

	                <tr ng-repeat="pagar in fluxo.ContaPagar"
	                    tabindex="-1"     
	                    class=""
	                    style="background: #ffffff !important;" 
	                    >

			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ pagar.NUMERO_NOTAFISCAL}} - @{{ pagar.EMPRESA_RAZAOSOCIAL}}</td>
			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ pagar.COBRANCA_DESCRICAO}}</td>
			            <td class="text-left "  style="text-align: center;" autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ pagar.DATA_D}}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ pagar.VALOR_SALDO | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterios' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
	                </tr>

	                <tr ng-repeat="negociado in fluxo.Negociados"
	                    tabindex="-1"     
	                    class=""
	                    style="background: #ffffff !important;" 
	                    >

			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ negociado.NUMERO_NOTAFISCAL}} - @{{ negociado.EMPRESA_RAZAOSOCIAL}}</td>
			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ negociado.COBRANCA_DESCRICAO}}</td>
			            <td class="text-left "  style="text-align: center;" autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ negociado.DATA_D}}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ negociado.VALOR_SALDO | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterios' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
	                </tr>

	                <tr ng-repeat="compra in fluxo.OrdensCompra"
	                    tabindex="-1"     
	                    class=""
	                    style="background: #ffffff !important;" 
	                    >

			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ compra.OC}} - @{{ compra.FORNECEDOR}}</td>
			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-left "  style="text-align: center;" autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ compra.DATA_D}}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ compra.VALOR | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterios' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
	                </tr>

	                <tr ng-if="(provisoes.TIPO + '').trim() == 'C' " ng-repeat="provisoes in fluxo.Provisoes"
	                    tabindex="-1"     
	                    class=""
	                    style="background: #ffffff !important;" 
	                    >

			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ provisoes.CONTROLE}} - @{{ provisoes.HISTORICO}}</td>
			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> </td>
			            <td class="text-left "  style="text-align: center;" autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ provisoes.DATA_D}}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ provisoes.VALOR_TOTAL | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterios' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
	                </tr>

	                <tr ng-if="(provisoes.TIPO + '').trim() == 'D' " ng-repeat="provisoes in fluxo.Provisoes"
	                    tabindex="-1"     
	                    class=""
	                    style="background: #ffffff !important;" 
	                    >

			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ provisoes.CONTROLE}} - @{{ provisoes.HISTORICO}}</td>
			            <td class="text-left "  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> </td>
			            <td class="text-left "  style="text-align: center;" autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ provisoes.DATA_D}}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"> @{{ provisoes.VALOR_TOTAL | number : 2 }}</td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterios' : fluxo.FLAG == true}"></td>
			            <td class="text-right"  autotitle ng-class="{'cell-dia-anterior' : fluxo.FLAG == true}"></td>
	                </tr>

	                <tr ng-repeat-end ng-if="false"></tr>  

	            </tbody>            
	        </table>
	    </div>

	    <div class="table-ec" style="margin-top: 20px;">
	        <table style="width: 99.999% !important;" class="table table-striped table-bordered table-hover">
	            <thead>
	                <tr style="background-color: #3479b7;">
	                    <th rowspan="2" style="text-align: center;">Saldo Banco</th>
	                    <th rowspan="2" style="text-align: center;">Negociado</th>
	                    <th colspan="3" style="text-align: center;">Provisões</th>
	                    <th rowspan="2" style="text-align: center;">Cta. a Pagar</th>
	                    <th rowspan="2" style="text-align: center;">Cta. a Receber</th>
	                    <th rowspan="2" style="text-align: center;">Saldo</th>
	                </tr>
	                <tr style="background-color: #3479b7;">
	                    <th style="text-align: center; top: 30px;">Pagamentos</th>
	                    <th style="text-align: center; top: 30px;">O. Compras</th>
	                    <th style="text-align: center; top: 30px;">Recebimentos</th>
	                </tr>
	        </thead>
	        <tbody>
	                <tr tabindex="-1"     
	                    class="tr-fixed-1"
	                    style="background-color: aquamarine; font-size: 16px;" 
	                    >

			            <td class="text-right"  autotitle> @{{ vm.DADOS.FLUXO_TOTAL.SALDO_BANCO | number : 2 }}</td>
			            <td class="text-right"  autotitle> @{{ vm.DADOS.FLUXO_TOTAL.NEGOCIADO   | number : 2 }}</td>
			            <td class="text-right"  autotitle> @{{ vm.DADOS.FLUXO_TOTAL.PAGAMENTO   | number : 2 }}</td>
			            <td class="text-right"  autotitle> @{{ vm.DADOS.FLUXO_TOTAL.COMPRA      | number : 2 }}</td>
			            <td class="text-right"  autotitle> @{{ vm.DADOS.FLUXO_TOTAL.RECEBIMENTO | number : 2 }}</td>
			            <td class="text-right"  autotitle> @{{ vm.DADOS.FLUXO_TOTAL.PAGAR  	    | number : 2 }}</td>
			            <td class="text-right"  autotitle> @{{ vm.DADOS.FLUXO_TOTAL.RECEBER     | number : 2 }}</td>
			            <td class="text-right"  autotitle> @{{ vm.DADOS.FLUXO_TOTAL.SALDO  	    | number : 2 }}</td>
	                </tr>            
	            </tbody>            
	        </table>
	    </div>
	</div>

</div>
@endsection

@section('script')

	<script>
	var acc = document.getElementsByClassName("accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
	    acc[i].onclick = function(){
	        this.classList.toggle("active");
	        var panel = this.nextElementSibling;
	        if (panel.style.display === "block") {
	            panel.style.display = "none";
	        } else {
	            panel.style.display = "block";
	        }
	    }
	}
	</script>

    <script src="{{ elixir('assets/js/_20110.app.js') }}"></script>
@append
