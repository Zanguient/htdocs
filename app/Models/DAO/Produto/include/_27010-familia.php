<?php

use App\Models\DTO\Produto\_27010;
use App\Models\Conexao\_Conexao;

$param = [];

$filtro                                  ? $param += ['FILTRO'          => $filtro]                            : null;
isset($condicao_campo['TIPOPRODUTO_ID']) ? $param += ['TIPOPRODUTO_ID'  => $condicao_campo['TIPOPRODUTO_ID' ]] : null;
isset($condicao_campo['STATUS'        ]) ? $param += ['STATUS'          => $condicao_campo['STATUS'         ]] : null;

$dto_22010 = new _27010(new _Conexao);

$ret = $dto_22010->selectFamilia($param);