<?php

namespace App\Http\Controllers\Compras;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Compras\_13060;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Helpers\Helpers;

class _13060Controller extends Controller
{
		 
    /**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	Log::info('Exibindo todas as Empresas | '. Auth::user()->USUARIO .' | '. \Request::getClientIp());
    	
    	//$dados = _13060::listar();
    	
    	return view('empresa.empresa.index', ['dados' => $dados]);
    }

    /**
     * Exibe o formulário de criação.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	return view('empresa.empresa.create');
    }

    /**
     * Grava os dados no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    	
    	
    	Log::info('Gravando Empresa | '. Auth::user()->USUARIO .' | '. \Request::getClientIp());
    	
    	/*$requisicao_id = _13060::gerarId();

    	$obj = new _13060();
    	$obj->setId($requisicao_id[0]->ID);
    	$obj->setCcusto($request->_ccusto_id);
    	$obj->setUsuarioGestorId($request->_gestor_id);
    	$obj->setUsuarioId(Auth::user()->CODIGO);
    	$obj->setUrgencia($request->urgencia ? '1' : '0');
    	$obj->setEmpresaId($request->_empresa_id ? $request->_empresa_id : 0);
    	$obj->setEmpresaDescricao(!$request->_empresa_id ? $request->empresa_descricao : '');
    	$obj->setEmpresaFone(!$request->_empresa_id ? $request->fone : '');
    	$obj->setEmpresaEmail(!$request->_empresa_id ? $request->email : '');
    	$obj->setEmpresaContato(!$request->_empresa_id ? $request->contato : '');
    	$obj->setData($request->data);
    	$obj->setDataUtilizacao($request->data_utilizacao);

    	$i = 0;
    	
    	foreach ($request->_produto_id as $prod_id ) {    
    		
    		addslashes($request->_produto_descricao[$i]);
    		
    		$obj->setProdutoId($prod_id ? $prod_id : 0);
    		$obj->setProdutoDescricao($request->_produto_descricao[$i] ? $request->_produto_descricao[$i] : '');
    		$obj->setUm($request->um[$i] ? $request->um[$i] : '');
    		$obj->setValorUnitario($request->valor_unitario[$i] ? str_replace(',', '.', str_replace('.', '', $request->valor_unitario[$i])) : 0);
 			$obj->setTamanho($request->tamanho[$i] ? $request->tamanho[$i] : 0);
			$obj->setQuantidade($request->quantidade[$i] ? str_replace(',', '.', str_replace('.', '', $request->quantidade[$i])) : 0);
			
	    	$i++;
	    	
    	}
    	
    	_13060::gravar($obj);
    	
    	
    	//Anexos
    		//$i = 0;
    		//foreach($request->anexo_arquivo as $anexo_arq) {
	    		
//     			$anexo_desc = $request->anexo_descricao[$i] ? $request->anexo_descricao[$i] : pathinfo($anexo_arq, PATHINFO_FILENAME);
//     			$anexo_ext  = pathinfo($anexo_arq, PATHINFO_EXTENSION);
				
    			
//     			print_r($anexo_arq);
// 				exit('out!');
    			
    			//if( empty($anexo_desc) ) break;
    			
    			/*
    			//NOME TEMPORÁRIO
    			$file_tmp = $_FILES["anexo_arquivo"]["tmp_name"];
    			//NOME DO ARQUIVO NO COMPUTADOR
    			$file_name = $_FILES["anexo_arquivo"]["name"];
    			//TAMANHO DO ARQUIVO
    			$file_size = $_FILES["anexo_arquivo"]["size"];
    			//MIME DO ARQUIVO
    			$file_type = $_FILES["anexo_arquivo"]["type"];
    			
    			$binario = file_get_contents($file_tmp[0]);
    			*/
    			
    			//$anexo_cont = pack('c*', $anexo_arq);    			
    			
//     			$imgbinary  = file_get_contents($anexo_arq);
//     			$anexo_cont = base64_encode($imgbinary);

    			/*$arqui = $_FILES['anexo_arquivo']['tmp_name'];
    			$tam = $_FILES['anexo_arquivo']['size'];
    			

    			$fp = fopen($arqui[$i], "rb");
    			$anexo_cont = fread($fp, $tam[$i]);
    			$anexo_cont = addslashes($anexo_cont);
    			fclose($fp);*/
    			
//     			print_r(utf8_encode($anexo_cont));
//     			exit('anexo');

    		
    			
    			
    			/*if( $request->hasFile('anexo_arquivo') ) {
    			
    				$file  = $request->file('anexo_arquivo')->getPath();
    				$ext   = $request->file('anexo_arquivo')->getExtension();
    				$name  = $request->file('anexo_arquivo')->getClientOriginalName();
    			    			
    			

	    			$arquivo_id = DB::select('select gen_id(GTBARQUIVOS, 1) ID from RDB$DATABASE');
	    			
	    			$sql_arq = 'insert into TBARQUIVO'.
			    				' (ID, DATAHORA, USUARIO_ID, ARQUIVO, CONTEUDO, EXTENSAO)'.
								' values(:id, :data, :usu_id, :arq, :cont, :ext)';
	    			
	    			$args_arq = array(
	    					':id' 		=> $arquivo_id[0]->ID,
	    					':data' 	=> "now",
	    					':usu_id' 	=> Auth::user()->CODIGO,
	    					':arq' 		=> "$name",
	    					':cont'		=> $file,
	    					':ext' 		=> "$ext"
	    			);
	    			
					DB::connection('firebird2')->insert($sql_arq, $args_arq);
					
			    	$i++;
		    	
    			}*/
    		//}
    	
    	Session::flash('flash_message', 'Sua Empresa foi feita com sucesso.');
    	 
    	return redirect('_13060');
    }
    
    /**
     * Exibe os dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	Log::info('Exibindo Empresa | '. Auth::user()->USUARIO .' | '. \Request::getClientIp());
    	 
    	//$dados = _13060::exibir($id);    	
    	 
    	return view('empresa.empresa.show', ['dado' => $dados['dado'][0], 'dado_itens' => $dados['dado_itens']]);
    }

    /**
     * Exibe o formulário para edição de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	Log::info('Alterando Empresa | '. Auth::user()->USUARIO .' | '. \Request::getClientIp());
    	
    	//$dados = _13060::exibir($id);    	
    	 
    	return view('empresa.empresa.edit', ['dado' => $dados['dado'][0], 'dado_itens' => $dados['dado_itens']]);
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
    	
    	Log::info('Aplicando alterações na Empresa | '. Auth::user()->USUARIO .' | '. \Request::getClientIp());
    	
    	/*$obj = new _13060();
    	$obj->setId($id);
    	$obj->setCcusto($request->_ccusto_id);
    	$obj->setUsuarioGestorId($request->_gestor_id);
    	$obj->setUrgencia($request->urgencia ? '1' : '0');
    	$obj->setEmpresaId($request->empresa_id);
    	$obj->setEmpresaDescricao($request->empresa_descricao);
    	$obj->setEmpresaFone($request->fone);
    	$obj->setEmpresaEmail($request->email);
    	$obj->setEmpresaContato($request->contato);
    	$obj->setData($request->data);
    	$obj->setDataUtilizacao($request->data_utilizacao);
    	
    	
    	$i = 0;
    	
    	foreach ($request->_produto_id as $prod_id ) {
    	
    		addslashes($request->_produto_descricao[$i]);
    		
    		$obj->setReqItemId($request->_req_item_id[$i]);
    		$obj->setProdutoId($prod_id ? $prod_id : 0);
    		$obj->setProdutoDescricao($request->_produto_descricao[$i] ? $request->_produto_descricao[$i] : '');
    		$obj->setUm($request->um[$i] ? $request->um[$i] : '');
    		$obj->setValorUnitario($request->valor_unitario[$i] ? str_replace(',', '.', str_replace('.', '', $request->valor_unitario[$i])) : 0);
    		$obj->setTamanho($request->tamanho[$i] ? $request->tamanho[$i] : 0);
    		$obj->setQuantidade($request->quantidade[$i] ? str_replace(',', '.', str_replace('.', '', $request->quantidade[$i])) : 0);
    	
    		$i++;
    	
    	}
    	
    	_13060::alterar($obj);*/
    	
    	Session::flash('flash_message', 'Empresa alterada com sucesso.');
    	 
    	return redirect('_13060');
    }

    /**
     * Excluir dados do banco de dados.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	Log::info('Excluindo Empresa | '. Auth::user()->USUARIO .' | '. \Request::getClientIp());
    	
    	//_13060::excluir($id);
    	
    	Session::flash('flash_message', 'Empresa excluída com sucesso.');
    	
    	return redirect('_13060');
    }
    
    /**
     * Pesquisa empresa de acordo com o que for digitado pelo usuário.
     * Função chamada via Ajax.
     * 
     * @param Request $request
     */
    public function pesquisa(Request $request)
    {
    	if( $request->ajax() ) {
    		
            $filtro = $request->get('filtro') == null ? null : ($request->get('filtro') ? '%' . Helpers::removeAcento($request->get('filtro'), '%', 'upper',true) . '%' : '');
            $status = $request->get('status') == null ? null : ($request->get('status') == '1' ? '1' : '0');
            
            $obj = new _13060();
			$obj->setFiltro($filtro);
            $obj->setStatus($status);
            
    		$empresas = _13060::pesquisaEmpresa($obj);
    
    		$res = '<ul class="nav empresas empresa">';
    
    		if( !empty($empresas) ) {
    				
                $res .= '<li>';
                $res .= '<div class="titulo-lista">';
                $res .= '<span class="span-codigo">Id</span>';
                $res .= '<span class="span-razao">Raz&atildeo social</span>';
                $res .= '<span class="span-uf">UF</span>';
                $res .= '<span class="span-cidade">Cidade</span>';
                $res .= '<span class="span-cnpj">Cnpj</span>';
                $res .= '</div>';
                $res .= '</li>';

    			foreach ($empresas as $empresa) {
    				
    				$res .= '<li>';
    				$res .= '<a href="#" title="'.$empresa->CODIGO.' - '.$empresa->RAZAOSOCIAL.' - '.$empresa->UF.' - '.$empresa->CIDADE.' - '.$empresa->CNPJ.'">';
					$res .= '<span class="span-codigo">'. $empresa->CODIGO .'</span>';
					$res .= '<span class="span-razao">'. $empresa->RAZAOSOCIAL .'</span>';
					$res .= '<span class="span-uf">'. $empresa->UF .'</span>';
					$res .= '<span class="span-cidade">'. $empresa->CIDADE .'</span>';
					$res .= '<span class="span-cnpj">'. $empresa->CNPJ .'</span>';
					$res .= '</a>';
    				$res .= '<input type="hidden" class="descricao"                         value="'. $empresa->CODIGO .' - '. $empresa->RAZAOSOCIAL .'" />';
    				$res .= '<input type="hidden" class="codigo"        name="_emp_id"      value="'. $empresa->CODIGO .'" />';
    				$res .= '<input type="hidden" class="razao-social"  name="_emp_razao"   value="'. $empresa->RAZAOSOCIAL .'" />';
    				$res .= '<input type="hidden" class="nome-fantasia" name="_emp_nome"    value="'. $empresa->NOMEFANTASIA .'" />';
    				$res .= '<input type="hidden" class="fone"          name="_emp_fone"    value="'. $empresa->FONE .'" />';
    				$res .= '<input type="hidden" class="email"         name="_emp_email"   value="'. $empresa->EMAIL .'" />';
    				$res .= '<input type="hidden" class="contato"       name="_emp_contato" value="'. $empresa->CONTATO .'" />';
    				$res .= '<input type="hidden" class="cidade"        name="_emp_cidade"  value="'. $empresa->CIDADE .'" />';
    				$res .= '<input type="hidden" class="uf"            name="_emp_uf"      value="'. $empresa->UF .'" />';
    				$res .= '</li>';
    			}
    
    		}
    		else $res .= '<div class="nao-cadastrado">Empresa n&atildeo cadastrada.</div>';
    
    		$res .= '</ul>';
    			
    		return Response::json($res);
    	}
    }
    
    /**
     * Paginação com scroll.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function paginacaoScroll(Request $request)
    {
    	/*if ( $request->ajax() ) {
    
    		$dados = _13060::paginacaoScroll($request->get('qtd_por_pagina'), $request->get('pagina'));
    
    		if( !empty($dados) ) {
    				
    			$res = '';
    			$i = 0;
    			foreach ($dados as $dado) {
    
    				$res .= '<tr link="http://'.$_SERVER['HTTP_HOST'].'/Compras/_13060/'.$dado->ID .'">';
    				$res .= '<td>'.$dado->ID.'</td>';
    				$res .= '<td>'.$dado->USUARIO.'</td>';
    				$res .= '<td>'.$dado->CCUSTO_DESCRICAO.'</td>';
    				if($dado->OC) $oc = $dado->OC; else $oc = "-";
    				$res .= '<td>'.$oc.'</td>';
    				if($dado->URGENCIA) { $classe_td = 'green'; $classe_span = 'glyphicon-ok'; } else { $classe_td = 'red'; $classe_span = 'glyphicon-remove'; }
    				$res .= '<td class="'.$classe_td.'"><span class="glyphicon '.$classe_span.'"></span></td>';
    				$res .= '<td>'.date_format(date_create($dado->DATA), 'd/m/Y').'</td>';
    				$res .= '</tr>';
    
    				$i++;
    
    			}
    				
    			echo utf8_encode($res);
    		}
    		else echo '';
    
    	}*/
    }
    
    /**
     * Filtrar lista de requisições.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtraObj(Request $request)
    {
    	/*if ( $request->ajax() ) {
    
    		$dados = _13060::filtraObj($request->get('filtro'));
    
    		if( !empty($dados) ) {
    
    			$res = '';
    			$i = 0;
    			foreach ($dados as $dado) {
    
    				$res .= '<tr link="http://'.$_SERVER['HTTP_HOST'].'/Compras/_13060/'.$dado->ID .'">';
    				$res .= '<td>'.$dado->ID.'</td>';
    				$res .= '<td>'.$dado->USUARIO.'</td>';
    				$res .= '<td>'.$dado->CCUSTO_DESCRICAO.'</td>';
    				if($dado->OC) $oc = $dado->OC; else $oc = "-";
    				$res .= '<td>'.$oc.'</td>';
    				if($dado->URGENCIA) { $classe_td = 'green'; $classe_span = 'glyphicon-ok'; } else { $classe_td = 'red'; $classe_span = 'glyphicon-remove'; }
    				$res .= '<td class="'.$classe_td.'"><span class="glyphicon '.$classe_span.'"></span></td>';
    				$res .= '<td>'.date_format(date_create($dado->DATA), 'd/m/Y').'</td>';
    				$res .= '</tr>';
    
    				$i++;
    
    			}
    				
    			echo utf8_encode($res);
    		}
    		else
    			echo '';
    
    	}*/
    }    
    
}
