@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11060.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('admin/_11060.titulo-incluir') }}
@endsection

@section('conteudo')
    <ul class="list-inline acoes">

        <li>
            <a href="{{ route('_11060.edit', $impressoras->ID) }}" class="btn btn-primary btn-alterar" data-hotkey="f9">
                <span class="glyphicon glyphicon-edit"></span> 
                {{ Lang::get('master.alterar') }}
            </a>
        </li>
        <li>
            <button type="button" class="btn btn-danger excluir-impressora" impressora-id="{{$impressoras->ID}}" data-hotkey="f12" >
                <span class="glyphicon glyphicon-trash"></span> 
                {{ Lang::get('master.excluir') }}
            </button>
        </li>
        <li>
            <a href="{{ url('_11060') }}" class="btn btn-default btn-voltar" data-hotkey="f11">
                <span class="glyphicon glyphicon-chevron-left"></span> 
                {{ Lang::get('master.voltar') }}
            </a>
        </li>

    </ul>
		
<fieldset disabled>
			<legend>{{ Lang::get('master.info-geral') }}</legend>
            
            <input type="hidden" name="id" class="form-control input-maior" autofocus="" required="" value="{{ $impressoras->ID }}" readonly>

				{{-- Estabelecimento  --}}
				@include('admin._11020.include.listar', [
					'estab_cadastrado'	=> $impressoras->ESTABELECIMENTO_ID,
                    'required'		=> 'required',
                    'autofocus'		=> 'autofocus',
                    'opcao_selec'	=> 'true'
				])
            
            <div class="form-group">
					<label>{{ Lang::get('admin/_11060.tabela-descricao') }}</label>
                    <input type="text" name="descricao" class="form-control input-maior" autofocus="" required="" value="{{ $impressoras->DESCRICAO }}" readonly>
			</div>
            
            <div class="form-group">
					<label>{{ Lang::get('admin/_11060.tabela-serial') }}</label>
					<input type="text" name="serial" class="form-control input-maior" autofocus="" required="" value="{{ $impressoras->CODIGO }}" readonly>
			</div>
            
	    </fieldset>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/table.js') }}"></script>
	<script src="{{ elixir('assets/js/_11060.js') }}"></script>
@append
    
@endsection
