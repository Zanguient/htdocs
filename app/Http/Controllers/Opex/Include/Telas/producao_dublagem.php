<?php
//arquivo que trata o bsc de teste

//variaveis que podem ser usadas e suas hierarquias
//$AreasConf
    //configurações da area do BSC (obj)
    //TELA - nome do arquivo que fara o tratamento do resultado da area
    //COMPONENTE_ID - id do compenente, o componente é um panel estra na tela (5s)
    //DESCRICAO - descricao que sera usada na tela

//$SetoresConf
    //dados dos setores selecionados (array(obj))
    //VALOR - GPID, CCusto, ou dados para serem usados no filtro do indicador

//$PerspectivaConf
    //Dados das perspectivas (array(obj))
    //INDICADOR_ID - id dos indicadores
    //ORDEM - ordem dos indicadores
    //AGRUPAMENTO_ID - id do grupo  do indicador

//$$dadosConsulta
    //Indicadores do bsc (array(obj))
    //ID - id do indicador
    //INDICADOR - array(obj) indicadores
        //DESCRICAO - descricao do indicador 
        //TIPO - tipo do indicador 1-se o indicador é quanto maior melhor 2-se o indicador for quanto menor melhor
        //DADOS - (array(obj)) - valores dos indicadores
        

//$Indicadores
    //indicadores e seus dados pre tratados
    //ID
    //INDICADOR 
        //ID
        //DESCRICAO
        //SQL_ID
        //TIPO
        //FAIXA_ID
        //ordem
    //DADOS
    //FAIXA
        //ID
        //FAIXA_ID
        //DESCRICAO
        //VALOR_MAXIMO
        //VALOR_MINIMO
        //DATA
        //DATA_TRATADO
        //COR_ID
        //ORDEM
        //SETOR_VALOR
        //AREA_ID
        //PERSPESCTIVA_ID
        //PESO
        //PESO_TOTAL

    //Calcula eficiencia de producao
    function CalcEfic($dif,$prd){

        $clc = 0;

        if($dif > 0){
            $clc = 100;
        }else{
            if($prd > 0){

               $dif = $dif * -1;
               $dif = $prd + $dif;
               $dif = $dif/100;

                if($dif > 0){
                    $clc = $prd/$dif;
                }else{
                    $clc = 0;
                }

            }else{
               $clc = 0;
            }
        }

        return number_format($clc, 1, '.', '');
    }
    
    //Calcula percentual de perdas
    function CalcPercPerdas($Prod,$Perd){

        $clc = 0;

        if($Prod > 0){
            $clc = ($Perd / $Prod) * 100; 
        }else{
            if($Perd > 0){
                $clc = 100;
            }else{
                $clc = 0;
            }
        }

        return number_format($clc, 2, '.', '');
    }
    
    //retorna a cor de um indicador 1-verde 2-amarelo 3-vermelho
    function calcCor($valor,$faixaA,$faixaB){
        $cor = 0;
        
        $valor  = tofloat($valor);
        $faixaA = tofloat($faixaA);
        $faixaB = tofloat($faixaB);
        
        if($valor == 0){
            $clc = 0;
        }else{
            if($valor >= $faixaA && $valor <= $faixaB){
                $cor = 2;
            }else{
                if($valor < $faixaA){
                    $cor = 3;
                }else{
                    if($valor > $faixaB){
                        $cor = 1;
                    }else{
                        $cor = 0;
                    }
                }
            }
        }
        
        return $cor;
        
    }
    
    //retorna a cor de um PERDA 1-verde 2-amarelo 3-vermelho
    function calcCorp($valor,$faixaA,$faixaB){
        $cor = 0;
        
        $valor  = tofloat($valor);
        $faixaA = tofloat($faixaA);
        $faixaB = tofloat($faixaB);
        
        if($valor == 0){
            $cor = 1;
        }else{
            if($valor >= $faixaA && $valor <= $faixaB){
                $cor = 2;
            }else{
                if($valor < $faixaA){
                    $cor = 1;
                }else{
                    if($valor > $faixaB){
                        $cor = 3;
                    }else{
                        $cor = 0;
                    }
                }
            }
        }

        return $cor;  
    }

    //variavel apara ver menor data de tratamento
    $data_indicador = '99.99.9999';
    $data_prod      = '99.99.9999';
    $data_temp      = '99.99.9999';
    $data_tempp     = '99.99.9999';
    
    $data_em_prod = '01.01.2001';
    
    $d1 = date('Y/m/d',strtotime($data_inicial));
    $d2 = date('Y/m/d');
    
    if($d1 == $d2){
        $flag = 0;
    }else{
        $flag = 1;
    }
    
    //retorno que deve ir para a view
    $dados = [];
    
    //descricao da coluna hora muda para mes se data é anterior a data de producao
    if($flag == 0){
        $desc_turno = 'HORA';
    }else{
        $desc_turno = 'MÊS';
    }

    $Indicador1  = $this->TratarColunasIndicador($Indicadores[ 0]['DADOS'],1,$data_prod,$data_indicador); //Produção dia, noite
    $Indicador2  = $this->TratarColunasIndicador($Indicadores[ 1]['DADOS'],1,$data_prod,$data_indicador); //Produção Semana
    $Indicador3  = $this->TratarColunasIndicador($Indicadores[ 2]['DADOS'],1,$data_prod,$data_indicador); //Produção Mez
    $Indicador4  = $this->TratarColunasIndicador($Indicadores[ 3]['DADOS'],1,$data_prod,$data_indicador); //Defeito Dia, Noite
    $Indicador5  = $this->TratarColunasIndicador($Indicadores[ 4]['DADOS'],1,$data_prod,$data_indicador); //Defeito Semana, Mês
    $Indicador6  = $this->TratarColunasIndicador($Indicadores[ 5]['DADOS'],1,$data_prod,$data_indicador); //Produção Horaria
    $Indicador7  = $this->TratarColunasIndicador($Indicadores[ 6]['DADOS'],1,$data_prod,$data_indicador); //Previsão Horaria
    $Indicador8  = $this->TratarColunasIndicador($Indicadores[ 7]['DADOS'],1,$data_prod,$data_indicador); //Meta Dia
    $Indicador9  = $this->TratarColunasIndicador($Indicadores[ 8]['DADOS'],1,$data_prod,$data_indicador); //Produção mensal Turno 1 e 2
    $Indicador10 = $this->TratarColunasIndicador($Indicadores[ 9]['DADOS'],1,$data_prod,$data_indicador); //% de EFICIENCIA
    $Indicador11 = $this->TratarColunasIndicador($Indicadores[10]['DADOS'],1,$data_prod,$data_indicador); //velocidade esteira
    $Indicador12 = $this->TratarColunasIndicador($Indicadores[11]['DADOS'],1,$data_prod,$data_indicador); //Diferenca mensal turno 1 e 2
    $Indicador13 = $this->TratarColunasIndicador($Indicadores[12]['DADOS'],1,$data_prod,$data_indicador); //Perdas mensal turno 1 e 2
    $Indicador14 = $this->TratarColunasIndicador($Indicadores[13]['DADOS'],1,$data_prod,$data_indicador); //Perdas mensal turno 1 e 2
    $Indicador15 = $this->TratarColunasIndicador($Indicadores[14]['DADOS'],1,$data_prod,$data_indicador); //Perdas mensal turno 1 e 2

    $programado_geral           = $Indicador13['INDICADOR']['PROGRAMADO']['VALOR'];
    $produzido_geral_t1         = $Indicador13['INDICADOR']['PRODUSIDO_T1']['VALOR'];
    $produzido_geral_t2         = $Indicador13['INDICADOR']['PRODUSIDO_T2']['VALOR'];

    $minutos_dia_t1             = $Indicador12['INDICADOR']['TEMPO_DIA_T1']['VALOR'];
    $minutos_hora_t1            = $Indicador12['INDICADOR']['TEMPO_HORA_T1']['VALOR'];
    $minutos_ultima_hora_t1     = $Indicador12['INDICADOR']['TEMPO_AGORA_T1']['VALOR'];

    $minutos_dia_t2             = $Indicador12['INDICADOR']['TEMPO_DIA_T2']['VALOR'];
    $minutos_hora_t2            = $Indicador12['INDICADOR']['TEMPO_HORA_T2']['VALOR'];
    $minutos_ultima_hora_t2     = $Indicador12['INDICADOR']['TEMPO_AGORA_T2']['VALOR'];

    $programado_mes             = $Indicador3['INDICADOR']['PROGRAMADO']['VALOR'];
    $produzido_mes_t1           = $Indicador3['INDICADOR']['PRODUSIDO_T1']['VALOR'];
    $produzido_mes_t2           = $Indicador3['INDICADOR']['PRODUSIDO_T2']['VALOR'];

    $programado_semana          = $Indicador2['INDICADOR']['PROGRAMADO']['VALOR'];
    $produzido_semana_t1        = $Indicador2['INDICADOR']['PRODUSIDO_T1']['VALOR'];
    $produzido_semana_t2        = $Indicador2['INDICADOR']['PRODUSIDO_T2']['VALOR'];

    $produzido_hora_t1          = $Indicador1['INDICADOR']['PRODUSIDO_T1']['VALOR'];
    $produzido_hora_t2          = $Indicador1['INDICADOR']['PRODUSIDO_T2']['VALOR'];

    $produzido_em_dia_semana_t1 = $Indicador4['INDICADOR']['PRODUSIDO_T1']['VALOR'];
    $produzido_em_dia_semana_t2 = $Indicador4['INDICADOR']['PRODUSIDO_T2']['VALOR'];

    $produzido_em_dia_mes_t1    = $Indicador5['INDICADOR']['PRODUSIDO_T1']['VALOR'];
    $produzido_em_dia_mes_t2    = $Indicador5['INDICADOR']['PRODUSIDO_T2']['VALOR'];

    $produzido_em_dia_dia_t1    = $Indicador6['INDICADOR']['PRODUSIDO_T1']['VALOR'];
    $produzido_em_dia_dia_t2    = $Indicador6['INDICADOR']['PRODUSIDO_T2']['VALOR'];

    $produzido_em_dia_hora_t1   = $Indicador7['INDICADOR']['PRODUSIDO_T1']['VALOR'];
    $produzido_em_dia_hora_t2   = $Indicador7['INDICADOR']['PRODUSIDO_T2']['VALOR'];

    $defeito_hora_t1            = $Indicador8['INDICADOR']['QUANTIDADE_HORA_T1']['VALOR'];
    $defeito_hora_t2            = $Indicador8['INDICADOR']['QUANTIDADE_HORA_T2']['VALOR'];
    $defeito_dia_t1             = $Indicador8['INDICADOR']['QUANTIDADE_DIA_T1']['VALOR'];
    $defeito_dia_t2             = $Indicador8['INDICADOR']['QUANTIDADE_DIA_T2']['VALOR'];
    $defeito_semana_t1          = $Indicador8['INDICADOR']['QUANTIDADE_SEMANA_T1']['VALOR'];
    $defeito_semana_t2          = $Indicador8['INDICADOR']['QUANTIDADE_SEMANA_T2']['VALOR'];
    $defeito_mes_t1             = $Indicador8['INDICADOR']['QUANTIDADE_MES_T1']['VALOR'];
    $defeito_mes_t2             = $Indicador8['INDICADOR']['QUANTIDADE_MES_T2']['VALOR'];

    $prod_bojo_dia_t1    = $Indicador14['INDICADOR']['PDIA']['VALOR'];
    $prod_bojo_dia_t2    = $Indicador14['INDICADOR']['PNOITE']['VALOR'];
    $prod_bojo_dia_r_t1  = $Indicador14['INDICADOR']['HDIA']['VALOR'];
    $prod_bojo_dia_r_t2  = $Indicador14['INDICADOR']['HNOITE']['VALOR'];

    $prod_bojo_mes_t1    = $Indicador11['INDICADOR']['QUANT_T1']['VALOR'];
    $prod_bojo_mes_t2    = $Indicador11['INDICADOR']['QUANT_T2']['VALOR'];

    $prod_bojo_semana_t1 = $Indicador10['INDICADOR']['QUANT_T1']['VALOR'];
    $prod_bojo_semana_t2 = $Indicador10['INDICADOR']['QUANT_T2']['VALOR'];

    $perdas_bojo_semana  = $Indicador9['INDICADOR']['PSEMANA']['VALOR'];
    $perdas_bojo_mes     = $Indicador9['INDICADOR']['PMES']['VALOR'];

    $prod_bojo_mes = $prod_bojo_mes_t1 + $prod_bojo_mes_t2;
    $prod_bojo_semana = $prod_bojo_semana_t1 + $prod_bojo_semana_t2;

    if($flag == 1){
        $prod_bojo_dia_r_t1 = $prod_bojo_mes_t1;
        $prod_bojo_dia_r_t2 = $prod_bojo_mes_t2;
    }

    $meta_minuto = 0;
    if(($minutos_dia_t1 + $minutos_dia_t2) > 0 ){
        $meta_minuto = ($programado_geral / ($minutos_dia_t1 + $minutos_dia_t2));
    }

    $meta_minuto_atual_t1 = $this->formatarN2($meta_minuto * $minutos_ultima_hora_t1,0);
    $meta_hora_atual_t1   = $this->formatarN2($meta_minuto * $minutos_hora_t1,0);

    $meta_minuto_atual_t2 = $this->formatarN2($meta_minuto * $minutos_ultima_hora_t2,0);
    $meta_hora_atual_t2   = $this->formatarN2($meta_minuto * $minutos_hora_t2,0);

    //valor mensal, usado para consulta de datas anterioores a data de producao
    $mPR1 = $produzido_mes_t1;          //Producao mensal turno 1
    $mPR2 = $produzido_mes_t2;          //Producao mensal turno 2

    $a= 0;
    if(($minutos_dia_t1 + $minutos_dia_t2) > 0 ){
        $a = ($programado_mes / ($minutos_dia_t1 + $minutos_dia_t2));
    }
    
    $mDi1 = $this->formatarN2($produzido_em_dia_mes_t1 - ($a * $minutos_dia_t1),0);         //Producao mensal turno 1
    $mDi2 = $this->formatarN2($produzido_em_dia_mes_t2 - ($a * $minutos_dia_t2),0);          //Producao mensal turno 2
    
    $mPe1 = $defeito_mes_t1;        //Producao mensal turno 1
    $mPe2 = $defeito_mes_t2;        //Producao mensal turno 2

    $mFc1 = CalcEfic($mDi1,$produzido_em_dia_mes_t1);
    $mFc2 = CalcEfic($mDi2,$produzido_em_dia_mes_t2);
    
    //dados da producao diaria
    $PR1 = $produzido_hora_t1 ;                                    //producao hora dia
    $PR2 = $produzido_geral_t1;                                    //producao geral dia
    $PR3 = $produzido_hora_t2;                                     //producao hora noite
    $PR4 = $produzido_geral_t2;                                    //producao geral noite
    $PR5 = $produzido_geral_t1 + $produzido_geral_t2;              //quantidade geral de producao turno 1 e 2
    
    if($flag == 1){
        $PR1 = $mPR1;
        $PR3 = $mPR2;
    }

    $EFIC_A_T1   = $this->formatarN($Indicador15['INDICADOR']['EFIC_A_T1']['VALOR'] ,2);
    $EFIC_B_T1   = $this->formatarN($Indicador15['INDICADOR']['EFIC_B_T1']['VALOR'] ,2);
    $PERDA_A_T1  = $this->formatarN($Indicador15['INDICADOR']['PERDA_A_T1']['VALOR'],2);
    $PERDA_B_T1  = $this->formatarN($Indicador15['INDICADOR']['PERDA_B_T1']['VALOR'],2);
    $EFIC_A_T2   = $this->formatarN($Indicador15['INDICADOR']['EFIC_A_T2']['VALOR'] ,2);
    $EFIC_B_T2   = $this->formatarN($Indicador15['INDICADOR']['EFIC_B_T2']['VALOR'] ,2);
    $PERDA_A_T2  = $this->formatarN($Indicador15['INDICADOR']['PERDA_A_T2']['VALOR'],2);
    $PERDA_B_T2  = $this->formatarN($Indicador15['INDICADOR']['PERDA_B_T2']['VALOR'],2);
    
    $efic_diaA = $EFIC_A_T1;    //meta A de eficiencia do dia 
    $efic_diaB = $EFIC_B_T1;    //meta B de eficiencia do dia  
    $efic_PDA  = $PERDA_A_T1;   //meta A de eficiencia de perda dia 
    $efic_PDB  = $PERDA_B_T1;   //meta B de eficiencia do perda dia 
    $efic_NoiA = $EFIC_A_T2;    //meta A de eficiencia do noite  
    $efic_NoiB = $EFIC_B_T2;    //meta B de eficiencia do noite  
    $efic_PNB  = $PERDA_A_T2;   //meta A de eficiencia do perda noite  
    $efic_PNA  = $PERDA_B_T2;   //meta B de eficiencia do perda noite  
    
    $PR_SEMANA = $produzido_semana_t1 + $produzido_semana_t2;        //producao da semana
    $PR_S_EFPA = ($EFIC_A_T1  + $EFIC_A_T2 )/2;     //meta A de efciencia de producao da semana
    $PR_S_EFPB = ($EFIC_B_T1  + $EFIC_B_T2 )/2;     //meta B de efciencia de producao da semana
    $PR_S_EFDA = ($PERDA_A_T1 + $PERDA_A_T2)/2;    //meta A de efciencia de defeitos da semana
    $PR_S_EFDB = ($PERDA_B_T1 + $PERDA_B_T2)/2;    //meta B de efciencia de defeitos da semana
    
    $PR_MES    = $produzido_mes_t1 + $produzido_mes_t2;     //producao da mes
    $PR_M_EFPA = ($EFIC_A_T1  + $EFIC_A_T2 )/2;     //meta A de efciencia de producao da mes
    $PR_M_EFPB = ($EFIC_B_T1  + $EFIC_B_T2 )/2;     //meta B de efciencia de producao da mes
    $PR_M_EFDA = ($PERDA_A_T1 + $PERDA_A_T2)/2;    //meta A de efciencia de defeitos da mes
    $PR_M_EFDB = ($PERDA_B_T1 + $PERDA_B_T2)/2;   //meta B de efciencia de defeitos da mes
    
    //descricao das faixas de eficiencia de producao do mes
    $faixa_verd_mes = 'ACIMA DE '.$PR_M_EFPB;
    $faixa_amar_mes = 'ENTRE '.$PR_M_EFPA.' e '.$PR_M_EFPB;
    $faixa_verm_mes = 'ABAIXO DE '.$PR_M_EFPA;
    
    //descricao das faixas de eficiencia de perdas do mes
    $faixaP_verd_mes = 'ABAIXO DE '.$PR_M_EFDA;
    $faixaP_amar_mes = 'ENTRE '.$PR_M_EFDA.' e '.$PR_M_EFDB;
    $faixaP_verm_mes = 'ACIMA DE '.$PR_M_EFDB;

    $PE1      = $defeito_hora_t1;       //perda hora dia
    $PE2      = $defeito_dia_t1;       //perda geral do dia
    $PE3      = $defeito_hora_t2;     //perda hora noite
    $PE4      = $defeito_dia_t2;     //perda geral noite
    $PE5      = $PE2 + $PE4;
    
    if($flag == 1){
        $PE1 = $mPe1;
        $PE3 = $mPe2;
    }

    $PE_SEMANA   = $defeito_semana_t1 + $defeito_semana_t2;  //perda da semana
    $PE_MES      = $defeito_mes_t1 + $defeito_mes_t2;     //perda do mes
    
    $DI1   = $produzido_em_dia_hora_t1 - $meta_minuto_atual_t1;      //perda da semana
    $DI2   = $produzido_em_dia_dia_t1 - $meta_hora_atual_t1;        //perda do mes
    $DI3   = $produzido_em_dia_hora_t2 - $meta_minuto_atual_t2;      //perda da semana
    $DI4   = $produzido_em_dia_dia_t2 - $meta_hora_atual_t2;        //perda do mes
    
    if($flag == 1){
        $DI1 = $mDi1;
        $DI3 = $mDi2;
    }
    
    $META_HORA  = ($meta_minuto_atual_t1 + $meta_minuto_atual_t2);
    
    $META_DIA   = $programado_geral;//Previsao de producao do dia
    
    $DI5   = $DI2 + $DI4;
    
    $DI_SEMANA   = ($produzido_em_dia_semana_t1 + $produzido_em_dia_semana_t2) - $programado_semana;
    $DI_MES      = ($produzido_em_dia_mes_t1 + $produzido_em_dia_mes_t2) - $programado_mes;

    $FC1 = CalcEfic($DI1,$produzido_em_dia_hora_t1);                     //calcula eficiencia hora dia
    $FC2 = CalcEfic($DI2,$produzido_em_dia_dia_t1);                     //calcula eficiencia dia
    $FC3 = CalcEfic($DI3,$produzido_em_dia_hora_t2);                     //calcula eficiencia hora noite
    $FC4 = CalcEfic($DI4,$produzido_em_dia_dia_t2);                     //calcula eficiencia noite
    $FC5 = CalcEfic($DI5,($produzido_em_dia_dia_t1 + $produzido_em_dia_dia_t2));                     //calcula eficiencia geral dia e noite
    $FC_SEMANA = CalcEfic($DI_SEMANA,($produzido_em_dia_semana_t1 + $produzido_em_dia_semana_t2));   //calcula eficiencia semana 
    $FC_MES    = CalcEfic($DI_MES,($produzido_em_dia_mes_t1 + $produzido_em_dia_mes_t2));         //calcula eficiencia mes

    if($flag == 1){
        $FC1 = $mFc1;
        $FC3 = $mFc2;
    }
    
    $CorPr1 = calcCor($FC1,$efic_diaA,$efic_diaB);  //cor producao hora dia
    $CorPr2 = calcCor($FC2,$efic_diaA,$efic_diaB);  //cor producao dia
    $CorPr3 = calcCor($FC3,$efic_NoiA,$efic_NoiB);  //cor producao hora noite
    $CorPr4 = calcCor($FC4,$efic_NoiA,$efic_NoiB);  //cor producao noite
    $CorPr5 = calcCor($FC5,$efic_diaA,$efic_diaB);  //para o geral estou usando a a faixa de efic do dia
    $CorPrSemana = calcCor($FC_SEMANA,$PR_S_EFPB,$PR_S_EFPB);
    $CorPrMes    = calcCor($FC_MES,$PR_M_EFPB,$PR_M_EFPB);
    
    $FP1 = CalcPercPerdas($prod_bojo_dia_r_t1,$PE1);
    $FP2 = CalcPercPerdas($prod_bojo_dia_t1,$PE2);
    $FP3 = CalcPercPerdas($prod_bojo_dia_r_t2,$PE3);
    $FP4 = CalcPercPerdas($prod_bojo_dia_t2,$PE4);
    $FP5 = CalcPercPerdas($prod_bojo_dia_t1 + $prod_bojo_dia_t2,$PE5);
    $FP_SEMANA = CalcPercPerdas($prod_bojo_semana,$PE_SEMANA);
    $FP_MES    = CalcPercPerdas($prod_bojo_mes,$PE_MES);
    
    $CorPe1 = calcCorp($FP1,$efic_PDA,$efic_PDB);  //cor PERDAS hora dia
    $CorPe2 = calcCorp($FP2,$efic_PDA,$efic_PDB);  //cor PERDAS dia
    $CorPe3 = calcCorp($FP3,$efic_PNA,$efic_PNB);  //cor PERDAS hora noite
    $CorPe4 = calcCorp($FP4,$efic_PNA,$efic_PNB);  //cor PERDAS noite
    $CorPe5 = calcCorp($FP5,$efic_PDA,$efic_PDB);  //para o geral estou usando a a faixa de efic do dia
    $CorPeSemana = calcCorp($FP_SEMANA,$PR_S_EFDA,$PR_S_EFDB);
    $CorPeMes    = calcCorp($FP_MES,$PR_M_EFDA,$PR_M_EFDB);
   
    //se as diferensas forem menores do que 0, devem ficar positivas
    if($DI1 < 0){$DI1 = $DI1 * -1;}
    if($DI2 < 0){$DI2 = $DI2 * -1;}
    if($DI3 < 0){$DI3 = $DI3 * -1;}
    if($DI4 < 0){$DI4 = $DI4 * -1;}
    if($DI5 < 0){$DI5 = $DI5 * -1;}
    if($DI_SEMANA < 0){$DI_SEMANA = $DI_SEMANA * -1;}
    if($DI_MES < 0){$DI_MES = $DI_MES * -1;}
    
    $EF = 90;            //% de EFICIENCIA
    
    $data_indicador = date('d/m/Y H:i:s',strtotime($data_indicador));
    $data_prod      = date('d/m/Y',strtotime($data_prod));
    
    if($flag == 1){
        $META_HORA = 0;
    }

    $pperdas = CalcPercPerdas($prod_bojo_mes,$perdas_bojo_mes);

    $dados = [
        'PR1'       => $PR1,        'CorPr1'       => $CorPr1,
        'PR2'       => $PR2,        'CorPr2'       => $CorPr2,
        'PR3'       => $PR3,        'CorPr3'       => $CorPr3, 
        'PR4'       => $PR4,        'CorPr4'       => $CorPr4,
        'PR5'       => $PR5,        'CorPr5'       => $CorPr5,
        'PR_SEMANA' => $PR_SEMANA,  'CorPrSemana'  => $CorPrSemana,
        'PR_MES'    => $PR_MES,     'CorPrMes'     => $CorPrMes,
        'PE1'       => $PE1,        'CorPe1'       => $CorPe1,
        'PE2'       => $PE2,        'CorPe2'       => $CorPe2,
        'PE3'       => $PE3,        'CorPe3'       => $CorPe3,
        'PE4'       => $PE4,        'CorPe4'       => $CorPe4,
        'PE5'       => $PE5,        'CorPe5'       => $CorPe5,
        'PE_SEMANA' => $PE_SEMANA,  'CorPeSemana'  => $CorPeSemana,
        'PE_MES'    => $PE_MES,     'CorPeMes'     => $CorPeMes,
        'DI1'       => $DI1,
        'DI2'       => $DI2,
        'DI3'       => $DI3,
        'DI4'       => $DI4,
        'DI5'       => $DI5,
        'DI_SEMANA' => $DI_SEMANA,
        'DI_MES'    => $DI_MES,
        'FC1'       => $FC1,
        'FC2'       => $FC2,
        'FC3'       => $FC3,
        'FC4'       => $FC4,
        'FC5'       => $FC5,
        'FC_SEMANA' => $FC_SEMANA,
        'FC_MES'    => $FC_MES,
        'FP1'       => $FP1,
        'FP2'       => $FP2,
        'FP3'       => $FP3,
        'FP4'       => $FP4,
        'FP5'       => $FP5,
        'FP_SEMANA' => $FP_SEMANA,
        'FP_MES'    => $FP_MES,

        'DESC_TURNO'=> $desc_turno,
        
        'FAIXAMES1'  => $faixa_verd_mes,
        'FAIXAMES2'  => $faixa_amar_mes,
        'FAIXAMES3'  => $faixa_verm_mes,
        
        'FAIXAMESP1' => $faixaP_verd_mes,
        'FAIXAMESP2' => $faixaP_amar_mes,
        'FAIXAMESP3' => $faixaP_verm_mes,
        
        'META_DIA'   => $META_DIA,   'META_HORA'    => $META_HORA,
        'VELOCIDADE' => 'Per. Bojo: '.$pperdas.'%', 'EFICIENCIA'   => $EF,
        'DESCRICAO'  => $descricao,
        'DATA'       => 'Data:'.$data_prod.' tratada em '.$data_indicador
    ];
    
    return view('opex._25900.include.producao_dublagem',['dados' => $dados]);        