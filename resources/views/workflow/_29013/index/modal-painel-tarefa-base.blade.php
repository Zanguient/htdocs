<fieldset id="tarefa">

	<legend>{{ Lang::get($menu.'.legend-tarefa') }}</legend>

	<ul 
		class="nav nav-pills" 
		id="tab-tarefa"
		role="tablist">

		<li 
	    	role="presentation" 
	    	class="active">

	    	<a 
	    		href="#tarefa-geral" 
	    		aria-controls="tarefa-geral" 
	    		role="tab" 
	    		data-toggle="tab">

	    		{{ Lang::get($menu.'.a-todas-tarefas') }}
	    	</a>

	    </li>

	    <li 
	    	role="presentation">

	    	<a 
	    		href="#tarefa-usuario" 
	    		aria-controls="tarefa-usuario" 
	    		role="tab" 
	    		data-toggle="tab">

	    		{{ Lang::get($menu.'.a-suas-tarefas') }}
	    	</a>

	    </li>

	</ul>

	<div class="tab-content">
    	
    	<div 
    		role="tabpanel" 
    		class="tab-pane fade in active"
    		id="tarefa-geral">

    		@include('workflow._29013.index.modal-painel-tarefa')

		</div>

		<div 
    		role="tabpanel" 
    		class="tab-pane fade"
    		id="tarefa-usuario">

    		@include('workflow._29013.index.modal-painel-tarefa-por-usuario')

		</div>

	</div>

</fieldset>