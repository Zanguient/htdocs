@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11140.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11140.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>

<ul class="list-inline acoes">
	<li>
		<button ng-click="vm.Create.gravar()" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="Gravando...">
				<span class="glyphicon glyphicon-ok"></span>
				 Gravar
		</button>
		</li>
	    <li>
	    	<a href="{{url('/_11140')}}" class="btn btn-danger btn-cancelar" data-hotkey="f11">

	    		<span class="glyphicon glyphicon-ban-circle"></span>
	    		 Cancelar
	    	</a>
	    </li>
	</ul>

	<fieldset>
		<legend>Informações gerais</legend>
		<div class="line-group">
			<div class="form-group">
				<label>Descrição:</label>
				<input type="text" name="descricao" class="form-control input-maior" autofocus="" required="">
			</div>

			<div class="form-group">
				<label>Título:</label>
				<input type="text" name="titulo" class="form-control input-medio" autofocus="" required="">
			</div>

			<div class="form-group">
				<label>Template:</label>
				<input type="number" name="template" class="form-control input-menor" autofocus="" required="">
			</div>

			<div class="form-group">
				<label>Menu GRUPO:</label>
				<select style="width: 196px;" name="relatorio-grupo"  placeholder="0 ou 1" class="form-control relatorio-grupo input-medio"  autofocus="" required="">
					<option value="ADM" >Admin. do Sistema</option>
					<option value="VEN" >Gestão de Vendas</option>
					<option value="COM" >Gestão de Compras</option>
					<option value="LOG" >Logística</option>
					<option value="EST" >Controle de Estoque</option>
					<option value="PAT" >Controle Patrimonial</option>
					<option value="CONT">Gestão Contábil</option>
					<option value="ENG" >Engenharia</option>
					<option value="FAV" >Favoritos/Histórico</option>
					<option value="FIN" >Financeiro (Cpa,Cre,Bco)</option>
					<option value="FIS" >Fiscal (NFe,NFs,Ecf)</option>
					<option value="PCP" >Ppcp/Produção</option>
					<option value="RH"  >Gestão de Pessoas</option>
					<option value="SUP" >Supply Chain</option>
					<option value="OPX" >Opex</option>
					<option value="CHA" >Chamados</option>
					<option value="PRO" >Estrutura de Produto</option>
					<option value="RLP" >Relatórios Personalizados</option>
					<option value="WOR" >Workflow</option>
				</select>
	      	</div>

	      	<div class="form-group">
				<label>Status:</label>
				<select style="width: 196px;" name="relatorio-grupo"  placeholder="0 ou 1" class="form-control relatorio-grupo input-medio"  autofocus="" required="">
					<option value="1" >Ativo</option>
					<option value="0" >Inativo</option>
				</select>
	      	</div>	      	

		</div>
	</fieldset>

	<fieldset>
		<legend>Informações de casos</legend>
		<button ng-click="vm.Create.modalAddInput()" class="btn btn-primary js-gravar">
				<span class="glyphicon glyphicon-plus"></span>
				 Adicionar
		</button><br><br>

		<div class="conteiner-Inputs">
			
		</div>

	</fieldset>
	
	<div style="display: -webkit-box;">
		<div class="consulta_angularjs1" ></div>
		<div class="consulta_angularjs2" ></div>
		<div class="consulta_angularjs3" ></div>
	</div>

	@include('admin._11140.include.modal-add-inputs')

</div>

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11140.app.js') }}"></script>
@append
