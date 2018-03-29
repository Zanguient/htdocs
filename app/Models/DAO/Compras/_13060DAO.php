<?php

namespace App\Models\DAO\Compras;

use App\Models\DTO\Compras\_13060;
use App\Models\Conexao\_Conexao;

class _13060DAO
{	
    
	public static function listar() 
	{
        //
	}
    
	public static function gerarId()
	{
        //
	}
    
	public static function gravar(_13060 $obj)
	{
        //
	}
    
	public static function exibir($id)
	{
        //
	}
    
	public static function alterar(_13060 $obj)
	{
        //
	}
    
	public static function excluir($id)
	{
        //
	}
    
	public static function pesquisaEmpresa($obj) 
    {
        $con = new _Conexao();
        
        return self::exibirEmpresa($con, $obj);
	}
    
	public static function paginacaoScroll($qtd_por_pagina, $pagina)
    {
        //
	}
    
    public static function exibirEmpresa(_Conexao $con, _13060 $obj) {
        
		$first  = 30;//env('NUM_REGISTROS', '20');
		$filtro = $obj->getFiltro() == null ? '' : ($obj->getFiltro()        ? "AND X.FILTRO LIKE '" . $obj->getFiltro() . "'" : '');
        $status = $obj->getStatus() == null ? '' : ($obj->getStatus() == '1' ? "AND E.STATUS = '1'" : "AND E.STATUS = '0'");
        
        $sql = 
        "
            SELECT FIRST :FIRST
                CODIGO,
                RAZAOSOCIAL,
                NOMEFANTASIA,
                FONE,
                EMAIL,
                CONTATO,
                CIDADE,
                UF,
                CNPJ

            FROM
                (SELECT
                    LPAD(E.CODIGO, 5, 0) CODIGO,
                    E.RAZAOSOCIAL,
                    E.NOMEFANTASIA,
                    E.FONE,
                    E.EMAIL,
                    E.CONTATO,
                    E.CIDADE,
                    E.UF,
                    E.CNPJ,
                    E.STATUS,
                    
                   (E.CODIGO        || ' ' ||
                    E.RAZAOSOCIAL   || ' ' ||
                    E.NOMEFANTASIA
                    ) FILTRO
                    
                FROM
                    TBEMPRESA E
                    
                WHERE
                    E.CODIGO > 0
                    /*STATUS*/          
                    AND E.HABILITA_FORNECEDOR = '1'
                )X

            WHERE
                X.CODIGO > 0
                /*FILTRO*/

            ORDER BY RAZAOSOCIAL                
        ";
        
        $sql = str_replace('/*FILTRO*/', $filtro, $sql);
        $sql = str_replace('/*STATUS*/', $status, $sql);
        
		$args = array(
			':FIRST'	=> $first
		);
        
		return $con->query($sql, $args);
    }
	
}

