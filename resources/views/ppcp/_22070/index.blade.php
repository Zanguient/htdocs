@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/22070.css') }}" />
@endsection

@section('conteudo')

<form class="form-inline form-filtrar">
	
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
			  'class2'            => 'consulta_gp_grup',
              'required'		  => 'required'
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

        {{-- Estação --}}
        @include('helper.include.view.consulta',
            [
              'label_descricao'   => 'Estação:',
              'obj_consulta'      => 'Ppcp/include/_22030-estacao',
              'obj_ret'           => ['ID','DESCRICAO'],
              'campos_sql'        => ['ID','DESCRICAO'],
              'campos_imputs'     => [['_estacao_id','ID'],['_estacao_descricao','DESCRICAO']],
              'filtro_sql'        => [
                                        ['UP','0'],
                                        ['STATUS','1'],
                                        ['ORDER','DESCRICAO,ID']
                                     ],
              'campos_tabela'     => [['ID','80'],['DESCRICAO','200']],
              'campos_titulo'     => ['ID','DESCRIÇÃO'],
              'class1'            => 'input-medio',
              'class2'            => 'consulta_estacao_group',
              'no_script'         => true
            ]
        )
		
		<div class="form-group">
			<label>{{ Lang::get('master.periodo') }}:</label>
			<input type="date" class="data-ini" id="data-ini" value="{{ date('Y-m-d', strtotime('-1 month')) }}" required />
			<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>
			<input type="date" class="data-fim" id="data-fim" value="{{ date('Y-m-d') }}" required />
		</div>
		<div class="form-group">
            <button type="submit" class="btn btn-primary filtrar" id="filtrar" data-hotkey="alt+f">
				<span class="glyphicon glyphicon-filter"></span>
				{{ Lang::get('master.filtrar') }}
			</button>
		</div>
		
	</div>
	
	<div class="row">
		<button type="button" class="btn btn-xs btn-default" id="filtrar-toggle" data-toggle="collapse" data-target="#filtro-up-estacao" aria-expanded="true" aria-controls="filtro-up-estacao">
			{{ Lang::get($menu.'.filtrar-up-estacao-toggle') }}
			<span class="caret"></span>
		</button>
	</div>
    
    <div id="filtro-up-estacao" class="row menu-lateral collapse" aria-expanded="false">
        <div class="tab">
            <span class="btn-toggle glyphicon glyphicon-remove"><span>
        </div>
        <div class="head">
            <span>Up's/Estações</span>
        </div>
		<div class="center">
           <div class="form-group marcar-todos">
				<input type="checkbox" id="up-marcar-todos" class="form-control up-marcar-todos" checked="">
                <label class="treeview-label" for="up-marcar-todos">Ocultar/Exibir Todos</label>
			</div>
			<div class="treeview-container">
			</div>
		</div>
    </div>
	
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Talões Programados</h3>
			</div>
            <div class="taloes-container"></div>
		</div>
	</div>
	
</form>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/_22070.js') }}"></script>
@append