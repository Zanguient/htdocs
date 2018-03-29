<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DTO\Admin\_11030;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto 11030 - Perfil
 */
class _11030Controller extends Controller {

	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11030';

    /**
     * Conexão.
     * @var _Conexao
     */
    private $con = null;

}