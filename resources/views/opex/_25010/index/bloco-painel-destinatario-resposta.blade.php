<div class="destinatario-resposta-bloco">

	<div class="table-ec table-container-resposta">

		<table class="table table-bordered table-header">

			<thead>
				<tr>
					<th class="pergunta">{{ Lang::get($menu.'.th-pergunta') }}</th>
					<th class="resposta">{{ Lang::get($menu.'.th-resposta') }}</th>
					<th class="nota text-right">{{ Lang::get($menu.'.th-nota') }}</th>
					
					<th 
						class="nivel-satisfacao"
						ng-if="vm.formulario.TIPO != 3">{{ Lang::get($menu.'.th-nivel-satisfacao') }}</th>

					<th class="justificativa">{{ Lang::get($menu.'.th-justificativa') }}</th>
				</tr>
			</thead>
		
			<tbody>
				<tr
					ng-repeat="resposta in vm.destinatarioResposta track by $index"
				>
					<td class="pergunta" title="@{{ resposta.FORMULARIO_PERGUNTA_ORDEM | lpad : [2, '0'] }}@{{ '. '+ resposta.PERGUNTA_DESCRICAO }}">@{{ resposta.FORMULARIO_PERGUNTA_ORDEM | lpad : [2, '0'] }}@{{ '. '+ resposta.PERGUNTA_DESCRICAO }}</td>
					<td class="resposta" title="@{{ resposta.ALTERNATIVA_DESCRICAO }}">@{{ resposta.ALTERNATIVA_DESCRICAO }}</td>
					<td class="nota text-right">@{{ resposta.ALTERNATIVA_NOTA | number }}</td>
					
					<td 
						class="nivel-satisfacao"
						ng-if="vm.formulario.TIPO != 3">@{{ resposta.NIVEL_SATISFACAO_DESCRICAO }}</td>

					<td class="justificativa" title="@{{ resposta.JUSTIFICATIVA }}">@{{ resposta.JUSTIFICATIVA }}</td>
				</tr>

				<tr
					ng-if="vm.destinatarioResposta.length == 0"
				>
					<td class="vazio" colspan="5">{{ Lang::get($menu.'.td-vazio') }}</td>
				</tr>
			</tbody>

		</table>
		
	</div>

	<div class="grafico-container">

		<label>{{ Lang::get($menu.'.label-destinatario-satisfacao') }}</label>
		<div id="grafico-satisf-usuario"></div>

		<div
			class="sobre-delfa-bloco" 
			ng-if="vm.formulario.TIPO == 3 && vm.painel.CLIENTE_SELECIONADO.NOTA_DELFA >= 0">

			<label class="lbl-titulo">{{ Lang::get($menu.'.label-sobre-delfa') }}:</label>
			<br>
			<label class="lbl-rotulo">{{ Lang::get($menu.'.label-nota-delfa') }}:</label>
			<label class="lbl-valor">@{{ vm.painel.CLIENTE_SELECIONADO.NOTA_DELFA | number }}</label>
			<br>
			<label class="lbl-rotulo">{{ Lang::get($menu.'.label-obs-delfa') }}:</label>
			<label class="lbl-valor">@{{ vm.painel.CLIENTE_SELECIONADO.OBSERVACAO_DELFA }}</label>
		</div>

	</div>

</div>