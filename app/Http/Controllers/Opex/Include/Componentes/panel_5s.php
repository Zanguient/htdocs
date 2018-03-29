<?php

$frase = [];
    
    $cont = 0;
    $soma_notas_original = 0;
    $soma_notas_revisada = 0;
    $indicadorVermelho = 0;

    $meta1 = 0;
    $meta2 = 0;
    $meta3 = 0;
    $meta4 = 0;
    $meta5 = 0;
    $meta6 = 0;

    $faixas = $dados_componente['FAIXA'];

    foreach($faixas as $faixa){
        $meta1 = $faixa->PERFIL1_A;
        $meta2 = $faixa->PERFIL1_B;
        $meta3 = $faixa->PERFIL2_A;
        $meta4 = $faixa->PERFIL2_B;
        $meta5 = $faixa->PERFIL3_A;
        $meta6 = $faixa->PERFIL3_B;
    }

    foreach($dados_componente['DADOS'] as $item){

        $valor = $item->VALOR2;

        $soma_notas_original = $soma_notas_original + $item->VALOR;
        $soma_notas_revisada = $soma_notas_revisada + $item->VALOR2;

        $cor   = 0;

        if($valor == 1){$indicadorVermelho = 1;}

        if((intval($valor) >= intval($meta1)) && (intval($valor) <= intval($meta2))){
            $cor = 1;
        }else{
            if((intval($valor) >= intval($meta3)) && (intval($valor) <= intval($meta4))){
                $cor = 2;
            }else{
                if((intval($valor) >= intval($meta5)) && (intval($valor) <= intval($meta6))){
                    $cor = 3;
                }
            }
        } 

        $frs = [
            'cor'       => $cor,
            'descricao' => $item->DESCRICAO
        ];

        array_push($frase, $frs);
        
        $cont++;
    }
    
    if($cont > 0){$perc_linha = (100 / $cont);}else{$perc_linha = 10;}
    
    if($perc_linha < 10){$perc_linha = 10;}

    if($cont == 0){$cont = 1;}
    
    $original = $this->formatarN($soma_notas_original/$cont,1);
    $revisada = $this->formatarN($soma_notas_revisada/$cont,1);

    $cor_img = 0;

    if((intval($revisada) >= intval($meta1)) && (intval($revisada) <= intval($meta2))){
        $cor_img = 1;
    }else{
        if((intval($revisada) >= intval($meta3)) && (intval($revisada) <= intval($meta4))){
            $cor_img = 2;
        }else{
            if((intval($revisada) >= intval($meta5)) && (intval($revisada) <= intval($meta6))){
                $cor_img = 3;
            }
        }
    }

    if($indicadorVermelho == 1){$cor_img = 3;}

    $dados = [
        'cor_imagem'        => $cor_img,
        'nota_original'     => $original,
        'nova_original'     => $revisada,
        'descricao'         => '1',
        'frases'            => $frase,
        'perc_linha'        => $perc_linha
    ];

return $dados;