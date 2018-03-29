<?php

namespace app\Http\Controllers\Patrimonio\_16010;

use App\Http\Controllers\Patrimonio\_16010\_16010Controller as Ctrl;
use App\Models\DTO\Patrimonio\_16010;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Patrimonio\_22010;


/**
 * Controller do objeto _16010 - Geracao de Remessas de Bojo
 */
class _16010ControllerApi extends Ctrl {
      
    
    public function getImobilizados() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_16010 = new _16010($this->con());

            $ret = $dto_16010->selectImbolizado($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getImobilizado() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
           
            validator($request, [
                'ID' => ['Id do Imobilizado','required']
            ],true);  
            
            $dto_16010 = new _16010($this->con());

            $imobilizados = $dto_16010->selectImbolizado($request);
            
            if ( count($imobilizados) == 0 ) {
                log_erro('Imobilizado inválido.');
            }
            
            $imobilizado = $imobilizados[0];
            
            $ret = (object) [
                'IMOBILIZADO' => $imobilizado,
                'ITENS'       => $dto_16010->selectImbolizadoItem((object)['IMOBILIZADO_ID'=>$imobilizado->ID]),
                'FRETES'      => $dto_16010->selectImbolizadoFrete((object)['IMOBILIZADO_ID'=>$imobilizado->ID])
            ];
            
            $this->con()->commit();
                        
            return (array)$ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getImobilizadoItem() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_16010 = new _16010($this->con());

            $ret = $dto_16010->selectImbolizadoItem($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getImobilizadoParcelas() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'IMOBILIZADO_ID' => ['Id do Imobilizado','required']
            ],true);              
            
            $dto_16010 = new _16010($this->con());

            $ret = $dto_16010->selectImbolizadoParcela($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getImobilizadoItemParcelas() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'IMOBILIZADO_ITEM_ID' => ['Id do Item do Imobilizado','required']
            ],true);              
            
            $dto_16010 = new _16010($this->con());

            $ret = $dto_16010->selectImbolizadoItemParcela($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    

    public function postImobilizadoItemEncerrar() {
        $this->Menu()->alterar('Encerrando imobilizado');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'NFS_ID' => ['Documento Fiscal de Saída','required'],
                'TIPO'   => ['Tipo Encerramento','required']
            ],true);
                        
            $dto_16010 = new _16010($this->con());
            
            
            if ( $request->TIPO == '1' ) {
                if ( !isset($request->IMOBILIZADO_ID) || !($request->IMOBILIZADO_ID > 0) ) {
                    log_erro('Informe o id do imobilizado.');
                }                
                
                $dto_16010->spiImobilizadoEncerrar((object)[
                    'NFS_ID' => $request->NFS_ID,
                    'IMOBILIZADO_ID' => $request->IMOBILIZADO_ID
                ]);
                
                
            } else {
                if ( !isset($request->ITENS) || empty($request->ITENS) ) {
                    log_erro('Selecione um ou mais itens para encerrar');
                }
                
                foreach ( $request->ITENS as $item ) {

                    $dto_16010->spiImobilizadoEncerrar((object)[
                        'NFS_ID'                => $request->NFS_ID,
                        'IMOBILIZADO_ID'        => $request->IMOBILIZADO_ID,
                        'IMOBILIZADO_ITEM_ID'   => $item->ID,
                    ]);                    
                }
            }
            
            
            $ret = (object) [];
            
//            if ( isset($request->FILTRO) ) {
//                $ret->DATA_RETURN = $dto_16010->getTaloesComposicao($request->FILTRO);
//            }
            
            $ret->SUCCESS_MSG = 'Imobilizado encerrado com sucesso!';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }    
    
    public function getImobilizadoTipo() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_16010 = new _16010($this->con());

            $ret = $dto_16010->selectImobilizadoTipo($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getNfs() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'NFS' => ['Documento Fiscal de Saída','required|numeric|digits_between:1,50|min:1',]
            ],true);            
            
            $dto_16010 = new _16010($this->con());

            $ret = $dto_16010->selectNfs($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getNfItem() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'NFE' => ['Documento Fiscal','required|numeric|digits_between:1,50|min:1',]
            ],true);            
            
            $dto_16010 = new _16010($this->con());

            $ret = $dto_16010->selectNfItem($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getDemonstratitvoDepreciacao() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_16010 = new _16010($this->con());

            $ret = $dto_16010->selectDemonstratitvoDepreciacao($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

    public function postImobilizado() {
        $this->Menu()->alterar('Gravando imobilizado');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);
            
            $imobilizado = $request->DADOS;
            $replicar    = $imobilizado->REPLICAR;
            
            validator($imobilizado, [
                'DESCRICAO'      => ['Descrição'                ,'required'],
                'CCUSTO'         => ['Código do Centro de Custo','required'],
                'TIPO_ID'        => ['Tipo do Imobilizado'      ,'required'],
                'TIPO_TAXA'      => ['Taxa de Depreciação'      ,'required'],
                'TIPO_VIDA_UTIL' => ['Vida Útil'                ,'required'],
                'ITENS'          => ['Componentes'              ,'required'],
            ],true);

            $imobilizado_clone = clone $imobilizado;

            for ($i=1; $i <= $replicar; $i++) {

                $imobilizado = clone $imobilizado_clone;

                if ( !isset($imobilizado->ID) || $i > 1 ) {
                    $imobilizado->ID = $this->con()->gen_id('GTBIMOBILIZADO');
                }
                
                $dto_16010 = new _16010($this->con());
                
                $dto_16010->insertImobilizado($imobilizado);
                
                foreach ( $imobilizado->ITENS as $item ) {

                    if ( isset($item->EXCLUIDO) ) {
                        $dto_16010->deleteImobilizadoItem($item);
                    } else {
                    
                        validator($item, [
                            'PRODUTO_ID'     => ['Id od Produto'   ,'required'],
                            'VALOR_UNITARIO' => ['Valor do Produto','required']                
                        ],true);                

                        if ( !isset($item->ID) || $i > 1  ) {
                            $item->ID = $this->con()->gen_id('GTBIMOBILIZADO_ITEM');
                        }

                        $item->IMOBILIZADO_ID = $imobilizado->ID;


                        $dto_16010->insertImobilizadoItem($item);
                    }
                }
                
                $ret = (object) [];
                
                if($request->FLAG == 2){

                   $param = (object) ['IMOBILIZADO_ID' => $imobilizado->ID];
                   $dto_16010->spiImobilizadoDepreciar($param);
                   $ret->SUCCESS_MSG = 'Imobilizado gravado e concluído com sucesso.'; 

                }else{
                    $ret->SUCCESS_MSG = 'Imobilizado gravado com sucesso.';    
                }
            }
        
            $this->con()->commit();
            
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postImobilizadoDepreciar() {
        $this->Menu()->alterar('Depreciando imobilizado');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);
            
            $dados = $request->DADOS;
            
            $dto_16010 = new _16010($this->con());
            
            $dto_16010->spiImobilizadoDepreciar($dados);
            
            $ret = (object) [];
            
//            if ( isset($request->FILTRO) ) {
//                $ret->DATA_RETURN = $dto_16010->getTaloesComposicao($request->FILTRO);
//            }
            
            $ret->SUCCESS_MSG = 'Depreciação inicializada com sucesso.';
            
            //$this->con()->rollback();
            $this->con()->commit();
            
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function deleteImobilizado() {
        $this->Menu()->alterar('Gravando imobilizado');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);
            
            $imobilizado = $request->DADOS;
            
            validator($imobilizado, [
                'ID' => ['Id do Imobilizado','required'],
            ],true);
                   
            
            $dto_16010 = new _16010($this->con());
            
            $dto_16010->deleteImobilizado($imobilizado);
            
            $ret = (object) [];
            
//            if ( isset($request->FILTRO) ) {
//                $ret->DATA_RETURN = $dto_16010->getTaloesComposicao($request->FILTRO);
//            }
            
            $ret->SUCCESS_MSG = 'Imobilizado excluído com sucesso.';
            
            
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