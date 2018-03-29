<div class="panel-group" id="accordionUsu" role="tablist" aria-multiselectable="true">

	<div 
		class="panel panel-default"
		ng-repeat="tarefa in $ctrl.tarefaPorUsuario | orderBy:['SEQUENCIA','ID'] track by $index"
		ng-if="tarefa.STATUSEXCLUSAO != '1'">

		<div 
			class="panel-heading" 
			role="tab" 
			id="headingUsu-@{{ $index }}"
			ng-class="{
				'parado'	: tarefa.STATUS_CONCLUSAO == '0' || tarefa.STATUS_CONCLUSAO == '4', 
				'iniciado'	: tarefa.STATUS_CONCLUSAO == '1',
				'pausado'	: tarefa.STATUS_CONCLUSAO == '2',
				'concluido'	: tarefa.STATUS_CONCLUSAO == '3'
			}">

			<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseUsu-@{{ $index }}" aria-expanded="false" aria-controls="collapseUsu-@{{ $index }}">
				<span>
					<span>{{ Lang::get($menu.'.label-id') }}:</span>
					<span ng-bind="tarefa.ID | lpad:[5,'0']"></span>
				</span>
				<span>
					<span>{{ Lang::get($menu.'.label-sequencia-abrev') }}:</span>
					<span ng-bind="tarefa.SEQUENCIA | lpad:[3,'0']"></span>
				</span>
				<span>
					<span>{{ Lang::get($menu.'.label-titulo') }}:</span>
					<span 
						class="titulo" 
						title="@{{ tarefa.TITULO }}" 
						ng-bind="tarefa.TITULO"></span>
				</span>
				<span>
					<span>{{ Lang::get($menu.'.label-previsao') }}:</span>
					<span 
						class="data-prevista"
						ng-bind="(tarefa.DATAHORA_INI_PREVISTA) +' - '+ (tarefa.DATAHORA_FIM_PREVISTA)"></span>
				</span>
				<span>
					<span>{{ Lang::get($menu.'.label-tempo-previsao') }}:</span>
					<span 
						class="tempo-previsto" 
						ng-bind="tarefa.TEMPO_PREVISTO_HUMANIZE"></span>
				</span>
				<span>
					<span>{{ Lang::get($menu.'.label-recalculado') }}:</span>
					<span 
						class="data-recalculada" 
						ng-bind="(tarefa.DATAHORA_INI_RECALCULADA) +' - '+ (tarefa.DATAHORA_FIM_RECALCULADA)"></span>
				</span>
				<span>
					<span>{{ Lang::get($menu.'.label-efetuado') }}:</span>
					<span
						class="data-efetuada"
						ng-bind="(tarefa.DATAHORA_INI_CONCLUSAO) +' - '+ (tarefa.DATAHORA_FIM_CONCLUSAO)"></span>
				</span>
				<span>
					<span>{{ Lang::get($menu.'.label-tempo-efetuado') }}:</span>
					<span 
						class="tempo-conclusao" 
						ng-bind="tarefa.TEMPO_CONCLUSAO_HUMANIZE"></span>
				</span>
				<span>
					<span>{{ Lang::get($menu.'.label-eficiencia-abrev') }}:</span>
					<span 
						class="tempo-eficiencia" 
						ng-class="{negativo: tarefa.PERCENTUAL_EFICIENCIA_TEMPO < 100}"
						ng-bind="(tarefa.PERCENTUAL_EFICIENCIA_TEMPO) +'%'"></span>
				</span>
				<span>
					<span>{{ Lang::get($menu.'.label-status') }}:</span>
					
					<span
		    			class="status fa fa-stop"
		    			title="{{ Lang::get($menu.'.title-status-parado') }}"
		    			ng-if="tarefa.STATUS_CONCLUSAO == '0' || tarefa.STATUS_CONCLUSAO == '4'"></span>
		    		<span
		    			class="status fa fa-play"
		    			title="{{ Lang::get($menu.'.title-status-iniciado') }}"
		    			ng-if="tarefa.STATUS_CONCLUSAO == '1'"></span>
		    		<span
		    			class="status fa fa-pause"
		    			title="{{ Lang::get($menu.'.title-status-pausado') }}"
		    			ng-if="tarefa.STATUS_CONCLUSAO == '2'"></span>
		    		<span
		    			class="status fa fa-check"
		    			title="{{ Lang::get($menu.'.title-status-concluido') }}"
		    			ng-if="tarefa.STATUS_CONCLUSAO == '3'"></span>
				</span>
			</a>

		</div>

		<div id="collapseUsu-@{{ $index }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingUsu-@{{ $index }}">

  			<div class="panel-body">
  				
  				<div class="tarefa-descricao-container">
      				<label>{{ Lang::get($menu.'.label-descricao') }}:</label>
					<span ng-bind="tarefa.DESCRICAO"></span>
				</div>

				@include('workflow._29012.create.tarefa-destinatario')
				@include('workflow._29012.create.tarefa-notificado')
				@include('workflow._29012.create.tarefa-movimentacao')
				@include('workflow._29012.create.tarefa-comentario')
				@include('workflow._29012.create.tarefa-arquivo')
				@include('workflow._29012.create.tarefa-arquivo-destinatario')

  			</div>

  		</div>

	</div>

</div>