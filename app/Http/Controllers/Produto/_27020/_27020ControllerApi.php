<?php

namespace app\Http\Controllers\Produto\_27020;

use App\Http\Controllers\Produto\_27020\_27020Controller as Ctrl;
use App\Models\DTO\Produto\_27020;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;



/**
 * Controller do objeto _27020 - Geracao de Remessas de Bojo
 */
class _27020ControllerApi extends Ctrl {
      
    
    public function getModelos() {
//        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_27020 = new _27020($this->con());
           
            $ret = $dto_27020->selectModelo($request);
            
                        
            if ( isset($request->GET_FILES) ) {
                
                $con = new _Conexao('FILES');
                
                $dto_file = new _27020($con);
            
                foreach ( $ret as $modelo ) {
                    $modelo->FILES = $dto_file->selectArquivo((object)['ID'=>$modelo->ID]);
                }
            }
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            
            if ( isset($con) ) {
                $con->rollback();
            }
            
            throw $e;
        }
    }
    
    public function getModeloTamanho() {
//        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_27020 = new _27020($this->con());

            $ret = $dto_27020->selectModeloTamanho($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    

    /**
     * Consultar arquivos das tarefas.
     */
    public function consultarArquivo($id) {
        

        $con = new _Conexao('FILES');

        try {

            $caminho = env('APP_TEMP', '').'modelo/';

            deleleFilesTree($caminho);
            
            $dto_27020 = new _27020($con);
            
            $arquivo = $dto_27020->selectArquivoConteudo((object)['ID'=>$id]);

            if ( count($arquivo) != 1 ) {
                log_info('Arquivo inválido');
            }
            
            $arquivo = $arquivo[0];

            $novoNome = \Auth::user()->CODIGO .'-'.$id.'-'. $arquivo->ARQUIVO;

            $arquivo->BINARIO = '/assets/temp/modelo/'.$novoNome;

            // Gravar no diretório temporário.
            $novoNome = $caminho.$novoNome;

            $novoArquivo = fopen($novoNome, "a+");
            fwrite($novoArquivo, $arquivo->CONTEUDO);
            fclose($novoArquivo);

            unset($arquivo->CONTEUDO);

            
            $con->commit();

            return $arquivo->BINARIO;

        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }    
}