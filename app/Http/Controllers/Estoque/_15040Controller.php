<?php

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DTO\Estoque\_15010;
use App\Models\DTO\Estoque\_15040;
use App\Models\DTO\Admin\_11010;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

/**
 * Controller do objeto 15040 - Baixa de estoque.
 */
class _15040Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'estoque/_15040';
	
	/**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		
		_11010::permissaoMenu($this->menu, 'INCLUIR');
		
		$estab_perm	= _11010::estabPerm();
    	// $dados		= _15040::listarRequisicao($estab_perm);
        
        $familias_baixa = _15010::selectPermissao('2');

        $filtro_obj 	= isset($_GET['filtro'])   ? $_GET['filtro'] 	: '';
		$status 		= isset($_GET['status'])   ? $_GET['status'] 	: '1';
		$estab 			= isset($_GET['estab'])    ? $_GET['estab'] 	: '';
		$data_ini		= isset($_GET['data_ini']) ? $_GET['data_ini'] 	: '';
		$data_fim		= isset($_GET['data_fim']) ? $_GET['data_fim'] 	: '';
		
		//formata campos numéricos
		// foreach ($dados as $dado) {
		// 	$dado->QUANTIDADE	= number_format($dado->QUANTIDADE, 4, ',', '.');
		// 	$dado->SALDO		= number_format($dado->SALDO, 4, ',', '.');
		// }

		return view('estoque._15040.index', [
			// 'dados'             => $dados,
			'menu'              => $this->menu,
            'familias_baixa'    => $familias_baixa,
            'filtro_obj' 		=> $filtro_obj,
			'status'			=> $status,
			'estab'				=> $estab,
			'data_ini'			=> $data_ini,
			'data_fim'			=> $data_fim
		]);
    }
	
	/**
     * Exibe o formulário de criação.
     *
     * @return \Illuminate\Http\Response
     */
	public function create() {
		
		$permissaoMenu	= _11010::permissaoMenu($this->menu);
		
		//$estab_perm		= _11010::estabPerm();
    	// $dados = _15040::listar();
		
		//formata campos numéricos
		// foreach ($dados as $dado) {
		// 	$dado->QUANTIDADE	= number_format($dado->QUANTIDADE, 4, ',', '.');
		// }

		$filtro_obj 	= isset($_GET['filtro'])   ? $_GET['filtro'] 	: '';
		$estab 			= isset($_GET['estab'])    ? $_GET['estab'] 	: '';
		$data_ini		= isset($_GET['data_ini']) ? $_GET['data_ini'] 	: '';
		$data_fim		= isset($_GET['data_fim']) ? $_GET['data_fim'] 	: '';
		
    	return view(
			'estoque._15040.create', [
				// 'dados'			=> $dados,
				'menu'			=> $this->menu,
				'permissaoMenu' => $permissaoMenu,
				'filtro_obj' 	=> $filtro_obj,
				'estab'			=> $estab,
				'data_ini'		=> $data_ini,
				'data_fim'		=> $data_fim
			]
		);
	}
	
	/**
     * Realiza a baixa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		
		_11010::permissaoMenu($this->menu, 'INCLUIR','Fixando Dados');
		
		$obj = new _15040();
    	
		$i = 0;
		foreach ($request->req_id as $req_id) {
			
			$saldo_ant	= Helpers::formataNumPadrao($request->req_saldo[$i]);
			$baixa		= $request->req_baixar[$i];
			$saldo		= $saldo_ant - $baixa;
			
			$obj->setRequisicaoId($req_id);	
			$obj->setSaldo($saldo);
			$obj->setUsuarioId(Auth::user()->CODIGO);
//			$obj->setOperacaoCodigo($request->_operacao_cod[$i]);
			$obj->setOperacaoCodigo($request->_operacao_requisicao[$i]);
			$obj->setLocalizacaoId($request->loc[$i]);
			$obj->setQuantidade($baixa);
			
			$i++;
		}
		
        return Response::json( _15040::gravar($obj) );

    }

	/**
     * Exibe os dados da Baixa.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$permissaoMenu = _11010::permissaoMenu($this->menu,null,'Visualizar item: '.$id);
		
    	$dados = _15040::exibir($id);
		
		if( empty($dados['dado']) ) {
			log_erro('Baixa não existe ou você não tem permissão para visualizá-la.');
		}

    	return view(
			'estoque._15040.show', [
				'dado'				=> $dados['dado'],
				'permissaoMenu'		=> $permissaoMenu,
				'menu'				=> $this->menu
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
		
    	$dados = _15040::exibir($id);
		
		if( empty($dados['dado']) ) {
			log_erro('Baixa não existe ou você não tem permissão para visualizá-la.');
		}

    	return view(
			'estoque._15040.edit', [
				'dado'				=> $dados['dado'],
				'menu'				=> $this->menu
			]
		);
    }
	
    /**
     * Atualiza dados no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
	 * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
		
		_11010::permissaoMenu($this->menu, 'ALTERAR','Alterando dados');
		
		$obj = new _15040();
    	
		$obj->setId($id);
		$obj->setUsuarioId(Auth::user()->CODIGO);
		$obj->setLocalizacaoId($request->loc);
		$obj->setOperacaoCodigo($request->_operacao_cod);
		$obj->setQuantidade($request->qtd);
		
        return Response::json( _15040::alterar($obj) );

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
    	
    	_15040::excluir($id);
    	
    	Session::flash('flash_message', 'Baixa excluída com sucesso.');
    	
    	return redirect('_15040');
    }
	
	/**
     * Paginação com scroll.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function paginacaoScroll(Request $request) {
		
        if ($request->ajax()) {
			
			$estab_perm	= _11010::estabPerm();
			$data_ini	= empty($request->data_ini) ? '' : $request->data_ini.' 00:00:00';
			$data_fim	= empty($request->data_fim) ? '' : $request->data_fim.' 23:59:59';
			
            $dados = _15040::paginacaoScroll(
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
            $i = 0;
            foreach ($dados as $dado) {
				
				$status		= trim($dado->STATUS);
				$disabled	= ($status != 1) ? 'disabled' : '';

				$res .= '<tr>';

				$res .= '<td class="'. $disabled .'" title="'. Lang::get($this->menu.'.chk-disabled-title-'.$status) .'">';
				$res .= '<input type="checkbox" class="chk-req-selec" '. $disabled .' />';
				$res .= '</td>';
				$res .= '<td class="status status-'. $status .'">';
				$res .= '<span class="fa fa-circle" title="'. Lang::get($this->menu.'.status-'.$status) .'"></span>';
				$res .=	'</td>';
				$res .= '<td class="req-id">' . $dado->ID . '</td>';
				$res .= '<td>' . date_format(date_create($dado->DATA), 'd/m/Y H:i:s') . '</td>';
				$res .= '<td>' . $dado->USUARIO_DESCRICAO . '</td>';
				$res .= '<td class="text-right">' . $dado->ESTABELECIMENTO_ID . '</td>';
				$res .= '<td class="text-right">' . $dado->DOCUMENTO . '</td>';
				$res .= '<td>' . $dado->CCUSTO .' - '. $dado->CCUSTO_DESCRICAO . '</td>';
				$res .= '<td>' . $dado->TURNO_ID .' - '.$dado->TURNO_DESCRICAO . '</td>';
				$res .= '<td class="text-right">' . $dado->FAMILIA_ID . '</td>';
				$res .= '<td class="req-produto">' . $dado->PRODUTO_ID .' - '. $dado->PRODUTO_DESCRICAO . ' ('. $dado->UM .')</td>';
				$res .= '<td class="text-right req-qtd">' . number_format($dado->QUANTIDADE, 4, ',', '.'). '</td>';
				$res .= '<td class="text-right">' . $dado->TAMANHO_DESCRICAO . '</td>';
				$res .= '<td>' . $dado->OBSERVACAO . '</td>';
				$res .= '<td class="text-right req-saldo">' . number_format($dado->SALDO, 4, ',', '.') . '</td>';
				
				$res .= '<input type="hidden" class="_req_produto_id" value="'.$dado->PRODUTO_ID.'" />';
				$res .= '<input type="hidden" class="_req_localizacao_padrao" value="'.$dado->LOCALIZACAO_PADRAO.'" />';
				$res .= '<input type="hidden" class="_operacao_requisicao" value="'.$dado->OPERACAO_REQUISICAO.'" />';

				$res .= '</tr>';

				$i++;
            }

            return $res;
        }
    }

	/**
     * Paginação com scroll (Baixa).
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function paginacaoScrollBaixa(Request $request) {
		
		$protocol = protocol();

        if ($request->ajax()) {
			
			$estab_perm	= _11010::estabPerm();
			$data_ini	= empty($request->data_ini) ? '' : $request->data_ini.' 00:00:00';
			$data_fim	= empty($request->data_fim) ? '' : $request->data_fim.' 23:59:59';
			
            $dados = _15040::paginacaoScrollBaixa(
						$request->qtd_por_pagina, 
						$request->pagina,
						$request->filtro,
						$estab_perm,
						$request->estab,
						$data_ini,
						$data_fim
					);

            $res = '';
            $i = 0;
            foreach ($dados as $dado) {

                $res .= '<tr link="'.$protocol.'://' . $_SERVER['HTTP_HOST'] . '/_15040/' . $dado->ID . '">';
				$res .= '<td>'. $dado->ID .'</td>';
				$res .= '<td>'. $dado->REQUISICAO_ID .'</td>';
				$res .= '<td>'. date_format(date_create($dado->DATAHORA), 'd/m/Y H:i:s') .'</td>';
				$res .= '<td class="text-right">'. $dado->ESTABELECIMENTO_ID .'</td>';
				$res .= '<td class="text-right">'. $dado->LOCALIZACAO_ID .'</td>';
				$res .= '<td>'. $dado->REQUERENTE_DESCRICAO .'</td>';
				$res .= '<td>'. $dado->CCUSTO .'</td>';
				$res .= '<td>'. $dado->PRODUTO_ID .' - '. $dado->PRODUTO_DESCRICAO .' ('. $dado->UM .')</td>';
				$res .= '<td class="text-right">'. $dado->TAMANHO_DESCRICAO .'</td>';
				$res .= '<td class="text-right">'. number_format($dado->QUANTIDADE, 4, ',', '.') .'</td>';
				$res .= '<td>'. $dado->OPERACAO_CODIGO .'</td>';
				$res .= '<td>'. $dado->USUARIO_DESCRICAO .'</td>';
				$res .= '<td class="text-right">'. $dado->ESTOQUE_ID .'</td>';
				$res .= '</tr>';

				$i++;
            }

            return $res;
        }
    }
	
    /**
     * Filtrar lista de requisições de consumo.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtrar(Request $request) {
		
        if ( $request->ajax() ) {
    
			$estab_perm	= _11010::estabPerm();
			$data_ini	= empty($request->data_ini) ? '' : $request->data_ini.' 00:00:00';
			$data_fim	= empty($request->data_fim) ? '' : $request->data_fim.' 23:59:59';
			
    		$dados = _15040::filtrar(
						$request->filtro,
						$estab_perm,
						$request->status,
						$request->estab,
						$data_ini,
						$data_fim
					);
			
    		if( !empty($dados) ) {
    
    			$res = '';
    			$i = 0;
    			foreach ($dados as $dado) {
					
					$status		= trim($dado->STATUS);
					$disabled	= ($status != 1) ? 'disabled' : '';
							
					$res .= '<tr>';
					
					$res .= '<td class="'. $disabled .'" title="'. Lang::get($this->menu.'.chk-disabled-title-'.$status) .'">';
					$res .= '<input type="checkbox" class="chk-req-selec" '. $disabled .' />';
					$res .= '</td>';
					$res .= '<td class="status status-'. $status .'">';
					$res .= '<span class="fa fa-circle" title="'. Lang::get($this->menu.'.status-'.$status) .'"></span>';
					$res .=	'</td>';
					$res .= '<td class="req-id">' . $dado->ID . '</td>';
					$res .= '<td>' . date_format(date_create($dado->DATA), 'd/m/Y H:i:s') . '</td>';
					$res .= '<td>' . $dado->USUARIO_DESCRICAO . '</td>';
					$res .= '<td class="text-right">' . $dado->ESTABELECIMENTO_ID . '</td>';
					$res .= '<td class="text-right">' . $dado->DOCUMENTO . '</td>';
					$res .= '<td>' . $dado->CCUSTO .' - '. $dado->CCUSTO_DESCRICAO . '</td>';
					$res .= '<td>' . $dado->TURNO_ID .' - '.$dado->TURNO_DESCRICAO . '</td>';
					$res .= '<td class="text-right">' . $dado->FAMILIA_ID . '</td>';
					$res .= '<td class="req-produto">' . $dado->PRODUTO_ID .' - '. $dado->PRODUTO_DESCRICAO . ' ('. $dado->UM .')</td>';
					$res .= '<td class="text-right req-qtd">' . number_format($dado->QUANTIDADE, 4, ',', '.'). '</td>';
					$res .= '<td class="text-right">' . $dado->TAMANHO_DESCRICAO . '</td>';
					$res .= '<td>' . $dado->OBSERVACAO . '</td>';
					$res .= '<td class="text-right req-saldo">' . number_format($dado->SALDO, 4, ',', '.') . '</td>';
                    
                    $res .= '<input type="hidden" class="_req_produto_id" value="'.$dado->PRODUTO_ID.'" />';
                    $res .= '<input type="hidden" class="_req_localizacao_padrao" value="'.$dado->LOCALIZACAO_PADRAO.'" />';
                    $res .= '<input type="hidden" class="_operacao_requisicao" value="'.$dado->OPERACAO_REQUISICAO.'" />';
                            
                    $res .= '</tr>';
					
    				$i++;
    
    			}
    				
    			return Response::json($res);
    		}
    		else { 
				echo '<tr link="#"><td colspan="16" class="filtro-vazio">Sua busca não retornou nenhum resultado.</td></tr>';
			}
    
    	}
    }

	/**
     * Filtrar lista de requisições de consumo.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtrarBaixa(Request $request) {
		
		$protocol = protocol();

        if ( $request->ajax() ) {
    
			$estab_perm	= _11010::estabPerm();
			$data_ini	= empty($request->data_ini) ? '' : $request->data_ini.' 00:00:00';
			$data_fim	= empty($request->data_fim) ? '' : $request->data_fim.' 23:59:59';
			
    		$dados = _15040::filtrarBaixa(
						$request->filtro,
						$estab_perm,
						$request->estab,
						$data_ini,
						$data_fim
					);
			
    		if( !empty($dados) ) {
    
    			$res = '';
    			$i = 0;
    			foreach ($dados as $dado) {
					
					$res .= '<tr link="'.$protocol.'://' . $_SERVER['HTTP_HOST'] . '/_15040/' . $dado->ID . '">';
					$res .= '<td>'. $dado->ID .'</td>';
					$res .= '<td>'. $dado->REQUISICAO_ID .'</td>';
					$res .= '<td>'. date_format(date_create($dado->DATAHORA), 'd/m/Y H:i:s') .'</td>';
					$res .= '<td class="text-right">'. $dado->ESTABELECIMENTO_ID .'</td>';
					$res .= '<td class="text-right">'. $dado->LOCALIZACAO_ID .'</td>';
					$res .= '<td>'. $dado->REQUERENTE_DESCRICAO .'</td>';
					$res .= '<td>'. $dado->CCUSTO .'</td>';
					$res .= '<td>'. $dado->PRODUTO_ID .' - '. $dado->PRODUTO_DESCRICAO .' ('. $dado->UM .')</td>';
					$res .= '<td class="text-right">'. $dado->TAMANHO_DESCRICAO .'</td>';
					$res .= '<td class="text-right">'. number_format($dado->QUANTIDADE, 4, ',', '.') .'</td>';
					$res .= '<td>'. $dado->OPERACAO_CODIGO .'</td>';
					$res .= '<td>'. $dado->USUARIO_DESCRICAO .'</td>';
					$res .= '<td class="text-right">'. $dado->ESTOQUE_ID .'</td>';
					$res .= '</tr>';
					
    				$i++;
    
    			}
    				
    			return Response::json($res);
    		}
    		else { 
				echo '<tr link="#"><td colspan="10" class="filtro-vazio">Sua busca não retornou nenhum resultado.</td></tr>';
			}
    
    	}
    }
}
