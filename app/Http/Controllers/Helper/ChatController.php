<?php

namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Models\Socket\Socket;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Helper\Chat;

class ChatController extends Controller {

	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'helper/chat';

    /**
     * Conexão.
     * @var _Conexao
     */
    private $con = null;


	public function viewIndex() {
		return view('helper/chat/index');
	}

	public function gravar(Request $request) {

		$this->con = new _Conexao();

		try {

			$dado = json_decode(json_encode($request->all()));

			Chat::gravar($dado, $this->con);

			$this->con->commit();
		} 
		catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    public function consultarHistoricoConversa(Request $request) {

    	$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$dado = Chat::consultarHistoricoConversa($param, $this->con);

			$this->con->commit();

			return Response::json($dado);
		} 
		catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }
}