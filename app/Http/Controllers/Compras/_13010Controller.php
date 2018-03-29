<?php

namespace App\Http\Controllers\Compras;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Compras\_13010;
use App\Models\DTO\Helper\Email;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Helpers\Helpers;
use App\Models\DTO\Admin\_11010;

class _13010Controller extends Controller {

    /**
     * Código do menu
     * @var int 
     */
    private $menu = 'compras/_13010';

    /**
     * Deletar arquivo.
     *
     * @return json
     */
    public function listarTamanho(Request $request) {

        if ($request->ajax()) {

            $id = $request->get('ProdID');

            $val = _13010::listarTamanho($id);
			
            return $val;
        }
    }

    /**
     * Deletar arquivo.
     *
     * @return json
     */
    public function deletararquivo(Request $request) {
        _11010::permissaoMenu($this->menu, 'EXCLUIR', 'Excluir Arquivo');

        $arquivo = $request->arquivo;
        $Ret = 0;
        $Dir = env('APP_TEMP', '');

        if (file_exists($Dir . $arquivo)) {
            $Ret = unlink($Dir . $arquivo);
        } else {
            $Ret = 3;
        }

        $var = array(
            "Ret" => $Ret
        );


        echo json_encode($var);
    }

    /**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        $p197          = _11010::controle(197); //[P197] - PERMITE GERAR OC A PARTIR DA REQUISIÇÃO NO GC-WEB

        $filtro_obj     = isset($_GET['filtro']) ? $_GET['filtro'] : '';
        $status         = isset($_GET['status']) ? $_GET['status'] : '0';

        $val = _13010::listar($p197);

        return view('compras._13010.index', [
            'dados'			=> $val,
            'permissaoMenu' => $permissaoMenu,
            'filtro_obj'    => $filtro_obj,
            'status'        => $status
        ]);
    }

    /**
     * Exibe o formulário de criação.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        _11010::permissaoMenu($this->menu, 'INCLUIR');

        $vinculo_id   = DB::connection('firebird')->select('select gen_id(GTBVINCULO_REQUISICAO, 1) ID from RDB$DATABASE');
		$estab_id_max = DB::connection('firebird')->select('SELECT MAX(CODIGO) ID_MAX FROM TBESTABELECIMENTO');
		
        return view(
			'compras._13010.create', [
				'vinculo'		=> $vinculo_id,
				'estab_id_max'	=> $estab_id_max[0]->ID_MAX
			]);
    }

    /**
     * Grava os dados no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		
        _11010::permissaoMenu($this->menu, 'INCLUIR', 'Fixando Dados');

        $requisicao_id = _13010::gerarId();

        $obj = new _13010();
        $obj->setId($requisicao_id[0]->ID);
		$obj->setEstabelecimentoId($request->estabelecimento_id);
        $obj->setVinculo($request->_vinculo_id);
        $obj->setCcusto($request->_ccusto_id);
        $obj->setUsuarioGestorId($request->_gestor_id);
        $obj->setUsuarioGestorEmail($request->_gestor_email);
        $obj->setUsuarioId(Auth::user()->CODIGO);
        $obj->setUrgencia($request->urgencia ? '1' : '0');
		$obj->setNecessitaLicitacao($request->necessita_licitacao ? '1' : '0');
        $obj->setEmpresaId($request->_empresa_id ? $request->_empresa_id : 0);
        $obj->setEmpresaDescricao(!empty($request->_empresa_id) ? $request->empresa_descricao : '');
        $obj->setEmpresaFone(!empty($request->_empresa_id) ? $request->fone : '');
        $obj->setEmpresaEmail(!empty($request->_empresa_id) ? $request->email : '');
        $obj->setEmpresaContato(!empty($request->_empresa_id) ? $request->contato : '');
        $obj->setData($request->data);
        $obj->setDataUtilizacao($request->data_utilizacao);
		$obj->setDescricao($request->descricao);

        $i = 0;

		//itens
        foreach ($request->_produto_id as $prod_id) {

            addslashes($request->_produto_descricao[$i]);

            $obj->setProdutoId($prod_id ? $prod_id : 0);
            $obj->setProdutoDescricao($request->_produto_descricao[$i] ? $request->_produto_descricao[$i] : '');
            $obj->setUm($request->um[$i] ? $request->um[$i] : '');
            $obj->setValorUnitario($request->valor_unitario[$i] ? str_replace(',', '.', str_replace('.', '', $request->valor_unitario[$i])) : 0);
            $obj->setTamanho($request->tamanho[$i] ? $request->tamanho[$i] : 0);
            $obj->setQuantidade($request->quantidade[$i] ? str_replace(',', '.', str_replace('.', '', $request->quantidade[$i])) : 0);
			$obj->setObservacaoItem($request->observacao_item[$i]);
			$obj->setOperacaoCodigo($request->_operacao_codigo[$i]);
			$obj->setOperacaoCcusto($request->_operacao_ccusto[$i]);
			$obj->setOperacaoCcontabil($request->_operacao_ccontabil[$i]);

            $i++;
        }

        $resposta = _13010::gravar($obj);

        //$this->enviarReqGestor($obj, 1);

        return Response::json($resposta);
    }

    /**
     * Exibe os dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $permissaoMenu = _11010::permissaoMenu($this->menu, null, 'Visualizar Item: ' . $id);
        $controle      = _11010::controle(197); //[P197] - PERMITE GERAR OC A PARTIR DA REQUISIÇÃO NO GC-WEB

        $val = _13010::exibir($id);

        return view('compras._13010.show', [
            'dado'          => $val['dado'][0],
            'dado_itens'    => $val['dado_itens'],
            'arquivo_itens' => $val['arquivo_itens'],
            'permissaoMenu' => $permissaoMenu,
            'controle'      => $controle
        ]);
    }

    /**
     * Exibe o formulÃ¡rio para edição de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
		
        _11010::permissaoMenu($this->menu, 'ALTERAR');

        $dados = _13010::exibir($id);

        return view('compras._13010.edit', ['dado' => $dados['dado'][0], 'dado_itens' => $dados['dado_itens'], 'arquivo_itens' => $dados['arquivo_itens'], 'dado_edicao' => $dados['dado_edicao'], 'Editar' => $dados['Editar'], 'vinculo' => $dados['vinculo_id']]);
    }

    /**
     * Atualiza dados no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
		_11010::permissaoMenu($this->menu, 'ALTERAR', 'Fixando Dados');

        $StrErroTemp = '';
        $StrErro = '';
        $StrTit = '';

        $obj = new _13010();
        $obj->setId($id);
		$obj->setDescricao($request->descricao);
		$obj->setEstabelecimentoId($request->estabelecimento_id);
        $obj->setCcusto($request->_ccusto_id);
        $obj->setVinculo($request->_vinculo_id);
        $obj->setEditavel($request->_req_info_editar);
		$obj->setUsuarioId(Auth::user()->CODIGO);
        $obj->setUsuarioGestorId($request->_gestor_id);
        $obj->setUsuarioGestorEmail($request->_gestor_email);
        $obj->setUrgencia($request->urgencia ? '1' : '0');
		$obj->setNecessitaLicitacao($request->necessita_licitacao ? '1' : '0');
        $obj->setEmpresaId($request->_empresa_id);
        $obj->setEmpresaDescricao($request->empresa_descricao);
        $obj->setEmpresaFone($request->fone);
        $obj->setEmpresaEmail($request->email);
        $obj->setEmpresaContato($request->contato);
        $obj->setData($request->data);
        $obj->setDataUtilizacao($request->data_utilizacao);


        $i = 0;

        foreach ($request->_produto_id as $prod_id) {

            if ($request->_req_item_excluir[$i] === '1') {

                $Ret = _13010::excluiProduto($request->_req_item_id[$i]);

                //$Erro = $Ret->Erros;
                $result = count($Ret);

                if ($result > 1) {
                    $ii = 1;

                    foreach ($Ret as $Erro) {

                        if ($ii == 2) {
                            $StrErro = 'Erro' . ($ii - 1) . '->' . $Erro;
                            $StrTit = 'erro';
                        } else {
                            $StrErro = $StrErro . ', Erro' . ($ii - 1) . '->' . $Erro;
                            $StrTit = 'erros';
                        }

                        $ii++;
                    }


                    $StrErroTemp = $StrErro;
                }
            }



            if (($request->_req_item_editar[$i] === '1') and ( $request->_req_item_excluir[$i] === '0')) {

                addslashes($request->_produto_descricao[$i]);

                $obj->setReqItemId($request->_req_item_id[$i]);
                $obj->setProdutoId($prod_id ? $prod_id : 0);
                $obj->setProdutoDescricao($request->_produto_descricao[$i] ? $request->_produto_descricao[$i] : '');
                $obj->setUm($request->um[$i] ? $request->um[$i] : '');
                $obj->setValorUnitario(Helpers::formataNumPadrao($request->valor_unitario[$i]));
                $obj->setTamanho($request->tamanho[$i] ? $request->tamanho[$i] : 0);
                $obj->setQuantidade(Helpers::formataNumPadrao($request->quantidade[$i]));
				$obj->setObservacaoItem($request->observacao_item[$i]);
				$obj->setOperacaoCodigo($request->_operacao_codigo[$i]);
				$obj->setOperacaoCcusto($request->_operacao_ccusto[$i]);
				$obj->setOperacaoCcontabil($request->_operacao_ccontabil[$i]);
            }

            if (($request->_req_item_editar[$i] === '3') and ( $request->_req_item_excluir[$i] === '3')) {

                addslashes($request->_produto_descricao[$i]);

                $obj->setReqItemId($request->_req_item_id[$i]);
                $obj->setProdutoId($prod_id ? $prod_id : 0);
                $obj->setProdutoDescricao($request->_produto_descricao[$i] ? $request->_produto_descricao[$i] : '');
                $obj->setUm($request->um[$i] ? $request->um[$i] : '');
                $obj->setValorUnitario(Helpers::formataNumPadrao($request->valor_unitario[$i]));
                $obj->setTamanho($request->tamanho[$i] ? $request->tamanho[$i] : 0);
                $obj->setQuantidade(Helpers::formataNumPadrao($request->quantidade[$i]));
				$obj->setObservacaoItem($request->observacao_item[$i]);
				$obj->setOperacaoCodigo($request->_operacao_codigo[$i]);
				$obj->setOperacaoCcusto($request->_operacao_ccusto[$i]);
				$obj->setOperacaoCcontabil($request->_operacao_ccontabil[$i]);
            }

            $i++;
        }

        $i = 0;
        foreach ($request->_vinculo_Arquivo_id as $vinc_id) {

            if ($request->_req_arquivo_excluir[$i] === '1') {
                $obj->setArquivoID($request->_vinculo_Arquivo_id[$i]);
            }

            $i++;
        }


        $resposta = _13010::alterar($obj);

        //$this->enviarReqGestor($obj, 2);

        return Response::json($resposta);
    }

    /**
     * Excluir dados do banco de dados.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
		
        _11010::permissaoMenu($this->menu, 'EXCLUIR', 'Excluir item');

        $Ret = _13010::excluir($id);

        //$Erro = $Ret->Erros;
        $result = count($Ret);
        $StrErro = '';
        $StrTit = '';
        if ($result > 1) {
            $ii = 1;

            foreach ($Ret as $Erro) {

                if ($ii == 2) {
                    $StrErro = 'Erro' . ($ii - 1) . '->' . $Erro;
                    $StrTit = 'erro';
                } else {
                    $StrErro = $StrErro . ', Erro' . ($ii - 1) . '->' . $Erro;
                    $StrTit = 'erros';
                }

                $ii++;
            }


            //Session::flash('flash_message', 'Requisição de Compra alterada com sucesso.');
            Session::flash('flash_message_error', 'Não foi possível excluir [' . ($ii - 2) . ' ' . $StrTit . ']: ' . $StrErro);
            return redirect('_13010');
        } else {
            Session::flash('flash_message', 'Requisição de Compra excluída com sucesso.');
            return redirect('_13010');
        }
    }

    /**
     * Pesquisa centro de custo de acordo com o que for digitado pelo usuÃ¡rio.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function pesquisaCCusto(Request $request) {
        if ($request->ajax()) {

            $ccustos = _13010::pesquisaCCusto($request->get('filtro'));

            $res = '<ul class="nav ccustos">';

            if (!empty($ccustos)) {

                foreach ($ccustos as $ccusto) {
                    $res .= '<li>';
                    $res .= '<a href="#">' . $ccusto->CODIGO . ' - ' . $ccusto->DESCRICAO . '</a>';
                    $res .= '<input type="hidden" class="id" value=\'' . $ccusto->CODIGO . '\' />';
                    $res .= '<input type="hidden" class="descricao" value=\'' . $ccusto->DESCRICAO . '\' />';
                    $res .= '</li>';
                }
            } else
                $res .= '<div class="nao-cadastrado">Centro de custo n&atildeo cadastrado.</div>';

            $res .= '</ul>';

            echo utf8_encode($res);
        }
    }

    /**
     * Pesquisa gestores de acordo com o que for digitado pelo usuÃ¡rio.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function pesquisaGestor(Request $request) {
        if ($request->ajax()) {

            $gestores = _13010::pesquisaGestor($request->get('filtro'));

            $res = '<ul class="nav gestores">';

            if (!empty($gestores)) {

                foreach ($gestores as $gestor) {
                    $res .= '<li>';
                    $res .= '<a href="#">' . $gestor->CODIGO . ' - ' . $gestor->NOME . '</a>';
                    $res .= '<input type="hidden" class="id" value=\'' . $gestor->CODIGO . '\' />';
                    $res .= '<input type="hidden" class="nome" value=\'' . $gestor->NOME . '\' />';
                    $res .= '<input type="hidden" class="email" value=\'' . $gestor->EMAIL . '\' />';
                    $res .= '</li>';
                }
            } else {
                $res .= '<div class="nao-cadastrado">Gestor n&atildeo cadastrado.</div>';
            }

            $res .= '</ul>';

            return Response::json($res);
        }
    }

    /**
     * Pesquisa produto de acordo com o que for digitado pelo usuÃ¡rio.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function pesquisaProduto(Request $request) {
        if ($request->ajax()) {

            $produtos = _13010::pesquisaProduto($request->filtro);

            $res = '<ul class="nav produtos">';

            $res .= '<li>';
            $res .= '<div class="titulo-lista">';
            $res .= '    <span class="span-FAMILIA">FAMÍLIA</span>';
            $res .= '    <span class="span-PRODUTO">PRODUTO</span>';
            $res .= '</div>';
            $res .= '</li>';

            if (!empty($produtos)) {

                foreach ($produtos as $produto) {
                    $res .= '<li>';
                    $res .= '<a href="#">';
                    $res .= '    <span class="span-FAMILIA">' . $produto->FAMILIA   . '</span>';
                    $res .= '    <span class="span-PRODUTO">' . $produto->DESCRICAO . '</span>';
                    $res .= '</a>';
                    $res .= '<input type="hidden" class="id" value=\'' . $produto->CODIGO . '\' />';
                    $res .= '<input type="hidden" class="descricao" value=\'' . $produto->DESCRICAO . '\' />';
                    $res .= '<input type="hidden" class="prod-descricao-id" value=\'' . $produto->CODIGO .'-'. $produto->DESCRICAO . '\' />';
                    $res .= '<input type="hidden" class="um" value=\'' . $produto->UNIDADEMEDIDA_SIGLA . '\' />';
                    $res .= '</li>';
                }

            } 
            else {
                $res .= '<div class="nao-cadastrado">Produto n&atildeo cadastrado.';
            }

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
    public function paginacaoScroll(Request $request) {
        $protocol = protocol();

        if ($request->ajax()) {

            $p197  = _11010::controle(197); //[P197] - PERMITE GERAR OC A PARTIR DA REQUISIÇÃO NO GC-WEB

            $dados = _13010::paginacaoScroll($request->get('qtd_por_pagina'), $request->get('pagina'), $request->get('status'), $p197);

            $res = '';
            $i = 0;
            foreach ($dados as $dado) {

                $res .= '<tr link="'.$protocol.'://' . $_SERVER['HTTP_HOST'] . '/_13010/' . $dado->ID . '">';
                $res .= '<td>' . $dado->ID . '</td>';
				$res .= '<td>' . $dado->DESCRICAO . '</td>';
                $res .= '<td>' . $dado->USUARIO . '</td>';
                $res .= '<td>' . $dado->CCUSTO_DESCRICAO . '</td>';
                
                if ($dado->OC)
                    $oc = $dado->OC;
                else
                    $oc = "-";
                $res .= '<td>' . $oc . '</td>';
                
                if ($dado->URGENCIA > 0) {
                    $classe_td = 'green';
                    $classe_span = 'glyphicon-ok';
                } else {
                    $classe_td = 'red';
                    $classe_span = 'glyphicon-remove';
                }
                $res .= '<td class="' . $classe_td . '"><span class="glyphicon ' . $classe_span . '"></span></td>';

                if ($dado->NECESSITA_LICITACAO > 0) {
                    $classe_td = 'green';
                    $classe_span = 'glyphicon-ok';
                } else {
                    $classe_td = 'red';
                    $classe_span = 'glyphicon-remove';
                }
                $res .= '<td class="' . $classe_td . '"><span class="glyphicon ' . $classe_span . '"></span></td>';

                $res .= '<td>' . date_format(date_create($dado->DATA), 'd/m/Y') . '</td>';
                $res .= '</tr>';

                $i++;
            }

            return $res;
        }
    }

    /**
     * Filtrar lista de requisicoes.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function filtraObj(Request $request) {
		$protocol = protocol();

        if ($request->ajax()) {

            $p197   = _11010::controle(197); //[P197] - PERMITE GERAR OC A PARTIR DA REQUISIÇÃO NO GC-WEB
            $Filtro = $request->get('filtro');
            $status = $request->get('status');

            $dados = _13010::filtraObj($Filtro, $status, $p197);

            if (!empty($dados)) {

                $res = '';
                $i = 0;

                //if ($Filtro <> '') {

                    foreach ($dados as $dado) {
                        //*
                        $Des = $dado->CCUSTO_DESCRICAO;
                        $Use = $dado->USUARIO;
                        $Cod = $dado->ID;
                        $vOC = $dado->OC;


                        //if ((strpos('¨' . $Des, $Filtro) > 0) or ( strpos('¨' . $Use, $Filtro) > 0) or ( strpos('¨' . $Cod, $Filtro) > 0) or ( strpos('¨' . $vOC, $Filtro) > 0)) {
                            $res .= '<tr link="'.$protocol.'://' . $_SERVER['HTTP_HOST'] . '/_13010/' . $dado->ID . '">';
                            $res .= '<td>' . $dado->ID . '</td>';
							$res .= '<td>' . $dado->DESCRICAO . '</td>';
                            $res .= '<td>' . $dado->USUARIO . '</td>';
                            $res .= '<td>' . $dado->CCUSTO_DESCRICAO . '</td>';
                            if ($dado->OC)
                                $oc = $dado->OC;
                            else
                                $oc = "-";
                            $res .= '<td>' . $oc . '</td>';

                            if ($dado->URGENCIA > 0) {
                                $classe_td = 'green';
                                $classe_span = 'glyphicon-ok';
                            } else {
                                $classe_td = 'red';
                                $classe_span = 'glyphicon-remove';
                            }
                            $res .= '<td class="' . $classe_td . '"><span class="glyphicon ' . $classe_span . '"></span></td>';
							
                            if ($dado->NECESSITA_LICITACAO > 0) {
                                $classe_td = 'green';
                                $classe_span = 'glyphicon-ok';
                            } else {
                                $classe_td = 'red';
                                $classe_span = 'glyphicon-remove';
                            }
                            $res .= '<td class="' . $classe_td . '"><span class="glyphicon ' . $classe_span . '"></span></td>';
                            $res .= '<td>' . date_format(date_create($dado->DATA), 'd/m/Y') . '</td>';
                            $res .= '</tr>';
                        //}
                        //*/

                        $i++;
                    }
                /*} else {

                    $dados = _13010::paginacaoScroll(20, 0);

                    foreach ($dados as $dado) {

                        $res .= '<tr link="http://' . $_SERVER['HTTP_HOST'] . '/_13010/' . $dado->ID . '">';
                        $res .= '<td>' . $Filtro . $dado->ID . '</td>';
						$res .= '<td>' . $dado->DESCRICAO . '</td>';
                        $res .= '<td>' . $dado->USUARIO . '</td>';
                        $res .= '<td>' . $dado->CCUSTO_DESCRICAO . '</td>';
                        if ($dado->OC)
                            $oc = $dado->OC;
                        else
                            $oc = "-";
                        $res .= '<td>' . $oc . '</td>';

                        if ($dado->URGENCIA > 0) {
                            $classe_td = 'green';
                            $classe_span = 'glyphicon-ok';
                        } else {
                            $classe_td = 'red';
                            $classe_span = 'glyphicon-remove';
                        }
                        $res .= '<td class="' . $classe_td . '"><span class="glyphicon ' . $classe_span . '"></span></td>';
						
                        if ($dado->NECESSITA_LICITACAO > 0) {
							$classe_td = 'green';
							$classe_span = 'glyphicon-ok';
						} else {
							$classe_td = 'red';
							$classe_span = 'glyphicon-remove';
						}
						$res .= '<td class="' . $classe_td . '"><span class="glyphicon ' . $classe_span . '"></span></td>';
                        
                        $res .= '<td>' . date_format(date_create($dado->DATA), 'd/m/Y') . '</td>';
                        $res .= '</tr>';

                        $i++;
                    }
                }*/
                return Response::json($res);
            } else {
                $res = '<tr><td colspan="8" style="text-align:center;" >Sem registros</td></tr>';

                return Response::json($res);
            }
        }
    }

    //*

    /**
     * Exclui produto.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function excluiProduto(Request $request) {
        if ($request->ajax()) {

            $Ret = _13010::excluiProduto($request->get('item'));

            //$Erro = $Ret->Erros;
            $result = count($Ret);
            $StrErro = '';
            $StrTit = '';
            if ($result > 1) {
                $ii = 1;

                foreach ($Ret as $Erro) {

                    if ($ii == 2) {
                        $StrErro = 'Erro' . ($ii - 1) . '->' . $Erro;
                        $StrTit = 'erro';
                    } else {
                        $StrErro = $StrErro . ', Erro' . ($ii - 1) . '->' . $Erro;
                        $StrTit = 'erros';
                    }

                    $ii++;
                }


                //Session::flash('flash_message', 'Requisição de Compra alterada com sucesso.');
                Session::flash('Erro_message', 'Não foi possível excluir [' . ($ii - 2) . ' ' . $StrTit . ']: ' . $StrErro);
                $StrErro = 'Não foi possível excluir [' . ($ii - 2) . ' ' . $StrTit . ']: ' . $StrErro;
                echo $StrErro;
            } else {
                echo 'sucesso';
            }
        }
    }

    public function excluiArquivo(Request $request) {
        if ($request->ajax()) {

            _13010::excluiArquivo($request->get('item'));
            echo 'sucesso';
        }
    }

    public function downloadArquivo(Request $request) {
        if ($request->ajax()) {
            //print_r('ponto de verificação 1');

            $Ret = _13010::downloadArquivo($request->get('item'));

            //$novoNome = $Ret['nome'];
			$novoNome = str_replace(" ","_",preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($Ret['nome']))));
			
            $conteudo = $Ret['conteudo'];
            $tamanho = $Ret['tamanho'];

            $temp = substr(md5(uniqid(time())), 0, 10);
            $novoNome = $temp . $novoNome;

            $Dir = env('APP_TEMP', '');

            $novoarquivo = fopen($Dir . $novoNome, "a+");
            fwrite($novoarquivo, $conteudo);
            fclose($novoarquivo);

            $var = array(
                "nome" => $novoNome,
                "tamanho" => $tamanho
            );

            echo json_encode($var);
        }
    }

    public function gravaArquivo(Request $request) {

        if ($request->ajax()) {

            $vin = 2;
            $file_type = '';
            $file_tmp = '';
            $file_name = '';

            $vin = $request->get('vinculo');
            $file_size = $request->get('tamanho');
            $file_name = $request->get('arquivonome');
            $binario = $request->get('conteudo');

            $tab = array("UTF-8", "ASCII", "Windows-1252", "ISO-8859-15", "ISO-8859-1", "ISO-8859-6", "CP1256");
            $chain = $binario;
            foreach ($tab as $i) {
                foreach ($tab as $j) {
                    $chain .= " $i$j " . iconv($i, $j, "$my_string");
                }
            }

            echo $chain;

            $Tabela = $request->get('tabela');
            $file_type = $request->get('tipo');

            _13010::EnviaArquivo($vin, $file_type, $file_tmp, $file_name, $file_size, $chain, $Tabela);

            echo 'sucesso';
        }
    }

    /**
     * Grava arquivo no banco de dados.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function enviaArquivo(Request $request) {

        //$output = "$.each($(':file')[x].files, function(i, file) {fdata.append('file-0', file);})";
        //echo $output;

        if ($request->ajax()) {
            $vin = 2;
            $file_type = '';
            $file_tmp = '';
            $file_name = '';


            //NOME TEMPORÁRIO
            $vin = $request->get('vinc');
            $Tabela = $request->get('tabela');
            $file_type = $request->get('tipo');
            $file_size = $request->get('tamanho');
            $file_tmp = $_FILES['file-0']['tmp_name'];
            //NOME DO ARQUIVO NO COMPUTADOR
            $file_name = $_FILES['file-0']['name'];
            //TAMANHO DO ARQUIVO
            //$file_size = $_FILES['file-0']['size'];
            //MIME DO ARQUIVO

            if ($file_type === '') {
                $file_type = 'unknown';
            }


            $binario = file_get_contents($file_tmp);


            $arquivo_id = DB::connection('firebird')->select('select gen_id(GTBARQUIVOS, 1) ID from RDB$DATABASE');


            $sql_arq = 'insert into tbvinculo (tabela,tabela_id,arquivo_id,sequencia,observacao,datahora,usuario_id)' .
                    'values (:tabela,:tabela_id,:arquivo_id,:sequencia,:observacao,:datahora,:usuario_id)';

            $args_arq = array(
                ':tabela' => $Tabela,
                ':tabela_id' => $vin,
                ':arquivo_id' => $arquivo_id[0]->ID,
                ':sequencia' => 1,
                //':observacao'	=> 'Incluido via Web',
                ':observacao' => "$file_name",
                ':datahora' => "now",
                ':usuario_id' => Auth::user()->CODIGO
            );

            DB::connection('firebird2')->insert($sql_arq, $args_arq);

            $sql_arq = 'insert into TBARQUIVO' .
                    ' (ID, DATAHORA, USUARIO_ID, ARQUIVO, CONTEUDO, EXTENSAO,TAMANHO)' .
                    ' values(:id, :data, :usu_id, :arq, :cont, :ext, :tamanho)';

            $args_arq = array(
                ':id' => $arquivo_id[0]->ID,
                ':data' => "now",
                ':usu_id' => Auth::user()->CODIGO,
                ':arq' => "$file_name",
                ':cont' => $binario,
                ':ext' => "$file_type",
                ':tamanho' => $file_size
            );

            DB::connection('firebird2')->insert($sql_arq, $args_arq);

            $var = array(
                "id" => $arquivo_id[0]->ID,
                "status" => 'sucesso'
            );

            return Response::json($var);
            echo json_encode($var);
        }
    }

    public function BaixaArquivo(Request $Request) {

        $nome = $Request->get('nome');
        $tamanho = $Request->get('tamanho');
        $Dir = env('APP_TEMP', '');

        print_r($Request);
        exit();

        $obj = $Dir . $nome;
        //$novoarquivo = fopen($obj, "a");
        $tipo = substr($obj, -3);

        switch ($tipo) {
            case "pdf": $tipo = "application/pdf";
                break;
            case "exe": $tipo = "application/octet-stream";
                break;
            case "zip": $tipo = "application/zip";
                break;
            case "doc": $tipo = "application/msword";
                break;
            case "xls": $tipo = "application/vnd.ms-excel";
                break;
            case "ppt": $tipo = "application/vnd.ms-powerpoint";
                break;
            case "gif": $tipo = "image/gif";
                break;
            case "png": $tipo = "image/png";
                break;
            case "jpg": $tipo = "image/jpg";
                break;
            case "mp3": $tipo = "audio/mpeg";
                break;
            case "php": $tipo = '';
                break;
            case "htm": $tipo = '';
                break;
            case "html":$tipo = '';
                break;
            default: $tipo = '';
                break;
        }

        $headers = array(
            'Content-Type' => $tipo,
            'Content-Length' => $tamanho
        );

        return \Response::download($Dir . $nome, $headers);

        if (file_exists($Dir . $nome)) {
            unlink($Dir . $nome);
        }
    }

    public function viewArquivo(Request $Request) {

        echo 'erro no caregamento';
    }

    public function ConteudoArquivo(Request $request) {

        if ($request->ajax()) {

            $file_tmp = $_FILES['file-0']['tmp_name'];
            $file_name = $_FILES['file-0']['name'];
            $file_type = $_FILES['file-0']['type'];

            $binario = file_get_contents($file_tmp);

            echo '' . $binario . '';
        }
    }

    /**
     * Enviar requisição para o gestor por e-mail.
     *
     * @param _13010 $objeto
	 * @param int $acao 1 = Gravar | 2 = Alterar
     * @return \Illuminate\Http\Response
     */
    public function enviarReqGestor(_13010 $objeto, $acao) {
		
        _11010::permissaoMenu($this->menu, 'ALTERAR', 'Enviando notificação de requisição para o gestor por e-mail.');
		
        $obj = new Email();
		
		$id_req = str_pad($objeto->getId(), 5, 0, STR_PAD_LEFT);

		switch($acao) {
			case 1: 
				$msg = "Foi gerada para seu setor a Requisi&ccedil;&atilde;o de Or&ccedil;amento ". $id_req .".";
				break;
			case 2:
				$msg = "Houve um altera&ccedil;&atilde;o na Requisi&ccedil;&atilde;o de Or&ccedil;amento ". $id_req .".";
				break;
			default:
				$msg = '';
		}

        $obj->setEmail($objeto->getUsuarioGestorEmail());
        $obj->setUsuarioId($objeto->getUsuarioId());
		$obj->setMensagem($msg);
        $obj->setUrl(env('URL_PRINCIPAL'));
        $obj->setAssunto('Requisição de Compra '. $id_req);
        $obj->setCorpo( env('URL_PRINCIPAL'). '/_13010/'. $id_req );
        $obj->setStatus('1');
        $obj->setDatahora(date('d.m.Y H:i:s'));
        $obj->setCodigo(3); //template

        return Response::json(Email::gravar($obj));

    }

}
