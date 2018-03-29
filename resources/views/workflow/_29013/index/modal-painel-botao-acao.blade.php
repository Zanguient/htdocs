<div>

	<a
		href="_28000/78?AUTO=1&WORKFLOW_ITEM_ID=@{{ $ctrl.infoGeral.ID }}"
		target="_blank"
		class="btn btn-warning" 
		data-hotkey="alt+r">
		
		<span class="glyphicon glyphicon-print"></span> 
		{{ Lang::get($menu.'.button-relatorio') }}
	</a>

	<button 
		type="button" 
		class="btn btn-default btn-voltar" 
		data-hotkey="f11"
		ng-click="$ctrl.fecharModal()">
		
		<span class="glyphicon glyphicon-chevron-left"></span> 
		{{ Lang::get('master.voltar') }}
	</button>

</div>