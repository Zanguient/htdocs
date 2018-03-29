<fieldset id="info-geral" ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-info-gerais') }}</legend>

	<div class="row">

		<div 
			style="cursor: pointer; color: blue;"
			ng-if="$ctrl.tipoTela != 'incluir'"
			data-consulta-historico
			data-tabela="TBPEDIDO"
			data-tabela-id="@{{ $ctrl.infoGeral.PEDIDO }}"
			type="button"
			class="form-group chave gerar-historico"
			data-hotkey="alt+h">
        	<span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
    	</div>

		<div 
			class="form-group chave" 
			style="cursor: pointer; color: blue;" 
			ng-click="$ctrl.gerarPDF()"
			ng-if="$ctrl.tipoTela != 'incluir'"
		>
			<span class="glyphicon glyphicon-list-alt"></span> Visualizar Impressão
					
		</div>

		<div 
			class="form-group chave" 
			style="cursor: pointer; color: blue;" 
			ng-click="$ctrl.ngWinPopUp('/_14020/PEDIDO/'+$ctrl.infoGeral.PEDIDO, $ctrl.infoGeral.PEDIDO,{width: 1200, height: 825})"
			ng-if="$ctrl.tipoTela != 'incluir'"
		>
			<span class="fa fa-truck"></span> Simular Frete
					
		</div>

		<div 
			class="form-group chave" 
			title="{{ Lang::get($menu.'.label-chave-liberacao-title') }}">

			<label>{{ Lang::get($menu.'.label-chave-liberacao') }}:</label>			
			<label 
				class="lbl-chave" 
				ng-bind="$ctrl.infoGeral.CHAVE | lpad:[5,'0']"></label>

		</div>		

		<div 
			class="form-group chave" 
			ng-if="$ctrl.tipoTela != 'incluir'"
		>
			<label>Notas Fiscais:</label>
			<label style="vertical-align: middle;font-size: 20px;font-weight: normal;cursor: text; margin-right: 7px;" ng-repeat="iten in $ctrl.infoGeral.NFS.split(',')">
	             <a href="/_12100?nota=@{{iten}}&cliente=@{{$ctrl.infoGeral.CLIENTE_CODIGO}}&representante=@{{$ctrl.infoGeral.REPRESENTANTE_CODIGO}}" target="_blank" >@{{iten}}</a>
	        </label>			
		</div>

		<div class="form-group" ng-if="$ctrl.tipoTela != 'incluir'">

			<label>{{ Lang::get($menu.'.label-num-pedido') }}:</label>
			<input 
				type="text" 
				class="form-control" 
				disabled 
				ng-model="$ctrl.infoGeral.PEDIDO"
			/>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-cliente') }}:</label>
			<input 
				type="text" 
				class="form-control input-maior" 
				disabled 
				ng-model="$ctrl.infoGeral.RAZAOSOCIAL"
			/>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-representante') }}:</label>
			<input 
				type="text" 
				class="form-control input-maior" 
				disabled
				ng-model="$ctrl.infoGeral.REPRESENTANTE_DESCRICAO" 
			/>

		</div>

		<div class="form-group">

			<label class="lbl-checkbox">
				<input 
					type="checkbox" 
					class="form-control"
					ng-model="$ctrl.infoGeral.PROGRAMADO"
					ng-true-value="'1'"
					ng-false-value="'0'"
					ng-checked="$ctrl.infoGeral.PROGRAMADO == '1'"
					ng-click="$ctrl.marcarProgramado()"
				/>
				{{ Lang::get($menu.'.label-programado') }}
			</label>

		</div>

		<div class="form-group">

			<label>
				{{ Lang::get($menu.'.label-data-cliente') }}:
				<span 
					class="fa fa-info-circle"
					title="{{ Lang::get($menu.'.msg-data-cliente-info') }}" 
				></span>
			</label>

			<input 
				type="date" 
				class="form-control"
				min="@{{ $ctrl.infoGeral.DATA_MIN_CLIENTE }}"
				ng-model="$ctrl.infoGeral.DATA_CLIENTE" 
				ng-disabled="$ctrl.infoGeral.PROGRAMADO == '0' || $ctrl.infoGeral.PROGRAMADO == null"
				ng-required="$ctrl.infoGeral.PROGRAMADO == '1'"
			/>

		</div>

	</div>

	<div class="row">

		<div class="form-group">

			<label title="{{ Lang::get($menu.'.label-pedido-cliente-title') }}">{{ Lang::get($menu.'.label-pedido-cliente') }}:</label>
			<input 
				type="text" 
				class="form-control" 
				title="{{ Lang::get($menu.'.label-pedido-cliente-title') }}" 
				required 
				ng-model="$ctrl.infoGeral.PEDIDO_CLIENTE"
			/>
		
		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-observacao') }}:</label>
			<input 
				type="text" 
				class="form-control input-maior" 
				ng-model="$ctrl.infoGeral.OBSERVACAO"
			/>
		
		</div>

		<div class="form-group">
		
			<label>{{ Lang::get($menu.'.label-email-xml') }}:</label>
			<input 
				type="email" 
				class="form-control input-maior" 
				multiple 
				ng-model="$ctrl.infoGeral.EMAIL_XML" 
				remove-ng-email-validation
				disabled 
			/>
		
		</div>

	</div>

	<div class="row">
		
<!--		<button 
			type="button" 
			id="detalhe-cliente-toggle" 
			class="btn btn-sm btn-info collapsed in" 
			data-toggle="collapse" 
			data-target="#detalhe-cliente" 
			aria-expanded="false" 
			aria-controls="detalhe-cliente"
		>
			{{ Lang::get($menu.'.button-detalhe-cliente-toggle') }}
			<span class="fa fa-caret-down"></span>
		</button>-->
		
<!--		<div id="detalhe-cliente">-->

			<div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-transportadora') }}:</label>
					<input 
						type="text" 
						class="form-control input-maior" 
						disabled 
						ng-model="$ctrl.infoGeral.TRANSPORTADORA_DESCRICAO" 
					/>

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-frete') }}:</label>
					<input 
						type="text" 
						class="form-control" 
						disabled
						ng-model="$ctrl.infoGeral.FRETE_DESCRICAO"  
					/>
				
				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-forma-pagamento') }}:</label>
					<input 
						type="text" 
						class="form-control" 
						disabled
						ng-model="$ctrl.infoGeral.PAGAMENTO_FORMA_DESCRICAO" 
					/>
				
				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-condicao-pagamento') }}:</label>
					<input 
						type="text" 
						class="form-control" 
						disabled 
						ng-model="$ctrl.infoGeral.PAGAMENTO_CONDICAO_DESCRICAO" 
					/>
				
				</div>

			</div>

		<!--</div>-->

	</div>

</fieldset>