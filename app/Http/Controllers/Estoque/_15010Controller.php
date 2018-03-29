<?php

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DTO\Estoque\_15010;
use App\Models\DTO\Admin\_11010;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Lang;

/**
 * Controller do objeto 'Requisição de Consumo'.
 */
class _15010Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'estoque/_15010';

	
	/**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		
		$permissaoMenu	= _11010::permissaoMenu($this->menu);
		$estab_perm		= _11010::estabPerm();

		$filtro_obj 	= isset($_GET['filtro'])   ? $_GET['filtro'] 	: '';
		$status 		= isset($_GET['status'])   ? $_GET['status'] 	: '1';
		$estab 			= isset($_GET['estab'])    ? $_GET['estab'] 	: '';
		$data_ini		= isset($_GET['data_ini']) ? $_GET['data_ini'] 	: '';
		$data_fim		= isset($_GET['data_fim']) ? $_GET['data_fim'] 	: '';

    	//$dados			= _15010::listar($estab_perm);
		
		//formata campos numéricos
		// foreach ($dados as $dado) {
		// 	$dado->QUANTIDADE	= number_format($dado->QUANTIDADE, 4, ',', '.');
		// 	$dado->SALDO		= number_format($dado->SALDO, 4, ',', '.');
		// }

		return view('estoque._15010.index', [
			// 'dados'			=> $dados,
			'permissaoMenu' => $permissaoMenu,
			'menu'			=> $this->menu,
			'filtro_obj' 	=> $filtro_obj,
			'status'		=> $status,
			'estab'			=> $estab,
			'data_ini'		=> $data_ini,
			'data_fim'		=> $data_fim
		]);
    }
	
	/**
     * Exibe o formulário de criação.
     *
     * @return \Illuminate\Http\Response
     */
	public function create() {
		
		_11010::permissaoMenu($this->menu, 'INCLUIR');
		
        $familias_requisicao = _15010::selectPermissao('1');

    	return view(
			'estoque._15010.create', [
				'menu'                  => $this->menu,
                'familias_requisicao'   => $familias_requisicao
			]
		);
	}
	
	/**
     * Grava os dados no banco de dados.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
	public function store(Request $request) {
		
    	_11010::permissaoMenu($this->menu, 'INCLUIR','Fixando Dados');

    	$obj = new _15010();
		
		$obj->setUsuarioId(Auth::user()->CODIGO);
    	$obj->setData($request->data);
		$obj->setEstabelecimentoId($request->_input_estab);
		$obj->setCcusto($request->_ccusto_id);
		$obj->setTurno($request->turno);
//		$obj->setProdutoId($request->_produto_id);
//		$obj->setQuantidade(Helpers::formataNumPadrao($request->qtd));
//		$obj->setTamanho( empty($request->tamanho) ? 0 : $request->tamanho );
//		$obj->setObservacao( empty($request->obs) ? '' : $request->obs );
//		$obj->setSaldo( empty($request->qtd) ? 0 : Helpers::formataNumPadrao($request->qtd));
    	$obj->setFlag( empty($request->flag_baixa_requisicao) ? 0 : $request->flag_baixa_requisicao );
        $obj->setOperacao( empty($request->_operacao_cod[0]) ? '' : $request->_operacao_cod[0] );
        $obj->setLocalizacao( empty($request->loc[0]) ? 0 : $request->loc[0] );
		
		$i = 0;
		
        foreach ($request->_produto_id as $prod_id) {

			$obj->setProdutoId($prod_id);
			$obj->setQuantidade( Helpers::formataNumPadrao($request->qtd[$i]) );
			$obj->setTamanho( empty($request->tamanho[$i]) ? 0 : $request->tamanho[$i] );
			$obj->setObservacao( empty($request->obs[$i]) ? '' : $request->obs[$i] );
			$obj->setSaldo( empty($request->qtd[$i]) ? 0 : Helpers::formataNumPadrao($request->qtd[$i]) );

            $i++;
			
        }		

		return Response::json( _15010::gravar($obj) );
		
    }
	
	/**
     * Exibe os dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$permissaoMenu = _11010::permissaoMenu($this->menu,null,'Visualizar item: '.$id);
					
		$pu214 = _11010::controle(214);
    	$dados = _15010::exibir($id);
			
		//formata campos numéricos
		$dados['dado']->QUANTIDADE		= number_format($dados['dado']->QUANTIDADE, 4, ',', '.');
		$dados['dado']->PRODUTO_SALDO	= number_format($dados['dado']->PRODUTO_SALDO, 4, ',', '.');

    	return view(
			'estoque._15010.show', [
				'dado'                  => $dados['dado'],
				'permissaoMenu'         => $permissaoMenu,
				'pu214'					=> $pu214,
				'menu'                  => $this->menu,
			]
		);
    }
	
	/**
     * Exibe o formulário para edição de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		_11010::permissaoMenu($this->menu, 'ALTERAR');
    	
    	$dados = _15010::exibir($id);
			
		//formata campos numéricos
		$dados['dado']->QUANTIDADE		= number_format($dados['dado']->QUANTIDADE, 4, ',', '.');
		$dados['dado']->PRODUTO_SALDO	= number_format($dados['dado']->PRODUTO_SALDO, 4, ',', '.');
    	 
    	return view(
			'estoque._15010.edit', [
				'dado'				=> $dados['dado'],
				'menu'				=> $this->menu
			]
		);
    }

    /**
     * Atualiza dados no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		_11010::permissaoMenu($this->menu, 'ALTERAR','Alterando dados');
		
        $obj = new _15010();
    	
    	$obj->setId($id);
		$obj->setUsuarioId(Auth::user()->CODIGO);
    	$obj->setData($request->data);
		$obj->setEstabelecimentoId($request->estab);
		$obj->setCcusto($request->_ccusto_id);
		$obj->setTurno($request->turno);
		$obj->setProdutoId($request->_produto_id);
		$obj->setQuantidade(Helpers::formataNumPadrao($request->qtd));
		$obj->setTamanho($request->tamanho);
		$obj->setObservacao($request->obs);
		$obj->setSaldo(Helpers::formataNumPadrao($request->qtd));
		
        return Response::json( _15010::alterar($obj) );

    }

    /**
     * Excluir dados do banco de dados.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	_11010::permissaoMenu($this->menu, 'EXCLUIR','Excluir item');
    	
    	_15010::excluir($id);
    	
    	Session::flash('flash_message', 'Requisição excluída com sucesso.');
    	
    	return redirect('_15010');
    }
	
	/**
     * Encerrar/desencerrar requisição.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function encerrar(Request $request)
    {
		_11010::permissaoMenu($this->menu, 'ALTERAR','Encerrando requisição');
		
        $obj = new _15010();
    	$obj->setId($request->requisicao_id);
		$obj->setStatus($request->status);
		
        return Response::json( _15010::encerrar($obj) );

    }
	
	/**
     * Paginação com scroll.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function paginacaoScroll(Request $request) {
		
		$protocol = protocol();

        if ($request->ajax()) {

			$estab_perm	= _11010::estabPerm();
			$data_ini	= empty($request->data_ini) ? '' : $request->data_ini.' 00:00:00';
			$data_fim	= empty($request->data_fim) ? '' : $request->data_fim.' 23:59:59';
			
            $dados = _15010::paginacaoScroll(
						$request->qtd_por_pagina, 
						$request->pagina,
						$request->filtro,
						$estab_perm,
						$request->status,
						$request->estab,
						$data_ini,
						$data_fim
					);

            $res = '';
            foreach ($dados as $dado) {

                $res .= '<tr link="'.$protocol.'://' . $_SERVER['HTTP_HOST'] . '/_15010/' . $dado->ID . '">';
				
				$res .= '<td class="status status-'.trim($dado->STATUS).'"><span class="fa fa-circle" title="'. Lang::get($this->menu.'.status-'.trim($dado->STATUS)) .'"></span></td>';
				$res .= '<td class="req-id">' . $dado->ID . '</td>';
				$res .= '<td>' . date_format(date_create($dado->DATA), 'd/m/Y H:i:s') . '</td>';
				$res .= '<td>' . $dado->USUARIO_DESCRICAO . '</td>';
				$res .= '<td class="text-right">' . $dado->ESTABELECIMENTO_ID . '</td>';
				$res .= '<td>' . $dado->CCUSTO .' - '. $dado->CCUSTO_DESCRICAO . '</td>';
				$res .= '<td>' . $dado->TURNO_ID .' - '.$dado->TURNO_DESCRICAO . '</td>';
				$res .= '<td class="req-produto">' . $dado->PRODUTO_ID .' - '. $dado->PRODUTO_DESCRICAO .' ('. $dado->UM .')</td>';
				$res .= '<td class="text-right req-qtd">' . number_format($dado->QUANTIDADE, 4, ',', '.'). '</td>';
				$res .= '<td class="text-right">' . $dado->TAMANHO_DESCRICAO . '</td>';
				$res .= '<td>' . $dado->OBSERVACAO . '</td>';
				$res .= '<td class="text-right req-saldo">' . number_format($dado->SALDO, 4, ',', '.') . '</td>';
				
                $res .= '</tr>';
            }

            return $res;
        }
    }

    /**
     * Filtrar lista de requisições.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtrar(Request $request) {
		
		$protocol = protocol();

		if ( $request->ajax() ) {
    
			$estab_perm	= _11010::estabPerm();
    		$data_ini	= empty($request->data_ini) ? '' : $request->data_ini.' 00:00:00';
			$data_fim	= empty($request->data_fim) ? '' : $request->data_fim.' 23:59:59';
			
    		$dados = _15010::filtrar(
						$request->filtro,
						$estab_perm,
						$request->status,
						$request->estab,
						$data_ini,
						$data_fim
					);
			
    		if( !empty($dados) ) {
    
    			$res = '';
    			foreach ($dados as $dado) {

					$res .= '<tr link="'.$protocol.'://' . $_SERVER['HTTP_HOST'] . '/_15010/' . $dado->ID . '">';
					
					$res .= '<td class="status status-'.trim($dado->STATUS).'"><span class="fa fa-circle" title="'. Lang::get($this->menu.'.status-'.trim($dado->STATUS)) .'"></span></td>';
					$res .= '<td class="req-id">' . $dado->ID . '</td>';
					$res .= '<td>' . date_format(date_create($dado->DATA), 'd/m/Y H:i:s') . '</td>';
					$res .= '<td>' . $dado->USUARIO_DESCRICAO . '</td>';
					$res .= '<td class="text-right">' . $dado->ESTABELECIMENTO_ID . '</td>';
					$res .= '<td>' . $dado->CCUSTO .' - '. $dado->CCUSTO_DESCRICAO . '</td>';
					$res .= '<td>' . $dado->TURNO_ID .' - '.$dado->TURNO_DESCRICAO . '</td>';
					$res .= '<td class="req-produto">' . $dado->PRODUTO_ID .' - '. $dado->PRODUTO_DESCRICAO .' ('. $dado->UM . ')</td>';
					$res .= '<td class="text-right req-qtd">' . number_format($dado->QUANTIDADE, 4, ',', '.'). '</td>';
					$res .= '<td class="text-right">' . $dado->TAMANHO_DESCRICAO . '</td>';
					$res .= '<td>' . $dado->OBSERVACAO . '</td>';
					$res .= '<td class="text-right req-saldo">' . number_format($dado->SALDO, 4, ',', '.') . '</td>';

					$res .= '</tr>';
    
    			}
    				
    			return $res;
    		}
    		else { 
				echo '<tr link="#"><td colspan="16" class="filtro-vazio">Sua busca não retornou nenhum resultado.</td></tr>';
			}
    
    	}
    }

}
