<div id="info-destaque">
	
	<div class="label label-default-light">
		<button type="button" class="btn btn-xs btn-default" id="status-producao" title="{{ Lang::get($menu.'.status-producao-title') }}" data-toggle="collapse" data-target="#resumo-producao" aria-expanded="false" aria-controls="resumo-producao">
			<span class="fa fa-circle-thin"></span>
		</button>
	</div>	
	<div class="label label-warning" id="operador">
		<span>{{ Lang::get('master.operador') }}:</span>
		<span class="valor">-</span>
		<input type="hidden" name="_operador_id" id="_operador-id" />
	</div>
	<div class="label label-primary" id="up-destaque">
		<span>{{ Lang::get('master.up') }}:</span>
		<span class="valor">-</span>
	</div>
	<div class="label label-danger" id="estacao-destaque">
		<span>{{ Lang::get('master.estacao') }}:</span>
		<span class="valor">-</span>
	</div>
	<div class="label label-success" id="remessa-talao-destaque">
		<span>{{ Lang::get($menu.'.remessa') }} - {{ Lang::get('master.talao') }}:</span>
		<span class="valor">-</span>
	</div>
	<div class="label label-default" id="data-destaque">
		<span>{{ Lang::get('master.data-prod') }}:</span>
		<span class="valor">{{ date('d/m/Y',strtotime($data_producao)) }}</span>
	</div>
	
</div>