@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/12030.css') }}" />
@endsection

@section('titulo')
{{ Lang::get('vendas/_12030.titulo-incluir') }}
@endsection

@section('conteudo')

	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	    
	    <ul class="list-inline acoes">
			<li>
				<button type="submit" class="btn btn-success gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
					<span class="glyphicon glyphicon-ok"></span> 
					{{ Lang::get('master.gravar') }}
				</button>
			</li>
            <li>
				<a href="{{ url('_12030') }}" class="btn btn-danger btn-cancelar" data-hotkey="f11">
					<span class="glyphicon glyphicon-ban-circle"></span> 
					{{ Lang::get('master.cancelar') }}
				</a>
			</li>
		</ul>
		
		<fieldset>
            <legend>Aprovar preço</legend>
            
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Email:</h3>
                </div>
                <input type="radio" name="gender" class="email" value="1"> Comercial
                <input type="radio" name="gender" class="email" value="2"> Espumas
                <input type="radio" name="gender" class="email" value="3"> Exportação
            </div>
            

            
            <div class="form-group">
				<label>Cliente:</label>
				<input type="text" name="descricao" class="form-control input-medio icliente" autofocus="" required="">
			</div>
            
            <div class="panel panel-primary">
                <div class="panel-heading area-texto-plano">
                    <h3 class="panel-title">Observação:</h3>
                </div>
                <textarea rows="7" class="observacao iobservacao"></textarea>
            </div>
            
		</fieldset>
        
        <fieldset>
            <legend>Modelos</legend>

            <div class="form-group">
                <label>Modelo:</label>
                <input type="text" name="descricao" class="form-control input-medio imodelo">
            </div>

            <div class="form-group">
                <label>Valor:</label>
                <input type="text" name="descricao" class="form-control input-menor ivalor mask-numero" decimal="2">
            </div>

            <button type="button" class="btn btn-info add-anexo add-item-dinamico" title="Adicionar anexo">
                <span class="glyphicon glyphicon-plus"></span> Adicionar
            </button>
            
            <div></div>

            <div class="panel panel-primary area-item">
                <div class="panel-heading">
                    <h3 class="panel-title">Valor - Modelo</h3>
                </div>
            </div>
            
	    </fieldset>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/mask.js') }}"></script>
	<script src="{{ elixir('assets/js/table.js') }}"></script>
    <script src="{{ elixir('assets/js/_12030.js') }}"></script>
@append
