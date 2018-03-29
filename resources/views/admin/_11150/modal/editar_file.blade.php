	@extends('helper.include.view.modal', ['id' => 'modal-file', 'class_size' => 'modal-lg'])

	@section('modal-header-left')

	<h4 class="modal-title">
		Arquivos
	</h4>

	@overwrite

	@section('modal-header-right')
		
		<button ng-click="vm.Arquivos.gravar(vm.painel_id, vm.caso_id, vm.user)" ng-disabled="vm.Arquivos.dados.length <= 0 || vm.Arquivos.comentario.length <= 0" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando...">
			<span class="glyphicon glyphicon-ok"></span> 
			Gravar
		</button>

		<a  ng-click="vm.Arquivos.canselar()"  class="btn btn-danger btn-cancelar" data-hotkey="f11">
			<span class="glyphicon glyphicon-ban-circle"></span> 
			Cancelar
		</a>

	@overwrite

	@section('modal-body')

	<div class="arquivo-container">
	
	<label>Novo:</label>
	<div class="grupo-btn-arquivos top">
		<button 
			type="button" 
			class="btn btn-sm btn-info" 
			ng-click="vm.Arquivos.addArquivo()">

			<span class="glyphicon glyphicon-plus"></span>
			Adicionar Arquivo
		</button>
	</div>

	<div ng-repeat="arq in vm.Arquivos.data.slice().reverse() track by $index">
		
		<div class="form-group">
			<input 
				type="file" multiple 
				class="form-control input-maior arquivo-binario"
				ngf-select="vm.Arquivos.processarArquivo($event, arq)"
				ng-model="arq.BINARIO"
				ng-if="!arq.BINARIO">

			{{-- Pré-visualização somente para imagem --}}
			<img
				class="pre-visualizacao-arquivo"
				ngf-src="arq.BINARIO"
				ng-if="
					(arq.TIPO.indexOf('image') > -1) 
				 || (arq.TIPO.indexOf('png')   > -1) 
				 || (arq.TIPO.indexOf('jpg')   > -1) 
				 || (arq.TIPO.indexOf('gif')   > -1)">

			{{-- Pré-visualização quando não for imagem --}}

			<span
				class="pre-visualizacao-arquivo fa fa-file-o @{{arq.CSS}}"
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
				ng-disabled="!arq.ID || !arq.BINARIO"
				style="display: none">
				<span class="glyphicon glyphicon-eye-open"></span>
			</button>

			<button 
				type="button" 
				class="btn btn-danger" 
				title="{{ Lang::get($menu.'.title-excluir-arquivo') }}"
				ng-click="vm.Arquivos.excluirArquivo(arq)"
				>

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

					Visualização indisponível!
				</label>

			</div>
		</div>
	</div>
	<div class="form-group"
		style="width: 100%;" 
		ng-if="vm.Arquivos.data.length > 0">
		<textarea ng-model=" vm.Arquivos.comentario" class="form-control"></textarea>
	</div>

</div>

@overwrite