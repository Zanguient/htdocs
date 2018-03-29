<?php

namespace App\Http\Controllers\Compras;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Compras\_13040;
use App\Models\DTO\Admin\_11010;
use Illuminate\Support\Facades\Response;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Models\DTO\Financeiro\_20010;
use App\Models\DTO\Financeiro\_20020;
use App\Models\DTO\Helper\Email;
use App\Models\Conexao\_Conexao;
use Exception;

/**
 * Controller
 * 13040 - Geração de Ordem de Compra
 */
class _13040Controller extends Controller
{
    /**
     * Código do menu
     * @var int 
     */
    private $menu = 'compras/_13040';
    
    /**
     * Geração de Ordem de Compra
     * @param Request $request
     * @return view _13040
     */
    public function create(Request $request)
    {        
    	_11010::permissaoMenu($this->menu,'INCLUIR');
    	
    	$ret = _13040::exibirProduto($request->licitacao_id);

    	// Formata números para o padrão brasileiro
    	foreach ($ret['orcamento'] as $o) {
    		$o->FRETE_VALOR	= number_format($o->FRETE_VALOR, 4, ',', '.');
    	}
    	foreach ($ret['orcamento_item'] as $o) {
    		$o->QUANTIDADE		= number_format($o->QUANTIDADE,		4, ',', '.');
    		$o->VALOR_UNITARIO	= number_format($o->VALOR_UNITARIO, 4, ',', '.');
    		$o->PERCENTUAL_IPI	= number_format($o->PERCENTUAL_IPI, 1, ',', '.');
    	}
    	foreach ($ret['orcamento_item_unico'] as $o) {
    		$o->QUANTIDADE	= number_format($o->QUANTIDADE,	4, ',', '.');
    	}
        
    	return view(
    		'compras._13040.create', [
    		'licitacao_id'				=> $request->licitacao_id,
    		'orcamentos' 	 			=> $ret['orcamento'], 
    		'orcamento_itens' 			=> $ret['orcamento_item'],
    		'orcamento_itens_unicos'	=> $ret['orcamento_item_unico'],
    		'arquivo_itens' 			=> $ret['arquivo_itens'],
            'pagamento_formas'          => _20010::listar(),
            'pagamento_condicoes'       => _20020::listar()
    	]);
    }
    
    /**
     * Grava Ordem de Compra
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {    	
    	_11010::permissaoMenu($this->menu,'INCLUIR','Fixando Dados');
    	
    	if ($request->ajax()) {
    		
			$obj = new _13040();
            $referencia = $request->referencia;
            $referencia_id = $request->id;
			
			//OC
			$i = -1;
			foreach ( $request->oc['empresa'] as $emp ) {
				$i++;
				
				$oc_id = _13040::gerarId();
				
				$obj->setOc($oc_id);
				$obj->setEstabelecimentoId($request->oc['estab'][$i]);
				$obj->setFornecedorId($request->oc['empresa'][$i]);
				$obj->setTransportadoraId($request->oc['transp'][$i]);
				$obj->setPagamentoForma($request->oc['forma'][$i]);
				$obj->setPagamentoCondicao($request->oc['cond'][$i]);
				$obj->setFrete( ($request->oc['frete'][$i] > 0) ? '1' : ($request->oc['frete'][$i] == 'CIF' ? '1' : '2') );
				$obj->setValorFrete( ($request->oc['frete'][$i] > 0) ? Helpers::formataNumPadrao($request->oc['frete'][$i]) : 0 );
				$obj->setUsuarioId(Auth::user()->CODIGO);
                $obj->setOcNivel($request->oc['nivel'][$i]);
                $obj->setReferencia($referencia);
                $obj->setReferenciaId($referencia_id);
				
				//Item de OC
				$x   = -1;
				$seq =  0;	
				foreach ( $request->item['valor'] as $valor ) {
					$x++;
					$seq++;

					if ( $request->oc['empresa'][$i] === $request->item['empresa'][$x] ) {
						//self::gravarOcItem($con, $obj, $oc_id, $seq, $x);
						
                        $obj->setItemFornecedorId($request->item['empresa'][$x]);
						$obj->setSequencia($seq);
						$obj->setProdutoCodigo($request->item['prod_id'][$x]);
                        $obj->setTamanho(floatval($request->item['tam'][$x]));
						$obj->setOrcamentoId($request->item['orcamento'][$x]);
						$obj->setQuantidade( Helpers::formataNumPadrao($request->item['qtd'][$x]) );
						$obj->setIpi( Helpers::formataNumPadrao($request->item['ipi'][$x]) );
						$obj->setValor( Helpers::formataNumPadrao($valor) );
						$obj->setDataEntrega($request->item['entrega'][$x]);
						$obj->setDataSaida($request->item['saida'][$x]);
						$obj->setControle( str_pad($request->item['estab'][$x],2,'0',STR_PAD_LEFT) . str_pad($oc_id,5,'0',STR_PAD_LEFT) . str_pad($seq,3,'0',STR_PAD_LEFT) );
						$obj->setDesconto( $request->item['desconto'][$x] > 0 ? Helpers::formataNumPadrao($request->item['desconto'][$x]): 0 );
						$obj->setCcusto($request->item['ccusto'][$x]);
						$obj->setOperacaoCodigo($request->item['operacao'][$x]);
						$obj->setContaContabil($request->item['ccontabil'][$x]);
					}
				}					
			}
			
			return Response::json( _13040::gravar($obj) );
    	}
    }
	
	public static function emailAutorizacao(_13040 $objeto, _Conexao $con) {
		
        /**
         * Bloco desativado por conta de muitos emails enviados para o Ricardo
         * Solicitado: Haroldo
         * Executado: Emerson
         */
        
//        
//        $obj = new Email();
//        $obj->setUsuarioId(Auth::user()->CODIGO);
//        $obj->setUrl(env('URL_PRINCIPAL'));
//        $obj->setStatus('1');
//        $obj->setDatahora(date('d.m.Y H:i:s'));
//        $obj->setCodigo(3); //template
//        
//        $i = -1;
//		foreach ($objeto->getOc() as $oc_id) {
//            $i++;
//			$oc_id      =  str_pad($oc_id, 5, 0, STR_PAD_LEFT);
//            $nivel_oc   = $objeto->getOcNivel()[$i];
//            $obj->setCorpo( env('URL_PRINCIPAL'). '/_13050/'. $oc_id );   
//            
//            if ( $nivel_oc == '3' ) {
//                
//                $user_nivel_3   = _13040::exibirNivelUsuario(3);
//
//                foreach ( $user_nivel_3 as $user ) {
//                    if ( empty($user->EMAIL) ) {
//                        log_erro('Usuário do nível de autorização 3 não estão com emails configurados.');
//                    }
//                    
//                    $obj->setEmail($user->EMAIL);
//                    $obj->setAssunto('Geração da Ordem de Compra '. $oc_id);
//                    $obj->setMensagem('Foi gerada a ordem de compra de n&uacute;mero ' . $oc_id . ' para seu setor.');
//
//                    Email::gravar($obj,$con);
//                }
//            }
//            
//            if ( $nivel_oc == '3' || $nivel_oc == '2' || $nivel_oc == '1' ) {
//                
//                $user_nivel_2   = _13040::exibirNivelUsuario(2);
//                
//                foreach ( $user_nivel_2 as $user ) {
//                    if ( empty($user->EMAIL) ) {
//                        log_erro('Usuário do nível de autorização 3 não estão com emails configurados.');
//                    }
//                    
//                    $obj->setEmail($user->EMAIL);
//                    $obj->setAssunto('Autorização da Ordem de Compra '. $oc_id);
//                    $obj->setMensagem('Foi gerada a ordem de compra de n&uacute;mero ' . $oc_id . ', e &eacute; necess&aacute;rio sua autoriza&ccedil;&atilde;o para ser dado prosseguimento no processo de compra.');       
//
//                    Email::gravar($obj,$con);  
//                }
//            }
//		}
	}
    
    public function ocDireta(Request $request) {
        
        _11010::permissaoMenu($this->menu,'INCLUIR');
        
        $id  = $request->requisicao_id;
        $ret = _13040::exibirRequisicao($id);
        
        return view(
    		'compras._13041.index', [
            'id'                => $id,
            'requisicao'        => $ret['requisicao'],   
            'requisicao_itens'  => $ret['requisicao_item'],   
            'pagamento_formas'          => _20010::listar(),
            'pagamento_condicoes'       => _20020::listar()            
        ]);
    }
}
