<button 
	type="submit" 
	class="btn btn-success" 
	data-hotkey="f10" 
	data-loading-text="{{ Lang::get('master.gravando') }}"
	ng-click="$ctrl.tipoTela = 'incluir'"
>
	<span class="glyphicon glyphicon-ok"></span> 
	{{ Lang::get('master.gravar') }}
</button>

<button 
	type="button" 
	class="btn btn-danger btn-cancelar" 
	data-hotkey="f11"
	ng-click="$ctrl.fecharModalLiberacao()"
>
	<span class="glyphicon glyphicon-ban-circle"></span> 
	{{ Lang::get('master.cancelar') }}
</button>