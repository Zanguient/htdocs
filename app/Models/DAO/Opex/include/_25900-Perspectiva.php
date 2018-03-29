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
        'AREA'              => $imputs['_area_id']
    ];
    
    
    if(!ConsultaAllDAO::validarValor($imputs['_area_id'])){
        print_l('Selecione uma área!');
    }
    
    $ret = _25900DAO::consultarPerspectiva($dados);