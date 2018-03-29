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

    //variavel apara ver menor data de tratamento
    $data_indicador = '99.99.9999';
    $data_prod      = '99.99.9999';
    $data_temp      = '99.99.9999';
    $data_tempp     = '99.99.9999';
    
    $INDICADOR_ARRAY = [];
    
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
    
    function calcPerc($base,$valor,$casas){
        $vals = 0;
        
        if($valor > 0){
            if($base > 0){
                $vals = (($valor / $base) * 100);
            }else{
                $vals = 100;
            }
        } 
        
        $vals = number_format($vals, $casas, ',', '');
        
        if($vals > 1000){
            $vals = 1000;
        }
        return $vals;
    }
    
    //retorno que deve ir para a view
    $dados = [];
    
    //descricao da coluna hora muda para mes se dara for anterior a data de producao
    if($flag == 0){
        $desc_turno = 'HORA';
    }else{
        $desc_turno = 'MÊS';
    }
    
    $Contador = 0;
    $SOMA_DESC      = '';
    $SOMA_DEF1      = '';
    $SOMA_DEF2      = '';
    $SOMA_DEF3      = '';
    $SOMA_PESO      = '';
    $SOMA_COR1      = '';
    $SOMA_COR2      = '';
    $SOMA_COR3      = '';
    $SOMA_VALOR1    = '';
    $SOMA_VALOR2    = '';
    $SOMA_VALOR3    = '';
    $SOMA_P_NOTS    = '';
    $SOMA_P_NOTM    = '';
    $SOMA_P_NOTT    = '';
    
    $total_peso_semana   = 0;
    $total_peso_mes      = 0;
    $total_peso_semestre = 0;
    
    $total_obtidoSA = 0;
    $total_obtidoME = 0;
    $total_obtidoSE = 0;
    
    $dados1 = [];
    $dados_absenteismo = [];
    
    foreach ($Indicadores as $Item){
        $Contador++;
        
        $Indicador  = $this->TratarColunasIndicador($Item['DADOS'],1,$data_prod,$data_indicador);
        
        $cont  = 0;
        $contT = count($Item['FAIXA']);
        $DEF1  = ''; $DEF2 = ''; $DEF3 = '';
        $peso = '';

        foreach ($Item['FAIXA']  as $faixa){
            $cont++;
            
            if($cont == 1){ $DEF1 = $faixa->DESCRICAO; }else{
                if($cont == 2){
                    if($contT == $cont){ $DEF3 = $faixa->DESCRICAO; }else{ $DEF2 = $faixa->DESCRICAO;}
                }else{ $DEF3 = $faixa->DESCRICAO; }
            }
            
            $peso = number_format( $faixa->PESO_TOTAL, 0, '.', ',');
        }

                            
        //if($Item['INDICADOR']->ORDEM == 1) {

            $dados1 = $Indicador;

            $MES1   = $this->formatarN2($Indicador['INDICADOR']['MES1']['VALOR'], 0);
            $MES2   = $this->formatarN2($Indicador['INDICADOR']['MES2']['VALOR'], 0);
            $MES3   = $this->formatarN2($Indicador['INDICADOR']['MES3']['VALOR'], 0);
            $MES4   = $this->formatarN2($Indicador['INDICADOR']['MES4']['VALOR'], 0);
            $MES5   = $this->formatarN2($Indicador['INDICADOR']['MES5']['VALOR'], 0);
            $MES6   = $this->formatarN2($Indicador['INDICADOR']['MES6']['VALOR'], 0);

            $VALOR1 = $this->formatarN2($Indicador['INDICADOR']['VALOR1']['VALOR'],2);
            $VALOR2 = $this->formatarN2($Indicador['INDICADOR']['VALOR2']['VALOR'],3);
            $VALOR3 = $this->formatarN2($Indicador['INDICADOR']['VALOR3']['VALOR'],3);
            $VALOR4 = $this->formatarN2($Indicador['INDICADOR']['VALOR4']['VALOR'],3);
            $VALOR5 = $this->formatarN2($Indicador['INDICADOR']['VALOR5']['VALOR'],3);
            $VALOR6 = $this->formatarN2($Indicador['INDICADOR']['VALOR6']['VALOR'],3);
 
            $METAM1_A = 0;
            $METAM2_A = 0;
            $METAM3_A = 0;
            $METAM4_A = 0;
            $METAM5_A = 0;
            $METAM6_A = 0;

            $METAM1_B = 0;
            $METAM2_B = 0;
            $METAM3_B = 0;
            $METAM4_B = 0;
            $METAM5_B = 0;
            $METAM6_B = 0;

            $COR1 = 1;
            $COR2 = 1;
            $COR3 = 1;
            $COR4 = 1;
            $COR5 = 1;
            $COR6 = 1;

            $MES1   = $this->iif($MES1 == 0 ,1, $MES1);
            $MES2   = $this->iif($MES2 == 0 ,2, $MES2);
            $MES3   = $this->iif($MES3 == 0 ,3, $MES3);
            $MES4   = $this->iif($MES4 == 0 ,4, $MES4);
            $MES5   = $this->iif($MES5 == 0 ,5, $MES5);
            $MES6   = $this->iif($MES6 == 0 ,6, $MES6);

            $MES1   = $this->iif($MES1 == 1 ,'JAN', 'JUL');
            $MES2   = $this->iif($MES2 == 2 ,'FEV', 'AGO');
            $MES3   = $this->iif($MES3 == 3 ,'MAR', 'SET');
            $MES4   = $this->iif($MES4 == 4 ,'ABR', 'OUT');
            $MES5   = $this->iif($MES5 == 5 ,'MAI', 'NOV');
            $MES6   = $this->iif($MES6 == 6 ,'JUN', 'DEZ');

            $INDICADOR = [
                'DESC'      => $Item['INDICADOR']->DESCRICAO,
                'DEF1'      => $METAM1_A,
                'DEF2'      => $METAM1_A,
                'DEF3'      => $METAM1_A,
                'PESO'      => $peso,
                'COR1'      => $COR1,
                'COR2'      => $COR2,
                'COR3'      => $COR3,
                'COR4'      => $COR4,
                'COR5'      => $COR5,
                'COR6'      => $COR6,
                'VALOR1'    => $this->formatarN2($VALOR1,2),
                'VALOR2'    => $this->formatarN2($VALOR2,2),
                'VALOR3'    => $this->formatarN2($VALOR3,2),
                'VALOR4'    => $this->formatarN2($VALOR4,2),
                'VALOR5'    => $this->formatarN2($VALOR5,2),
                'VALOR6'    => $this->formatarN2($VALOR6,2),
                'P_NOTS'    => $METAM1_B,
                'P_NOTM'    => $METAM1_B,
                'P_NOTT'    => $METAM1_B,
                'ORDEM'     => $Item['INDICADOR']->ORDEM,
                'PFAIXS'    => $METAM1_B,
                'PFAIXM'    => $METAM1_B,
                'PFAIXT'    => $METAM1_B
            ];

            array_push($INDICADOR_ARRAY,$INDICADOR);
   
    }

    $linhas = count($INDICADOR_ARRAY);
    $linhas_perc = 90 / $linhas;
    $linhas_g = 90 / $linhas;

    $grupos_indicador = [];

    foreach($agrupamentos as $grupos){
        $grupos_indi = [
            'DESCRICAO' => $grupos->DESCRICAO,
            'LINHAS'    => ($grupos->LINHAS * $linhas_g),
            'AJUSTE'    => ($grupos->LINHAS * 2) - 2,
            'COR'       => $grupos->CODIGO 
        ];
        
        array_push($grupos_indicador, $grupos_indi);
    }

    $dados = [
        'INDICADORES'   => $INDICADOR_ARRAY,
        'AGRUPAMENTOS'  => $grupos_indicador,
        'DESCRICAO'     => $AreasConf->DESCRICAO.' ('.$descricao.')',
        'DATA'          => 'Data:'.$data_prod.' tratada em '.$data_indicador,
        'PERC_LINHAS'   => $linhas_perc,
        'DADOS_COMP'    => $dados_componente,
        'MES1'          => $MES1,
        'MES2'          => $MES2,
        'MES3'          => $MES3,
        'MES4'          => $MES4,
        'MES5'          => $MES5,
        'MES6'          => $MES6
    ];
    
    
    return view('opex._25900.include.painel_bsc_comercial',['dados' => $dados]);        