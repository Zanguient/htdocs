<?php

namespace App\Http\Controllers\Compras;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Compras\_13021;
use App\Models\DTO\Helper\Arquivo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Helpers\Helpers;
use App\Models\DTO\Helper\Email;

class _13021Controller extends Controller
{
    
    /**
     * Exibe os dados.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function ver(Request $request)
    {
		$request->orcamento_id = Helpers::decrypt($request->orcamento_id);

		Log::info('Exibindo orçamento (pedido de proposta) '.$request->orcamento_id.' do Fornecedor '.$request->fornecedor);
		
    	$val = _13021::verifValLicit($request->orcamento_id);

		//verifica se a data de validade já passou.
		if ($val['validade'][0]->DATA_VALIDADE < \date('Y-m-d')) {

			return view(
				'compras._13021.edit', [
				'expirado' => 'expirado',
				'validade' => $val['validade'][0]->DATA_VALIDADE
			]);
			
		} 
		else {
			
			$orc = _13021::exibirOrcamento($request->orcamento_id);
			
			//formatar campos numéricos
			$orc['orcamento'][0]->FRETE_VALOR = number_format($orc['orcamento'][0]->FRETE_VALOR, 4, ',', '.');

			foreach ($orc['orcamento_item'] as $o) {
				$o->QUANTIDADE		= number_format($o->QUANTIDADE, 4, ',', '.');
				$o->VALOR_UNITARIO	= number_format($o->VALOR_UNITARIO, 4, ',', '.');
				$o->PERCENTUAL_IPI	= number_format($o->PERCENTUAL_IPI, 4, ',', '.');
			}
			
			//verificar se o vínculo já existe
			if( empty($orc['orcamento'][0]->VINCULO_ID) ) {
				
				$vinc = Arquivo::gerarVinculo('ORCAMENTO');				
				$vinc = $vinc['vinculo'][0]->ID;
			}
			else {
				
				$vinc = $orc['orcamento'][0]->VINCULO_ID;
			}

			return view(
				'compras._13021.edit', [
                'id_hash'           => Helpers::encrypt($request->orcamento_id),
				'licitacao'			=> $orc['licitacao'][0],
				'orcamento'			=> $orc['orcamento'][0],
				'orcamento_item'	=> $orc['orcamento_item'],
				'arquivo'			=> $orc['arquivo'],
				'validade'			=> $val['validade'][0]->DATA_VALIDADE,
				'vinculo'			=> $vinc
			]);
		}

    }

    
    /**
     * Atualiza (grava) dados no banco de dados.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $orcamento_id
     * @return \Illuminate\Http\Response
     */
    public function gravar(Request $request, $orcamento_id)
    {
    	Log::info('Gravando proposta do fornecedor '.$request->fornecedor_id.' - '.$request->fornecedor);

    	$orcamento_id = Helpers::decrypt($orcamento_id);
        
    	$obj = new _13021();
    	
    	$obj->setOrcamentoId($orcamento_id);
    	$obj->setEmpresaContato($request->contato);
    	$obj->setValidadeProposta($request->validade);
    	$obj->setPrazoEntrega($request->prazo);
    	$obj->setFrete($request->frete);
		$obj->setFreteValor(Helpers::formataNumPadrao($request->frete_valor));
    	$obj->setPagForma($request->pag_forma);
    	$obj->setPagCondicao($request->pag_cond);
    	$obj->setObservacao($request->observacao);
		$obj->setVinculo($request->_vinculo_id);
		$obj->setTabela('ORCAMENTO');
		$obj->setStatusResposta('1');
    	
    	$i = 0;
    	foreach ($request->_produto_id as $prod_id) {

    		$obj->setProdutoId($prod_id);
    		$obj->setValorUnitario(Helpers::formataNumPadrao($request->valor_unitario[$i]));
    		$obj->setPercentualIpi(Helpers::formataNumPadrao($request->ipi[$i]));
			$obj->setObsProduto($request->obs_produto[$i]);
    		
    		$i++;
    	}
		
		//Excluir arquivos
		$i = 0;
        foreach ($request->_vinculo_arquivo_id as $vinc_id ) {

            if( $request->_req_arquivo_excluir[$i] === '1' ) {
                $obj->setArquivoExcluir($vinc_id);
            }

            $i++;
        }
		
		$ret = _13021::alterar($obj);
		
		$this->enviarEmailResp($request->_licitacao_id, $request->fornecedor, $request->_comprador_email);
		
		return Response::json( $ret );
		
	}
	
	/**
	 * Enviar e-mail com notificação que houve resposta do fornecedor.
	 * 
	 * @param int $licitacao_id
	 * @param string $fornecedor
	 * @param string $usuario_email
	 */
	public function enviarEmailResp($licitacao_id, $fornecedor, $usuario_email) {
		
		$obj = new Email();
		
		$obj->setId( Email::gerarId() );		
        $obj->setUsuarioId( \Auth::user()->CODIGO ? \Auth::user()->CODIGO : 999 );
        $obj->setUrl(env('URL_PRINCIPAL'));
        $obj->setStatus('1');
        $obj->setDatahora(date('d.m.Y H:i:s'));
        $obj->setCodigo(3); //template
		$obj->setCorpo( env('URL_PRINCIPAL').'/_13040/'.$licitacao_id );
		$obj->setEmail($usuario_email);
		$obj->setAssunto('Resposta para a Licitação '. $licitacao_id);
		$obj->setMensagem('Houve uma resposta para a Licita&ccedil;&atilde;o '. $licitacao_id .' do Fornecedor '.$fornecedor);

		Email::gravar($obj);
		
	}
    
}
