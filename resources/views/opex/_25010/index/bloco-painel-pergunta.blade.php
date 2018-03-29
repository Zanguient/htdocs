<fieldset class="pergunta-container">

	<legend>{{ Lang::get($menu.'.legend-pergunta') }}</legend>

	<div class="panel-group">

		<div 
			class="panel panel-primary panel-pergunta" 
			ng-repeat="pergunta in vm.painel.PERGUNTA"
		>

		    <div 
		    	class="panel-heading" 
		    	id="heading@{{ pergunta.FORMULARIO_PERGUNTA_ID }}-pergunta" 
		    	role="tab"
		    >
	            <span class="pergunta">
	                @{{ pergunta.PERGUNTA_ORDEM | lpad : [2, '0'] }}@{{ '. '+ pergunta.PERGUNTA_DESCRICAO }}
	            </span>
		    </div>

		    <div 
		    	class="panel-collapse collapse in"
		    	id="collapse@{{ pergunta.FORMULARIO_PERGUNTA_ID }}-pergunta"
		    	aria-labelledby="heading@{{ pergunta.FORMULARIO_PERGUNTA_ID }}-pergunta"
		    	aria-expanded="true"
		    	role="tabpanel"
		    >
	        	
	        	<div class="panel-body">

					<div class="grafico-container">
						<div id="grafico-satisf-pergunta-@{{ pergunta.FORMULARIO_PERGUNTA_ID }}"></div>
					</div>

					<div class="grafico-container">
						<div id="grafico-satisf-alternativa-@{{ pergunta.FORMULARIO_PERGUNTA_ID }}"></div>
					</div>

	        	</div>

	        </div>

		</div>

	</div>

</fieldset>