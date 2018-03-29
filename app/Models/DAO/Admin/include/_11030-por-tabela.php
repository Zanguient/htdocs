<?php

use App\Models\DTO\Admin\_11030;

$param = [];
$param = $filtro							? $param + ['FILTRO'	=> $filtro]						: $param;
$param = isset($condicao_campo['GP'])		? $param + ['GP'		=> $condicao_campo['GP']]		: $param;
$param = isset($condicao_campo['TABELA'])	? $param + ['TABELA'	=> $condicao_campo['TABELA']]	: $param;
$param = isset($condicao_campo['ORDER'])	? $param + ['ORDER'		=> $condicao_campo['ORDER']]	: $param;

$ret = _11030::consultarPerfilPorTabela($param);