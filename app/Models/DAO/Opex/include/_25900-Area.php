<?php

    use App\Models\DAO\Opex\_25900DAO;
    use App\Models\DAO\Helper\ConsultaAllDAO;
    
    $dados = [
        'filtro'            => $filtro,
        'obj'               => $obj,
        'campos'            => $campos,
        'condicao'          => $condicao,
        'condicao_campo'    => $condicao_campo,
        'imputs'            => $imputs,
        'ESTAB'             => $imputs['_input_estab']
    ];
    
    if(!ConsultaAllDAO::validarValor($imputs['_input_estab'])){
        print_l('Selecione um estabelecimento!');
    }
    
    $ret = _25900DAO::consultarArea($dados);