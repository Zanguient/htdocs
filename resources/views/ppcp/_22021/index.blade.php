@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22021.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/22021.css') }}" />
@endsection

@section('conteudo')

@include('ppcp._22021.index.botao-acao')

<form class="form-inline">
	
	<div class="row">
		
		{{-- Estabelecimento --}}
		@include('admin._11020.include.listar', [
			'autofocus'		=> 'autofocus',
			'required'		=> 'required'
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
			  'class2'            => 'consulta_gp_grup',
			  'required'		  => 'required'
			]
		)
		
		<div class="form-group">
			<label>{{ Lang::get('master.periodo') }}:</label>
			<input type="date" class="data-ini" id="data-ini" value="{{ date('Y-m-d') }}" />
			<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>
			<input type="date" class="data-fim" id="data-fim" value="{{ date('Y-m-d') }}" />
		</div>
		
		<div class="form-group">
			<input type="checkbox" id="somente-sobra" class="form-control" checked />
			<label for="somente-sobra">{{ Lang::get($menu.'.somente-sobra') }}</label>
		</div>
		
	</div>
	
</form>

@include('helper.include.view.pdf-imprimir')

@endsection

@section('script')
	<script src="{{ elixir('assets/js/pdf.js') }}"></script>
    <script src="{{ elixir('assets/js/_22021.js') }}"></script>
@append
