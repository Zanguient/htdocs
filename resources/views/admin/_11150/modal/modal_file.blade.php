	@extends('helper.include.view.modal', ['id' => 'modal-file', 'class_size' => 'modal-lg'])

	@section('modal-header-left')

	<h4 class="modal-title">
		Feed
	</h4>

	@overwrite

	@section('modal-header-right')
		
		<button ng-click="vm.Arquivos.gravar(vm.painel_id, vm.caso_id, vm.user, vm.feed_editar, vm.tipo_feed)" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando..." ng-if="vm.tipo_feed != 1">
			<span class="glyphicon glyphicon-ok"></span> 
			Gravar
		</button>

		<button ng-click="vm.Arquivos.gravar(vm.painel_id, vm.caso_id, vm.user, vm.feed_editar, vm.tipo_feed)" ng-disabled="!(vm.Arquivos.de.length > 0)  || !(vm.Arquivos.para.length > 0) || !(vm.Arquivos.assunto.length > 0) " class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando..."  ng-if="vm.tipo_feed == 1">
			<span class="glyphicon glyphicon-ok"></span> 
			Enviar
		</button>

		<button ng-click="vm.Acoes.excluirFeedArquivo(vm.painel_id, vm.caso_id, vm.user, vm.feed_editar)" ng-if="vm.Arquivos.editando == true" class="btn btn-danger" data-hotkey="f12" data-loading-text="Excluindo...">
			<span class="glyphicon glyphicon-trash"></span> 
			Excluir
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
				class="pre-visualizacao-arquivo A@{{arq.CSS != unknown ? arq.CSS : arq.TIPO}}"
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

	<button style="float: right; margin-top: -40px;" ng-click="vm.editar_contato = !vm.editar_contato"  ng-if="vm.tipo_feed == 1" class="btn btn-danger">
			<span class="glyphicon glyphicon-plus"></span>
	</button>

	<div class="email-container" ng-if="vm.tipo_feed == 1 && vm.editar_contato == true">
		<div class="form-group EmailContato1">
            <label>De:</label>
            <div class="contato-div-container" style="background-color: #eeeeee;" ng-click="vm.EmailContato1.setFoco()">
				<div ng-repeat="valor in vm.EmailContato1.itens track by $index" class="contato-iten">@{{valor}} 
				@php /* 
					<div ng-click="vm.EmailContato1.deletarItem($index)">x</div>
				@php */
				</div>
				<input ng-blur="vm.EmailContato1.blur()" disabled type="text" ng-keydown="vm.EmailContato1.keydown($event)" ng-keypress="vm.EmailContato1.keypress($event)" ng-model="vm.EmailContato1.valor" required="required" name="descricao" class="form-control input-maior">
            </div>
            <div class="lista-email" ng-if="vm.EmailContato1.valor.length >= 3">
				<div ng-keydown="vm.EmailContato1.listaKeydown($event)" tabindex="-1" ng-keypress="vm.EmailContato1.addEmail(contato)" class="lista-itens" ng-repeat="contato in vm.Contatos  | filter:vm.EmailContato1.valor" ng-click="vm.EmailContato1.addEmail(contato)">
					<span>@{{contato.NOME}}</span>   <span><@{{contato.EMAIL}}></span>
				</div>
			</div>            
        </div>
        <div class="form-group EmailContato2">
            <label>Para:</label>
            <div class="contato-div-container" ng-click="vm.EmailContato2.setFoco()">
				<div ng-repeat="valor in vm.EmailContato2.itens track by $index" class="contato-iten">@{{valor}}<div ng-click="vm.EmailContato2.deletarItem($index)">x</div></div>
				<input  ng-blur="vm.EmailContato2.blur()" type="text" ng-keydown="vm.EmailContato2.keydown($event)" ng-keypress="vm.EmailContato2.keypress($event)" ng-model="vm.EmailContato2.valor" required="required" name="descricao" class="form-control input-maior">
            </div>
        	<div class="lista-email" ng-if="vm.EmailContato2.valor.length >= 3">
				<div ng-keydown="vm.EmailContato2.listaKeydown($event)" tabindex="-1" ng-keypress="vm.EmailContato2.addEmail(contato)" class="lista-itens" ng-repeat="contato in vm.Contatos  | filter:vm.EmailContato2.valor" ng-click="vm.EmailContato2.addEmail(contato)">
					<span>@{{contato.NOME}}</span>   <span><@{{contato.EMAIL}}></span>
				</div>
			</div>            
        </div>
        <div class="form-group EmailContato3">
            <label>Cc:</label>
            <div class="contato-div-container" ng-click="vm.EmailContato3.setFoco()">
				<div ng-repeat="valor in vm.EmailContato3.itens track by $index" class="contato-iten">@{{valor}}<div ng-click="vm.EmailContato3.deletarItem($index)">x</div></div>
				<input  ng-blur="vm.EmailContato3.blur()" type="text" ng-keydown="vm.EmailContato3.keydown($event)" ng-keypress="vm.EmailContato3.keypress($event)" ng-model="vm.EmailContato3.valor" required="required" name="descricao" class="form-control input-maior">
            </div>
        	<div class="lista-email" ng-if="vm.EmailContato3.valor.length >= 3">
				<div ng-keydown="vm.EmailContato3.listaKeydown($event)" tabindex="-1" ng-keypress="vm.EmailContato3.addEmail(contato)" class="lista-itens" ng-repeat="contato in vm.Contatos  | filter:vm.EmailContato3.valor" ng-click="vm.EmailContato3.addEmail(contato)">
					<span>@{{contato.NOME}}</span>   <span><@{{contato.EMAIL}}></span>
				</div>
			</div>            
        </div>
        <div class="form-group EmailContato4">
            <label>Cco:</label>
            <div class="contato-div-container" ng-click="vm.EmailContato4.setFoco()">
				<div ng-repeat="valor in vm.EmailContato4.itens track by $index" class="contato-iten">@{{valor}}<div ng-click="vm.EmailContato4.deletarItem($index)">x</div></div>
				<input  ng-blur="vm.EmailContato4.blur()" type="text" ng-keydown="vm.EmailContato4.keydown($event)" ng-keypress="vm.EmailContato4.keypress($event)" ng-model="vm.EmailContato4.valor" required="required" name="descricao" class="form-control input-maior">
            </div>
        	<div class="lista-email" ng-if="vm.EmailContato4.valor.length >= 3">
				<div ng-keydown="vm.EmailContato4.listaKeydown($event)" tabindex="-1" ng-keypress="vm.EmailContato4.addEmail(contato)" class="lista-itens" ng-repeat="contato in vm.Contatos  | filter:vm.EmailContato4.valor" ng-click="vm.EmailContato4.addEmail(contato)">
					<span>@{{contato.NOME}}</span>   <span><@{{contato.EMAIL}}></span>
				</div>
			</div>            
        </div>
        <div class="form-group">
            <label>Assunto:</label>
            <input type="text" ng-model="vm.Arquivos.assunto" required="required" name="descricao" class="form-control input-maior">
        </div>
	</div>
	<div class="form-group"
		style="width: 100%;">
		<textarea style="height: 130px;" name="editor1" id="editor1" rows="10" cols="80" ng-model=" vm.Arquivos.comentario" class="form-control"></textarea>
	</div>

</div>

@overwrite