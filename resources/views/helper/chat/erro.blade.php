<div
	class="erro-container"
	ng-if="$ctrl.houveErro || $ctrl.reconectando">
	
	<div>
		<label>Chat desconectado.</label>

		<button 
			type="button"
			class="btn btn-default"
			ng-click="$ctrl.reconectarWebSocket(true)">
				
			<span 
				class="fa"
				ng-class="{'fa-plug': !$ctrl.reconectando, 'fa-circle-o-notch': $ctrl.reconectando}"></span>
			
			<span 
				ng-if="!$ctrl.reconectando">Reconectar</span>

			<span 
				ng-if="$ctrl.reconectando">Reconectando...</span>
		</button>
	</div>

</div>