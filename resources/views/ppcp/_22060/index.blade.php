@extends('master')

@section('titulo')
{{ Lang::get('ppcp/_22060.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/22060.css') }}" />
@endsection

@section('conteudo')

<form class="form-inline">
	
	<div class="row">
		
		{{-- Estabelecimento --}}
		@include('admin._11020.include.listar', [
			'autofocus'		=> 'autofocus'
		])
		
		{{-- GP --}}
		@include('helper.include.view.consulta',
			[
			  'label_descricao'   => 'GP:',
			  'obj_consulta'      => 'Ppcp/include/_22030-gp',
			  'obj_ret'           => ['ID','DESCRICAO'],
			  'campos_sql'        => ['ID','DESCRICAO'],
			  'campos_imputs'     => [['_gp_id','ID'],['_gp_descricao','DESCRICAO']],
			  'filtro_sql'        => [
										['STATUS','1'],
										['ORDER','DESCRICAO,ID']
									 ],
			  'campos_tabela'     => [['ID','80'],['DESCRICAO','200']],
			  'campos_titulo'     => ['ID','DESCRIÇÃO'],
			  'class1'            => 'input-medio',
			  'class2'            => 'consulta_gp_grup'
			]
		)

		{{-- UP --}}
		@include('helper.include.view.consulta',
			[
			  'label_descricao'   => 'UP:',
			  'obj_consulta'      => 'Ppcp/include/_22030-up',
			  'obj_ret'           => ['UP_ID','UP_DESCRICAO'],
			  'campos_sql'        => ['UP_ID','UP_DESCRICAO'],
			  'campos_imputs'     => [['_up_id','UP_ID'],['_up_descricao','UP_DESCRICAO']],
			  'filtro_sql'        => [
										['GP','0'],
										['STATUS','1'],
										['ORDER','UP_DESCRICAO,UP_ID']
									 ],
			  'campos_tabela'     => [['UP_ID','80'],['UP_DESCRICAO','200']],
			  'campos_titulo'     => ['ID','DESCRIÇÃO'],
			  'class1'            => 'input-medio',
			  'class2'            => 'consulta_up_group',
			  'no_script'         => true
			]
		)
		
		<div class="form-group">
			<label>{{ Lang::get('master.periodo') }}:</label>
			<input type="date" class="data-ini" id="data-ini" value="{{ date('Y-m-d', strtotime('-1 month')) }}" />
			<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>
			<input type="date" class="data-fim" id="data-fim" value="{{ date('Y-m-d') }}" />
		</div>
		<div class="form-group">
			<input type="checkbox" id="dividir-estacao" class="form-control" />
			<label>{{ Lang::get($menu.'.dividir-por-estacao') }}</label>
		</div>
		<div class="form-group">
			<button type="button" class="btn btn-primary filtrar" id="filtrar" data-hotkey="alt+f">
				<span class="glyphicon glyphicon-filter"></span>
				{{ Lang::get('master.filtrar') }}
			</button>
		</div>
		
	</div>
	
	<div class="row obj_resizable" >
		<div class="panel-group estab-group" id="accordion-estab" role="tablist" aria-multiselectable="true">
			@include('ppcp._22060.index.panel-estab')
		</div>
	</div>
	
</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/_22060.js') }}"></script>
@append