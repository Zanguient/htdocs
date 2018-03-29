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
            if($dif == $prd){
                $clc = 99.9;
            }else{
                $clc = 100;
            }
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

        return number_format($clc, 1, '.', '');
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
    
    foreach ($data_producao as $datas){
        $data_em_prod = $datas->DATA_PRODUCAO;
    }
    
    $d1 = date('Y/m/d',strtotime($data_em_prod));
    $d2 = date('Y/m/d');
    
    if($d1 == $d2){
        $flag = 0;
    }else{
        $flag = 1;
    }
    
    //retorno que deve ir para a view
    $dados = [];
    
    //descricao da coluna hora muda para mes de dara for anterior a data de producao
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
    
    $Indicador1_ = $this->TratarColunasIndicador($Indicadores[0]['DADOS'],2,$data_tempp,$data_temp); //media Produção Semana
    $Indicador2_ = $this->TratarColunasIndicador($Indicadores[1]['DADOS'],2,$data_tempp,$data_temp); //media Produção Mez
    $Indicador3_ = $this->TratarColunasIndicador($Indicadores[2]['DADOS'],2,$data_tempp,$data_temp); //media Defeito Dia, Noite

    //valor mensal, usabdo para consulta de datas anterioores ao da data de producao
    $mPR1 = $Indicador9['INDICADOR']['TURNO1']['VALOR'];          //Producao mensal turno 1
    $mPR2 = $Indicador9['INDICADOR']['TURNO2']['VALOR'];          //Producao mensal turno 2
    
    $mDi1 = $Indicador12['INDICADOR']['TURNO1']['VALOR'];         //Producao mensal turno 1
    $mDi2 = $Indicador12['INDICADOR']['TURNO2']['VALOR'];         //Producao mensal turno 2
    
    $mPe1 = $Indicador13['INDICADOR']['PTURNO1']['VALOR'];        //Producao mensal turno 1
    $mPe2 = $Indicador13['INDICADOR']['PTURNO2']['VALOR'];        //Producao mensal turno 2
    
    //dados da producao diaria
    $PR1 = $Indicador1['INDICADOR']['HDIA']['VALOR'];             //producao hora dia
    $PR2 = $Indicador1['INDICADOR']['PDIA']['VALOR'];             //producao geral dia
    $PR3 = $Indicador1['INDICADOR']['HNOITE']['VALOR'];           //producao hora noite
    $PR4 = $Indicador1['INDICADOR']['PNOITE']['VALOR'];           //producao geral noite
    $PR5 = $Indicador1['INDICADOR']['QUANTIDADE']['VALOR'];       //quantidade geral de producao turno 1 e 2
    
    if($flag == 1){
        $PR1 = $mPR1;
        $PR3 = $mPR2;
    }
    
    $efic_diaA = $Indicador1_['INDICADOR']['EFIC_A1']['VALOR'];    //meta A de eficiencia do dia 
    $efic_diaB = $Indicador1_['INDICADOR']['EFIC_B1']['VALOR'];    //meta B de eficiencia do dia  
    $efic_PDA  = $Indicador1_['INDICADOR']['PERDA_A1']['VALOR'];   //meta A de eficiencia de perda dia 
    $efic_PDB  = $Indicador1_['INDICADOR']['PERDA_B1']['VALOR'];   //meta B de eficiencia do perda dia 
    $efic_NoiA = $Indicador1_['INDICADOR']['EFIC_A2']['VALOR'];    //meta A de eficiencia do noite  
    $efic_NoiB = $Indicador1_['INDICADOR']['EFIC_B2']['VALOR'];    //meta B de eficiencia do noite  
    $efic_PNB  = $Indicador1_['INDICADOR']['PERDA_A2']['VALOR'];   //meta A de eficiencia do perda noite  
    $efic_PNA  = $Indicador1_['INDICADOR']['PERDA_B2']['VALOR'];   //meta B de eficiencia do perda noite  
    
    $PR_SEMANA = $Indicador2['INDICADOR']['QUANT']['VALOR'];       //producao da semana
    $PR_S_EFPA = $Indicador2_['INDICADOR']['EFIC_A']['VALOR'];     //meta A de efciencia de producao da semana
    $PR_S_EFPB = $Indicador2_['INDICADOR']['EFIC_B']['VALOR'];     //meta B de efciencia de producao da semana
    $PR_S_EFDA = $Indicador2_['INDICADOR']['PERDA_A']['VALOR'];    //meta A de efciencia de defeitos da semana
    $PR_S_EFDB = $Indicador2_['INDICADOR']['PERDA_B']['VALOR'];    //meta B de efciencia de defeitos da semana
    
    $PR_MES    = $Indicador3['INDICADOR']['QUANT']['VALOR'];       //producao da mes
    $PR_M_EFPA = $Indicador3_['INDICADOR']['EFIC_A']['VALOR'];     //meta A de efciencia de producao da mes
    $PR_M_EFPB = $Indicador3_['INDICADOR']['EFIC_B']['VALOR'];     //meta B de efciencia de producao da mes
    $PR_M_EFDA = $Indicador3_['INDICADOR']['PERDA_A']['VALOR'];    //meta A de efciencia de defeitos da mes
    $PR_M_EFDB = $Indicador3_['INDICADOR']['PERDA_B']['VALOR'];    //meta B de efciencia de defeitos da mes
    
    //descricao das faixas de eficiencia de producao do mes
    $faixa_verd_mes = 'ACIMA DE '.$PR_M_EFPB;
    $faixa_amar_mes = 'ENTRE '.$PR_M_EFPA.' e '.$PR_M_EFPB;
    $faixa_verm_mes = 'ABAIXO DE '.$PR_M_EFPA;
    
    //descricao das faixas de eficiencia de perdas do mes
    $faixaP_verd_mes = 'ABAIXO DE '.$PR_M_EFDA;
    $faixaP_amar_mes = 'ENTRE '.$PR_M_EFDA.' e '.$PR_M_EFDB;
    $faixaP_verm_mes = 'ACIMA DE '.$PR_M_EFDB;
    
    $PE1      = $Indicador4['INDICADOR']['HDIA']['VALOR'];       //perda hora dia
    $PE2      = $Indicador4['INDICADOR']['PDIA']['VALOR'];       //perda geral do dia
    $PE3      = $Indicador4['INDICADOR']['HNOITE']['VALOR'];     //perda hora noite
    $PE4      = $Indicador4['INDICADOR']['PNOITE']['VALOR'];     //perda geral noite
    $PE5      = $PE2 + $PE4;
    
    if($flag == 1){
        $PE1 = $mPe1;
        $PE3 = $mPe2;
    }

    $PE_SEMANA   = $Indicador5['INDICADOR']['PSEMANA']['VALOR'];  //perda da semana
    $PE_MES      = $Indicador5['INDICADOR']['PMES']['VALOR'];     //perda do mes
    
    $DI1   = $Indicador6['INDICADOR']['XDIA']['VALOR'];           //perda da semana
    $DI2   = $Indicador6['INDICADOR']['PDIA']['VALOR'];           //perda do mes
    $DI3   = $Indicador6['INDICADOR']['XNOITE']['VALOR'];         //perda da semana
    $DI4   = $Indicador6['INDICADOR']['PNOITE']['VALOR'];         //perda do mes
    
    if($flag == 1){
        $DI1 = $mDi1;
        $DI3 = $mDi2;
    }
    
    $HD    = $Indicador6['INDICADOR']['HDIA']['VALOR'];           //Meta de producao dia tuno 1
    $HN    = $Indicador6['INDICADOR']['HNOITE']['VALOR'];         //Meta de producao dia tuno 2
    
    $META_HORA = $HD + $HN;
    
    $META_DIA   = $Indicador7['INDICADOR']['PREVISAO_HORARIA']['VALOR'];//Previsao de producao da semana
    
    $DI_SEMANA  = $Indicador8['INDICADOR']['PSEMANA']['VALOR'];    //Meta de producao da semana
    $DI_MES     = $Indicador8['INDICADOR']['PMES']['VALOR'];       //Meta de producao do mes
    
    $DI1 =  $PR1 - $DI1;
    $DI2 =  $PR2 - $DI2;
    $DI3 =  $PR3 - $DI3;
    $DI4 =  $PR4 - $DI4;
    
    $DI5   = $DI2 + $DI4;
    
    $DI_SEMANA   = $PR_SEMANA - $DI_SEMANA;
    $DI_MES      = $PR_MES - $DI_MES;

    $FC1 = CalcEfic($DI1,$PR1);                     //calcula eficiencia hora dia
    $FC2 = CalcEfic($DI2,$PR2);                     //calcula eficiencia dia
    $FC3 = CalcEfic($DI3,$PR3);                     //calcula eficiencia hora noite
    $FC4 = CalcEfic($DI4,$PR4);                     //calcula eficiencia noite
    $FC5 = CalcEfic($DI5,$PR5);                     //calcula eficiencia geral dia e noite
    $FC_SEMANA = CalcEfic($DI_SEMANA,$PR_SEMANA);   //calcula eficiencia semana 
    $FC_MES    = CalcEfic($DI_MES,$PR_MES);         //calcula eficiencia mes
    
    $CorPr1 = calcCor($FC1,$efic_diaA,$efic_diaB);  //cor producao hora dia
    $CorPr2 = calcCor($FC2,$efic_diaA,$efic_diaB);  //cor producao dia
    $CorPr3 = calcCor($FC3,$efic_NoiA,$efic_NoiB);  //cor producao hora noite
    $CorPr4 = calcCor($FC4,$efic_NoiA,$efic_NoiB);  //cor producao noite
    $CorPr5 = calcCor($FC5,$efic_diaA,$efic_diaB);  //para o geral estou usando a a faixa de efic do dia
    $CorPrSemana = calcCor($FC_SEMANA,$PR_S_EFPB,$PR_S_EFPB);
    $CorPrMes    = calcCor($FC_MES,$PR_M_EFPB,$PR_M_EFPB);
    
    $FP1 = CalcPercPerdas($PR1,$PE1);
    $FP2 = CalcPercPerdas($PR2,$PE2);
    $FP3 = CalcPercPerdas($PR3,$PE3);
    $FP4 = CalcPercPerdas($PR4,$PE4);
    $FP5 = CalcPercPerdas($PR5,$PE5);
    $FP_SEMANA = CalcPercPerdas($PR_SEMANA,$PE_SEMANA);
    $FP_MES    = CalcPercPerdas($PR_MES,$PE_MES);
    
    $CorPe1 = calcCorp($FP1,$efic_PDB,$efic_PDA);  //cor PERDAS hora dia
    $CorPe2 = calcCorp($FP2,$efic_PDB,$efic_PDA);  //cor PERDAS dia
    $CorPe3 = calcCorp($FP3,$efic_PNB,$efic_PNA);  //cor PERDAS hora noite
    $CorPe4 = calcCorp($FP4,$efic_PNB,$efic_PNA);  //cor PERDAS noite
    $CorPe5 = calcCorp($FP5,$efic_PDB,$efic_PDA);  //para o geral estou usando a a faixa de efic do dia
    $CorPeSemana = calcCorp($FP_SEMANA,$PR_S_EFDB,$PR_S_EFDA);
    $CorPeMes    = calcCorp($FP_MES,$PR_M_EFDB,$PR_M_EFDA);
   
    //se as diferensas forem menores do que 0, devem ficar positivas
    if($DI1 < 0){$DI1 = $DI1 * -1;}
    if($DI2 < 0){$DI2 = $DI2 * -1;}
    if($DI3 < 0){$DI3 = $DI3 * -1;}
    if($DI4 < 0){$DI4 = $DI4 * -1;}
    if($DI5 < 0){$DI5 = $DI5 * -1;}
    if($DI_SEMANA < 0){$DI_SEMANA = $DI_SEMANA * -1;}
    if($DI_MES < 0){$DI_MES = $DI_MES * -1;}
    
    $EF = $Indicador10['INDICADOR']['CODIGO']['VALOR'];            //% de EFICIENCIA
    
    $VELOCIDADE = $Indicador11['INDICADOR']['TEMPO']['VALOR'];     //Velocidade da esteira
    $VELOCIDADE = 'T:'.$VELOCIDADE.' s/q';
    
    $data_indicador = date('d/m/Y H:i:s',strtotime($data_indicador));
    $data_prod      = date('d/m/Y',strtotime($data_prod));
    
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
        'VELOCIDADE' => $VELOCIDADE, 'EFICIENCIA'   => $EF,
        'DESCRICAO'  => $descricao,
        'DATA'       => 'Data:'.$data_prod.' tratada em '.$data_indicador
    ];
    
    return view('opex._25900.include.producao',['dados' => $dados]);        