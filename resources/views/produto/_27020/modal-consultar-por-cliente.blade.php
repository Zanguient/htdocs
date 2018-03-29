@extends('helper.include.view.modal', [
	'id' => 'modal-consultar-modelo-por-cliente'
])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.modal-title') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-hotkey="f11"
		ng-click="$ctrl.fecharModal()"
	>
		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>

@overwrite

@section('modal-body')

	<form class="form-inline">
		
		<div class="row">

			<div class="form-group">

				<div class="input-group">

					<input 
						type="search" 
						class="form-control input-maior input-filtrar-modelo" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off"
						ng-model="$ctrl.filtrarModeloPorCliente" 
					/>
					<button 
						type="button" 
						class="input-group-addon btn-filtro" 
						tabindex="-1"
						ng-click="$ctrl.consultarModeloPorCliente()"
					>
						<span class="fa fa-filter"></span>
					</button>

				</div>

			</div>

		</div>

		<div class="row">
		
			<div class="form-group">

				@include('produto._27020.modal-consultar-por-cliente-table')

			</div>

		</div>
		
	</form>

	<div class="visualizar-arquivo">
		<a class="btn btn-default download-arquivo" href="" download data-hotkey="alt+b">
			<span class="glyphicon glyphicon-download"></span>
			{{ Lang::get('master.download') }}
		</a>
		<input type="hidden" class="arquivo_nome_deletar" name="_arquivo_nome[]" />
		<button type="button" class="btn btn-default esconder-arquivo" data-hotkey="f11"
			ng-click="$ctrl.excluirArquivo()"
		>
			<span class="glyphicon glyphicon-chevron-left"></span>
			{{ Lang::get('master.voltar') }}
		</button>
		<object></object>
	</div>

@overwrite