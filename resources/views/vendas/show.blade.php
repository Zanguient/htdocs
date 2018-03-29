@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/12030.css') }}" />
@endsection

@section('titulo')
{{ Lang::get('vendas/_12030.titulo-alterar') }}
@endsection

@section('conteudo')

	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	    
	    <ul class="list-inline acoes">
			<li>
				<button type="submit" id="{{ $dados['dados'][0]->ID}}" class="btn btn-danger itemexcluir" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
					<span class="glyphicon glyphicon-trash"></span> 
					{{ Lang::get('master.excluir') }}
				</button>
			</li>
            <li>
				<a href="{{ url('_12030') }}" class="btn btn-default btn-cancelar" data-hotkey="f11">
					<span class="glyphicon glyphicon-chevron-left"></span> 
					{{ Lang::get('master.voltar') }}
				</a>
			</li>
		</ul>
		
		<fieldset>
            <legend>Aprovar preço</legend>
            
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Email:</h3>
                </div>
                <input type="radio" name="gender" class="email" value="1" disabled> Comercial
                <input type="radio" name="gender" class="email" value="2" disabled> Espumas
                <input type="radio" name="gender" class="email" value="3" disabled> Exportação
            </div>
            

            
            <div class="form-group">
				<label>Cliente:</label>
                <input type="text" name="descricao" class="form-control input-medio icliente" value="{{ $dados['dados'][0]->CLIENTE}}" autofocus="" required="" readonly>
			</div>
            
            <div class="panel panel-primary">
                <div class="panel-heading area-texto-plano">
                    <h3 class="panel-title">Observação:</h3>
                </div>
                <textarea rows="7" class="observacao iobservacao" readonly>{{ $dados['dados'][0]->OBSERVACAO}}</textarea>
            </div>
            
		</fieldset>
        
        <fieldset>
            <legend>Modelos</legend>

            <div class="form-group">
                <label>Modelo:</label>
                <input type="text" name="descricao" class="form-control input-medio imodelo" readonly>
            </div>

            <div class="form-group">
                <label>Valor:</label>
                <input type="text" name="descricao" class="form-control input-menor ivalor mask-numero" decimal="2" readonly>
            </div>

            <button type="button" class="btn btn-info add-anexo add-item-dinamico" title="Adicionar anexo" disabled>
                <span class="glyphicon glyphicon-plus"></span> Adicionar
            </button>
            
            <div></div>

            <div class="panel panel-primary area-item">
                <div class="panel-heading">
                    <h3 class="panel-title">Valor - Modelo</h3>
                </div>
                
            @foreach ($dados['itens'] as $item)
                <div class="item">
                    <div class="valor">{{$item->VALOR}}</div>|<div class="modelo">{{$item->MODELO}}</div>
                    <button type="button" class="btn btn-danger item-excluir" title="Excluir" style="display: block;" disabled>
                        <i class="glyphicon glyphicon-trash"></i></button></div>
            @endforeach
            
            </div>
            
	    </fieldset>

@endsection

@section('script')
	<script src="{{ elixir('assets/js/delete-confirm.js') }}"></script>
    <script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
	<script src="{{ elixir('assets/js/mask.js') }}"></script>
	<script src="{{ elixir('assets/js/table.js') }}"></script>
    <script src="{{ elixir('assets/js/_12030.js') }}"></script>
@append
