@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11080.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11080.css') }}" />
    <link rel="stylesheet" href="../build/assets/images/buttons.dataTables.min.css" />
@endsection

@section('conteudo')

<input type="hidden" name="user-relatorio"  class="user-relatorio"  value="{{ ucwords(mb_strtolower(Auth::user()->NOME ? Auth::user()->NOME : Auth::user()->USUARIO)) }}">

          
  <form action="{{ route('_11080.store') }}" url-redirect="{{ url('sucessoGravar/_11080') }}" method="POST" class="form-inline form-add js-gravar">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      
      <ul class="list-inline acoes">
      <li>
          <button type="button" class="btn btn-success gravar-rel-personalizado">
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
          <input type="text" name="relatorio-nome"  placeholder="Nome do relatório"  class="form-control relatorio-nome input-maior" autofocus="" required="">
      </div>

      <div class="form-group">
          <label>Filtro:</label>
          <input type="text" name="relatorio-filtro" placeholder="Descrição da filtragem" class="form-control relatorio-filtro input-maior" autofocus="" required="">
      </div>

      <div class="form-group">
          <label>Tipo:</label>
          <input type="number" name="relatorio-tipo"  placeholder="Ex. 1" class="form-control relatorio-tipo input-menor" autofocus=""  value="3" required="">
      </div>

      <div class="form-group">
          <label>Menu GRUPO:</label>
          <select style="width: 196px;" name="relatorio-grupo"  placeholder="0 ou 1" class="form-control relatorio-grupo input-medio"  autofocus="" required="">
            <option value="ADM" >Admin. do Sistema</option>
            <option value="VEN" >Gestão de Vendas</option>
            <option value="COM" >Gestão de Compras</option>
            <option value="LOG" >Logística</option>
            <option value="EST" >Controle de Estoque</option>
            <option value="PAT" >Controle Patrimonial</option>
            <option value="CONT">Gestão Contábil</option>
            <option value="ENG" >Engenharia</option>
            <option value="FAV" >Favoritos/Histórico</option>
            <option value="FIN" >Financeiro (Cpa,Cre,Bco)</option>
            <option value="FIS" >Fiscal (NFe,NFs,Ecf)</option>
            <option value="PCP" >Ppcp/Produção</option>
            <option value="RH"  >Gestão de Pessoas</option>
            <option value="SUP" >Supply Chain</option>
            <option value="OPX" >Opex</option>
            <option value="CHA" >Chamados</option>
            <option value="PRO" >Estrutura de Produto</option>
            <option value="RLP" >Relatórios Personalizados</option>
            <option value="WOR" >Workflow</option>
            <option value="CTG" >Custos</option>
          </select>
      </div>

      <div class="form-group">
          <label>Template:</label>
          <input type="number" name="relatorio-template"  placeholder="Ex. 1" class="form-control relatorio-template input-menor" autofocus="" value="1" required="">
      </div>

      <div class="form-group">
          <label>Versão:</label>
          <input type="text" name="relatorio-versao"  placeholder="Ex. 1.0.0" class="form-control relatorio-versao input-menor" value="1.0.0" autofocus="" required="">
      </div>

      <div class="form-group">
          <label>Fonte Web:</label>
          <input type="number" name="relatorio-fonteweb"  placeholder="10" class="form-control relatorio-fonteweb input-menor"  value="10" autofocus="" required="">
      </div>

      <div class="form-group">
          <label>Fonte Exp:</label>
          <input type="number" name="relatorio-fonteexp"  placeholder="8" class="form-control relatorio-fonteexp input-menor"  value="7" autofocus="" required="">
      </div>

      <div class="form-group">
          <label>Totalizador:</label>
          <select style="width: 196px;" name="relatorio-totalizador"  placeholder="0 ou 1" class="form-control relatorio-totalizador input-menor"  autofocus="" required="">  <option value="0">Não</option>   <option value="1">Sim</option></select>
      </div>

      <div class="form-group">
          <input type="checkbox" name="relatorio-zebrado" id="necessita-licitacao" class="form-control relatorio-zebrado" checked />
          <label for="necessita-licitacao">Zebrado?</label>
      </div>

      <div class="form-group">
          <input type="checkbox" name="relatorio-paisagem" id="necessita-licitacao" class="form-control relatorio-paisagem" />
          <label for="necessita-licitacao">Paisagem?</label>
      </div>
  
      <br><br>

      <fieldset class="observacao">
      <legend>SQL</legend>     
        <div class="form-group" style="width: 100%;">
          <div class="textarea-grupo"  style="width: 100%;">
          <textarea name="" class="form-control normal-case cad_relatorio-sql" rows="5" cols="100" placeholder="Sql que será tratado" style="width: 100%; height: 280px; font-family: monospace;"></textarea>
          <br>
          <br>

          <div>
            <button type="button" class="btn btn-success relatorio-tratar-sql" data-loading-text="{{ Lang::get('compras/_13020.gerandoOrc') }}">
              <span class="glyphicon glyphicon-ok"></span>
               Listar Inputs
            </button>
          </div>
          
        </div>
        </div>
      </fieldset>

    <fieldset class="observacao">
      <legend>Parâmetros:</legend>     
      <div class="lista-variaveis">
        
      </div>
    </fieldset>

    <fieldset class="observacao">
      <legend>Campos:</legend>
      <div class="alert-warning" style="
          border-radius: 5px;
          border: 1px solid;
          padding: 3px;
          margin-bottom: 5px;
      ">
          <span>link:</span>
          <div>
           As variaveis que podem ser usanas nos links são: 
            <ul>
              <li>#URLHOST# - Caminho do site, Ex.:https://gc.delfa.com.br</li>
              <li>#NOME DO CAMPO# - Campos que são retornados na consulta</li>
              <li>#NOME  DO PARAMETRO# - Parâmetros usados no Sql</li>
              <li>AUTO=1 Para filtrar o relatório automaticamente</li>
            </ul>
            <p>
            Ex. link: #URLHOST#/_15050?PRODUTO_ID=#CODIGO#<br>
            Ex. link relatório: #URLHOST#/_28000/55?DATA_1=#DATA_1#&DATA_2=#DATA_2#&AUTO=1
            <p>
            Obs.: Datas como parâmetros de URL devem ser passadas como ANO/MES/DIA - 2017/12/30
            <p>
            Obs.: Os parâmetros passados na URL não funcionam para campos de filtragem como ESTABELECIMENTO e FAMÍLIA
          </div>  
      </div>  
      <div class="lista-campos">
        
      </div>
    </fieldset>
    
    <div class="imputs-relatorio">

    </div>

    <fieldset class="observacao">
      <legend>Pré-visualização:</legend>     
      <div class="preview-relatorio">
        
      </div>
    </fieldset>
           
    </fieldset>
      
  </form>

@endsection

@section('script')

  <script src="../build/assets/images/jquery.dataTables.min.js"></script>
  <script src="../build/assets/images/dataTables.buttons.min.js"></script>
  <script src="../build/assets/images/buttons.flash.min.js"></script>
  <script src="../build/assets/images/jszip.min.js"></script>
  <script src="../build/assets/images/pdfmake.min.js"></script>
  <script src="../build/assets/images/vfs_fonts.js"></script>
  <script src="../build/assets/images/buttons.html5.min.js"></script>
  <script src="../build/assets/images/buttons.print.min.js"></script>
  
  <script src="{{ elixir('assets/js/_28000.js') }}"></script>

@endsection
