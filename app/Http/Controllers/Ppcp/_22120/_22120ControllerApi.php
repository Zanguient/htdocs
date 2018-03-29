<?php

namespace app\Http\Controllers\Ppcp\_22120;

use App\Http\Controllers\Ppcp\_22120\_22120Controller as Ctrl;
use App\Models\DTO\Ppcp\_22120;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Ppcp\_22030;
use App\Http\Controllers\Ppcp\_22120Controller;


/**
 * Controller do objeto _22120 - Geracao de Remessas de Bojo
 */
class _22120ControllerApi extends Ctrl {
      
    
    public function getRemessasVinculo() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22120 = new _22120($this->con());

            $ret = $dto_22120->selectRemessasVinculo($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    
    public function getTaloesVinculo() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22120 = new _22120($this->con());

            validator($request, [
                'REMESSA_ID_ORIGEM' => ['Id da Remessa Principal','required'],
                'REMESSA_ID'        => ['Id da Remessa Vinculada','required'],
                'GP_ID'             => ['Id do Grupo de Produção','required'],
            ],true);  
            
            $taloes = $dto_22120->selectTaloesVinculo($request);

            $estacoes = _22030::listar([
                'RETORNO'   => ['GP_UP_ESTACAO'],
                'STATUS'    => [1],
                'UP_STATUS' => [1],
                'GP'        => [$request->GP_ID],
                //'PERFIL'  => $consumo->PERFIL Todas estações deverão vir na consulta independente do perfil, por esse motivo essa linha está comentada.
            ])->GP_UP_ESTACAO;            
            
            
            $ret = [
                'TALOES'    => $taloes,
                'ESTACOES'  => $estacoes
            ];
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getOrigemNecessidade() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22120 = new _22120($this->con());


            validator($request, [
                'REMESSA_TIPO' => ['Tipo de Remessa'         ,'required'],
                'GP_ID'        => ['Id do Grupo de Produção' ,'required'],
                'FAMILIA_ID'   => ['Id da Família da Remessa','required'],
                
            ],true);  
            
            $ret = (object)[];
            
            switch ($request->REMESSA_TIPO) {
                // Componente
                case 1:

                    validator($request, [
                        'ORIGEM'     => ['Remessa de Origem'       ,'required'],
                        'REMESSA_ID' => ['Id da Remessa de Origem' ,'required'],
                        'REQUISICAO' => ['Requisicão'              ,'required'],
                    ],true);          
                    
                    $ret->SKUS = $dto_22120->selectConsumoNecessidade((object)[
                        'REMESSA'    => $request->ORIGEM,
                        'REMESSA_ID' => $request->REMESSA_ID,
                        'FAMILIA_ID' => $request->FAMILIA_ID,
                        'REQUISICAO' => $request->REQUISICAO,
                    ]);
                    
                    $ret->CONSUMOS = $dto_22120->selectConsumo((object)[
                        'REMESSA_ID'         => $request->REMESSA_ID,
                        'FAMILIA_ID_CONSUMO' => $request->FAMILIA_ID,
                        'STATUS_CONSUMO'     => '0',
                    ]);
                    
                    break;
                
                // Pedido
                case 2:

                    validator($request, [
                        'ORIGEM'     => ['Número do Pedido','required'],
                    ],true);                      

                    $ret->SKUS = $dto_22120->selectPedidoNecessidade((object)[
                        'PEDIDO'     => $request->ORIGEM,
                        'FAMILIA_ID' => $request->FAMILIA_ID,
                    ]);                    
                    
                    break;
                
                // Reposicao de Estoque
                case 3:
                    
                    validator($request, [
                        'ESTABELECIMENTO_ID' => ['Id do Estabelecimento','required'],
                    ],true);    

                    $ret->SKUS = $dto_22120->selectReposicaoNecessidade((object)[
                        'ESTABELECIMENTO_ID' => $request->ESTABELECIMENTO_ID,
                        'FAMILIA_ID'         => $request->FAMILIA_ID,
                    ]);   

                    break;
                
                // Requisição
                case 4:

                    $ret->SKUS = $dto_22120->selectRequisicaoNecessidade((object)[
                        'FAMILIA_ID' => $request->FAMILIA_ID,
                    ]);   

                    break;

                default:
                    log_erro('Tipo de remessa inválido');
                    
                    break;
            }
            
            $ret->ESTACOES = _22030::listar([
                'RETORNO'   => ['GP_UP_ESTACAO'],
                'STATUS'    => [1],
                'UP_STATUS' => [1],
                'GP'        => [$request->GP_ID],
                //'PERFIL'  => $consumo->PERFIL Todas estações deverão vir na consulta independente do perfil, por esse motivo essa linha está comentada.
            ])->GP_UP_ESTACAO;            
            
            
            $this->con()->commit();
                        
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    
    
    public function getOrigemDados() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22120 = new _22120($this->con());

            validator($request, [
                'REMESSA_TIPO' => ['Tipo de Remessa','required'],
            ],true);  
            
            $ret = (object)[];
            
            switch ($request->REMESSA_TIPO) {
                // Componente
                case 1:

                    validator($request, [
                        'ORIGEM' => ['Remessa de Origem','required'],
                    ],true);          
                    
                    $ret->FAMILIAS = $dto_22120->selectConsumoFamilia((object)[
                        'REMESSA' => $request->ORIGEM
                    ]);
                    
                    break;
                
                // Pedido
                case 2:

                    validator($request, [
                        'ORIGEM' => ['Número do Pedido','required'],
                    ],true);                      

                    $ret->FAMILIAS = $dto_22120->selectPedidoFamilia((object)[
                        'PEDIDO' => $request->ORIGEM
                    ]);                    
                    
                    break;
                
                // Reposicao de Estoque
                case 3:

                    $ret->FAMILIAS = $dto_22120->selectReposicaoFamilia();   

                    break;
                
                // Requisição
                case 4:

                    $ret->FAMILIAS = $dto_22120->selectRequisicaoFamilia();   

                    break;

                default:
                    log_erro('Tipo de remessa inválido');
                    
                    break;
            }
            
                        
            $this->con()->commit();
                        
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postRemessaIntermediaria(){
        $this->Menu()->incluir('Gravando remessa intermediária');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);
            
            $dados = $request->DADOS;
            
            
            validator($dados, [
                'REMESSA_ID_ORIGEM' => ['Id da remessa de origem','required'],
                'REMESSA_ID'        => ['Id da remessa vinculada','required'],
                'GP_ID'             => ['Id do grupo de produção','required'],
                'UPS'               => ['UPs','required'],
            ],true);
            
            $ups = $dados->UPS;
            
            $programado = false;
            $controle = 1;
            $dto_22120 = new _22120($this->con());
            foreach ( $ups as $up ) {
                
                validator($up, [
                    'ESTACOES' => ['Estações','required']
                ],true);

                $estacoes = $up->ESTACOES;
                
                foreach ( $estacoes as $estacao ) {

                    if ( isset($estacao->TALOES) ) {

                        $taloes = $estacao->TALOES;

                        foreach ( $taloes as $talao ) {

                            $talao->OBSERVACAO          = $dados->REMESSA_ID_ORIGEM . '-' . $dados->REMESSA_ID;
                            $talao->GP_ID               = $dados->GP_ID;
                            $talao->UP_ID               = $up->UP_ID;
                            $talao->ESTACAO             = $estacao->ESTACAO;
                            $talao->MODELO_ID           = $talao->TALAO_MODELO_ID;
                            $talao->TAMANHO             = $talao->TALAO_TAMANHO;
                            $talao->REMESSA_TALAO_ID    = $talao->TALAO_CONTROLE;
                            $talao->QUANTIDADE          = $talao->TALAO_QUANTIDADE;

                            $dto_22120->insertRemessaTalaoIntermediario($talao);
                            
                            if ( !$programado ) {
                                $programado = true;
                            }
                        }
                    }
                }
            }
                  
            if ( !$programado ) {
                log_erro('Nenhuma talão foi progrmado.');
            }
           
            $dto_22120->spiRemessaIntermediaria();
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                
                $ctrl_22120 = new _22120Controller();
                
                $ret->DATA_RETURN = [
                    'DADOS'      => $ctrl_22120->getRemessasVinculo((array)$request->FILTRO,$this->con())
                ];
            }
            
            $ret->SUCCESS_MSG = 'Remessa Intermediária Gerada com Sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();

                        
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postConsumoAlterar(){
        $this->Menu()->incluir('Alterando consumo');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);
            
            $dados = $request->DADOS;
            
            
            validator($dados, [
                'PRODUTO_ID' => ['Id do Produto','required'],
                'TAMANHO'    => ['Id do Tamanho','required'],
                'CONSUMOS'   => ['Consumos','required']
            ],true);
            
            $consumos = $dados->CONSUMOS;
            
            $dto_22120 = new _22120($this->con());
            foreach ( $consumos as $consumo ) {
                
                validator($consumo, [
                    'ID' => ['Id do Consumo','required']
                ],true);
                
                
                $dto_22120->updateRemessaConsumo((object)[
                    'ID'         => $consumo->ID,
                    'UPD_PRODUTO_ID' => $dados->PRODUTO_ID,
                    'UPD_TAMANHO'    => $dados->TAMANHO,
                ]);
            }

            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                
                $ctrl_22120 = new _22120Controller();
                
                $ret->DATA_RETURN = [
                    'DADOS'      => $ctrl_22120->getRemessasVinculo((array)$request->FILTRO,$this->con())
                ];
            }
            
            $ret->SUCCESS_MSG = 'Consumos alterados com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();

                        
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postTalaoLiberacaoCancelar(){
        $this->Menu()->incluir('Cancelando liberação de talões');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'TALOES'   => ['Talões','required']
            ],true);
            
            $taloes = $dados->TALOES;
            
            $dto_22120 = new _22120($this->con());
            foreach ( $taloes as $talao ) {
                
                validator($talao, [
                    'REMESSA_ID'       => ['Id da Remessa','required'],
                    'REMESSA_TALAO_ID' => ['Controle do Talão','required']
                ],true);
                
                
                $dto_22120->updateRemessaTalaoLiberacaoCancelar((object)[
                    'REMESSA_ID'       => $talao->REMESSA_ID,
                    'REMESSA_TALAO_ID' => $talao->REMESSA_TALAO_ID
                ]);
            }

            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                
                $ctrl_22120 = new _22120Controller();
                
                $ret->DATA_RETURN = [
                    'DADOS'      => $ctrl_22120->getRemessasVinculo((array)$request->FILTRO,$this->con())
                ];
            }
            
            $ret->SUCCESS_MSG = 'Liberação cancelada com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();

                        
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
}