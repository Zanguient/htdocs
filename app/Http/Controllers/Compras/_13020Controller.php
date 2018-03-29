<?php

namespace App\Http\Controllers\Compras;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\DTO\Helper\Email;
use App\Models\DTO\Compras\_13020;
use App\Models\DTO\Admin\_11010;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Exception;


class _13020Controller extends Controller
{
    /**
     * Código do menu
     * @var int 
     */
    private $menu = 'compras/_13020';
    
    /**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$permissaoMenu = _11010::permissaoMenu($this->menu);
    	
    	$dados = _13020::listar();

		return view('compras._13020.index', [
			'dados'			=> $dados,
			'permissaoMenu' => $permissaoMenu
		]);
    }

    /**
     * Exibe o formulário de criação.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	_11010::permissaoMenu($this->menu, 'INCLUIR');
    	
    	$req = _13020::listaRequisicaoPendente();
		
		//formata campos numéricos
		foreach ($req['req_item'] as $r) {
			$r->QUANTIDADE		= number_format($r->QUANTIDADE, 4, ',', '.');
			$r->VALOR_UNITARIO	= number_format($r->VALOR_UNITARIO, 4, ',', '.');
		}
		
    	return view(
			'compras._13020.create', [
				'req'		=> $req['req'], 
				'req_item'	=> $req['req_item']
			]
		);
    }

    /**
     * Grava os dados no banco de dados.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
	public function store(Request $request)
    {    	
    	_11010::permissaoMenu($this->menu, 'INCLUIR','Fixando Dados');
    	
    	$licitacao_id = _13020::gerarId();

    	$obj = new _13020();
    	
    	$obj->setLicitacaoId($licitacao_id[0]->ID);
    	$obj->setLicitacaoDescricao($request->descricao);
    	$obj->setUsuarioId(Auth::user()->CODIGO);
    	$obj->setDataValidade($request->validade);    	
    	$obj->setObservacao($request->observacao);
    	
    	foreach ($request->_empresa_id as $emp_id) {
    		
    		$orcamento_id = _13020::gerarIdOrcamento();
			
    		$obj->setOrcamentoId($orcamento_id);
    		$obj->setEmpresaId($emp_id);    	
    	}
    	
    	$i = 0;
    	foreach ($request->_produto_id as $prod_id) {  			   		
    		
    		$obj->setRequisicaoId($request->_requisicao_id[$i]);
    		$obj->setProdutoId($prod_id);
			$obj->setUm($request->_produto_um[$i]);
			$obj->setTamanho($request->_produto_tam[$i]);
    		$obj->setQuantidade(Helpers::formataNumPadrao($request->_produto_qtd[$i]));
			$obj->setProdutoInfo($request->_produto_obs[$i]);
			$obj->setOperacaoCodigo($request->_operacao_codigo[$i]);
			$obj->setOperacaoCcusto($request->_operacao_ccusto[$i]);
			$obj->setOperacaoCcontabil($request->_operacao_ccontabil[$i]);
    		
    		$i++;
    		 
    	}
    	
		return Response::json( _13020::gravar($obj) );
		
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
		
    	$dados = _13020::exibir($id);  
		
		foreach ($dados['orcamento'] as $o) {
			$o->FONE              = Helpers::maskFone($o->FONE);
			$o->ORCAMENTO_ENCRYPT = Helpers::encrypt($o->ORCAMENTO_ENCRYPT);            
		}
		
		//formata campos numéricos
		foreach ($dados['orcamento_item'] as $o) {
			$o->QUANTIDADE		= number_format($o->QUANTIDADE, 4, ',', '.');
			$o->VALOR_UNITARIO	= number_format($o->VALOR_UNITARIO, 4, ',', '.');
		}
    	
    	return view(
				'compras._13020.show', [
    				'licitacao' 	 => $dados['licitacao'][0],
					'requisicao'	 => $dados['requisicao'],
    				'orcamento' 	 => $dados['orcamento'], 
    				'orcamento_item' => $dados['orcamento_item'],
    				'ocs'            => $dados['oc'],
    				'permissaoMenu'	 => $permissaoMenu
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
    	
    	$dados = _13020::exibir($id);
        
		//hash id
		foreach ($dados['orcamento'] as $o) {
			$o->ORCAMENTO_ENCRYPT = Helpers::encrypt($o->ORCAMENTO_ENCRYPT);
		}
        
		//formata campos numéricos
		foreach ($dados['orcamento_item'] as $o) {
			$o->QUANTIDADE		= number_format($o->QUANTIDADE, 4, ',', '.');
			$o->VALOR_UNITARIO	= number_format($o->VALOR_UNITARIO, 4, ',', '.');
		}
		
		//Requisições pendentes
    	$req = _13020::listaRequisicaoPendente();
		
		//formata campos numéricos
		foreach ($req['req_item'] as $r) {
			$r->QUANTIDADE		= number_format($r->QUANTIDADE, 4, ',', '.');
			$r->VALOR_UNITARIO	= number_format($r->VALOR_UNITARIO, 4, ',', '.');
		}
    	 
    	return view(
			'compras._13020.edit', [
				'licitacao' 		=> $dados['licitacao'][0],
				'requisicao'		=> $dados['requisicao'],
				'orcamento' 		=> $dados['orcamento'],
				'orcamento_item' 	=> $dados['orcamento_item'],
				'req' 				=> $req['req'], 
				'req_item' 			=> $req['req_item']
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
		_11010::permissaoMenu($this->menu, 'ALTERAR','Fixando Dados');
		
        //Verifica se já existem OC's geradas para esta licitação
        $oc = _13020::exibirOc($id);
        if ( count($oc) > 0 ) {
            throw new Exception("Operação bloqueada! Já existem OC's para esta licitação", 99998);
        }
        
    	$obj = new _13020();
    	
    	$obj->setLicitacaoId($id);
    	$obj->setLicitacaoDescricao($request->descricao);
    	$obj->setUsuarioId(Auth::user()->CODIGO);
    	$obj->setDataValidade($request->validade);    	
    	$obj->setObservacao($request->observacao);
    	
    	$i = 0;
    	foreach ($request->_empresa_id as $emp_id) {
    		
    		$obj->setOrcamentoId($request->_orcamento_id[$i]);	//utilizado também ao alterar para verificar se o fornecedor já está na licitação
    		$obj->setEmpresaId($emp_id);
			
			$obj->setEmpresaExcluir($request->_empresa_excluir[$i] ? $request->_empresa_excluir[$i] : 0);
    		
    		$i++;
    	}
    	
    	$i = 0;
    	foreach ($request->_prod_id as $prod_id) {  			   		
    		
    		$obj->setRequisicaoId($request->_requisicao_id[$i]);
    		$obj->setProdutoId($prod_id);
			$obj->setUm($request->_prod_um[$i]);
			$obj->setTamanho($request->_prod_tamanho[$i]);
    		$obj->setQuantidade(Helpers::formataNumPadrao($request->_prod_qtd[$i]));
			$obj->setProdutoInfo($request->_produto_obs[$i]);
			$obj->setOperacaoCodigo($request->_operacao_codigo[$i]);
			$obj->setOperacaoCcusto($request->_operacao_ccusto[$i]);
			$obj->setOperacaoCcontabil($request->_operacao_ccontabil[$i]);
			
    		//$obj->setValorUnitario($request->_prod_valor[$i]);
    		$obj->setProdutoLicitacao($request->_prod_licitacao[$i]);	//utilizando apenas ao alterar para verificar se o produto já existe na licitação
    		$obj->setRequisicaoItemId($request->_req_item_id[$i] ? $request->_req_item_id[$i] : 0);	//utilizando apenas ao alterar para verificar se o produto já existe na licitação
    		
			$obj->setProdutoExcluir($request->_produto_excluir[$i] ? $request->_produto_excluir[$i] : 0);
    		
    		$i++;
    	
    	}
    	 
        return Response::json( _13020::alterar($obj) );

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
    	
    	_13020::excluir($id);
    	
    	Session::flash('flash_message_error flash_message', 'Orçamento excluído com sucesso.');
    	
    	return redirect('_13020');
    }
    
    /**
     * Paginação com scroll.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function paginacaoScroll(Request $request)
    {
        $protocol = protocol();
		
    	if ( $request->ajax() ) {
    
    		$dados = _13020::paginacaoScroll($request->get('qtd_por_pagina'), $request->get('pagina'));

			$res = '';
			$i = 0;
			foreach ($dados as $dado) {

				$res .= '<tr link="'.$protocol.'://'.$_SERVER['HTTP_HOST'].'/_13020/'.$dado->ID .'">';
				$res .= '<td>'.$dado->ID.'</td>';
				$res .= '<td>'. $dado->DESCRICAO .'</td>';
				$res .= '<td>'. $dado->FAMILIAS .'</td>';
				$res .= '<td>'.date_format(date_create($dado->DATAHORA), 'd/m/Y').'</td>';
				$res .= '<td>'.date_format(date_create($dado->DATA_VALIDADE), 'd/m/Y').'</td>';
				$res .= '<td>'.$dado->REQUERENTE.'</td>';
				$res .= '</tr>';

				$i++;

			}
			
			return utf8_encode($res);
    	}
    }
    
    /**
     * Filtrar lista de orçamentos.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtraObj(Request $request)
    {
        $protocol = protocol();
		
    	if ( $request->ajax() ) {
    
    		$dados = _13020::filtraObj($request->get('filtro'));
    
    		if( !empty($dados) ) {
    
    			$res = '';
    			$i = 0;
    			foreach ($dados as $dado) {
    
					$res .= '<tr link="'.$protocol.'://'.$_SERVER['HTTP_HOST'].'/_13020/'.$dado->ID .'">';
					$res .=	'<td>'. $dado->ID .'</td>';
					$res .= '<td>'. $dado->DESCRICAO .'</td>';
					$res .= '<td>'. $dado->FAMILIAS .'</td>';
					$res .=	'<td>'. date_format(date_create($dado->DATAHORA), 'd/m/Y') .'</td>';
					$res .=	'<td>'. date_format(date_create($dado->DATA_VALIDADE), 'd/m/Y') .'</td>';
					$res .=	'<td>'. $dado->REQUERENTE .'</td>';
					$res .=	'</tr>';
					
    				$i++;
    
    			}
    				
    			return Response::json($res);
    		}
    		else { 
				echo '<tr link="#"><td colspan="6" class="filtro-vazio">Sua busca não retornou nenhum resultado.</td></tr>';
			}
    
    	}
    }   
	
	/**
     * Enviar orçamento para os fornecedores por e-mail.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enviarEmail(Request $request)
    {		
		_11010::permissaoMenu($this->menu, 'ALTERAR','Enviando orçamento para o fornecedor por e-mail');
		
    	if ($request->ajax()) {

            $obj = new Email();

            $obj->setEmail($request->email);
            $obj->setUsuarioId($request->usuario_id);
            $obj->setUrl($request->url);
            $obj->setAssunto($request->assunto);
            $obj->setCorpo($request->corpo);
            $obj->setStatus($request->status);
            $obj->setDatahora($request->data_hora);
            $obj->setCodigo($request->codigo);

            return Response::json( Email::gravar($obj) );

    	}
    	
    }
	
	/**
	 * Editar dados do fornecedor.
	 * 
	 * @param Request $request
	 */
	public function editarDadosFornec(Request $request) {
		
		$obj = new _13020();
		$obj->setEmpresaId($request->empresa_id);
		$obj->setEmpresaEmail($request->empresa_email);
		$obj->setEmpresaFone($request->empresa_fone);
		$obj->setEmpresaContato($request->empresa_contato);
		
		_13020::editarDadosFornec($obj);
		
	}

}