<?php

$sql = "SELECT T.CODIGO as ID, LPAD(T.CODIGO, 2, 0) as MASK, T.DESCRICAO FROM TBTURNO T";

$ret = $con->query($sql);