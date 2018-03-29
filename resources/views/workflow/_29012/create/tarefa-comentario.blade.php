<div class="comentario-container">

	<label>{{ Lang::get($menu.'.label-comentarios') }}:</label>

	<div 
		class="button-container"
		ng-if="tarefa.COMENTARIO.length > 0">

		<button 
			type="button" 
			class="btn btn-sm btn-info" 
			title="{{ Lang::get($menu.'.title-add-comentario') }}"
			ng-click="$ctrl.addComentario(tarefa)"
			ng-disabled="
				(tarefa.STATUS_CONCLUSAO != '1')
				|| (tarefa.COMENTARIO[tarefa.COMENTARIO.length-1].COMENTARIO == null 
					|| tarefa.COMENTARIO[tarefa.COMENTARIO.length-1].COMENTARIO == '')
			">

			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get($menu.'.button-add-comentario') }}
		</button>

		{{--
			Desabilitado se:
			- o último comentário não possuir ID ou se estiver vazio;
			- e se não houver nenhum comentário marcado para exclusão.
		--}}
		<button 
			type="button" 
			class="btn btn-sm btn-success" 
			title="{{ Lang::get($menu.'.title-gravar-comentario') }}"
			ng-click="$ctrl.gravarWorkflowItemTarefaComentario(tarefa)"
			ng-disabled="
				(tarefa.COMENTARIO[tarefa.COMENTARIO.length-1].ID != undefined
					|| (tarefa.COMENTARIO[tarefa.COMENTARIO.length-1].COMENTARIO == null || tarefa.COMENTARIO[tarefa.COMENTARIO.length-1].COMENTARIO == ''))
				&& tarefa.existeComentarioParaExcluir != true
			">

			<span class="glyphicon glyphicon-ok"></span>
			{{ Lang::get($menu.'.button-gravar-comentario') }}
		</button>
		
	</div>

	<div class="scroll">

		<span ng-if="tarefa.COMENTARIO.length == 0">{{ Lang::get($menu.'.label-comentario-nenhum') }}</span>

		<div 
			class="form-group" 
			ng-repeat="coment in tarefa.COMENTARIO track by $index"
			ng-if="coment.STATUSEXCLUSAO != '1'">

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="2" 
					cols="45" 
					maxlength="500"
					ng-disabled="coment.USUARIO_ID != null || tarefa.STATUS_CONCLUSAO != '1'"
					ng-model="coment.COMENTARIO"></textarea>

				<span 
					class="comentario-usuario"
					ng-if="coment.USUARIO_ID != null"
					ng-bind="'Por: '+ (coment.USUARIO_ID | lpad:[5,'0']) +' - '+ coment.USUARIO_DESCRICAO"
					title="@{{ 'Por: '+ (coment.USUARIO_ID | lpad:[5,'0']) +' - '+ coment.USUARIO_DESCRICAO }}"></span>

				<span class="contador"><span>@{{ 500 - coment.COMENTARIO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
			
			</div>

			<button 
				type="button" 
				class="btn btn-danger" 
				title="{{ Lang::get($menu.'.title-excluir-comentario') }}"
				ng-click="$ctrl.excluirComentario(tarefa, coment)"
				ng-disabled="tarefa.STATUS_CONCLUSAO != '1'">

				<span class="glyphicon glyphicon-trash"></span>
			</button>

		</div>

	</div>

</div>