
    <div class="topo-producao">
        <div class="titulo-table1">
            <div class="desc-fab font-ajustavel5">{{ $adic['desc'] }}</div>
            <div class="desc-datas">Data:{{ $adic['data_consulta'] }} tratada em {{ $adic['data_exec'] }}</div>
        </div>
        <div class="titulo-table2 font-ajustavel5">
            <div id="relogio" class="tempo-producao" >
                <span id="hora" class="hora-producao"></span>
            </div>
            
            {{--<span class="glyphicon glyphicon-fullscreen go-fullscreen btn-full" gofullscreen="tela-full"></span>--}}
        </div>
    </div>

    <div class="turno1-producao">
        <div class="bsc1 font-ajustavel2">1º TURNO</div>
        <div class="bsc2 font-ajustavel2">{{ $adic['desc_flag'] }}</div>
        <div class="bsc3 font-ajustavel2">GERAL</div>
    </div>
    
    <div class="turno2-producao">
        <div class="bsc1 font-ajustavel2">2º TURNO</div>
        <div class="bsc2 font-ajustavel2">{{ $adic['desc_flag'] }}</div>
        <div class="bsc3 font-ajustavel2">GERAL</div>
    </div>

    <div class="geral-producao font-ajustavel3">GERAL DOS TURNOS</div>
    <div class="meta-producao font-ajustavel4 "><div class="div-center2">META</div></div>
    <div class="semana-producao font-ajustavel4 "><div class="div-center">SEMANA</div></div>
    <div class="mes-producao font-ajustavel4 "><div class="div-center">MÊS</div></div>
    
    <div class="perdas-producao1 font-ajustavel2">PRODUÇÃO</div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel2"></div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    <div class="perdas-producao4 font-ajustavel2">
        {{-- <span class="glyphicon glyphicon-info-sign info-prod" ></span> --}}
    </div>
    
    <div class="coluna1 producao-turno1-hora-producao fonte-valores                     {{ 'cor-'.$adic['corV1'] }}">  <div><span class="font-ajustavel">{{ $adic['flag'] == 1 ? $ret['PR1'] : $ret['MPR1'] }}</span></div></div>
    <div class="coluna2 producao-turno1-geral-producao fonte-valores                    {{ 'cor-'.$adic['corV2'] }}">  <div><span class="font-ajustavel">{{ $ret['PR2'] }}</span></div></div>
    <div class="coluna3 producao-turno2-hora-producao fonte-valores fonte-valores       {{ 'cor-'.$adic['corV3'] }}">  <div><span class="font-ajustavel">{{ $adic['flag'] == 1 ? $ret['PR3'] : $ret['MPR3'] }}</span></div></div>
    <div class="coluna4 producao-turno2-geral-producao fonte-valores fonte-valores      {{ 'cor-'.$adic['corV4'] }}">  <div><span class="font-ajustavel">{{ $ret['PR4'] }}</span></div></div>
    <div class="producao-geraldosturnos-producao fonte-valores fonte-valores    {{ 'cor-'.$adic['corV5'] }}">  <div><span class="font-ajustavel">{{ $ret['PR5'] }}</span></div></div>
    <div class="producao-meta-producao fonte-valores fonte-valores">            <div class="font-ajustavel"><span>{{ $ret['META_DIA'] }}</span></div></div>
    <div class="producao-semana-producao fonte-valores fonte-valores            {{ 'cor-'.$adic['corV6'] }}">  <div><span class="font-ajustavel">{{ $ret['PR_SEMANA'] }}</span></div></div>
    <div class="producao-mes-producao fonte-valores fonte-valores               {{ 'cor-'.$adic['corV7'] }}">  <div><span class="font-ajustavel">{{ $ret['PR_MEZ'] }}</span></div></div>
    
    <div class="perdas-producao1 font-ajustavel2">DIFERENÇA</div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel3 tcenter">M. HORA</div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    
    <div class="coluna1 diferenca-turno1-hora-producao fonte-valores fonte-valores      {{ 'cor-'.$adic['corV1'] }}">  <div><span class="font-ajustavel no-negative">{{ $adic['flag'] == 1 ? $ret['DI1'] : $ret['MDI1'] }}</span></div></div>
    <div class="coluna2 diferenca-turno1-geral-producao fonte-valores fonte-valores     {{ 'cor-'.$adic['corV2'] }}">  <div><span class="font-ajustavel no-negative">{{ $ret['DI2'] }}</span></div></div>
    <div class="coluna3 diferenca-turno2-hora-producao fonte-valores fonte-valores      {{ 'cor-'.$adic['corV3'] }}">  <div><span class="font-ajustavel no-negative">{{ $adic['flag'] == 1 ? $ret['DI3'] : $ret['MDI3'] }}</span></div></div>
    <div class="coluna4 diferenca-turno2-geral-producao fonte-valores fonte-valores     {{ 'cor-'.$adic['corV4'] }}">  <div><span class="font-ajustavel no-negative">{{ $ret['DI4'] }}</span></div></div>
    <div class="diferenca-geraldosturnos-producao fonte-valores fonte-valores   {{ 'cor-'.$adic['corV5'] }}">  <div><span class="font-ajustavel no-negative">{{ $ret['DI5'] }}</span></div></div>
    <div class="diferenca-meta-producao fonte-valores fonte-valores ">           <div class="font-ajustavel"><span>{{ $ret['META_HORA'] }}</span></div></div>
    <div class="diferenca-semana-producao fonte-valores fonte-valores           {{ 'cor-'.$adic['corV6'] }}">  <div><span class="font-ajustavel  no-negative">{{ $ret['DI_SEMANA'] }}</span></div></div>
    <div class="diferenca-mes-producao fonte-valores fonte-valores              {{ 'cor-'.$adic['corV7'] }}">  <div><span class="font-ajustavel  no-negative">{{ $ret['DI_MEZ'] }}</span></div></div>
    
    <div class="perdas-producao1 font-ajustavel2">EFICIÊNCIA</div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel3 tcenter">META DO MÊS</div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    
    <div class="coluna1 eficiencia-turno1-hora-producao fonte-valores fonte-valores     {{ 'cor-'.$adic['corV1'] }}">  <div><span class="font-ajustavel">{{ $adic['flag'] == 1 ? $ret['EF1'] : $ret['MEF1'] }}%</div><input type="hidden" class="Efic" value="{{ $adic['flag'] == 1 ? $ret['EF1'] : 1 }}"></div>
    <div class="coluna2 eficiencia-turno1-geral-producao fonte-valores fonte-valores    {{ 'cor-'.$adic['corV2'] }}">  <div><span class="font-ajustavel">{{ $ret['EF2'] }}%</div><input type="hidden" class="Efic" value="{{ $ret['EF2'] }}"></div>
    <div class="coluna3 eficiencia-turno2-hora-producao fonte-valores fonte-valores     {{ 'cor-'.$adic['corV3'] }}">  <div><span class="font-ajustavel">{{ $adic['flag'] == 1 ? $ret['EF3'] : $ret['MEF3'] }}%</div><input type="hidden" class="Efic" value="{{ $adic['flag'] == 1 ? $ret['EF3'] : 1 }}"></div>
    <div class="coluna4 eficiencia-turno2-geral-producao fonte-valores fonte-valores    {{ 'cor-'.$adic['corV4'] }}">  <div><span class="font-ajustavel">{{ $ret['EF4'] }}%</div><input type="hidden" class="Efic" value="{{ $ret['EF4'] }}"></div>
    <div class="eficiencia-geraldosturnos-producao fonte-valores fonte-valores  {{ 'cor-'.$adic['corV5'] }}">  <div><span class="font-ajustavel">{{ $ret['EF5'] }}%</div><input type="hidden" class="Efic" value="{{ $ret['EF5'] }}"></div>
    <div class="eficiencia-meta-producao fonte-valores fonte-valores">
        <div class="mestas-mez font-ajustavel6 tcenter cor-1">{{ $adic['descEfic1'] }}</span></div>
        <div class="mestas-mez font-ajustavel6 tcenter cor-2">{{ $adic['descEfic2'] }}</span></div>
        <div class="mestas-mez font-ajustavel6 tcenter cor-3">{{ $adic['descEfic3'] }}</span></div>
    </div>
    <div class="eficiencia-semana-producao fonte-valores fonte-valores          {{ 'cor-'.$adic['corV6'] }}">  <div><span class="font-ajustavel">{{ $ret['EF_SEMANA'] }}%</div></div>
    <div class="eficiencia-mes-producao fonte-valores fonte-valores             {{ 'cor-'.$adic['corV7'] }}">  <div><span class="font-ajustavel">{{ $ret['EF_MEZ'] }}%</div></div>
    
    <div class="perdas-producao1 font-ajustavel2">PERDAS </div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel2 tcenter"><span>{{ $ret['EFP'] }}</span></div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    
    <div class="colunaP1 perdasL1-turno1-hora-producao fonte-valores fonte-valores       {{ 'cor-'.$adic['corP1'] }}">  <div><span class="font-ajustavel">{{ $adic['flag'] == 1 ? $ret['PE1'] : $ret['MPE1'] }}</span></div><input type="hidden" class="Efic" value="{{ $adic['flag'] == 1 ? $ret['PE1'] : 1 }}"></div>
    <div class="colunaP2 perdasL1-turno1-geral-producao fonte-valores fonte-valores      {{ 'cor-'.$adic['corP2'] }}">  <div><span class="font-ajustavel">{{ $ret['PE2'] }}</span></div><input type="hidden" class="Efic" value="{{ $ret['PE2'] }}"></div>
    <div class="colunaP3 perdasL1-turno2-hora-producao fonte-valores fonte-valores       {{ 'cor-'.$adic['corP3'] }}">  <div><span class="font-ajustavel">{{ $adic['flag'] == 1 ? $ret['PE3'] : $ret['MPE3'] }}</span></div><input type="hidden" class="Efic" value="{{ $adic['flag'] == 1 ? $ret['PE3'] : 1 }}"></div>
    <div class="colunaP4 perdasL1-turno2-geral-producao fonte-valores fonte-valores      {{ 'cor-'.$adic['corP4'] }}">  <div><span class="font-ajustavel">{{ $ret['PE4'] }}</span></div><input type="hidden" class="Efic" value="{{ $ret['PE4'] }}"></div>
    <div class="perdasL1-geraldosturnos-producao fonte-valores fonte-valores    {{ 'cor-'.$adic['corP5'] }}">  <div><span class="font-ajustavel">{{ $ret['PE5'] }}</span></div></div>
            <div class="perdasL1-meta-producao fonte-valores fonte-valores fundo-blak"> <div class="font-ajustavel3 tcenter">
                <div class="perdas-mez font-ajustavel6 tcenter cor-998"></span>...</div>
                <div class="perdas-mez font-ajustavel6 tcenter cor-998"></span>...</div>
                <div class="perdas-mez font-ajustavel6 tcenter cor-999">META DO MÊS</span></div>
            </div></div>
    <div class="perdasL1-semana-producao fonte-valores fonte-valores            {{ 'cor-'.$adic['corP6'] }}">  <div><span class="font-ajustavel">{{ $ret['PE_SEMANA'] }}</span></div></div>
    <div class="perdasL1-mes-producao fonte-valores fonte-valores               {{ 'cor-'.$adic['corP7'] }}">  <div><span class="font-ajustavel">{{ $ret['PE_MEZ'] }}</span></div></div>
    
    <div class="colunaP1 perdasL2-turno1-hora-producao fonte-valores fonte-valores       {{ 'cor-'.$adic['corP1'] }}">  <div><span class="font-ajustavel">{{ $adic['flag'] == 1 ? $ret['PEP1'] : $ret['MPEP1'] }}%</div></div>
    <div class="colunaP2 perdasL2-turno1-geral-producao fonte-valores fonte-valores      {{ 'cor-'.$adic['corP2'] }}">  <div><span class="font-ajustavel">{{ $ret['PEP2'] }}%</div></div>
    <div class="colunaP3 perdasL2-turno2-hora-producao fonte-valores fonte-valores       {{ 'cor-'.$adic['corP3'] }}">  <div><span class="font-ajustavel">{{ $adic['flag'] == 1 ? $ret['PEP3'] : $ret['MPEP3'] }}%</div></div>
    <div class="colunaP4 perdasL2-turno2-geral-producao fonte-valores fonte-valores      {{ 'cor-'.$adic['corP4'] }}">  <div><span class="font-ajustavel">{{ $ret['PEP4'] }}%</div></div>
    <div class="perdasL2-geraldosturnos-producao fonte-valores fonte-valores    {{ 'cor-'.$adic['corP5'] }}">  <div><span class="font-ajustavel">{{ $ret['PEP5'] }}%</div></div>
            <div class="perdasL2-meta-producao fonte-valores fonte-valores fundo-branco">
                <div class="font-ajustavel">
                    <div class="mestas-mez font-ajustavel6 tcenter cor-1">{{ $adic['descEficP1'] }}</span></div>
                    <div class="mestas-mez font-ajustavel6 tcenter cor-2">{{ $adic['descEficP2'] }}</span></div>
                    <div class="mestas-mez font-ajustavel6 tcenter cor-3">{{ $adic['descEficP3'] }}</span></div>
                </div>
            </div>
    <div class="perdasL2-semana-producao fonte-valores fonte-valores            {{ 'cor-'.$adic['corP6'] }}">  <div><span class="font-ajustavel">{{ $ret['PEP_SEMANA'] }}%</div></div>
    <div class="perdasL2-mes-producao fonte-valores fonte-valores               {{ 'cor-'.$adic['corP7'] }}">  <div><span class="font-ajustavel">{{ $ret['PEP_MEZ'] }}%</div></div>
    
    <div class="pseparador-producao"></div>
    
    <div class="dados-prod">
       <span class="fa fa-close fechar-info-prod" gofullscreen="fundo-tela"></span>
       <table class="tabela-info-prod">
       <?php
        foreach($ret as $key => $value){
            echo '<tr><td>'.$key.': '.$value.'</td></tr>';
        }
       ?>
       </table>
    </div>

    <div class="lista-hora-ranking" style="display: none;">
        
            @foreach ($HoraRanking as $Ranking)
                <input type="hidden" class="hora-ranking" horainicio="{{$Ranking->HORA_INICIO}}" horafim="{{$Ranking->HORA_FIM}}">
            @endforeach 
            
    </div>