@extends('helper.include.view.modal', ['id' => 'modal-pesq-usuario'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.legend-destinatario') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar"
		data-hotkey="f11"
		ng-click="$ctrl.fecharModalPesqUsuario()">

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
						id="input-filtrar-usuario"
						class="form-control input-maior" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off"
						ng-model="$ctrl.filtrarUsuario" 
						ng-init="$ctrl.filtrarUsuario = ''" 
					/>
					<button type="button" class="btn input-group-addon btn-filtro" tabindex="-1"><span class="fa fa-search"></span></button>
				</div>
			</div>

		</div>

		<div class="row">

			<div class="table-container table-container-usuario">

				<table class="table table-bordered table-header">
					<thead>
						<tr>
							<th class="chk"></th>
							<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
							<th class="usuario">{{ Lang::get($menu.'.th-usuario') }}</th>
							<th class="nome">{{ Lang::get($menu.'.th-nome') }}</th>
							<th class="setor">{{ Lang::get($menu.'.th-setor') }}</th>
						</tr>
					</thead>
				</table>

				<div class="scroll-table">
					<table class="table table-striped table-bordered table-body">						
						<tbody vs-repeat vs-scroll-parent=".table-container">
							<tr 
								ng-repeat="usuario in $ctrl.listaUsuario | orderBy: 'USUARIO' | filter: $ctrl.filtrarUsuario"
								ng-click="$ctrl.selecionarUsuario(usuario, $event)"
							>
								<td class="chk">
									<input 
										type="checkbox"
										class="chk-selec-usuario" 
										ng-checked="$ctrl.verificarDestinatarioExiste(usuario) > -1"
									/>
								</td>
								<td class="text-right id">@{{ usuario.ID }}</td>
								<td class="usuario">@{{ usuario.USUARIO }}</td>
								<td class="nome">@{{ usuario.NOME }}</td>
								<td class="setor">@{{ usuario.SETOR }}</td>
							</tr>
						</tbody>
					</table>
				</div>

			</div>

		</div>

	</form>

@overwrite