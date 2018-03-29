<div 
	class="msg-container"
	ng-if="$ctrl.usuarioIdConSelec > 0"
>
	<div class="conversa">

		<div class="conversa-header">

			<button 
				type="button"
				class="btn btn-xs btn-default"
				ng-click="$ctrl.consultarHistoricoConversa()"
				ng-if="$ctrl.usuarioEhCliente == false"
			>
				<span class="fa fa-history"></span>
				Conversas anteriores
			</button>

		</div>

		<div class="conversa-msg-container">

			<div 
				class="conversa-msg"
				ng-repeat="conversa in $ctrl.MENSAGE track by $index"
				ng-if="
					(conversa.REMETENTE == true && conversa.PARA == $ctrl.usuarioIdConSelec) 
				 || (conversa.DE == $ctrl.usuarioIdConSelec && conversa.PARA == $ctrl.usuarioIdConAtual)
				"
				ng-class="{'msg-remetente': conversa.REMETENTE == true, 'msg-destinatario': conversa.REMETENTE == false}"
			>
				<label class="msg">@{{ conversa.MSG }}</label>
				<label class="data">

					<span>@{{ conversa.DATA | date:"dd/MM/yyyy HH:mm" }}</span>

					{{-- Exibe apenas se for do setor Comercial, não estiver enviando a msg e houver chave na tela --}}
					<span 
						class="chave" 
						ng-if="$ctrl.usuarioEhCliente == false && $ctrl.usuarioEhRepresentante == false && conversa.REMETENTE == false && conversa.CHAVE > 0">
						 - Chave: @{{ conversa.CHAVE | lpad:[5,'0'] }}</span>

				</label>
			</div>

		</div>

	</div>

	<div class="digitar-msg">

		<textarea
			class="form-control normal-case"
			rows="2"
			cols="50"
			placeholder="Digite sua mensagem..."
			ng-model="$ctrl.mensagem"
			ng-focus="$ctrl.selecionarUsuario()"
		></textarea>

		<button 
			type="button" 
			class="btn btn-default" 
			title="Enviar mensagem" 
			ng-click="$ctrl.enviarMsg()"
			ng-disabled="!$ctrl.mensagem"
		>
			<span class="fa fa-paper-plane"></span>
		</button>

	</div>

</div>