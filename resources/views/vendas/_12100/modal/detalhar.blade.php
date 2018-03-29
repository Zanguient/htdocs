@extends('helper.include.view.modal', ['id' => 'modal-nota', 'class_size' => 'modal-big'])

@section('modal-header-left')

<h4 class="modal-title">
	Nota Fiscal
</h4>

@overwrite

@section('modal-header-right')
	
	<button type="button" ng-click="vm.Acoes.btnVoltar()" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
	  <span class="glyphicon glyphicon-chevron-left"></span> Voltar
	</button>

@overwrite

@section('modal-body')
	
	<button  type="button" class="btn btn-sm btn-primary btn-filtrar" id="btn-table-filter"  data-hotkey="alt+a" ng-click="vm.Acoes.BaixarNota()">
		<span class="glyphicon glyphicon-download"></span>
		Danfe
	</button>

	<a class="btn btn-sm btn-danger btn-filtrar download-arquivo" href="/assets/temp/files/@{{vm.NOTA.XML.NOME}}" download="" data-hotkey="alt+b">
		<span class="glyphicon glyphicon-download"></span>
		XML
	</a>

	<button  type="button" class="btn btn-sm btn-warning btn-filtrar"  data-hotkey="alt+e" ng-click="vm.Acoes.ModalEtiqueta()">
		<span class="glyphicon glyphicon-print"></span>
		Imprimir Etiqueta
	</button>

	<button  type="button" class="btn btn-sm btn-info btn-filtrar"  data-hotkey="alt+e" ng-click="vm.ngWinPopUp('/_14020/NF/'+vm.NOTA.INFO.NUMERO_NOTAFISCAL, vm.NOTA.INFO.NUMERO_NOTAFISCAL,{width: 1200, height: 825})">
		<span class="fa fa-truck"></span> Simular Frete
	</button>


    </div>

<fieldset>
	<legend>Informações da Nota Fiscal</legend>

	<div style="display: inline-flex;">
		<div class="form-group" style="margin-right: 20px;">
	        <label>Numero:</label>
	        <input type="text" class="form-control  relatorio-nome input-menor" value="@{{vm.NOTA.INFO.NUMERO_NOTAFISCAL}}" disabled="">
	    </div>

	    <div class="form-group" style="margin-right: 20px;">
	        <label>Emissão:</label>
	        <input style="width: 100px;" type="text" class="form-control  relatorio-nome input-medio" value="@{{vm.NOTA.INFO.DATA_EMISSAO}}" disabled="">
	    </div>

	    <div class="form-group" style="margin-right: 20px;">
	        <label>Embarque / Entrega:</label>
	        <input style="width: 230px;" type="text" class="form-control  relatorio-nome input-medio" value="@{{vm.NOTA.INFO.EMBARQUE}}" disabled="">
	    </div>

	    <div class="form-group" style="margin-right: 20px;">
	        <label>Transportadora:</label>
	        <input type="text" class="form-control  relatorio-nome input-maior" value="@{{vm.NOTA.INFO.TRANSPORTADORA}}" disabled="">
	    </div>
	</div>

    <div style="margin-bottom: 20px;" class="table-ec">
	    <div class="scroll-table">
	        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
	            <thead>
		            <tr>
						<th>FRETE</th>
						<th>VR. DESCONTO</th>
						<th>VR. ACRESCIMO</th>
						<th>VR. IPI</th>
						<th>VR. FRETE</th>
						<th>VR. TOTAL</th>
						<th>QUANTIDADE</th>
						<th>XML</th>
		            </tr>
		        </thead>
	            <tbody class="tabela-itens">

	                <tr>
		                <td style="text-align: center;" auto-title >@{{vm.NOTA.INFO.FRETE}}</td>
		                <td style="text-align: right;"  auto-title >@{{vm.NOTA.INFO.TOTAL_DESCONTO}}</td>
		                <td style="text-align: right;"  auto-title >@{{vm.NOTA.INFO.TOTAL_ACRESCIMO}}</td>
		                <td style="text-align: right;"  auto-title >@{{vm.NOTA.INFO.TOTAL_IPI}}</td>
		                <td style="text-align: right;"  auto-title >@{{vm.NOTA.INFO.TOTAL_FRETE}}</td>
		                <td style="text-align: right;"  auto-title >@{{vm.NOTA.INFO.TOTAL_NF}}</td>
		                <td style="text-align: right;"  auto-title >@{{vm.NOTA.INFO.TOTAL_QUANTIDADE}}</td>
		                <td style="text-align: center;" auto-title >@{{vm.NOTA.INFO.XML}}</td>
	                </tr>

	            </tbody>
	        </table>
	    </div>
	</div>
    

</fieldset>

<fieldset>
	<legend>Itens da Nota Fiscal</legend>
	<div style="max-height: calc(100vh - 427px);" class="table-ec">
	    <div class="scroll-table">
	        <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
	            <thead>
		            <tr>
						<th>PEDIDO</th>
						<th>PEDIDO CLIENTE</th>
						<th>PRODUTO</th>
						<th>TAMANHO</th>
						<th>QUANTIDADE</th>
						<th>VR. UNITÁRIO</th>
						<th>VR. DESCONTO</th>
						<th>VR. ACRECIMO</th>
						<th>VR. IPI</th>
						<th>VR. FRETE</th>
						<th>VR. TOTAL</th>
		            </tr>
		        </thead>
	            <tbody class="tabela-itens">

	                <tr class="lista-itens" tabindex="0" ng-repeat="nota in vm.NOTA.ITENS">

		                <td style="text-align: left;"   auto-title ng-bind-html="trustAsHtml(nota.PEDIDO)"></td>
		                <td style="text-align: left;"   auto-title >@{{nota.PEDIDO_CLIENTE}}</td>
		                <td class="linha_produto"       auto-title >@{{nota.PRODUTO_ID}} - @{{nota.PRODUTO_DESCRICAO}}</td>
		                <td style="text-align: center;" auto-title >@{{nota.TAMANHO}}</td>
		                <td style="text-align: right;"  auto-title >@{{nota.QUANTIDADE}}</td>
		                <td style="text-align: right;"  auto-title >@{{nota.VALOR_UNITARIO}}</td>
		                <td style="text-align: right;"  auto-title >@{{nota.VALOR_DESCONTO}}</td>
		                <td style="text-align: right;"  auto-title >@{{nota.VALOR_ACRESCIMO}}</td>
		                <td style="text-align: right;"  auto-title >@{{nota.VALOR_IPI}}</td>
		                <td style="text-align: right;"  auto-title >@{{nota.VALOR_FRETE}}</td>
		                <td style="text-align: right;"  auto-title >@{{nota.VALOR_TOTAL}}</td>
	                </tr>
	            </tbody>
	        </table>
	    </div>
	</div>

	<div class="visualizar-arquivo" ng-show="vm.NOTA.XML.VER">

		<a class="btn btn-default download-arquivo" href="/assets/temp/files/@{{vm.NOTA.XML.NOME}}" download="" data-hotkey="alt+b">
			<span class="glyphicon glyphicon-download"></span>
			Baixar
		</a>
		
		<button type="button" class="btn btn-default esconder-arquivo" data-hotkey="f11" ng-click="vm.NOTA.XML.VER = false">
			<span class="glyphicon glyphicon-chevron-left"></span>
			Voltar
		</button>

		<label class="lbl-visualizacao-indisponivel ng-scope">
			Visualização indisponível!
		</label>

	</div>
</fieldset>

@overwrite