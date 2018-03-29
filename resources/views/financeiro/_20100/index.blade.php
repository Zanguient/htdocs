@extends('master')

@section('titulo')
    {{ Lang::get('financeiro/_20100.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/20100.css') }}" type="text/css" media="all" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

	<ul class="list-inline acoes">

	    <li>
			<div class="consulta_banco">
			</div>
		</li>
		<li>
			<div class="form-group">
				<label for="data">Data:</label>
				<input type="date" name="data" id="data1" class="form-control" ng-model="vm.DATA1" value="" required/>
			</div>
		</li>
		<li>
			<div class="form-group">
				<label for="data">Data:</label>
				<input type="date" name="data" id="data2" class="form-control"  ng-model="vm.DATA2" value="" required/>
			</div>
		</li>
		<li>
			<div class="form-group checkbox-group">
                <input type="checkbox" ng-model="vm.DETALHAR"> <label >Detalhar</label> 
            </div>
		</li>
		<li>
			<button type="button" class="btn btn-sm btn-primary btn-filtrar" id="btn-table-filter" data-hotkey="alt+f" ng-click="vm.Acoes.filtrar()">
				<span class="glyphicon glyphicon-filter"></span>
				Filtrar
			</button>
		</li>
	</ul>
	
	<div style="margin-top: 33px; margin-bottom: 3px;">
		<button type="button" style="" class="btn btn-primary" ng-click="vm.Acoes.export1()">
			<span class="glyphicon glyphicon-save"></span> 
			Exportar para CSV
		</button>
		<button type="button" style="" class="btn btn-primary" ng-click="vm.Acoes.export2()">
			<span class="glyphicon glyphicon-save"></span> 
			Exportar para XLS
		</button>
		<button type="button" style="" class="btn btn-primary" ng-click="vm.Acoes.imprimir()">
			<span class="glyphicon glyphicon-print"></span> 
			Imprimir
		</button>
	</div>
	
	<div class="" id="container-registros">

		<div class="table-ec spand div-table" style="height: calc(100vh - 300px); min-height: 270px;">
	        <table class="table table-striped table-bordered table-hover table-condensed table-middle tabela-registros" id="tabela-registros">
	            <thead>
	                <tr style="height: 31px; background-color: #3479b7;">
	                    <th>Data</th>
	                    <th>Descrição</th>
	                    <th>Entradas</th>
	                    <th>Saídas</th>
	                    <th>Saldo</th>
	                </tr>
	        </thead>
	        <tbody>
	                <tr ng-repeat-start="item in vm.DADOS.RELATORIO"
	                    tabindex="-1"     
	                    class="tr-fixed-1"
	                    style="background: #f9efca!important; font-weight: bold; height: 31px;" 
	                    >

	                    <td class="row-fixed row-fixed-1" colspan="2" autotitle>dia - @{{ item.DATA }}</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>R$ @{{ item.DEBITO  | number : 2 }}</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>R$ @{{ item.CREDITO | number : 2 }}</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>R$ @{{ item.SALDO   | number : 2 }}</td>  

	                </tr>                  
	                <tr ng-repeat-start="extrato in item.EXTRATO"
	                    tabindex="0"
	                    style="background: #e7f7f5; font-size: 12px;" 
	                    >
	                    <td class="row-fixed row-fixed-2" autotitle>@{{ extrato.DATA }}</td>
			            <td class="row-fixed row-fixed-2" autotitle>@{{ extrato.HISTORICO }}</td>

			            <td class="row-fixed row-fixed-2 text-right" ng-if="(extrato.NATUREZA + '').trim() == 'D'" autotitle>R$ @{{ extrato.VALOR_DEBITO  | number : 2 }}</td>
			            <td class="row-fixed row-fixed-2 text-right" ng-if="(extrato.NATUREZA + '').trim() == 'D'" autotitle></td>

			            <td class="row-fixed row-fixed-2 text-right" ng-if="(extrato.NATUREZA + '').trim() == 'C'" autotitle></td>
			            <td class="row-fixed row-fixed-2 text-right" ng-if="(extrato.NATUREZA + '').trim() == 'C'" autotitle>R$ @{{ extrato.VALOR_CREDITO | number : 2 }}</td>

			            <td class="row-fixed row-fixed-2 text-right" autotitle>R$ @{{ extrato.SALDO         | number : 2 }}</td>              
	                </tr> 
	                <tr ng-repeat="detalhe in extrato.DETALHES"
	                    tabindex="0"
	                    style="background-color: #fff; font-size: 10px; height: 31px;" 
	                    >
	                    <td class="" autotitle>DOC.: @{{ detalhe.CLASSIFICACAO_CONTABIL }}</td>
			            <td class="" autotitle>@{{ detalhe.HISTORICO_CONTABIL }}</td>

			            <td class="text-right" ng-if="(extrato.NATUREZA + '').trim() == 'D'" autotitle>R$ @{{ detalhe.VALOR  | number : 2 }}</td>
			            <td class="text-right" ng-if="(extrato.NATUREZA + '').trim() == 'D'" autotitle></td>  
			            <td class="text-right" ng-if="(extrato.NATUREZA + '').trim() == 'C'" autotitle></td>
			            <td class="text-right" ng-if="(extrato.NATUREZA + '').trim() == 'C'" autotitle>R$ @{{ detalhe.VALOR  | number : 2 }}</td>  

			            <td class="text-right" autotitle></td>  

	                </tr>  
	                <tr ng-repeat-end ng-if="false"></tr>  
	                <tr ng-repeat-end ng-if="false"></tr>

	                <tr tabindex="-1"     
	                    class="tr-fixed-1"
	                    style="background-color: aquamarine; font-size: 16px;" 
	                    >

	                    <td class="row-fixed row-fixed-1" autotitle>TOTAL FINAL DO PERÍODO</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>Anterior: R$ @{{ vm.SALDO_ANTERIOR | number : 2 }}</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>R$ @{{ vm.TOTAL.DEBITO   | number : 2 }}</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>R$ @{{ vm.TOTAL.CREDITO  | number : 2 }}</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>R$ @{{ vm.TOTAL.SALDO    | number : 2 }}</td>  

	                </tr> 

	            </tbody>            
	        </table>
	    </div>
	</div>
	
	    <div class="table-ec" style="margin-top: 20px;">
	        <table class="table table-striped table-bordered table-hover">
	            <thead>
	                <tr style=" background-color: #3479b7;">
	                    <th></th>
	                    <th>Anterior</th>
	                    <th>Entradas</th>
	                    <th>Saídas</th>
	                    <th>Saldo</th>
	                </tr>
	        </thead>
	        <tbody>
	                <tr tabindex="-1"     
	                    class="tr-fixed-1"
	                    style="background-color: aquamarine; font-size: 16px;" 
	                    >

	                    <td class="row-fixed row-fixed-1" autotitle>TOTAL FINAL DO PERÍODO</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>R$ @{{ vm.SALDO_ANTERIOR | number : 2 }}</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>R$ @{{ vm.TOTAL.DEBITO   | number : 2 }}</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>R$ @{{ vm.TOTAL.CREDITO  | number : 2 }}</td>
			            <td class="row-fixed row-fixed-1 text-right"  autotitle>R$ @{{ vm.TOTAL.SALDO    | number : 2 }}</td>  

	                </tr>            
	            </tbody>            
	        </table>
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

    <script src="{{ elixir('assets/js/_20100.app.js') }}"></script>
@append
