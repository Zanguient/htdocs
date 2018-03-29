<div id="pedido-item-escolhido-resumo">

	<div class="form-group">

		<label>{{ Lang::get($menu.'.label-quantidade-total') }}:</label>

		<div class="input-group left-icon">

			<input 
				type="text" 
				class="form-control input-menor"
				readonly
				ng-model="$ctrl.quantidadeGeralTotal"
				ng-value="$ctrl.quantidadeGeralTotal | number"
			/>

			<div class="input-group-addon">@{{ $ctrl.pedidoItemEscolhido[0].produto.UM }}</div>

		</div>
	</div>

	<div class="form-group">

		<label>{{ Lang::get($menu.'.label-valor-total') }}:</label>

		<div class="input-group left-icon">

			<div class="input-group-addon">R$</div>

			<input 
				type="text" 
				class="form-control"
				readonly
				ng-model="$ctrl.valorGeralTotal"
				ng-value="$ctrl.valorGeralTotal | number:2"
			/>

		</div>
	</div>

	<div class="form-group">
		<label class="label label-danger data-cliente-info">{{ Lang::get($menu.'.msg-prev-fat') }}</label>
	</div>

</div>

