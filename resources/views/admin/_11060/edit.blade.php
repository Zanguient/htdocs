@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11060.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('admin/_11060.titulo-incluir') }}
@endsection

@section('conteudo')
<form action="{{ route('_11060.update', $impressoras->ID) }}" url-redirect="{{ url('sucessoAlterar/_11060', $impressoras->ID) }}" method="POST" class="form-inline edit js-gravar">
	    <input type="hidden" name="_method" value="PATCH">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
		<ul class="list-inline acoes">
			<li>
				<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
					<span class="glyphicon glyphicon-ok"></span> 
					{{ Lang::get('master.gravar') }}
				</button>
			</li>
			<li>
				<a href="{{ url('_11060', $impressoras->ID) }}" class="btn btn-danger btn-cancelar" data-hotkey="f11">
					<span class="glyphicon glyphicon-ban-circle"></span> 
					{{ Lang::get('master.cancelar') }}
				</a>
			</li>
		</ul>	
		
        <fieldset>
			<legend>{{ Lang::get('master.info-geral') }}</legend>
            
				{{-- Estabelecimento  --}}
				@include('admin._11020.include.listar', [
					'estab_cadastrado'	=> $impressoras->ESTABELECIMENTO_ID,
                    'required'		=> 'required',
                    'autofocus'		=> 'autofocus',
                    'opcao_selec'	=> 'true'
				])
            
            <div class="form-group">
					<label>{{ Lang::get('admin/_11060.tabela-descricao') }}</label>
                    <input type="text" name="descricao" class="form-control input-maior" autofocus="" required="" value="{{ $impressoras->DESCRICAO }}">
			</div>
            
            <div class="form-group">
					<label>{{ Lang::get('admin/_11060.tabela-serial') }}</label>
					<input type="text" name="serial" class="form-control input-maior" autofocus="" required="" value="{{ $impressoras->CODIGO }}">
			</div>
            
	    </fieldset>
    </form>
@endsection

@section('script')
	<script src="{{ elixir('assets/js/table.js') }}"></script>
	<script src="{{ elixir('assets/js/_11060.js') }}"></script>
@append
    
@endsection
