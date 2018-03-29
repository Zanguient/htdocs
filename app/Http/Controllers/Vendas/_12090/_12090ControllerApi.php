<?php

namespace app\Http\Controllers\Vendas\_12090;

use App\Http\Controllers\Vendas\_12090\_12090Controller as Ctrl;
use App\Models\DTO\Vendas\_12090;


/**
 * Controller do objeto _12090 - Geracao de Remessas de Bojo
 */
class _12090ControllerApi extends Ctrl {
        
    public function getEmpresas() {
        try {     
            
            $request = $this->request();
            
            $dto_12090 = new _12090($this->con());

            $ret = $dto_12090->selectEmpresas($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getEmpresa() {
        try {     
            
            $request = $this->request();
            
            $dto_12090 = new _12090($this->con());


            validator($request, [
                'EMPRESA_ID' => ['Id da Empresa','required']
            ],true);              

            $ret = $dto_12090->selectEmpresa($request);
            
            if ( !isset($ret[0]) ) {
                log_erro('Empresa não localizada.');
            }
            
            $ret = $ret[0];
            
            $this->con()->commit();
                        
            return (array)$ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    
    
    public function getModelosPreco() {
        try {     
            
            $request = $this->request();
            
            $dto_12090 = new _12090($this->con());


            validator($request, [
                'EMPRESA_ID' => ['Id da Empresa','required']
            ],true);              

            $ret = $dto_12090->selectModeloPreco($request);
            
            if ( !isset($ret[0]) ) {
                log_erro('Precos de Produtos não localizado.');
            }
                        
            $this->con()->commit();
                        
            return (array)$ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    

    public function insertCota() {
        $this->Menu()->incluir('Vs2.0 - Incluindo cota');
        try {     
            /**
             * Preparação da entrada dos dados
             */
            $dto_12090 = new _12090($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'CCUSTO'    => ['Centro de Custo'      ,'required'],
                'CCONTABIL' => ['Conta Contábil'       ,'required'],
                'MES_1'     => ['Mes Inicial'          ,'required'],
                'ANO_1'     => ['Ano Inicial'          ,'required'],
                'MES_2'     => ['Mes Final'            ,'required'],
                'ANO_2'     => ['Ano FInal'            ,'required'],
                'NOTIFICA'  => ['Status para Nificação','required'],
                'BLOQUEIA'  => ['Status para Bloqueio' ,'required'],
                'DESTACA'   => ['Status para Destaque' ,'required'],
                'TOTALIZA'  => ['Status para Totalizar','required'],
                'VALOR'     => ['Valor da Cota'        ,'required']
            ],true);
            
            $dto_12090->insertCota($dados);
                        
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS' => $dto_12090->selectCotas($this->prepareRequestCotas((object)$request->FILTRO))
                ];
            }
            
            $ret->SUCCESS_MSG = 'Inclusão realizada com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    

    public function updateCota() {
        $this->Menu()->alterar('Vs2.0 - Alterando cota');
        try {     
            /**
             * Preparação da entrada dos dados
             */
            $dto_12090 = new _12090($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'ITENS' => ['Itens','required']
            ],true);
            
            $cotas = $dados->ITENS;
            
            
            /**
             * Tratamento dos dados
             */
            foreach ( $cotas as $cota ) {
                
                validator($cota, [
                    'ID'               => ['Id da Cota'        ,'required'],
                    'TOTALIZA'         => ['Totaliza Cota'     ,'required'],
                    'BLOQUEIA'         => ['Bloqueia Cota'     ,'required'],
                    'NOTIFICA'         => ['Notifica Cota'     ,'required'],
                    'DESTAQUE'         => ['Destaca Cota'      ,'required'],
                    'VALOR'            => ['Valor da Cota'     ,'required'],
//                    'OBSERVACAO_GERAL' => ['Observação da Cota','required'],
                ],true);
            
                $dto_12090->updateCota($cota);
            }
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS' => $dto_12090->selectCotas($this->prepareRequestCotas((object)$request->FILTRO)),
                    'COTA'  => $dto_12090->selectCota($this->prepareRequestCota($request->FILTRO_COTA))
                ];
            }
            
            $ret->SUCCESS_MSG = 'Alteração realizada com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    

    public function deleteCota() {
        $this->Menu()->excluir('Vs2.0 - Excluindo cota');
        try {     
            /**
             * Preparação da entrada dos dados
             */
            $dto_12090 = new _12090($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'ITENS' => ['Itens','required']
            ],true);
            
            $cotas = $dados->ITENS;
            
            
            /**
             * Tratamento dos dados
             */
            foreach ( $cotas as $cota ) {
                
                validator($cota, [
                    'ID' => ['Id da Cota','required']
                ],true);
            
                $dto_12090->deleteCota($cota);
            }            
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS' => $dto_12090->selectCotas($this->prepareRequestCotas((object)$request->FILTRO)),
                    'COTA'  => $dto_12090->selectCota($this->prepareRequestCota($request->FILTRO_COTA))
                ];
            }
            
            $ret->SUCCESS_MSG = 'Exclusão realizada com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    

    public function insertCotaExtra() {
        $this->Menu()->consultar('Vs2.0 - Inserindo cota extra');
        userControl(221,true);
        try {     
            /**
             * Preparação da entrada dos dados
             */
            $dto_12090 = new _12090($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $item = $request->DADOS;

            validator($item, [
                'ID'         => ['Id da Cota'              ,'required'],
                'VALOR'      => ['Valor da Cota Extra'     ,'required'],
                'OBSERVACAO' => ['Observação da Cota Extra','required'],
            ],true);

            $dto_12090->insertCotaExtra($item);
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS' => $dto_12090->selectCotas($this->prepareRequestCotas((object)$request->FILTRO)),
                    'COTA'  => $dto_12090->selectCota($this->prepareRequestCota($request->FILTRO_COTA))
                ];
            }
            
            $ret->SUCCESS_MSG = 'Cota extra incluída com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    

    public function deleteCotaExtra() {
        $this->Menu()->consultar('Vs2.0 - Excluindo cota extra');
        userControl(221,true);
        try {     
            /**
             * Preparação da entrada dos dados
             */
            $dto_12090 = new _12090($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $item = $request->DADOS;

            validator($item, [
                'ID' => ['Id da Cota Extra','required'],
            ],true);

            $dto_12090->deleteCotaExtra($item);
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS' => $dto_12090->selectCotas($this->prepareRequestCotas((object)$request->FILTRO)),
                    'COTA'  => $dto_12090->selectCota($this->prepareRequestCota($request->FILTRO_COTA))
                ];
            }
            
            $ret->SUCCESS_MSG = 'Cota extra excluída com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    

    public function insertCotaReducao() {
        $this->Menu()->consultar('Vs2.0 - Inserindo cota redução');
        userControl(222,true);
        try {     
            /**
             * Preparação da entrada dos dados
             */
            $dto_12090 = new _12090($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $item = $request->DADOS;

            validator($item, [
                'ID'         => ['Id da Cota'                ,'required'],
                'VALOR'      => ['Valor da Cota Reducao'     ,'required'],
                'OBSERVACAO' => ['Observação da Cota Reducao','required'],
            ],true);

            $dto_12090->insertCotaReducao($item);
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS' => $dto_12090->selectCotas($this->prepareRequestCotas((object)$request->FILTRO)),
                    'COTA'  => $dto_12090->selectCota($this->prepareRequestCota($request->FILTRO_COTA))
                ];
            }
            
            $ret->SUCCESS_MSG = 'Cota redução incluída com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    

    public function deleteCotaReducao() {
        $this->Menu()->consultar('Vs2.0 - Excluindo redução de cota');
        userControl(222,true);
        try {     
            /**
             * Preparação da entrada dos dados
             */
            $dto_12090 = new _12090($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $item = $request->DADOS;

            validator($item, [
                'ID' => ['Id da Redução de cota','required'],
            ],true);

            $dto_12090->deleteCotaReducao($item);
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS' => $dto_12090->selectCotas($this->prepareRequestCotas((object)$request->FILTRO)),
                    'COTA'  => $dto_12090->selectCota($this->prepareRequestCota($request->FILTRO_COTA))
                ];
            }
            
            $ret->SUCCESS_MSG = 'Redução de cota excluída com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    

    public function getCotaGgfDetalhe() {
        $this->Menu()->consultar('Vs2.0 - Consultando Detalhamento do GGF');
        try {     
            
            $request = $this->request();
            
            $dto_12090 = new _12090($this->con());

            $ret = $dto_12090->selectCotaGgfDetalhe($this->prepareRequestCotaGgfDetalhe($request));
            
            $this->con()->commit();
                        
            return (array)$ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }       
    
    private function prepareRequestCotas($request) {

        $time_inicial	  = strtotime($request->ANO_1.'-'.$request->MES_1.'-01');
        $time_final		  = strtotime($request->ANO_2.'-'.$request->MES_2.'-01');    		

        $request->DATA_1	= date('Y.m.01',$time_inicial);
        $request->DATA_2	= date('Y.m.t',$time_final); 
        
        return $request;
    }
    
    private function prepareRequestCota($request) {

        validator($request, [
            'ID'        => ['Id da Cota'     ,'required'],
            'CCUSTO'    => ['Centro de Custo','required'],
            'CCONTABIL' => ['Conta Contábil' ,'required'],
            'MES'       => ['Mes'            ,'required'],
            'ANO'       => ['Ano'            ,'required'],
        ],true);              


        $time_inicial	  = strtotime($request->ANO.'-'.$request->MES.'-01');
        $time_final		  = strtotime($request->ANO.'-'.$request->MES.'-01');    		

        $request->DATA_1	= date('Y.m.01',$time_inicial);
        $request->DATA_2	= date('Y.m.t',$time_final); 
        
        return $request;
    }
    
    private function prepareRequestCotaGgfDetalhe($request) {

        validator($request, [
            'CCUSTO'     => ['Centro de Custo','required'],
            'FAMILIA_ID' => ['Id da Família' ,'required'],
            'MES'        => ['Mes'            ,'required'],
            'ANO'        => ['Ano'            ,'required'],
        ],true);              


        $time_inicial	  = strtotime($request->ANO.'-'.$request->MES.'-01');
        $time_final		  = strtotime($request->ANO.'-'.$request->MES.'-01');    		

        $request->DATA_1	= date('Y.m.01',$time_inicial);
        $request->DATA_2	= date('Y.m.t',$time_final); 
        
        return $request;
    }
}