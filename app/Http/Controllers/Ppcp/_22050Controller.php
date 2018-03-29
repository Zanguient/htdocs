<?php


namespace App\Http\Controllers\Ppcp;

use App\Http\Controllers\Controller;
use App\Models\DTO\Ppcp\_22050;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class _22050Controller extends Controller
{
    /**
     * Código do menu Gestão de Operadores de Processos
     * @var int 
     */
    private $menu = 'ppcp/_22050';
    
    public static function autenticacao(Request $request)
	{
        
        $param = obj_case($request->all());
        
        $dados = [
            'COD_BARRAS'  => $param->BARRAS,
            'OPERACAO_ID' => $param->OPERACAO_ID,
            'VALOR_EXT'   => $param->VALOR_EXT,
            'ABORT'       => $param->ABORT,
            'VERIFICAR_UP'=> $param->VERIFICAR_UP
        ];
        
        $res = _22050Controller::validarOperador($dados);
        
        if ( $request->ajax() )
        {
            $ret = Response::json($res);
        } else {
            $ret = $res;
        }    
        return $ret;
    }
    
	public static function validarOperador($dados = [])
	{
        $dados = obj_case($dados);
        
        $cod_barras   = $dados->COD_BARRAS  ;
        $operacao_id  = $dados->OPERACAO_ID ;
        $valor_ext    = $dados->VALOR_EXT   ;
        $abort        = (isset($dados->ABORT) && $dados->ABORT == true) ? true : false;
        $verificar_up = (isset($dados->VERIFICAR_UP) && $dados->VERIFICAR_UP > 0) ? $dados->VERIFICAR_UP : 'false';
        
        if (!$cod_barras) {
			log_erro('Código de barras inválido.');
		}
        
        if (!$operacao_id) {
			log_erro('Operação inválida.');
		}
        
        $param = (object)[
            'RETORNO'         => ['OPERADOR','OPERACAO'],
            'FIRST'           => '1',
            'STATUS'          => [1],
            'OPERADOR_BARRAS' => [$cod_barras],
            'OPERACAO_ID'     => $operacao_id,
			'VALOR_EXT'		  => $valor_ext
        ];
            
		if ( $verificar_up != 'false' ) {
			$param->UP_ID = $verificar_up;
		}
        
		$operacao = _22050::listar($param)->OPERACAO;
        
        if ( !isset($operacao[0]) ) {
			if ($abort) {
				log_erro('Operação não cadastrada.');
			}
        }
		
		$res = _22050::listar($param)->OPERADOR;
		
		if ( ($verificar_up != 'false') && !isset($res[0]) ) {

			if ($abort) {
				log_erro('Erro ao autenticar Operador.<br/>Verifique se:<br/> - Está cadastrado;<br/> - Possui permissão para a operação:<br/>'
                        . '"' . $operacao[0]->ID . ' - ' . $operacao[0]->PARAMETRO . '"'
                        . '<br/> - Está vinculado à UP selecionada.');
			}
		} else 
		if ( !isset($res[0]) ) {

			if ($abort) {
				log_erro('Erro ao autenticar Operador.<br/>Verifique se:<br/> - Está cadastrado;<br/> - Possui permissão para a operação:<br/>'
                       . '"' . $operacao[0]->ID . ' - ' . $operacao[0]->PARAMETRO . '"');
			}
		} else 
        if ( !isset($res[0]) ) {

			if ($abort) {
				log_erro('Operador não cadastrado.');
			}
		}	
        
        return $res;
	}    
}
