<div class="alternativa-grupo" 
	ng-repeat="altern in perg.ALTERNATIVA track by $index"
>
	
	<div class="form-group">
		<label>{{ Lang::get($menu.'.label-alternativa') }}:</label>
		<input 
			type="text" 
			class="form-control input-alternativa normal-case" 
			required
			ng-model="altern.DESCRICAO"
		/>
	</div>

	<div class="form-group">
		<label>{{ Lang::get($menu.'.label-nota') }}:</label>
		<input 
			type="number" 
			class="form-control input-menor input-alternativa-nota" 
			min="0" 
			step="0.0001" 
			required
			ng-model="altern.NOTA"
			ng-disabled="altern.NIVEL_SATISFACAO == 3"
			ng-change="vm.somarNota()"
			string-to-number
		/>
	</div>

	<div class="form-group">
		<label>{{ Lang::get($menu.'.label-nivel-satisfacao') }}:</label>
		<select 
			class="form-control" 
			required 
			ng-model="altern.NIVEL_SATISFACAO"			
			ng-change="vm.selecionarNivelSatisfacao(altern)"
		>
			<option 
				ng-repeat="nivel in vm.nivelSatisfacao track by $index" 
				value="@{{ nivel.ID }}"
				ng-selected="altern.NIVEL_SATISFACAO == nivel.ID"
			>
				@{{ nivel.DESCRICAO }}
			</option>
		</select>
	</div>

	<div 
		class="form-group form-group-justif"
		ng-if="perg.TIPO_RESPOSTA == '2'"
	>
		<input 
			type="checkbox" class="form-control"
			ng-checked="altern.JUSTIFICATIVA_OBRIGATORIA == 1"
			ng-click="altern.JUSTIFICATIVA_OBRIGATORIA = (altern.JUSTIFICATIVA_OBRIGATORIA == 0 ? 1 : 0)"
		/>
		<label for="justif-obrig">{{ Lang::get($menu.'.label-justif-obrig') }}</label>

	</div>
	
	<div class="form-group">

		<button 
			type="button" class="btn btn-danger btn-alternativa-excluir" title="{{ Lang::get($menu.'.title-excluir') }}"
			ng-click="vm.excluirAlternativa(perg, $index)"
		>
			<span class="glyphicon glyphicon-trash"></span>
		</button>

	</div>

</div>

<button type="button" class="btn btn-sm btn-info add-item-dinamico" title="{{ Lang::get($menu.'.title-add-altern') }}"
	ng-click="vm.addAlternativa(perg)"
>
	<span class="glyphicon glyphicon-plus"></span>
	{{ Lang::get($menu.'.button-add-altern') }}
</button>