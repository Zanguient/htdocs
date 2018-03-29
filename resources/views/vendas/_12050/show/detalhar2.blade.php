    @php function iff($tst,$cmp,$bad) {return(($tst == $cmp)?$cmp:$bad);}

    <input type="hidden" class="_data_data1"    value="{{$filtro['periodo_inicial']}}">
    <input type="hidden" class="_data_data2"    value="{{$filtro['periodo_final']}}">
    <input type="hidden" class="_data_dados"    value="{{$filtro['val_base']}}">
    <input type="hidden" class="_data_dados2"   value="{{$filtro['val_prod']}}">
    <input type="hidden" class="_data_tipo"     value="{{$filtro['tipo']}}">
    <input type="hidden" class="_data_titulo"   value="{{$filtro['titulo']}}">
    <input type="hidden" class="_data_familia"  value="{{$filtro['familias']}}">
    
    @if($mostrar_linha == 2)
        <div class="conteiner-filtro">
            @php $addfiltrobtn = 0;
            @php $cont_reg = 0;
            @foreach ( $filtro['sql_filtro'] as $item )
                @if($item[0] != '1')
                    <div class="filtro-item">
                        <div class="filtro-campo">{{$item[0]}}:</div>
                        <div class="filtro-valor">{{$item[1]}}</div>
                        <div class="filtro-fechar" data-campo="{{$item[0]}}" data-valor="{{$item[1]}}">x</div>
                    </div>
                    @php $cont_reg++;
                @endif
                @php $addfiltrobtn = 1;
            @endforeach
        
            @if($addfiltrobtn == 1)
                <button class="filtro-btn btn btn-sm btn-primary btn-filtrar-lista">
                    <span class="glyphicon glyphicon-filter"></span>
                    Filtrar
                </button>
                @if($cont_reg++ > 0)
                    
                @else
                    <div class="ajuda-filtro"><div>Dé um duplo click em um dos itens das tabelas para criar uma filtragem</div></div>
                @endif

            @endif
        </div>
    @endif

<fieldset class="tab-container">
    
    <ul id="tab" class="nav nav-tabs acoes" role="tablist"> 

        
        @if($mostrar_linha == 1)
            <li role="presentation" class="active tab-detalhamento">
                <a href="#tab2-container" id="tab2-tab" role="tab" data-toggle="tab" aria-controls="tab2-container" aria-expanded="false">
                    Agrupamento por Linha
                </a>
            </li>

            <li role="presentation" class="tab-detalhamento">
                <a href="#tab4-container" id="tab4-tab" role="tab" data-toggle="tab" aria-controls="tab4-container" aria-expanded="false">
                    Agrupamento por GP
                </a>
            </li>

            <li role="presentation" class="tab-detalhamento">
                <a href="#tab20-container" id="tab20-tab" role="tab" data-toggle="tab" aria-controls="tab20-container" aria-expanded="false">
                    Agrupamento por cor
                </a>
            </li>

            <li role="presentation" class="tab-detalhamento">
                <a href="#tab21-container" id="tab21-tab" role="tab" data-toggle="tab" aria-controls="tab21-container" aria-expanded="false">
                    Agrupamento por Densidade
                </a>
            </li>

        @endif

        @if($mostrar_linha == 2)
            <li role="presentation" class="active tab-detalhamento">
                <a href="#tab2-container" id="tab2-tab" role="tab" data-toggle="tab" aria-controls="tab2-container" aria-expanded="false">
                    Agrupamento por Linha
                </a>
            </li>

            <li role="presentation" class="tab-detalhamento">
                <a href="#tab3-container" id="tab3-tab" role="tab" data-toggle="tab" aria-controls="tab3-container" aria-expanded="false">
                    Agrupamento por Defeito
                </a>
            </li>

            <li role="presentation" class="tab-detalhamento">
                <a href="#tab4-container" id="tab4-tab" role="tab" data-toggle="tab" aria-controls="tab4-container" aria-expanded="false">
                    Agrupamento por GP
                </a>
            </li>

            <li role="presentation" class="tab-detalhamento">
                <a href="#tab20-container" id="tab20-tab" role="tab" data-toggle="tab" aria-controls="tab20-container" aria-expanded="false">
                    Agrupamento por cor
                </a>
            </li>

            <li role="presentation" class="tab-detalhamento">
                <a href="#tab21-container" id="tab21-tab" role="tab" data-toggle="tab" aria-controls="tab21-container" aria-expanded="false">
                    Agrupamento por Densidade
                </a>
            </li>
        @endif
        
        @if($mostrar_linha == 0)
            <li role="presentation" class="active tab-detalhamento">
                <a href="#tab1-container" id="tab1-tab" role="tab" data-toggle="tab" aria-controls="tab1-container" aria-expanded="true">
                    Agrupamento por Modelo
                </a>
            </li>

            <li role="presentation" class="tab-detalhamento">
                <a href="#tab4-container" id="tab4-tab" role="tab" data-toggle="tab" aria-controls="tab4-container" aria-expanded="false">
                    Agrupamento por UP
                </a>
            </li>

        @else
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab1-container" id="tab1-tab" role="tab" data-toggle="tab" aria-controls="tab1-container" aria-expanded="true">
                    Agrupamento por Modelo
                </a>
            </li> 
        @endif

        <li role="presentation" class="tab-detalhamento">
            <a href="#tab5-container" id="tab5-tab" role="tab" data-toggle="tab" aria-controls="tab5-container" aria-expanded="true">
                Agrupamento por Perfil
            </a>
        </li> 

    </ul>

    <div id="tab-content" class="tab-content">

        @if($mostrar_linha == 0)
            <div role="tabpanel" class="tab-pane fade active in" id="tab1-container" aria-labelledby="tab1-tab">
        @else
            <div role="tabpanel" class="tab-pane fade" id="tab1-container" aria-labelledby="tab1-tab">
        @endif

            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">MODELO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">% QUANTIDADE</th>
                        @if($mostrar_linha == 2)
                            <th class="estacao title-prod">PROD. MODELO</th>
                            <th class="estacao title-prod">% MODELO</th>
                            <th class="estacao title-prod">% PRODUÇÃO</th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @php $soma_prods = 0;
                    @php $contador   = 0;

                    @php $soma_prod_modelo = 0;

                    @foreach ( $dados as $linhas )

                    @if($mostrar_linha == 2)
                        <tr tabindex="0" data-campo-sql="MODELO" data-valor-sql="{{$linhas->DESCRICAO}}" class="add-filtro linhas-prod">
                    @else
                        <tr tabindex="0" class="linhas-prod">
                    @endif
                        
                            <td class="descricao  coll-prod"    title="">{{$linhas->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if(($mostrar_linha == 0)||($mostrar_linha == 1))
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        @endif
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if(($mostrar_linha == 0)||($mostrar_linha == 1))
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                        @endif  
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            @if($mostrar_linha == 2)

                            @php $qtd_modelo = 0;
                            @php $QTD_TURNO1 = 0;
                            @php $QTD_TURNO2 = 0;
                            @php $taloes = 0;

                            @foreach ( $dado8 as $prod_gp )
                                @if($linhas->DESCRICAO == $prod_gp->DESCRICAO )
                                    @php $qtd_modelo = $prod_gp->QUANTIDADE;
                                    @php $QTD_TURNO1 = $prod_gp->QTD_TURNO1;
                                    @php $QTD_TURNO2 = $prod_gp->QTD_TURNO2;
                                    @php $taloes     = $prod_gp->TALOES;
                                @endif
                            @endforeach

                            @if($qtd_modelo == 0)
                                @php $qtd_modelo = $linhas->QUANTIDADE;
                            @endif

                            @if($QTD_TURNO1 == 0)
                                @php $QTD_TURNO1 = 1;
                            @endif

                            @if($QTD_TURNO2 == 0)
                                @php $QTD_TURNO2 = 1;
                            @endif

                            <td class="coll-numeric  coll-prod" title="">{{number_format($qtd_modelo, 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($qtd_modelo == 0, $linhas->QUANTIDADE,$qtd_modelo)) * 100)  , 2, ',', '.')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/$QTD_TURNO1) * 100)  , 2, ',', '.')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/$QTD_TURNO2) * 100)  , 2, ',', '.')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                        @php $soma_prod_modelo = $soma_prod_modelo + floatval($qtd_modelo);

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            @endif

                        </tr> 
                        
                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @if($mostrar_linha == 2)
                            @php $soma_prods = $soma_prods + floatval(($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100);
                        @endif

                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '' )}}%</td>
                        @if($mostrar_linha == 2)
                            <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prod_modelo , 2, ',', '' )}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods , 2, ',', '' )}}%</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods , 2, ',', '' )}}%</td>
                        @endif
                    </tr>

                </tbody>
            </table>
        </div>
    
        @if($mostrar_linha == 0)
        <div role="tabpanel" class="tab-pane fade" id="tab4-container" aria-labelledby="tab4-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRICÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dado2 as $linhas )
                    
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if($mostrar_linha == 0)
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        @endif
                                    </div>

                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if($mostrar_linha == 0)
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        @endif
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                                
                        </tr>
                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table>
        </div>
        @endif

        @if($mostrar_linha == 1)

        <div role="tabpanel" class="tab-pane fade active in" id="tab2-container" aria-labelledby="tab2-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">LINHA</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dado2 as $linhas )
                    
                        <tr tabindex="0" class="linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->LINHA}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if($mostrar_linha == 1)
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                        @endif
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if($mostrar_linha == 1)
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                        @endif
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                        </tr>
                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab4-container" aria-labelledby="tab4-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRICÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dado3 as $linhas )
                    
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if($mostrar_linha == 1)
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                        @endif
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if($mostrar_linha == 1)
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                        @endif
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                                
                        </tr>
                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab20-container" aria-labelledby="tab20-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRICÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dado7 as $linhas )
                    
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if($mostrar_linha == 1)
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                        @endif
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if($mostrar_linha == 1)
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                        @endif
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                                
                        </tr>
                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table>
        </div>


        <div role="tabpanel" class="tab-pane fade" id="tab21-container" aria-labelledby="tab21-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRICÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dado8 as $linhas )
                    
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if($mostrar_linha == 1)
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                        @endif
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if($mostrar_linha == 1)
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$linhas->TALOES}}</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                        @endif
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                                
                        </tr>
                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table>
        </div>


        @endif

        @if($mostrar_linha == 2)

        <div role="tabpanel" class="tab-pane fade active in" id="tab2-container" aria-labelledby="tab2-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">LINHA</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">% QUANTIDADE</th>
                        <th class="estacao title-prod">PROD. LINHA</th>
                        <th class="estacao title-prod">% LINHA</th>
                        <th class="estacao title-prod">% PRODUÇÃO</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @php $soma_prods = 0;

                    @php $soma_prod_linha = 0;

                    @foreach ( $dado2 as $linhas )
                    
                        <tr tabindex="0"  data-campo-sql="LINHA" data-valor-sql="{{$linhas->LINHA}}"  class="add-filtro linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->LINHA}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
          
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
   
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            @php $qtd_linha = 0;
                            @php $QTD_TURNO1 = 0;
                            @php $QTD_TURNO2 = 0;
                            @php $taloes = 0;

                            @foreach ( $dado5 as $prod_linha )
                                @if($linhas->LINHA == $prod_linha->LINHA )
                                    @php $qtd_linha  = $prod_linha->QUANTIDADE;
                                    @php $QTD_TURNO1 = $prod_linha->QTD_TURNO1;
                                    @php $QTD_TURNO2 = $prod_linha->QTD_TURNO2;
                                    @php $taloes     = $prod_linha->TALOES;
                                @endif

                            @endforeach

                            @if($qtd_linha == 0)
                                @php $qtd_linha = $linhas->QUANTIDADE;
                            @endif

                            @if($QTD_TURNO1 == 0)
                                @php $QTD_TURNO1 = 1;
                            @endif

                            @if($QTD_TURNO2 == 0)
                                @php $QTD_TURNO2 = 1;
                            @endif

                            <td class="coll-numeric  coll-prod" title="">{{number_format(($qtd_linha)  , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/$qtd_linha) * 100)  , 2, ',', '.')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/$QTD_TURNO1) * 100)  , 2, ',', '.')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/$QTD_TURNO2) * 100)  , 2, ',', '.')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>


                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            
                            
                        </tr>

                        
                        @php $soma_prod_linha = $soma_prod_linha + floatval($qtd_linha);

                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @php $soma_prods = $soma_prods + floatval(($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100);
                        
                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '' )}}%</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prod_linha , 2, ',', '' )}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods , 2, ',', '' )}}%</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div role="tabpanel"class="tab-pane fade" id="tab3-container" aria-labelledby="tab3-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRICÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                        <th class="estacao title-prod">% PRODUÇÃO</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @php $soma_prods = 0;
                    @foreach ( $dado3 as $linhas )
                    
                        <tr tabindex="0"   data-campo-sql="DEFEITO" data-valor-sql="{{$linhas->DESCRICAO}}"  class="add-filtro linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
 
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                        </tr>
                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @php $soma_prods = $soma_prods + floatval(($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100);
                        

                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '' )}}%</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab4-container" aria-labelledby="tab4-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRICÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">% QUANTIDADE</th>
                        <th class="estacao title-prod">PROD. GP</th>
                        <th class="estacao title-prod">% GP</th>
                        <th class="estacao title-prod">% PRODUÇÃO</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @php $soma_prods = 0;

                    @php $soma_prod_gp = 0;

                    @foreach ( $dado4 as $linhas )
                    
                        <tr tabindex="0"  data-campo-sql="GP" data-valor-sql="{{$linhas->DESCRICAO}}" class="add-filtro linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
 
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
      
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            @php $qtd_gp = 0;
                            @php $QTD_TURNO1 = 0;
                            @php $QTD_TURNO2 = 0;
                            @php $taloes = 0;

                            @foreach ( $dado7 as $prod_gp )
                                @if($linhas->DESCRICAO == $prod_gp->DESCRICAO )
                                    @php $qtd_gp     = $prod_gp->QUANTIDADE;
                                    @php $QTD_TURNO1 = $prod_gp->QTD_TURNO1;
                                    @php $QTD_TURNO2 = $prod_gp->QTD_TURNO2;
                                    @php $taloes     = $prod_gp->TALOES;
                                @endif
                            @endforeach

                            @if($qtd_gp == 0)
                                @php $qtd_gp = $linhas->QUANTIDADE;
                            @endif

                            @if($QTD_TURNO1 == 0)
                                @php $QTD_TURNO1 = 1;
                            @endif

                            @if($QTD_TURNO2 == 0)
                                @php $QTD_TURNO2 = 1;
                            @endif

                            <td class="coll-numeric  coll-prod" title="">{{number_format($qtd_gp, 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/$qtd_gp) * 100)  , 2, ',', '.')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/$QTD_TURNO1) * 100)  , 2, ',', '.')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/$QTD_TURNO2) * 100)  , 2, ',', '.')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                                
                        </tr>

                        
                        @php $soma_prod_gp = $soma_prod_gp + floatval($qtd_gp);

                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @php $soma_prods = $soma_prods + floatval(($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100);
                        
                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total   , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct   , 2, ',', '' )}}%</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prod_gp , 2, ',', '' )}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods   , 2, ',', '' )}}%</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods   , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab20-container" aria-labelledby="tab20-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRICÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">% QUANTIDADE</th>
                        <th class="estacao title-prod">PROD. GP</th>
                        <th class="estacao title-prod">% GP</th>
                        <th class="estacao title-prod">% PRODUÇÃO</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @php $soma_prods = 0;

                    @php $soma_prod_gp = 0;

                    @foreach ( $dado10 as $linhas )
                    
                        <tr tabindex="0"  data-campo-sql="COR" data-valor-sql="{{$linhas->DESCRICAO}}" class="add-filtro linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
 
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
      
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            @php $qtd_gp = 0;
                            @php $QTD_TURNO1 = 0;
                            @php $QTD_TURNO2 = 0;
                            @php $taloes = 0;

                            @foreach ( $dado12 as $prod_gp )
                                @if($linhas->DESCRICAO == $prod_gp->DESCRICAO )
                                    @php $qtd_gp     = $prod_gp->QUANTIDADE;
                                    @php $QTD_TURNO1 = $prod_gp->QTD_TURNO1;
                                    @php $QTD_TURNO2 = $prod_gp->QTD_TURNO2;
                                    @php $taloes     = $prod_gp->TALOES;
                                @endif
                            @endforeach

                            @if($qtd_gp == 0)
                                @php $qtd_gp = $linhas->QUANTIDADE;
                            @endif

                            @if($QTD_TURNO1 == 0)
                                @php $QTD_TURNO1 = 1;
                            @endif

                            @if($QTD_TURNO2 == 0)
                                @php $QTD_TURNO2 = 1;
                            @endif

                            <td class="coll-numeric  coll-prod" title="">{{number_format($qtd_gp, 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/$qtd_gp) * 100)  , 2, ',', '.')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/$QTD_TURNO1) * 100)  , 2, ',', '.')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/$QTD_TURNO2) * 100)  , 2, ',', '.')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                                
                        </tr>

                        
                        @php $soma_prod_gp = $soma_prod_gp + floatval($qtd_gp);

                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @php $soma_prods = $soma_prods + floatval(($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100);
                        
                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total   , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct   , 2, ',', '' )}}%</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prod_gp , 2, ',', '' )}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods   , 2, ',', '' )}}%</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods   , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab21-container" aria-labelledby="tab21-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRICÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">% QUANTIDADE</th>
                        <th class="estacao title-prod">PROD. GP</th>
                        <th class="estacao title-prod">% GP</th>
                        <th class="estacao title-prod">% PRODUÇÃO</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @php $soma_prods = 0;

                    @php $soma_prod_gp = 0;

                    @foreach ( $dado11 as $linhas )
                    
                        <tr tabindex="0"  data-campo-sql="DENSIDADE" data-valor-sql="{{$linhas->DESCRICAO}}" class="add-filtro linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
 
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
      
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            @php $qtd_gp = 0;
                            @php $QTD_TURNO1 = 0;
                            @php $QTD_TURNO2 = 0;
                            @php $taloes = 0;

                            @foreach ( $dado13 as $prod_gp )
                                @if($linhas->DESCRICAO == $prod_gp->DESCRICAO )
                                    @php $qtd_gp     = $prod_gp->QUANTIDADE;
                                    @php $QTD_TURNO1 = $prod_gp->QTD_TURNO1;
                                    @php $QTD_TURNO2 = $prod_gp->QTD_TURNO2;
                                    @php $taloes     = $prod_gp->TALOES;
                                @endif
                            @endforeach

                            @if($qtd_gp == 0)
                                @php $qtd_gp = $linhas->QUANTIDADE;
                            @endif

                            @if($QTD_TURNO1 == 0)
                                @php $QTD_TURNO1 = 1;
                            @endif

                            @if($QTD_TURNO2 == 0)
                                @php $QTD_TURNO2 = 1;
                            @endif

                            <td class="coll-numeric  coll-prod" title="">{{number_format($qtd_gp, 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/$qtd_gp) * 100)  , 2, ',', '.')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/$QTD_TURNO1) * 100)  , 2, ',', '.')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/$QTD_TURNO2) * 100)  , 2, ',', '.')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>
                                
                        </tr>

                        
                        @php $soma_prod_gp = $soma_prod_gp + floatval($qtd_gp);

                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        @php $soma_prods = $soma_prods + floatval(($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100);
                        
                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total   , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct   , 2, ',', '' )}}%</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prod_gp , 2, ',', '' )}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods   , 2, ',', '' )}}%</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods   , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table>
        </div>

        @endif

        <div role="tabpanel" class="tab-pane fade" id="tab5-container" aria-labelledby="tab5-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRICÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">% QUANTIDADE</th>

                        @if($mostrar_linha == 2)
                            <th class="estacao title-prod">PROD. PERFIL</th>
                            <th class="estacao title-prod">% PERFIL</th>
                            <th class="estacao title-prod">% PRODUÇÃO</th>
                        @endif

                    </tr>
                </thead>
                <tbody>
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;

                    @php $soma_prod_perfil = 0;

                    @foreach ( $dado6 as $linhas )

                        @if($mostrar_linha == 2)
                            <tr tabindex="0"  data-campo-sql="PERFIL" data-valor-sql="{{$linhas->DESCRICAO}}"  class="add-filtro linhas-prod">
                        @else
                            <tr tabindex="0"  class="linhas-prod">
                        @endif
                    
                        
                            <td class="descricao  coll-prod"    title="">{{$linhas->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}
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
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO1, 2, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format($linhas->QTD_TURNO2, 2, ',', '.')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
 
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td>

                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%
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
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class='text-left'>2</td>
                                                    <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100)  , 2, ',', '')}}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
      
                                    </div>
                                    " data-original-title="Produção">
                                </span>
                            </td> 

                            @if($mostrar_linha == 2)

                                @php $qtd_perfil = 0;
                                @php $QTD_TURNO1 = 0;
                                @php $QTD_TURNO2 = 0;
                                @php $taloes = 0;

                                @foreach ( $dado9 as $prod_perfil )
                                    @if($linhas->DESCRICAO == $prod_perfil->DESCRICAO )
                                        @php $qtd_perfil = $prod_perfil->QUANTIDADE;
                                        @php $QTD_TURNO1 = $prod_perfil->QTD_TURNO1;
                                        @php $QTD_TURNO2 = $prod_perfil->QTD_TURNO2;
                                        @php $taloes     = $prod_perfil->TALOES;
                                    @endif
                                @endforeach

                                @if($qtd_perfil == 0)
                                    @php $qtd_perfil = $linhas->QUANTIDADE;
                                @endif

                                @if($QTD_TURNO1 == 0)
                                    @php $QTD_TURNO1 = 1;
                                @endif

                                @if($QTD_TURNO2 == 0)
                                    @php $QTD_TURNO2 = 1;
                                @endif

                                <td class="coll-numeric  coll-prod" title="">{{number_format($qtd_perfil, 2, ',', '.')}}
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
                                                        <td class='text-left'>{{number_format($QTD_TURNO1, 2, ',', '.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class='text-left'>2</td>
                                                        <td class='text-left'>{{number_format($QTD_TURNO2, 2, ',', '.')}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class='table table-striped table-bordered'>
                                                <thead>
                                                    <tr>
                                                        <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                    </tr>
                                                </thead>
                                            </table>

                                        </div>
                                        " data-original-title="Produção">
                                    </span>
                                </td>

                                <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/$qtd_perfil) * 100)  , 2, ',', '.')}}%
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
                                                        <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/$QTD_TURNO1) * 100)  , 2, ',', '.')}}%</td>
                                                    </tr>
                                                    <tr>
                                                        <td class='text-left'>2</td>
                                                        <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/$QTD_TURNO2) * 100)  , 2, ',', '.')}}%</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class='table table-striped table-bordered'>
                                                <thead>
                                                    <tr>
                                                        <th class='text-left'>{{'Talões:'.$taloes}}</th>
                                                    </tr>
                                                </thead>
                                            </table>

                                        </div>
                                        " data-original-title="Produção">
                                    </span>
                                </td>

                                <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%
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
                                                        <td class='text-left'>{{number_format((($linhas->QTD_TURNO1/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                    </tr>
                                                    <tr>
                                                        <td class='text-left'>2</td>
                                                        <td class='text-left'>{{number_format((($linhas->QTD_TURNO2/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100)  , 2, ',', '')}}%</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                        " data-original-title="Produção">
                                    </span>
                                </td>

                                    @php $soma_prod_perfil = $soma_prod_perfil + floatval($qtd_perfil);
                                    @php $soma_prods = $soma_prods + floatval(($linhas->QUANTIDADE/iff($prod == 0, $linhas->QUANTIDADE,$prod)) * 100);

                            @endif       
                                
                        </tr>

                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/iff($base == 0, $linhas->QUANTIDADE,$base)) * 100);
                        

                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '' )}}%</td>
                        @if($mostrar_linha == 2)
                            <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prod_perfil , 2, ',', '' )}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods , 2, ',', '' )}}%</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($soma_prods , 2, ',', '' )}}%</td>
                        @endif
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</fieldset>



