@extends('helper.include.view.modal', ['id' => 'modal-pesq-usuario-notificado'])

@section('modal-header-left')

	<h4 class="modal-title">
		{{ Lang::get($menu.'.legend-notificado') }}
	</h4>

@overwrite

@section('modal-header-right')

	<button 
		type="button" 
		class="btn btn-default btn-voltar"
		data-hotkey="f11"
		ng-click="$ctrl.fecharModalPesqUsuarioNotificado()">

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
						id="input-filtrar-usuario-notificado"
						class="form-control input-maior" 
						placeholder="{{ Lang::get($menu.'.placeholder-filtrar') }}" 
						autocomplete="off"
						ng-model="$ctrl.filtrarUsuarioNotificado" 
						ng-init="$ctrl.filtrarUsuarioNotificado = ''" 
					/>
					<button type="button" class="btn input-group-addon btn-filtro" tabindex="-1"><span class="fa fa-search"></span></button>
				</div>
			</div>

		</div>

		<div class="row">
			
			<div class="form-group">

				<label class="label-sem-email">{{ Lang::get($menu.'.label-sem-email') }}</label>
				
			</div>

		</div>

		<div class="row">

			<div class="table-ec table-container table-container-usuario">

				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th class="chk"></th>
							<th class="text-right id">{{ Lang::get($menu.'.th-id') }}</th>
							<th class="usuario">{{ Lang::get($menu.'.th-usuario') }}</th>
							<th class="nome">{{ Lang::get($menu.'.th-nome') }}</th>
							<th class="setor">{{ Lang::get($menu.'.th-setor') }}</th>
							<th class="possui-email">{{ Lang::get($menu.'.th-possui-email') }}</th>
						</tr>
					</thead>

					<tbody vs-repeat vs-scroll-parent=".table-container">
						<tr 
							ng-repeat="usuario in $ctrl.listaUsuario | orderBy: 'USUARIO' | filter: $ctrl.filtrarUsuarioNotificado"
							ng-click="$ctrl.selecionarUsuarioNotificado(usuario, $event)"
						>
							<td class="chk">
								<input 
									type="checkbox"
									class="chk-selec-usuario-notificado" 
									ng-checked="$ctrl.verificarNotificadoExiste(usuario) > -1"
								/>
							</td>
							<td class="text-right id">@{{ usuario.ID }}</td>
							<td class="usuario">@{{ usuario.USUARIO }}</td>
							<td class="nome">@{{ usuario.NOME }}</td>
							<td class="setor">@{{ usuario.SETOR }}</td>

							<td 
								class="possui-email"
								title="@{{ usuario.EMAIL }}">
								
								<span 
									class="glyphicon glyphicon-ok sim"
									ng-if="usuario.EMAIL.length > 0"></span>
								<span 
									class="glyphicon glyphicon-remove nao"
									ng-if="usuario.EMAIL.length == 0"></span>
							</td>
						</tr>
					</tbody>
				</table>
				
			</div>

		</div>

	</form>

@overwrite