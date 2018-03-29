<?php

use App\Models\DTO\Ppcp\_22110;

$param = [];

$filtro ? $param += ['FILTRO'  => $filtro] : null;

$ret = _22110::selectAgrupamento($param);