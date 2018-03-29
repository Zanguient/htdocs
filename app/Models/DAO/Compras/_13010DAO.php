<?php

namespace App\Models\DAO\Compras;

use App\Models\DTO\Compras\_13010;
use App\Models\DTO\Helper\Historico;
use App\Models\Conexao\_Conexao;
use Exception;

class _13010DAO {

    /**
     * listar tamanhos.
     * @return array
     */
    public static function listarTamanho($id) {

        $con = new _Conexao();

        $sql = 'SELECT * from spc_grade_produto(:PRODUTO_ID)';

        $args = array(':PRODUTO_ID' => $id);

        $retorno = $con->query($sql, $args);

        if (count($retorno) > 0) {

            $t_tamanhos = $retorno[0]->TOTAL_TAMANHOS;
            $descricao = $retorno[0]->DESCRICAO;

            $t01 = $retorno[0]->T01;
            $t06 = $retorno[0]->T06;
            $t02 = $retorno[0]->T02;
            $t07 = $retorno[0]->T07;
            $t03 = $retorno[0]->T03;
            $t08 = $retorno[0]->T08;
            $t04 = $retorno[0]->T04;
            $t09 = $retorno[0]->T09;
            $t05 = $retorno[0]->T05;
            $t10 = $retorno[0]->T10;

            $t11 = $retorno[0]->T11;
            $t16 = $retorno[0]->T16;
            $t12 = $retorno[0]->T12;
            $t17 = $retorno[0]->T17;
            $t13 = $retorno[0]->T13;
            $t18 = $retorno[0]->T18;
            $t14 = $retorno[0]->T14;
            $t19 = $retorno[0]->T19;
            $t15 = $retorno[0]->T15;
            $t20 = $retorno[0]->T20;

            $a01 = $retorno[0]->A01;
            $a06 = $retorno[0]->A06;
            $a02 = $retorno[0]->A02;
            $a07 = $retorno[0]->A07;
            $a03 = $retorno[0]->A03;
            $a08 = $retorno[0]->A08;
            $a04 = $retorno[0]->A04;
            $a09 = $retorno[0]->A09;
            $a05 = $retorno[0]->A05;
            $a10 = $retorno[0]->A10;

            $a11 = $retorno[0]->A11;
            $a16 = $retorno[0]->A16;
            $a12 = $retorno[0]->A12;
            $a17 = $retorno[0]->A17;
            $a13 = $retorno[0]->A13;
            $a18 = $retorno[0]->A18;
            $a14 = $retorno[0]->A14;
            $a19 = $retorno[0]->A19;
            $a15 = $retorno[0]->A15;
            $a20 = $retorno[0]->A20;

            $Tamanhos = array();
            $Temp = array($descricao, $t_tamanhos);
            array_push($Tamanhos, $Temp);
            $Temp = array($t01, $a01, 1);
            array_push($Tamanhos, $Temp);
            $Temp = array($t02, $a02, 2);
            array_push($Tamanhos, $Temp);
            $Temp = array($t03, $a03, 3);
            array_push($Tamanhos, $Temp);
            $Temp = array($t04, $a04, 4);
            array_push($Tamanhos, $Temp);
            $Temp = array($t05, $a05, 5);
            array_push($Tamanhos, $Temp);
            $Temp = array($t06, $a06, 6);
            array_push($Tamanhos, $Temp);
            $Temp = array($t07, $a07, 7);
            array_push($Tamanhos, $Temp);
            $Temp = array($t08, $a08, 8);
            array_push($Tamanhos, $Temp);
            $Temp = array($t09, $a09, 9);
            array_push($Tamanhos, $Temp);
            $Temp = array($t10, $a10, 10);
            array_push($Tamanhos, $Temp);
            $Temp = array($t11, $a11, 11);
            array_push($Tamanhos, $Temp);
            $Temp = array($t12, $a12, 12);
            array_push($Tamanhos, $Temp);
            $Temp = array($t13, $a13, 13);
            array_push($Tamanhos, $Temp);
            $Temp = array($t14, $a14, 14);
            array_push($Tamanhos, $Temp);
            $Temp = array($t15, $a15, 15);
            array_push($Tamanhos, $Temp);
            $Temp = array($t16, $a16, 16);
            array_push($Tamanhos, $Temp);
            $Temp = array($t17, $a17, 17);
            array_push($Tamanhos, $Temp);
            $Temp = array($t18, $a18, 18);
            array_push($Tamanhos, $Temp);
            $Temp = array($t19, $a19, 19);
            array_push($Tamanhos, $Temp);
            $Temp = array($t20, $a20, 20);
            array_push($Tamanhos, $Temp);

            return array($Tamanhos, $retorno[0]);


			
        } else {
            log_erro('Produto sem tamanhos definidos.');
        }
    }

    /**
     * Select da página inicial.
     * @return array
     */
    public static function listar($p197) {

        $con = new _Conexao();

        $porCCusto = '';

        // Se tiver permissão para gerar OC, vê todas as requisições.
        if ($p197 != '1') {

            $porCCusto = "
                LEFT JOIN TBUSUARIO_CCUSTO UC ON UC.CCUSTO = R.CCUSTO
        
                WHERE
                    UC.USUARIO_ID = ". \Auth::user()->CODIGO;
        }

        $sql = "
			select first 30
			    lpad(r.ID, 5, '0') ID,
				r.DESCRICAO,
			    r.URGENCIA,
				r.NECESSITA_LICITACAO,
			    r.DATA,
				(select first 1 list(o.OC) from TBOC o where o.REFERENCIA = 'R' AND O.REFERENCIA_ID = R.ID) OC,
			    (select first 1 u.NOME from TBUSUARIO u where u.CODIGO = r.USUARIO_ID) USUARIO,
			    (select first 1 c.DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO) CCUSTO_DESCRICAO

			from
			    TBREQUISICAO_OC r
                ". $porCCusto ."

			order by
			    r.ID DESC
        ";

        return $con->query($sql);
    }

    /**
     * Gerar id do objeto.
     * @return integer
     */
    public static function gerarId() {

        $con = new _Conexao();

        $sql = 'select gen_id(GTBREQUISICAO_OC, 1) ID from RDB$DATABASE';

        return $con->query($sql);
    }

    /**
     * Inserir dados do objeto na base de dados.
     * @param _13010 $obj
     */
    public static function gravar(_13010 $obj) {

        $con = new _Conexao();
        $con_files = new _Conexao('FILES');
        
        try {

            $sql = 'insert into TBREQUISICAO_OC' .
                    ' (ID, CCUSTO, USUARIO_GESTOR_ID, USUARIO_ID, URGENCIA, EMPRESA_ID, EMPRESA_DESCRICAO, EMPRESA_FONE, EMPRESA_EMAIL, EMPRESA_CONTATO, DATA, DATA_UTILIZACAO, VINCULO_ID, ESTABELECIMENTO_ID, NECESSITA_LICITACAO, DESCRICAO)' .
                    ' values (:id, :ccusto, :usuario_gestor_id, :usuario_id, :urgencia, :empresa_id, :empresa_desc, :empresa_fone, :empresa_email, :empresa_contato, :data, :data_utilizacao, :vinculo, :estab, :nec_lic, :desc)';

            $args = array(
                ':id'                   => $obj->getId(),
                ':ccusto'               => $obj->getCcusto(),
                ':vinculo'              => $obj->getVinculo(),
                ':usuario_gestor_id'    => $obj->getUsuarioGestorId(),
                ':usuario_id'           => $obj->getUsuarioId(),
                ':urgencia'             => $obj->getUrgencia(),
                ':empresa_id'           => $obj->getEmpresaId(),
                ':empresa_desc'         => $obj->getEmpresaDescricao(),
                ':empresa_fone'         => $obj->getEmpresaFone(),
                ':empresa_email'        => $obj->getEmpresaEmail(),
                ':empresa_contato'      => $obj->getEmpresaContato(),
                ':data'                 => $obj->getData(),
                ':data_utilizacao'      => $obj->getDataUtilizacao(),
				':estab'				=> $obj->getEstabelecimentoId(),
				':nec_lic'				=> $obj->getNecessitaLicitacao(),
				':desc'					=> $obj->getDescricao()
            );

            $query1 = $con->execute($sql, $args);

            $i = 0;

            foreach ($obj->getProdutoId() as $prod_id) {

                $sql = 'insert into TBREQUISICAO_OC_ITEM' .
                        ' (REQUISICAO_ID, PRODUTO_ID, PRODUTO_DESCRICAO, UM, TAMANHO, QUANTIDADE, VALOR_UNITARIO, OBSERVACAO, OPERACAO_CODIGO, OPERACAO_CCUSTO, OPERACAO_CCONTABIL)' .
                        ' values(:req_id, :prod_id, :prod_desc, :um, :tam, :qtd, :vlr, :obs, :operacao, :oper_ccusto, :oper_ccont)';

                $args = array(
                    ':req_id'       => $obj->getId(),
                    ':prod_id'      => $prod_id,
                    ':prod_desc'    => $obj->getProdutoDescricao()[$i],
                    ':um'           => $obj->getUm()[$i],
                    ':tam'          => $obj->getTamanho()[$i],
                    ':qtd'          => $obj->getQuantidade()[$i],
                    ':vlr'          => $obj->getValorUnitario()[$i],
					':obs'			=> $obj->getObservacaoItem()[$i],
					':operacao'		=> $obj->getOperacaoCodigo()[$i],
					':oper_ccusto'	=> $obj->getOperacaoCcusto()[$i],
					':oper_ccont'	=> $obj->getOperacaoCcontabil()[$i]
                );

                $query2 = $con->execute($sql, $args);

                $i++;
            }

            $sql = 'UPDATE TBVINCULO V SET V.STATUSVINCULO = 1 WHERE V.TABELA = :TABELA AND V.TABELA_ID = :ID';

            $args = array(
                ':TABELA'   => 'RequisicaoDeCompra',
                ':ID'       => $obj->getVinculo()
            );

            $query3 = $con_files->execute($sql, $args);

            $con->commit();
            $con_files->commit();
            
        } catch (Exception $e) {

            $con->rollback();
            $con_files->rollback();

            throw $e;
        }
    }

    /**
     * Retorna dados do objeto na base de dados.
     * @param int $id
     * @return array
     */
    public static function exibir($id) {
		
        $con = new _Conexao();
        $con_files = new _Conexao('FILES');

        $sql = "
			select lpad(r.ID, 5, '0') ID, r.DESCRICAO, r.CCUSTO, r.USUARIO_GESTOR_ID, r.USUARIO_ID,
				r.URGENCIA, r.EMPRESA_ID, r.EMPRESA_DESCRICAO, r.EMPRESA_FONE, r.EMPRESA_EMAIL,
				r.EMPRESA_CONTATO, r.DATA, r.DATA_UTILIZACAO,coalesce(r.VINCULO_ID,0) as VINCULO_ID,
				(select first 1 lpad(u.codigo,4,0)||' - '||u.NOME GESTOR from TBUSUARIO u where u.CODIGO = r.USUARIO_GESTOR_ID) AS GESTOR,
				(select first 1 u.EMAIL from TBUSUARIO u where u.CODIGO = r.USUARIO_GESTOR_ID) AS GESTOR_EMAIL,
				(select first 1 lpad(c.codigo,8,0)||' - '||c.DESCRICAO CCUSTO_DESCRICAO from VWCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO) AS CCUSTO_DESCRICAO,
				VINCULO_ID, ESTABELECIMENTO_ID, r.NECESSITA_LICITACAO
			from TBREQUISICAO_OC r
			where r.ID = :id
		";

        $args = array(':id' => $id);

        $dado = $con->query($sql, $args);

        $sql = "
			SELECT
				LPAD(I.ID, 5, '0') REQ_ITEM_ID,
				I.REQUISICAO_ID,
				I.OC,
				LPAD(I.PRODUTO_ID,5,0) PRODUTO_ID,
				I.PRODUTO_DESCRICAO,
				I.OBSERVACAO,
				I.UM,
				I.TAMANHO,
				(Select Tam_Descricao From SP_Tamanho_Grade (P.GRADE_CODIGO, I.Tamanho)) TAMANHO_DESCRICAO,
				I.QUANTIDADE,
				I.VALOR_UNITARIO,
				I.OPERACAO_CODIGO,
				I.OPERACAO_CCUSTO,
				I.OPERACAO_CCONTABIL
			FROM
				TBREQUISICAO_OC_ITEM I
			LEFT JOIN
                TBPRODUTO P ON P.CODIGO = I.PRODUTO_ID

			WHERE
				I.REQUISICAO_ID = :ID
		";

        $args = array(':ID' => $id);

        $dado_itens = $con->query($sql, $args);

        $sql = '
			SELECT V.ID, V.ARQUIVO_ID,V.OBSERVACAO,V.USUARIO_ID FROM TBVINCULO V
			WHERE V.TABELA = :TABELA AND V.TABELA_ID = :ID and STATUSVINCULO = 1
        ';

        $vinc = $dado[0]->VINCULO_ID;
        $tabela = "RequisicaoDeCompra";


        $args = array(':ID' => $vinc,
            ':TABELA' => "$tabela");

        $arquivo_itens = $con_files->query($sql, $args);

        $sql = " SELECT lpad(i.ID, 5, '0') ID, I.LICITACAO_ID FROM TBREQUISICAO_OC_ITEM I WHERE I.REQUISICAO_ID = :REC AND I.LICITACAO_ID > 0";
        $args = array(':REC' => $id);

        $dado_edicao = $con->query($sql, $args);

        if (empty($dado_edicao)) {
            $Editar = 0;
        } else {
            $Editar = 1;
        }

        $vinculo_id = $dado[0]->VINCULO_ID;

        return array(
            'dado'			=> $dado,
            'dado_itens'	=> $dado_itens,
            'arquivo_itens' => $arquivo_itens,
            'dado_edicao'	=> $dado_edicao,
            'Editar'		=> $Editar,
            'vinculo_id'	=> $vinculo_id
        );
    }

    /**
     * Atualiza dados do objeto na base de dados.
     *
     * @param _13010 $obj
     */
    public static function alterar(_13010 $obj) {

        $con = new _Conexao();
		$con_files = new _Conexao('FILES');

        try {
            $sql = 'UPDATE TBVINCULO V SET V.STATUSVINCULO = 1 WHERE V.TABELA = :TABELA AND V.TABELA_ID = :ID';

            $args = array(
                ':TABELA' => 'RequisicaoDeCompra',
                ':ID' => $obj->getVinculo()
            );

            $query1 = $con_files->execute($sql, $args);


            if ($obj->getEditavel() == 0) {

                $sql = '
            			update TBREQUISICAO_OC
            		 	set DESCRICAO = :desc, CCUSTO = :ccusto, USUARIO_GESTOR_ID = :gestor_id,
        				    URGENCIA = :urg, EMPRESA_ID = :emp_id, EMPRESA_DESCRICAO = :emp_desc,
            			    EMPRESA_FONE = :emp_fone, EMPRESA_EMAIL = :emp_email,
            				EMPRESA_CONTATO = :emp_cont, DATA = :data, DATA_UTILIZACAO = :data_ut,
							ESTABELECIMENTO_ID = :estab, NECESSITA_LICITACAO = :nec_lic
            			where ID = :id
            	';

                $args = array(
					':desc'			=> $obj->getDescricao(),
                    ':ccusto'       => $obj->getCcusto(),
                    ':gestor_id'    => $obj->getUsuarioGestorId(),
                    ':urg'          => $obj->getUrgencia(),
                    ':emp_id'       => $obj->getEmpresaId(),
                    ':emp_desc'     => $obj->getEmpresaDescricao(),
                    ':emp_fone'     => $obj->getEmpresaFone(),
                    ':emp_email'    => $obj->getEmpresaEmail(),
                    ':emp_cont'     => $obj->getEmpresaContato(),
                    ':data'         => $obj->getData(),
                    ':data_ut'      => $obj->getDataUtilizacao(),
					':estab'		=> $obj->getEstabelecimentoId(),
					':nec_lic'		=> $obj->getNecessitaLicitacao(),
                    ':id'           => $obj->getId()
                );

                $query2 = $con->execute($sql, $args);
            }


            $i = 0;

            if (count($obj->getProdutoId()) > 0) {

                foreach ($obj->getProdutoId() as $prod_id) {

                    if (empty($obj->getReqItemId()[$i])) {

                        $sql = '
							insert into TBREQUISICAO_OC_ITEM
							(REQUISICAO_ID, PRODUTO_ID, PRODUTO_DESCRICAO, OBSERVACAO, UM, TAMANHO, QUANTIDADE, VALOR_UNITARIO, OPERACAO_CODIGO, OPERACAO_CCUSTO, OPERACAO_CCONTABIL)
							values(:req_id, :prod_id, :prod_desc, :prod_obs, :um, :tam, :qtd, :vlr, :operacao, :oper_ccusto, :oper_ccont)
                		';

                        $args = array(
                            ':req_id'       => $obj->getId(),
                            ':prod_id'      => $prod_id,
                            ':prod_desc'    => $obj->getProdutoDescricao()[$i],
							':prod_obs'     => $obj->getObservacaoItem()[$i],
                            ':um'           => $obj->getUm()[$i],
                            ':tam'          => $obj->getTamanho()[$i],
                            ':qtd'          => $obj->getQuantidade()[$i],
                            ':vlr'          => $obj->getValorUnitario()[$i],
							':operacao'		=> $obj->getOperacaoCodigo()[$i],
							':oper_ccusto'	=> $obj->getOperacaoCcusto()[$i],
							':oper_ccont'	=> $obj->getOperacaoCcontabil()[$i]
                        );

                        $query3 = $con->execute($sql, $args);
						
                    } else {

                        $sql = '
							update TBREQUISICAO_OC_ITEM
							set PRODUTO_ID = :prod_id, 
								PRODUTO_DESCRICAO = :prod_desc, 
								OBSERVACAO = :prod_obs, 
								FLAG = 0,
								UM = :um, 
								TAMANHO = :tam, 
								QUANTIDADE = :qtd, 
								VALOR_UNITARIO = :vlr, 
								OPERACAO_CODIGO = :operacao, 
								OPERACAO_CCUSTO = :oper_ccusto, 
								OPERACAO_CCONTABIL = :oper_ccont
							where 
								REQUISICAO_ID = :req_id and ID = :id
            	    	';

                        $args = array(
                            ':prod_id'      => $prod_id,
                            ':prod_desc'    => $obj->getProdutoDescricao()[$i],
							':prod_obs'     => $obj->getObservacaoItem()[$i],
                            ':um'           => $obj->getUm()[$i],
                            ':tam'          => $obj->getTamanho()[$i],
                            ':qtd'          => $obj->getQuantidade()[$i],
                            ':vlr'          => $obj->getValorUnitario()[$i],
							':operacao'		=> $obj->getOperacaoCodigo()[$i],
							':oper_ccusto'	=> $obj->getOperacaoCcusto()[$i],
							':oper_ccont'	=> $obj->getOperacaoCcontabil()[$i],
							':req_id'       => $obj->getId(),
                            ':id'           => $obj->getReqItemId()[$i]
                        );

                        $query3 = $con->execute($sql, $args);
                    }

                    $i++;
                }
            }

            $i = 0;

            if (count($obj->getArquivoID()) > 0) {

                foreach ($obj->getArquivoID() as $prod_id) {

                    $sql = 'DELETE FROM TBVINCULO WHERE ID = :item';
                    $args = array(':item' => $obj->getArquivoID()[$i]);

                    Historico::setHistorico('TBREQUISICAO_OC', $obj->getId(), 'ARQUIVO DE ID:' . $obj->getId() . ' EXCLUIDO', $con);

                    $query5 = $con_files->execute($sql, $args);
                }
            }

            $con->commit();
            $con_files->commit();

        } catch (Exception $e) {

            $con->rollback();
            $con_files->rollback();

            throw $e;
        }
    }

    /**
     * Exclui dados do objeto na base de dados.
     * @param int $id
     */
    public static function excluir($id) {
		
        $con = new _Conexao();

		try {
			
			$sql = " SELECT lpad(I.ID, 5, '0') ID,I.LICITACAO_ID FROM TBREQUISICAO_OC_ITEM I WHERE I.REQUISICAO_ID = :REC AND I.LICITACAO_ID > 0";
			$args = array(':REC' => $id);

			$dado = $con->query($sql, $args);

			$Erros = array('Erros' => '');

			if (empty($dado)) {

				$sql = 'delete from TBREQUISICAO_OC where ID = :id';
				$args = array(':id' => $id);

				$Ret = $con->execute($sql, $args);

				$sql = 'delete from TBREQUISICAO_OC_ITEM where REQUISICAO_ID = :id';
				$args = array(':id' => $id);

				$Ret = $con->execute($sql, $args);

			} else {

				foreach ($dado as $item) {
					$StrErro = 'O item de ID:' . $item->ID . ' já tem uma licitação de ID:' . $item->LICITACAO_ID;
					array_push($Erros, $StrErro);
				}
			}

			$con->commit();
			return $Erros;
			
		} catch(Exception $e) {

            $con->rollback();
            throw $e;

		}
    }

    /**
     * Pesquisa centro de custo de acordo com o que for digitado pelo usuário.
     * Função chamada via Ajax.
     *
     * @param string $filtro
     * @return array
     */
    public static function pesquisaCCusto($filtro) {
        $Palavra = $filtro ? '%' . str_replace(' ', '%', $filtro) . '%' : '%';
        $num_registros = env('NUM_REGISTROS', '20');

        $con = new _Conexao();

        $sql = 'select first :num_registros c.CODIGO, c.DESCRICAO from TBCENTRO_DE_CUSTO c
		        where (c.CODIGO like :Palavra1) or (c.DESCRICAO like :Palavra2)
				order by 2';

        $args = array(':num_registros' => $num_registros, ':Palavra1' => $Palavra, ':Palavra2' => $Palavra);

        $dado = $con->query($sql, $args);

        return $dado;
    }

    /**
     * Pesquisa gestores de acordo com o que for digitado pelo usuário.
     * Função chamada via Ajax.
     *
     * @param string $filtro
     * @return array
     */
    public static function pesquisaGestor($filtro) {
		
        $palavra = $filtro ? '%' . str_replace(' ', '%', $filtro) . '%' : '%';
        $num_registros = env('NUM_REGISTROS', '20');

        $con = new _Conexao();

        $sql = "
			SELECT FIRST :NUM_REGISTROS 
				LPAD(U.CODIGO, 3, '0') CODIGO, IIF(U.NOME <> '', U.NOME, U.USUARIO) NOME, U.EMAIL
			FROM TBUSUARIO U
			WHERE (U.CODIGO || U.NOME || U.USUARIO LIKE :PALAVRA) AND U.GESTOR = '1'
			ORDER BY 2
		";

        $args = array(
					':NUM_REGISTROS'	=> $num_registros, 
					':PALAVRA'			=> $palavra
				);

        $dado = $con->query($sql, $args);

        return $dado;
    }

    /**
     * Pesquisa produto de acordo com o que for digitado pelo usuário.
     * Função chamada via Ajax.
     *
     * @param string $filtro
     * @return array
     */
    public static function pesquisaProduto($filtro) {
        
		$palavra = $filtro ? '%' . str_replace(' ', '%', $filtro) . '%' : '%';
        $num_registros = 15;

        $con = new _Conexao();

        $sql = "
			SELECT FIRST  :NUM_REGISTROS

            f.codigo as FAMILIA_ID,
            LPAD(f.CODIGO, 3, '0')||' - '||f.descricao as FAMILIA,
            LPAD(P.CODIGO, 5, '0') CODIGO,
            P.DESCRICAO,
            P.UNIDADEMEDIDA_SIGLA

            FROM TBPRODUTO P, tbfamilia f
            WHERE 
                P.STATUS = '1'
            AND P.CODIGO || P.DESCRICAO LIKE :PALAVRA
            and f.codigo = p.familia_codigo

            ORDER BY P.DESCRICAO ascending
		";

        $args = array(':NUM_REGISTROS' => $num_registros, ':PALAVRA' => $palavra);

        $dado = $con->query($sql, $args);

        return $dado;
    }

    /**
     * Paginação com scroll.
     * Função chamada via Ajax.
     *
     * @param int $qtd_por_pagina
     * @param int $pagina
     * @return array
     */
    public static function paginacaoScroll($qtd_por_pagina, $pagina, $status, $p197) {

        $con = new _Conexao();

        $porCCusto = '';

        // Se tiver permissão para gerar OC, vê todas as requisições.
        if ($p197 != '1') {

            $porCCusto = "
                LEFT JOIN TBUSUARIO_CCUSTO UC ON UC.CCUSTO = R.CCUSTO
        
                WHERE
                    UC.USUARIO_ID = ". \Auth::user()->CODIGO;
        }

        $sql = "
			select first :qtd skip :pag
                x.ID,
                x.DESCRICAO,
                x.URGENCIA,
                x.NECESSITA_LICITACAO,
                x.DATA,
                x.OC,
                x.USUARIO,
                x.CCUSTO,
                x.CCUSTO_DESCRICAO

            from (
                select
                    lpad(r.ID, 5, '0') ID,
                    r.DESCRICAO,
                    r.URGENCIA,
                    r.NECESSITA_LICITACAO,
                    r.DATA,
                    (select first 1 list(o.OC) from TBOC o where o.REFERENCIA = 'R' AND O.REFERENCIA_ID = R.ID) OC,
                    (select first 1 u.NOME from TBUSUARIO u where u.CODIGO = r.USUARIO_ID) USUARIO,
                    r.CCUSTO,
                    (select first 1 c.DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO) CCUSTO_DESCRICAO

                from
                    TBREQUISICAO_OC r
                
                ". $porCCusto ."
            ) x

            where
                IIF(COALESCE(:STATUS, 0) = 0, 1=1, IIF(:STATUS2 = 1, x.OC is null, x.OC is not null))

            order by 
                x.ID DESC
		";

        $args = array(
            ':qtd'          => $qtd_por_pagina, 
            ':pag'          => $pagina,
            ':STATUS'       => $status,
            ':STATUS2'      => $status
        );

        $dados = $con->query($sql, $args);


        return $dados;
    }

    /**
     * Paginação com scroll.
     * Função chamada via Ajax.
     *
     * @param int $qtd_por_pagina
     * @param int $pagina
     * @return array
     */
    public static function DadosOC($Filtro) {
        $Palavra = $filtro ? '%' . str_replace(' ', '%', $filtro) . '%' : '%';
        $num_registros = env('NUM_REGISTROS', '20');

        $con = new _Conexao();

        $sql = '

                select * from
                (
                select r.ID, r.DESCRICAO, r.URGENCIA, r.NECESSITA_LICITACAO, r.DATA,
                    (select first 1 list(i.OC) OC from TBREQUISICAO_OC_ITEM i where i.REQUISICAO_ID = r.ID) as OC,
                    (select first 1 u.NOME USUARIO from TBUSUARIO u where u.CODIGO = r.USUARIO_ID) as USUARIO,
                    (select first 1 c.DESCRICAO CCUSTO_DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO) as CCUSTO_DESCRICAO
                from TBREQUISICAO_OC r
                ) s

                where (s.ID like :Palavra1) or (s.CCUSTO_DESCRICAO like :Palavra2) or (s.USUARIO like :Palavra3)
                order by s.ID DESC';



        $args = array(':Palavra1' => $Palavra, ':Palavra2' => $Palavra, ':Palavra3' => $Palavra);

        $dado = $con->query($sql, $args);

        return $dado;
    }

    /**
     * Filtrar lista de requisições.
     * Função chamada via Ajax.
     *
     * @param string $filtro
     * @return array
     */
    public static function filtraObj($filtro, $status, $p197) {

        $Palavra = $filtro ? '%' . str_replace(' ', '%', $filtro) . '%' : '%';

        $porCCusto = '';

        // Se tiver permissão para gerar OC, vê todas as requisições.
        if ($p197 != '1') {

            $porCCusto = "
                LEFT JOIN TBUSUARIO_CCUSTO UC ON UC.CCUSTO = R.CCUSTO
        
                WHERE
                    UC.USUARIO_ID = ". \Auth::user()->CODIGO;
        }

        $con = new _Conexao();

        // condição iif: status: 0 = todos; 1 = pendentes; 2 - baixados
        $sql = "
            select first 30
                x.ID,
                x.DESCRICAO,
                x.URGENCIA,
                x.NECESSITA_LICITACAO,
                x.DATA,
                x.OC,
                x.USUARIO,
                x.CCUSTO,
                x.CCUSTO||' - '||x.CCUSTO_DESCRICAO as CCUSTO_DESCRICAO

            from (
                select
                    lpad(r.ID, 5, '0') ID,
                    r.DESCRICAO,
                    r.URGENCIA,
                    r.NECESSITA_LICITACAO,
                    r.DATA,
                    (select first 1 list(o.OC) from TBOC o where o.REFERENCIA = 'R' AND O.REFERENCIA_ID = R.ID) OC,
                    (select first 1 u.NOME from TBUSUARIO u where u.CODIGO = r.USUARIO_ID) USUARIO,
                    r.CCUSTO,
                    (select first 1 c.DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO) CCUSTO_DESCRICAO

                from
                    TBREQUISICAO_OC r
                
                ". $porCCusto ."
            ) x

            where
                IIF(COALESCE(:STATUS, 0) = 0, 1=1, IIF(:STATUS2 = 1, x.OC is null, x.OC is not null))
            and (
                upper(
                    x.ID||' '||
                    x.DESCRICAO||' '||
                    x.USUARIO||' '||
                    x.CCUSTO||' '||
                    x.CCUSTO_DESCRICAO                
                ) like upper(:Palavra)
            )

            order by 
                x.ID DESC
        ";

        $args = [
            ':STATUS'   => $status, 
            ':STATUS2'  => $status, 
            ':Palavra'  => $Palavra
        ];

        return $con->query($sql, $args);
    }

    /**
     * Exclui produto.
     * Função chamada via Ajax.
     *
     * @param int $item
     */
    public static function excluiProduto($item) {

        $con = new _Conexao();

        $sql = ' SELECT I.ID,I.LICITACAO_ID FROM TBREQUISICAO_OC_ITEM I WHERE ID = :REC AND LICITACAO_ID > 0';
        $args = array(':REC' => $item);

        $dado = $con->query($sql, $args);

        $Erros = array('Erros' => '');

        if (empty($dado)) {

            $sql = 'delete from TBREQUISICAO_OC_ITEM where ID = :item';
            $args = array(':item' => $item);

            $Ret = $con->execute($sql, $args);
            $con->commit();
        } else {

            foreach ($dado as $prod) {
                $StrErro = 'O item de ID:' . $prod->ID . ' já tem uma licitação de ID:' . $prod->LICITACAO_ID;
                array_push($Erros, $StrErro);
            }
        }

        return $Erros;
    }

    
    public static function excluiArquivo($item) {

        $Erros = '';
        $con_files = new _Conexao('FILES');

        try {

            $sql = 'DELETE FROM TBVINCULO WHERE ID = :item';
            $args = array(':item' => $item);

            $Ret = $con_files->execute($sql, $args);

            $con_files->commit();
			
        } catch (Exception $e) {
            $con_files->rollback();
            throw $e;
        }

        return $Erros;
    }

    
    public static function downloadArquivo($item) {

        $con_files = new _Conexao('FILES');

        $sql = 'SELECT a.arquivo,a.conteudo,a.tamanho,a.extensao FROM tbarquivo a where a.id =  (select v.arquivo_id from tbvinculo v where v.id = :item)';
        $args = array(':item' => $item);

        $dado = $con_files->query($sql, $args);

        $nome = $dado[0]->ARQUIVO;
        $conteudo = $dado[0]->CONTEUDO;
        $tamanho = $dado[0]->TAMANHO;
        $extensao = $dado[0]->EXTENSAO;

        return array('nome' => $nome, 'conteudo' => $conteudo, 'tamanho' => $tamanho, 'extensao' => $extensao);
    }

    
    public static function enviaArquivo($vin, $file_type, $file_tmp, $file_name, $file_size, $binario, $Tabela) {
        
		$con_files = new _Conexao('FILES');

        if ($file_type = '') {
            $file_type = 'unknown';
        }

        //$arquivo_id = DB::connection('firebird')->select('select gen_id(GTBARQUIVOS, 1) ID from RDB$DATABASE');
        $arquivo_id = $con_files->query('select gen_id(GTBARQUIVOS, 1) ID from RDB$DATABASE');

        $sql_arq = 'insert into tbvinculo (tabela,tabela_id,arquivo_id,sequencia,observacao,datahora,usuario_id)' .
                'values (:tabela,:tabela_id,:arquivo_id,:sequencia,:observacao,:datahora,:usuario_id)';

        $args_arq = array(
            ':tabela' => $Tabela,
            ':tabela_id' => $vin,
            ':arquivo_id' => $arquivo_id[0]->ID,
            ':sequencia' => 1,
            ':observacao' => 'Incluido via Web',
            ':datahora' => "now",
            ':usuario_id' => Auth::user()->CODIGO
        );

        $Ret = $con_files->execute($sql_arq, $args_arq);

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

        $Ret = $con_files->execute($sql_arq, $args_arq);

        $con_files->commit();
    }

}
