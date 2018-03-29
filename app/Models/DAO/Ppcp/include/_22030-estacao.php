<?php

use App\Models\DTO\Ppcp\_22030;

$param = ['RETORNO' => ['ESTACAO']];
$param = $filtro							? $param + ['FILTRO'	=> $filtro]						: $param;
$param = isset($condicao_campo['UP'])		? $param + ['UP'		=> $condicao_campo['UP']]		: $param;
$param = isset($condicao_campo['STATUS'])	? $param + ['STATUS'	=> $condicao_campo['STATUS']]	: $param;

$ret = _22030::listar($param)->ESTACAO;