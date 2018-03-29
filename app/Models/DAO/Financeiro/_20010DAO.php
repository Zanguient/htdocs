<?php

namespace App\Models\DAO\Financeiro;

use App\Models\DTO\Financeiro\_20010;
use App\Models\Conexao\_Conexao;

class _20010DAO
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
        
        return self::exibirPagamentoForma($con, $obj);
	}
    
	public static function paginacaoScroll($qtd_por_pagina, $pagina)
    {
        //
	}
    
    public static function exibirPagamentoForma(_Conexao $con, _20010 $obj = null) {
        
        if ( !$obj ) {
            $obj = new _20010;
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
                    TBPAGAMENTO_FORMA A

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

