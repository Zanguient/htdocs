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
        
        $COR1       = 0; //cor da semana
        $COR2       = 0; //cor do mes
        $COR3       = 0; //cor do semestre
        $VALOR1     = 0; //valor da semana
        $VALOR2     = 0; //valor do mes
        $VALOR3     = 0; //valor do semestre
        $P_NOTS     = 0; //peso obtidos semana
        $P_NOTM     = 0; //peso obtido mes
        $P_NOTT     = 0; //peso obtido semestre
        $PFAIXS     = 0; //peso da faixa da semana
        $PFAIXM     = 0; //peso da faixa da mes
        $PFAIXT     = 0; //peso da faixa da semestre
        $TABELAS    = []; //tabela descritiva semana
        $TABELAM    = []; //tabela descritiva semana
        $TABELAT    = []; //tabela descritiva semana

        $TABELAS    = [
            [
                'DESCRICAO' => 'EXEMPLO DESC',
                'CONTEUDOS' => 'EXEMPLO CONT',
            ],
            [
                'DESCRICAO' => 'EXEMPLO DESC',
                'CONTEUDOS' => 'EXEMPLO CONT',
            ]
        ];

        $TABELAM    = [
            [
                'DESCRICAO' => 'EXEMPLO DESC',
                'CONTEUDOS' => 'EXEMPLO CONT',
            ],
            [
                'DESCRICAO' => 'EXEMPLO DESC',
                'CONTEUDOS' => 'EXEMPLO CONT',
            ]
        ];

        $TABELAT    = [
            [
                'DESCRICAO' => 'EXEMPLO DESC',
                'CONTEUDOS' => 'EXEMPLO CONT',
            ],
            [
                'DESCRICAO' => 'EXEMPLO DESC',
                'CONTEUDOS' => 'EXEMPLO CONT',
            ]
        ];

                            
        if($Item['INDICADOR']->ORDEM == 1) {

            $dados1 = $Indicador;

            $VALOR1 = $this->formatarN2($Indicador['INDICADOR']['PRODUZIDO_SEMANA']['VALOR']  ,0);
            $VALOR2 = $this->formatarN2($Indicador['INDICADOR']['PRODUZIDO_MES']['VALOR']     ,0);
            $VALOR3 = $this->formatarN2($Indicador['INDICADOR']['PRODUZIDO_SEMESTRE']['VALOR'],0); 

            $CARGA_SEMANA           = $this->formatarN($Indicador['INDICADOR']['CARGA_SEMANA']['VALOR']          , 2);
            $PROGRAMADO_SEMANA      = $this->formatarN($Indicador['INDICADOR']['PROGRAMADO_SEMANA']['VALOR']     , 2);
            $UTILIZADO_SEMANA       = $this->formatarN($Indicador['INDICADOR']['UTILIZADO_SEMANA']['VALOR']      , 2);
            $QTD_UTILIZADO_SEMANA   = $this->formatarN($Indicador['INDICADOR']['QTD_UTILIZADO_SEMANA']['VALOR']  , 2);
            $PRODUZIDO_SEMANA       = $this->formatarN($Indicador['INDICADOR']['PRODUZIDO_SEMANA']['VALOR']      , 2);
            
            $CARGA_MES              = $this->formatarN($Indicador['INDICADOR']['CARGA_MES']['VALOR']          , 2);
            $PROGRAMADO_MES         = $this->formatarN($Indicador['INDICADOR']['PROGRAMADO_MES']['VALOR']     , 2);
            $UTILIZADO_MES          = $this->formatarN($Indicador['INDICADOR']['UTILIZADO_MES']['VALOR']      , 2);
            $QTD_UTILIZADO_MES      = $this->formatarN($Indicador['INDICADOR']['QTD_UTILIZADO_MES']['VALOR']  , 2);
            $PRODUZIDO_MES          = $this->formatarN($Indicador['INDICADOR']['PRODUZIDO_MES']['VALOR']      , 2);
            
            $CARGA_SEMESTRE         = $this->formatarN($Indicador['INDICADOR']['CARGA_SEMESTRE']['VALOR']          , 2);
            $PROGRAMADO_SEMESTRE    = $this->formatarN($Indicador['INDICADOR']['PROGRAMADO_SEMESTRE']['VALOR']     , 2);
            $UTILIZADO_SEMESTRE     = $this->formatarN($Indicador['INDICADOR']['UTILIZADO_SEMESTRE']['VALOR']      , 2);
            $QTD_UTILIZADO_SEMESTRE = $this->formatarN($Indicador['INDICADOR']['QTD_UTILIZADO_SEMESTRE']['VALOR']  , 2);
            $PRODUZIDO_SEMESTRE     = $this->formatarN($Indicador['INDICADOR']['PRODUZIDO_SEMESTRE']['VALOR']      , 2);

            $EFIC_SA = calcPerc($QTD_UTILIZADO_SEMANA,$UTILIZADO_SEMANA,2);
            $EFIC_ME = calcPerc($QTD_UTILIZADO_MES,$UTILIZADO_MES,2);
            $EFIC_SE = calcPerc($QTD_UTILIZADO_SEMESTRE,$UTILIZADO_SEMESTRE,2);

            $EFIC_SA1= calcPerc($PROGRAMADO_SEMANA,$CARGA_SEMANA,2);
            $EFIC_ME1= calcPerc($PROGRAMADO_MES,$CARGA_MES,2);
            $EFIC_SE1= calcPerc($PROGRAMADO_SEMESTRE,$CARGA_SEMESTRE,2);

            $EFIC_SA = calcPerc($EFIC_SA,$EFIC_SA1,2);
            $EFIC_ME = calcPerc($EFIC_ME,$EFIC_ME1,2);
            $EFIC_SE = calcPerc($EFIC_SE,$EFIC_SE1,2);
            
            $this->getCor($Item['FAIXA'],$EFIC_SA,$COR1,$P_NOTS,1,$PFAIXS);
            $this->getCor($Item['FAIXA'],$EFIC_ME,$COR2,$P_NOTM,1,$PFAIXM);
            $this->getCor($Item['FAIXA'],$EFIC_SE,$COR3,$P_NOTT,1,$PFAIXT);
            
            $total_obtidoSA = $total_obtidoSA + $P_NOTS;
            $total_obtidoME = $total_obtidoME + $P_NOTM;
            $total_obtidoSE = $total_obtidoSE + $P_NOTT;

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ],
                [
                    'DESCRICAO' => 'Produzido',
                    'CONTEUDOS' => $VALOR1,
                ],
                [
                    'DESCRICAO' => 'Programado',
                    'CONTEUDOS' => $this->formatarN($PROGRAMADO_SEMANA,0)
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ],
                [
                    'DESCRICAO' => 'Produzido',
                    'CONTEUDOS' => $VALOR2,
                ],
                [
                    'DESCRICAO' => 'Programado',
                    'CONTEUDOS' => $this->formatarN($PROGRAMADO_MES,0)
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ],
                [
                    'DESCRICAO' => 'Produzido',
                    'CONTEUDOS' => $VALOR3,
                ],
                [
                    'DESCRICAO' => 'Programado',
                    'CONTEUDOS' => $this->formatarN($PROGRAMADO_SEMESTRE,0)
                ]
            ];
   
        }
        
        if($Item['INDICADOR']->ORDEM == 2) {
            $Indicador  = $dados1;

            $COR1       = $INDICADOR_ARRAY[0]['COR1'];
            $COR2       = $INDICADOR_ARRAY[0]['COR2'];
            $COR3       = $INDICADOR_ARRAY[0]['COR3'];
            
            $CARGA_SEMANA           = $this->formatarN2($Indicador['INDICADOR']['CARGA_SEMANA']['VALOR']            , 2);
            $PROGRAMADO_SEMANA      = $this->formatarN2($Indicador['INDICADOR']['PROGRAMADO_SEMANA']['VALOR']       , 2);
            $UTILIZADO_SEMANA       = $this->formatarN2($Indicador['INDICADOR']['UTILIZADO_SEMANA']['VALOR']        , 2);
            $QTD_UTILIZADO_SEMANA   = $this->formatarN2($Indicador['INDICADOR']['QTD_UTILIZADO_SEMANA']['VALOR']    , 2);
            $PRODUZIDO_SEMANA       = $this->formatarN2($Indicador['INDICADOR']['PRODUZIDO_SEMANA']['VALOR']        , 2);
                
            $CARGA_MES              = $this->formatarN2($Indicador['INDICADOR']['CARGA_MES']['VALOR']               , 2);
            $PROGRAMADO_MES         = $this->formatarN2($Indicador['INDICADOR']['PROGRAMADO_MES']['VALOR']          , 2);
            $UTILIZADO_MES          = $this->formatarN2($Indicador['INDICADOR']['UTILIZADO_MES']['VALOR']           , 2);
            $QTD_UTILIZADO_MES      = $this->formatarN2($Indicador['INDICADOR']['QTD_UTILIZADO_MES']['VALOR']       , 2);
            $PRODUZIDO_MES          = $this->formatarN2($Indicador['INDICADOR']['PRODUZIDO_MES']['VALOR']           , 2);
            
            $CARGA_SEMESTRE         = $this->formatarN2($Indicador['INDICADOR']['CARGA_SEMESTRE']['VALOR']          , 2);
            $PROGRAMADO_SEMESTRE    = $this->formatarN2($Indicador['INDICADOR']['PROGRAMADO_SEMESTRE']['VALOR']     , 2);
            $UTILIZADO_SEMESTRE     = $this->formatarN2($Indicador['INDICADOR']['UTILIZADO_SEMESTRE']['VALOR']      , 2);
            $QTD_UTILIZADO_SEMESTRE = $this->formatarN2($Indicador['INDICADOR']['QTD_UTILIZADO_SEMESTRE']['VALOR']  , 2);
            $PRODUZIDO_SEMESTRE     = $this->formatarN2($Indicador['INDICADOR']['PRODUZIDO_SEMESTRE']['VALOR']      , 2);
            
            $EFIC_SA = calcPerc($QTD_UTILIZADO_SEMANA,$UTILIZADO_SEMANA,2);
            $EFIC_ME = calcPerc($QTD_UTILIZADO_MES,$UTILIZADO_MES,2);
            $EFIC_SE = calcPerc($QTD_UTILIZADO_SEMESTRE,$UTILIZADO_SEMESTRE,2);

            $EFIC_SA1= calcPerc($PROGRAMADO_SEMANA,$CARGA_SEMANA,2);
            $EFIC_ME1= calcPerc($PROGRAMADO_MES,$CARGA_MES,2);
            $EFIC_SE1= calcPerc($PROGRAMADO_SEMESTRE,$CARGA_SEMESTRE,2,2);

            $VALOR1 = calcPerc($EFIC_SA,$EFIC_SA1,2);
            $VALOR2 = calcPerc($EFIC_ME,$EFIC_ME1,2);
            $VALOR3 = calcPerc($EFIC_SE,$EFIC_SE1,2);
            
            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,1,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,1,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,1,$PFAIXT);
            
            $total_obtidoSA = $total_obtidoSA + $P_NOTS;
            $total_obtidoME = $total_obtidoME + $P_NOTM;
            $total_obtidoSE = $total_obtidoSE + $P_NOTT;

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ],
                [
                    'DESCRICAO' => 'Produzido',
                    'CONTEUDOS' => $VALOR1,
                ],
                [
                    'DESCRICAO' => 'Programado',
                    'CONTEUDOS' => $this->formatarN($PROGRAMADO_SEMANA,0)
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ],
                [
                    'DESCRICAO' => 'Produzido',
                    'CONTEUDOS' => $VALOR2,
                ],
                [
                    'DESCRICAO' => 'Programado',
                    'CONTEUDOS' => $this->formatarN($PROGRAMADO_MES,0)
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ],
                [
                    'DESCRICAO' => 'Produzido',
                    'CONTEUDOS' => $VALOR3,
                ],
                [
                    'DESCRICAO' => 'Programado',
                    'CONTEUDOS' => $this->formatarN($PROGRAMADO_SEMESTRE,0)
                ]
            ];
   
        }
        
        if($Item['INDICADOR']->ORDEM == 3) {
            
            $VALOR1 = $Indicador['INDICADOR']['QUANTIDADE_SEMANA']['VALOR'];
            $VALOR2 = $Indicador['INDICADOR']['QUANTIDADE_MES']['VALOR'];
            $VALOR3 = $Indicador['INDICADOR']['QUANTIDADE_SEMESTRE']['VALOR'];     
            
            $prod1 = $INDICADOR_ARRAY[0]['VALOR1'];
            $prod2 = $INDICADOR_ARRAY[0]['VALOR2'];
            $prod3 = $INDICADOR_ARRAY[0]['VALOR3'];

            $T1 = $VALOR1;
            $T2 = $VALOR2;
            $T3 = $VALOR3;

            $VALOR1 = calcPerc($prod1,$VALOR1,2);
            $VALOR2 = calcPerc($prod2,$VALOR2,2);
            $VALOR3 = calcPerc($prod3,$VALOR3,2);
            
            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,2,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,2,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,2,$PFAIXT);
            
            $total_obtidoSA = $total_obtidoSA + $P_NOTS;
            $total_obtidoME = $total_obtidoME + $P_NOTM;
            $total_obtidoSE = $total_obtidoSE + $P_NOTT;
            
            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ],
                [
                    'DESCRICAO' => 'Perdas de Bojo',
                    'CONTEUDOS' => $T1,
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ],
                [
                    'DESCRICAO' => 'Perdas de Bojo',
                    'CONTEUDOS' => $T2,
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ],
                [
                    'DESCRICAO' => 'Perdas de Bojo',
                    'CONTEUDOS' => $T3,
                ]
            ];


   
        }
        
        if($Item['INDICADOR']->ORDEM == 4) { 

            $PEDIDO_SEMANA  = $Indicador['INDICADOR']['PEDIDO_SEMANA']['VALOR'];
            $FAT_NAO_SEMANA = $Indicador['INDICADOR']['FAT_NAO_SEMANA']['VALOR'];
            $FAT_DIA_SEMANA = $Indicador['INDICADOR']['FAT_DIA_SEMANA']['VALOR'];
            $FAT_ANT_SEMANA = $Indicador['INDICADOR']['FAT_ANT_SEMANA']['VALOR'];
            $FAT_ATR_SEMANA = $Indicador['INDICADOR']['FAT_ATR_SEMANA']['VALOR'];

            $PEDIDO_MES  = $Indicador['INDICADOR']['PEDIDO_MES']['VALOR'];
            $FAT_NAO_MES = $Indicador['INDICADOR']['FAT_NAO_MES']['VALOR'];
            $FAT_DIA_MES = $Indicador['INDICADOR']['FAT_DIA_MES']['VALOR'];
            $FAT_ANT_MES = $Indicador['INDICADOR']['FAT_ANT_MES']['VALOR'];
            $FAT_ATR_MES = $Indicador['INDICADOR']['FAT_ATR_MES']['VALOR'];

            $PEDIDO_SEMESTRE  = $Indicador['INDICADOR']['PEDIDO_SEMESTRE']['VALOR'];
            $FAT_NAO_SEMESTRE = $Indicador['INDICADOR']['FAT_NAO_SEMESTRE']['VALOR'];
            $FAT_DIA_SEMESTRE = $Indicador['INDICADOR']['FAT_DIA_SEMESTRE']['VALOR'];
            $FAT_ANT_SEMESTRE = $Indicador['INDICADOR']['FAT_ANT_SEMESTRE']['VALOR'];
            $FAT_ATR_SEMESTRE = $Indicador['INDICADOR']['FAT_ATR_SEMESTRE']['VALOR'];

            $VALOR1 = calcPerc($PEDIDO_SEMANA   ,$FAT_DIA_SEMANA + $FAT_ANT_SEMANA,2);
            $VALOR2 = calcPerc($PEDIDO_MES      ,$FAT_DIA_MES + $FAT_ANT_MES,2);
            $VALOR3 = calcPerc($PEDIDO_SEMESTRE ,$FAT_DIA_SEMESTRE + $FAT_ANT_SEMESTRE,2);

            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,1,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,1,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,1,$PFAIXT);

            $total_obtidoSA = $total_obtidoSA + $P_NOTS;
            $total_obtidoME = $total_obtidoME + $P_NOTM;
            $total_obtidoSE = $total_obtidoSE + $P_NOTT;

            $VALOR1 = $this->formatarN2($VALOR1 ,2);
            $VALOR2 = $this->formatarN2($VALOR2 ,2);
            $VALOR3 = $this->formatarN2($VALOR3 ,2);

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ],
                [
                    'DESCRICAO' => 'Pedidos',
                    'CONTEUDOS' => $PEDIDO_SEMANA,
                ],
                [
                    'DESCRICAO' => 'Pedidos em dia',
                    'CONTEUDOS' => $FAT_DIA_SEMANA + $FAT_ANT_SEMANA,
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ],
                [
                    'DESCRICAO' => 'Pedidos',
                    'CONTEUDOS' => $PEDIDO_MES,
                ],
                [
                    'DESCRICAO' => 'Pedidos em dia',
                    'CONTEUDOS' => $FAT_DIA_MES + $FAT_ANT_MES,
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ],
                [
                    'DESCRICAO' => 'Pedidos',
                    'CONTEUDOS' => $PEDIDO_SEMESTRE,
                ],
                [
                    'DESCRICAO' => 'Pedidos em dia',
                    'CONTEUDOS' => $FAT_DIA_SEMESTRE + $FAT_ANT_SEMESTRE,
                ]
            ];
        }
        
        if($Item['INDICADOR']->ORDEM == 5) {
            
            $dados_absenteismo = $Indicador;

            $MIN_TRABALHO_SEMANA    = $Indicador['INDICADOR']['MIN_TRABALHO_SEMANA']['VALOR'];
            $MIN_AUSENTE_SEMANA     = $Indicador['INDICADOR']['MIN_AUSENTE_SEMANA']['VALOR'];
            
            $MIN_TRABALHO_MES       = $Indicador['INDICADOR']['MIN_TRABALHO_MES']['VALOR'];
            $MIN_AUSENTE_MES        = $Indicador['INDICADOR']['MIN_AUSENTE_MES']['VALOR'];
            
            $MIN_TRABALHO_SEMESTRE  = $Indicador['INDICADOR']['MIN_TRABALHO_SEMESTRE']['VALOR'];
            $MIN_AUSENTE_SEMESTRE   = $Indicador['INDICADOR']['MIN_AUSENTE_SEMESTRE']['VALOR'];
            
            if($MIN_TRABALHO_SEMANA   == 0){$VALOR1 = 0;}else{ $VALOR1 =(($MIN_AUSENTE_SEMANA   * 100) / $MIN_TRABALHO_SEMANA   );};
            if($MIN_TRABALHO_MES      == 0){$VALOR2 = 0;}else{ $VALOR2 =(($MIN_AUSENTE_MES      * 100) / $MIN_TRABALHO_MES      );};
            if($MIN_TRABALHO_SEMESTRE == 0){$VALOR3 = 0;}else{ $VALOR3 =(($MIN_AUSENTE_SEMESTRE * 100) / $MIN_TRABALHO_SEMESTRE );};
            
            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,2,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,2,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,2,$PFAIXT);
            
            $total_obtidoSA = $total_obtidoSA + $P_NOTS;
            $total_obtidoME = $total_obtidoME + $P_NOTM;
            $total_obtidoSE = $total_obtidoSE + $P_NOTT;

            $VALOR1 = $this->formatarN2($VALOR1 ,2);
            $VALOR2 = $this->formatarN2($VALOR2 ,2);
            $VALOR3 = $this->formatarN2($VALOR3 ,2);

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ],
                [
                    'DESCRICAO' => 'Minutos Trabalhados',
                    'CONTEUDOS' => $MIN_TRABALHO_SEMANA ,
                ],
                [
                    'DESCRICAO' => 'Minutos ausentes',
                    'CONTEUDOS' => $MIN_AUSENTE_SEMANA ,
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ],
                [
                    'DESCRICAO' => 'Minutos Trabalhados',
                    'CONTEUDOS' => $MIN_TRABALHO_MES ,
                ],
                [
                    'DESCRICAO' => 'Minutos ausentes',
                    'CONTEUDOS' => $MIN_AUSENTE_MES,
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ],
                [
                    'DESCRICAO' => 'Minutos Trabalhados',
                    'CONTEUDOS' => $MIN_TRABALHO_SEMESTRE ,
                ],
                [
                    'DESCRICAO' => 'Minutos ausentes',
                    'CONTEUDOS' => $MIN_AUSENTE_SEMESTRE,
                ]
            ];
        }
        
        if($Item['INDICADOR']->ORDEM == 61) {

            $Indicador = $dados_absenteismo;

            $DEMITIDO_SEMANA      = $Indicador['INDICADOR']['DEMITIDO_SEMANA'   ]['VALOR'];
            $ADMITIDO_SEMANA      = $Indicador['INDICADOR']['ADMITIDO_SEMANA'   ]['VALOR'];
            $JA_ADMITIDO_SEMANA   = $Indicador['INDICADOR']['JA_ADMITIDO_SEMANA']['VALOR'];
            
            $DEMITIDO_MES         = $Indicador['INDICADOR']['DEMITIDO_MES'   ]['VALOR'];
            $ADMITIDO_MES         = $Indicador['INDICADOR']['ADMITIDO_MES'   ]['VALOR'];
            $JA_ADMITIDO_MES      = $Indicador['INDICADOR']['JA_ADMITIDO_MES']['VALOR'];
            
            $DEMITIDO_SEMESTRE    = $Indicador['INDICADOR']['DEMITIDO_SEMESTRE'   ]['VALOR'];
            $ADMITIDO_SEMESTRE    = $Indicador['INDICADOR']['ADMITIDO_SEMESTRE'   ]['VALOR'];
            $JA_ADMITIDO_SEMESTRE = $Indicador['INDICADOR']['JA_ADMITIDO_SEMESTRE']['VALOR'];
            
            $TUNOVER1_SEMANA      = (($DEMITIDO_SEMANA   + $ADMITIDO_SEMANA   ) * 100.0 / 2);
            $TUNOVER1_MES         = (($DEMITIDO_MES      + $ADMITIDO_MES      ) * 100.0 / 2);
            $TUNOVER1_SEMESTRE    = (($DEMITIDO_SEMESTRE + $ADMITIDO_SEMESTRE ) * 100.0 / 2);
            
            $TUNOVER2_SEMANA      = $JA_ADMITIDO_SEMANA;
            $TUNOVER2_MES         = $JA_ADMITIDO_SEMANA;
            $TUNOVER2_SEMESTRE    = $JA_ADMITIDO_SEMANA;
            
            if($TUNOVER2_SEMANA   == 0 ){$TUNOVER_SEMANA   = 0;}else{ $TUNOVER_SEMANA   = ($TUNOVER1_SEMANA   / $TUNOVER2_SEMANA   );}
            if($TUNOVER2_MES      == 0 ){$TUNOVER_MES      = 0;}else{ $TUNOVER_MES      = ($TUNOVER1_MES      / $TUNOVER2_MES      );}
            if($TUNOVER2_SEMESTRE == 0 ){$TUNOVER_SEMESTRE = 0;}else{ $TUNOVER_SEMESTRE = ($TUNOVER1_SEMESTRE / $TUNOVER2_SEMESTRE );}
            
            $VALOR1 = $this->formatarN2($TUNOVER_SEMANA   ,2);
            $VALOR2 = $this->formatarN2($TUNOVER_MES      ,2);
            $VALOR3 = $this->formatarN2($TUNOVER_SEMESTRE ,2);
            
            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,2,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,2,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,2,$PFAIXT);
            
            $total_obtidoSA = $total_obtidoSA + $P_NOTS;
            $total_obtidoME = $total_obtidoME + $P_NOTM;
            $total_obtidoSE = $total_obtidoSE + $P_NOTT;

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ],
                [
                    'DESCRICAO' => 'Demitidos',
                    'CONTEUDOS' => $DEMITIDO_SEMANA ,
                ],
                [
                    'DESCRICAO' => 'Adimitidos',
                    'CONTEUDOS' => $ADMITIDO_SEMANA ,
                ],
                [
                    'DESCRICAO' => 'Colaboradores',
                    'CONTEUDOS' => $JA_ADMITIDO_SEMANA ,
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ],
                [
                    'DESCRICAO' => 'Demitidos',
                    'CONTEUDOS' => $DEMITIDO_MES ,
                ],
                [
                    'DESCRICAO' => 'Adimitidos',
                    'CONTEUDOS' => $ADMITIDO_MES ,
                ],
                [
                    'DESCRICAO' => 'Colaboradores',
                    'CONTEUDOS' => $JA_ADMITIDO_MES ,
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ],
                [
                    'DESCRICAO' => 'Demitidos',
                    'CONTEUDOS' => $DEMITIDO_SEMESTRE ,
                ],
                [
                    'DESCRICAO' => 'Adimitidos',
                    'CONTEUDOS' => $ADMITIDO_SEMESTRE ,
                ],
                [
                    'DESCRICAO' => 'Colaboradores',
                    'CONTEUDOS' => $JA_ADMITIDO_SEMESTRE ,
                ]
            ];
        }
        
        if($Item['INDICADOR']->ORDEM == 6) {

            $REMESSAS_SEMANA    = $Indicador['INDICADOR']['REMESSAS_SEMANA']['VALOR'];
            $ATRASADAS_SEMANA   = $Indicador['INDICADOR']['ATRASADAS_SEMANA']['VALOR'];
            $EMDIA_SEMANA       = $Indicador['INDICADOR']['EMDIA_SEMANA']['VALOR'];
            $REMESSAS_MES       = $Indicador['INDICADOR']['REMESSAS_MES']['VALOR'];
            $ATRASADAS_MES      = $Indicador['INDICADOR']['ATRASADAS_MES']['VALOR'];
            $EMDIA_MES          = $Indicador['INDICADOR']['EMDIA_MES']['VALOR'];
            $REMESSAS_SEMESTRE  = $Indicador['INDICADOR']['REMESSAS_SEMESTRE']['VALOR'];
            $ATRASADAS_SEMESTRE = $Indicador['INDICADOR']['ATRASADAS_SEMESTRE']['VALOR'];
            $EMDIA_SEMESTRE     = $Indicador['INDICADOR']['EMDIA_SEMESTRE']['VALOR'];

            if($REMESSAS_SEMANA   == 0 ){$VALOR1 = 0;}else{ $VALOR1 = (($EMDIA_SEMANA   / $REMESSAS_SEMANA  )*100);}
            if($REMESSAS_MES      == 0 ){$VALOR2 = 0;}else{ $VALOR2 = (($EMDIA_MES      / $REMESSAS_MES     )*100);}
            if($REMESSAS_SEMESTRE == 0 ){$VALOR3 = 0;}else{ $VALOR3 = (($EMDIA_SEMESTRE / $REMESSAS_SEMESTRE)*100);}
            
            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,1,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,1,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,1,$PFAIXT);
            
            $total_obtidoSA = $total_obtidoSA + $P_NOTS;
            $total_obtidoME = $total_obtidoME + $P_NOTM;
            $total_obtidoSE = $total_obtidoSE + $P_NOTT;

            $VALOR1 = $this->formatarN2($VALOR1 ,2);
            $VALOR2 = $this->formatarN2($VALOR2 ,2);
            $VALOR3 = $this->formatarN2($VALOR3 ,2);
            
            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ]
            ];

            $old1 = '';
            $old2 = '';
            foreach ($Item['DADOS'] as $key => $value) {

                $setor = $value->SETOR_VALOR;
                $campo = $value->CAMPO;
                $valor = $value->VALOR;

                if($campo == 'REMESSAS_SEMANA'){
                    if($old1 != $setor){
                        $old1 = $setor;
                        array_push($TABELAS,[
                            'DESCRICAO' => $setor.' Rem.:',
                            'CONTEUDOS' => $valor,
                        ]);
                    }
                }

                if($campo == 'EMDIA_SEMANA'){
                    if($old2 != $setor){
                        $old2 = $setor;
                        array_push($TABELAS,[
                            'DESCRICAO' => $setor.' Em dia.:',
                            'CONTEUDOS' => $valor,
                        ]);
                    }
                }
            }

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ]
            ];

            $old1 = '';
            $old2 = '';
            foreach ($Item['DADOS'] as $key => $value) {

                $setor = $value->SETOR_VALOR;
                $campo = $value->CAMPO;
                $valor = $value->VALOR;

                if($campo == 'REMESSAS_MES'){
                    if($old1 != $setor){
                        $old1 = $setor;
                        array_push($TABELAM,[
                            'DESCRICAO' => $setor.' Rem.:',
                            'CONTEUDOS' => $valor,
                        ]);
                    }
                }

                if($campo == 'EMDIA_MES'){
                    if($old2 != $setor){
                        $old2 = $setor;
                        array_push($TABELAM,[
                            'DESCRICAO' => $setor.' Em dia.:',
                            'CONTEUDOS' => $valor,
                        ]);
                    }
                }
            }

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ]
            ];

            $old1 = '';
            $old2 = '';
            foreach ($Item['DADOS'] as $key => $value) {

                $setor = $value->SETOR_VALOR;
                $campo = $value->CAMPO;
                $valor = $value->VALOR;

                if($campo == 'REMESSAS_SEMESTRE'){
                    if($old1 != $setor){
                        $old1 = $setor;
                        array_push($TABELAT,[
                            'DESCRICAO' => $setor.' Rem.:',
                            'CONTEUDOS' => $valor,
                        ]);
                    }
                }

                if($campo == 'EMDIA_SEMESTRE'){
                    if($old2 != $setor){
                        $old2 = $setor;
                        array_push($TABELAT,[
                            'DESCRICAO' => $setor.' Em dia.:',
                            'CONTEUDOS' => $valor,
                        ]);
                    }
                }
            }
  
        }
        
        if($Item['INDICADOR']->ORDEM == 7) {
            
            $VALOR_SEMANA = $Indicador['INDICADOR']['VALOR_SEMANA']['VALOR'];
            $PESO_SEMANA  = $Indicador['INDICADOR']['PESO_SEMANA' ]['VALOR'];
            $META1_SEMANA = $Indicador['INDICADOR']['META1_SEMANA']['VALOR'];
            $META2_SEMANA = $Indicador['INDICADOR']['META2_SEMANA']['VALOR'];
            $TIPO_SEMANA  = $Indicador['INDICADOR']['TIPO_SEMANA' ]['VALOR'];
            
            $VALOR_SEMANA = ($VALOR_SEMANA * $PESO_SEMANA);
            $META1_SEMANA = ($META2_SEMANA * $PESO_SEMANA);
            $META2_SEMANA = ($META2_SEMANA * $PESO_SEMANA);
            
            $META_SEMANA  = $this->iif(($TIPO_SEMANA == 1 ) , $META1_SEMANA, $META2_SEMANA);
            if($META_SEMANA == 0 ){$VALOR1 = -1;}else{ $VALOR1 =($VALOR_SEMANA / $META_SEMANA) * 100;}
            
            $VALOR_MES    = $Indicador['INDICADOR']['VALOR_MES']['VALOR'];
            $PESO_MES     = $Indicador['INDICADOR']['PESO_MES' ]['VALOR'];
            $META1_MES    = $Indicador['INDICADOR']['META1_MES']['VALOR'];
            $META2_MES    = $Indicador['INDICADOR']['META2_MES']['VALOR'];
            $TIPO_MES     = $Indicador['INDICADOR']['TIPO_MES' ]['VALOR'];
            
            $VALOR_MES    = ($VALOR_MES * $PESO_MES);
            $META1_MES    = ($META2_MES * $PESO_MES);
            $META2_MES    = ($META2_MES * $PESO_MES);
            
            $META_MES     = $this->iif(($TIPO_MES == 1 ) , $META1_MES, $META2_MES);
            if($META_MES == 0 ){$VALOR2 = -1;}else{ $VALOR2 =($VALOR_MES / $META_MES) * 100;}
            
            $VALOR_SEMESTRE = $Indicador['INDICADOR']['VALOR_SEMESTRE']['VALOR'];
            $PESO_SEMESTRE  = $Indicador['INDICADOR']['PESO_SEMESTRE' ]['VALOR'];
            $META1_SEMESTRE = $Indicador['INDICADOR']['META1_SEMESTRE']['VALOR'];
            $META2_SEMESTRE = $Indicador['INDICADOR']['META2_SEMESTRE']['VALOR'];
            $TIPO_SEMESTRE  = $Indicador['INDICADOR']['TIPO_SEMESTRE' ]['VALOR'];
            
            $VALOR_SEMESTRE = ($VALOR_SEMESTRE * $PESO_SEMESTRE);
            $META1_SEMESTRE = ($META2_SEMESTRE * $PESO_SEMESTRE);
            $META2_SEMESTRE = ($META2_SEMESTRE * $PESO_SEMESTRE);
            
            $META_SEMESTRE  = $this->iif(($TIPO_SEMESTRE == 1 ) , $META1_SEMESTRE, $META2_SEMESTRE);
            if($META_SEMESTRE == 0 ){$VALOR3 = -1;}else{ $VALOR3 =($VALOR_SEMESTRE / $META_SEMESTRE) * 100;}
            
            $VALOR1 = $this->formatarN2($VALOR1 ,2);
            $VALOR2 = $this->formatarN2($VALOR2 ,2);
            $VALOR3 = $this->formatarN2($VALOR3 ,2);
            
            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,1,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,1,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,1,$PFAIXT);
            
            $COR1 = $this->iif($VALOR1 == -1,0,$COR1);
            $COR2 = $this->iif($VALOR2 == -1,0,$COR2);
            $COR3 = $this->iif($VALOR3 == -1,0,$COR3);
            
            $P_NOTS = $this->iif($VALOR1 == -1,0,$P_NOTS);
            $P_NOTM = $this->iif($VALOR2 == -1,0,$P_NOTM);
            $P_NOTT = $this->iif($VALOR3 == -1,0,$P_NOTT);
            
            $total_obtidoSA = $total_obtidoSA + $P_NOTS;
            $total_obtidoME = $total_obtidoME + $P_NOTM;
            $total_obtidoSE = $total_obtidoSE + $P_NOTT;

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ]
            ];
        }
        
        if($Item['INDICADOR']->ORDEM == 8) {
            
            $VALOR1 = $this->filterCampo($Item['DADOS'],'QUANTIDADE_SEMANA'  )->VALOR;
            $VALOR2 = $this->filterCampo($Item['DADOS'],'QUANTIDADE_MES'     )->VALOR;
            $VALOR3 = $this->filterCampo($Item['DADOS'],'QUANTIDADE_SEMESTRE')->VALOR;

            $T1 = $VALOR1;
            $T2 = $VALOR2;
            $T3 = $VALOR3;

            $prod1 = $INDICADOR_ARRAY[0]['VALOR1'];
            $prod2 = $INDICADOR_ARRAY[0]['VALOR2'];
            $prod3 = $INDICADOR_ARRAY[0]['VALOR3'];
            
            $VALOR1 = calcPerc($prod1,$VALOR1,2);
            $VALOR2 = calcPerc($prod2,$VALOR2,2);
            $VALOR3 = calcPerc($prod3,$VALOR3,2);

            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,2,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,2,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,2,$PFAIXT);

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ],
                [
                    'DESCRICAO' => 'Requisitado',
                    'CONTEUDOS' => $T1,
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ],
                [
                    'DESCRICAO' => 'Requisitado',
                    'CONTEUDOS' => $T2,
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ],
                [
                    'DESCRICAO' => 'Requisitado',
                    'CONTEUDOS' => $T3,
                ]
            ];
        }

        if($Item['INDICADOR']->ORDEM == 9) {
            
            
            $VALOR1 = $Indicador['INDICADOR']['QUANTIDADE_SEMANA']['VALOR'];
            $VALOR2 = $Indicador['INDICADOR']['QUANTIDADE_MES']['VALOR'];
            $VALOR3 = $Indicador['INDICADOR']['QUANTIDADE_SEMESTRE']['VALOR'];

            $FAT1 = $Indicador['INDICADOR']['FATURADO_SEMANA']['VALOR']   / $Indicador['INDICADOR']['FATURADO_SEMANA']['CONT'];
            $FAT2 = $Indicador['INDICADOR']['FATURADO_MES']['VALOR']      / $Indicador['INDICADOR']['FATURADO_MES']['CONT'];
            $FAT3 = $Indicador['INDICADOR']['FATURADO_SEMESTRE']['VALOR'] / $Indicador['INDICADOR']['FATURADO_SEMESTRE']['CONT'];

            $T1 = $VALOR1;
            $T2 = $VALOR2;
            $T3 = $VALOR3;

            $VALOR1 = calcPerc($FAT1, $VALOR1,4);
            $VALOR2 = calcPerc($FAT2, $VALOR2,4);
            $VALOR3 = calcPerc($FAT3, $VALOR3,4);

            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,2,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,2,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,2,$PFAIXT);

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ],
                [
                    'DESCRICAO' => 'Devolução',
                    'CONTEUDOS' => $T1,
                ],
                [
                    'DESCRICAO' => 'Faturamento',
                    'CONTEUDOS' => $FAT1,
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ],
                [
                    'DESCRICAO' => 'Devolução',
                    'CONTEUDOS' => $T2,
                ],
                [
                    'DESCRICAO' => 'Faturamento',
                    'CONTEUDOS' => $FAT2,
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ],
                [
                    'DESCRICAO' => 'Devolução',
                    'CONTEUDOS' => $T3,
                ],
                [
                    'DESCRICAO' => 'Faturamento',
                    'CONTEUDOS' => $FAT3,
                ]
            ];
        }
        
        if($Item['INDICADOR']->ORDEM == 10) {
            
            $VALOR_SEMANA   = $Indicador['INDICADOR']['VALOR_SEMANA']['VALOR'];
            $PESO_SEMANA    = $Indicador['INDICADOR']['PESO_SEMANA' ]['VALOR'];
            $META1_SEMANA   = $Indicador['INDICADOR']['META1_SEMANA']['VALOR'];
            $META2_SEMANA   = $Indicador['INDICADOR']['META2_SEMANA']['VALOR'];
            $TIPO_SEMANA    = $Indicador['INDICADOR']['TIPO_SEMANA' ]['VALOR'];
            $META_SEMANA    = $this->iif(($TIPO_SEMANA == 1 ) , $META1_SEMANA, $META2_SEMANA);
            $VALOR1         = $VALOR_SEMANA;
            
            $VALOR_MES      = $Indicador['INDICADOR']['VALOR_MES']['VALOR'];
            $PESO_MES       = $Indicador['INDICADOR']['PESO_MES' ]['VALOR'];
            $META1_MES      = $Indicador['INDICADOR']['META1_MES']['VALOR'];
            $META2_MES      = $Indicador['INDICADOR']['META2_MES']['VALOR'];
            $TIPO_MES       = $Indicador['INDICADOR']['TIPO_MES' ]['VALOR'];
            $META_MES       = $this->iif(($TIPO_MES == 1 ) , $META1_MES, $META2_MES);
            $VALOR2         = $VALOR_MES;
            
            $VALOR_SEMESTRE = $Indicador['INDICADOR']['VALOR_SEMESTRE']['VALOR'];
            $PESO_SEMESTRE  = $Indicador['INDICADOR']['PESO_SEMESTRE' ]['VALOR'];
            $META1_SEMESTRE = $Indicador['INDICADOR']['META1_SEMESTRE']['VALOR'];
            $META2_SEMESTRE = $Indicador['INDICADOR']['META2_SEMESTRE']['VALOR'];
            $TIPO_SEMESTRE  = $Indicador['INDICADOR']['TIPO_SEMESTRE' ]['VALOR'];
            $META_SEMESTRE  = $this->iif(($TIPO_SEMESTRE == 1 ) , $META1_SEMESTRE, $META2_SEMESTRE);
            $VALOR3         = $VALOR_SEMESTRE;
            
            $VALOR1 = $this->formatarN2($VALOR1 ,2);
            $VALOR2 = $this->formatarN2($VALOR2 ,2);
            $VALOR3 = $this->formatarN2($VALOR3 ,2);
            
            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,2,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,2,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,2,$PFAIXT);
            
            $total_obtidoSA = $total_obtidoSA + $P_NOTS;
            $total_obtidoME = $total_obtidoME + $P_NOTM;
            $total_obtidoSE = $total_obtidoSE + $P_NOTT;

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ]
            ];
        }
        
        if($Item['INDICADOR']->ORDEM == 11) {

            $VALOR_SEMANA   = $Indicador['INDICADOR']['VALOR_SEMANA']['VALOR'];
            $PESOA_SEMANA   = $Indicador['INDICADOR']['PESOA_SEMANA']['VALOR'];
            $PESOB_SEMANA   = $Indicador['INDICADOR']['PESOB_SEMANA']['VALOR'];
            $TIPO_SEMANA    = $Indicador['INDICADOR']['TIPO_SEMANA']['VALOR'];

            $VALOR_MES      = $Indicador['INDICADOR']['VALOR_MES']['VALOR'];
            $PESOA_MES      = $Indicador['INDICADOR']['PESOA_MES']['VALOR'];
            $PESOA_MES      = $Indicador['INDICADOR']['PESOA_MES']['VALOR'];
            $PESOB_MES      = $Indicador['INDICADOR']['PESOB_MES']['VALOR'];
            $TIPO_MES       = $Indicador['INDICADOR']['TIPO_MES']['VALOR'];

            $VALOR_SEMESTRE = $Indicador['INDICADOR']['VALOR_SEMESTRE']['VALOR'];
            $PESOA_SEMESTRE = $Indicador['INDICADOR']['PESOA_SEMESTRE']['VALOR'];
            $PESOB_SEMESTRE = $Indicador['INDICADOR']['PESOB_SEMESTRE']['VALOR'];
            $TIPO_SEMESTRE  = $Indicador['INDICADOR']['TIPO_SEMESTRE']['VALOR'];
            
            if($TIPO_SEMANA == 1 ){
                if($PESOB_SEMANA > 0){
                    $VALOR1 = ($VALOR_SEMANA / $PESOB_SEMANA) * 100;
                }else{
                    $VALOR1 = -1;
                }
            }else{
                if($PESOA_SEMANA > 0){
                    $VALOR1 = ($VALOR_SEMANA / $PESOA_SEMANA) * 100;
                }else{
                    $VALOR1 = -1;
                }
            }

            if($TIPO_MES == 1 ){
                if($PESOB_MES > 0){
                    $VALOR2 = ($VALOR_MES / $PESOB_MES) * 100;
                }else{
                    $VALOR2 = -1;
                }
            }else{
                if($PESOA_MES > 0){
                    $VALOR2 = ($VALOR_MES / $PESOA_MES) * 100;
                }else{
                    $VALOR2 = -1;
                }
            }

            if($TIPO_SEMESTRE == 1 ){
                if($PESOB_SEMESTRE > 0){
                    $VALOR3 = ($VALOR_SEMESTRE / $PESOB_SEMESTRE) * 100;
                }else{
                    $VALOR3 = -1;
                }
            }else{
                if($PESOA_SEMESTRE > 0){
                    $VALOR3 = ($VALOR_SEMESTRE / $PESOA_SEMESTRE) * 100;
                }else{
                    $VALOR3 = -1;
                }
            }

            $VALOR1 = $this->formatarN2($VALOR1 ,2);
            $VALOR2 = $this->formatarN2($VALOR2 ,2);
            $VALOR3 = $this->formatarN2($VALOR3 ,2);
            
            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,1,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,1,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,1,$PFAIXT);
            
            $COR1 = $this->iif($VALOR1 == -1,0,$COR1);
            $COR2 = $this->iif($VALOR2 == -1,0,$COR2);
            $COR3 = $this->iif($VALOR3 == -1,0,$COR3);
            
            $P_NOTS = $this->iif($VALOR1 == -1,0,$P_NOTS);
            $P_NOTM = $this->iif($VALOR2 == -1,0,$P_NOTM);
            $P_NOTT = $this->iif($VALOR3 == -1,0,$P_NOTT);
            
            $total_obtidoSA = $total_obtidoSA + $P_NOTS;
            $total_obtidoME = $total_obtidoME + $P_NOTM;
            $total_obtidoSE = $total_obtidoSE + $P_NOTT;

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTS,
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTM,
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso da Faixa',
                    'CONTEUDOS' => $PFAIXT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $P_NOTT,
                ]
            ];
               
        }
        
        $INDICADOR = [
            'DESC'      => $Item['INDICADOR']->DESCRICAO,
            'DEF1'      => $DEF1,
            'DEF2'      => $DEF2,
            'DEF3'      => $DEF3,
            'PESO'      => $peso,
            'COR1'      => $COR1,
            'COR2'      => $COR2,
            'COR3'      => $COR3,
            'VALOR1'    => $VALOR1,
            'VALOR2'    => $VALOR2,
            'VALOR3'    => $VALOR3,
            'P_NOTS'    => $P_NOTS,
            'P_NOTM'    => $P_NOTM,
            'P_NOTT'    => $P_NOTT,
            'ORDEM'     => $Item['INDICADOR']->ORDEM,
            'PFAIXS'    => $PFAIXS,
            'PFAIXM'    => $PFAIXM,
            'PFAIXT'    => $PFAIXT,
            'TABELAS'   => $TABELAS,
            'TABELAM'   => $TABELAM,
            'TABELAT'   => $TABELAT
        ];
        
        if($Item['INDICADOR']->ORDEM == 12) {

            $VALOR1 = 0;
            $VALOR2 = 0;
            $VALOR3 = 0;

            if($total_peso_semana > 0){
                $VALOR1 = ($total_obtidoSA / $total_peso_semana) * 100;
            }

            if($total_peso_semana > 0){
                $VALOR2 = ($total_obtidoME / $total_peso_mes) * 100;
            }

            if($total_peso_semana > 0){
                $VALOR3 = ($total_obtidoSE / $total_peso_semestre) * 100;
            }

            $this->getCor($Item['FAIXA'],$VALOR1,$COR1,$P_NOTS,1,$PFAIXS);
            $this->getCor($Item['FAIXA'],$VALOR2,$COR2,$P_NOTM,1,$PFAIXM);
            $this->getCor($Item['FAIXA'],$VALOR3,$COR3,$P_NOTT,1,$PFAIXT);
            
            $P_NOTS     = $total_peso_semana;
            $P_NOTM     = $total_peso_mes;
            $P_NOTT     = $total_peso_semestre;

            $TABELAS    = [
                [
                    'DESCRICAO' => 'Peso Máximo',
                    'CONTEUDOS' => $P_NOTS,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $total_obtidoSA,
                ]
            ];

            $TABELAM    = [
                [
                    'DESCRICAO' => 'Peso Máximo',
                    'CONTEUDOS' => $P_NOTM,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $total_obtidoME,
                ]
            ];

            $TABELAT    = [
                [
                    'DESCRICAO' => 'Peso Máximo',
                    'CONTEUDOS' => $P_NOTT,
                ],
                [
                    'DESCRICAO' => 'Peso Obtido',
                    'CONTEUDOS' => $total_obtidoSE,
                ]
            ];
            
            $INDICADOR = [
                'DESC'      => $Item['INDICADOR']->DESCRICAO,
                'DEF1'      => $DEF1,
                'DEF2'      => $DEF2,
                'DEF3'      => $DEF3,
                'PESO'      => $SOMA_PESO,
                'COR1'      => $COR1,
                'COR2'      => $COR2,
                'COR3'      => $COR3,
                'VALOR1'    => $this->formatarN2($VALOR1,2),
                'VALOR2'    => $this->formatarN2($VALOR2,2),
                'VALOR3'    => $this->formatarN2($VALOR3,2),
                'P_NOTS'    => $P_NOTS,
                'P_NOTM'    => $P_NOTM,
                'P_NOTT'    => $P_NOTT,
                'ORDEM'     => $Item['INDICADOR']->ORDEM,
                'PFAIXS'    => $PFAIXS,
                'PFAIXM'    => $PFAIXM,
                'PFAIXT'    => $PFAIXT,
                'TABELAS'   => $TABELAS,
                'TABELAM'   => $TABELAM,
                'TABELAT'   => $TABELAT
            ];
        }
        
        if($VALOR1 != -1){$total_peso_semana   = $total_peso_semana   + $peso;}
        if($VALOR2 != -1){$total_peso_mes      = $total_peso_mes      + $peso;}
        if($VALOR3 != -1){$total_peso_semestre = $total_peso_semestre + $peso;}

        $SOMA_PESO = $peso + $SOMA_PESO;
        
        array_push($INDICADOR_ARRAY, $INDICADOR);
    }
    
    $data_prod      = date('d/m/Y',strtotime($data_prod));
    $data_indicador = date('d/m/Y',strtotime($data_indicador)).' as '.date('H:i:s',strtotime($data_indicador));
    
    
    $linhas = count($INDICADOR_ARRAY);
    $linhas_perc = 94 / $linhas;
    $linhas_g = 94 / $linhas;
       
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
        'DADOS_COMP'    => $dados_componente
    ];
    
    return view('opex._25900.include.painel_bsc',['dados' => $dados]);        