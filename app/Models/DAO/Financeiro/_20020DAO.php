<?php

namespace App\Models\DAO\Financeiro;

use App\Models\DTO\Financeiro\_20020;
use App\Models\Conexao\_Conexao;

class _20020DAO
{	
    
	public static function gerarId()
	{
        //
	}
    
	public static function gravar(Empresa $obj)
	{
        //
	}
    
	public static function exibir($id)
	{
        //
	}
    
	public static function alterar(Empresa $obj)
	{
        //
	}
    
	public static function excluir($id)
	{
        //
	}
    
	public static function listar($obj = null) 
    {
        $con = new _Conexao();
        
        return self::exibirPagamentoCondicao($con, $obj);
	}
    
	public static function paginacaoScroll($qtd_por_pagina, $pagina)
    {
        //
	}
    
    public static function exibirPagamentoCondicao(_Conexao $con, _20020 $obj = null) {
        
        if ( !$obj ) {
            $obj = new _20020;
        }
        
		$first  = 300;//env('NUM_REGISTROS', '20');
		$filtro = $obj->getFiltro() == null ? '' : ($obj->getFiltro()        ? "AND X.FILTRO LIKE '" . $obj->getFiltro() . "'" : '');
        $status = $obj->getStatus() == null ? '' : ($obj->getStatus() == '1' ? "AND E.STATUS = '1'" : "AND E.STATUS = '0'");
        
        $sql = 
        "
            SELECT FIRST :FIRST
                CODIGO ID,
                DESCRICAO,
                STATUS
            FROM
                (SELECT
                    A.CODIGO,
                    A.DESCRICAO,
                    A.STATUS,
                    (A.CODIGO || ' ' ||
                     A.DESCRICAO) FILTRO

                FROM
                    TBPAGAMENTO_CONDICAO A

                WHERE
                    A.CODIGO > 0
                /*STATUS*/)X

            WHERE
                X.CODIGO > 0
            /*FILTRO*/       
            
            ORDER BY DESCRICAO
        ";
        
        $sql = str_replace('/*FILTRO*/', $filtro, $sql);
        $sql = str_replace('/*STATUS*/', $status, $sql);
        
		$args = array(
			':FIRST'	=> $first
		);
        
		return $con->query($sql, $args);
    }
	
}

