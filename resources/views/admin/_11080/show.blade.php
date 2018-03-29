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

      <li><a href="{{ $permissaoMenu->ALTERAR ? route('_11080.edit', $id) : '#' }}" class="btn btn-primary btn-alterar" data-hotkey="f9" {{ $permissaoMenu->ALTERAR ? '' : 'disabled' }}><span class="glyphicon glyphicon-edit"></span> {{ Lang::get('master.alterar') }}</a>
      </li>
      <li>
        <button type="button" data-id="{{$id}}" class="btn btn-danger itemexcluir" data-hotkey="f10" data-loading-text="Excluindo..."  {{ $permissaoMenu->EXCLUIR ? '' : 'disabled' }}>
          <span class="glyphicon glyphicon-trash"></span> 
          Excluir
        </button>
      </li>
      <li>
        <a href="{{ url('_11080') }}" class="btn btn-default btn-cancelar" data-hotkey="f11">
          <span class="glyphicon glyphicon-chevron-left"></span> 
          Voltar
        </a>
      </li>
    </ul>
    
    <fieldset>
      <legend>Informações Gerais:</legend>  

      <div class="form-group">
          <label>Relatorio Nome:</label>
          <input type="text" name="relatorio-nome" class="form-control  relatorio-nome input-maior" autofocus="" required="" value="{{$dados['INFO'][0]->NOME}}" disabled>
      </div>

      <div class="form-group">
          <label>Filtro:</label>
          <input type="text" name="relatorio-filtro" class="form-control relatorio-filtro input-maior" autofocus="" required=""  value="{{$dados['CONF'][0]->filtro}}" disabled>
      </div>

      <div class="form-group">
          <label>Tipo:</label>
          <input type="text" name="relatorio-tipo" class="form-control relatorio-tipo input-menor" autofocus="" required=""  value="{{$dados['INFO'][0]->TIPO}}" disabled>
      </div>

      <div class="form-group">
          <label>Template:</label>
          <input type="text" name="relatorio-template" class="form-control relatorio-template input-menor" autofocus="" required=""  value="{{$dados['INFO'][0]->TEMPLATE_ID}}" disabled>
      </div>

      <div class="form-group">
          <label>Versão:</label>
          <input type="text" name="relatorio-versao" class="form-control relatorio-versao input-menor" autofocus="" required=""  value="{{$dados['CONF'][0]->versao}}" disabled>
      </div>

      <div class="form-group">
          @if($dados['CONF'][0]->zebrado == 1)
            <input type="checkbox" name="relatorio-zebrado" id="necessita-licitacao" class="form-control relatorio-zebrado" checked  disabled/>
          @else
            <input type="checkbox" name="relatorio-zebrado" id="necessita-licitacao" class="form-control relatorio-zebrado" disabled/>
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
  @endforeach

  <fieldset class="observacao">
      <legend>Parametros:</legend>     
      <div class="lista-variaveis">  
        @php $adicionado = 0;
        @foreach($dados['IMPUTS'] as $key => $value)

          <div><div>{{$value->PARAMETRO}}:</div><div>
          <input type="text" class="c-variavel-{{$value->PARAMETRO}} parametro-imput" name="fname" data-imput="{{$value->PARAMETRO}}" value="" disabled>
          
          @if($value->TIPO == 1)
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec1" name="vehicle" value="0" checked disabled>String?
          @else
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec1" name="vehicle" value="0" disabled>String?
          @endif

          @if($value->TIPO == 2)
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec2" name="vehicle" value="0" checked disabled>Data?
          @else
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec2" name="vehicle" value="0" disabled>Data?
          @endif

          @if($value->TIPO == 3)
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec3" name="vehicle" value="0" checked disabled>Numeric?
          @else
            <input type="checkbox" class="c-variavel-{{$value->PARAMETRO}}-chec3" name="vehicle" value="0" disabled>Numeric?
          @endif

          Descrição: <input type="text" class="c-variavel-{{$value->PARAMETRO}}-desc parametro-imput" name="fname" data-imput="{{$value->PARAMETRO}}" value="{{$value->DESCRICAO}}" disabled>
          </div></div><br>

          @php $adicionado = 0;

        @endforeach
      </div>
    </fieldset>

    <fieldset class="observacao">
      <legend>Campos:</legend>     
      <div class="lista-variaveis">  
        @php $adicionado = 0;
        @foreach($dados['CAMPOS'] as $key => $value)

        <div>{{$value->CAMPO}}

          @if($value->VISIVEL == 1)
            <input type="checkbox" class="visivel-{{$value->CAMPO}}-chec" name="vehicle" value="0" checked disabled> Visivel?
          @else
            <input type="checkbox" class="visivel-{{$value->CAMPO}}-chec" name="vehicle" value="0" disabled> Visivel?
          @endif

        Descrição: <input type="text" class="visivel-{{$value->CAMPO}}-desc" name="fname" value="{{$value->DESCRICAO}}" disabled>
        Cor:<input type="text" class="visivel-{{$value->CAMPO}}-cor" name="fname" value="{{$value->COR}}" disabled>
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
