<div class="lista-usuario-container">

	<div class="lista">

		<button 
			type="button"
			class="btn btn-default btn-selec-usuario" 
			ng-repeat="online in $ctrl.ONLINE_CHAT track by $index"
			ng-click="$ctrl.selecionarUsuario(online)"
			ng-if="$ctrl.analisarUsuario(online)"
			ng-class="{'btn-primary': $ctrl.usuarioIdConSelec == online.USUARIO_ID}"
			title="@{{ online.NOME }}"
		>
			<span class="fa fa-circle usuario-online" ng-if="online.STATUS == 1"></span>
			<span class="fa fa-circle usuario-offline" ng-if="online.STATUS == 0"></span>
			@{{ online.NOME | lowercase }}
			<span class="fa fa-commenting usuario-nova-msg" ng-if="online.NOTIF_NOVA_MSG == 1"></span>
		</button>

	</div>

</div>