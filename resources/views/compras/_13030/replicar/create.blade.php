@extends('master')

@section('titulo')
{{ Lang::get('compras/_13030.titulo-incluir') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/13030.css') }}" />
@endsection

@section('conteudo')

<form action="{{ route('_13030.replicar.store') }}" url-redirect="{{ url('sucessoGravar/_13030') }}" method="POST" class="form-inline form-add js-gravar">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <ul class="list-inline acoes">
        <li>
			<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
				<span class="glyphicon glyphicon-ok"></span>
				 {{ Lang::get('master.gravar') }}
			</button>
		</li>
        <li>
			<a href="{{ url('_13030') }}" class="btn btn-danger btn-cancelar" data-hotkey="f11">
				<span class="glyphicon glyphicon-ban-circle"></span>
				{{ Lang::get('master.cancelar') }}
			</a>
		</li>
    </ul>

    <fieldset>
        <legend>Replicar Cotas</legend>
        <div class="alert alert-warning">
            <p><b>Atenção!</b></p>
            <p>Obs¹: Se no mês de destino já existirem cotas do mês de origem, as cotas do mês de destino passaram a ter todos os valores iguais ao mês de origem, exceto: cota extra e reduções.</p>
            <p>Obs²: Se houverem cotas no mês de destino que não existem no mês de origem, estes permaneceram inalterados.</p>
        </div>        
        <div class="faturamento-cadastrar">
            @include('helper.include.view.input-mes-ano',[
                'label'         =>  'Data de Origem'    ,
                'mes_name'      =>  'mes_origem'        ,
                'ano_name'      =>  'ano_origem'        ,
                'mes_selected'  =>  'now'               ,
                'ano_selected'  =>  'now'               ,
                'required'      =>  true                , 
            ])
            
            @include('helper.include.view.input-mes-ano',[
                'label'         =>  'Data de Destino'   ,
                'mes_name'      =>  'mes_destino'       ,
                'ano_name'      =>  'ano_destino'       ,
                'mes_selected'  =>  'now'               ,
                'ano_selected'  =>  'now'               ,
                'required'      =>  true                , 
            ])            
        </div>
    </fieldset>
</form>
</form>
@endsection

@section('script')
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
@append
