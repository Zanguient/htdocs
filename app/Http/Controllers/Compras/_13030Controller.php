<?php

namespace App\Http\Controllers\Compras;

use PDF;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Compras\_13030;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Admin\_11010;
use Exception;
use Illuminate\Support\Facades\View;
use App\Models\Conexao\_Conexao;

class _13030Controller extends Controller
{
    /**
     * Código do menu
     * @var int 
     */
    private $menu = 'compras/_13030';
    private $con;
    
    /**
     * Lista todos os dados.
     *
     * @return \Illuminate\Http\Response
     */	
    public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        $ctrl_198 = _11010::controle(198); // 198 - PERMITE GERENCIAR FATURAM. NAS COTAS ORÇAMENTÁRIAS
    
		$cota_zerada = true;
		$cota_valida = true;
		$totaliza    = false;
		$ggf         = false;
		$faturamento = false;
        
        $meses = array(1 => ['01','Janeiro'],['02','Fevereiro'],['03','Março'],['04','Abril'],['05','Maio'],['06','Junho'],['07','Julho'],['08','Agosto'],['09','Setembro'],['10','Outubro'],['11','Novembro'],['12','Dezembro']);

        return view('compras._13030.index', [
        	'permissaoMenu' => $permissaoMenu,
        	'cota_zerada'	=> $cota_zerada,
        	'cota_valida'	=> $cota_valida,
            'totaliza'      => $totaliza,
        	'ggf'           => $ggf,
            'faturamento'   => $faturamento,
        	'meses'			=> $meses,
            'ctrl_198'      => $ctrl_198
        ]);
    }
    /**
     * Exibe o formulário de criação.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	_11010::permissaoMenu($this->menu,'INCLUIR');
    	
    	$meses	= array(1 => ['01','Janeiro'],['02','Fevereiro'],['03','Março'],['04','Abril'],['05','Maio'],['06','Junho'],['07','Julho'],['08','Agosto'],['09','Setembro'],['10','Outubro'],['11','Novembro'],['12','Dezembro']);
    	 
    	return view(
    		'compras._13030.create', [
    		'meses'		=> $meses,
    	]);
    }

    /**
     * Grava os dados no banco de dados.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
	{
		_11010::permissaoMenu($this->menu,'INCLUIR','Fixando Dados');
        
		if ($request->ajax()) {

			$obj = new _13030();
			$ccusto			=	$request->_ccusto;
			$ccontabil		=	$request->_ccontabil;
			$mes_inicial 	=	$request->mes_inicial;
			$ano_inicial 	=	$request->ano_inicial;
			$ano_final 		=	$request->ano_final;
			$mes_final 		= 	$request->mes_final;
			$bloqueio	 	=	$request->bloqueio 	  ? '1' : '0';
			$notificacao 	=	$request->notificacao ? '1' : '0';
			$destaque	 	= 	$request->destaque	  ? '1' : '0';
			$totaliza	 	= 	$request->totaliza	  ? '1' : '0';
			$time_inicial	=	strtotime($ano_inicial.'-' . $mes_inicial. '-01');
			$time_final		=	strtotime($ano_final  .'-' . $mes_final  . '-01');
            
			while ($time_inicial <= $time_final) {
				$mes		=	date('m', $time_inicial);
				$ano		=	date('Y', $time_inicial);
				$periodo	=	date('Y.m.d', $time_inicial);
				$valor		=	Helpers::formataNumPadrao($request->valor);

				$res = _13030::consultaCota(null,$ccusto,$ccontabil,$periodo);

				if (!$res) {

					$id = _13030::gerarId();

					$obj->setId($id);
					$obj->setCcusto($ccusto);
					$obj->setConta($ccontabil);
					$obj->setMes($mes);
					$obj->setAno($ano);
					$obj->setBloqueio($bloqueio);
					$obj->setNotificacao($notificacao);
					$obj->setDestaque($destaque);
					$obj->setTotaliza($totaliza);
					$obj->setValor($valor);
				}
				$time_inicial = strtotime("+1 month", $time_inicial);
			} 
			
			return Response::json( _13030::gravar($obj) );
		}

	}

    /**
     * Exibe os dados.
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $id = $request->id;
        
    	$res = _13030::exibir($id);

        if ( strripos($request->url(), 'show') ) {
            $view = 'compras._13030.show.body';
        } else {
            $view = 'compras._13030.show';
        }
        
    	return view(
    		$view, [
        	'permissaoMenu' => $permissaoMenu,
    		'id' 		    => $id, 
    		'cota' 		    => $res['cota'], 
    		'itens'		    => $res['cota_itens'], 
    		'extras' 	    => $res['cota_extra'], 
    		'outros' 	    => $res['cota_outro'],
            'ref'           => $request->ref
    	]);
    }

    /**
     * Exibe o formulário para edição de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {	
    	_11010::permissaoMenu($this->menu,'ALTERAR');
        
    	$res 	= _13030::exibir($id);

    	return view(
    		'compras._13030.edit', [
    		'id' 		=> $id, 
    		'cota' 		=> $res['cota'], 
    		'itens'		=> $res['cota_itens'], 
    		'extras' 	=> $res['cota_extra'], 
    		'outros' 	=> $res['cota_outro']
    	]);
    }

    /**
     * Atualiza dados no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    	_11010::permissaoMenu($this->menu,'ALTERAR','Fixando Dados');
        
        if ( $request->ajax() ) {
            
            $obj              = new _13030();
            $id               = $request->id;
            $ccusto           = $request->_ccusto;
            $ccontabil        = $request->_ccontabil;
            $mes              = $request->_mes;
            $ano              = $request->_ano;
            $bloqueio	      = $request->bloqueio 	 ? '1' : '0';
            $notificacao      = $request->notificacao ? '1' : '0';
            $destaque	      = $request->destaque 	 ? '1' : '0';   
			$totaliza	      = $request->totaliza	 ? '1' : '0';         
            $valor            = Helpers::formataNumPadrao($request->cota);
            $extra_add        = $request->cota_extra_add;
            $extra_del        = $request->cota_extra_del;
            $outro_add        = $request->cota_outros_add;
            $outro_del        = $request->cota_outros_del;            
            $observacao_geral = $request->cota_observacao;
            
            /** Seta os valores para serem alterados */
            $obj->setId($id);
            $obj->setCcusto($ccusto);
            $obj->setConta($ccontabil);
            $obj->setMes($mes);
            $obj->setAno($ano);
            $obj->setBloqueio($bloqueio);
            $obj->setNotificacao($notificacao);
            $obj->setDestaque($destaque);
			$obj->setTotaliza($totaliza);
            $obj->setValor($valor);
            $obj->setObservacaoGeral($observacao_geral);
            
            /** Seta o array de cotas extras para adicionar */
            isset($extra_add) ? $obj->setCotaExtraAdd($extra_add) : null;
            
            /** Seta o array de cotas extras para deletar */
            isset($extra_del) ? $obj->setCotaExtraDel($extra_del) : null;
            
            /** Seta o array de cotas extras para adicionar */
            isset($outro_add) ? $obj->setCotaOutroAdd($outro_add) : null;
            
            /** Seta o array de cotas extras para deletar */
            isset($outro_del) ? $obj->setCotaOutroDel($outro_del) : null;            
    
            /** Chama a gravação no banco */
            return Response::json( _13030::alterar($obj) );
        }
    }

    /**
     * Paginação com scroll.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function DeletaItemAccordion(Request $request)
    {
    	_11010::permissaoMenu($this->menu,'EXCLUIR','Excluindo Cota');
        
        $id	 = $request->get('ID');
    	_13030::excluir($id);
    }
    
    /**
     * Excluir dados do banco de dados.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	_11010::permissaoMenu($this->menu,'EXCLUIR','Excluindo Cota');
        
    	_13030::excluir($id);
    }

    /**
     * @param Request $request
     */

    public function consultaCota(Request $request)
    {

    	if( $request -> ajax() ){

			$ccusto			=	$request->get('ccusto');
			$ccontabil		=	$request->get('ccontabil');
    		$mesIncial		=	$request->get('mesInicial');
    		$anoInicial		=	$request->get('anoInicial');
    		$mesFinal		=	$request->get('mesFinal');
    		$anoFinal		=	$request->get('anoFinal');
			$periodoInicial	=	date('Y.m.t', strtotime($anoInicial . '-' . $mesIncial . '-01'));
			$periodoFinal	=	date('Y.m.t', strtotime($anoFinal   . '-' . $mesFinal  . '-01'));
			$ret 			=	_13030::consultaCota(null,$ccusto,$ccontabil,$periodoInicial,$periodoFinal);
			$res			=   '';
			
			if ( $ret ) {
				$res  = '<table class="table table-striped table-hover table-condensed sucesso">';
				$res .= '  <thead>';
				$res .= '	<tr>';
				$res .= '	  <th>Período</th>';
				$res .= '	  <th class="text-right">Cota</th>';
				$res .= '	  <th class="text-right">Saldo</th>';
				$res .= '	</tr>';
				$res .= '  </thead>';
				$res .= '  <tbody>';
				foreach ( $ret as $cota ) {
					$res .= '	<tr>';
					$res .= '	  <td>' . str_pad($cota->MES,2,'0',STR_PAD_LEFT) . '/' . $cota->ANO . '</td>';
					$res .= '	  <td class="text-right">R$ ' . number_format($cota->VALOR, 2, ',', '.') . '</td>';
					$res .= '	  <td class="text-right">R$ ' . number_format($cota->SALDO, 2, ',', '.') . '</td>';
					$res .= '	</tr>';
				}
				$res .= '  </tbody>';
				$res .= '</table>';				
			}
    	}
    	echo $res;
    }

    /**
     * Paginação com scroll.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
    public function listar(Request $request)
    {	
        set_time_limit ( 0 );
    	if ( $request->ajax() ) {            
            
    		$filtro			  = $request->get('filtro') == null ? null : ($request->get('filtro') ? '%' . Helpers::removeAcento($request->get('filtro'), '%', 'upper',true) . '%' : '');
    		$mes_inicial	  = $request->get('mes_inicial');
    		$ano_inicial	  = $request->get('ano_inicial');
    		$mes_final		  = $request->get('mes_final');
    		$ano_final		  = $request->get('ano_final');  		
    		$cota_zerada	  = $request->get('cota_zerada')      === 'true' ? true : null;
    		$cota_valida	  = $request->get('cota_valida')      === 'true' ? true : null;
            $cota_totaliza    = $request->get('cota_totaliza')    === 'true' ? true : null;
            $cota_ggf         = $request->get('cota_ggf')         === 'true' ? true : null;
            $cota_faturamento = $request->get('cota_faturamento') === 'true' ? true : null;
    		$time_inicial	  = strtotime($ano_inicial.'-' . $mes_inicial. '-01');
    		$time_final		  = strtotime($ano_final  .'-' . $mes_final  . '-01');    		
    		$data_inicial	  = date('Y.m.01',$time_inicial);
    		$data_final		  = date('Y.m.t',$time_final);  

            $param = (object) array (
                'DATA_1'           => $data_inicial,
                'DATA_2'           => $data_final,
                'COTA_ZERADA'      => $cota_zerada,
                'COTA_VALIDA'      => $cota_valida,
                'FILTRO'           => $filtro,
                'COTA_FATURAMENTO' => $cota_faturamento,
                'COTA_GGF'         => $cota_ggf,
            );      
            
            log_info('consulta cotas - inicio');
            $res = _13030::listar($param);
            log_info('consulta cotas - fim');
                        
            $cotas = $res['cota'];
            $itens = $res['item'];
            $fats  = $res['fat'];
            
            $ccustos  = array();
            $periodos = array();
            $contas   = array();

            //Variáveis array de Cálculo
            $a_valor   = 0;
            $a_extra   = 0;
            $a_total   = 0;
            $a_outros  = 0;
            $a_util    = 0;
            $a_saldo   = 0;  
            $a         = -1;  
            $ccusto    = -1;
            $a_custo   = 0;

            $b_valor   = 0;
            $b_extra   = 0;
            $b_total   = 0;
            $b_outros  = 0;
            $b_util    = 0;
            $b_saldo   = 0;  
            $b         = -1;  
            $periodo   = -1;
            $b_custo   = 0;

            $c_valor   = 0;
            $c_extra   = 0;
            $c_total   = 0;
            $c_outros  = 0;
            $c_util    = 0;
            $c_saldo   = 0;  
            $c         = -1;  
            $conta     = -1;      

            $t_valor   = 0;
            $t_extra   = 0;
            $t_total   = 0;
            $t_outros  = 0;
            $t_util    = 0;
            $t_saldo   = 0;  

            foreach ( $fats as $fat ) {
                $a_custo = $a_custo+($fat->VALOR_TOTAL+$fat->VALOR_TOTAL_EXTRA)-$fat->VALOR_TOTAL_DEV;             
            }   
                
            foreach ( $cotas as $i => $item )
            {

                // Verifica se é uma conta de GGF/GGA
                if ( trim($item->CCONTABIL) <> '99999999999999' ) {
                    
                    /***************************
                     *  Contabiliza valores  ***
                     ***************************/
                    
                    //CCustos
                    $a_valor  = $a_valor  + $item->VALOR ;
                    $a_extra  = $a_extra  + $item->EXTRA ;
                    $a_total  = $a_total  + $item->TOTAL ;
                    $a_outros = $a_outros + $item->OUTROS;
                    $a_util   = $a_util   + $item->UTIL  ;
                    $a_saldo  = $a_saldo  + $item->SALDO ;
                    //Períodos
                    $b_valor  = $b_valor  + $item->VALOR ;
                    $b_extra  = $b_extra  + $item->EXTRA ;
                    $b_total  = $b_total  + $item->TOTAL ;
                    $b_outros = $b_outros + $item->OUTROS;
                    $b_util   = $b_util   + $item->UTIL  ;
                    $b_saldo  = $b_saldo  + $item->SALDO ;
                    //CContabil
                    $c_valor  = $c_valor  + $item->VALOR ;
                    $c_extra  = $c_extra  + $item->EXTRA ;
                    $c_total  = $c_total  + $item->TOTAL ;
                    $c_outros = $c_outros + $item->OUTROS;
                    $c_util   = $c_util   + $item->UTIL  ;
                    $c_saldo  = $c_saldo  + $item->SALDO ;
                }

                foreach ( $fats as $fat ) {
                    if ( $fat->ANO.$fat->MES == $item->ANO.$item->MES ) {
                        $b_custo = ((($fat->VALOR_TOTAL+$fat->VALOR_TOTAL_EXTRA)-$fat->VALOR_TOTAL_DEV) >0) ? (($b_util+$b_outros) / (($fat->VALOR_TOTAL+$fat->VALOR_TOTAL_EXTRA)-$fat->VALOR_TOTAL_DEV))*100 : 0;
                    }                
                }   
                
                if ($item->TOTALIZA == 1 || $cota_totaliza )  {
                //Total
                    $t_valor  = $t_valor  + $item->VALOR ;
                    $t_extra  = $t_extra  + $item->EXTRA ;
                    $t_total  = $t_total  + $item->TOTAL ;
                    $t_outros = $t_outros + $item->OUTROS;
                    $t_util   = $t_util   + $item->UTIL  ;
                    $t_saldo  = $t_saldo  + $item->SALDO ;       
                }

                //Insere Centro de Custo
                if ($ccusto <> $item->CCUSTO) {
                    $ccusto  = $item->CCUSTO;
                    $a = $i;      

                    $a_valor  = $item->VALOR ;
                    $a_extra  = $item->EXTRA ;
                    $a_total  = $item->TOTAL ;
                    $a_outros = $item->OUTROS;
                    $a_util   = $item->UTIL  ;
                    $a_saldo  = $item->SALDO ; 
                }

                $ccustos[$a] = 
                (object) array(  
                    'CCUSTO'            => $item->CCUSTO,
                    'CCUSTO_MASK'       => $item->CCUSTO_MASK,
                    'CCUSTO_DESCRICAO'  => $item->CCUSTO_DESCRICAO,
                    'VALOR'             => $a_valor ,
                    'EXTRA'             => $a_extra ,
                    'TOTAL'             => $a_total ,
                    'OUTROS'            => $a_outros,
                    'UTIL'              => $a_util,
                    'SALDO'             => $a_saldo ,
                    'PERC_UTIL'         => $a_valor > 0 ? (($a_outros+$a_util)/$a_valor)*100 : (($a_valor == 0) && (($a_outros+$a_util) < 0) ? 100 : 0 ),
                    'FILTRO'            => 1,
                    'CUSTO_SETOR'       => ($a_custo > 0) ? (($a_util+$a_outros)/$a_custo)*100 : 0
                ); 

                //Insere Períodos
                if ($periodo <> $item->PERIODO_DESCRICAO.$item->CCUSTO) {
                    $periodo  = $item->PERIODO_DESCRICAO.$item->CCUSTO;
                    $b = $i;     

                    $b_valor  = $item->VALOR ;
                    $b_extra  = $item->EXTRA ;
                    $b_total  = $item->TOTAL ;
                    $b_outros = $item->OUTROS;
                    $b_util   = $item->UTIL  ;
                    $b_saldo  = $item->SALDO ; 
                }

                $periodos[$b] = 
                (object) array(  
                    'CCUSTO'            => $item->CCUSTO,
                    'ANO'               => $item->ANO,
                    'MES'               => $item->MES,
                    'PERIODO_DESCRICAO' => $item->PERIODO_DESCRICAO,
                    'VALOR'             => $b_valor ,
                    'EXTRA'             => $b_extra ,
                    'TOTAL'             => $b_total ,
                    'OUTROS'            => $b_outros,
                    'UTIL'              => $b_util,
                    'SALDO'             => $b_saldo ,
                    'PERC_UTIL'         => $b_valor > 0 ? (($b_outros+$b_util)/$b_valor)*100 : (($b_valor == 0) && (($b_outros+$b_util) < 0) ? 100 : 0 ),
                    'FILTRO'            => 1,
                    'CUSTO_SETOR'       => $b_custo
                ); 
                
                //Insere Contas
                if ($conta <> $item->CCONTABIL.$item->ANO.$item->MES.$item->CCUSTO) {
                    $conta  = $item->CCONTABIL.$item->ANO.$item->MES.$item->CCUSTO;
                    $c = $i;     

                    $c_valor  = $item->VALOR ;
                    $c_extra  = $item->EXTRA ;
                    $c_total  = $item->TOTAL ;
                    $c_outros = $item->OUTROS;
                    $c_util   = $item->UTIL  ;
                    $c_saldo  = $item->SALDO ; 
                }

                $contas[$c] = 
                (object) array(  
                    'TOTALIZA'           => $item->TOTALIZA,
                    'DESTAQUE'           => $item->DESTAQUE,
                    'ID'                 => $item->ID,
                    'CCUSTO'             => $item->CCUSTO,
                    'ANO'                => $item->ANO,
                    'MES'                => $item->MES,
                    'CCONTABIL'          => $item->CCONTABIL,
                    'CCONTABIL_MASK'     => $item->CCONTABIL_MASK,
                    'CCONTABIL_DESCRICAO'=> $item->CCONTABIL_DESCRICAO,
                    'VALOR'              => $c_valor ,
                    'EXTRA'              => $c_extra ,
                    'TOTAL'              => $c_total ,
                    'OUTROS'             => $c_outros,
                    'UTIL'               => $c_util,
                    'SALDO'              => $c_saldo ,
                    'PERC_UTIL'          => $c_valor > 0 ? (($c_outros+$c_util)/$c_valor)*100 : (($c_valor == 0) && (($c_outros+$c_util) < 0) ? 100 : 0 ),
                    'FILTRO'             => 1                
                );                                           
            }
           
            //Insere Totalizador CCusto
            $total = 
            (object) array(  
                'CCUSTO'            => '9999999999',
                'CCUSTO_MASK'       => 'TOTAL',
                'CCUSTO_DESCRICAO'  => '',
                'VALOR'             => $t_valor ,
                'EXTRA'             => $t_extra ,
                'TOTAL'             => $t_total ,
                'OUTROS'            => $t_outros,
                'UTIL'              => $t_util,
                'SALDO'             => $t_saldo ,
                'PERC_UTIL'         => $t_valor > 0 ? (($t_outros+$t_util)/$t_valor)*100 : (($t_valor == 0) && (($t_outros+$t_util) < 0) ? 100 : 0 ),
                'FILTRO'            => 1                
            ); 
            array_push($ccustos, $total);
            
            $order = 
            function($a, $b)
            {            
                //Ordena por ANO
                $c = $a->ANO - $b->ANO;
                if($c != 0) {
                    return $c;
                }

                //Ordena por MÊS
                $c = $a->MES - $b->MES;
                if($c != 0) {
                    return $c;
                }

                //Ordena por CContábil
                $c = strcmp($a->CCONTABIL, $b->CCONTABIL);
                if($c != 0) {
                    return $c;
                }

                return $c;            
            };            
            usort($cotas, $order);

            //Variáveis array de Cálculo

            $b_valor   = 0;
            $b_extra   = 0;
            $b_total   = 0;
            $b_outros  = 0;
            $b_util    = 0;
            $b_saldo   = 0;  
            $b         = -1;  
            $periodo   = -1;

            $c_valor   = 0;
            $c_extra   = 0;
            $c_total   = 0;
            $c_outros  = 0;
            $c_util    = 0;
            $c_saldo   = 0;  
            $c         = -1;  
            $conta     = -1;  

            foreach ( $cotas as $i => $item )
            { 

                if ($item->TOTALIZA == 1 || $cota_totaliza ) {     

                    //Contabiliza valores
                    //Períodos
                    $b_valor  = $b_valor  + $item->VALOR ;
                    $b_extra  = $b_extra  + $item->EXTRA ;
                    $b_total  = $b_total  + $item->TOTAL ;
                    $b_outros = $b_outros + $item->OUTROS;
                    $b_util   = $b_util   + $item->UTIL  ;
                    $b_saldo  = $b_saldo  + $item->SALDO ;
                    //CContabil
                    $c_valor  = $c_valor  + $item->VALOR ;
                    $c_extra  = $c_extra  + $item->EXTRA ;
                    $c_total  = $c_total  + $item->TOTAL ;
                    $c_outros = $c_outros + $item->OUTROS;
                    $c_util   = $c_util   + $item->UTIL  ;
                    $c_saldo  = $c_saldo  + $item->SALDO ;


                    //Insere Períodos
                    if ($periodo <> $item->PERIODO_DESCRICAO.'9999999999') {
                        $periodo  = $item->PERIODO_DESCRICAO.'9999999999';
                        array_push($periodos, (object) array());
                        $b = max(array_keys($periodos));   

                        $b_valor  = $item->VALOR ;
                        $b_extra  = $item->EXTRA ;
                        $b_total  = $item->TOTAL ;
                        $b_outros = $item->OUTROS;
                        $b_util   = $item->UTIL  ;
                        $b_saldo  = $item->SALDO ;
                    }

                    $periodos[$b] = 
                    (object) array(  
                        'CCUSTO'            => '9999999999',
                        'ANO'               => $item->ANO,
                        'MES'               => $item->MES,
                        'PERIODO_DESCRICAO' => $item->PERIODO_DESCRICAO,
                        'VALOR'             => $b_valor ,
                        'EXTRA'             => $b_extra ,
                        'TOTAL'             => $b_total ,
                        'OUTROS'            => $b_outros,
                        'UTIL'              => $b_util,
                        'SALDO'             => $b_saldo ,
                        'PERC_UTIL'         => $b_valor > 0 ? (($b_outros+$b_util)/$b_valor)*100 : (($b_valor == 0) && (($b_outros+$b_util) < 0) ? 100 : 0 ),
                        'FILTRO'            => 1                
                    ); 

                    //Insere Contas
                    if ($conta <> $item->CCONTABIL.$item->ANO.$item->MES.'9999999999') {
                        $conta  = $item->CCONTABIL.$item->ANO.$item->MES.'9999999999';
                        array_push($contas, (object) array());
                        $c = max(array_keys($contas));     

                        $c_valor  = $item->VALOR ;
                        $c_extra  = $item->EXTRA ;
                        $c_total  = $item->TOTAL ;
                        $c_outros = $item->OUTROS;
                        $c_util   = $item->UTIL  ;
                        $c_saldo  = $item->SALDO ; 
                    }

                    $contas[$c] = 
                    (object) array(  
                        'TOTALIZA'           => $item->TOTALIZA,
                        'DESTAQUE'           => $item->DESTAQUE,
                        'ID'                 => $item->ID,
                        'CCUSTO'             => '9999999999',
                        'ANO'                => $item->ANO,
                        'MES'                => $item->MES,
                        'CCONTABIL'          => $item->CCONTABIL,
                        'CCONTABIL_MASK'     => $item->CCONTABIL_MASK,
                        'CCONTABIL_DESCRICAO'=> $item->CCONTABIL_DESCRICAO,
                        'VALOR'              => $c_valor ,
                        'EXTRA'              => $c_extra ,
                        'TOTAL'              => $c_total ,
                        'OUTROS'             => $c_outros,
                        'UTIL'               => $c_util,
                        'SALDO'              => $c_saldo ,
                        'PERC_UTIL'          => $c_valor > 0 ? (($c_outros+$c_util)/$c_valor)*100 : (($c_valor == 0) && (($c_outros+$c_util) < 0) ? 100 : 0 ),
                        'FILTRO'             => 1                
                    );    
                }
            }        
            
            $t_fat = 0;
            $o_fat = 0;
            $e_fat = 0;
            foreach ( $fats as $fat ) {
                $t_fat = $t_fat + $fat->VALOR_TOTAL;
                $o_fat = $o_fat + $fat->VALOR_TOTAL_DEV;
                $e_fat = $e_fat + $fat->VALOR_TOTAL_EXTRA;
                
                $a_fat = 
                (object) array(  
                    'CCUSTO'            => '99999999999',
                    'ANO'               => $fat->ANO,
                    'MES'               => $fat->MES,
                    'PERIODO_DESCRICAO' => $fat->PERIODO_DESCRICAO,
                    'VALOR'             => $fat->VALOR_TOTAL,
                    'EXTRA'             => $fat->VALOR_TOTAL_EXTRA,
                    'TOTAL'             => $fat->VALOR_TOTAL+$fat->VALOR_TOTAL_EXTRA,
                    'OUTROS'            => $fat->VALOR_TOTAL_DEV,
                    'UTIL'              => 0,
                    'SALDO'             => ($fat->VALOR_TOTAL+$fat->VALOR_TOTAL_EXTRA)-$fat->VALOR_TOTAL_DEV,
                    'PERC_UTIL'         => 0,
                    'FILTRO'            => 1                     
                ); 
                array_push($periodos, $a_fat);                  
            }
                       
            if ( $param->COTA_FATURAMENTO == '1' )  {
            
                //Insere o Faturamento
                $a_fat = 
                (object) array(  
                    'CCUSTO'            => '99999999999',
                    'CCUSTO_MASK'       => 'FATURAMENTO',
                    'CCUSTO_DESCRICAO'  => '',
                    'VALOR'             => $t_fat,
                    'EXTRA'             => $e_fat,
                    'TOTAL'             => $e_fat+$t_fat,
                    'OUTROS'            => $o_fat,
                    'UTIL'              => 0,
                    'SALDO'             => ($e_fat+$t_fat)-$o_fat,
                    'PERC_UTIL'         => 0,
                    'FILTRO'            => 1                
                ); 
                array_push($ccustos, $a_fat);     
            }

//            return view(
//                'compras._13030.index.accordion', [
//                'ret' => [
//                    'ccustos'	=> $ccustos,
//                    'periodos'	=> $periodos,
//                    'contas'	=> $contas,
//                    'itens'		=> $itens
//                ],
//                'permissaoMenu' => _11010::permissaoMenu($this->menu)
//            ]);
            
            return $this->montaAccordion([
                    'ccustos'	=> $ccustos,
                    'periodos'	=> $periodos,
                    'contas'	=> $contas,
                    'itens'		=> $itens
            ]);            
    	}
    }
    
    public function montaAccordion($ret)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
    	$res = '';
    	$i = 0;
    	$x = 0;
                
    	if( !empty($ret) ) {
            set_time_limit(0);
    		 //Nível Centro de Custo
    		foreach ($ret['ccustos'] as $ccusto_key => $ccusto) {
    			$ccusto_valor  = "R$ ". number_format($ccusto->VALOR,  2, ',', '.');
    			$ccusto_extra  = "R$ ". number_format($ccusto->EXTRA,  2, ',', '.');
    			$ccusto_total  = "R$ ". number_format($ccusto->TOTAL,  2, ',', '.');
    			$ccusto_outros = "R$ ". number_format($ccusto->OUTROS, 2, ',', '.');
    			$ccusto_saldo  = "R$ ". number_format($ccusto->SALDO,  2, ',', '.');
    			$ccusto_util   = $ccusto->CCUSTO <> 99999999999 ? "R$ ". number_format($ccusto->UTIL,   2, ',', '.')  : '';
                $ccusto_perc   = $ccusto->CCUSTO <> 99999999999 ? number_format($ccusto->PERC_UTIL, 2, ',', '.') .'%' : '';
                $ccusto_custo  = isset($ccusto->CUSTO_SETOR) ? number_format($ccusto->CUSTO_SETOR, 2, ',', '.') . '%' : '';
    			
    			$i++;
    			$negativo = floatval($ccusto->PERC_UTIL) > 100 ? 'negativo' : '';
    			
    			$res .= "<div class='panel panel-default heading". $ccusto->CCUSTO ."avo'>";
    			$res .= "	<div class='panel-heading heading-ccusto " . $negativo . " " . ($ccusto->CCUSTO == 9999999999 ? "total" : "") . " " . ($ccusto->CCUSTO == 99999999999 ? "faturado" : "") . " " . $negativo . "' role='tab' id='heading". $ccusto->CCUSTO ."'>";
    			$res .= "       <a role='button' data-toggle='collapse' href='#collapse". $ccusto->CCUSTO ."' aria-controls='collapse". $ccusto->CCUSTO ."'>";
    			$res .= "           <div class='label'>". $ccusto->CCUSTO_MASK ."</div>";
    			$res .= "           <div class='label limit-width'>". $ccusto->CCUSTO_DESCRICAO ."</div>";
    			$res .= "           <div class='label'>" . $ccusto_valor  ."</div>";
    			$res .= "           <div class='label'>" . $ccusto_extra  ."</div>";
    			$res .= "           <div class='label'>" . $ccusto_total  ."</div>";
    			$res .= "           <div class='label'>" . $ccusto_outros ."</div>";
    			$res .= "           <div class='label'>" . $ccusto_util   ."</div>";
    			$res .= "           <div class='label'>" . $ccusto_perc   ."</div>";
    			$res .= "           <div class='label'>" . $ccusto_saldo  ."</div>";
    			$res .= "           <div class='label'>" . $ccusto_custo  ."</div>";
    			$res .= "       </a>";
    			$res .= "   </div>";
    			$res .= "   <div id='collapse". $ccusto->CCUSTO ."' class='panel-collapse collapse' role='tabpanel' aria-labelledby='heading". $ccusto->CCUSTO ."'>";
    			$res .= "   <div class='panel-body'>";
    			$res .= "   <div class='panel-group' id='accordion". $i ."' role='tablist' aria-multiselectable='true'>";
    
    			// Nível Período
    			foreach ($ret['periodos'] as $periodo_key => $periodo) {
                    $periodo_valor  = "R$ ". number_format($periodo->VALOR,  2, ',', '.');
                    $periodo_extra  = "R$ ". number_format($periodo->EXTRA,  2, ',', '.');
                    $periodo_total  = "R$ ". number_format($periodo->TOTAL,  2, ',', '.');
                    $periodo_outros = "R$ ". number_format($periodo->OUTROS, 2, ',', '.');
                    $periodo_util   = $periodo->CCUSTO <> 99999999999 ? "R$ ". number_format($periodo->UTIL,   2, ',', '.')  : '';
                    $periodo_saldo  = "R$ ". number_format($periodo->SALDO,  2, ',', '.');
                    $periodo_perc   = $periodo->CCUSTO <> 99999999999 ? number_format($periodo->PERC_UTIL, 2, ',', '.') .'%' : '';                    
                    $periodo_custo  = isset($periodo->CUSTO_SETOR) ? number_format($periodo->CUSTO_SETOR, 2, ',', '.') . '%' : '';
    
    				if ($periodo->CCUSTO === $ccusto->CCUSTO){
    					$x++;
    					$periodo_id = $periodo->CCUSTO . $periodo->ANO . $periodo->MES;
                        $negativo = floatval($periodo->PERC_UTIL) > 100 ? 'negativo' : '';
    					
    					$res .= "<div class='panel panel-default heading". $periodo_id ."pai'>";
    					$res .= "   <div class='panel-heading heading-periodo " . $negativo . "' role='tab' id='heading". $periodo_id ."'>";
    					$res .= "         <a role='button' data-toggle='collapse' " . ($periodo->CCUSTO == 99999999999 ? '' : "href='#collapse". $periodo_id ."'") . " aria-controls='collapse". $periodo_id ."'>";
    					$res .= "           <div class='label'>". $periodo->PERIODO_DESCRICAO ."</div>";
    					$res .= "           <div class='label'>". $periodo_valor  ."</div>";
    					$res .= "           <div class='label'>". $periodo_extra  ."</div>";
    					$res .= "           <div class='label'>". $periodo_total  ."</div>";
    					$res .= "           <div class='label'>". $periodo_outros ."</div>";
    					$res .= "           <div class='label'>". $periodo_util   ."</div>";
    					$res .= "           <div class='label'>". $periodo_perc   ."</div>";
    					$res .= "           <div class='label'>". $periodo_saldo  ."</div>";
    					$res .= "           <div class='label'>". $periodo_custo  ."</div>";
    					$res .= "           </a>";
    					$res .= "           </div>";
    					$res .= "       <div id='collapse". $periodo_id ."' class='panel-collapse collapse' role='tabpanel' aria-labelledby='heading". $periodo_id ."'> ";
    					$res .= "   <div class='panel-body'>";
    					$res .= "   <div class='panel-group heading". $periodo_id ."grupo' id='accordion". $i . $x ."' role='tablist' aria-multiselectable='true'>";
    
    					// Nível Conta Contábil 
    					foreach ($ret['contas'] as $ccontabil_key => $conta){
    						
    						if ($conta->CCUSTO === $periodo->CCUSTO && $conta->ANO === $periodo->ANO && $conta->MES === $periodo->MES ){

    							$conta_id = $periodo_id  . $conta->CCONTABIL;
    							
//                                if ( $permissaoMenu->ALTERAR ) {
//                                    $EditRota = '   <button type="button" class="btn btn-primary btn-sm popup-show" id="' . $conta->ID . '"><span class="glyphicon glyphicon-info-sign"></span> Detalhar</button>'; 
////                                    $EditRota = '   <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href=\'' . route('_13030.edit', $conta->ID) . '\'"><span class="glyphicon glyphicon-edit"></span> Alterar</button>'; 
//                                } else {
//                                    $EditRota = '   <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href=\'' . route('_13030.show', $conta->ID) . '\'"><span class="glyphicon glyphicon-info-sign"></span> Detalhar</button>'; 
//                                }
    							$DelClass = $permissaoMenu->EXCLUIR ? 'deletar' : '';
    							$DelPerm  = $permissaoMenu->EXCLUIR ? '' : 'disabled';
    							$destaque = $conta->DESTAQUE == 1 ? 'destaque' : '';
                                $negativo = floatval($conta->PERC_UTIL) > 100 ? 'negativo' : '';
    
    							$res .= "   <div class='panel panel-default heading". $conta_id ."filho''>";
    							$res .= "   <div class='panel-heading heading-ccontabil " . $destaque . " " . $negativo . "' role='tab' id='heading". $conta_id ."'>";
    							$res .= "   <a role='button' " . ( ( !($conta->CCUSTO == 9999999999) && !($conta->CCONTABIL == 99999999999999) ) ? 'class="popup-show" id="' . $conta->ID . '"' : " data-toggle='collapse' " . ($conta->CCUSTO == 9999999999 ? '' : "href='#collapse" . $conta_id . "'") )  . " aria-controls='collapse". $conta_id ."'> ";
    							$res .= "   <div class='label'>". $conta->CCONTABIL_MASK ."</div>";
    							$res .= "   <div class='label'>". $conta->CCONTABIL_DESCRICAO . ((($conta->TOTALIZA == 1) && ($conta->CCUSTO <> 9999999999)) ? "<span class='glyphicon glyphicon-plus' data-toggle='tooltip' title='Esta C. Contábil será contabilizada no totalizador geral'></span>" : "") . "</div>";
    							$res .= "   <div class='label'>R$ ". number_format($conta->VALOR,  2, ',', '.') ."</div>";
    							$res .= "   <div class='label'>R$ ". number_format($conta->EXTRA,  2, ',', '.') ."</div>";
    							$res .= "   <div class='label'>R$ ". number_format($conta->TOTAL,  2, ',', '.') ."</div>";
    							$res .= "   <div class='label'>R$ ". number_format($conta->OUTROS, 2, ',', '.') ."</div>";
    							$res .= "   <div class='label'>R$ ". number_format($conta->UTIL,   2, ',', '.') ."</div>";
    							$res .= "   <div class='label'>".    number_format($conta->PERC_UTIL, 2, ',', '.') .'%' ."</div> ";
    							$res .= "   <div class='label'>R$ ". number_format($conta->SALDO,  2, ',', '.') ."</div>";
    							$res .= "   </a>";
    							if ( !($conta->CCUSTO == 9999999999) && !($conta->CCONTABIL == 99999999999999) ) {
    							$res .= "   <div class='acoes'>";
    							$res .= '<button type="button" class="btn btn-primary btn-sm popup-show" id="' . $conta->ID . '" contaid="'.$conta->ID.'" classdelete="heading'. $conta_id .'filho" classpai="heading'. $periodo->CCUSTO . $periodo->ANO . $periodo->MES .'pai" classavo="heading'. $periodo->CCUSTO .'avo" classgrupopai="heading'. $periodo->CCUSTO . $periodo->ANO . $periodo->MES .'grupo"><span class="glyphicon glyphicon-info-sign"></span> Detalhar</button>';
    							if ( $conta->PERC_UTIL > 100 ) {
                                        $res .= '<button type="button" data-toggle="modal" data-target="#planacao-modal" tela="130301" subvinculo="'.$conta->ID.'" class="btn btn-danger  btn-sm popup-show-plano-acao" id="'.$conta->CCONTABIL.'" perfil="1" ccusto="'.$conta->CCUSTO.'" vinculo="'.$conta->CCONTABIL.'" controlen="225" descpa="'. $ccusto->CCUSTO_MASK.' - '.$ccusto->CCUSTO_DESCRICAO.' : '.$conta->CCONTABIL_DESCRICAO.'" oque="'.$conta->CCONTABIL_DESCRICAO.'" classdelete="heading'. $conta_id .'filho" classpai="heading'. $periodo->CCUSTO . $periodo->ANO . $periodo->MES .'pai" classavo="heading'. $periodo->CCUSTO .'avo" classgrupopai="heading'. $periodo->CCUSTO . $periodo->ANO . $periodo->MES .'grupo"><span class="glyphicon glyphicon-info-sign"></span> Plan. Ação</button>';
                                }
                                //$res .= '   <button type="button" class="btn btn-danger btn-sm ' . $DelClass . '" ' . $DelPerm . ' contaid="'.$conta->ID.'" classdelete="heading'. $conta_id .'filho" classpai="heading'. $periodo->CCUSTO . $periodo->ANO . $periodo->MES .'pai" classavo="heading'. $periodo->CCUSTO .'avo" classgrupopai="heading'. $periodo->CCUSTO . $periodo->ANO . $periodo->MES .'grupo"><span class="glyphicon glyphicon-trash"></span> Excluir</button> ';
    							$res .= '   </div>';
    							}
    							$res .= "   </div>";
    							$res .= "   <div id='collapse". $conta_id ."' class='panel-collapse collapse lista-itens' role='tabpanel' aria-labelledby='heading". $conta_id ."'>";
    							$res .= "   <div class='list-group'>";
    							$res .= "   <ul class='list-group'>";
    
    							$count = 0;
    						
    							// Nível Itém
    							foreach($ret['itens'] as $item_key => $iten){
    
    								if( $iten->CCUSTO == $conta->CCUSTO && $iten->CCONTABIL == $conta->CCONTABIL && $iten->MES == $conta->MES && $iten->ANO == $conta->ANO ){
    
    									$nat_class = strripos($iten->NATUREZA,'D') === 0 ? 'nat-debito' : 'nat-credit';
    									$nat_desc  = strripos($iten->NATUREZA,'D') === 0 ? 'Natureza: Débito' : 'Natureza: Crédito';
    									
                                        // Item do GGF
                                        if ( $iten->NATUREZA == 'G' ) {
                                            
                                            if ( $iten->PERCENTUAL_UTILIZADO > 100 ) {
                                                $percentual = "<span style='color: rgb(169, 15, 15);'> ". number_format($iten->PERCENTUAL_UTILIZADO, 2, ',', '.') ."%</span>";
                                            } else {
                                                $percentual = number_format($iten->PERCENTUAL_UTILIZADO, 2, ',', '.') ."%";
                                            }
                                            
                                            $res .= "   <li class='list-group-item natureza-" . $iten->NATUREZA . "'>";
                                            $res .= "   	<div class='label descricao' title='".$iten->DESCRICAO."'>".$iten->DESCRICAO."</div>";
                                            $res .= "   	<div class='label extra'>R$ ". number_format($iten->VALOR_CREDITO, 2, ',', '.') ."</div>";
                                            $res .= "   	<div class='label cota'>R$ ". number_format($iten->VALOR_COTA, 2, ',', '.') ."</div>";
                                            $res .= "   	<div class='label utilizado'>R$ ". number_format($iten->VALOR_UTILIZADO, 2, ',', '.') ."</div>";
                                            $res .= "   	<div class='label percentual'>" . $percentual . "</div>";
                                            $res .= "   	<div class='label saldo'>R$ ". number_format($iten->SALDO, 2, ',', '.') ."</div>";
                                            $res .= '       <button type="button" class="btn btn-primary btn-sm show-modal-ggf" data-item="ggf" data-ccusto="'.$iten->CCUSTO.'" data-mes="'.$iten->MES.'" data-ano="'.$iten->ANO.'" data-familia_id="'.$iten->FAMILIA_ID.'"><span class="glyphicon glyphicon-info-sign"></span> Detalhar</button>';
                                            $res .= "   </li>";
                                        } else {
                                            $res .= "   <li class='list-group-item'>";
                                            $res .= "   	<div class='label'>".$iten->DESCRICAO."</div>";
                                            $res .= "   	<div class='label'>(R$ ". number_format($iten->DESCONTO_IMPOSTO, 2, ',', '.') .")</div>";
                                            $res .= "   	<div class='label'>R$ ". number_format($iten->VALOR, 2, ',', '.') ."</div>";
                                            $res .= '   	<div class="label '. $nat_class .'" data-toggle="tooltip" data-placement="auto" title="' . $nat_desc . '">' .  $iten->NATUREZA  . '</div>';
                                            $res .= "   	<div class='label'>". date_format(date_create($iten->DATA), 'd/m/Y') ."</div>";
                                            $res .= "   </li>";
                                        }
                                        
    									$count++;
                                        unset($ret['itens'][$item_key]);
    								}
    							}
    
    							$res .= "                   </ul>";
    							$res .= "               <div class='panel-footer'>Nº de registros: ". $count ."</div>";
    							$res .= "           </div>";
    							$res .= "       </div>";
    							$res .= "   </div>";
                                unset($ret['contas'][$ccontabil_key]);
    						}
                            
    					}
    
    					$res .= "               </div>";
    					$res .= "           </div>";
    					$res .= "       </div>";
    					$res .= "   </div>";
                        unset($ret['periodos'][$periodo_key]);
    				}
                    
    			}
    
    			$res .= "                   </div>";
    			$res .= "               </div>";
    			$res .= "           </div>";
    			$res .= "       </div> ";
                
                unset($ret['ccustos'][$ccusto_key]);
    		}
    		
    		
    	}
        
    	return $res;
    }
    
    public function ggf(Request $request)
    {
        $this->con = new _Conexao;
        try {
            
            $args = (object) $request->all();
            
            if ( isset($args->ajuste_inventario) ) {

                $ggfs = _13030::selectAjusteInventario($args,$this->con);

                $args->GROUP_FAMILIA = false;
                $ggf = _13030::selectAjusteInventario($args,$this->con);                
            } else {

                $ggfs = _13030::selectGgf($args,$this->con);

                $args->GROUP_FAMILIA = false;
                $ggf = _13030::selectGgf($args,$this->con);
            }
            
            
            if ( !isset($ggf[0]) ) {
                log_erro('Registro não localizado.');
            }
            
            $ggf = $ggf[0];
            
            
            $this->con->commit();
            
            $view = View::make('compras._13030.show-ggf.modal-body',[
                'ggf'    => $ggf,
                'ggfs'   => $ggfs
            ])->render();
            
            return $view;
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }
    
    public function ggfDetalhe(Request $request)
    {
        $this->con = new _Conexao;
        try {
            
            $param = (object) $request->all();
            
            $time = strtotime($request->ano .'-' . $request->mes . '-01');
            
            $param->DATA_1 = date('Y.m.01',$time);
            $param->DATA_2 = date('Y.m.t' ,$time); 	   

            
            if ( isset($param->ajuste_inventario) ) {
            
                $ggf = _13030::selectAjusteInventario($param,$this->con);

                if ( !isset($ggf[0]) ) {
                    log_erro('Registro não localizado não localizado');
                }

                $ggf = $ggf[0];

                $dados = _13030::selectAjusteInventarioDetalhe($param,$this->con);
            } else {
            
                $ggf = _13030::selectGgf($param,$this->con);

                if ( !isset($ggf[0]) ) {
                    log_erro('Registro não localizado não localizado');
                }

                $ggf = $ggf[0];

                $dados = _13030::selectGgfDetalhe($param,$this->con);
            }
            
            $this->con->commit();
            
            
            return View::make('compras._13030.show-ggf-detalhe.modal-body',[
                'ggf'   => $ggf,
                'dados' => $dados
            ])->render();
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }
    
    public function dre(Request $request)
    {        
        $request->mes_1 = '1';           //Janeiro
        $request->mes_2 = date('m');     //Mês corrente
        $request->ano_1 = date('Y');     //Ano corrente
        $request->cota_zerada   = true; //Não exibe cotas zeradas
        $request->cota_valida   = true;  //Exibe cotas válidas
        $request->cota_totaliza = false; //Totaliza todas as cotas
        
        $table = $this->dreFilter($request);
        
        $meses = array(1 => ['01','Janeiro'],['02','Fevereiro'],['03','Março'],['04','Abril'],['05','Maio'],['06','Junho'],['07','Julho'],['08','Agosto'],['09','Setembro'],['10','Outubro'],['11','Novembro'],['12','Dezembro']);        
        
    	return view(
    		'compras._13030.dre', [
        	'var'   => $table,
            'print' => $table,
            'cota_zerada'	=> $request->cota_zerada,
        	'cota_valida'	=> $request->cota_valida,
        	'cota_totaliza'	=> $request->cota_totaliza,
        	'meses'			=> $meses
    	]);
    }
    
    public function dreShow(Request $request) {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $id = $request->id;
        
    	$res = _13030::exibir($id);
                
    	return view(
    		'compras._13030.show.body', [
        	'permissaoMenu' => $permissaoMenu,
    		'id' 		=> $id, 
    		'cota' 		=> $res['cota'], 
    		'itens'		=> $res['cota_itens'], 
    		'extras' 	=> $res['cota_extra'], 
    		'outros' 	=> $res['cota_outro'],
            'ref'       => $request->ref
    	]);
    }
    
    public function dreFilter(Request $request)
    {        
        set_time_limit(0);
        
        $filtro			  = $request->filtro == null ? null : ($request->filtro ? '%' . Helpers::removeAcento($request->filtro, '%', 'upper',true) . '%' : '');	
        $cota_zerada      = $request->cota_zerada            === 'true' ? true : null;
        $cota_valida	  = $request->cota_valida            === 'true' ? true : null;
        $cota_totaliza	  = $request->cota_totaliza          === 'true' ? true : null;
        $cota_ggf	      = $request->cota_ggf               === 'true' ? true : null;
        $cota_inv	      = $request->cota_ajuste_inventario === 'true' ? true : null;
        $cota_faturamento = $request->cota_faturamento       === 'true' ? true : null;
        
        $mes_1  = $request->mes_1;
        $ano_1  = $request->ano_1;
        $mes_2  = $request->mes_2;
        $ano_2  = $request->ano_2 ? $request->ano_2 : $request->ano_1;
        $time_1 = strtotime($ano_1 .'-' . $mes_1 . '-01');
        $time_2 = strtotime($ano_2 .'-' . $mes_2 . '-01');    		
        $data_1 = date('Y.m.01',$time_1);
        $data_2 = date('Y.m.t' ,$time_2); 	   
       
        
        $res = _13030::listar((object) [
            'DATA_1'        => $data_1,
            'DATA_2'        => $data_2,
            'COTA_ZERADA'   => $cota_zerada,
            'COTA_VALIDA'   => $cota_valida,
            'COTA_FATURAMENTO' => $cota_faturamento,
            'COTA_GGF'         => $cota_ggf,            
            'COTA_INV'         => $cota_inv,            
            'FILTRO'        => $filtro,
            'DADOS'         => 
                (object) [
                    'COTA' => true,
                    'FAT'  => true
                ]
        ]);  
        
        $table = 
        _13030Dre::table(
            (object) array (
                'COTA'          => $res['cota'],
                'FATURAMENTO'   => $res['fat'],
                'COTA_TOTALIZA' => $cota_totaliza,
                'ANO'           => $ano_1
            )
        );
        
        return $table;
    }
    
    public function drePdf(Request $request) {
        
    	_11010::permissaoMenu($this->menu);
                
        $table = $request->table;      
        
        //caminho e nome do arquivo
        $arq_temp = public_path().'/assets/temp/DRE.pdf';

        //apagar arquivo, caso já exista
        if(file_exists($arq_temp) ) {
            unlink($arq_temp);
        }
        
        PDF::setPaper('A4','landscape')    
            ->loadView('compras._13030.dre_pdf_body', ['table' => $table])
            ->setOption('header-html',view('compras._13030.dre_pdf_header'),'html')   
            ->setOption('footer-html',view('compras._13030.dre_pdf_footer'),'html')   
            ->save($arq_temp)
        ;  
        
        return '/assets/temp/DRE.pdf';
    }
    

    
    public function fatIndex()
    {
        _11010::controle(198, true); // 198 - PERMITE GERENCIAR FATURAM. NAS COTAS ORÇAMENTÁRIAS

        $fat = $this->fatLancamentos();

        return view(
            'compras._13030.faturamento.create',[
            'faturamentos' => $fat
        ]);
    }
    
    public function fatStore(Request $request)
    {
        _11010::controle(198, true); // 198 - PERMITE GERENCIAR FATURAM. NAS COTAS ORÇAMENTÁRIAS      

        validator($request->all(), [
            'ESTABELECIMENTO_ID' => ['Estabelecimento','required'],
            'ANO'                => ['Ano'            ,'required'],
            'MES'                => ['Mês'            ,'required'],
            'VALOR'              => ['Valor'          ,'required|numeric|min:1'],
        ],true);
        
        $request['ID'] = _13030::faturamentoGerarId();
        
        _13030::faturamentoStore((object) $request->all());
    }
    
    public function fatUpdate(Request $request)
    {
        _11010::controle(198, true); // 198 - PERMITE GERENCIAR FATURAM. NAS COTAS ORÇAMENTÁRIAS

        validator($request->all(), [
            'ID'                 => ['Id','required'],
            'ESTABELECIMENTO_ID' => ['Estabelecimento','required'],
            'ANO'                => ['Ano'            ,'required'],
            'MES'                => ['Mês'            ,'required'],
            'VALOR'              => ['Valor'          ,'required|numeric|min:1'],
        ],true);
           
        _13030::faturamentoUpdate((object) $request->all());
    }
    
    public function fatDestroy(Request $request)
    {
        _11010::controle(198, true); // 198 - PERMITE GERENCIAR FATURAM. NAS COTAS ORÇAMENTÁRIAS

        validator($request->all(), [
            'ID'                 => ['Id','required']
        ],true);

        _13030::faturamentoDestroy((object) $request->all());
    }
    
    public function fatTable()
    {
        _11010::controle(198, true); // 198 - PERMITE GERENCIAR FATURAM. NAS COTAS ORÇAMENTÁRIAS
        
        return view('compras._13030.faturamento.include.table-lancamentos',['faturamentos'=>$this->fatLancamentos()]);
    }
    
    
    public function fatLancamentos($param = []) {
        
        $ano = isset($param->ANO) ? $param->ANO : date('Y');
        
        $param = (object) array(
            'ANO' => $ano
        );
        
        return _13030::faturamentoQuery($param);
    }
    
    public function replicarIndex()
    {
        _11010::permissaoMenu($this->menu,'INCLUIR','Replicando Cotas');

        return view('compras._13030.replicar.create');
    }
    
    public function replicarStore(Request $request)
    {
        _11010::permissaoMenu($this->menu,'INCLUIR','Replicando Cotas - Fixando Dados');
        
        Helpers::validator($request->all(), [
            'ano_origem'  => 'required|numeric|min:4',
            'mes_origem'  => 'required|numeric|min:1',
            'ano_destino' => 'required|numeric|min:4',
            'mes_destino' => 'required|numeric|min:1',
        ]);
        
        $data_origem  = date('Y.m.d', strtotime($request->ano_origem .'-'.$request->mes_origem .'-01'));
        $data_destino = date('Y.m.d', strtotime($request->ano_destino.'-'.$request->mes_destino.'-01'));

        _13030::replicarStore((object)[
            'DATA_ORIGEM'  => $data_origem,
            'DATA_DESTINO' => $data_destino,
        ]);
    }    
}


class _13030Dre extends Controller
{ 
    private static $cotas;
    private static $cota_totaliza;
    private static $faturamentos;
    private static $periodos;
    private static $ano;

    /**
     * 
     * @param (object)array $param<br/>
     * <ul>
     * <li><b>COTA</b> => Cotas do Controle Orçamentário</li>
     * <li><b>FATURAMENTO</b> => Faturamento Mensal do Estabelecimento</li>
     * </ul>
     * @return string Tabela em HTML
     */
    public static function table($param = [])
    {
        self::$cotas         = $param->COTA;
        self::$cota_totaliza = $param->COTA_TOTALIZA;
        self::$faturamentos  = isset($param->FATURAMENTO) ? $param->FATURAMENTO : null;
        self::$ano           = $param->ANO;
        
        $struct = _13030Dre::dataStruct();        
        
        $ccustos        = $struct->CCUSTO;
        $contas         = $struct->CCONTABIL;
        self::$periodos = $struct->PERIODO;
  
        $calc = _13030Dre::dataCalc();
        
        $ccustos_calc   = $calc->CCUSTO;
        $ccontabil_calc = $calc->CCONTABIL;
        
        $ccustos    = $ccustos;
        $contas     = $contas;
        $periodos   = self::$periodos;
        $datas_c    = $ccustos_calc;
        $datas      = $ccontabil_calc;
        
        $t  =       '<div class="table-ec" style="height: calc(100vh - 306px);">'; 
        $t .=       '<table class="table table-striped table-bordered table-hover lista-obj cota-horizontal table-condensed table-middle table-no-break" border="solid">'; 
        $t .=           '<thead>';
        $t .=               '<tr>';
        //$t .=                   '<th class="t-low-med text-center">C. Custo</th>';
        $t .=                   '<th class="t-med-larg text-center">C. Contábil</th>';
        set_time_limit(0);
        foreach ( $periodos as $periodo ) {
            $t .=               '<th class="' . (($periodo->ANO > 9999999) ? 't-low-tot' : 't-low-sm meses-transition') . ' text-center">' . $periodo->PERIODO_DESCRICAO . '</th>';
        }
        $t .=               '</tr>';
        $t .=           '</thead>';
        $t .=           '<body>';
        
        set_time_limit(0);
        foreach ( $ccustos as $ccusto ) {
                       
            if ( $ccusto->CCUSTO == '9999999999' ) {
                $agrup = 'total';
            } elseif ( $ccusto->CCUSTO == '99999999999' ) {
                $agrup = 'faturamento';
            } else {
                $agrup = 'ccusto';
            }
            
            //*
            $t .= '<tr class="' . $agrup . '">';
            $t .= '<td>' . (( $ccusto->CCUSTO_MASK == '' ) ? '' : $ccusto->CCUSTO_MASK . ' - ') . $ccusto->CCUSTO_DESCRICAO . '</td>';
            
            foreach ( $periodos as $periodo ) {
                $t .=  '<td class="text-right ' . (($periodo->ANO > 9999999) ? '' : ' meses-transition') . '">';
                foreach ( $datas_c as $data ) {
                    if ( $data->CCUSTO == $ccusto->CCUSTO) {
                        
                        if ( $ccusto->CCUSTO.$periodo->ANO.$periodo->MES == $data->CCUSTO.$data->ANO.$data->MES ) {
                                        
                            // Coluna de cotas
                            if ( $periodo->ANO == 9999999999999 ) {
                                $t .=   '<div class="valor">R$ ' . number_format($data->UTIL,  2, ',', '.') . '</div><div class="perc">' . (( $data->PERC_UTIL == '' ) ? '' : 'R$ ' . number_format($data->PERC_UTIL,  2, ',', '.')) . '</div>';
                            } else {
                                $t .=   '<div class="valor">R$ ' . number_format($data->UTIL,  2, ',', '.') . '</div><div class="perc ' . (($data->PERC_UTIL > 100) ? 'media-red' : '') . '">' . (( $data->PERC_UTIL == '' ) ? '' : number_format($data->PERC_UTIL,  2, ',', '.') . '%') . '</div>';
                            }                            
                        }
                    }
                }                        
                $t .= '</td>';
            }
            //*/
            
            $t .= '</tr>';
            foreach ( $contas as $conta ) {
                if ( $conta->CCUSTO == $ccusto->CCUSTO ) {
                    $t .=   '<tr>';
                    $t .=       '<td>' . $conta->CCONTABIL_MASK . ' - ' . $conta->CCONTABIL_DESCRICAO . '</td>';
                    foreach ( $periodos as $periodo ) {
                        $t .=   '<td class="text-right ' . (($periodo->ANO > 9999999) ? '' : ' meses-transition') . '">';
                        foreach ( $datas as $data ) {

                            if ( $data->CCUSTO == $ccusto->CCUSTO ) {
        
                                if ( $conta->CCUSTO.$conta->CCONTABIL.$periodo->ANO.$periodo->MES == $data->CCUSTO.$data->CCONTABIL.$data->ANO.$data->MES ) {

                                    if ( $data->ID != '' ) {
                                        
                                        if ( $data->TIPO == 'GGF' || $data->TIPO == 'INV' ) {
                                            $t .= '<a class="popup-show ' . (( $data->TOTALIZA == 1 )? 'totaliza' : '') . '" ' . ( $data->TIPO == 'INV' ? 'data-ajuste-inventario="true"' : '' ) . ' data-ccusto="' . $data->CCUSTO . '" data-mes="' . $data->MES . '" data-ano="' . $data->ANO . '" href="#id-' . $data->ID . '">';
                                        } else {
                                            $t .= '<a class="popup-show ' . (( $data->TOTALIZA == 1 )? 'totaliza' : '') . '" id="' . $data->ID . '" href="#id-' . $data->ID . '">';
                                        }
                                        
                                        $t .= '<div class="valor">R$ ' . number_format($data->UTIL,  2, ',', '.') . '</div> <div class="perc ' . (($data->PERC_UTIL > 100) ? 'media-red' : '') . '">' . number_format($data->PERC_UTIL,  2, ',', '.') . '%</div></a>';
                                        
                                    } else {
                                        
                                        
                                        if ( $periodo->ANO == 99999999999999 ) {
                                            //if ( $data->TIPO != 'GGF' ) {
                                                if($data->PERC_UTIL > 100){
                                                    $t .= '<button type="button"  data-toggle="modal" data-target="#planacao-modal"  valor="' . number_format($data->PERC_UTIL,  2, ',', '.') . '" tela="130302" subvinculo="'.self::$ano.'" class="btn btn-danger btn-sm popup-show-plano-acao2" id="' . $conta->CCONTABIL . '" perfil="1" ccusto="'.$conta->CCUSTO.'" vinculo="'.$conta->CCONTABIL.'" controlen="225" descpa="'. $ccusto->CCUSTO_MASK.' - '.$ccusto->CCUSTO_DESCRICAO.' : '.$conta->CCONTABIL_DESCRICAO.'" oque="'.$conta->CCONTABIL_DESCRICAO.'" ><span class="glyphicon glyphicon-info-sign"></span> Plan. Ação</button>';  
                                                }else{
                                                    $t .= '<button type="button"  data-toggle="modal" data-target="#planacao-modal"  valor="' . number_format($data->PERC_UTIL,  2, ',', '.') . '" tela="130302" subvinculo="'.self::$ano.'" class="btn btn-primary btn-sm popup-show-plano-acao2" id="' . $conta->CCONTABIL . '" perfil="1" ccusto="'.$conta->CCUSTO.'" vinculo="'.$conta->CCONTABIL.'" controlen="225" descpa="'. $ccusto->CCUSTO_MASK.' - '.$ccusto->CCUSTO_DESCRICAO.' : '.$conta->CCONTABIL_DESCRICAO.'" oque="'.$conta->CCONTABIL_DESCRICAO.'" ><span class="glyphicon glyphicon-info-sign"></span> Plan. Ação</button>';    
                                                }   
                                            //}
                                        } else
                                        // Coluna de cotas
                                        if ( $periodo->ANO == 9999999999999 ) {
                                            $t .= '<div class="valor">R$ ' . number_format($data->UTIL,  2, ',', '.') . '</div> <div class="perc">' . (( $data->PERC_UTIL == '' ) ? '' : 'R$ ' . number_format($data->PERC_UTIL,  2, ',', '.')) . '</div>';
                                        } else {
                                            $t .= '<div class="valor">R$ ' . number_format($data->UTIL,  2, ',', '.') . '</div> <div class="perc ' . (($data->PERC_UTIL > 100) ? 'media-red' : '') . '">' . (( $data->PERC_UTIL == '' ) ? '' : number_format($data->PERC_UTIL,  2, ',', '.') . '%') . '</div>';
                                        }  
                                    }
                                }
                            }
                        }                        
                        $t .=   '</td>';
                    }                    
                    $t .=   '</tr>';
                }
            }
        }
        $t .=           '</body>';
        $t .=       '</table>';        
        $t .=       '</div>';        
        
        return $t;
    }
    
    private static function dataStruct($param = []) {
        
        $dados     = self::$cotas;
        $ccusto    = array();
        $ccontabil = array();
        $periodo   = array();

        /***********************************************************************
         *             Cria agrupamento por C. Custo e C. Contábil            * 
         ***********************************************************************/
        
        //Ordena por C.Custo e C.Contábil
        orderBy($dados, 'CCUSTO', SORT_STRING, 'CCONTABIL', SORT_STRING); 

        //Cria array agrupado
        $id_01 = -1;
        $id_02  = -1;
        
        set_time_limit(0);
        foreach ( $dados as $i => $item )
        {         
           
            //Insere Centro de Custo
            if ($id_01 <> $item->CCUSTO) {
                $id_01  = $item->CCUSTO;
                $a = $i;      
            }

            $ccusto[$a] = 
            (object) array(  
                'CCUSTO'            => $item->CCUSTO,
                'CCUSTO_MASK'       => $item->CCUSTO_MASK,
                'CCUSTO_DESCRICAO'  => $item->CCUSTO_DESCRICAO        
            ); 

            
            //Insere Contas
            if ($id_02 <> $item->CCONTABIL.$item->CCUSTO) {
                $id_02  = $item->CCONTABIL.$item->CCUSTO;
                $b = $i;     
            }

            $ccontabil[$b] = 
            (object) array(  
                'CCUSTO'             => $item->CCUSTO,
                'CCONTABIL'          => $item->CCONTABIL,
                'ID'                 => $item->ID,
                'CCONTABIL_MASK'     => $item->CCONTABIL_MASK,
                'CCONTABIL_DESCRICAO'=> $item->CCONTABIL_DESCRICAO             
            ); 
            
            
        }
        
        /***********************************************************************
         *                   Cria agrupamento por Ano / Mês                    * 
         ***********************************************************************/        
        
        //Ordena por Ano e Mês
        orderBy($dados, 'ANO', 'MES'); 
         
        //Cria agrupamento
        $id_03 = -1;        
        
        set_time_limit(0);
        foreach ( $dados as $i => $item )
        {  
            //Insere Períodos
            if ($id_03 <> $item->PERIODO_DESCRICAO) {
                $id_03  = $item->PERIODO_DESCRICAO;
                $c = $i;     
            }

            $periodo[$c] = 
            (object) array(  
                'ANO'               => $item->ANO,
                'MES'               => $item->MES,
                'PERIODO_DESCRICAO' => $item->PERIODO_DESCRICAO
            );               
        }    
        
        /***********************************************************************
         *                  Insere o Totalizador no C. Custo                   * 
         ***********************************************************************/ 
        array_push(
            $ccusto,
            (object) array(  
                'CCUSTO'            => '9999999999',
                'CCUSTO_MASK'       => '',
                'CCUSTO_DESCRICAO'  => 'TOTAL'              
            )
        );           
        
        
        /***********************************************************************
         *                  Insere o Totalizador do Faturamento                * 
         ***********************************************************************/ 
        if ( isset(self::$faturamentos) ) {
            array_push(
                $ccusto,
                (object) array(  
                    'CCUSTO'            => '99999999999',
                    'CCUSTO_MASK'       => '',
                    'CCUSTO_DESCRICAO'  => 'FATURAMENTO'              
                )
            );      
        }
        
        /***********************************************************************
         *                       Insere o Totalizador Geral                    * 
         ***********************************************************************/ 
        array_push(
            $periodo,
            (object) array(  
                'ANO'               => '99999999999',
                'MES'               => '99999999999',
                'PERIODO_DESCRICAO' => 'TOTAL'
            )
        );       
        
        
        /***********************************************************************
         *                         Insere a Média Geral                        * 
         ***********************************************************************/ 
        array_push(
            $periodo,
            (object) array(  
                'ANO'               => '999999999999',
                'MES'               => '999999999999',
                'PERIODO_DESCRICAO' => 'MÉDIA'
            )
        );      
        
        /***********************************************************************
         *                     Insere o Totalizador de Cotas                   * 
         ***********************************************************************/ 
        array_push(
            $periodo,
            (object) array(  
                'ANO'               => '9999999999999',
                'MES'               => '9999999999999',
                'PERIODO_DESCRICAO' => 'COTA TOTAL/MÉDIA'
            )
        );
        
        array_push(
            $periodo,
            (object) array(  
                'ANO'               => '99999999999999',
                'MES'               => '99999999999999',
                'PERIODO_DESCRICAO' => 'PLANO DE AÇÃO'
            )
        );
        
        return (object) array (
            'CCUSTO'    => $ccusto,
            'CCONTABIL' => $ccontabil,
            'PERIODO'   => $periodo
        );
    }
    
    private static function dataCalc($param = [])
    {
        $dados   = self::$cotas;
        $fat     = self::$faturamentos;
        $periodo = self::$periodos;
        
        $ccontabil = array();
        $ccusto   = array(); 
        
        //Ordena por C.Custo e C.Contábil
        orderBy($dados, 'CCUSTO', SORT_STRING, 'CCONTABIL', SORT_STRING); 
         
        //Calcula as C. Contábil
        $a_valor   = 0;
        $a_extra   = 0;
        $a_total   = 0;
        $a_outros  = 0;
        $a_util    = 0;
        $a_saldo   = 0;          
        $id_01     = -1;   
        
        set_time_limit(0);
        foreach ( $dados as $i => $item )
        {                     

            // Verifica se é uma conta de GGF/GGA
//            if ( trim($item->CCONTABIL) <> '99999999999999' ) {
                //Valores
                $a_valor  = $a_valor  + $item->VALOR ;
                $a_extra  = $a_extra  + $item->EXTRA ;
                $a_total  = $a_total  + $item->TOTAL ;
                $a_outros = $a_outros + $item->OUTROS;
                $a_util   = $a_util   + $item->UTIL  ;
                $a_saldo  = $a_saldo  + $item->SALDO ;            
//            }
            
            //Insere Datas
            if ($id_01 <> $item->CCONTABIL.$item->CCUSTO.$item->PERIODO_DESCRICAO) {
                $id_01  = $item->CCONTABIL.$item->CCUSTO.$item->PERIODO_DESCRICAO;
                $a = $i;   
                
                $a_valor  = $item->VALOR ;
                $a_extra  = $item->EXTRA ;
                $a_total  = $item->TOTAL ;
                $a_outros = $item->OUTROS;
                $a_util   = $item->UTIL  ;
                $a_saldo  = $item->SALDO ;                 
            }

            $ccontabil[$a] = 
            (object) array(  
                'TIPO'              => $item->TIPO,
                'ID'                => $item->ID,
                'CCUSTO'            => $item->CCUSTO,
                'CCONTABIL'         => $item->CCONTABIL,
                'ANO'               => $item->ANO,
                'MES'               => $item->MES,
                'PERIODO_DESCRICAO' => $item->PERIODO_DESCRICAO,
                'VALOR'             => $a_valor,
                'EXTRA'             => $a_extra,
                'TOTAL'             => $a_total,
                'OUTROS'            => $a_outros,
                'UTIL'              => $a_util + $a_outros,
                'SALDO'             => $a_saldo,
                'PERC_UTIL'         => $a_valor > 0 ? (($a_outros+$a_util)/$a_valor)*100 : (($a_valor == 0) && (($a_outros+$a_util) < 0) ? 100 : 0 ),
                'TOTALIZA'          => $item->TOTALIZA
            );    
        }       

        //Ordena por Ano e Mês
        orderBy($dados, 'ANO', 'MES', 'CCUSTO', SORT_STRING);        
        
        $b_valor  = 0;
        $b_extra  = 0;
        $b_total  = 0;
        $b_outros = 0;
        $b_util   = 0;
        $b_saldo  = 0;     
        $id_02    = -1;  
        
        set_time_limit(0);
        foreach ( $dados as $i => $item )
        {          
            
            // Verifica se é uma conta de GGF/GGA
            if ( trim($item->CCONTABIL) <> '99999999999999' ) {
                //Valores
                $b_valor  = $b_valor  + $item->VALOR ;
                $b_extra  = $b_extra  + $item->EXTRA ;
                $b_total  = $b_total  + $item->TOTAL ;
                $b_outros = $b_outros + $item->OUTROS;
                $b_util   = $b_util   + $item->UTIL  ;
                $b_saldo  = $b_saldo  + $item->SALDO ;         
            }
            
            //Insere Datas
            if ($id_02 <> $item->CCUSTO.$item->PERIODO_DESCRICAO) {
                $id_02  = $item->CCUSTO.$item->PERIODO_DESCRICAO;
                $d = $i;   
                
                $b_valor  = $item->VALOR ;
                $b_extra  = $item->EXTRA ;
                $b_total  = $item->TOTAL ;
                $b_outros = $item->OUTROS;
                $b_util   = $item->UTIL  ;
                $b_saldo  = $item->SALDO ;                 
            }

            $ccusto[$d] = 
            (object) array(  
                'CCUSTO'            => $item->CCUSTO,
                'ANO'               => $item->ANO,
                'MES'               => $item->MES,
                'VALOR'             => $b_valor ,
                'EXTRA'             => $b_extra ,
                'TOTAL'             => $b_total ,
                'OUTROS'            => $b_outros ,
                'UTIL'              => $b_util + $b_outros,
                'SALDO'             => $b_saldo ,
                'PERC_UTIL'         => $b_valor > 0 ? (($b_outros+$b_util)/$b_valor)*100 : (($b_valor == 0) && (($b_outros+$b_util) < 0) ? 100 : 0 )
            );               
        }        
        
        $ccusto = _13030Dre::pushTotalizador(
            (object) array (
                'DADOS'   => $dados,
                'ARRAY'   => $ccusto,
                'PERIODO' => $periodo
            )
        );   
        
        
        if ( isset($fat) ) {
            $ccusto = _13030Dre::pushFaturamento(
                (object) array (
                    'DADOS'   => $fat,
                    'ARRAY'   => $ccusto,
                    'PERIODO' => $periodo
                )
            );    
        }
        
        $total = _13030Dre::pushTotalGeral( (object) array (
            'DADOS'     => $dados,
            'CCUSTO'    => $ccusto,
            'CCONTABIL' => $ccontabil,
            'PERIODO'   => $periodo
        ));    
        
        return (object) array(
            'CCUSTO'    => $total->CCUSTO,
            'CCONTABIL' => $total->CCONTABIL
        );
    }
    
    private static function pushTotalizador($param = []) {
        
        $dados   = $param->DADOS;
        $array   = $param->ARRAY;
        $periodo = $param->PERIODO;
        
        
        $tot_valor   = 0;
        $tot_extra   = 0;
        $tot_total   = 0;
        $tot_outros  = 0;
        $tot_util    = 0;
        $tot_saldo   = 0;         
        
        $num_valor   = 0;
        $num_extra   = 0;
        $num_total   = 0;
        $num_outros  = 0;
        $num_util    = 0;
        $num_saldo   = 0; 
        $id    = -1;
        
        
        set_time_limit(0);
        foreach ( $dados  as $item ) {
               
            if ($item->TOTALIZA == 1 || self::$cota_totaliza ) {
                $tot_valor  = $tot_valor  + $item->VALOR ;
                $tot_extra  = $tot_extra  + $item->EXTRA ;
                $tot_total  = $tot_total  + $item->TOTAL ;
                $tot_outros = $tot_outros + $item->OUTROS;
                $tot_util   = $tot_util   + $item->UTIL  ;
                $tot_saldo  = $tot_saldo  + $item->SALDO ;      

                $num_valor  = $num_valor  + $item->VALOR ;
                $num_extra  = $num_extra  + $item->EXTRA ;
                $num_total  = $num_total  + $item->TOTAL ;
                $num_outros = $num_outros + $item->OUTROS;
                $num_util   = $num_util   + $item->UTIL  ;
                $num_saldo  = $num_saldo  + $item->SALDO ;


                //Insere Datas
                if ($id <> $item->PERIODO_DESCRICAO) {
                    $id  = $item->PERIODO_DESCRICAO;
                    array_push($array, (object) array());
                    $a = max(array_keys($array));                 

                    $num_valor  = $item->VALOR ;
                    $num_extra  = $item->EXTRA ;
                    $num_total  = $item->TOTAL ;
                    $num_outros = $item->OUTROS;
                    $num_util   = $item->UTIL  ;
                    $num_saldo  = $item->SALDO ;  
                }
                $array[$a] = 
                (object) array(  
                    'CCUSTO'            => '9999999999',
                    'ANO'               => $item->ANO,
                    'MES'               => $item->MES,
                    'VALOR'             => $num_valor,
                    'EXTRA'             => $num_extra,
                    'TOTAL'             => $num_total,
                    'OUTROS'            => $num_outros,
                    'UTIL'              => $num_util + $num_outros,
                    'SALDO'             => $num_saldo,
    //                'PERC_UTIL'         => $num_valor+$num_extra > 0 ? ((1-($num_saldo/($num_valor+$num_extra)))*100) : (($num_valor+$num_extra = 0) && ($num_saldo < 0) ? 100 : 0 )
                    'PERC_UTIL'         => $num_valor > 0 ? (($num_outros+$num_util)/$num_valor)*100 : (($num_valor == 0) && (($num_outros+$num_util) < 0) ? 100 : 0 )
                );        
            }
        }
        

        $indice = 
        (object) array(  
                'CCUSTO'            => '9999999999',
                'ANO'               => '99999999999',
                'MES'               => '99999999999',
                'VALOR'             => $tot_valor,
                'EXTRA'             => $tot_extra,
                'TOTAL'             => $tot_total,
                'OUTROS'            => $tot_outros,
                'UTIL'              => $tot_util + $tot_outros,
                'SALDO'             => $tot_saldo,
//                'PERC_UTIL'         => $tot_valor+$tot_extra > 0 ? ((1-($tot_saldo/($tot_valor+$tot_extra)))*100) : (($tot_valor+$tot_extra = 0) && ($tot_saldo < 0) ? 100 : 0 )             
                'PERC_UTIL'         => $tot_valor > 0 ? (($tot_outros+$tot_util)/$tot_valor)*100 : (($tot_valor == 0) && (($tot_outros+$tot_util) < 0) ? 100 : 0 )          
        ); 
        array_push($array, $indice);         
        
        array_push($array, _13030Dre::pushMedia( (object) array (
            'PERIODO'   => $periodo,
            'TOTAL'     => $indice
        )));
        
        array_push($array, _13030Dre::pushCota( (object) [
            'PERIODO'   => $periodo,
            'TOTAL'     => $indice
        ]));

        
        return $array;
    }
    
    private static function pushFaturamento($param = [])
    {
        $dados   = $param->DADOS;
        $array   = $param->ARRAY;
        $periodo = $param->PERIODO;

        $total = 0;
        
        set_time_limit(0);
        foreach ( $dados as $fat ) {
            $total = $total + $fat->VALOR_TOTAL;

            $indice = 
            (object) array(  
                'CCUSTO'            => '99999999999',
                'ANO'               => $fat->ANO,
                'MES'               => $fat->MES,
                'VALOR'             => 0,
                'EXTRA'             => 0,
                'TOTAL'             => 0,
                'OUTROS'            => 0,
                'UTIL'              => ($fat->VALOR_TOTAL+$fat->VALOR_TOTAL_EXTRA)-$fat->VALOR_TOTAL_DEV,
                'SALDO'             => 0,
                'PERC_UTIL'         => ''                 
            ); 
            array_push($array, $indice);                  
        }
        
        $indice = 
        (object) array(  
            'CCUSTO'            => '99999999999',
            'ANO'               => '99999999999',
            'MES'               => '99999999999',
            'VALOR'             => 0,
            'EXTRA'             => 0,
            'TOTAL'             => 0,
            'OUTROS'            => 0,
            'UTIL'              => $total,
            'SALDO'             => 0,
            'PERC_UTIL'         => ''                 
        ); 
        array_push($array, $indice);         
        
        array_push($array, _13030Dre::pushMedia( (object) array (
            'PERIODO'   => $periodo,
            'TOTAL'     => $indice
        )));                 
        
        return $array;
    }
    
    private static function pushTotalGeral($param = [])
    {
        $dados     = $param->DADOS;
        $ccusto    = $param->CCUSTO;
        $ccontabil = $param->CCONTABIL;
        $periodo   = $param->PERIODO;
        
        //Ordena por Ano e Mês
        orderBy($dados, 'CCUSTO', SORT_STRING, 'CCONTABIL', SORT_STRING); 
        
        
        //Calcula as C. Contábil
        $a_valor   = 0;
        $a_extra   = 0;
        $a_total   = 0;
        $a_outros  = 0;
        $a_util    = 0;
        $a_saldo   = 0;          
        $id_01     = -1;     
        
        $b_valor  = 0;
        $b_extra  = 0;
        $b_total  = 0;
        $b_outros = 0;
        $b_util   = 0;
        $b_saldo  = 0;     
        $id_02    = -1;    
        
        set_time_limit(0);
        foreach ( $dados as $i => $item )
        {   
            /******************************************************
             *           Totalizador por C. Contábil              *
             ******************************************************/            
            
            //Valores
            $a_valor  = $a_valor  + $item->VALOR ;
            $a_extra  = $a_extra  + $item->EXTRA ;
            $a_total  = $a_total  + $item->TOTAL ;
            $a_outros = $a_outros + $item->OUTROS;
            $a_util   = $a_util   + $item->UTIL  ;
            $a_saldo  = $a_saldo  + $item->SALDO ;            
            
            //Insere Datas
            if ($id_01 <> $item->CCUSTO.$item->CCONTABIL) {
                $id_01  = $item->CCUSTO.$item->CCONTABIL;
                array_push($ccontabil, (object) array());
                $a = max(array_keys($ccontabil));            
                
                array_push($ccontabil, (object) array());
                $x = max(array_keys($ccontabil));              
                
                $a_valor  = $item->VALOR ;
                $a_extra  = $item->EXTRA ;
                $a_total  = $item->TOTAL ;
                $a_outros = $item->OUTROS;
                $a_util   = $item->UTIL  ;
                $a_saldo  = $item->SALDO ;     
            }

            $ccontabil[$a] = 
            (object) array(  
                'TIPO'              => $item->TIPO,
                'ID'                => '',
                'CCUSTO'            => $item->CCUSTO,
                'CCONTABIL'         => $item->CCONTABIL,
                'ANO'               => '99999999999',
                'MES'               => '99999999999',
                'PERIODO_DESCRICAO' => $item->PERIODO_DESCRICAO,
                'VALOR'             => $a_valor,
                'EXTRA'             => $a_extra,
                'TOTAL'             => $a_total,
                'OUTROS'            => $a_outros,
                'UTIL'              => $a_util + $a_outros,
                'SALDO'             => $a_saldo,
                'PERC_UTIL'         => $a_valor > 0 ? (($a_outros+$a_util)/$a_valor)*100 : (($a_valor == 0) && (($a_outros+$a_util) < 0) ? 100 : 0 )                            
            );   
                    
            $ccontabil[$x] = _13030Dre::pushMedia( (object) array (
                'PERIODO'   => $periodo,
                'TOTAL'     => $ccontabil[$a]
            ));  
            $ccontabil[$x+1] = _13030Dre::pushCota( (object) [
                'PERIODO'   => $periodo,
                'TOTAL'     => $ccontabil[$a]
            ]);
            
            $ccontabil[$x+2] = _13030Dre::pushPlanoAcao( (object) [
                'PERIODO'   => $periodo,
                'TOTAL'     => $ccontabil[$a]
            ]);
               
            /******************************************************
             *              Totalizador por C. Custo              *
             ******************************************************/
            
            // Verifica se é uma conta de GGF/GGA
            if ( trim($item->CCONTABIL) <> '99999999999999' ) {
                //Valores
                $b_valor  = $b_valor  + $item->VALOR ;
                $b_extra  = $b_extra  + $item->EXTRA ;
                $b_total  = $b_total  + $item->TOTAL ;
                $b_outros = $b_outros + $item->OUTROS;
                $b_util   = $b_util   + $item->UTIL  ;
                $b_saldo  = $b_saldo  + $item->SALDO ;    
            }
            
            //Insere Datas
            if ($id_02 <> $item->CCUSTO) {
                $id_02  = $item->CCUSTO;
                array_push($ccusto, (object) array());
                $d = max(array_keys($ccusto));               
                
                array_push($ccusto, (object) array());
                $y = max(array_keys($ccusto));       
                
                $b_valor  = $item->VALOR ;
                $b_extra  = $item->EXTRA ;
                $b_total  = $item->TOTAL ;
                $b_outros = $item->OUTROS;
                $b_util   = $item->UTIL  ;
                $b_saldo  = $item->SALDO ;        
            }

            $ccusto[$d] = 
            (object) array(  
                'CCUSTO'    => $item->CCUSTO,
                'ANO'       => '99999999999',
                'MES'       => '99999999999',
                'VALOR'     => $b_valor ,
                'EXTRA'     => $b_extra ,
                'TOTAL'     => $b_total ,
                'OUTROS'    => $b_outros ,
                'UTIL'      => $b_util + $b_outros,
                'SALDO'     => $b_saldo ,
                'PERC_UTIL' => $b_valor > 0 ? (($b_outros+$b_util)/$b_valor)*100 : (($b_valor == 0) && (($b_outros+$b_util) < 0) ? 100 : 0 )                                            
            );         
            
            $ccusto[$y] = _13030Dre::pushMedia( (object) array (
                'PERIODO'   => $periodo,
                'TOTAL'     => $ccusto[$d]
            )); 
            
            $ccusto[$y+1] = _13030Dre::pushCota( (object) [
                'PERIODO'   => $periodo,
                'TOTAL'     => $ccusto[$d]
            ]);        
 
        }
        
        return (object) array(
            'CCUSTO'    => $ccusto,
            'CCONTABIL' => $ccontabil
        );        
    }
    
    private static function pushMedia($param = []) {
        
        $periodos = $param->PERIODO;
        
        $count_med = 0;
        
        set_time_limit(0);
        foreach ( $periodos as $periodo ) {
            $data_item = date('Y.m.t', strtotime($periodo->ANO.'-'.$periodo->MES.'-01'));
            $data_serv = date('Y.m.t', time());
            
            if ( $data_item <= $data_serv && $periodo->ANO < 99999999 ) {
                $count_med++;
            }    
        }
        
        $util = ($count_med > 0) ? $param->TOTAL->UTIL / $count_med : 0;
        $perc = ($param->TOTAL->VALOR > 0) ? (($param->TOTAL->UTIL)/$param->TOTAL->VALOR)*100 : (($param->TOTAL->VALOR == 0) && (($param->TOTAL->OUTROS+$param->TOTAL->UTIL) < 0) ? 100 : 0 );
//        $perc = ($param->TOTAL->VALOR > 0) ? (($param->TOTAL->OUTROS+$param->TOTAL->UTIL)/$param->TOTAL->VALOR)*100 : (($param->TOTAL->VALOR == 0) && (($param->TOTAL->OUTROS+$param->TOTAL->UTIL) < 0) ? 100 : 0 );
                  
        
        $total = clone $param->TOTAL;
        $total->ANO       = '999999999999';
        $total->MES       = '999999999999';
        $total->VALOR     = 0;
        $total->EXTRA     = 0;
        $total->TOTAL     = 0;
        $total->OUTROS    = 0;
        $total->UTIL      = $util;
        $total->SALDO     = 0;
        $total->PERC_UTIL = $perc;   
        
        return $total;                 
    }
    
    private static function pushPlanoAcao($param = []) {
        
        $periodos = $param->PERIODO;
        
        $count_med = 0;
        
        set_time_limit(0);
        foreach ( $periodos as $periodo ) {
            $data_item = date('Y.m.t', strtotime($periodo->ANO.'-'.$periodo->MES.'-01'));
            $data_serv = date('Y.m.t', time());
            
            if ( $data_item <= $data_serv && $periodo->ANO < 99999999 ) {
                $count_med++;
            }    
        }
        
        $util = ($count_med > 0) ? $param->TOTAL->UTIL / $count_med : 0;
        $perc = ($param->TOTAL->VALOR > 0) ? (($param->TOTAL->UTIL)/$param->TOTAL->VALOR)*100 : (($param->TOTAL->VALOR == 0) && (($param->TOTAL->OUTROS+$param->TOTAL->UTIL) < 0) ? 100 : 0 );
//        
        $total = clone $param->TOTAL;
        $total->ANO       = '99999999999999';
        $total->MES       = '99999999999999';
        $total->VALOR     = 0;
        $total->EXTRA     = 0;
        $total->TOTAL     = 0;
        $total->OUTROS    = 0;
        $total->UTIL      = 0;
        $total->SALDO     = 0;
        $total->PERC_UTIL = $perc;   
        
        return $total;                 
    }

    
    private static function pushCota($param = []) {
        
        $periodos = $param->PERIODO;
        
        $count_med = 0;
        
        set_time_limit(0);
        foreach ( $periodos as $periodo ) {
            $data_item = date('Y.m.t', strtotime($periodo->ANO.'-'.$periodo->MES.'-01'));
            $data_serv = date('Y.m.t', time());
            
            if ( $data_item <= $data_serv && $periodo->ANO < 99999999 ) {
                $count_med++;
            }    
        }
        
        $util = $param->TOTAL->VALOR;
        $perc = ($count_med > 0) ? $param->TOTAL->VALOR / $count_med : 0;
        
        $total = clone $param->TOTAL;
        $total->ANO       = '9999999999999';
        $total->MES       = '9999999999999';
        $total->VALOR     = 0;
        $total->EXTRA     = 0;
        $total->TOTAL     = 0;
        $total->OUTROS    = 0;
        $total->UTIL      = $util;
        $total->SALDO     = 0;
        $total->PERC_UTIL = $perc;   
        
        return $total;                   
    }    
}
