@extends('master')

@section('titulo')
{{ Lang::get('compras/_13030.titulo-incluir') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13030.css') }}" />
@endsection

@section('conteudo')

<form action="{{ route('_13030.faturamento.store') }}" url-redirect="{{ url('sucessoGravar/_13030/faturamento') }}" method="POST" class="form-inline form-add js-gravar">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <ul class="list-inline acoes">
        <li>
			<a href="{{ url('_13030') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
				<span class="glyphicon glyphicon-chevron-left"></span>
				{{ Lang::get('master.voltar') }}
			</a>
		</li>
    </ul>

    <fieldset>
        <legend>Cadastrar Faturamento</legend>
        <div class="faturamento-cadastrar">
            @include('admin._11020.include.listar'    ,['required'=>true])
            @include('helper.include.view.input-mes'  ,['required'=>true, 'selected' => 'now'])
            @include('helper.include.view.input-ano'  ,['required'=>true, 'selected' => 'now'])
            @include('helper.include.view.input-valor',['required'=>true])
            <div class="form-group">        
                <button type="button" class="btn btn-success btn-inline btn-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
					<span class="glyphicon glyphicon-ok"></span>
					 {{ Lang::get('master.gravar') }}
				</button>
            </div>
        </div>
        <div class="faturamento-alterar">
            @include('compras._13030.faturamento.include.table-lancamentos',['faturamentos'=>$faturamentos])
        </div>

    </fieldset>
</form>
</form>
@endsection

@section('script')
	<script src="{{ elixir('assets/js/data-table.js') }}"></script> 
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<!--<script src="{{ elixir('assets/js/mask.js'     ) }}"></script>-->
	<script src="{{ elixir('assets/js/_13030.js'   ) }}"></script>
@append