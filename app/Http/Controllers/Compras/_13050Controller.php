<?php

namespace App\Http\Controllers\Compras;

use PDF;
use Exception;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\DTO\Helper\Arquivo;
use App\Models\DTO\Compras\_13040;
use App\Models\DTO\Compras\_13050;
use App\Models\DTO\Helper\Email;
use App\Models\DTO\Admin\_11010;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Response;

class _13050Controller extends Controller
{
    /**
     * Código do menu Autorizações de Ordem de Compra
     * @var int 
     */
    private $menu = 'compras/_13050';
    
    public function index(Request $request)
    {        
        set_time_limit ( 0 );
        
		$permissaoMenu = _11010::permissaoMenu($this->menu);
        $usuario = _11010::listar();
        
    	$request['data_1']    = date('Y.m.01',strtotime("-1 month"));
    	$request['data_2']    = date('Y.m.t');
        
        if ( $usuario->NIVEL_OC > 0 ) {
            $request['pendencia'] = 'true';
        } else {
            $request['pendentes'] = 'true';
        }
        
        $itens = $this->createList($request);
        $meses = array(1 => ['01','Janeiro'],['02','Fevereiro'],['03','Março'],['04','Abril'],['05','Maio'],['06','Junho'],['07','Julho'],['08','Agosto'],['09','Setembro'],['10','Outubro'],['11','Novembro'],['12','Dezembro']);

		return view(
            'compras._13050.index', [
			'itens'			=> $itens,
			'permissaoMenu' => $permissaoMenu,
            'meses'         => $meses,
            'usuario'       => $usuario,
		]);
    }

    public function create()
    {
    	//
    }

    public function store(Request $request)
    {    	
        //
    }

    public function show(Request $request,$id)
    {
        set_time_limit ( 0 );
        
		$permissaoMenu  = _11010::permissaoMenu($this->menu);
        $controle       = _11010::controle(194); //[P194] - PERMITE ENVIAR OC AO FORNECEDOR
        
        $obj = new _13050;
        $obj->setFirst(1);
        $obj->setId($id);
        $obj->setItens(true);
        $obj->setHistorico(true);
        
        $dados  = _13050::listar($obj);
        
        $oc           = $dados['oc'][0]; 
        $item         = $dados['oc_item'];
        $pendencia    = $dados['oc_pendencia'];
        $historico    = $dados['oc_historico'];
        
        /**
         * Lista as informações do usuário conectado
         */
        $usuario = _11010::listar();
        
        /**
         * Lista todos os usuário com o nível menor ou igual ao nível da OC 
         */
        $usuarios = _11010::listar((object)[
            'NIVEL_OC' => $oc->NIVEL_OC,
            'ID'       => ''
        ]);
        
        /**
         * Verifica se o usuário tem permissão para autorizar Ordem de Compra
         */
        $autorizar_oc = false;
        foreach ( $usuarios as $user ) {
            if ( $user->ID == Auth::getUser()->CODIGO && $oc->AUTORIZACAO == 1 ) {
                $autorizar_oc = true;
                break;
            }
        }        

        /**
         * Verifica qual referencia da ordem de compra
         * L = Licatação
         * R = Requisição
         */
		switch (trim($oc->REFERENCIA)) {
            case 'L': $referencia = '_13040'; $ref_descricao = 'Visualizar Propostas';  break;
            case 'R': $referencia = '_13010'; $ref_descricao = 'Visualizar Requisição'; break;
            default : $referencia = '';       $ref_descricao = '';                      break;
		}     

        if ( strripos($request->url(), 'show') ) {       
            $view = 'compras._13050.show.body';
        } else {
            $view = 'compras._13050.show';
        }
        
		return view(
            $view, [
            'id'            => $id,
            'oc'            => $oc,
            'itens'         => $item,
            'pendencias'    => $pendencia,
            'historicos'    => $historico,
            'autorizar_oc'  => $autorizar_oc,
			'permissaoMenu' => $permissaoMenu,
            'controle'      => $controle,
            'referencia'    => $referencia,
            'ref_descricao' => $ref_descricao,
            'usuario'       => $usuario
		]);
    }
    
    public function edit($id)
    {
    	//
    }
    
    public function update(Request $request)
    {
    	//
    }
    
    public function destroy($id)
    {
    	//
    }
    
    public function search(Request $request)
    {
        //
    }
    
    public function createList(Request $request)
    {
        
        $filtro      = $request->get('filtro') ? '%' . $request->get('filtro') . '%' : null;
        $first       = $request->get('qtd_por_pagina');
        $skip        = $request->get('pagina');
        $data_1      = $request->get('data_1');
        $data_2      = $request->get('data_2');
        $pendencia   = $request->get('pendencia')   == 'true' ? '1' : null;
        $pendentes   = $pendencia ? '1' : ($request->get('pendentes')   == 'true' ? '1' : null);
        $autorizadas = $request->get('autorizadas') == 'true' ? '2' : null;
        $reprovadas  = $request->get('reprovadas')  == 'true' ? '3' : null;
        
        if ( strtotime(str_replace('.', '-', $data_1)) > strtotime(str_replace('.', '-', $data_2)) ) {
            log_erro('Período inválido! Data inicial maior que a final');
        }
        
        $array_status = array();
        array_push($array_status, $pendentes, $autorizadas, $reprovadas);
        $status = Helpers::arrayToList($array_status,'9');
        
        $obj = new _13050;
        $obj->setFiltro($filtro);
        $obj->setFirst($first);
        $obj->setSkip($skip);
        $obj->setStatus('1');
        $obj->setDataInicial($data_1);
        $obj->setDataFinal($data_2);
        $obj->setAutorizacao($status);
//        $obj->setEnviada('0');
        $obj->setPendencia($pendencia);
        $obj->setItemPedente('1');

        $dados  = _13050::listar($obj);
        
        $ocs    = $dados['oc'];
        $res    = '';

        /**
         * Lista as informações do usuário conectado
         */
        $usuario = _11010::listar();
        
        
        //$con = new _Conexao();
        
        
        if ( !empty($ocs) || $skip ) {
            
            $i = 0;
            foreach ($ocs as $oc) {

                /**
                 * Verifica se o usuário tem permissão para autorizar Ordem de Compra
                 */

                /**
                 * Lista todos os usuário com o nível menor ou igual ao nível da OC 
                 */
                $usuarios = _11010::listar((object)[
                    'NIVEL_OC' => $oc->NIVEL_OC,
                    'ID'       => ''
                ]);

                $autorizar_oc = false;
                foreach ( $usuarios as $user ) {
                    if ( $user->ID == Auth::getUser()->CODIGO && $oc->AUTORIZACAO == 1 ) {
                        $autorizar_oc = true;
                        break;
                    }
                }

                $i++;
                $link  = url('_13050', $oc->ID);
                $id    = $oc->ID;
                $data  = date_format(date_create($oc->DATA), 'd/m/Y');
                $forn  = $oc->FORNECEDOR_ID . ' - ' . $oc->FORNECEDOR_DESCRICAO;
                $fam   = $oc->FAMILIAS;
                $qtd   = $oc->QTD_ITENS;
                $valor = 'R$ ' . $oc->VALOR_TOTAL_GERAL;
                $obs   = $oc->OBSERVACAO_INTERNA;

                $user = '';

                if ( $oc->OC_ENVIADA == 1 && $oc->AUTORIZACAO == 2 ) {
                    $user = 'Aprovada / enviada ao fornecedor';
                } elseif ( $oc->OC_ENVIADA == 0 && $oc->AUTORIZACAO == 2 ) {
                    $user = 'Aprovada / Aguardando envio ao fornecedor';
                } elseif ( $oc->AUTORIZACAO == 2 ) {
                    $user = 'Aprovada';
                } elseif ( $oc->AUTORIZACAO == 3 ) {
                    $user = 'Reprovada';
                }
                
                if ($user == '') {
                    foreach ($dados['nivel'] as $nivel) {
                        if ( trim($oc->NIVEL_OC) ==  trim($nivel->ID) ) {
                            $user = 'Aguardando autorização da ' . $nivel->DESCRICAO;
                        }
                    }
                }

                $pend = [];
                //$pend = _13050::ocPendencia2($con, $oc->ID);
                $pend = 'btn-success'; 

                if(count($pend) > 0){
                //    $pend = 'btn-danger';
                }

                $res .= '<tr tabindex="' . $i . '" id="' . $oc->ID . '">';
                $res .= '<td><button';
                $res .= '   type="button"';
                $res .= '   class="btn '.$pend.' autorizar-oc2" data-oc="'.$id.'" '.($autorizar_oc ? '' : 'disabled').' data-index="'.$i.'"';
                $res .= '   data-loading-text="gravando..."';
                $res .= '>';
                $res .= '   <span class="glyphicon glyphicon-ok"></span> Autorizar OC';
                $res .= '</button></td>';
                $res .= '    <td>' . $id   . '</td>';
                $res .= '    <td>' . $data . '</td>';
                $res .= '    <td>' . $forn . '</td>';
                $res .= '    <td>' . $fam  . '</td>';
                $res .= '    <td class="text-right">' . $qtd   . '</td>';
                $res .= '    <td class="text-right">' . $valor . '</td>';
                $res .= '    <td>' . $user . '</td>';
                $res .= '    <td>' . $obs  . '</td>';
                $res .= '</tr>';
            }
        }

       // $con->commit();
        
        return $res;
    }
	
	/**
	 * Enviar PDF de OC.
	 * Função chamada via Ajax.
	 * 
	 * @param Request $request
	 */
	public function enviarPdfOc(Request $request) {
		$this->pdfOc($request, 1);
	}
	
	/**
	 * Imprimir PDF de OC.
	 * Função chamada via Ajax.
	 * 
	 * @param Request $request
	 * @return string URL do pdf gerado
	 */
	public function imprimirPdfOc(Request $request) {
		return $this->pdfOc($request, 2);
	}
	
	/**
	 * Gerar PDF com a OC autorizada.
	 * Função auxiliar para 'enviarPdfOc' e 'imprimirPdfOc'.
	 * 
	 * @param Request $request
	 * @param int $opcao 1 - Enviar | 2 - Imprimir
	 * @return string URL do pdf gerado
	 */
	public function pdfOc(Request $request, $opcao) {
		
		if ($opcao === 1) {
			_11010::permissaoMenu($this->menu,'ALTERAR','Enviando Ordem de Compra - '.$request->oc);
		}
		else {
			_11010::permissaoMenu($this->menu,'ALTERAR','Imprimindo Ordem de Compra - '.$request->oc);
		}
		
		_11010::controle(194,true); //[P194] - PERMITE ENVIAR OC AO FORNECEDOR
		
        if( $request->ajax() ) {

            $obj = new _13050();

            $obj->setId($request->oc);
            $obj->setFornecedorId($request->_fornecedor_id);

            $dados = _13050::infoPdfOc($obj);

            //máscaras
            $dados['estab'][0]->CEP   = Helpers::maskCep($dados['estab'][0]->CEP);
            $dados['estab'][0]->CNPJ  = Helpers::maskCnpj($dados['estab'][0]->CNPJ);
            $dados['estab'][0]->FONE  = Helpers::maskFone($dados['estab'][0]->FONE);
            $dados['estab'][0]->FAX   = Helpers::maskFone($dados['estab'][0]->FAX);
            $dados['fornec'][0]->CEP  = Helpers::maskCep($dados['fornec'][0]->CEP);
            $dados['fornec'][0]->CNPJ = Helpers::maskCnpj($dados['fornec'][0]->CNPJ);
            $dados['fornec'][0]->FONE = Helpers::maskFone($dados['fornec'][0]->FONE);
            $dados['fornec'][0]->FAX  = Helpers::maskFone($dados['fornec'][0]->FAX);

            //gerar e salvar pdf
            $pdf_load = PDF::loadView(
                'compras._13050.pdf_oc', [
                    'estab'				=> $dados['estab'][0],
                    'oc'				=> $request->oc,
                    'data'				=> date('d/m/Y'),
                    'datahora'			=> date('d/m/Y H:i'),
                    'fornec'			=> $dados['fornec'][0],
                    'transp'			=> $request->_transp_desc,
                    'pag_forma'			=> $request->_pag_forma_desc,
                    'pag_cond'			=> $request->_pag_cond_desc,
                    'frete'				=> $request->_frete,
                    'comprador'			=> $request->_comprador_desc,
                    'tab_prod_id'		=> $request->_tab_prod_id,
                    'tab_prod_desc'		=> $request->_tab_prod_desc,
					'tab_prod_info'		=> $request->_tab_prod_info,
                    'tab_qtd'			=> $request->_tab_qtd,
                    'tab_valor'			=> $request->_tab_valor,
                    'tab_ipi'			=> $request->_tab_ipi,
                    'tab_acresc'		=> $request->_tab_acresc,
                    'tab_desconto'		=> $request->_tab_desconto,
                    'tab_total'			=> $request->_tab_total,
                    'tab_data_saida'	=> $request->_tab_data_saida,
                    'tab_data_entrega'	=> $request->_tab_data_entrega,
                    'qtd_item'			=> $request->qtd_item,
                    'obs'				=> $request->obs,
                    'subtotal'			=> $request->subtotal,
                    'ipi_total'			=> $request->ipi_total,
                    'acresc_total'		=> $request->acresc_total,
                    'desconto_total'	=> $request->desconto_total,
                    'valor_frete'		=> $request->valor_frete,
                    'total_geral'		=> $request->total_geral,
                    'usuario'			=> Auth::getUser()->USUARIO,
                    'autorizacao'		=> 2
                ]
			);
            //)->inline();
            //)->save($arq_temp);
			
			//imprimir
			if($opcao == 2) {
				
				//caminho e nome do arquivo
				$arq_temp = public_path().'\assets\temp\oc'.$request->oc.'.pdf';

				//apagar arquivo, caso já exista
				if(file_exists($arq_temp) ) {
					unlink($arq_temp);
				}
				
				$pdf_load->save($arq_temp);

				return '/assets/temp/oc'.$request->oc.'.pdf';
				
			}
			//enviar
			else {
				
				$pdf = $pdf_load->inline();
				$this->gravarPdfOc($pdf->original, $request->oc, $dados['fornec'][0]->EMAIL);
				
			}
        }
	}
	
	/**
	 * Excluir PDF de OC.
	 * Função chamada via Ajax.
	 * 
	 * @param Request $request
	 */
	public function excluirPdfOc(Request $request) {
		
		//caminho e nome do arquivo
		$arq_temp = public_path() . str_replace('/', '\\', $request->url_temp);
		
		//apagar arquivo, caso já exista
		if(file_exists($arq_temp) ) {
			unlink($arq_temp);
		}
		
	}
	
	/**
	 * Gravar PDF da OC no banco para ser enviada por e-mail.
	 * 
	 * @param binary $pdf
	 * @param int $oc
	 * @param string $fornec_email
	 */
	public function gravarPdfOc($pdf, $oc, $fornec_email) {
		
		$arq_id = Arquivo::gerarIdArquivo();
		$vinc	= Arquivo::gerarVinculo('OC');
		
		$obj = new Arquivo();
		
		$obj->setId($arq_id['id'][0]->ID);
		$obj->setVinculo($vinc['vinculo'][0]->ID);
		$obj->setSequencia(1);
		$obj->setTabela('OC');
		$obj->setTipo('pdf');
		$obj->setTamanho(strlen($pdf));
		//$obj->setTmpName($arq_temp);
		$obj->setNome('oc'.$oc.'.pdf');
		$obj->setConteudo($pdf);
		$obj->setUsuarioId(!empty(\Auth::user()->CODIGO) ? \Auth::user()->CODIGO : 999);
		$obj->setData('now');

		Arquivo::gravarArquivo($obj);
		
		$this->enviarPdfOcEmail($oc, $fornec_email, $arq_id['id'][0]->ID);
	}
	
	/**
	 * Enviar e-mail para o fornecedor com a OC.
	 * 
	 * @param int $oc
	 * @param string $fornec_email
	 */
	public function enviarPdfOcEmail($oc, $fornec_email, $arq_id) {
		
		$obj = new Email();
		
		$obj->setId( Email::gerarId() );		
        $obj->setUsuarioId(Auth::user()->CODIGO);
        $obj->setUrl(env('URL_PRINCIPAL'));
        $obj->setStatus('1');
        $obj->setDatahora(date('d.m.Y H:i:s'));
        $obj->setCodigo(4); //template

		$obj->setCorpo( env('URL_PRINCIPAL') );
		
		$obj->setEmail($fornec_email);
		
		$assunto = 'Delfa - Ordem de Compra '. $oc;
		$obj->setAssunto($assunto);
		
		$obj->setMensagem('Segue em anexo a Ordem de Compra de n&uacute;mero '. $oc .'.');

		Email::gravar($obj, null, $arq_id, $assunto);
		
		_13050::enviarOc($oc);
	}
	
	public function autorizacao(Request $request)
    {
        set_time_limit ( 0 );
        _11010::permissaoMenu($this->menu,'ALTERAR','Autorizar OC');
        
        $id    = $request->id;    
        $obs   = $request->obs;    
        /**
         * $itens = Itens que não serão referência de menor preço para as proximas compras do mesmo produto do item
         */
        $itens = [];

        if ( isset($request->itens) ) {
            foreach( $request->itens as $item ) {
                $itens[] = (int)$item['value'];
            }
        }
        
        switch (trim($request->tipo)) {
            case '1': $tipo =  2; break;
            case '2': $tipo =  3; break;
            default : $tipo = ''; break;
        }        
        
        $obj = new _13050;
        $obj->setFirst(1);
        $obj->setId($id);
        
        $oc  = _13050::listar($obj)['oc'][0];
        $nivel_oc = $oc->NIVEL_OC; 

        /**
         * Lista todos os usuário com o nível menor ou igual ao nível da OC 
         */
        $usuarios = _11010::listar((object)[
            'NIVEL_OC' => $nivel_oc,
            'ID'       => ''
        ]);
        
        /**
         * Verifica se o usuário tem permissão para autorizar esta ordem de compra ($id)
         */
        $permissao = false;
        foreach ( $usuarios as $usuario ) {
            if ( $usuario->ID == Auth::getUser()->CODIGO && $oc->AUTORIZACAO == 1 ) {
                $permissao = true;
                break;
            }
        }
        
        /**
         * Se permissão retornar verdadeiro, o usuário poderá autorizar a ordem de compra
         */
        if ( $permissao ) {
            /**
             * Proximo nível de autorização
             */
            $proximo_nivel = $usuario->NIVEL_OC-1;
            
            /**
             * Verifica se o proximo nível é igual a zero para mudar o status da oc para autorizada
             */
            $autorizado = ( $proximo_nivel == 0 || $tipo == 3 ) ? $tipo : null;
            
            /**
             * Realiza o processo de autorização
             */
            _13050::autorizar((object)[
                'ID'                 => $id,
                'ESTABELECIMENTO_ID' => $oc->ESTABELECIMENTO_ID,
                'PROXIMO_NIVEL'      => $proximo_nivel,
                'USUARIO_NIVEL'      => $usuario->NIVEL_OC,
                'AUTORIZACAO'        => $autorizado,
                'TIPO'               => $tipo,
                'OBS'                => $obs,
                'ITENS'              => $itens,
            ]);
            
        } else {
            log_erro('Você não tem permissão para realizar esta operação');
        }
    }

    public static function pendencias(Request $request){
        $con  = new _Conexao();
        $pend = _13050::ocPendencia2($con, $request->ID);
        $con->commit();

        $pend =  count($pend); 

        return Response::json( ['Q' => $pend ]);
    }
    
	public static function autorizacaoEmail($param = []) {
		
        $con       = $param->CON;
        $id        = $param->ID;
        $tipo      = $param->TIPO;
        $old_nivel = $param->NIVEL;
        $new_nivel = ($old_nivel > 1) ? ($old_nivel - 1) : 1;
        
        $obj = new Email();
        $obj->setUsuarioId(Auth::user()->CODIGO);
        $obj->setUrl(env('URL_PRINCIPAL'));
        $obj->setStatus('1');
        $obj->setDatahora(date('d.m.Y H:i:s'));
        $obj->setCodigo(3); //template
        $obj->setCorpo( env('URL_PRINCIPAL'). '/_13050/'. $id );   
        
        if ( $tipo == 1 && $new_nivel > 1 ) { //AUTORIZADO - NIVEIS MAIORES PARA OS MENORES. EX: RICARDO -> MANOEL
            
            $user_nivel = _13040::exibirNivelUsuario($new_nivel);
            $obj->setEmail($user_nivel->EMAIL);
            $obj->setAssunto('Autorização da Ordem de Compra '. $id);
            $obj->setMensagem('A sua autoriza&ccedil;&atilde;o na Ordem de Compra ' . $id . ', &eacute; necess&aacute;ria para que seja dado prosseguimento no processo de compra.');       

            Email::gravar($obj,$con);
        }
        elseif ( $tipo == 1 && $new_nivel == 1 ) { //AUTORIZADO - MANOEL -> SIRLANDIA
            
            $user_nivel = _13040::exibirNivelUsuario($new_nivel);
            $obj->setEmail($user_nivel->EMAIL);
            $obj->setAssunto('Autorização da Ordem de Compra '. $id);
            $obj->setMensagem('A Ordem de Compra ' . $id . ', est&aacute; aguardando o envio do Or&ccedil;amento para o fornecedor.');       

            Email::gravar($obj,$con);
        }
	}    
	
	/**
	 * Enviar e-mail para o comprador (cargo: suprimentos) notificando que a OC foi autorizada.
	 * 
	 * @param int $oc
	 * @param int $tipo
	 */
	public function notificarComprador($oc, $tipo) {
		
		$obj = new Email();
		
		$obj->setId( Email::gerarId() );		
        $obj->setUsuarioId( Auth::user()->CODIGO );
        $obj->setUrl( env('URL_PRINCIPAL') );
        $obj->setStatus('1');
        $obj->setDatahora(date('d.m.Y H:i:s'));
        $obj->setCodigo(3); //template

		$obj->setCorpo( env('URL_PRINCIPAL') );
		
		$obj->setEmail('compras@delfa.com.br');
		
		$assunto = 'Delfa - Ordem de Compra '. $oc;
		$obj->setAssunto($assunto);
		
		$obj->setMensagem('Segue em anexo a Ordem de Compra de n&uacute;mero '. $oc .'.');

		Email::gravar($obj, null);
		
		_13050::enviarOc($oc);
		
	}
}
