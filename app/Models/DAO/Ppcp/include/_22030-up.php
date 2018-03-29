<?php

use App\Models\DTO\Ppcp\_22030;

$param = ['RETORNO' => ['UP']];
$param = $filtro								? $param + ['FILTRO'	=> $filtro]							: $param;
$param = isset($condicao_campo['GP'])			? $param + ['GP'		=> $condicao_campo['GP']]			: $param;
$param = isset($condicao_campo['PERFIL_UP'])	? $param + ['PERFIL_UP'	=> $condicao_campo['PERFIL_UP']]	: $param;
$param = isset($condicao_campo['STATUS'])		? $param + ['STATUS'	=> $condicao_campo['STATUS']]		: $param;
$param = isset($condicao_campo['FAMILIA'])		? $param + ['FAMILIA'	=> $condicao_campo['FAMILIA']]		: $param;
$param = isset($condicao_campo['ORDER'])		? $param + ['ORDER'		=> $condicao_campo['ORDER']]		: $param;

$ret = _22030::listar($param)->UP;