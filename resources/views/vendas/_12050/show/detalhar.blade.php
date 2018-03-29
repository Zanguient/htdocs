<fieldset class="tab-container">

    <ul id="tab" class="nav nav-tabs acoes" role="tablist"> 

        <li role="presentation" class="active tab-detalhamento">
            <a href="#tab1-container" id="tab1-tab" role="tab" data-toggle="tab" aria-controls="tab1-container" aria-expanded="true">
                Agrupamento por Cliente
            </a>
        </li> 
 
        <li role="presentation" class="tab-detalhamento">
            <a href="#tab2-container" id="tab2-tab" role="tab" data-toggle="tab" aria-controls="tab2-container" aria-expanded="false">
                Agrupamento por Representante
            </a>
        </li>

        <li role="presentation" class="tab-detalhamento">
            <a href="#tab3-container" id="tab3-tab" role="tab" data-toggle="tab" aria-controls="tab3-container" aria-expanded="false">
                Agrupamento por UF
            </a>
        </li>

        <li role="presentation" class="tab-detalhamento">
            <a href="#tab4-container" id="tab4-tab" role="tab" data-toggle="tab" aria-controls="tab4-container" aria-expanded="false">
                Agrupamento por Perfil
            </a>
        </li>

        <li role="presentation" class="tab-detalhamento">
            <a href="#tab5-container" id="tab5-tab" role="tab" data-toggle="tab" aria-controls="tab5-container" aria-expanded="false">
                Agrupamento por Linha
            </a>
        </li>

        <li role="presentation" class="tab-detalhamento">
            <a href="#tab6-container" id="tab6-tab" role="tab" data-toggle="tab" aria-controls="tab6-container" aria-expanded="false">
                Agrupamento por Cor
            </a>
        </li>

    </ul>

    <div id="tab-content" class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="tab1-container" aria-labelledby="tab1-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">CLIENTE</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>

                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dados as $linhas )
                    
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->CLIENTE}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linhas->QUANTIDADE/$base) * 100)  , 2, ',', '')}}%</td>
                        </tr>
                        
                        @php $soma_total = $soma_total + floatval($linhas->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linhas->QUANTIDADE/$base) * 100);

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
        <div role="tabpanel" class="tab-pane fade" id="tab2-container" aria-labelledby="tab2-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">REPRESENTANTE</th>
                        <th class="estacao title-prod">UF</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>
                
                    @php $contador1 = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dado2 as $linha )
                    
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linha->REPRESENTANTE}}</td>
                            <td class="descricao  coll-prod"    title="">{{$linha->UF}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linha->QUANTIDADE , 2, ',', '.')}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linha->QUANTIDADE/$base) * 100)  , 2, ',', '')}}%</td>
                        </tr>
                        
                        @php $soma_total = $soma_total + floatval($linha->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linha->QUANTIDADE/$base) * 100);

                        @php $contador1++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="descricao  coll-prod"    title=""></td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '' )}}%</td>
                    </tr>

                </tbody>
            </table> 
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab3-container" aria-labelledby="tab3-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">UF</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>
                
                    @php $contador1 = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dado3 as $linha )
                    
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linha->UF}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linha->QUANTIDADE , 2, ',', '.')}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linha->QUANTIDADE/$base) * 100)  , 2, ',', '')}}%</td>
                        </tr>

                        @php $soma_total = $soma_total + floatval($linha->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linha->QUANTIDADE/$base) * 100);

                        @php $contador1++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '.' )}}%</td>
                    </tr>

                </tbody>
            </table> 
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab4-container" aria-labelledby="tab4-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRIÇÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>
                
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dado4 as $linha )
                    
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linha->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linha->QUANTIDADE , 2, ',', '.')}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linha->QUANTIDADE/$base) * 100)  , 2, ',', '')}}%</td>
                        </tr>

                        @php $soma_total = $soma_total + floatval($linha->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linha->QUANTIDADE/$base) * 100);

                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '.' )}}%</td>
                    </tr>

                </tbody>
            </table> 
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab5-container" aria-labelledby="tab5-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRIÇÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>
                
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dado5 as $linha )
                    
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linha->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linha->QUANTIDADE , 2, ',', '.')}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linha->QUANTIDADE/$base) * 100)  , 2, ',', '')}}%</td>
                        </tr>

                        @php $soma_total = $soma_total + floatval($linha->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linha->QUANTIDADE/$base) * 100);

                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '.' )}}%</td>
                    </tr>

                </tbody>
            </table> 
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab6-container" aria-labelledby="tab6-tab">
            <table class="table table-striped table-bordered table-hover historico-corpo-tabela">
                <thead>
                    <tr>
                        <th class="estacao title-prod">DESCRIÇÃO</th>
                        <th class="estacao title-prod">QUANTIDADE</th>
                        <th class="estacao title-prod">QUANTIDADE %</th>
                    </tr>
                </thead>
                <tbody>
                
                    @php $contador = 0;
                    @php $soma_total = 0;
                    @php $soma_perct = 0;
                    @foreach ( $dado6 as $linha )
                    
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linha->DESCRICAO}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linha->QUANTIDADE , 2, ',', '.')}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format((($linha->QUANTIDADE/$base) * 100)  , 2, ',', '')}}%</td>
                        </tr>

                        @php $soma_total = $soma_total + floatval($linha->QUANTIDADE);
                        @php $soma_perct = $soma_perct + floatval(($linha->QUANTIDADE/$base) * 100);

                        @php $contador++;

                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_total , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_perct , 2, ',', '.' )}}%</td>
                    </tr>

                </tbody>
            </table> 
        </div>

    </div>

</fieldset>

