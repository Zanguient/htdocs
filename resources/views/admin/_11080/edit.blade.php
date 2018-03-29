@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11080.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11080.css') }}" />
@endsection

@section('conteudo')

  <form action="{{ route('_11080.store') }}" url-redirect="{{ url('sucessoGravar/_11080') }}" method="POST" class="form-inline form-add js-gravar">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <ul class="list-inline acoes">
      <li>
          <button type="button" data-id="{{$id}}" class="btn btn-success gravar-rel-personalizado">
            <span class="glyphicon glyphicon-ok"></span> 
            {{ Lang::get('master.gravar') }}
          </button>
      </li>
      <li>
        <a href="{{ url('_11080') }}" class="btn btn-danger btn-cancelar" data-hotkey="f11">
          <span class="glyphicon glyphicon-ban-circle"></span> 
          {{ Lang::get('master.cancelar') }}
        </a>
      </li>
    </ul>
    
    <fieldset>
      <legend>Informações Gerais:</legend>  

      <div class="form-group">
          <label>Relatorio Nome:</label>
          <input type="text" name="relatorio-nome" class="form-control  relatorio-nome input-maior" autofocus="" required="" value="{{$dados['INFO'][0]->NOME}}" >
      </div>

      <div class="form-group">
          <label>Filtro:</label>
          <input type="text" name="relatorio-filtro" class="form-control relatorio-filtro input-maior" autofocus="" required=""  value="{{$dados['CONF'][0]->filtro}}" >
      </div>

      <div class="form-group">
          <label>Tipo:</label>
          <input type="text" name="relatorio-tipo" class="form-control relatorio-tipo input-menor" autofocus="" required=""  value="{{$dados['INFO'][0]->TIPO}}" >
      </div>

      <div class="form-group">
          <label>Template:</label>
          <input type="text" name="relatorio-template" class="form-control relatorio-template input-menor" autofocus="" required=""  value="{{$dados['INFO'][0]->TEMPLATE_ID}}" >
      </div>

      <div class="form-group">
          <label>Versão:</label>
          <input type="text" name="relatorio-versao" class="form-control relatorio-versao input-menor" autofocus="" required=""  value="{{$dados['CONF'][0]->versao}}" >
      </div>



      <div class="form-group">
          @if($dados['CONF'][0]->zebrado == 1)
            <input type="checkbox" name="relatorio-zebrado" id="necessita-licitacao" class="form-control relatorio-zebrado" checked  />
          @else
            <input type="checkbox" name="relatorio-zebrado" id="necessita-licitacao" class="form-control relatorio-zebrado" />
          @endif
          
          <label for="necessita-licitacao">Zebrado?</label>
      </div>
  
      <br>
      <br>
    
@foreach($dados['SQL'] as $key => $value)
<fieldset class="observacao">
<legend>SQL</legend>     
<div class="form-group" style="width: 100%;">
<div class="textarea-grupo"  style="width: 100%;">
<textarea name="" class="form-control relatorio-sql" rows="5" cols="100" placeholder="Sql que sera tratado" style="width: 100%; height: 280px;">
{{$value->SQL}}
</textarea>
</div>
</div>
</fieldset>

<div>
  <button type="button" class="btn btn-success relatorio-tratar-sql" data-loading-text="{{ Lang::get('compras/_13020.gerandoOrc') }}">
    <span class="glyphicon glyphicon-ok"></span>
     Listar Imputs
  </button>
</div>
@endforeach

  <fieldset class="observacao">
      <legend>Parametros:</legend>     
      <div class="lista-variaveis">  
        @php $adicionado = 0;
        @foreach($dados['IMPUTS'] as $key => $value)

          <div><div>{{$value->PARAMETRO}}:</div><div>
          <input type="text" class="c-variavel-{{$value->PARAMETRO}} parametro-imput" name="fname" data-imput="{{$value->PARAMETRO}}" value="" >
          
          @if($value->TIPO == 1)
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec1" name="vehicle" value="0" checked >String?
          @else
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec1" name="vehicle" value="0" >String?
          @endif

          @if($value->TIPO == 2)
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec2" name="vehicle" value="0" checked >Data?
          @else
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec2" name="vehicle" value="0" >Data?
          @endif

          @if($value->TIPO == 3)
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec3" name="vehicle" value="0" checked >Numeric?
          @else
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec3" name="vehicle" value="0" >Numeric?
          @endif

          Descrição: <input type="text" class="c-variavel-{{$value->PARAMETRO}}-desc parametro-imput" name="fname" data-imput="{{$value->PARAMETRO}}" value="{{$value->DESCRICAO}}" >
          </div></div>

          @php $adicionado = 0;

        @endforeach

      </div>
      
      <br>
      <button type="button" class="btn btn-success tratar-campos"><span class="glyphicon glyphicon-ok"></span>Listar Campos</button>
        
    </fieldset>

    <fieldset class="observacao">
      <legend>Campos:</legend>     
      <div class="lista-variaveis">  
        @php $adicionado = 0;
        @foreach($dados['CAMPOS'] as $key => $value)

        <div>{{$value->CAMPO}}

          @if($value->VISIVEL == 1)
            <input type="checkbox" class="visivel-{{$value->CAMPO}}-chec" name="vehicle" value="0" checked > Visivel?
          @else
            <input type="checkbox" class="visivel-{{$value->CAMPO}}-chec" name="vehicle" value="0" > Visivel?
          @endif

        Descrição: <input type="text" class="visivel-{{$value->CAMPO}}-desc" name="fname" value="{{$value->DESCRICAO}}" >
        Cor:<input type="text" class="visivel-{{$value->CAMPO}}-cor" name="fname" value="{{$value->COR}}" >
        <br><br></div>

        @endforeach
      </div>
    </fieldset>
      
  </form>

@endsection

@section('script')

  <script src="{{ elixir('assets/js/table.js') }}"></script>
  <script src="{{ elixir('assets/js/_11080.js') }}"></script>

@append
