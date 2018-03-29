@extends('helper.include.view.modal', ['id' => 'modal-detalhar-componentes'])

@section('modal-header-left')

	<h4 class="modal-title">
		Ficha de Produção
	</h4>
@overwrite

@section('modal-header-right')
	<button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
		<span class="glyphicon glyphicon-chevron-left"></span> Voltar
	</button>

@overwrite

@section('modal-body')
	<table class="table table-bordered table-header">
		<tbody>
			<tr>
				<td colspan="2" style="font-size: 14px;font-weight: bold;">@{{vm.composicao.Componente.DESCRICAO}}</td>
			</tr>
			<tr>
				<td>Remessa: @{{vm.composicao.Componente.REMESSA_ID}}</td>
				<td>Talão: @{{vm.composicao.Componente.REMESSA_TALAO_ID}}</td>
			</tr>
		</tbody>
	</table>
	<p>
	<table class="table table-bordered table-header">
		<thead>
			<tr>
				<th>Descrição</th>
				<th>Qtd. Padrão</th>
				<th>Qtd. Talão</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="composicao in vm.composicao.Dados track by $index" >
				<td>@{{composicao.TIPO_DESCRICAO}}</td>
				<td>@{{composicao.QUANTIDADE_PADRAO | number : 2}}</td>
				<td>@{{composicao.QUANTIDADE | number : 2}}</td>
			</tr>
		</tbody>
	</table>

@overwrite