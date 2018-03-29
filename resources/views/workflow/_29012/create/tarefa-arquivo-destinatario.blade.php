<div class="arquivo-container">

	<label>{{ Lang::get($menu.'.label-arquivos-destinatario') }}:</label>

	<div 
		class="button-container"
		ng-if="tarefa.ARQUIVO_DESTINATARIO.length > 0">

		<button 
			type="button" 
			class="btn btn-sm btn-info add-item-dinamico" 
			title="{{ Lang::get($menu.'.title-add-arquivo') }}"
			ng-click="$ctrl.addArquivo(tarefa)"
			ng-disabled="
				tarefa.STATUS_CONCLUSAO != '1'
				|| tarefa.ARQUIVO_DESTINATARIO[tarefa.ARQUIVO_DESTINATARIO.length-1]['BINARIO'] == null
			">

			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get($menu.'.button-add-arquivo') }}
		</button>

		{{-- 
			Desabilitado se:
		 	- o último arquivo não tiver sido escolhido ou possuir ID ou já tiver sido gravado (flag definida via script);
		 	- e se não houver algum arquivo para excluir.
		--}}
		<button 
			type="button" 
			class="btn btn-sm btn-success" 
			title="{{ Lang::get($menu.'.title-gravar-arquivo') }}"
			ng-click="$ctrl.gravarWorkflowItemArquivoDoDestinatario(tarefa)"
			ng-disabled="
				(tarefa.ARQUIVO_DESTINATARIO[tarefa.ARQUIVO_DESTINATARIO.length-1]['BINARIO'] == null
					|| tarefa.ARQUIVO_DESTINATARIO[tarefa.ARQUIVO_DESTINATARIO.length-1]['ID'] != undefined)
				&& tarefa.ARQUIVO_DESTINATARIO_EXCLUIR == undefined
			">

			<span class="glyphicon glyphicon-ok"></span>
			{{ Lang::get($menu.'.button-gravar-arquivo') }}
		</button>

	</div>
	
	<div class="scroll">

		<span ng-if="tarefa.ARQUIVO_DESTINATARIO.length == 0">{{ Lang::get($menu.'.label-arquivos-nenhum') }}</span>

		<div
			class="form-group"
			ng-repeat="arq in tarefa.ARQUIVO_DESTINATARIO track by $index">

			<input 
				type="file" 
				class="form-control input-maior arquivo-binario"
				ngf-select="$ctrl.processarArquivo($event, arq)"
				ng-model="arq.BINARIO"
				ng-if="!arq.BINARIO"
				ng-disabled="tarefa.STATUS_CONCLUSAO != '1'">

			{{-- Pré-visualização somente para imagem --}}
			<img
				class="pre-visualizacao-arquivo"
				ngf-src="arq.BINARIO"
				ng-if="
					(arq.TIPO.indexOf('image') > -1) 
				 || (arq.TIPO.indexOf('png') > -1) 
				 || (arq.TIPO.indexOf('jpg') > -1) 
				 || (arq.TIPO.indexOf('gif') > -1)">

			{{-- Pré-visualização quando não for imagem --}}
			<span
				class="pre-visualizacao-arquivo fa fa-file-o"
				ng-if="
					(arq.TIPO.indexOf('image') == -1) 
				 && (arq.TIPO.indexOf('png') == -1) 
				 && (arq.TIPO.indexOf('jpg') == -1) 
				 && (arq.TIPO.indexOf('gif') == -1)"></span>

			<input
				type="text"
				class="form-control input-maior normal-case arquivo-nome"
				disabled
				ng-model="arq.NOME"
				ng-value="arq.ID 
							? arq.ID +' - '+ arq.NOME 
							: arq.NOME"
				ng-click="arq.VER = true"
				ng-if="arq.BINARIO">

			<button 
				type="button" 
				class="btn btn-info btn-ver-arquivo"
				ng-click="arq.VER = true"
				ng-disabled="!arq.ID || !arq.BINARIO">

				<span class="glyphicon glyphicon-eye-open"></span>
			</button>

			<button 
				type="button" 
				class="btn btn-danger" 
				title="{{ Lang::get($menu.'.title-excluir-arquivo') }}"
				ng-click="$ctrl.excluirArquivo(tarefa, arq)"
				ng-disabled="tarefa.STATUS_CONCLUSAO != '1'">

				<span class="glyphicon glyphicon-trash"></span>
			</button>

			<div 
				class="visualizar-arquivo"
				ng-show="arq.VER">

				<a 
					class="btn btn-default download-arquivo" 
					href="@{{ arq.BINARIO }}" 
					download 
					data-hotkey="alt+b">
					
					<span class="glyphicon glyphicon-download"></span>
					{{ Lang::get('master.download') }}
				</a>
				
				<button 
					type="button" 
					class="btn btn-default esconder-arquivo" 
					data-hotkey="f11"
					ng-click="arq.VER = false">
					
					<span class="glyphicon glyphicon-chevron-left"></span>
					{{ Lang::get('master.voltar') }}
				</button>

				{{-- Visualização somente para imagem e pdf --}}
				<object 
					data="@{{ arq.BINARIO }}"
					ng-class="{imagem: 
						(arq.TIPO.indexOf('image') > -1)
					 || (arq.TIPO.indexOf('png') > -1) 
					 || (arq.TIPO.indexOf('jpg') > -1) 
					 || (arq.TIPO.indexOf('gif') > -1)}"
					ng-if="
						(arq.TIPO.indexOf('pdf') > -1) 
					 || (arq.TIPO.indexOf('image') > -1) 
					 || (arq.TIPO.indexOf('png') > -1) 
					 || (arq.TIPO.indexOf('jpg') > -1) 
					 || (arq.TIPO.indexOf('gif') > -1)"></object>

				{{-- Msg de visualização indisponível. --}}
				<label
					class="lbl-visualizacao-indisponivel"
					ng-if="
						(arq.TIPO.indexOf('pdf') == -1) 
					 && (arq.TIPO.indexOf('image') == -1) 
					 && (arq.TIPO.indexOf('png') == -1) 
					 && (arq.TIPO.indexOf('jpg') == -1) 
					 && (arq.TIPO.indexOf('gif') == -1)">

					{{ Lang::get($menu.'.label-msg-vis-indisp') }}
				</label>

			</div>

		</div>
	
	</div>

	

</div>