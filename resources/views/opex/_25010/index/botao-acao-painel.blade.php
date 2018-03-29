<button 
	type="button"
	class="btn btn-primary" 
	data-hotkey="alt+c"
	ng-click="vm.csv(2)"
>
	<span class="glyphicon glyphicon-save"></span> 
	Exportar para CSV
</button>

<button 
	type="button"
	class="btn btn-primary" 
	data-hotkey="alt+v"
	ng-click="vm.csv(1)"
>
	<span class="glyphicon glyphicon-save"></span> 
	Exportar para XLS
</button>

<button 
	type="button" 
	class="btn btn-default btn-voltar" 
	data-dismiss="modal" 
	data-hotkey="f11"
	ng-click="vm.limparPainel()"
>
	<span class="glyphicon glyphicon-chevron-left"></span> 
	{{ Lang::get('master.voltar') }}
</button>