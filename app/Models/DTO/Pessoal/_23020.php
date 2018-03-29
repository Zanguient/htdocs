<?php

namespace App\Models\DTO\Pessoal;

use App\Models\DAO\Pessoal\_23020DAO;

/**
 * Objeto _23020 - Registro de Producao - Div. Bojo Colante
 */
class _23020
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
    

    public function getTaloesComposicao($param1) {
        
        
        unset($param1->TALAO_ID);
        
        $arr_taloes      = $this->selectTalao($param1);
        $arr_consumos    = [];
        $arr_alocados    = [];
        $arr_historicos  = [];
        $arr_detalhes    = [];
        $arr_componentes = [];
        
        foreach ( $arr_taloes as $talao ) {
        
            $talao_composicao = $this->getTalaoComposicao($talao);
            
            $arr_consumos    = array_merge($arr_consumos    ,$talao_composicao->CONSUMOS   );
            $arr_alocados    = array_merge($arr_alocados    ,$talao_composicao->ALOCADOS   );
            $arr_historicos  = array_merge($arr_historicos  ,$talao_composicao->HISTORICOS );
            $arr_detalhes    = array_merge($arr_detalhes    ,$talao_composicao->DETALHES   );
            $arr_componentes = array_merge($arr_componentes ,$talao_composicao->COMPONENTES);
        }
        
        $ret = [
            'TALOES'      => $arr_taloes,
            'CONSUMOS'    => $arr_consumos,
            'ALOCADOS'    => $arr_alocados,
            'HISTORICOS'  => $arr_historicos,
            'DETALHES'    => $arr_detalhes,
            'COMPONENTES' => $arr_componentes
        ];
        
        return $ret;
    }
    

    public function getTalaoComposicao($talao, $return_talao = false) {
        
        $ret = (object)[];
        
        if ( $return_talao ) {
            
            $arr_talao = $this->selectTalao($talao);
            
            if ( !isset($arr_talao[0]) ) {
                log_erro('Talão não localizado.');
            }
            
            $talao = $arr_talao[0];
            
            $ret->TALAO = $talao;
        }
        
        $ret->CONSUMOS = $this->selectTalaoConsumo((object)[
            'TALAO_ID' => $talao->TALAO_ID,
        ]);

        $ret->ALOCADOS = $this->selectTalaoConsumoAlocacao((object)[
            'TALAO_ID' => $talao->TALAO_ID,
        ]);

        $ret->HISTORICOS = $this->selectTalaoHistorico((object)[
            'TALAO_ID' => $talao->TALAO_ID
        ]);

        $ret->DETALHES = $this->selectTalaoDetalhe((object)[
            'REMESSA_ID'       => $talao->REMESSA_ID,
            'REMESSA_TALAO_ID' => $talao->REMESSA_TALAO_ID
        ]);
        
        $ret->COMPONENTES = $this->selectTalaoConsumoComponente((object)[
            'REMESSA_ID'       => $talao->REMESSA_ID,
            'REMESSA_TALAO_ID' => $talao->REMESSA_TALAO_ID
        ]);
        
        return $ret;
    }
    
    public function postTalaoLiberar($param) {
        $this->spuRemessaTalaoLiberar($param);
        $this->spuRemessaTalaoDetalheLiberar($param);
    }    
    
    public function selectColaborador($param1) {
        
        $sql = "
            SELECT FIRST :FIRST SKIP :SKIP
                C.CODIGO COLABORADOR_ID,
                C.PESSOAL_NOME COLABORADOR_NOME,
                TRIM(C.PESSOAL_SEXO) COLABORADOR_SEXO,
                C.CRACHA COLABORADOR_CRACHA
            FROM
                TBCOLABORADOR C
            WHERE TRUE
            /*@COLABORADOR_CRACHA*/
        ";
        
        $param = (object)[];

        if ( isset($param1->COLABORADOR_CRACHA) && $param1->COLABORADOR_CRACHA > -1 ) {
            $param->COLABORADOR_CRACHA = " = $param1->COLABORADOR_CRACHA";
        }
           
        $colaborador_cracha = array_key_exists('COLABORADOR_CRACHA', $param) ? "AND C.CRACHA $param->COLABORADOR_CRACHA" : '';
        
        $args = [
            'FIRST' => setDefValue($param1->FIRST,50),
            'SKIP'  => setDefValue($param1->SKIP,0),
            '@COLABORADOR_CRACHA' => $colaborador_cracha
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    
    public function updateColaboradorCentroDeTrabalho($param) {
        
        $sql = "
            UPDATE OR INSERT INTO TBPONTO_CARTAO (
                COLABORADOR_CODIGO,
                DATA,
                CCUSTO_PRODUCAO
            ) VALUES (
                :COLABORADOR_ID,
                CURRENT_DATE,
                :CCUSTO_PRODUCAO
            ) MATCHING(
                COLABORADOR_CODIGO,
                DATA
            );
        ";
        
        $args = [
            'COLABORADOR_ID'  => $param->COLABORADOR_ID,
            'CCUSTO_PRODUCAO' => $param->CCUSTO_PRODUCAO,
        ];    
        
        return $this->con->query($sql,$args);
    }     
    
}