<?php

namespace App\Http\Controllers\Ppcp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DTO\Ppcp\_22060;
use App\Models\DTO\Admin\_11010;
use Illuminate\Support\Facades\View;

/**
 * Controller do objeto 'Gerenciamento de Produção'.
 */
class _22060Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'ppcp/_22060';
	
	/**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		
		_11010::permissaoMenu($this->menu);

		return view('ppcp._22060.index', ['menu' => $this->menu]);
    }
	
    /**
     * Filtrar Estabelecimento e GP.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtrarEstabGp(Request $request) {
		
		if ( $request->ajax() ) {
			
			$obj = new _22060();
			$obj->setEstabelecimentoId($request->estabelecimento_id);
			$obj->setGpId($request->gp_id);
			$obj->setUpId($request->up_id);
			$obj->setDataInicial($request->data_ini);
			$obj->setDataFinal($request->data_fim);
			
			$estab_dado		= _22060::filtrarEstabelecimento($obj);
			$gp_dado		= _22060::filtrarGp($obj);

			return View::make('ppcp._22060.index.panel-estab')
							->with('menu', $this->menu)
							->with('estabelecimento', $estab_dado)
							->with('gp', $gp_dado)
							->render();
    
    	}
    }
	
	/**
     * Filtrar UP.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtrarUp(Request $request) {
		
		if ( $request->ajax() ) {
			
			$obj = new _22060();
			$obj->setEstabelecimentoId($request->estabelecimento_id);
			$obj->setGpId($request->gp_id);
			$obj->setUpId($request->up_id);
			$obj->setDataInicial($request->data_ini);
			$obj->setDataFinal($request->data_fim);
			
			$up_dado		= _22060::filtrarUp($obj);

			return View::make('ppcp._22060.index.panel-up')
							->with('menu', $this->menu)
							->with('up', $up_dado)
							->render();
    
    	}
    }
	
	/**
     * Filtrar Estação.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtrarEstacao(Request $request) {
		
		if ( $request->ajax() ) {
			
			$obj = new _22060();
			$obj->setEstabelecimentoId($request->estabelecimento_id);
			$obj->setGpId($request->gp_id);
			$obj->setUpId($request->up_id);
			$obj->setDataInicial($request->data_ini);
			$obj->setDataFinal($request->data_fim);
			
			$estacao_dado	= _22060::filtrarEstacao($obj);

			return View::make('ppcp._22060.index.panel-estacao')
							->with('menu', $this->menu)
							->with('dividir_estacao', $request->dividir_estacao)
							->with('estacao', $estacao_dado)
							->render();
    
    	}
    }
	
	/**
     * Filtrar Talão.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtrarTalao(Request $request) {
		
		if ( $request->ajax() ) {
			
			$obj = new _22060();
			$obj->setEstabelecimentoId($request->estabelecimento_id);
			$obj->setGpId($request->gp_id);
			$obj->setUpId($request->up_id);
			$obj->setEstacaoId($request->estacao_id);
			$obj->setDataInicial($request->data_ini);
			$obj->setDataFinal($request->data_fim);
			
			$talao = _22060::filtrarTalao($obj);

			return View::make('ppcp._22060.index.panel-talao')
							->with('menu', $this->menu)
							->with('talao', $talao)
							->render();
    
    	}
    }
	
	/**
     * Filtrar Talão Detalhado.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtrarTalaoDetalhe(Request $request) {
		
		if ( $request->ajax() ) {
			
			$obj = new _22060();
			$obj->setEstabelecimentoId($request->estabelecimento_id);
			$obj->setGpId($request->gp_id);
			$obj->setUpId($request->up_id);
			$obj->setTalaoId($request->talao_id);
			$obj->setDataInicial($request->data_ini);
			$obj->setDataFinal($request->data_fim);
			
			$talao_detalhe	= _22060::filtrarTalaoDetalhe($obj);

			return View::make('ppcp._22060.index.panel-talao-detalhe')
							->with('menu', $this->menu)
							->with('talao_detalhe', $talao_detalhe)
							->render();
    
    	}
    }
	
}
