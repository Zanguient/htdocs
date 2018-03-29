
    <div class="topo-producao">
        <div class="titulo-table1">
            <div class="desc-fab font-ajustavel5">{{$dados['DESCRICAO']}}</div>
            <div class="desc-datas">{{$dados['DATA']}}</div>
        </div>
        <div class="titulo-table2 font-ajustavel5">
            <div id="relogio" class="tempo-producao" >
                <span id="hora" class="hora-producao"></span>
            </div>
         </div>
    </div>

    <div class="turno1-producao">
        <div class="bsc1 font-ajustavel2">1º TURNO</div>
        <div class="bsc2 font-ajustavel2">{{$dados['DESC_TURNO']}}</div>
        <div class="bsc3 font-ajustavel2">GERAL</div>
    </div>
    
    <div class="turno2-producao">
        <div class="bsc1 font-ajustavel2">2º TURNO</div>
        <div class="bsc2 font-ajustavel2">{{$dados['DESC_TURNO']}}</div>
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
    <div class="perdas-producao4 font-ajustavel2"></div>
    
    <div class="coluna1 producao-turno1-hora-producao fonte-valores                     {{'cor-'.$dados['CorPr1']}}">  <div><span class="font-ajustavel">{{$dados['PR1']}}</span></div></div>
    <div class="coluna2 producao-turno1-geral-producao fonte-valores                    {{'cor-'.$dados['CorPr2']}}">  <div><span class="font-ajustavel">{{$dados['PR2']}}</span></div></div>
    <div class="coluna3 producao-turno2-hora-producao fonte-valores fonte-valores       {{'cor-'.$dados['CorPr3']}}">  <div><span class="font-ajustavel">{{$dados['PR3']}}</span></div></div>
    <div class="coluna4 producao-turno2-geral-producao fonte-valores fonte-valores      {{'cor-'.$dados['CorPr4']}}">  <div><span class="font-ajustavel">{{$dados['PR4']}}</span></div></div>
    <div class="producao-geraldosturnos-producao fonte-valores fonte-valores            {{'cor-'.$dados['CorPr5']}}">  <div><span class="font-ajustavel">{{$dados['PR5']}}</span></div></div>
    <div class="producao-meta-producao fonte-valores fonte-valores                                                 ">  <div class="font-ajustavel">{{$dados['META_DIA']}}<span></span></div></div>
    <div class="producao-semana-producao fonte-valores fonte-valores               {{'cor-'.$dados['CorPrSemana']}}">  <div><span class="font-ajustavel">{{$dados['PR_SEMANA']}}</span></div></div>
    <div class="producao-mes-producao fonte-valores fonte-valores                     {{'cor-'.$dados['CorPrMes']}}">  <div><span class="font-ajustavel">{{$dados['PR_MES']}}</span></div></div>
    
    <div class="perdas-producao1 font-ajustavel2">DIFERENÇA</div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel3 tcenter">M. HORA</div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    
    <div class="coluna1 diferenca-turno1-hora-producao fonte-valores fonte-valores      {{'cor-'.$dados['CorPr1']}}">  <div><span class="font-ajustavel no-negative">{{$dados['DI1']}}</span></div></div>
    <div class="coluna2 diferenca-turno1-geral-producao fonte-valores fonte-valores     {{'cor-'.$dados['CorPr2']}}">  <div><span class="font-ajustavel no-negative">{{$dados['DI2']}}</span></div></div>
    <div class="coluna3 diferenca-turno2-hora-producao fonte-valores fonte-valores      {{'cor-'.$dados['CorPr3']}}">  <div><span class="font-ajustavel no-negative">{{$dados['DI3']}}</span></div></div>
    <div class="coluna4 diferenca-turno2-geral-producao fonte-valores fonte-valores     {{'cor-'.$dados['CorPr4']}}">  <div><span class="font-ajustavel no-negative">{{$dados['DI4']}}</span></div></div>
    <div class="diferenca-geraldosturnos-producao fonte-valores fonte-valores           {{'cor-'.$dados['CorPr5']}}">  <div><span class="font-ajustavel no-negative">{{$dados['DI5']}}</span></div></div>
    <div class="diferenca-meta-producao fonte-valores fonte-valores                  ">  <div class="font-ajustavel">  {{$dados['META_HORA']}}</span></div></div>
    <div class="diferenca-semana-producao fonte-valores fonte-valores              {{'cor-'.$dados['CorPrSemana']}}">  <div><span class="font-ajustavel  no-negative">{{$dados['DI_SEMANA']}}</span></div></div>
    <div class="diferenca-mes-producao fonte-valores fonte-valores                    {{'cor-'.$dados['CorPrMes']}}">  <div><span class="font-ajustavel  no-negative">{{$dados['DI_MES']}}</span></div></div>
    
    <div class="perdas-producao1 font-ajustavel2">EFICIÊNCIA</div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel3 tcenter">META DO MÊS</div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    
    <div class="coluna1 eficiencia-turno1-hora-producao fonte-valores fonte-valores     {{'cor-'.$dados['CorPr1']}}">  <div><span class="font-ajustavel">{{$dados['FC1']}}%</span></div><input type="hidden" class="Efic" value=""></div>
    <div class="coluna2 eficiencia-turno1-geral-producao fonte-valores fonte-valores    {{'cor-'.$dados['CorPr2']}}">  <div><span class="font-ajustavel">{{$dados['FC2']}}%</span></div><input type="hidden" class="Efic" value=""></div>
    <div class="coluna3 eficiencia-turno2-hora-producao fonte-valores fonte-valores     {{'cor-'.$dados['CorPr3']}}">  <div><span class="font-ajustavel">{{$dados['FC3']}}%</span></div><input type="hidden" class="Efic" value=""></div>
    <div class="coluna4 eficiencia-turno2-geral-producao fonte-valores fonte-valores    {{'cor-'.$dados['CorPr4']}}">  <div><span class="font-ajustavel">{{$dados['FC4']}}%</span></div><input type="hidden" class="Efic" value=""></div>
    <div class="eficiencia-geraldosturnos-producao fonte-valores fonte-valores          {{'cor-'.$dados['CorPr5']}}">  <div><span class="font-ajustavel">{{$dados['FC5']}}%</span></div><input type="hidden" class="Efic" value=""></div>
    <div class="eficiencia-meta-producao fonte-valores fonte-valores">
        <div class="mestas-mez font-ajustavel6 tcenter cor-1">{{$dados['FAIXAMES1']}}</div>
        <div class="mestas-mez font-ajustavel6 tcenter cor-2">{{$dados['FAIXAMES2']}}</div>
        <div class="mestas-mez font-ajustavel6 tcenter cor-3">{{$dados['FAIXAMES3']}}</div>
    </div>
    <div class="eficiencia-semana-producao fonte-valores fonte-valores {{'cor-'.$dados['CorPrSemana']}}">  <div><span class="font-ajustavel">{{$dados['FC_SEMANA']}}%</span></div></div>
    <div class="eficiencia-mes-producao fonte-valores fonte-valores    {{'cor-'.$dados['CorPrMes']}}   ">  <div><span class="font-ajustavel">{{$dados['FC_MES']}}%</span></div></div>
    
    <div class="perdas-producao1 font-ajustavel2">PERDAS </div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao2 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel2"></div>
    <div class="perdas-producao3 font-ajustavel2 tcenter"><span>{{$dados['VELOCIDADE']}}</span></div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    <div class="perdas-producao4 font-ajustavel2"></div>
    
    <div class="colunaP1 perdasL1-turno1-hora-producao fonte-valores fonte-valores       {{'cor-'.$dados['CorPe1']}}">  <div><span class="font-ajustavel">{{$dados['PE1']}}</span></div><input type="hidden" class="Efic" value=""></div>
    <div class="colunaP2 perdasL1-turno1-geral-producao fonte-valores fonte-valores      {{'cor-'.$dados['CorPe2']}}">  <div><span class="font-ajustavel">{{$dados['PE2']}}</span></div><input type="hidden" class="Efic" value=""></div>
    <div class="colunaP3 perdasL1-turno2-hora-producao fonte-valores fonte-valores       {{'cor-'.$dados['CorPe3']}}">  <div><span class="font-ajustavel">{{$dados['PE3']}}</span></div><input type="hidden" class="Efic" value=""></div>
    <div class="colunaP4 perdasL1-turno2-geral-producao fonte-valores fonte-valores      {{'cor-'.$dados['CorPe4']}}">  <div><span class="font-ajustavel">{{$dados['PE4']}}</span></div><input type="hidden" class="Efic" value=""></div>
    <div class="perdasL1-geraldosturnos-producao fonte-valores fonte-valores             {{'cor-'.$dados['CorPe5']}}">  <div><span class="font-ajustavel">{{$dados['PE5']}}</span></div></div>
            <div class="perdasL1-meta-producao fonte-valores fonte-valores fundo-blak"> <div class="font-ajustavel3 tcenter">
                <div class="perdas-mez font-ajustavel6 tcenter cor-998"></span>...</div>
                <div class="perdas-mez font-ajustavel6 tcenter cor-998"></span>...</div>
                <div class="perdas-mez font-ajustavel6 tcenter cor-999">META DO MÊS</span></div>
            </div></div>
    <div class="perdasL1-semana-producao fonte-valores fonte-valores            {{'cor-'.$dados['CorPeSemana']}}">  <div><span class="font-ajustavel">{{$dados['PE_SEMANA']}}</span></div></div>
    <div class="perdasL1-mes-producao fonte-valores fonte-valores               {{'cor-'.$dados['CorPeMes']}}   ">  <div><span class="font-ajustavel">{{$dados['PE_MES']}}</span></div></div>
    
    <div class="colunaP1 perdasL2-turno1-hora-producao fonte-valores fonte-valores       {{'cor-'.$dados['CorPe1']}}">  <div><span class="font-ajustavel">{{$dados['FP1']}}%</span></div></div>
    <div class="colunaP2 perdasL2-turno1-geral-producao fonte-valores fonte-valores      {{'cor-'.$dados['CorPe2']}}">  <div><span class="font-ajustavel">{{$dados['FP2']}}%</span></div></div>
    <div class="colunaP3 perdasL2-turno2-hora-producao fonte-valores fonte-valores       {{'cor-'.$dados['CorPe3']}}">  <div><span class="font-ajustavel">{{$dados['FP3']}}%</span></div></div>
    <div class="colunaP4 perdasL2-turno2-geral-producao fonte-valores fonte-valores      {{'cor-'.$dados['CorPe4']}}">  <div><span class="font-ajustavel">{{$dados['FP4']}}%</span></div></div>
    <div class="perdasL2-geraldosturnos-producao fonte-valores fonte-valores             {{'cor-'.$dados['CorPe5']}}">  <div><span class="font-ajustavel">{{$dados['FP5']}}%</span></div></div>
            <div class="perdasL2-meta-producao fonte-valores fonte-valores fundo-branco">
                <div class="font-ajustavel">
                    <div class="mestas-mez font-ajustavel6 tcenter cor-1">{{$dados['FAIXAMESP1']}}</div>
                    <div class="mestas-mez font-ajustavel6 tcenter cor-2">{{$dados['FAIXAMESP2']}}</div>
                    <div class="mestas-mez font-ajustavel6 tcenter cor-3">{{$dados['FAIXAMESP3']}}</div>
                </div>
            </div>
    <div class="perdasL2-semana-producao fonte-valores fonte-valores           {{'cor-'.$dados['CorPeSemana']}}">  <div><span class="font-ajustavel">{{$dados['FP_SEMANA']}}%</span></div></div>
    <div class="perdasL2-mes-producao fonte-valores fonte-valores              {{'cor-'.$dados['CorPeMes']}}   ">  <div><span class="font-ajustavel">{{$dados['FP_MES']}}%</span></div></div>
    
    <div class="pseparador-producao"></div>
    
    <div class="dados-prod">
       <span class="fa fa-close fechar-info-prod" gofullscreen="fundo-tela"></span>
       <table class="tabela-info-prod">
       
       </table>
    </div>