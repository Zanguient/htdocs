<?php

namespace App\Http\Controllers\Ppcp;

use App\Http\Controllers\Controller;
use App\Models\DTO\Admin\_11000;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Pessoal\_23010;
use App\Models\DTO\Ppcp\_22010;
use App\Models\DTO\Ppcp\_22040;
use App\Models\DTO\Admin\_11050;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Exception;
use App;
use Illuminate\Support\Facades\View;
use App\Models\DTO\Helper\Historico;
use PDF;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Ppcp\_22130;

class _22010Controller extends Controller
{
    /**
     * Código do menu Registro de Produção de Blocos Laminados / Torneados
     * @var int 
     */
    private $menu = 'ppcp/_22020';
    
    
    public function consultaJustificativa(Request $request)
    {        
        $justificativa = _22130::consultaJustificativa('E');
        
        return $justificativa;
    }

    public function index(Request $request)
    {        
//        if ( env('REGISTRO_PRODUCAO',0) == 0 || sizeof(userMenu('ppcp/_22010',0,null,false)) == 0 ) {
//
//            return redirect('/_22020');
//        } else {
        
            $permissaoMenu	= userMenu($this->menu);

            $turno = _23010::listarSelect([
                'FIRST'          => 1,
                'TURNO_CORRENTE' => 1
            ]);

            if (!isset($turno[0])) {
                log_erro('Data de produção inválida!');
            } else {
                $data_producao = $turno[0]->DATA_PRODUCAO;
            }

            return view(
                'ppcp._22010.index', [
                'permissaoMenu'			=> $permissaoMenu,
                'menu'					=> $this->menu,
                'taloes_produzir'		=> [],
                'taloes_produzidos'		=> [],
                'data_producao'         => $data_producao,
                'ver_pares'				=> 0
            ]);
//        }
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
		$permissaoMenu  = _11010::permissaoMenu($this->menu);
        
        if ( strripos($request->url(), 'show') ) {       
            $view = 'ppcp._22010.show.body';
        } else {
            $view = 'ppcp._22010.show';
        }
        
		return view(
            $view, [
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
        // TO-DO
    }

    /**
     * Totalizador de produção
     * @param Request $request
     */
    public function talaoTotalizador(Request $request)
    {
        $res = _22040::listar([
            'RETORNO'            => ['TALAO'],
            'ESTABELECIMENTO_ID' => $request->estabelecimento_id,
            'GP_ID'              => $request->gp_id,
            'UP_ID'              => $request->up_id,
            'ESTACAO'            => $request->estacao,
            'STATUS'             => [1] // Status 1 = Em Aberto
        ]);
        
        if ( !empty($request->retorno) && in_array('VIEW', $request->retorno) ) {
            $ret = view('ppcp._22010.index.talao-produzir',[
                'menu'   => $this->menu,
                'taloes_produzir' => $res->TALAO
            ]);
        } else
        if ( $request->ajax() )
        {
            $ret = Response::json($res);
        } else {
            $ret = $res;
        }        
		
        return $ret;
    }    
    
    /**
     * Lista todos os talões a serem produzidos
     * @param Request $request
     */
    public function talaoProduzir(Request $request, $json = false)
    {
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
		
        $res = _22040::listar($param);
		
        if ( !empty($request->retorno) && in_array('VIEW', $request->retorno) ) {
            
            $pu212	= _11010::controle(212);
            $pu213	= _11010::controle(213);
            
            $ret = view('ppcp._22010.index.talao-produzir',[
                'menu'					=> $this->menu,
				'ver_pares'				=> $request->ver_pares,
				'ver_up_todos'			=> $request->up_todos,
                'taloes_produzir'		=> orderBy($res->TALAO, 'PROGRAMACAO_DATA', 'DATAHORA_INICIO', 'REMESSA_ID')
            ]);
        } else
        if ( $request->ajax() || $json )
        {
            $ret = Response::json($res);
        } else {
            $ret = $res;
        }        
        
        return $ret;
    }
    
    /**
     * Lista todos os talões produzidos
     * @param Request $request
     */
    public function talaoProduzido(Request $request)
    {
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
		
        $res = _22040::listar($param);
        orderBy($res->TALAO,'DATAHORA_REALIZADO_INICIO',SORT_DESC);
                        
        //Se no array $request->retorno existir o campo 'VIEW', será retornado uma view
        if ( !empty($request->retorno) && in_array('VIEW', $request->retorno) ) {
            $ret = view('ppcp._22010.index.talao-produzido',[
                'menu'					=> $this->menu,
                'taloes_produzidos'		=> $res->TALAO,
				'ver_pares'				=> $request->ver_pares,
				'ver_up_todos'			=> $request->up_todos
            ]);
        } else
        if ( $request->ajax() )
        {
            $ret = Response::json($res);
        } else {
            $ret = $res;
        }
        
        return $ret;
    }
	
	/**
	 * Consultar a composição do talão.
	 * 
	 * @param Request $request
	 * @return array
	 */
	public function consultarTalaoComposicao(Request $request) {
		
		return [
			'DETALHE'	=> $this->talaoDetalhe($request), 
			'HISTORICO'	=> $this->talaoHistorico($request), 
			'MATERIA'	=> $this->talaoMateriaPrima($request), 
			'DEFEITO'	=> $this->talaoDefeito($request)
		];
		
	}
    
    /**
     * Lista talões detalhados de um talão acumulado
     * @param Request $request
     */
    public function talaoDetalhe(Request $request)
    {
        $res = _22040::listar([
            'RETORNO'				=> ['TALAO_DETALHE'], 
            'REMESSA_ID'			=> $request->remessa_id,
            'REMESSA_TALAO_ID'		=> $request->remessa_talao_id,
			'APROVEITAMENTO_STATUS' => $request->status
        ]);
		
		$p203 = _11010::controle(203);
		$p211 = _11010::controle(211);
			
        //Se no array $request->retorno existir o campo 'VIEW', será retornado uma view
        if ( !empty($request->retorno) && in_array('VIEW', $request->retorno) ) {
			            
			$ret = View::make('ppcp._22010.index.talao-detalhe')
						->with('menu', $this->menu)
						->with('taloes_detalhe', $res->TALAO_DETALHE)
						->with('p203', $p203)
						->with('p211', $p211)
						->render();
			
        } else
        if ( $request->ajax() )
        {
            $ret = Response::json($res);
        } else {
            $ret = $res;
        }
        
        return $ret;
    }
    
    /**
     * Lista o histórico das ações realizadas em um talão
     * @param Request $request
     */
    public function talaoHistorico(Request $request)
    {
        $res = _22040::remessaProgramacao([
            'RETORNO'        => ['HISTORICO'],
            'PROGRAMACAO_ID' => $request->programacao_id
        ]);
        
        //Se no array $request->retorno existir o campo 'VIEW', será retornado uma view
        if ( !empty($request->retorno) && in_array('VIEW', $request->retorno) ) {
			
            $historico = orderBy($res->HISTORICO,'ID', SORT_DESC);
            $ret = View::make('ppcp._22010.index.talao-historico')
						->with('menu', $this->menu)
						->with('taloes_historico', $historico)
						->render();
			
        } else
        if ( $request->ajax() )
        {
            $ret = Response::json($res);
        } else {
            $ret = $res;
        }

        return $ret;
    }
    
    /**
     * Alterar sobra matéria-prima
     * @param Request $request
     */
    public function alterarQtdSobraMaterial(Request $request){
        return _22010::alterarQtdSobraMaterial(['CONSUMO_ID' => $request->consumo_id, 'SOBRA' => $request->sobra]);
    }


    /**
     * Lista a matéria-prima de um talão.
     * @param Request $request
     */
    public function talaoMateriaPrima(Request $request)
    {
        $taloes_materia_prima = orderBy(_22010::materiaPrima([
            'RETORNO'			=> ['MATERIA_PRIMA'],
            'REMESSA_ID'		=> $request->remessa_id,
            'REMESSA_TALAO_ID'	=> $request->remessa_talao_id,
			'STATUS'			=> $request->status
        ])->MATERIA_PRIMA, 'FAMILIA_ID', 'CONSUMO_ID');   
                
        $taloes_vinculo = _22010::projecaoVinculo([
            'RETORNO'  => ['TALAO_VINCULO'],
            'TALAO_ID' => $request->id,
        ])->TALAO_VINCULO;
		
		//consulta peças disponíveis
		$pecas_disponiveis = $this->pecaDisponivel($request->ver_peca_disponivel, $taloes_materia_prima);		
		
		$p209 = _11010::controle(209);
		$p210 = _11010::controle(210);
                
        //Se no array $request->retorno existir o campo 'VIEW', será retornado uma view
        if ( !empty($request->retorno) && in_array('VIEW', $request->retorno) ) {
			
            $ret = 
            View::make('ppcp._22010.index.talao-materia'				)
                ->with('menu'					, $this->menu			)
                ->with('taloes_materia_prima'	, $taloes_materia_prima	)
                ->with('taloes_vinculo'			, $taloes_vinculo		)
                ->with('ver_peca_disponivel'	, $request->ver_peca_disponivel)
                ->with('pecas_disponiveis'		, $pecas_disponiveis	)
				->with('p209'					, $p209					)
				->with('p210'					, $p210					)
				->with('status_talao'			, $request->status		)
                ->render()
            ;
        }

        return $ret;
    }
	
	/**
	 * Consultar peças disponíveis para o consumo do talão.
	 * @param char $ver_peca_disponivel
	 * @param array $taloes_materia_prima
	 * @return array
	 */
	public function pecaDisponivel($ver_peca_disponivel, $taloes_materia_prima) {
		
		$pecas_disponiveis = [];
		
		//Verificar se o GP tem permissão de ver as peças disponíveis da matéria-prima
		if ( $ver_peca_disponivel === '1' ) {
			
			foreach ($taloes_materia_prima as $t) {
									
				//apenas matéria-prima
				if( trim($t->COMPONENTE) == '0') {

					//consultar as peças disponíveis para o produto
					array_push(
						$pecas_disponiveis, 
						(object)_22010::consultarPecaDisponivel([
							'RETORNO'		=> ['PECA_DISPONIVEL'],
							'PRODUTO_ID'	=> $t->PRODUTO_ID,
							'QUANTIDADE'	=> $t->QUANTIDADE
						])->PECA_DISPONIVEL
					);

				}				
				
			}
			
		}
		
		return $pecas_disponiveis;
		
	}
    
    /**
     * Lista os defeitos de um talão
     * @param Request $request
     */
    public function talaoDefeito(Request $request)
    {
        $res = _22040::remessaDefeito([
            'RETORNO'          => ['DEFEITO'],
            'REMESSA_ID'       => $request->remessa_id,
            'REMESSA_TALAO_ID' => $request->remessa_talao_id
        ]);

        //Se no array $request->retorno existir o campo 'VIEW', será retornado uma view
        if ( !empty($request->retorno) && in_array('VIEW', $request->retorno) ) {
			
            $ret = View::make('ppcp._22010.index.talao-defeito')
						->with('menu', $this->menu)
						->with('taloes_defeito', $res->DEFEITO)
						->render();
			
        } else
        if ( $request->ajax() )
        {
            $ret = Response::json($res);
        } else {
            $ret = $res;
        }
        
        return $ret;
	}
    
    /**
     * Verifica se um talão é válido
     * @param Request $request
     */
    public function talaoValido(Request $request)
    {
        $talao = _22040::listar([
            'RETORNO'            => ['TALAO'],
            'ESTABELECIMENTO_ID' => $request->estabelecimento_id,
            'REMESSA_ID'         => $request->remessa_id,
            'REMESSA_TALAO_ID'   => $request->remessa_talao_id,
            'TALAO_ID'           => $request->talao_id,
            'STATUS'             => $request->status_talao,
            'PROGRAMACAO_STATUS' => $request->status_programacao,
            'GP_ID'              => $request->gp_id,
            'UP_ID'              => $request->up_id,
//            'ESTACAO'            => $request->estacao //Verificação por estação desativada  
        ])->TALAO;
        
        if ( !isset($talao[0]) ) {
            log_erro('Talão inválido/inexistente.');
        }
        
        $res = $talao;
        
        if ( $request->ajax() )
        {
            $ret = Response::json($res);
        } else {
            $ret = $res;
        }
        
        return $ret;
    }
	
	/**
	 * Verifica se a Estação está ativa (em produção).
	 * @param Request $request
	 * @return array
	 */
	public function verificarEstacaoAtiva(Request $request) {
		
		$res = _22010::verificarEstacaoAtiva([
			'UP_ID'		 => $request->up_id,
			'ESTACAO_ID' => $request->estacao_id
		]);
		
		if ( $request->ajax() ) {			
            $ret = Response::json($res);
        } 
		else {			
            $ret = $res;
        }
        
        return $ret;
	}
    
    public function acao(Request $request, $tipo)
    {   
        
        if ( $tipo == 'iniciar' )
        {
            $param = [
                'PROGRAMACAO_HISTORICO_STATUS' => 0, // 0 - INICIADO/REINICIADO
                'PROGRAMACAO_STATUS'           => 2, // 2 - EM ANDAMENTO
                'REMESSA_TALAO_DETALHE_STATUS' => 2, // 2 - EM PRODUÇÃO
                'ESTACAO_TALAO_ID'             => $request->talao_id,
            ];            
        } else 
        if ( $tipo == 'pausar' )
        {
            $param = [
                'PROGRAMACAO_HISTORICO_STATUS' => 1, // 1 - PARADA TEMPORÁRIA
                'PROGRAMACAO_STATUS'           => 1, // 1 - INICIADO/PARADO
                'ESTACAO_TALAO_ID'             => 0,  // 0 - LIBERA A ESTAÇÃO
                'REMESSA_TALAO_DETALHE_STATUS' => 1, // 1 - EM ABERTO
            ];    
        } else 
        if ( $tipo == 'finalizar' )
        {
            $param = [
                'PROGRAMACAO_HISTORICO_STATUS' => 2, // 2 - FINALIZADO
                'PROGRAMACAO_STATUS'           => 3, // 3 - FINALIZADO
                'REMESSA_TALAO_STATUS'         => 2, // 2 - PRODUZIDO
                'REMESSA_TALAO_DETALHE_STATUS' => 3, // 3 - PRODUZIDO
                'ESTACAO_TALAO_ID'             => 0, // 0 - LIBERA A ESTAÇÃO
            ];     
        }
        
        $param = $param + $request->all();
        
        _22010::registraAcao($param);  
        log_info('O OPERADOR ' . $request->operador_id . ' REALIZOU A AÇÃO "' . strtoupper($tipo) . '" NA REMESSA_ID/TALÃO: [' . $request->remessa_id . '/' . $request->remessa_talao_id . ']',  $this->menu);

    }
	
	public function registrarMateriaPrima(Request $request)
    {       
        $cod_barras = strtoupper($request->codigo_barras);
        
        log_info('Registrando materia-prima. Código de Barras: ' . $cod_barras,  $this->menu);
        
        /**
         * Verifica se a quantidade de caracteres do código de barras é diferente de 13
         */
        if ( strlen($cod_barras) != 13 && strlen($cod_barras) != 12 ) {
            log_erro('Código de barras de materia prima inválido. Código: ' . $cod_barras);
        }
        /**
         * Verifica para onde será direcionado a consulta
         * 'RD' = [R=REMESSA; D=DETALHE]   Id do talão Detalhado 
         * 'P'  = [P=PESAGEM]              Id da Pesagem 
         */
        else 
        if ( strstr($cod_barras, 'RD') ) {
            $id = (float) str_replace('RD', '', $cod_barras);
            $tipo = 'D'; // Registra vínculo a partir do talão detalhado
            
            $res = _22040::listar([
                'RETORNO'                  => ['TALAO_DETALHE'],
                'REMESSA_TALAO_DETALHE_ID' => $id,
                'STATUS'                   => [3], // 3 - Produzido
				//'QUANTIDADE_SALDO'		   => '> 0'
            ])->TALAO_DETALHE;
        }
        else 
        if ( strstr($cod_barras, 'P') ){
            $id = (float) str_replace('P', '', $cod_barras);
            $tipo = 'R'; // Registra vínculo a partir do registro da revisão
            
            $res = _22010::pesagem([
                'RETORNO' => ['PESAGEM'],
                'ID'      => $id,
				//'SALDO'   => '> 0'
            ])->PESAGEM;
        }
        else {
            log_erro('Tipo de código de barras de materia prima inválido. Código: ' . $cod_barras);
        }
        
        if ( !isset($res[0]) ) {
            log_erro('Registro não localizado. Código: ' . $cod_barras);
        } else {

            if ( $res[0]->PRODUTO_ID != $request->produto_id ) {
                log_erro('Produtos não conferem! Código: ' . $cod_barras . '. CONSUMO:' . $request->produto_id . ' INFORMADO:' . $res[0]->PRODUTO_ID);
            }
            if ( !( $res[0]->SALDO > 0 ) ) {
                $alocado = '';
                //verifica se tem o campo alocado, que contem outras alocações de uma peça
                if(isset($res[0]->ALOCADO)){
                    $alocado = $res[0]->ALOCADO;
                }
                 
                log_erro('Não há saldo disponível para esta PEÇA! Código: ' . $cod_barras . ' ' . $alocado);
            }

            if ( !( $res[0]->SALDO > 0 ) ) {
                $alocado = '';
                //verifica se tem o campo alocado, que contem outras alocações de uma peça
                if(isset($res[0]->ALOCADO)){
                    $alocado = $res[0]->ALOCADO;
                }
                 
                log_erro('Não há saldo disponível para esta PEÇA! Código: ' . $cod_barras . ' ' . $alocado);
            }

        }
        
        $param = [];
        foreach ($res as $item) {
            array_push($param,(object)[
                'TALAO_ID'        => $request->talao_id,
                'CONSUMO_ID'      => $request->consumo_id,
                'TIPO'            => $tipo,
                'ITEM_ESTOQUE_ID' => $id
            ]);
        }

        _22010::gravarVinculo($param);
    }
    
    public function registrarComponente(Request $request)
    {
        $cod_barras = $request->codigo_barras;
		
        log_info('Registrando componente. Código de Barras: ' . $cod_barras,  $this->menu);
        
        /**
         * Verifica se a quantidade de caracteres do código de barras é diferente de 13
         */
        if ( strlen($cod_barras) != 13 && strlen($cod_barras) != 12 ) {
            log_erro('Código de barras de componente inválido. Código: ' . $cod_barras);
        }
         
        /**
         * Verifica para onde será direcionado a consulta
         * 'RA' = [R=REMESSA; A=ACUMULADO] Id do talão Acumulado 
         */
        if ( strstr($cod_barras, 'RA') ) { 
            $id = (float) str_replace('RA', '', $cod_barras);
            $tipo = 'D'; // Registra vínculo a partir do talão detalhado

            $res = _22010::projecaoVinculo([
                'RETORNO'                 => ['VINCULO'],
                'REMESSA_ID_ORIGEM'       => $request->remessa_id,
                'REMESSA_TALAO_ID_ORIGEM' => $request->remessa_talao_id,
                'TALAO_ID_DESTINO'        => $id,
            ])->VINCULO;
        }
        else {
            log_erro('Tipo de código de barras de componente inválido. Código: ' . $cod_barras);
        }

        if ( !isset($res[0]) ) {
            log_erro('Registro não localizado, Talão '.((float) str_replace('RA', '', $cod_barras)).' não produzido, ou não vinculado a esta remessa.');
        }

        $param = [];

        //log_info($res);

        foreach ($res as $item) {
            
            if ( !( $item->QUANTIDADE_SALDO > 0 ) ) {
                log_erro('Não há saldo disponível para este item! Cód. Talão: ' . $item->REMESSA_TALAO_DETALHE_ID);
            }
            
            array_push($param,(object)[
                'TALAO_ID'          => $request->talao_id,
                'CONSUMO_ID'        => $item->CONSUMO_ID,
                'TIPO'              => $tipo,
                'ITEM_ESTOQUE_ID'   => $item->REMESSA_TALAO_DETALHE_ID,
                'QUANTIDADE_ALOCAR' => $item->QUANTIDADE_SALDO
            ]);
        }

        _22010::gravarVinculo($param);
    }
	
    public function registroPesagem(Request $request)
    {

        $cod_barras = $request->codigo_barras;
        
        /**
         * Verifica se a quantidade de caracteres do código de barras é diferente de 13
         */
        if ( strlen($cod_barras) != 13 && strlen($cod_barras) != 12  ) {
            log_erro('Código de barras inválido. Código: ' . $cod_barras);
        }
         
        /**
         * Verifica para onde será direcionado a consulta
         * 'P'  = [P=PESAGEM]              Id da Pesagem 
         */
        if ( strstr($cod_barras, 'P') ){
            $id = (float) str_replace('P', '', $cod_barras);
            $tipo = 'R'; // Registra vínculo a partir do registro da revisão
            
			if (isset($request->not_status)) {
				$res = _22010::pesagem([
					'RETORNO'   => ['PESAGEM'],
					'ID'        => $id,
					'SALDO'		=> '> 0'
				])->PESAGEM;
			}
			else {
				$res = _22010::pesagem([
					'RETORNO'      => ['PESAGEM'],
					'ID'           => $id
				])->PESAGEM;
			}
        }
        else {
            log_erro('Tipo de código de barras inválido. Código: ' . $cod_barras);
        }

        if ( !isset($res[0]) ) {
            log_erro('Registro não localizado. Código: ' . $cod_barras);
        }else{

            if ( ((trim($res[0]->RESULTADO) == 'P') && (trim($res[0]->STATUS_OB) == '2')) || ((trim($res[0]->RESULTADO) == 'I') || (trim($res[0]->RESULTADO) == 'R'))) {
  
            }else{
               $alocado = '';
               log_erro('Peça com pendência na OB ou liberação, Resultado:'.$res[0]->RESULTADO.' Status OB:'.$res[0]->STATUS_OB.' Código: ' . $cod_barras . ' ' . $alocado);
            }
        }       
              
        if ( $res[0]->PRODUTO_ID != $request->produto_id ) {
            log_erro('Produtos não conferem! Código: ' . $cod_barras . '. Cód. produto atual: ' . $res[0]->PRODUTO_ID . '. Informado: ' . $request->produto_id);
        } else
        if ( !( $res[0]->SALDO > 0 ) ) {
            log_erro('Não há saldo disponível para este item! Código: ' . $cod_barras);
        }
        
        if ( isset($request->peca_conjunto) ) {
            
            if ( $res[0]->CLASSIFICACAO != '' ) {
                $ob_class = _22010::pesagem([
                    'RETORNO'          => ['OB_CLASSIFICACAO'],
                    'REMESSA_ID'       => $request->remessa_id,
                    'REMESSA_TALAO_ID' => $request->remessa_talao_id,
                    'PRODUTO_ID'	   => $request->produto_id,
                    'PECA_CONJUNTO'    => $request->peca_conjunto,
                    'CLASSIFICACAO'    => $res[0]->CLASSIFICACAO
                ])->OB_CLASSIFICACAO;

                if ( isset($ob_class[0]) ) {
                    log_erro('Classificação da peça diferente das peças já registradas. Operação Cancelada!');
                }
            }
            
            $peca_conjunto = _22040::listar([
                'RETORNO'                       => ['TALAO_DETALHE'],
                'REMESSA_ID'                    => $request->remessa_id,
                'REMESSA_TALAO_ID'              => $request->remessa_talao_id,
                'REMESSA_TALAO_DETALHE_ID_NOT'  => $request->remessa_talao_detalhe_id,
                'PECA_CONJUNTO'                 => $request->peca_conjunto,
                'PRODUTO_ID'                    => $request->produto_id
            ])->TALAO_DETALHE;

            if ( isset($peca_conjunto[0]) ) {
                $res[0]->PECA_CONJUNTO = $peca_conjunto[0];
            }
        }
            
        return Response::json($res[0]);
    }
    
	public function baixarQuantidadeProduzida(Request $request) {

        try{
            $qtd       = $request->QUANTIDADE;
            $qtd_1     = $qtd;
            $qtd_2     = 0;
            $qtd_alt_1 = is_numeric($request->QUANTIDADE_ALTERNATIVA) ? $request->QUANTIDADE_ALTERNATIVA : 0;
            $qtd_alt_2 = 0;

            if ( isset($request->TALAO_DETALHE_ID_2) ) {

                $qtd_alt_2 = is_numeric($request->QUANTIDADE_ALTERNATIVA_2) ? $request->QUANTIDADE_ALTERNATIVA_2 : 0;
            
                $qtd_alt_soma = $qtd_alt_1 + $qtd_alt_2;
                
                $qtd_perc_1 = $qtd_alt_1 / $qtd_alt_soma;
                $qtd_perc_2 = $qtd_alt_2 / $qtd_alt_soma;
                
                $qtd_1 = $qtd_perc_1 * $qtd;
                $qtd_2 = $qtd_perc_2 * $qtd;
            }
            
            if ( !(($qtd_1+$qtd_2) > 0) ) {
                log_erro('Quantidade informada inválida!');
            }
            
            $con = new _Conexao();

            if ( $qtd_1 > 0 ) {
                
        		_22010::baixarQuantidadeProduzida([
        			'SOMAR_QUANTIDADE'			=> true,
        			'RETORNO'					=> $request->RETORNO,
        			'TALAO_ID'					=> $request->TALAO_ID,
        			'TALAO_DETALHE_ID'			=> $request->TALAO_DETALHE_ID,
        			'CONSUMO_ID'				=> $request->CONSUMO_ID,
        			'TIPO'						=> $request->TIPO,
        			'TABELA_ID'					=> $request->TABELA_ID,
        			'PRODUTO_ID'				=> $request->PRODUTO_ID,
        			'TAMANHO'					=> $request->TAMANHO,
        			'QUANTIDADE'				=> $qtd_1,
        			'QUANTIDADE_ALTERNATIVA'	=> $qtd_alt_1,
        			'REMESSA_ID'				=> $request->REMESSA_ID,
        			'REMESSA_TALAO_ID'			=> $request->REMESSA_TALAO_ID
        		],$con);

                //_22010::toleranciaTecido(['TALAO_DETALHE_ID' => $request->TALAO_DETALHE_ID], $con);
            }
            
            if ( $qtd_2 > 0 ) {
                
                _22010::baixarQuantidadeProduzida([
                    'SOMAR_QUANTIDADE'			=> true,
                    'RETORNO'					=> $request->RETORNO,
                    'TALAO_ID'					=> $request->TALAO_ID,
                    'TALAO_DETALHE_ID'			=> $request->TALAO_DETALHE_ID_2,
                    'CONSUMO_ID'				=> $request->CONSUMO_ID_2,
                    'TIPO'						=> $request->TIPO,
                    'TABELA_ID'					=> $request->TABELA_ID,
                    'PRODUTO_ID'				=> $request->PRODUTO_ID,
                    'TAMANHO'					=> $request->TAMANHO,
                    'QUANTIDADE'				=> $qtd_2,
                    'QUANTIDADE_ALTERNATIVA'	=> $qtd_alt_2,
                    'REMESSA_ID'				=> $request->REMESSA_ID,
                    'REMESSA_TALAO_ID'			=> $request->REMESSA_TALAO_ID
                ], $con);

                //_22010::toleranciaTecido(['TALAO_DETALHE_ID' => $request->TALAO_DETALHE_ID], $con);
            }

            $etiqueta = '';
                   
            $sobra_tecido = _22010::itemTbRevisao2([
                'TABELA_ID'           => $request->TABELA_ID,
                'REMESSA_ID'          => $request->REMESSA_ID,
                'REMESSA_TALAO_ID'    => $request->REMESSA_TALAO_ID
            ]);

            $imp = _22010::validarImpressaoTecido([
                'PRODUTO_ID'         => $request->PRODUTO_ID,
                'REMESSA_ID'         => $request->REMESSA_ID,
                'REMESSA_TALAO_ID'   => $request->REMESSA_TALAO_ID
            ], $con);

            $tag = _22010Controller::randomName();

            $string = '';

            if (count($sobra_tecido) > 0 && $imp == 1) {
                $etiqueta = _11050::etiqueta(142);

                foreach ( $sobra_tecido as $sobra ) {

                    if($sobra->SALDO > ($request->QUANTIDADE + $sobra->UTILIZADO)){

                        $str_talao = $etiqueta;

                        $CODIGO2   = $sobra->ID;
                        $CODIGO    = 'P' . str_pad($sobra->ID , 12, "0", STR_PAD_LEFT);
                        $datanota  = date_format(date_create($sobra->DATA_NF),'d/m/y');

                        $str_talao = str_replace('#ETIQUETA#'  , $tag       , $str_talao);
                        $str_talao = str_replace('ETIQUETA'    , $tag       , $str_talao);
                        $str_talao = str_replace('#PRODUTO#'   , str_remove_acento($sobra->PRODUTO)   , $str_talao);
                        
                        $metragem = '';
                        
                        $k = floatval(number_format($request->PECA_QUANTIDADE - $sobra->TARA ,4,'.',','));
                        //$m = floatval(number_format($sobra->RENDIMENTO_CONSUMO               ,4,'.',','));

                        $qtd_alt = ($request->QUANTIDADE_ALTERNATIVA + $request->QUANTIDADE_ALTERNATIVA_2);
                        $m = ($qtd_alt / $request->QUANTIDADE);

                        $s = number_format($k  ,4,',','.' );
                        $r = number_format($m  ,4,',','.' );

                        if(($m > 0) and ($k > 0) ){
                            $metragem = number_format( $k * $m ,2,',','.');
                        }

                        $operador = $request->OPERADOR_ID.' - '.$request->OPERADOR_NOME;
                        
                        $str_talao = str_replace('#FORNECEDOR#'     , str_remove_acento($sobra->FORNECEDOR)     , $str_talao);
                        $str_talao = str_replace('#DATA_NF#'        , $datanota                                 , $str_talao);
                        $str_talao = str_replace('#OB#'             , $sobra->OB                                , $str_talao);
                        $str_talao = str_replace('#METRAGEM#'       , $metragem                                 , $str_talao);
                        $str_talao = str_replace('#PESO_LIQUIDO#'   , $s                                        , $str_talao);
                        $str_talao = str_replace('#CODIGO#'         , $CODIGO                                   , $str_talao);
                        $str_talao = str_replace('#CODIGO2#'        , $CODIGO2                                  , $str_talao);
                        $str_talao = str_replace('#RENDIMENTO#'     , $r                                        , $str_talao);
                        $str_talao = str_replace('#NF#'             , $sobra->NUMERO_NOTAFISCAL                 , $str_talao);
                        $str_talao = str_replace('#PRODUTO_ID#'     , $sobra->PRODUTO_ID                        , $str_talao);
                        $str_talao = str_replace('#ENDERECAMENTO#'  , $sobra->ENDERECAMENTO                     , $str_talao);
                        $str_talao = str_replace('#OPERADOR#'       , $operador                                 , $str_talao);
                        $str_talao = str_replace('#CLASSIFICACAO#'  , trim($sobra->CLASSIFICACAO)               , $str_talao);
                        $str_talao = str_replace('#DATA_HORA_IMP#'  , date('d/m/y H:i')                         , $str_talao);
                        $str_talao = str_replace('#LOCALIZACAO#'    , date('d/m/y H:i')                         , $str_talao);

                        $string = '' . $str_talao;
                    }
                }
            }

            
            if ( isset($request->REGISTRO_AUTOMATICO) && $request->REGISTRO_AUTOMATICO == '0' ) {
                Historico::setHistorico('TBREMESSA', $request->REMESSA_ID, 'PECA ' . $request->TIPO . '/' . $request->TABELA_ID . ' QTD: ' . $request->PECA_QUANTIDADE . ' BAIXADA MANUALMENTE PARA O TALAO :' . $request->REMESSA_TALAO_ID);
            }

            $con->commit();

            return ['ETIQUETA' => $string];

        } catch (Exception $e) {
            try{
                $con->rollback();
            } catch (Exception $j) {
            }

            throw $e;
        }
	}

	/**
	 * Alterar quantidade alocada da matéria-prima.
	 * @param Request $request
	 * @return array
	 */
	public function alterarQtdAlocada(Request $request) {
		
		$res = _22010::alterarQtdAlocada([
			'RETORNO'		=> $request->retorno,
			'QUANTIDADE'	=> $request->qtd,
			'CONSUMO_ID'	=> $request->consumo_id
		]);
		
		if ( $request->ajax() ) {			
            $ret = Response::json($res);
        } 
		else {			
            $ret = $res;
        }
        
        return $ret;
	}
	
	/**
	 * Alterar quantidade de produção ou a quantidade alternativa de produção do detalhe do talão.
	 * @param Request $request
	 * @return array
	 */
	public function alterarQtdTalaoDetalhe(Request $request) {
		
		$res = _22010::alterarQtdTalaoDetalhe([
			'RETORNO'			=> $request->retorno,
			'QUANTIDADE'		=> $request->qtd,
            'SOBRA'				=> $request->sbr,
			'TALAO_DETALHE_ID'	=> $request->talao_detalhe_id,
            'REMESSA_ID'        => $request->REMESSA_ID,
            'REMESSA_TALAO_ID'	=> $request->REMESSA_TALAO_ID
		]);
		
		
		if ( $request->ajax() ) {			
            $ret = Response::json($res);
        } 
		else {			
            $ret = $res;
        }
        
        return $ret;
	}
	
	/**
	 * Alterar todas as quantidades de produção ou as quantidades alternativas de produção do detalhe do talão.
	 * @param Request $request
	 * @return array
	 */
	public function alterarTodasQtdTalaoDetalhe(Request $request) {
		
		$res = _22010::alterarTodasQtdTalaoDetalhe([
			'REMESSA_ID'		=> $request->REMESSA_ID,
			'REMESSA_TALAO_ID'	=> $request->REMESSA_TALAO_ID,
            'TIPO'              => $request->TIPO
		]);
        
		if ( $request->ajax() ) {			
            $ret = Response::json($res);
        } 
		else {			
            $ret = $res;
        }
        
        return $ret;
	}
    
    public static function randomName() {
        $str = "ABCDEFG";
        $codigo = str_shuffle($str);
        
        return $codigo;
    }
    
    /**
     * Crias a string para impressão das etiquetas de produção
     */
    public function etiqueta(Request $request)
    {     

        $reimpressao = 0;
        if($request->reimpressao){
            $reimpressao = $request->reimpressao;
        }

        $dados = $request->all();
                                
        $tag = _22010Controller::randomName();
        
        if (!($request->id > 0)) {
            log_erro('Talão inválido. Operação cancelada!');
        }
        if (!( $request->operador_id || $request->operador_descricao)) {
            log_erro('Operador inválido. Operação cancelada!');
        }
        
        $taloes_detalhado = _22040::listar([
            'RETORNO'            => ['TALAO_DETALHE'],
            'TALAO_ID'           => $request->id,
            'STATUS'             => 3,
        ])->TALAO_DETALHE;

        if (!isset($taloes_detalhado[0])) {
            log_erro('Talão não inexistente ou não produzido. Operação cancelada!');
        }

        $materia_prima = _22010::materiaPrimaSobra([
            'RETORNO'            => ['MATERIA_PRIMA'],
            'TALAO_ID'           => $request->id
        ]);
        
        $itens_materia_prima = _22010::itensmateriaPrima([
            'RETORNO'            => ['MATERIA_PRIMA'],
            'TALAO_ID'           => $request->id
        ]);

        $string   = '';
        
        if ( isset($request->retorno) && in_array('PRODUCAO', $request->retorno) ) {

            //////////////////////////////////////////////////////////////////////////////////////////////////////
            // Laminação


            if ( trim($taloes_detalhado[0]->GP_PERFIL) == 'L' || trim($taloes_detalhado[0]->GP_PERFIL) == 'C' || trim($taloes_detalhado[0]->GP_PERFIL) == 'I' )
            {
                $etiqueta = _11050::etiqueta(150); 
                
                //Etiqueta laminação
                foreach ( $taloes_detalhado as $talao ) {
                    if ( $talao->QUANTIDADE_PRODUCAO > 0 ) {

						//log_info($talao);

						$relaxamento          = number_format($talao->RELAXAMENTO,2,',','.');
				
						$str_talao = $etiqueta;
						
						$produto_descricao    = str_remove_acento($talao->PRODUTO_DESCRICAO);
						$quantidade_projetada = number_format($talao->QUANTIDADE,1,',','.') . ' ' . $talao->UM;
						$quantidade_produzida = number_format($talao->QUANTIDADE_PRODUCAO,1,',','.') . ' ' . $talao->UM;
						$datahora_producao    = date_format(date_create($talao->DATAHORA_PRODUCAO),'d/m/y H:i');
						$up_descricao         = str_remove_acento($talao->UP_DESCRICAO);
						$up_destino           = str_remove_acento($talao->UP_DESTINO);
						$impressao            = date('d/m/y H:i') . ' VIA:' . ($talao->VIA_ETIQUETA + 1);
						
						if ( trim($talao->REMESSA_COMPONENTE) == '1' ) {
							$codigo_barras        = 'RA' . str_pad($talao->TALAO_ID, 11, "0", STR_PAD_LEFT);
						} else {
							$codigo_barras        = 'RD' . str_pad($talao->ID, 11, "0", STR_PAD_LEFT);
						}
						
						$str_talao = str_replace('#ETIQUETA#' , $tag                                  , $str_talao);
						$str_talao = str_replace('ETQPROD' , $tag                         , $str_talao);
						
						$str_talao = str_replace('#REMESSA#'            , $talao->REMESSA             , $str_talao);                    
						$str_talao = str_replace('#TALAO_ID#'           , $talao->REMESSA_TALAO_ID    , $str_talao);                    
						$str_talao = str_replace('#ESPESSURA#'          , $talao->ESPESSURA           , $str_talao);                                 
						$str_talao = str_replace('#PRODUTO#'            , $produto_descricao          , $str_talao);                   
						$str_talao = str_replace('#DENSIDADE#'          , $talao->DENSIDADE           , $str_talao);                           
						$str_talao = str_replace('#QTD_PROJ#'           , $quantidade_projetada       , $str_talao);                              
						$str_talao = str_replace('#QTD_PROD#'           , $quantidade_produzida       , $str_talao);                              
						$str_talao = str_replace('#DATA_HORA_PRODUCAO#' , $datahora_producao          , $str_talao);                          
						$str_talao = str_replace('#OPERADOR#'           , $request->operador_descricao, $str_talao);                          
						$str_talao = str_replace('#TALAO_DETALHE_ID#'   , $talao->ID                  , $str_talao);                        
						$str_talao = str_replace('#UP#'                 , $up_descricao               , $str_talao);                                   
						$str_talao = str_replace('#COD_BARRAS#'         , $codigo_barras              , $str_talao);
						$str_talao = str_replace('#RELAXAMENTO#'        , $relaxamento                , $str_talao);
						$str_talao = str_replace('#DATA_HORA_IMPRESSAO#', $impressao                  , $str_talao); 
						$str_talao = str_replace('#LOCALIZACAO#'        ,str_remove_acento( $talao->LOCALIZACAO)        , $str_talao);                      
						
						$string = $string . $str_talao;
					}
                }
                
                //sobra
                if ( isset($request->retorno) && in_array('SOBRA', $request->retorno) ) {
                    //////////////////////////////////////////////////////////////////////////////////////
                    //sobra de materia aproveitamento
                    $cods = '';
                    foreach ( $itens_materia_prima as $materia ) {
                        foreach ($materia as $prima){
                            $t = $prima->COD;
                            if($t != ''){
                                if($cods == ''){
                                    $cods = $t;
                                }else{
                                    $cods = $t.','.$cods;
                                }
                            }
                        }
                    }

                    if($cods == ''){
                        $sobra_aproveitamento = [];
                    }else{
                        $sobra_aproveitamento = _22010::itemTbRevisao([
                            'TABELA_ID'           => $cods
                        ]);
                    } 

                    if (count($sobra_aproveitamento) > 0)
                    {
                        $etiqueta = _11050::etiqueta(158);

                        foreach ( $sobra_aproveitamento as $sobra ) {
                            
                            if($sobra->SALDO > 0){

                                $talao  = _22040::listar([
                                    'RETORNO'  => ['TALAO_DETALHE'],
                                    'REMESSA_TALAO_DETALHE_ID' => $sobra->TALAO_ID,
                                    'STATUS'             => 3,
                                ])->TALAO_DETALHE;
                               
                                
                                if (isset($talao[0])) {
                                    //aproveitamento de producao
                                    $talao = $talao[0];

                                    $str_talao = $etiqueta;

                                    $produto_descricao    = str_remove_acento($talao->PRODUTO_DESCRICAO);
                                    $quantidade_projetada = number_format($talao->QUANTIDADE,1,',','.') . ' ' . $talao->UM;
                                    $quantidade_produzida = number_format($talao->QUANTIDADE_PRODUCAO,1,',','.') . ' ' . $talao->UM;
                                    $datahora_producao    = date_format(date_create($talao->DATAHORA_PRODUCAO),'d/m/y H:i');
                                    $up_descricao         = str_remove_acento($talao->UP_DESCRICAO);
                                    $codigo_barras        = 'P' . str_pad($talao->REVISAO_ID, 12, "0", STR_PAD_LEFT);

                                    $espessura            = $talao->ESPESSURA;

                                    $str_talao = str_replace('#ETIQUETA#' , $tag                     , $str_talao);
                                    $str_talao = str_replace('ETQPROD' , $tag                                        , $str_talao);
                                    
                                    $str_talao = str_replace('#PRODUTO#' , $produto_descricao          , $str_talao);

                                    $str_talao = str_replace('#LINHA1#'  , ''                                      , $str_talao);                    
                                    $str_talao = str_replace('#LINHA2#'  , 'METRAGEM:'.number_format($sobra->SALDO ,4,',','.')        , $str_talao);                    
                                    $str_talao = str_replace('#LINHA3#'  , ''                                           , $str_talao); 
                                    $str_talao = str_replace('#LINHA4#'  , 'REMESSA:'.$talao->REMESSA                   , $str_talao);                    
                                    $str_talao = str_replace('#LINHA5#'  , 'TALAO:'.$talao->REMESSA_TALAO_ID.' / '.$talao->ID , $str_talao);                    
                                    $str_talao = str_replace('#LINHA6#'  , 'LOCALIZACAO:' . str_remove_acento( $talao->LOCALIZACAO)          , $str_talao);                    
                                    $str_talao = str_replace('#LINHA7#'  , 'OPERADOR:'.$request->operador_descricao     , $str_talao); 

                                    $str_talao = str_replace('#DENSIDADE#'          , $talao->DENSIDADE                 , $str_talao);
                                    $str_talao = str_replace('#COD_BARRAS#'         , $codigo_barras                    , $str_talao);                                         
                                    $str_talao = str_replace('#DATA_HORA_IMPRESSAO#', date('d/m/y H:i')                 , $str_talao);                      
                                    $str_talao = str_replace('#ESPESSURA#', $espessura                                  , $str_talao);                      

                                    $string = $string . $str_talao;

                                    Historico::setHistorico('TBREMESSA', $taloes_detalhado[0]->REMESSA_ID, 'Sobra gerada para ' . $talao->ID  . ' ('.$talao->QUANTIDADE_SOBRA.') impresso por ' . $request->operador_id . '-' . $request->operador_descricao);
                                }else{
                                    //aproveitamento de espuma de sao paulo
                                    $talao = $sobra;
                                    
                                    $str_talao = $etiqueta;

                                    $produto_descricao    = str_remove_acento($talao->PRODUTO);
                                    $datahora_producao    = date_format(date_create($talao->DATA_NF),'d/m/y H:i');
                                    $codigo_barras        = 'P' . str_pad($talao->ID, 12, "0", STR_PAD_LEFT);

                                    $espessura            = '';

                                    $str_talao = str_replace('#ETIQUETA#' ,$tag                     , $str_talao);
                                    $str_talao = str_replace('ETQPROD' , $tag                                        , $str_talao);

                                    $str_talao = str_replace('#PRODUTO#' , $produto_descricao                                   , $str_talao);

                                    $str_talao = str_replace('#LINHA1#'  , 'FORNECEDOR:'.$talao->FORNECEDOR                           , $str_talao);                    
                                    $str_talao = str_replace('#LINHA2#'  , 'NOTA FISCAL:'.$talao->NUMERO_NOTAFISCAL                   , $str_talao);                    
                                    $str_talao = str_replace('#LINHA3#'  , 'METRAGEM:'.number_format($talao->SALDO ,4,',','.')        , $str_talao); 
                                    $str_talao = str_replace('#LINHA4#'  , 'OPERADOR:'.$request->operador_descricao                   , $str_talao);                    
                                    $str_talao = str_replace('#LINHA5#'  , 'LOTE:'.$talao->LOTE                                       , $str_talao);                    
                                    $str_talao = str_replace('#LINHA6#'  , 'OB:'.$talao->OB . '  LOCALIZACAO:' . str_remove_acento( $talao->LOCALIZACAO)   , $str_talao);                    
                                    $str_talao = str_replace('#LINHA7#'  , 'ENDERECAMENTO:'.$talao->ENDERECAMENTO                     , $str_talao); 
                                    
                                    $str_talao = str_replace('#DENSIDADE#'          , $talao->DENSIDADE                               , $str_talao);
                                    $str_talao = str_replace('#COD_BARRAS#'         , $codigo_barras                                  , $str_talao);                                         
                                    $str_talao = str_replace('#DATA_HORA_IMPRESSAO#', date('d/m/y H:i')                               , $str_talao);                      
                                    $str_talao = str_replace('#ESPESSURA#'          , $talao->ESPESSURA                               , $str_talao);                      
                                    
                                    $string = $string . $str_talao;
                                }
                            }
                        }
                    }
                }
                
                $etiqueta = _11050::etiqueta(158);

                //sobra de materia prima
                foreach ( $materia_prima as $materia ) {
                    foreach ($materia as $prima){
                    $str_talao = '';

                        if($prima->QUANTIDADE_SOBRA > 0){

                            $taloes = _22010::remessaOrigemConsumo(['CONSUMO_ID' => $prima->CONSUMO_ID]);

                            //log_info($taloes);
                            //log_info($prima);

                            foreach ($taloes as $talao){

                                $str_talao = $etiqueta;

                                $produto_descricao    = str_remove_acento($talao->PROD);
                                
                                if ( trim($prima->COMPONENTE) == '1' ) {
                                    $codigo_barras        = 'RA' . str_pad($talao->TALAO_ID, 11, "0", STR_PAD_LEFT);
                                } else {
                                    $codigo_barras        = 'RD' . str_pad($talao->ID, 11, "0", STR_PAD_LEFT);
                                }

//                                $codigo_barras        = 'RD' . str_pad($talao->ID, 11, "0", STR_PAD_LEFT);

                                $str_talao = str_replace('#ETIQUETA#'           , $tag                            , $str_talao);
                                $str_talao = str_replace('ETQPROD'              , $tag                             , $str_talao);
                                $str_talao = str_replace('#PRODUTO#'            , $produto_descricao                                         , $str_talao);
                                $str_talao = str_replace('#LINHA1#'             , ''                                                         , $str_talao);                    
                                $str_talao = str_replace('#LINHA1#'             , ''                                                         , $str_talao);                    
                                $str_talao = str_replace('#LINHA2#'             , 'QUANTIDADE:'.$materia[0]->QUANTIDADE_SOBRA                , $str_talao);                    
                                $str_talao = str_replace('#LINHA3#'             , ''                                                         , $str_talao); 
                                $str_talao = str_replace('#LINHA4#'             , 'ORIGEM:'.$talao->ID                                       , $str_talao);                    
                                $str_talao = str_replace('#LINHA5#'             , 'COR:'.$talao->COR                                         , $str_talao);                    
                                $str_talao = str_replace('#LINHA6#'             , 'TALAO:'.$talao->REMESSA_TALAO_ID.' / '.$talao->REMESSA_ID , $str_talao);                    
                                $str_talao = str_replace('#LINHA7#'             , 'OPERADOR:'.$request->operador_descricao                   , $str_talao); 
                                $str_talao = str_replace('#ESPESSURA#'          , $talao->ESPESSURA                                          , $str_talao); 
                                $str_talao = str_replace('#DENSIDADE#'          , $talao->TAMANHO                                            , $str_talao);
                                $str_talao = str_replace('#COD_BARRAS#'         , $codigo_barras                                             , $str_talao);                                         
                                $str_talao = str_replace('#DATA_HORA_IMPRESSAO#', date('d/m/y H:i')                                          , $str_talao);
                                $str_talao = str_replace('#LOCALIZACAO#'        , str_remove_acento( $talao->LOCALIZACAO)                                         , $str_talao);                       

                                $string = $string . $str_talao;

                                Historico::setHistorico('TBREMESSA', $taloes_detalhado[0]->REMESSA_ID, 'Sobra gerada para consumo ' . $prima->CONSUMO_ID  . ' ('.$prima->QUANTIDADE_SOBRA.') impresso por ' . $request->operador_id . '-' . $request->operador_descricao);
                            }
                        }    
                    }
                }
            } else
            //////////////////////////////////////////////////////////////////////////////////////////////////////
            // Metradeira
            if ( trim($taloes_detalhado[0]->GP_PERFIL) == 'M')
            {            
                $talao1 = _22040::listar([
                    'RETORNO'  => ['TALAO'],
                    'TALAO_ID' => $request->id,
                    'STATUS'   => 2,
                ])->TALAO[0];
                
                //Etiqueta metradeira acumulado
                $str_talao = _11050::etiqueta(151);   

                $modelo_descricao     = str_remove_acento($talao1->MODELO_DESCRICAO);
                $quantidade_projetada = str_pad(number_format($talao1->QUANTIDADE                      ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM;
                $quantidade_produzida = str_pad(number_format($talao1->QUANTIDADE_PRODUCAO             ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM;
                $quantidade_alt_proj  = str_pad(number_format($talao1->QUANTIDADE_ALTERNATIVA          ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM_ALTERNATIVA;
                $quantidade_alt_prod  = str_pad(number_format($talao1->QUANTIDADE_ALTERNATIVA_PRODUCAO ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM_ALTERNATIVA;
                $datahora_producao    = date_format(date_create($talao1->DATAHORA_REALIZADO_FIM)       ,'d/m/y H:i');
                $up_descricao         = str_remove_acento($talao1->UP_DESCRICAO);
                $codigo_barras        = 'RA' . str_pad($talao1->ID, 11, "0", STR_PAD_LEFT);
                $up_destino           = str_remove_acento($talao1->UP_DESTINO);
                $data_origem          = date_format(date_create($talao1->DATA_ORIGEM),'d/m/y');
                $via                  = ' VIA:' . ($talao1->VIA_ETIQUETA + 1);
                $impressao            = date('d/m/y H:i') . $via;
                    
                $prod_k = $talao1->QUANTIDADE_PRODUCAO;
                $prod_m = $talao1->QUANTIDADE_ALTERNATIVA_PRODUCAO;

                if(($prod_k > 0) && ($prod_m > 0) ){
                    $rendimento = number_format($prod_m/$prod_k,4,',','.').' '.$talao1->UM_ALTERNATIVA.'/'.$talao1->UM;
                }else{
                    $rendimento = '0,000 '.$talao1->UM_ALTERNATIVA.'/'.$talao1->UM;
                }

                $str_talao = str_replace('#ETIQUETA#' , $tag     , $str_talao);
                $str_talao = str_replace('ETQPROD' , $tag         , $str_talao);
                
                
                $str_talao = str_replace('#MODELO#'             , $modelo_descricao          , $str_talao);                   
                $str_talao = str_replace('#REMESSA#'            , $talao1->REMESSA           , $str_talao);                    
                $str_talao = str_replace('#TALAO_ID#'           , $talao1->REMESSA_TALAO_ID  , $str_talao);                    
                $str_talao = str_replace('#TALAO_ORIGEM#'       , $talao1->TALOES_ORIGEM     , $str_talao);                                 
                $str_talao = str_replace('#QTD_PROJ#'           , $quantidade_projetada      , $str_talao);                              
                $str_talao = str_replace('#QTD_PROD#'           , $quantidade_produzida      , $str_talao);                              
                $str_talao = str_replace('#QTD_PROJ_ALT#'       , $quantidade_alt_proj       , $str_talao);                              
                $str_talao = str_replace('#QTD_PROD_ALT#'       , $quantidade_alt_prod       , $str_talao);                              
                $str_talao = str_replace('#DATA_HORA_PRODUCAO#' , $datahora_producao         , $str_talao);                          
                $str_talao = str_replace('#OPERADOR#'           , mb_strimwidth($talao1->OPERADOR_DESCRICAO , 0, 18, "...") , $str_talao);                          
                $str_talao = str_replace('#TALAO_DETALHE_ID#'   , $talao1->ID                , $str_talao);                        
                $str_talao = str_replace('#UP#'                 , $up_descricao              , $str_talao);                                   
                $str_talao = str_replace('#COD_BARRAS#'         , $codigo_barras             , $str_talao);                                         
                $str_talao = str_replace('#DATA_HORA_IMPRESSAO#', $impressao                 , $str_talao);
                $str_talao = str_replace('#RENDIMENTO#'         , $rendimento                , $str_talao);
                $str_talao = str_replace('#UP_DESTINO#'         , $up_destino                , $str_talao);
                $str_talao = str_replace('#DATA_ORIGEM#'        , $data_origem               , $str_talao);
                $str_talao = str_replace('#LOCALIZACAO#'        , str_remove_acento($talao1->LOCALIZACAO)       , $str_talao);  
                    
                $string1 = $str_talao;

                //////////////////////////////////////////////////////////

                // Laço que identifica a quantidade de pares que haverãm na etiqueta
                $pares = [];
                $taloes_detalhado_aux = $taloes_detalhado;
                foreach ( $taloes_detalhado as $talao )
                {
                    $next = next($taloes_detalhado_aux);

                    //Verifica se o próximo valor identificador é diferente do atual
                    if ( empty($next) || $next->PECA_CONJUNTO != $talao->PECA_CONJUNTO ) {
                    array_push($pares, $talao);   
                    }
                }

                //////////////////////////////////////////////////////////

                $n_pares     = count($pares);
                $n_etiquetas =  ceil($n_pares / 10);
                $i           = 0;
                $j           = -1;
                $string2     = '';
                $etiqueta    = _11050::etiqueta(152);
                // Laço que varre a quantidade de etiquetas
                for ($i = 0; $i < $n_etiquetas; $i++) {
                    $str_talao   = $etiqueta;
                    
                    $str_talao = str_replace('#ETIQUETA#'   , $tag      , $str_talao);
                    $str_talao = str_replace('ETQPROD'      , $tag      , $str_talao);

                    $str_talao = str_replace('#VIA#'         , $i+1 . '/' . $n_etiquetas, $str_talao);               
                    $str_talao = str_replace('#REMESSA#'     , $talao1->REMESSA         , $str_talao);                    
                    $str_talao = str_replace('#TALAO_ID#'    , $talao1->REMESSA_TALAO_ID . $via, $str_talao);                    
                    $str_talao = str_replace('#TALAO_ORIGEM#', $talao1->TALOES_ORIGEM   , $str_talao);                           
                    $str_talao = str_replace('#QTD_PROJ#'    , $quantidade_alt_proj     , $str_talao);                              
                    $str_talao = str_replace('#QTD_PROD#'    , $quantidade_alt_prod     , $str_talao);   

                    //Variável que guarda o sequenciamento dos pares
                    $seq = 0;

                    //Laço que varre todos os pares da etiqueta
                    for ($y = 0; $y < 10; $y++) {
                        $j++;
                        $seq++;

                        //Variável que guarda o número do item
                        $item = 0;

                        //Verifica se o item existe 
                        if (($j+1) <= $n_pares){

                            foreach ( $taloes_detalhado as $talao )
                            {
                                if ( $talao->PECA_CONJUNTO == $pares[$j]->PECA_CONJUNTO ) {
                                    $item++;

                                    if ($item == 1) {
                                        $str_talao = str_replace('#PRODUTO_' . $seq . '#', str_remove_acento($talao->TALOES_ORIGEM_MODELO_DESCRICAO), $str_talao); 
                                    }

                                    $qtd_alt_prod = str_pad(number_format($talao->QUANTIDADE_ALTERN_PRODUCAO,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao->UM_ALTERNATIVA;

                                    $str = $qtd_alt_prod . ' - ' . str_remove_acento(trim($talao->COR_DESCRICAO)) . (trim($talao->OBS) ? ' - ' . trim($talao->OBS) : '');

                                    $str_talao = str_replace('#PRODUTO_' . $seq . '_LADO_' . $item . '#' , $str, $str_talao);
                                }
                            }
                        }

                        $str_talao = str_replace('#PRODUTO_' . $seq . '#'        , '', $str_talao); 
                        $str_talao = str_replace('#PRODUTO_' . $seq . '_LADO_1#' , '', $str_talao); 
                        $str_talao = str_replace('#PRODUTO_' . $seq . '_LADO_2#' , '', $str_talao); 
                    }    

                    $string2 = $str_talao . $string2;
                }

                $string = $string2 . $string1;
                
                if($reimpressao == 1){
                    //sobra
                    if ( isset($request->retorno) && in_array('SOBRA', $request->retorno) ) {
                        //////////////////////////////////////////////////////////////////////////////////////
                        //sobra de materia prima tecido
                        $cods = '';
                        foreach ( $itens_materia_prima as $materia ) {
                            foreach ($materia as $prima){
                                $t = $prima->COD;
                                if($t != ''){
                                if($cods == ''){$cods = $t;}else{$cods = $t.','.$cods;}}
                            }
                        }

                        if($cods == ''){
                            $sobra_tecido = [];
                        }else{
                            $sobra_tecido = _22010::itemTbRevisao([
                                'TABELA_ID'           => $cods
                            ]);
                        }

                        if (count($sobra_tecido) > 0)
                        {
                            $etiqueta = _11050::etiqueta(142);

                            foreach ( $sobra_tecido as $sobra ) {

                                if($sobra->SALDO > 0){

                                    $str_talao = $etiqueta;

                                    $CODIGO2  = $sobra->ID;

                                    $CODIGO   = 'P' . str_pad($sobra->ID , 12, "0", STR_PAD_LEFT);
                                    $datanota = date_format(date_create($sobra->DATA_NF),'d/m/y');

                                    $str_talao = str_replace('#ETIQUETA#'  , $tag       , $str_talao);
                                    $str_talao = str_replace('ETIQUETA'    , $tag       , $str_talao);
                                    $str_talao = str_replace('#PRODUTO#'   , str_remove_acento($sobra->PRODUTO)   , $str_talao);
                                    
                                    $metragem = '';
                                    
                                    $k = floatval(number_format($sobra->SALDO ,4,'.',','));
                                    $m = floatval(number_format($sobra->RENDIMENTO_CONSUMO ,4,'.',','));
                                    
                                    if(($m > 0) and ($k > 0) ){
                                        $metragem = number_format($k*$m,4,',','.');
                                    }
                                    
                                    $str_talao = str_replace('#FORNECEDOR#', str_remove_acento($sobra->FORNECEDOR),$str_talao);
                                    $str_talao = str_replace('#DATA_NF#'        , $datanota                     , $str_talao);
                                    $str_talao = str_replace('#OB#'             , $sobra->OB                    , $str_talao);
                                    $str_talao = str_replace('#METRAGEM#'       , $metragem                     , $str_talao);
                                    $str_talao = str_replace('#PESO_LIQUIDO#'   , number_format($sobra->SALDO ,4,',','.')                , $str_talao);
                                    $str_talao = str_replace('#CODIGO#'         , $CODIGO                       , $str_talao);
                                    $str_talao = str_replace('#CODIGO2#'        , $CODIGO2                      , $str_talao);
                                    $str_talao = str_replace('#RENDIMENTO#'     , number_format($sobra->RENDIMENTO_CONSUMO,4,',','.')    , $str_talao);
                                    $str_talao = str_replace('#NF#'             , $sobra->NUMERO_NOTAFISCAL     , $str_talao);
                                    $str_talao = str_replace('#PRODUTO_ID#'     , $sobra->PRODUTO_ID            , $str_talao);
                                    $str_talao = str_replace('#ENDERECAMENTO#'  , $sobra->ENDERECAMENTO         , $str_talao);
                                    $str_talao = str_replace('#OPERADOR#'       , $request->operador_descricao  , $str_talao);
                                    $str_talao = str_replace('#CLASSIFICACAO#'  , trim($sobra->CLASSIFICACAO)   , $str_talao);
                                    $str_talao = str_replace('#DATA_HORA_IMP#'  , date('d/m/y H:i')             , $str_talao);
                                    $str_talao = str_replace('#LOCALIZACAO#'    , str_remove_acento( $sobra->LOCALIZACAO)           , $str_talao);   

                                    $string = $string . $str_talao;

                                    //Historico::setHistorico('TBREMESSA', $taloes_detalhado[0]->REMESSA_ID, 'Sobra gerada para ' . $talao->ID  . ' ('+$talao->QUANTIDADE_SOBRA+') impresso por ' . $request->operador_id . '-' . $request->operador_descricao);
                                }
                            }
                        }
                        //print_l($sobra_tecido);
                    }
                }

            }

            //////////////////////////////////////////////////////////////////////////////////////////////////////
            // Dublagem
            if ( trim($taloes_detalhado[0]->GP_PERFIL) == 'D')
            {
                //se perfil é da dublagem bojo
                $PERFIL_UP = $dados['perfil_up'] == 'B';
                
                $string1 = '';

                if($PERFIL_UP){
                    $talao1 = _22040::listar([
                        'RETORNO'  => ['TALAO'],
                        'TALAO_ID' => $request->id,
                        'STATUS'   => 2,
                    ])->TALAO[0];
                    
                    //Etiqueta metradeira acumulado
                    $str_talao = _11050::etiqueta(151);   

                    $modelo_descricao     = str_remove_acento($talao1->MODELO_DESCRICAO);
                    $quantidade_projetada = str_pad(number_format($talao1->QUANTIDADE                      ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM;
                    $quantidade_produzida = str_pad(number_format($talao1->QUANTIDADE_PRODUCAO             ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM;
                    $quantidade_alt_proj  = str_pad(number_format($talao1->QUANTIDADE_ALTERNATIVA          ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM_ALTERNATIVA;
                    $quantidade_alt_prod  = str_pad(number_format($talao1->QUANTIDADE_ALTERNATIVA_PRODUCAO ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM_ALTERNATIVA;
                    $datahora_producao    = date_format(date_create($talao1->DATAHORA_REALIZADO_FIM)       ,'d/m/y H:i');
                    $up_descricao         = str_remove_acento($talao1->UP_DESCRICAO);
                    $codigo_barras        = 'RA' . str_pad($talao1->ID, 11, "0", STR_PAD_LEFT);
                    $up_destino           = str_remove_acento($talao1->UP_DESTINO);
                    $data_origem          = date_format(date_create($talao1->DATA_ORIGEM),'d/m/y');
                    $via                  = ' VIA:' . ($talao1->VIA_ETIQUETA + 1);
                    $impressao            = date('d/m/y H:i') . $via;
                        
                    $prod_k = $talao1->QUANTIDADE_PRODUCAO;
                    $prod_m = $talao1->QUANTIDADE_ALTERNATIVA_PRODUCAO;

                    $rendimento = '0,000 '.$talao1->UM;

                    $str_talao = str_replace('#ETIQUETA#' , $tag     , $str_talao);
                    $str_talao = str_replace('ETQPROD' , $tag         , $str_talao);
                    
                    $str_talao = str_replace('#MODELO#'             , $modelo_descricao          , $str_talao);                   
                    $str_talao = str_replace('#REMESSA#'            , $talao1->REMESSA           , $str_talao);                    
                    //$str_talao = str_replace('#TALAO_ID#'           , $talao1->REMESSA_TALAO_ID  , $str_talao);                    
                    //$str_talao = str_replace('#TALAO_ORIGEM#'       , $talao1->TALOES_ORIGEM     , $str_talao);                                 
                    $str_talao = str_replace('#QTD_PROJ#'           , $quantidade_projetada      , $str_talao);                              
                    $str_talao = str_replace('#QTD_PROD#'           , $quantidade_produzida      , $str_talao);                              
                    $str_talao = str_replace('#QTD_PROJ_ALT#'       , ''                         , $str_talao);                              
                    $str_talao = str_replace('#QTD_PROD_ALT#'       , ''                         , $str_talao);                              
                    $str_talao = str_replace('#DATA_HORA_PRODUCAO#' , $datahora_producao         , $str_talao);                          
                    $str_talao = str_replace('#OPERADOR#'           , mb_strimwidth($talao1->OPERADOR_DESCRICAO , 0, 18, "...") , $str_talao);                          
                    $str_talao = str_replace('#TALAO_DETALHE_ID#'   , $talao1->ID                , $str_talao);                        
                    $str_talao = str_replace('#UP#'                 , $up_descricao              , $str_talao);                                   
                    $str_talao = str_replace('#COD_BARRAS#'         , $codigo_barras             , $str_talao);                                         
                    $str_talao = str_replace('#DATA_HORA_IMPRESSAO#', $impressao                 , $str_talao);
                    $str_talao = str_replace('#LOCALIZACAO#'        , str_remove_acento($talao1->LOCALIZACAO)      , $str_talao); 
                    //$str_talao = str_replace('#RENDIMENTO#'         , $rendimento                , $str_talao);
                    $str_talao = str_replace('#UP_DESTINO#'         , $up_destino                , $str_talao);
                    $str_talao = str_replace('#DATA_ORIGEM#'        , $data_origem               , $str_talao);

                    $str_talao = str_replace('A25,380,0,3,1,2,N,"TALAO:', 'A25,340,0,2,1,2,N,"TALOES:'     , $str_talao); 
                    $str_talao = str_replace('A430,380,0,3,1,2,N,"RENDIMENTO: #RENDIMENTO#"', 'A120,380,0,2,1,2,N,"#TALAO_ID2#"'                  , $str_talao); 

                    $c = trim($talao1->TALOES_ORIGEM).' ' ;

                    $a = $c;
                    $b = '';

                    if(strlen($c) > 53){
                        $a = substr($c, 00, 52);
                        $b = substr($c, 54, -1);
                    }
                    
                    $str_talao = str_replace('#TALAO_ID#'           , $a                         , $str_talao);
                    $str_talao = str_replace('#TALAO_ID2#'          , $b                         , $str_talao);                    
                    $str_talao = str_replace('#TALAO_ORIGEM#'       , $talao1->REMESSA_TALAO_ID  , $str_talao);
                        
                    $string1 = $str_talao;

                    $taloes = _22010::remessaOrigem([
                        'RETORNO'            => ['TALAO'],
                        'TALAO_ID'           => $request->id,
                        //'STATUS'             => 2,
                    ])->TALAO;

                    $etiquetaFim    = _11050::etiqueta(171);
                    
                    $etiqueta = _11050::etiqueta(136);
                    
                    $contEtiquetas = 0;

                    $v_origem = 0;
                    $v_cores  = [];
                    $v_soma   = 0;
                    $v_remesa = 0;

                    foreach ( $taloes as $talao )
                    {   
                        $detalhado = 0;

                        foreach ($taloes_detalhado as $detalhe){

                            $pos = substr_count($detalhe->TALOES_ORIGEM . '', str_pad($talao->CONTROLE, 4, '0', STR_PAD_LEFT) . '');
                            
                            //log_info($pos . ' / ' . $talao->CONTROLE .  ' / ' .  $detalhe->TALOES_ORIGEM);

                            if ($pos == 1) {
                                $detalhado = $detalhe->ID;
                                //log_info($pos . ' / ' . str_pad($talao->CONTROLE, 4, '0', STR_PAD_LEFT) .  ' / ' .  $detalhado .  ' / ' . $detalhe->TALOES_ORIGEM);
                            }
                        }
                        
                        $str_talao = $etiqueta;

                        if ($detalhado == 0){
                            $ob = '';
                        }else{

                            $fls = [
                                'TALAO_ID'                  => $talao->ID,
                                'REMESSA_ID'                => $dados['remessa_id'],
                                'REMESSA_TALAO_ID'          => $dados['remessa_talao_id'],
                                'REMESSA_TALAO_DETALHE_ID'  => $detalhado
                            ];

                            $ob = _22010::consultaOBTalao($fls);
                        }



                        $v_origem = $talao->TALAO_ORIGEM;
                        array_push( $v_cores,$talao->COR_CODIGO);
                        $v_soma   = $v_soma + ($talao->CONSUMO_MEDIO);
                        $v_remesa = $talao->REMESSA;
                                            
                        $up_destino     = str_remove_acento($talao->UP_DESTINO);
                        $remessa        = str_pad($talao->REMESSA, 5, '0', STR_PAD_LEFT);
                        $remessa_data   = date_format(date_create($talao->REMESSA_DATA),'d/m/y');
                        $consumo        = number_format($talao->CONSUMO_MEDIO,3,',','.') . ' Mts ' . number_format($talao->MEDIAPAR,3,',','.') . ' Prs/Mt';
                        $espessura      = number_format($talao->MODELO_ESPESSURA,2,',','.');
                        $densidade      = number_format($talao->MODELO_DENSIDADE,0,',','.');
                        $cod_liberacao  = str_pad($talao->REMESSA, 6, '0', STR_PAD_LEFT) . str_pad($talao->CONTROLE, 4, '0', STR_PAD_LEFT);
                        $talao_controle = str_pad($talao->CONTROLE, 4, '0', STR_PAD_LEFT);
                        $qtd_liberacao  = str_pad((int)($talao->QUANTIDADE - $talao->QUANTIDADE_SOBRA), 2, ' ', STR_PAD_LEFT);
                        $qtd_sobra      = str_pad((int)$talao->QUANTIDADE_SOBRA, 2, '0', STR_PAD_LEFT);
                        $chapa          = str_pad((int)$talao->CHAPAS , 2, '0', STR_PAD_LEFT);
                        $placa          = str_pad((int)$talao->PLACAS , 2, '0', STR_PAD_LEFT);
                        $sobra          = str_pad((int)$talao->SOBRAS , 2, '0', STR_PAD_LEFT);
                        $placa_         = str_pad((int)$talao->PLACAS_, 2, '0', STR_PAD_LEFT);
                        $sobra_         = str_pad((int)$talao->SOBRAS_, 2, '0', STR_PAD_LEFT);
                        $aproveitamento = ($talao->APROVEITAMENTO > 0) ? '+'.str_pad((int)$talao->APROVEITAMENTO, 2, '0', STR_PAD_LEFT) : '';
                        $boca           = str_pad($talao->PROGRAMACAO_BOCA, 2, '0', STR_PAD_LEFT);
                        $classe         = '('.ceil($talao->CLASSE).')';
                        $produto2       = str_replace('BOJO ', '', str_remove_acento($talao->PRODUTO_DESCRICAO));
                        $vip            = ( $talao->REMESSA_TIPO == '2' ) ? 'VIP' : '' ;
                        $impressao      = date('d/m/y H:i') . ' VIA:' . ($talao->VIA_ETIQUETA + 1);                    
                                            
                        if ( $talao->PERC_SOBRA > 0 && $talao->REMESSA_REQUISICAO == '0' ) { //SOBRA
                            $tipo       = 'R';
                            $comentario = number_format($talao->PERC_SOBRA,1,',','.') . '%';
                        } else {
                            $tipo  = $talao->REMESSA_REQUISICAO;
                            
                            if ( trim($tipo) == '1' ) { //REQUISIÇÃO
                                $tipo       = 'R';
                                $comentario = ' REQ ';
                            } else { //NORMAL
                                $tipo       = 'N';
                                $comentario = '';
                            }
                        }

                        if ( substr($talao->SEQ,0,3) == 'ULT' ) {
                            $sequencial1 = '';
                            $sequencial2 = $talao->SEQ;
                        } else {
                            $sequencial1 = $talao->SEQ;
                            $sequencial2 = '';
                        }

                        if ( trim($talao_controle) >= 8000 && trim($talao_controle) <= 8999 ) { //REQUISIÇÃO
                                $comentario = ' EXTRA ';
                        }

                        if ( $talao->MATRIZ_CODIGO > 0) {

                            $largura_t     = $talao->{'LARGURA_T'     . str_pad($talao->TAMANHO,2,'0', STR_PAD_LEFT)};
                            $comprimento_t = $talao->{'COMPRIMENTO_T' . str_pad($talao->TAMANHO,2,'0', STR_PAD_LEFT)};
                            $placa_t       = $talao->{'PLACA_T'       . str_pad($talao->TAMANHO,2,'0', STR_PAD_LEFT)};
                            $sobra_t       = $talao->{'SOBRA_T'       . str_pad($talao->TAMANHO,2,'0', STR_PAD_LEFT)};

                            $tamanho_placa  = number_format($largura_t    ,2,',','.') . ' x ';
                            $tamanho_placa .= number_format($comprimento_t,2,',','.');

                            if ( $sobra_t > 0 ) {

                                $tamanho_sobra  = number_format($largura_t,2,',','.') . ' x ';
                                $tamanho_sobra .= number_format($talao->LARGURA - ($placa_t * $comprimento_t),2,',','.');
                            } else {
                               $tamanho_sobra = '';
                            }
                        } else {
                            $tamanho_placa = '';
                            $tamanho_sobra = '';
                        }

                        $str_talao = str_replace('#ETIQUETA#'           , $tag, $str_talao);
                        $str_talao = str_replace('ETQPROD'              , $tag , $str_talao);
                        $str_talao = str_replace('#REMESSA#'            , $remessa                 , $str_talao); 
                        $str_talao = str_replace('#DATA#'               , $remessa_data            , $str_talao);
                        $str_talao = str_replace('#MTZ#'                , $talao->MATRIZ_DESCRICAO , $str_talao);    
                        $str_talao = str_replace('#CONSUMO#'            , $consumo                 , $str_talao);    
                        $str_talao = str_replace('#ESPESSURA#'          , $espessura               , $str_talao);    
                        $str_talao = str_replace('#DENSIDADE#'          , $densidade               , $str_talao);    
                        $str_talao = str_replace('#CODIGOLIBERACAO1#'   , $cod_liberacao.'03'      , $str_talao);    
                        $str_talao = str_replace('#CODIGOLIBERACAO2#'   , $cod_liberacao.'04'      , $str_talao);    
                        $str_talao = str_replace('#NUMERO_TALAO#'       , $talao_controle          , $str_talao);    
                        $str_talao = str_replace('#TAMANHO#'            , $talao->TAMANHO_GRADE    , $str_talao);    
                        $str_talao = str_replace('#QUANTIDADELIBERACAO#', $qtd_liberacao           , $str_talao);   
                        $str_talao = str_replace('#QUANTIDADE_SOBRA#'   , $qtd_sobra               , $str_talao);    
                        $str_talao = str_replace('#FABRICA#'            , $talao->ESTEIRA_DESCRICAO, $str_talao);    
                        $str_talao = str_replace('#CHAPAS#'             , $chapa                   , $str_talao);    
                        $str_talao = str_replace('#PLACAS#'             , $placa                   , $str_talao);    
                        $str_talao = str_replace('#SOBRAS#'             , $sobra                   , $str_talao);     
                        $str_talao = str_replace('#SUB_PLACAS#'         , $placa_                  , $str_talao);    
                        $str_talao = str_replace('#SUB_SOBRAS#'         , $sobra_                  , $str_talao);    
                        $str_talao = str_replace('#APROVEITAMENTO#'     , $aproveitamento          , $str_talao);    
                        $str_talao = str_replace('#TURNO#'              , $talao->TURNO            , $str_talao);    
                        $str_talao = str_replace('#OB#'                 , $ob                      , $str_talao);    
                        $str_talao = str_replace('#SUBUP#'              , $boca                    , $str_talao);    
                        $str_talao = str_replace('#CLASSE#'             , $classe                  , $str_talao);    
                        $str_talao = str_replace('#PRODUTO_ID#'         , $talao->PRODUTO_CODIGO   , $str_talao);    
                        $str_talao = str_replace('#PRODUTO2#'           , $produto2                , $str_talao);    
                        $str_talao = str_replace('#TIPO#'               , $tipo                    , $str_talao);    
                        $str_talao = str_replace('#COMENTARIO#'         , $comentario              , $str_talao);    
                        $str_talao = str_replace('#VIP#'                , $vip                     , $str_talao);    
                        $str_talao = str_replace('#COMPOSICAO#'         , ''                       , $str_talao);    
                        $str_talao = str_replace('#SEQUENCIAL1#'        , $sequencial1             , $str_talao);    
                        $str_talao = str_replace('#SEQUENCIAL2#'        , $sequencial2             , $str_talao);    
                        $str_talao = str_replace('#CLIENTE#'            , $talao->CLIENTE          , $str_talao);    
                        $str_talao = str_replace('#TAMANHOPLACAS#'      , $tamanho_placa           , $str_talao);    
                        $str_talao = str_replace('#TAMANHOSOBRAS#'      , $tamanho_sobra           , $str_talao);  
                        $str_talao = str_replace('#TALAO_ORIGEM#'       , $talao->TALAO_ORIGEM     , $str_talao);  
                        $str_talao = str_replace('#DATA_HORA_IMPRESSAO#', $impressao               , $str_talao);  
                        $str_talao = str_replace('#UP_DESTINO#'         , $up_destino              , $str_talao);
                        $str_talao = str_replace('#LOCALIZACAO#'        , str_remove_acento( $talao->LOCALIZACAO)       , $str_talao); 
                           
                        $str_talao = str_replace('#CONTADOR#'           , $contEtiquetas+1 , $str_talao);
                        
                        $string = $string . $str_talao;
                        $contEtiquetas++;
                    }

                }else{
                    //se perfil é da dublagem espumas

                    $talao1 = _22040::listar([
                        'RETORNO'  => ['TALAO'],
                        'TALAO_ID' => $request->id,
                        'STATUS'   => 2,
                    ])->TALAO[0];
                    
                    //Etiqueta metradeira acumulado
                    $str_talao = _11050::etiqueta(151);   

                    $modelo_descricao     = str_remove_acento($talao1->MODELO_DESCRICAO);
                    $quantidade_projetada = str_pad(number_format($talao1->QUANTIDADE                      ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM;
                    $quantidade_produzida = str_pad(number_format($talao1->QUANTIDADE_PRODUCAO             ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM;
                    $quantidade_alt_proj  = str_pad(number_format($talao1->QUANTIDADE_ALTERNATIVA          ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM_ALTERNATIVA;
                    $quantidade_alt_prod  = str_pad(number_format($talao1->QUANTIDADE_ALTERNATIVA_PRODUCAO ,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao1->UM_ALTERNATIVA;
                    $datahora_producao    = date_format(date_create($talao1->DATAHORA_REALIZADO_FIM)       ,'d/m/y H:i');
                    $up_descricao         = str_remove_acento($talao1->UP_DESCRICAO);
                    $codigo_barras        = 'RA' . str_pad($talao1->ID, 11, "0", STR_PAD_LEFT);
                    $up_destino           = str_remove_acento($talao1->UP_DESTINO);
                    $data_origem          = date_format(date_create($talao1->DATA_ORIGEM),'d/m/y');
                    $via                  = ' VIA:' . ($talao1->VIA_ETIQUETA + 1);
                    $impressao            = date('d/m/y H:i') . $via;
                        
                    $prod_k = $talao1->QUANTIDADE_PRODUCAO;
                    $prod_m = $talao1->QUANTIDADE_ALTERNATIVA_PRODUCAO;

                    $rendimento = '0,000 '.$talao1->UM;

                    $str_talao = str_replace('#ETIQUETA#' , $tag     , $str_talao);
                    $str_talao = str_replace('ETQPROD' , $tag         , $str_talao);
                    
                    
                    $str_talao = str_replace('#MODELO#'             , $modelo_descricao          , $str_talao);                   
                    $str_talao = str_replace('#REMESSA#'            , $talao1->REMESSA           , $str_talao);                    
                    $str_talao = str_replace('#TALAO_ID#'           , $talao1->REMESSA_TALAO_ID  , $str_talao);                    
                    $str_talao = str_replace('#TALAO_ORIGEM#'       , $talao1->TALOES_ORIGEM     , $str_talao);                                 
                    $str_talao = str_replace('#QTD_PROJ#'           , $quantidade_projetada      , $str_talao);                              
                    $str_talao = str_replace('#QTD_PROD#'           , $quantidade_produzida      , $str_talao);                              
                    $str_talao = str_replace('#QTD_PROJ_ALT#'       , ''                         , $str_talao);                              
                    $str_talao = str_replace('#QTD_PROD_ALT#'       , ''                         , $str_talao);                              
                    $str_talao = str_replace('#DATA_HORA_PRODUCAO#' , $datahora_producao         , $str_talao);                          
                    $str_talao = str_replace('#OPERADOR#'           , mb_strimwidth($talao1->OPERADOR_DESCRICAO , 0, 18, "...") , $str_talao);                           
                    $str_talao = str_replace('#TALAO_DETALHE_ID#'   , $talao1->ID                , $str_talao);                        
                    $str_talao = str_replace('#UP#'                 , $up_descricao              , $str_talao);                                   
                    $str_talao = str_replace('#COD_BARRAS#'         , $codigo_barras             , $str_talao);                                         
                    $str_talao = str_replace('#DATA_HORA_IMPRESSAO#', $impressao                 , $str_talao);
                    $str_talao = str_replace('#RENDIMENTO#'         , $rendimento                , $str_talao);
                    $str_talao = str_replace('#UP_DESTINO#'         , $up_destino                , $str_talao);
                    $str_talao = str_replace('#DATA_ORIGEM#'        , $data_origem               , $str_talao);
                    $str_talao = str_replace('#LOCALIZACAO#'        , str_remove_acento($talao1->LOCALIZACAO)       , $str_talao); 
  
                    $string1 = $str_talao;

                    //////////////////////////////////////////////////////////

                    // Laço que identifica a quantidade de pares que haverãm na etiqueta
                    $pares = [];
                    $taloes_detalhado_aux = $taloes_detalhado;
                    foreach ( $taloes_detalhado as $talao )
                    {
                        $next = next($taloes_detalhado_aux);

                        //Verifica se o próximo valor identificador é diferente do atual
                        if ( empty($next) || $next->PECA_CONJUNTO != $talao->PECA_CONJUNTO ) {
                        array_push($pares, $talao);   
                        }
                    }

                    //////////////////////////////////////////////////////////

                    $n_pares     = count($pares);
                    $n_etiquetas =  ceil($n_pares / 10);
                    $i           = 0;
                    $j           = -1;
                    $string2     = '';
                    $etiqueta    = _11050::etiqueta(152);
                    // Laço que varre a quantidade de etiquetas
                    for ($i = 0; $i < $n_etiquetas; $i++) {
                        $str_talao   = $etiqueta;
                        
                        $str_talao = str_replace('#ETIQUETA#'   , $tag      , $str_talao);
                        $str_talao = str_replace('ETQPROD'      , $tag      , $str_talao);

                        $str_talao = str_replace('#VIA#'         , $i+1 . '/' . $n_etiquetas, $str_talao);               
                        $str_talao = str_replace('#REMESSA#'     , $talao1->REMESSA         , $str_talao);                    
                        $str_talao = str_replace('#TALAO_ID#'    , $talao1->REMESSA_TALAO_ID . $via, $str_talao);                    
                        $str_talao = str_replace('#TALAO_ORIGEM#', $talao1->TALOES_ORIGEM   , $str_talao);                           
                        $str_talao = str_replace('#QTD_PROJ#'    , $quantidade_projetada    , $str_talao);                              
                        $str_talao = str_replace('#QTD_PROD#'    , $quantidade_produzida    , $str_talao);   

                        //Variável que guarda o sequenciamento dos pares
                        $seq = 0;

                        //Laço que varre todos os pares da etiqueta
                        for ($y = 0; $y < 10; $y++) {
                            $j++;
                            $seq++;

                            //Variável que guarda o número do item
                            $item = 0;

                            //Verifica se o item existe 
                            if (($j+1) <= $n_pares){

                                foreach ( $taloes_detalhado as $talao )
                                {
                                    if ( $talao->PECA_CONJUNTO == $pares[$j]->PECA_CONJUNTO ) {
                                        $item++;

                                        if ($item == 1) {
                                            $str_talao = str_replace('#PRODUTO_' . $seq . '#', str_remove_acento($talao->TALOES_ORIGEM_MODELO_DESCRICAO), $str_talao); 
                                        }

                                        $quantidade_produzida = str_pad(number_format($talao->QUANTIDADE_PRODUCAO,1,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $talao->UM;

                                        $str = $quantidade_produzida . ' - ' . str_remove_acento(trim($talao->COR_DESCRICAO)) . (trim($talao->OBS) ? ' - ' . trim($talao->OBS) : '');

                                        $str_talao = str_replace('#PRODUTO_' . $seq . '_LADO_' . $item . '#' , $str, $str_talao);
                                    }
                                }
                            }

                            $str_talao = str_replace('#PRODUTO_' . $seq . '#'        , '', $str_talao); 
                            $str_talao = str_replace('#PRODUTO_' . $seq . '_LADO_1#' , '', $str_talao); 
                            $str_talao = str_replace('#PRODUTO_' . $seq . '_LADO_2#' , '', $str_talao); 
                        }    

                        $string2 = $str_talao . $string2;
                    }

                    $string = $string2 . $string1;
                    
                    //sobra
                    if ( isset($request->retorno) && in_array('SOBRA', $request->retorno) ) {
                        //////////////////////////////////////////////////////////////////////////////////////
                        //sobra de materia prima tecido
                        $cods = '';
                        foreach ( $itens_materia_prima as $materia ) {
                            foreach ($materia as $prima){
                                $t = $prima->COD;
                                if($t != ''){
                                if($cods == ''){$cods = $t;}else{$cods = $t.','.$cods;}}
                            }
                        }

                        if($cods == ''){
                            $sobra_tecido = [];
                        }else{
                            $sobra_tecido = _22010::itemTbRevisao([
                                'TABELA_ID'           => $cods
                            ]);
                        }

                        if (count($sobra_tecido) > 0)
                        {
                            $etiqueta = _11050::etiqueta(142);

                            foreach ( $sobra_tecido as $sobra ) {

                                if($sobra->SALDO > 0 && $sobra->FAMILIA_ID != 74){

                                    $str_talao = $etiqueta;

                                    $CODIGO2  = $sobra->ID;
                                    $CODIGO   = 'P' . str_pad($sobra->ID , 12, "0", STR_PAD_LEFT);
                                    $datanota = date_format(date_create($sobra->DATA_NF),'d/m/y');

                                    $str_talao = str_replace('#ETIQUETA#'  , $tag       , $str_talao);
                                    $str_talao = str_replace('ETIQUETA'    , $tag       , $str_talao);
                                    $str_talao = str_replace('#PRODUTO#'   , str_remove_acento($sobra->PRODUTO)   , $str_talao);
                                    
                                    $metragem = '';
                                    
                                    $k = floatval(number_format($sobra->SALDO ,4,'.',','));
                                    $m = floatval(number_format($sobra->RENDIMENTO_CONSUMO ,4,'.',','));
                                    
                                    if(($m > 0) and ($k > 0) ){
                                        $metragem = number_format($k*$m,4,',','.');
                                    }
                                    
                                    $str_talao = str_replace('#FORNECEDOR#', str_remove_acento($sobra->FORNECEDOR),$str_talao);
                                    $str_talao = str_replace('#DATA_NF#'        , $datanota                     , $str_talao);
                                    $str_talao = str_replace('#OB#'             , $sobra->OB                    , $str_talao);
                                    $str_talao = str_replace('#METRAGEM#'       , $metragem                     , $str_talao);
                                    $str_talao = str_replace('#PESO_LIQUIDO#'   , number_format($sobra->SALDO ,4,',','.')                , $str_talao);
                                    $str_talao = str_replace('#CODIGO#'         , $CODIGO                       , $str_talao);
                                    $str_talao = str_replace('#CODIGO2#'        , $CODIGO2                      , $str_talao);
                                    $str_talao = str_replace('#RENDIMENTO#'     , number_format($sobra->RENDIMENTO_CONSUMO,4,',','.')    , $str_talao);
                                    $str_talao = str_replace('#NF#'             , $sobra->NUMERO_NOTAFISCAL     , $str_talao);
                                    $str_talao = str_replace('#PRODUTO_ID#'     , $sobra->PRODUTO_ID            , $str_talao);
                                    $str_talao = str_replace('#ENDERECAMENTO#'  , $sobra->ENDERECAMENTO         , $str_talao);
                                    $str_talao = str_replace('#OPERADOR#'       , str_pad($request->operador_descricao, 15, " ", STR_PAD_LEFT) , $str_talao);
                                    $str_talao = str_replace('#CLASSIFICACAO#'  , trim($sobra->CLASSIFICACAO)   , $str_talao);
                                    $str_talao = str_replace('#DATA_HORA_IMP#'  , date('d/m/y H:i')             , $str_talao);
                                    $str_talao = str_replace('#LOCALIZACAO#'    , str_remove_acento( $sobra->LOCALIZACAO)           , $str_talao); 

                                    $string = $string . $str_talao;

                                    //Historico::setHistorico('TBREMESSA', $taloes_detalhado[0]->REMESSA_ID, 'Sobra gerada para ' . $talao->ID  . ' ('+$talao->QUANTIDADE_SOBRA+') impresso por ' . $request->operador_id . '-' . $request->operador_descricao);
                                }
                            }
                        }
                        //print_l($sobra_tecido);
                    }

                }
                
                $etiqueta = _11050::etiqueta(158);

                
                //sobra de materia prima
                foreach ( $materia_prima as $materia ) {
                    foreach ($materia as $prima){
                    $str_talao = '';

                        if($prima->QUANTIDADE_SOBRA > 0){

                            $taloes = _22010::remessaOrigemConsumo(['CONSUMO_ID' => $prima->CONSUMO_ID]);

                            //log_info($taloes);
                            //log_info($request->id);

                            foreach ($taloes as $talao){
                                
                                if($prima->QUANTIDADE_SOBRA > 0 && $talao->ESPESSURA > 0){
                                    
                                    $str_talao = $etiqueta;
                                    
                                    if(trim($talao->TIPO) == 'D'){
                                        $produto_descricao    = str_remove_acento($talao->PROD);
                                        
                                        if ( trim($prima->COMPONENTE) == '1' ) {
                                            $codigo_barras        = 'RA' . str_pad($talao->TALAO_ID, 11, "0", STR_PAD_LEFT);
                                        } else {
                                            $codigo_barras        = 'RD' . str_pad($talao->ID, 11, "0", STR_PAD_LEFT);
                                        }
                                        
                                        $desc                 = 'REM: '.$talao->REMESSA.' / '.$talao->REMESSA_TALAO_ID;
                                    }else{
                                        $produto_descricao    = str_remove_acento($talao->PROD);
                                        $codigo_barras        = 'P' . str_pad($talao->ID, 12, "0", STR_PAD_LEFT);
                                        $desc                 = 'PECA: '.$talao->ID;
                                    }

                                        $str_talao = str_replace('#ETIQUETA#'           , $tag                                                       , $str_talao);
                                        $str_talao = str_replace('ETQPROD'              , $tag                                                       , $str_talao);
                                        $str_talao = str_replace('#PRODUTO#'            , $produto_descricao                                         , $str_talao);
                                        $str_talao = str_replace('#LINHA1#'             , $desc                                                      , $str_talao);                    
                                        $str_talao = str_replace('#LINHA1#'             , ''                                                         , $str_talao);                    
                                        $str_talao = str_replace('#LINHA2#'             , 'QUANTIDADE:'.$prima->QUANTIDADE_SOBRA                     , $str_talao);                    
                                        $str_talao = str_replace('#LINHA3#'             , 'OPERADOR:'.$request->operador_descricao                   , $str_talao); 
                                        $str_talao = str_replace('#LINHA4#'             , 'ORIGEM:'.$talao->ID                                       , $str_talao);                    
                                        $str_talao = str_replace('#LINHA5#'             , 'COR:'.$talao->COR                                         , $str_talao);                    
                                        $str_talao = str_replace('#LINHA6#'             , 'LOC.:'. str_remove_acento($talao->LOCALIZACAO)            , $str_talao);                    
                                        $str_talao = str_replace('#LINHA7#'             , '[SOBRA]'                                                  , $str_talao); 
                                        $str_talao = str_replace('#ESPESSURA#'          , $talao->ESPESSURA                                          , $str_talao); 
                                        $str_talao = str_replace('#DENSIDADE#'          , $talao->TAMANHO                                            , $str_talao);
                                        $str_talao = str_replace('#COD_BARRAS#'         , $codigo_barras                                             , $str_talao);                                         
                                        $str_talao = str_replace('#DATA_HORA_IMPRESSAO#', date('d/m/y H:i')                                          , $str_talao);  

                                        $string = $string . $str_talao;
                                    
                                        Historico::setHistorico('TBREMESSA', $taloes_detalhado[0]->REMESSA_ID, 'Sobra gerada para consumo ' . $prima->CONSUMO_ID  . ' ('.$prima->QUANTIDADE_SOBRA.') impresso por ' . $request->operador_id . '-' . $request->operador_descricao);
                                }    
                            }
                        }    
                    }
                }

                if($PERFIL_UP){
                    $unicos = array_unique($v_cores);
                    
                    $etiquetaFim = str_replace('#NUMERO_TALOES#' , str_pad($contEtiquetas         , 2, '0', STR_PAD_LEFT) , $etiquetaFim);
                    $etiquetaFim = str_replace('#CONSSUMO#'      , number_format($v_soma          , 2, ',', '.'         ) , $etiquetaFim);
                    $etiquetaFim = str_replace('#CORRES#'        , count($unicos), $etiquetaFim);
                    $etiquetaFim = str_replace('#ORIGEM#'        , $v_origem, $etiquetaFim);
                    $etiquetaFim = str_replace('#REMESA#'        , $v_remesa, $etiquetaFim);

                    $string = $string . $string1 . $etiquetaFim;
                }else{
                    
                }
            }
        }

        
        //sobra
        if ( isset($request->retorno) && in_array('SOBRA', $request->retorno) ) {
            $etiqueta = _11050::etiqueta(158);

            //sobra de produção
            foreach ( $taloes_detalhado as $talao ) {

                $str_talao = '';

                if($talao->QUANTIDADE_SOBRA > 0){

                    $str_talao = $etiqueta;
                    
                    $produto_descricao    = str_remove_acento($talao->PRODUTO_DESCRICAO);
                    $quantidade_projetada = number_format($talao->QUANTIDADE,1,',','.') . ' ' . $talao->UM;
                    $quantidade_produzida = number_format($talao->QUANTIDADE_PRODUCAO,1,',','.') . ' ' . $talao->UM;
                    $datahora_producao    = date_format(date_create($talao->DATAHORA_PRODUCAO),'d/m/y H:i');
                    $up_descricao         = str_remove_acento($talao->UP_DESCRICAO);
                    $codigo_barras        = 'P' . str_pad($talao->REVISAO_ID, 12, "0", STR_PAD_LEFT);

                    $espessura            = $talao->ESPESSURA;

                    $str_talao = str_replace('#ETIQUETA#' , $tag                           , $str_talao);

                    $str_talao = str_replace('#PRODUTO#' , $produto_descricao          , $str_talao);

                    $str_talao = str_replace('#LINHA1#'  , ''                                           , $str_talao);                    
                    $str_talao = str_replace('#LINHA2#'  , 'METRAGEM:'.$talao->QUANTIDADE_SOBRA         , $str_talao);                    
                    $str_talao = str_replace('#LINHA3#'  , ''                                           , $str_talao); 
                    $str_talao = str_replace('#LINHA4#'  , 'REMESSA:'.$talao->REMESSA                   , $str_talao);                    
                    $str_talao = str_replace('#LINHA5#'  , 'TALAO:'.$talao->REMESSA_TALAO_ID.' / '.$talao->ID , $str_talao);                    
                    $str_talao = str_replace('#LINHA6#'  , 'LOCALIZACAO:'.str_remove_acento( $talao->LOCALIZACAO)           , $str_talao);                     
                    $str_talao = str_replace('#LINHA7#'  , 'OPERADOR:'.$request->operador_descricao     , $str_talao); 

                    $str_talao = str_replace('#DENSIDADE#'          , $talao->DENSIDADE                 , $str_talao);
                    $str_talao = str_replace('#COD_BARRAS#'         , $codigo_barras                    , $str_talao);                                         
                    $str_talao = str_replace('#DATA_HORA_IMPRESSAO#', date('d/m/y H:i')                 , $str_talao);                      
                    $str_talao = str_replace('#ESPESSURA#', $espessura                                  , $str_talao);                      

                    $string = $string . $str_talao;

                    Historico::setHistorico('TBREMESSA', $taloes_detalhado[0]->REMESSA_ID, 'Sobra gerada para ' . $talao->ID  . ' ('.$talao->QUANTIDADE_SOBRA.') impresso por ' . $request->operador_id . '-' . $request->operador_descricao);
                }
            }
        }
        
        // Registra o histórico de impressão
        if ( !(trim($string) == '')) {
            _22010::updateTalaoViaEtiqueta($request->id);
            Historico::setHistorico('TBREMESSA', $taloes_detalhado[0]->REMESSA_ID, 'Etiqueta do talão ' . $request->id . '/'.$taloes_detalhado[0]->REMESSA_TALAO_ID.' impresso por ' . $request->operador_id . '-' . $request->operador_descricao);
            
        }
        
       return $string;    
    }
	
	/**
	 * Recarregar o status do talão.
	 * @param Request $request
	 * @return array
	 */
	public function recarregarStatus(Request $request) {
		
		$res = _22010::recarregarStatus([
            'ESTABELECIMENTO_ID'   => $request->estabelecimento_id,
            'GP_ID'                => $request->gp_id,
            'UP_ID'                => $request->up_id,
            'ESTACAO'              => $request->estacao,
            'STATUS'               => 1, // Status 1 = Em Aberto
			'TALAO_ID'			   => $request->talao_id,
            'TALAO_COMPOSICAO'     => isset($request->talao_composicao) ? $request->talao_composicao : '0',
            'GP_PECAS_DISPONIVEIS' => isset($request->gp_pecas_disponiveis) ? $request->gp_pecas_disponiveis : '0'
        ]);
		
		if ( $request->ajax() ) {			
            $ret = Response::json($res);
        } 
		else {			
            $ret = $res;
        }
        
        return $ret;
	}
    
    /**
	 * Recarregar talão vinculo.
	 * @param Request $request
	 * @return array
	 */
	public function remessaOrigemConsumo($consumo_id) {
		
		$res = _22010::remessaOrigemConsumo(['consumo_id' => $consumo_id->estabelecimento_id]);

        return $ret;
	}
	
	/**
	 * Excluir item alocado
	 * @param Request $request
	 * @return array
	 */
	public function projecaoVinculoExcluir(Request $request)
    {
		_22010::projecaoVinculoExcluir([
            'ID' => $request->id
        ]);
	}

	public function registrarAproveitamento(Request $request)
    {       
        $cod_barras = $request->CODIGO_BARRAS;
		$verif_aproveit = '';
        
        /**
         * Verifica se a quantidade de caracteres do código de barras é diferente de 13
         */
        if ( strlen($cod_barras) != 13 && strlen($cod_barras) != 12 ) {
            log_erro('Código de barras inválido. COD:'.$cod_barras);
        }
         
        /**
         * Verifica para onde será direcionado a consulta
         * 'P'  = [P=PESAGEM]              Id da Pesagem 
         */ 
        elseif ( strstr($cod_barras, 'P') ){
            $id = (float) str_replace('P', '', $cod_barras);
            $tipo = 'R'; // Registra vínculo a partir do registro da revisão
            
            $verif_aproveit = _22010::verificarAproveitamento(['ID' => $id]);
        }
        else {
            log_erro('Código de barras inválido.. COD:'.$cod_barras);
        }

        if ( !isset($verif_aproveit[0]) ) {
            log_erro('Registro não localizado ou o item não é um aproveitamento. COD:'.$cod_barras);
        }
		else {
        
			$param = (object)[
				'TALAO_ID'					=> $request->TALAO_ID,
				'REMESSA_TALAO_DETALHE_ID'	=> $request->REMESSA_TALAO_DETALHE_ID,
				'TIPO'						=> $tipo,
				'ID'						=> $id,
				'QUANTIDADE'				=> $request->QUANTIDADE
			];

			_22010::registrarAproveitamento($param);
			
		}
    }
	
	/**
	 * Autenticar UP.
	 * @param Request $request
	 * @return json
	 */
	public function autenticarUp(Request $request) {
        
        /**
         * Verifica se a quantidade de caracteres do código de barras é diferente de 13
         */
        if ( strlen($request->up_barra) != 10 ) {
            log_erro('Código de barras inválido. COD:'. $request->up_barra);
        }
        
		$up_barra_obj = _22010::autenticarUp(['UP_BARRA' => strtoupper($request->up_barra)]);
        
		if( empty($up_barra_obj) ) {
			log_erro('UP inválida.');
		}
		else if( $up_barra_obj[0]->ID != $request->up_selecionada ) {
			log_erro('UP programada para o talão é diferente da UP autenticada.');
		}
		else {		
			return Response::json(true);
		}
		
	}
	
	/**
	 * Totalizadores diários.
	 * @param Request $request
	 * @return json
	 */
	public function totalizadorDiario(Request $request) {
		
		$param = (object)[];
		
		isset($request->estabelecimento_id) ? $param->ESTABELECIMENTO_ID	= $request->estabelecimento_id	: null;
		isset($request->gp_id)				? $param->GP_ID					= $request->gp_id				: null;
		isset($request->up_id)				? $param->UP_ID					= $request->up_id				: null;
		isset($request->up_todos)			? $param->UP_TODOS				= $request->up_todos			: null;
		isset($request->estacao)			? $param->ESTACAO				= $request->estacao				: null;
		isset($request->estacao_todos)		? $param->ESTACAO_TODOS			= $request->estacao_todos		: null;
		isset($request->data_ini)			? $param->DATA_INI				= $request->data_ini			: null;
		isset($request->data_fim)			? $param->DATA_FIM				= $request->data_fim			: null;
		isset($request->turno)				? $param->TURNO					= $request->turno				: null;
		isset($request->turno_hora_ini)		? $param->TURNO_HORA_INI		= $request->turno_hora_ini		: null;
		isset($request->turno_hora_fim)		? $param->TURNO_HORA_FIM		= $request->turno_hora_fim		: null;
		
        $res = _22010::totalizadorDiario($param);
		$ret = [];
		
        if ( !empty($request->retorno) && in_array('VIEW', $request->retorno) ) {
			
            $ret = [
				'VIEW' => view('ppcp._22010.index.totalizador-diario',[
                    'menu'					=> $this->menu,
                    'totalizador_diario'	=> $res,
                    'perfil_gp'				=> $request->_perfil_gp,
                    'ver_pares'				=> $request->ver_pares,
                    'ver_up_todos'			=> $request->up_todos
                ])->render(),
				'DADO' =>	$res
			];
        } 
        
        return Response::json($ret);		
	}
}