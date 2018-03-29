@extends('helper.include.view.modal', ['id' => 'modal-destinatario-usuario'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.legend-destinatario-usuario') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="f11">
		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get($menu.'.button-voltar') }}
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
						ng-model="vm.filtrarUsuario" 
						ng-init="vm.filtrarUsuario = ''" 
					/>
					<button type="button" class="btn input-group-addon btn-filtro" tabindex="-1"><span class="fa fa-search"></span></button>
				</div>
			</div>

		</div>

		<div class="row">

			<div class="table-ec table-selec-usuario">

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
									
					<tbody vs-repeat vs-scroll-parent=".table-selec-usuario">
						<tr 
							ng-repeat="usuario in vm.listaUsuario | orderBy: 'USUARIO' | filter: vm.filtrarUsuario"
							ng-click="vm.selecionarUsuario(usuario, $event)">

							<td class="chk">
								<input 
									type="checkbox"
									class="chk-selec-usuario" 
									ng-checked="vm.verificarUsuarioEhDestinatario(usuario) > -1"
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

	</form>

@overwrite