<?php

namespace app\Http\Controllers\Ppcp\_22010;

use App\Http\Controllers\Ppcp\_22010\Controller as Ctrl;
use App\Models\DTO\Ppcp\_22010;
use App\Models\DTO\Ppcp\_22040;


/**
 * Controller do objeto _22010 - Geracao de Remessas de Bojo
 */
class ControllerApi extends Ctrl {
        
    public function getTalaoProduzirAll() {
        $this->Menu()->consultar('Consultando talões a produzir');
                
        try {      
            
            $request = $this->request(true);
                  
            $param = (object)[
                'RETORNO'            => ['TALAO'],
                'STATUS'             => [1] // Status 1 = Em Aberto
            ];

            isset($request->estabelecimento_id) 					? $param->ESTABELECIMENTO_ID	= $request->estabelecimento_id	: null;
            isset($request->gp_id)									? $param->GP_ID					= $request->gp_id				: null;
            isset($request->up_id)									? $param->UP_ID					= $request->up_id				: null;
            isset($request->up_todos)								? $param->UP_TODOS				= $request->up_todos			: null;
            isset($request->up_origem)								? $param->UP_ORIGEM				= $request->up_origem			: null;
            isset($request->estacao)								? $param->ESTACAO				= $request->estacao				: null;
            isset($request->estacao_todos)							? $param->ESTACAO_TODOS			= $request->estacao_todos		: null;
            isset($request->remessa) && !empty($request->remessa)	? $param->REMESSA				= [$request->remessa]			: null;
            isset($request->data_ini)								? $param->DATA_INI				= $request->data_ini			: null;
            isset($request->data_fim)								? $param->DATA_FIM				= $request->data_fim			: null;

            $res = _22040::listar($param)->TALAO;
 
            return response()->json($res);
        }
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }
        
    public function getTalaoProduzidoAll() {
        $this->Menu()->consultar('Consultando talões produzidos');
                
        try {      
            
            $request = $this->request(true);

            $param = (object)[
                'RETORNO'            => ['TALAO'],
                'STATUS'             => [2] // Status 2 = Produzido
            ];

            !empty($request->estabelecimento_id)? $param->ESTABELECIMENTO_ID	= $request->estabelecimento_id	: null;
            !empty($request->gp_id)			 	? $param->GP_ID					= $request->gp_id				: null;
            !empty($request->up_id)		 		? $param->UP_ID					= $request->up_id				: null;
            isset($request->up_todos)			? $param->UP_TODOS				= $request->up_todos			: null;
            !empty($request->estacao)			? $param->ESTACAO				= $request->estacao				: null;
            isset($request->estacao_todos)		? $param->ESTACAO_TODOS			= $request->estacao_todos		: null;
            !empty($request->data_ini)			? $param->data_ini				= $request->data_ini			: null;
            !empty($request->data_fim)			? $param->data_fim				= $request->data_fim			: null;
            isset($request->data_producao)		? $param->DATA_PRODUCAO			= \DateTime::createFromFormat('d/m/Y', $request->data_producao)->format('Y-m-d')	: null;
            isset($request->turno)				? $param->TURNO					= $request->turno				: null;
            isset($request->turno_hora_ini)		? $param->TURNO_HORA_INI		= $request->turno_hora_ini		: null;
            isset($request->turno_hora_fim)		? $param->TURNO_HORA_FIM		= $request->turno_hora_fim		: null;

            $res = _22040::listar($param)->TALAO;
 
            return response()->json($res);
        }
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }
    
    public function getTalaoComposicao() {
        try {      
            $dto_22010 = new _22010($this->con());
            
            $request = $this->request();
            
            $res = $dto_22010->getTalaoComposicao($request);
            
            return response()->json($res);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getTalaoConsumoPecasDisponiveis() {
        try {      
            $dto_22010 = new _22010($this->con());
            
            $request = $this->request();
            
            $res = $dto_22010->getTalaoConsumoPecaDisponivel($request);
            
            return response()->json($res);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getDefeitos() {
        try {      
            $dto_22010 = new _22010($this->con());
            
            $request = $this->request();
            
            validator((array) $request, [
                'FAMILIA_ID' => ['Família de Produto','required'],
            ],true);            
            
            $res = $dto_22010->getDefeitos($request);
            
            return response()->json($res);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postDefeitos() {
        $this->Menu()->incluir('Registrando Defeitos');
        try {      
            $dto_22010 = new _22010($this->con());
            
            $request = $this->request();
            
            /**
             * Parametros com Requisitos mínimos
             */
            validator((array) $request, [
                'ESTABELECIMENTO_ID'        => ['Código do Estabelecimento'    ,'required'],
                'REMESSA_ID'                => ['Código da Remessa'            ,'required'],
                'REMESSA_TALAO_DETALHE_ID'  => ['Controle do Talão'            ,'required'],
                'PRODUTO_ID'                => ['Código do Produto'            ,'required'],
                'TAMANHO'                   => ['Código do Tamanho'            ,'required'],
                'QUANTIDADE'                => ['Quantidade de Defeitos'       ,'required'],
                'DEFEITO_ID'                => ['Código do Defeito'            ,'required'],
                'GP_ID'                     => ['Código do Grupo de Produção'  ,'required'],
                'OPERADOR_ID'               => ['Código do Operador'           ,'required'],
            ],true);            
            
            /**
             * Paremetros não obrigatórios
             */
            setDefValue($request->OBSERVACAO,'');
            
            $dto_22010->postDefeitos($request);
            
            $this->con()->commit();
                        
            return response()->json([
                'SUCCESS_MSG'   => 'Defeito gravado com sucesso!'
            ]);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function excludeDefeitos() {
        $this->Menu()->incluir('Excluindo Defeito');
        try {      
            $dto_22010 = new _22010($this->con());
            
            $request = $this->request();
            
            /**
             * Parametros com Requisitos mínimos
             */
            validator((array) $request, [
                'DEFEITO_TRANSACAO_ID' => ['Código da Transação do Defeito','required']
            ],true);            
                  
            $dto_22010->excludeDefeito($request);
            
            $this->con()->commit();
                        
            return response()->json([
                'SUCCESS_MSG'   => 'Defeito Excluído com sucesso!'
            ]);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postFicha() {
        $this->Menu()->incluir('Registrando Ficha de Produção');
        try {      
            $dto_22010 = new _22010($this->con());
            
            $request = $this->request();
            
            /**
             * Parametros com Requisitos mínimos
             */
            validator((array) $request, [
                'REMESSA_ID'        => ['Código da Remessa','required'],
                'REMESSA_TALAO_ID'  => ['Controle do Talão','required'],
                'MODELO_ID'         => ['Código do Modelo' ,'required'],
                'QUANTIDADE'        => ['Quantidade'       ,'required'],
            ],true);            
            
            /**
             * Paremetros não obrigatórios
             */
            setDefValue($request->OBSERVACAO,'');
            
            $dto_22010->postTalaoFicha($request);
            
            $ficha = $dto_22010->getTalaoFicha($request);
            
            $this->con()->commit();
                        
            return response()->json([
                'FICHA'         => $ficha,
//                'SUCCESS_MSG'   => 'Ficha registrada com sucesso!'
            ]);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    
    
    public function getJustificativa() {
        $this->Menu(false)->incluir();
        try {      
            $dto_22010 = new _22010($this->con());
                        
            $justificativa = $dto_22010->getJustificativa([]);
            
            $this->con()->commit();
                        
            return response()->json([
                'JUSTIFICATIVA'         => $justificativa,
//                'SUCCESS_MSG'   => 'Ficha registrada com sucesso!'
            ]);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    
    
    public function getTotalizadorDiario() {
        
        $request = $this->request(true);
        
		$param = (object)[];
		
		isset($request->estabelecimento_id) ? $param->ESTABELECIMENTO_ID	= $request->estabelecimento_id	: null;
		isset($request->gp_id)				? $param->GP_ID					= $request->gp_id				: null;
        isset($request->perfil_up_id)       ? $param->PERFIL_UP_ID          = $request->perfil_up_id        : null;
		isset($request->up_id)				? $param->UP_ID					= $request->up_id				: null;
		isset($request->up_todos)			? $param->UP_TODOS				= $request->up_todos			: null;
		isset($request->estacao)			? $param->ESTACAO				= $request->estacao				: null;
		isset($request->estacao_todos)		? $param->ESTACAO_TODOS			= $request->estacao_todos		: null;
		isset($request->data_ini)			? $param->DATA_INI				= $request->data_ini			: null;
		isset($request->data_fim)			? $param->DATA_FIM				= $request->data_fim			: null;
		isset($request->turno)				? $param->TURNO					= $request->turno				: null;
		isset($request->turno_hora_ini)		? $param->TURNO_HORA_INI		= $request->turno_hora_ini		: null;
		isset($request->turno_hora_fim)		? $param->TURNO_HORA_FIM		= $request->turno_hora_fim		: null;
		
        return response()->json(_22010::totalizadorDiario($param));
    }
    
    
    public function getTalaoVinculoModelos() {
        try {      
            $dto_22010 = new _22010($this->con());
            
            $request = $this->request();
            
            validator((array) $request, [
                'TALAO_ID' => ['Código do Talão','required'],
            ],true);            
            
            $res = $dto_22010->getTalaoVinculoModelos($request);
            
            return response()->json($res);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
}