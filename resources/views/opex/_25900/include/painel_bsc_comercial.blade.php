<div class="bsc-container">
 
    <div class="bsc-coluna0 bsc-linha">
        <div class="desc-go-bsc">
            <div class="desc-esc"></div>
            <div class="desc-cet">
                <span class="glyphicon glyphicon-info-sign popover-detalhar" data-toggle="popover" data-placement="top" data-element-content="#data-tratamento"></span>  
                <div class="logo-bsc"></div>
                <div class="fras-bsc">{{$dados['DESCRICAO']}}</div>
            </div>
            <div class="desc-dir"></div>
        </div>

        <div id="data-tratamento" style="display: none;">
            {{$dados['DATA']}}
        </div>

        <div class="corpo-bsc">
            
            <div class="bsc-coluna00 bsc-linha tituli-lateral-bsc">
                <div class="grupos-bsc" style="height: calc({{$dados['PERC_LINHAS']}}% - 3px); background-color: #187BBD;">BSC</div>
                @foreach ($dados['AGRUPAMENTOS'] as $grupo)
                    <div class="grupos-bsc" style="height: calc({{$grupo['LINHAS']}}% - {{$grupo['AJUSTE']}}px); background-color: #{{$grupo['COR']}};" >
                        {{$grupo['DESCRICAO']}}
                    </div>
                @endforeach
            </div>
            
            <div class="corpo-linhas">
                <div class="bsc-coluna1 bsc-linha tituli-bsc" style="height: {{$dados['PERC_LINHAS']}}%;"><div>Indicadores</div></div>
                <div class="bsc-coluna2 bsc-linha tituli-bsc" style="height: {{$dados['PERC_LINHAS']}}%;"><div>Meta</div></div>
                <div class="bsc-coluna3 bsc-linha tituli-bsc" style="height: {{$dados['PERC_LINHAS']}}%;"><div>Peso</div></div>
                <div class="bsc-coluna4 bsc-linha tituli-bsc" style="height: {{$dados['PERC_LINHAS']}}%;"><div>{{$dados['MES1']}}</div></div>
                <div class="bsc-coluna5 bsc-linha tituli-bsc" style="height: {{$dados['PERC_LINHAS']}}%;"><div>{{$dados['MES2']}}</div></div>
                <div class="bsc-coluna6 bsc-linha tituli-bsc" style="height: {{$dados['PERC_LINHAS']}}%;"><div>{{$dados['MES3']}}</div></div>
                <div class="bsc-coluna4 bsc-linha tituli-bsc" style="height: {{$dados['PERC_LINHAS']}}%;"><div>{{$dados['MES4']}}</div></div>
                <div class="bsc-coluna5 bsc-linha tituli-bsc" style="height: {{$dados['PERC_LINHAS']}}%;"><div>{{$dados['MES5']}}</div></div>
                <div class="bsc-coluna6 bsc-linha tituli-bsc" style="height: {{$dados['PERC_LINHAS']}}%;"><div>{{$dados['MES6']}}</div></div>

                @foreach ($dados['INDICADORES'] as $indicador)
                    <div class="bsc-coluna1 bsc-linha desc-bsc" style="height: {{ $dados['PERC_LINHAS']}}%;">{{ $indicador['DESC'] }}</div>
                    <div class="bsc-coluna2 bsc-linha" style="height: {{$dados['PERC_LINHAS']}}%;">
                        <div class="bsc-meta a">{{ $indicador['DEF1'] }}</div>
                        <div class="bsc-meta b">{{ $indicador['DEF2'] }}</div>
                        <div class="bsc-meta c">{{ $indicador['DEF3'] }}</div>
                    </div>
                    
                    <div class="bsc-coluna3 bsc-linha val-bsc" style="height: {{ $dados['PERC_LINHAS'] }}%;">{{ $indicador['PESO'] }}</div>

                    <div class="bsc-coluna4 bsc-linha val-bsc  {{ 'cor-bsc'.$indicador['COR1'] }}" style="height: {{$dados['PERC_LINHAS'] }}%;">
                        <span class="glyphicon glyphicon-info-sign popover-detalhar" data-toggle="popover" data-placement="top" data-element-content="#detalhar-container1-{{$indicador['ORDEM']}}"></span>
                        <div style=" margin-top: -0.9vw;">{{ $indicador['VALOR1'] }}</div>
                    </div>

                    <div class="bsc-coluna5 bsc-linha val-bsc  {{ 'cor-bsc'.$indicador['COR2'] }}" style="height: {{$dados['PERC_LINHAS'] }}%;">
                        <span class="glyphicon glyphicon-info-sign popover-detalhar" data-toggle="popover" data-placement="top" data-element-content="#detalhar-container2-{{$indicador['ORDEM']}}"></span>
                        <div style=" margin-top: -0.9vw;">{{ $indicador['VALOR2'] }}</div>
                    </div>

                    <div class="bsc-coluna6 bsc-linha val-bsc  {{ 'cor-bsc'.$indicador['COR3'] }}" style="height: {{$dados['PERC_LINHAS'] }}%;">
                        <span class="glyphicon glyphicon-info-sign popover-detalhar" data-toggle="popover" data-placement="top" data-element-content="#detalhar-container3-{{$indicador['ORDEM']}}"></span>
                        <div style=" margin-top: -0.9vw;">{{ $indicador['VALOR3'] }}</div>
                    </div>

                    <div class="bsc-coluna7 bsc-linha val-bsc  {{ 'cor-bsc'.$indicador['COR4'] }}" style="height: {{$dados['PERC_LINHAS'] }}%;">
                        <span class="glyphicon glyphicon-info-sign popover-detalhar" data-toggle="popover" data-placement="top" data-element-content="#detalhar-container3-{{$indicador['ORDEM']}}"></span>
                        <div style=" margin-top: -0.9vw;">{{ $indicador['VALOR4'] }}</div>
                    </div>

                    <div class="bsc-coluna8 bsc-linha val-bsc  {{ 'cor-bsc'.$indicador['COR5'] }}" style="height: {{$dados['PERC_LINHAS'] }}%;">
                        <span class="glyphicon glyphicon-info-sign popover-detalhar" data-toggle="popover" data-placement="top" data-element-content="#detalhar-container3-{{$indicador['ORDEM']}}"></span>
                        <div style=" margin-top: -0.9vw;">{{ $indicador['VALOR5'] }}</div>
                    </div>

                    <div class="bsc-coluna9 bsc-linha val-bsc  {{ 'cor-bsc'.$indicador['COR6'] }}" style="height: {{$dados['PERC_LINHAS'] }}%;">
                        <span class="glyphicon glyphicon-info-sign popover-detalhar" data-toggle="popover" data-placement="top" data-element-content="#detalhar-container3-{{$indicador['ORDEM']}}"></span>
                        <div style=" margin-top: -0.9vw;">{{ $indicador['VALOR6'] }}</div>
                    </div>

                @endforeach
                
            </div>
        </div>
    </div>
     
</div>


<link rel="stylesheet" href="{{ elixir('assets/css/25900_bsc_comercial.css') }}" />

