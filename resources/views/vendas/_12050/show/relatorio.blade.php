<div class="relatorio-conteiner table-ec">
<table class="table tabele-datatable table-striped table-bordered table-hover table-talao-produzido">
    <thead>
        <tr>
            <th class="estacao ">DATA</th>
            <th class="estacao title-prod">PEDIDOS</th>
            <th class="estacao title-prod">FATURAMENTO</th>
            <th class="estacao title-prod">DEVOLUÇÕES</th>
            <th class="estacao title-prod">DEFEITOS</th>
            @foreach ( $dados['FAMILIAS'] as $fan )
                @if($fan->FAMILIA_CODIGO == $dados['FAMILIA'])
                    <th class="estacao title-prod">PROD. {{$fan->DESCRICAO}}</th>
                @else
                    <th class="estacao title-prod">PROD. {{$fan->DESCRICAO}}</th>
                    
                    @if($fan->FAMILIA_CODIGO == 12)
                    <th class="estacao title-prod">DEF. {{$fan->DESCRICAO}}</th>                
                    @endif
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php $contador = 0;
        @foreach ( $dados['DADOS'] as $linhas )

            <tr tabindex="0" class=" linhas-prod">
                <td class="coll-string  coll-prod" data-y="{{ date('Y', strtotime($linhas['PEDIDOS']->DATA))}}"  data-m="{{ date('m', strtotime($linhas['PEDIDOS']->DATA))}}" data-familia="{{$dados['FAMILIA']}}" data-d="{{ date('d', strtotime($linhas['PEDIDOS']->DATA))}}"  title="">{{ date('d/m/Y', strtotime($linhas['PEDIDOS']->DATA))}}</td>
                <td class="coll-numeric coll-prod detalhar-result" data-familia="{{$dados['FAMILIA']}}"  data-titulo="Pedidos"        data-tipo="pedidosDia"       data-data="{{$linhas['PEDIDOS']->DATA}}" data-dados="{{ number_format($linhas['PEDIDOS']->QUANTIDADE  , 2, ',', '')}}"    title="">{{ number_format($linhas['PEDIDOS']->QUANTIDADE      , 2, ',', '.') }}</td>
                <td class="coll-numeric coll-prod detalhar-result"  data-familia="{{$dados['FAMILIA']}}" data-titulo="Faturamento"    data-tipo="faturamentoDia"   data-data="{{$linhas['PEDIDOS']->DATA}}" data-dados="{{ number_format($linhas['FATURAMENTO']->QUANTIDADE , 2, ',', '')}}" title="">{{ number_format($linhas['FATURAMENTO']->QUANTIDADE , 2, ',', '.') }}</td>
                <td class="coll-numeric coll-prod detalhar-result"  data-familia="{{$dados['FAMILIA']}}" data-titulo="Devoluções"     data-tipo="devolucaoDia"     data-data="{{$linhas['PEDIDOS']->DATA}}" data-dados="{{ number_format($linhas['DEVOLUCAO']->QUANTIDADE  , 2, ',', '')}}"  title="">{{ number_format($linhas['DEVOLUCAO']->QUANTIDADE   , 2, ',', '.') }}</td>
                
                

                    @php $tipo = 'producaoDia';
                    <td
                        class        ="coll-numeric coll-prod aa-teste"
                        ng-click     ="vm.Acao.filterDefeito('{{$dados['FAMILIA']}}','{{$linhas['PEDIDOS']->DATA}}','{{ number_format($linhas['DEFEITO']->QUANTIDADE  , 2, ',', '')}}','{{ number_format(0    , 2, ',', '')}}',0)"
                        data-titulo  ="Defeitos"
                        data-familia ="{{$dados['FAMILIA']}}"
                        data-tipo    ="defeitoDia" 
                        data-data    ="{{$linhas['PEDIDOS']->DATA}}"
                        data-dados   ="{{ number_format($linhas['DEFEITO']->QUANTIDADE  , 2, ',', '')}}"
                        data-dados2  ="{{ number_format(0    , 2, ',', '')}}"
                        title        =""
                    >

                    {{ number_format($linhas['DEFEITO']->QUANTIDADE     , 2, ',', '.') }}
                        <span class="glyphicon glyphicon-info-sign" data-toggle="popover" data-placement="top" title="" data-content="
                            <div class='defeito-container'>
                                <table class='table table-striped table-bordered'>
                                    <thead>
                                        <tr>
                                            <th class='text-left'>Turno</th>
                                            <th class='text-left'>Qtd.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class='text-left'>1</td>
                                            <td class='text-left'>{{number_format($linhas['DEFEITO']->QTD_TURNO1, 2, ',', '.')}}</td>
                                        </tr>
                                        <tr>
                                            <td class='text-left'>2</td>
                                            <td class='text-left'>{{number_format($linhas['DEFEITO']->QTD_TURNO2, 2, ',', '.')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            " data-original-title="Defeitos">
                        </span>
                    </td>             
                    

                @php $cont = 0;
                @foreach ( $linhas['PRODUCAO'] as $key => $prod )

                    @php $item = $linhas['DEFEITOS'][$prod[$contador]->CODE][0];
                    @php $tipo = 'producaoDia2';

                    <td class="coll-numeric coll-prod kay-cor-{{$cont}} detalhar-result2" data-familia="{{$prod[$contador]->CODE}}" data-titulo="Prod. {{$prod[$contador]->DESC}}" data-tipo="{{$tipo}}" data-data="{{$linhas['PEDIDOS']->DATA}}"  data-dados="{{ number_format(($prod[$contador]->QUANTIDADE - $item->QUANTIDADE)  , 2, ',', '')}}" title="">{{ number_format(($prod[$contador]->QUANTIDADE - $item->QUANTIDADE) , 2, ',', '.').' '.$prod[$contador]->UNIDADE }}
                        <span class="glyphicon glyphicon-info-sign" data-toggle="popover" data-placement="top" title="" data-content="
                            <div class='defeito-container'>
                                <table class='table table-striped table-bordered'>
                                    <thead>
                                        <tr>
                                            <th class='text-left'>Turno</th>
                                            <th class='text-left'>Qtd.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class='text-left'>1</td>
                                            <td class='text-left'>{{number_format(($prod[$contador]->QTD_TURNO1 - $item->QTD_TURNO1), 2, ',', '.').' '.$prod[0]->UNIDADE}}</td>
                                        </tr>
                                        <tr>
                                            <td class='text-left'>2</td>
                                            <td class='text-left'>{{number_format(($prod[$contador]->QTD_TURNO2 - $item->QTD_TURNO2), 2, ',', '.').' '.$prod[0]->UNIDADE}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <table class='table table-striped table-bordered'>
                                    <thead>
                                        <tr>
                                            <th class='text-left'>{{'Talões:'.$prod[$contador]->TALOES}}</th>
                                        </tr>
                                        <tr>
                                            <th class='text-left'>{{'Defeitos: '.number_format($prod[$contador]->DEFEITO , 2, ',', '.')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            " data-original-title="Produção">
                        </span>
                    </td>

                    @php /*@if($item->CODE != $dados['FAMILIA'])*/
                    @if($item->CODE == 12)
                        <td
                        ng-click     ="vm.Acao.filterDefeito('{{$item->CODE}}','{{$linhas['PEDIDOS']->DATA}}','{{ number_format($item->QUANTIDADE , 2, ',', '')}}','{{ number_format(($prod[$contador]->QUANTIDADE - $item->QUANTIDADE) , 2, ',', '')}}',0)"
                        class="coll-numeric coll-prod kay-cor-{{$cont}}"
                        data-familia ="{{$item->CODE}}"
                        data-titulo  ="Def. {{$prod[$contador]->DESC}}"
                        data-tipo    ="defeitoDia"
                        data-data    ="{{$linhas['PEDIDOS']->DATA}}"
                        data-dados   ="{{ number_format($item->QUANTIDADE , 2, ',', '')}}"
                        data-dados2  ="{{ number_format(($prod[$contador]->QUANTIDADE - $item->QUANTIDADE) , 2, ',', '')}}"
                        title=""
                        >{{ number_format($item->QTD_1 , 2, ',', '.')}} / {{ number_format($item->QTD_2 , 2, ',', '.')}}
                            <span class="glyphicon glyphicon-info-sign" data-toggle="popover" data-placement="top" title="" data-content="
                                <div class='defeito-container'>
                                    <table class='table table-striped table-bordered'>
                                        <thead>
                                            <tr>
                                                <th class='text-left'>Turno</th>
                                                <th class='text-left'>Qtd.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class='text-left'>1</td>
                                                <td class='text-left'>{{number_format($item->QTD_TURNO1, 2, ',', '.')}}</td>
                                            </tr>
                                            <tr>
                                                <td class='text-left'>2</td>
                                                <td class='text-left'>{{number_format($item->QTD_TURNO2, 2, ',', '.')}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                " data-original-title="Produção">
                            </span>
                        </td>
                    @endif


                    @php $cont++;
                @endforeach

                
            </tr>
            @php $contador++;
        @endforeach
            
            <tr tabindex="0" class="linta-total">
                <td class="coll-string"  title="">TOTAL</td>
                <td class="coll-numeric detalhar-result3" data-familia="{{$dados['FAMILIA']}}" data-titulo="Pedidos"     data-tipo="pedidosDia"       data-dados="{{ number_format($dados['TOTAL']['PEDIDOS']  , 2, ',', '')}}"     title="">{{ number_format($dados['TOTAL']['PEDIDOS']       , 2, ',', '.') }}</td>
                <td class="coll-numeric detalhar-result3" data-familia="{{$dados['FAMILIA']}}" data-titulo="Faturamento" data-tipo="faturamentoDia"   data-dados="{{ number_format($dados['TOTAL']['FATURAMENTO']  , 2, ',', '')}}" title="">{{ number_format($dados['TOTAL']['FATURAMENTO']   , 2, ',', '.') }}</td>
                <td class="coll-numeric detalhar-result3" data-familia="{{$dados['FAMILIA']}}" data-titulo="Devoluções"  data-tipo="devolucaoDia"     data-dados="{{ number_format($dados['TOTAL']['DEVOLUCAO']  , 2, ',', '')}}"   title="">{{ number_format($dados['TOTAL']['DEVOLUCAO']     , 2, ',', '.') }}</td>
                
                @php $desc = $linhas['PRODUCAO'][$key][0]->DESC;
                @php $item = $dados['TOTAL']['DEFEITOS'][$key];

                @php $tipo = 'producaoDia';

                    <td
                        ng-click     ="vm.Acao.filterDefeito('{{$dados['FAMILIA']}}','0','{{ number_format($dados['TOTAL']['DEFEITO'] , 2, ',', '')}}','{{ number_format(0 , 2, ',', '')}}',1)"
                        class="coll-numeric"
                        data-familia="{{$dados['FAMILIA']}}"
                        data-titulo="Defeitos"
                        data-tipo="defeitoDia"
                        data-dados="{{ number_format($dados['TOTAL']['DEFEITO']  , 2, ',', '')}}"
                        data-dados2="{{ number_format(0, 2, ',', '')}}"
                        title=""
                    >

                    {{ number_format($dados['TOTAL']['DEFEITO']       , 2, ',', '.') }}
                    <span class="glyphicon glyphicon-info-sign" data-toggle="popover" data-placement="top" title="" data-content="
                            <div class='defeito-container'>
                                <table class='table table-striped table-bordered'>
                                    <thead>
                                        <tr>
                                            <th class='text-left'>Turno</th>
                                            <th class='text-left'>Qtd.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class='text-left'>1</td>
                                            <td class='text-left'>{{number_format($dados['TOTAL']['DEFEITO1'], 2, ',', '.')}}</td>
                                        </tr>
                                        <tr>
                                            <td class='text-left'>2</td>
                                            <td class='text-left'>{{number_format($dados['TOTAL']['DEFEITO2'], 2, ',', '.')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            " data-original-title="Defeitos">
                        </span>
                </td>

                @php $cont = 0;

                @foreach ( $dados['TOTAL']['PRODUCAO'] as $key => $prod )
                    
                    @php $desc = $linhas['PRODUCAO'][$key][0]->DESC;
                    @php $item = $dados['TOTAL']['DEFEITOS'][$key];

                    @php $tipo = 'producaoDia2';
                    
                    <td class="coll-numeric detalhar-result3" data-familia="{{$key}}" data-titulo="Prod. {{$desc}}" data-tipo="{{$tipo}}" data-dados="{{ number_format($prod->QUANTIDADE , 2, ',', '')}}" title="">{{ number_format(($prod->QUANTIDADE - $item->QUANTIDADE) , 2, ',', '.') }}
                        <span class="glyphicon glyphicon-info-sign" data-titulo="Prod." ata-dados="{{ number_format($prod->QUANTIDADE  , 2, ',', '')}}" data-toggle="popover" data-placement="top" title="" data-content="
                                <div class='defeito-container'>
                                    <table class='table table-striped table-bordered'>
                                        <thead>
                                            <tr>
                                                <th class='text-left'>Turno</th>
                                                <th class='text-left'>Qtd.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class='text-left'>1</td>
                                                <td class='text-left'>{{ number_format(($prod->QTD_TURNO1 - $item->QTD_TURNO1) , 2, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td class='text-left'>2</td>
                                                <td class='text-left'>{{ number_format(($prod->QTD_TURNO2 - $item->QTD_TURNO2), 2, ',', '.') }}</td>
                                            </tr>
                                        </tbody>

                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Defeito: '.number_format($prod->DEFEITO, 2, ',', '.')}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </table>
                                </div>
                                " data-original-title="Produção">
                            </span>                 
                    </td>

                    @php /*@if($item->CODE != $dados['FAMILIA'])*/
                    @if($item->CODE == 12)
                        <td
                            ng-click     ="vm.Acao.filterDefeito('{{$item->CODE}}','{{$linhas['PEDIDOS']->DATA}}','{{ number_format($item->QUANTIDADE , 2, ',', '')}}','{{ number_format(($prod->QUANTIDADE - $item->QUANTIDADE) , 2, ',', '')}}',1)"
                            class="coll-numeric"
                            data-familia="{{$item->CODE}}"
                            data-titulo="Def. {{$desc}}"
                            data-tipo="defeitoDia"
                            data-data="{{$linhas['PEDIDOS']->DATA}}"
                            data-dados="{{ number_format($item->QUANTIDADE , 2, ',', '')}}"
                            data-dados2="{{ number_format(($prod->QUANTIDADE - $item->QUANTIDADE) , 2, ',', '')}}"
                            title=""
                            >{{ number_format($item->QTD_1 , 2, ',', '.')}} / {{ number_format($item->QTD_2 , 2, ',', '.')}}
                            <span class="glyphicon glyphicon-info-sign" data-toggle="popover" data-placement="top" title="" data-content="
                                <div class='defeito-container'>
                                    <table class='table table-striped table-bordered'>
                                        <thead>
                                            <tr>
                                                <th class='text-left'>Turno</th>
                                                <th class='text-left'>Qtd.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class='text-left'>1</td>
                                                <td class='text-left'>{{number_format($item->QTD_TURNO1, 2, ',', '.')}}</td>
                                            </tr>
                                            <tr>
                                                <td class='text-left'>2</td>
                                                <td class='text-left'>{{number_format($item->QTD_TURNO2, 2, ',', '.')}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                " data-original-title="Produção">
                            </span>
                        </td>
                    @endif
                    
               
                    @php $cont++;
                @endforeach

            </tr>
        
    </tbody>
</table>
</div>

<br>
   

    <div id="totalizador-grafico-div" class="grafico3-conteiner">

        <div class="area-filtro-grafico">
            <button type="button" class="btn btn-screem-grafico btn-screem-grafico1 go-fullscreen" gofullscreen="totalizador-grafico-div" title="Tela cheia">
                <span class="glyphicon glyphicon-fullscreen"></span>
            </button>
            <select class="select-tipo-grafico1">
                <option value="LineChart">Linhas</option>
                <option value="AreaChart">Áreas</option>
                <option value="SteppedAreaChart">Andares</option>
            </select>
            <ul id="totalizador-grafico-filter2" class=""><ul></div>
        <div id="totalizador-grafico-filter"  style="display: none;"></div>
        <div id="totalizador-grafico"></div>
    </div>

<br>

<div class="faturamento-familias">
    <div class="tabela-conteiner">
    <legend>Faturamento por Família:</legend>
    <table class="table tabele-datatable table-striped table-bordered table-hover tabela-fat-familias table-talao-produzido">
        <thead>
            <tr>
                <th class="estacao">FAMÍLIA</th>
                <th class="estacao">FATURAMENTO</th>
                <th class="estacao">FATURAMENTO %</th>   
            </tr>
        </thead>
        <tbody>
            @php $soma = 0;
            
            @foreach ( $dados['FATFAMILIA'] as $linhas )
                @php $soma = $soma + $linhas->QUANTIDADE;
            @endforeach

            @if($soma == 0)
                @php $soma = 100;
            @endif
            
            @php $familias = '';
            @php $tag = 0;

            @foreach ( $dados['FATFAMILIA'] as $linhas )

                @if($tag == 0)
                    @php $familias = $linhas->CODIGO;
                @else
                    @php $familias = $familias .','. $linhas->CODIGO;
                @endif

                @php $tag++
            @endforeach

            @foreach ( $dados['FATFAMILIA'] as $linhas )
            <tr class="linhas-fat-fam detalhar-famila-tabela" tabindex="0" data-titulo="{{$linhas->DESCRICAO}}" data-familia="{{$linhas->CODIGO}}">
                <td class="descricao" data-descricao="{{$linhas->DESCRICAO}}" data-familia="{{$linhas->CODIGO}}" title="">{{$linhas->DESCRICAO}}</td>
                <td class="faturamento coll-numeric " data-faturamento="{{number_format($linhas->QUANTIDADE , 2, '.', '')}}" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.') }}</td>
                <td class="faturamento coll-numeric" data-faturamento="{{number_format($linhas->QUANTIDADE , 2, '.', '')}}" title="">{{number_format(($linhas->QUANTIDADE/$soma) * 100, 2, ',', '.') }}%</td>
            </tr>
            @endforeach

            <tr tabindex="0" class="linta-total detalhar-famila-tabela2" data-titulo="TODAS" data-familia="{{$familias}}">
                <td class="" title="">TOTAL</td>
                <td class="coll-numeric" title="">{{number_format($soma, 2, ',', '.') }}</td>
                <td class="coll-numeric" title="">{{number_format(($soma/$soma) * 100, 2, ',', '.') }}%</td>
            </tr>

        </tbody>
    </table>
    </div>
    
    
    
    <div id="totalizador-diario-grafico-dashboard" class="grafico-conteiner">
      <legend>% de Faturamento por Família:</legend>
      <div class="area-filtro-grafico">
          <button type="button" class="btn btn-screem-grafico btn-screem-grafico2 go-fullscreen" gofullscreen="totalizador-diario-grafico-dashboard" title="Tela cheia">
            <span class="glyphicon glyphicon-fullscreen"></span>
          </button>
          <select class="select-tipo-grafico2">
            <option value="PieChart">Pizza</option>
            <option value="ColumnChart">Colunas</option>
            <option value="BarChart">Barras</option>            
          </select>
          <ul id="totalizador-grafico-filter3" class=""><ul>
      </div>
      <div id="totalizador-diario-grafico-filter"   style="display: none;"></div>
      <div id="totalizador-diario-grafico"></div>
    </div>

</div>

<div class="modal fade modal-historico in" id="modal-historico" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-left">
                    <h4 class="modal-title" id="myModalLabel">Histórico</h4>
                    
                    <input type="hidden" name="_qtd_base" class="_qtd_base" value="">

                </div>
                <div class="modal-header-center"></div>
                <div class="modal-header-right">
                    <button type="button" class="btn btn-default btn-voltar btn-popup-right" data-hotkey="f11" data-dismiss="modal">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    Voltar
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="historico-corpo" data-id="">
                </div>
            </div>
        </div>
	</div>
</div>