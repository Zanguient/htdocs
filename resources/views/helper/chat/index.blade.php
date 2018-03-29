<div 
	class="chat" 
	ng-class="{'minimizado': $ctrl.estadoJanelaChat == 0}"
>

	<label 
		class="chat-titulo"
		ng-click="$ctrl.estadoJanelaChat = ($ctrl.estadoJanelaChat == 0) ? 1 : 0;"
	>
		<span 
			class="fa fa-comment" 
			ng-if="$ctrl.estadoJanelaChat == 0"
		></span>

		<span 
			class="fa fa-circle" 
			ng-if="$ctrl.estadoJanelaChat == 0 && $ctrl.notifNovaMsgGeral == 1"
		></span>

		<span 
			ng-if="$ctrl.estadoJanelaChat == 1"
		>
			Chat
		</span>

		<span 
			class="fa fa-window-minimize"
			ng-if="$ctrl.estadoJanelaChat == 1"
		></span>

	</label>

	@include('helper.chat.erro')	
	@include('helper.chat.listaUsuario')	
	@include('helper.chat.msg')	

</div>