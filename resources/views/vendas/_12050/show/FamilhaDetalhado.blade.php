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
                    @php $soma_quantidade = 0;
                    @foreach ( $dado1 as $linhas )
                        @php $soma_quantidade = $soma_quantidade + $linhas->QUANTIDADE;
                    @endforeach

                    @php $contador = 0;
                    @foreach ( $dado1 as $linhas )
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->RAZAOSOCIAL}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format(($linhas->QUANTIDADE/$soma_quantidade)*100 , 2, ',', '.')}}%</td>
                        </tr>
                        @php $contador++;
                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_quantidade , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">100%</td>
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

                    @php $soma_quantidade = 0;
                    @foreach ( $dado2 as $linhas )
                        @php $soma_quantidade = $soma_quantidade + $linhas->QUANTIDADE;
                    @endforeach

                    @php $contador = 0;
                    @foreach ( $dado2 as $linhas )
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->RAZAOSOCIAL}}</td>
                            <td class="descricao  coll-prod"    title="">{{$linhas->UF}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format(($linhas->QUANTIDADE/$soma_quantidade)*100 , 2, ',', '.')}}%</td>
                        </tr>
                        @php $contador++;
                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="descricao  coll-prod"    title=""></td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_quantidade , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">100%</td>
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

                    @php $soma_quantidade = 0;
                    @foreach ( $dado3 as $linhas )
                        @php $soma_quantidade = $soma_quantidade + $linhas->QUANTIDADE;
                    @endforeach

                    @php $contador = 0;
                    @foreach ( $dado3 as $linhas )
                        <tr tabindex="0" class=" linhas-prod">
                            <td class="descricao  coll-prod"    title="">{{$linhas->UF}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format($linhas->QUANTIDADE , 2, ',', '.')}}</td>
                            <td class="coll-numeric  coll-prod" title="">{{number_format(($linhas->QUANTIDADE/$soma_quantidade)*100 , 2, ',', '.')}}%</td>
                        </tr>
                        @php $contador++;
                    @endforeach

                    <tr tabindex="0" class="linta-total">
                        <td class="descricao  coll-prod"    title="">TOTAL</td>
                        <td class="coll-numeric  coll-prod" title="">{{number_format($soma_quantidade , 2, ',', '.')}}</td>
                        <td class="coll-numeric  coll-prod" title="">100%</td>
                    </tr>

                </tbody>
            </table>    
        </div>
    </div>

</fieldset>



