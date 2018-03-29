<?php

namespace App\Http\Controllers\Ppcp;

use PDF;
use App\Http\Controllers\Controller;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Ppcp\_22030;
use App\Models\DTO\Ppcp\_22040;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Exception;

class _22040Controller extends Controller
{
    /**
     * Código do menu Gestão de Remessas
     * @var int 
     */
    private $menu = 'ppcp/_22040';
    
    /**
     * reabrir talao 
     * @param Request $request 
     */
    public function reabrirTalao(Request $request)
    {   
        $param = $request->all();
        
        _22040::reabrirTalao($param);
        
        return self::show($request,$request->remessa_id);
    }
    
    /**
     * Tela principal
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {        
		$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'ppcp._22040.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);    
    }

    /**
     * Geração de remessas
     * @return type
     */
    public function create()
    {
		$permissaoMenu = _11010::permissaoMenu($this->menu,'INCLUIR');
        
		return view(
            'ppcp._22040.create', [
            'menu' => $this->menu
		]);    	
    }

    /**
     * Gravação da remessa
     * @param Request $request
     * @return type
     */
    public function store(Request $request)
    {   
		_11010::permissaoMenu($this->menu,'INCLUIR','Gravando Remessa');
        
        $param = (object)[];
        
        /**
         * Se for uma remessa de bojo atualiza para o campo TBREMESSA.WEB para '1'
         * (Remessa que será utilizada no gcweb)
         */
        if ( is_numeric($request->_remessa) ) {
            $param->MANIPULACAO_WEB = (object)['ID' => $request->_remessa];  
        }
        
        /**
         * Gera o Id da Remessa
         */
        $id = _22040::gerarId();
        
        $remessa = (object)[
            'ID'                 => $id,
            'ESTABELECIMENTO_ID' => $request->_estab,
            'FAMILIA_ID'         => $request->_familia,
            'PERFIL'             => $request->_perfil,
            'GP_ID'              => $request->_gp,
            'DATA_PRODUCAO'      => date('Y.m.d',strtotime($request->_data_producao)),
            'COMPONENTE'         => 1 //ESTE CAMPO SÓ SERA 1 ENQUANTO AS REMESSAS FOREM GERADAS A PARTIR DE OUTRAS REMESSAS
        ];
        
        if ( $request->_remessa == 'REP' ) { // Remessa de reposição
            $remessa->REMESSA    = 'RP' . $request->_estab . $request->_perfil . lpad(_22040::gerarReposicaoLaminacaoId(),5,'0');
            $remessa->COMPONENTE = 0;
        } else
        if ( $request->_requisicao == 1 && is_numeric($request->_remessa) ) { // Remessa de requisição de bojo
            $cod_remessa = $request->_remessa;
                        
            $remessa->REMESSA    = 'RQ' . $request->_estab . $request->_perfil . lpad($cod_remessa,6,'0');
            $remessa->TIPO       = '2';
            $remessa->REQUISICAO = '1';
        } else 
        if ( $request->_requisicao == 1 ) { // Outras remessas de requisição
            $cod_remessa = strstr($request->_remessa, 'RQ') ? substr($request->_remessa , 4) : _22040::gerarRequisicaoId();
            
            $remessa->REMESSA    = 'RQ' . $request->_estab . $request->_perfil . lpad($cod_remessa,6,'0');
            $remessa->TIPO       = '2';
            $remessa->REQUISICAO = '1';
            
            if ( $request->_remessa == 'REQ' ) {
                $remessa->COMPONENTE = 0;
            }
        } else 
        if ( strstr($request->_remessa, 'PD') && ! is_numeric(substr($request->_remessa , 2)) ) { // Remessa de pedido
            // Remessa vindo de remessa por pedido
            $remessa->REMESSA    = 'PD' . $request->_estab . $request->_perfil . lpad(substr($request->_remessa , 4),6,'0');
        } else
        if ( strstr($request->_remessa, 'PD') && is_numeric(substr($request->_remessa , 2)) ) { // Remessa de remessa de pedido
            // Remessa por pedido
            $remessa->REMESSA    = 'PD' . $request->_estab . $request->_perfil . lpad(substr($request->_remessa , 2),6,'0');
            $remessa->COMPONENTE = 0;
        } else {
            // Remessa vindo de remessa (compoente)
            $remessa_origem = is_numeric($request->_remessa) ? $request->_remessa : substr($request->_remessa , 2);
            $remessa->REMESSA = $request->_estab . $request->_perfil . $remessa_origem;
        }
             
        /**
         * Transforma o $request de talões em (object)array
         */
        $taloes_detalhe = [];
        $requisicoes    = [];
        $controle_seq = 1;
        foreach($request->_bloco_divisor as $key => $item) {
            $item_push = [];
            $item_push['ESTABELECIMENTO_ID'    ] = $request->_estab;
            $item_push['GP_ID'                 ] = $request->_gp;
            $item_push['UP_ID'                 ] = $request->_up              [$key];
            $item_push['ESTACAO'               ] = $request->_estacao         [$key];
            $item_push['DIVISOR'               ] = $request->_bloco_divisor   [$key];  
            $item_push['DIVISOR_TALAO'         ] = $request->_talao_divisor   [$key];  
            $item_push['PRODUTO_ID'            ] = $request->_prod_id         [$key];
            $item_push['MODELO_ID'             ] = $request->_modelo          [$key];
            $item_push['COR_ID'                ] = $request->_cor             [$key];
            $item_push['ESPESSURA'             ] = $request->_espessura       [$key];
            $item_push['DENSIDADE'             ] = $request->_densidade       [$key];
            $item_push['TAMANHO'               ] = $request->_tamanho         [$key];
            $item_push['LOCALIZACAO_ID'        ] = $request->_localizacao     [$key];
            $item_push['QUANTIDADE'            ] = $request->_qtd_prog        [$key]; 
            $item_push['QUANTIDADE_ALTERNATIVA'] = $request->_qtd_alternativa [$key]; 
            
            if ( !isset($request->_controle_seq[$key+1]) || ($request->_controle_seq[$key+1] != $request->_controle_seq[$key])) {
                $item_push['CONTROLE_SEQ'] = $controle_seq++;     
            } else {
                $item_push['CONTROLE_SEQ'] = $controle_seq;
            }

            array_push($taloes_detalhe, (object)$item_push);  
            
            /**
             * Se for uma remessa de requisicao captura o id das requiscoes
             */
            if ( isset($remessa->REQUISICAO) && $remessa->REQUISICAO == '1' ) {
                
                $requisicao_push = (object)[
                    'ID'            => $request->_remessa_talao_id[$key],
                    'REMESSA_ID'    => $id
                ];
                
                if(!in_array($requisicao_push, $requisicoes)){
                    array_push($requisicoes, $requisicao_push);  
                }

            }
        }
        
        /**
         * Transforma o $request de vinculos de consumo em (object)array
         */
        $consumo_vinculos = [];
        if ( isset($request->_consumo_ref) ) {
            foreach($request->_consumo_ref as $key => $item) {

                $item_push = [];
                $item_push['QUANTIDADE'            ] = $request->_consumo_qtd  [$key]; 
                $item_push['CONSUMO_ID'            ] = $request->_consumo_id   [$key]; 
                $item_push['REMESSA_ID'            ] = $id                           ;
                $item_push['REMESSA_TALAO_CONTROLE'] = $request->_consumo_ref  [$key];
                $item_push['DIVISOR_TALAO'         ] = $request->_consumo_talao[$key];

                array_push($consumo_vinculos, (object)$item_push);  
            }
        }

        /**
         * Transforma o $request de tempos em (object)array
         */
        $tempos = [];
        foreach($request->_tempo_talao as $key => $item) {
            $item_push = [];
            $item_push['TEMPO'                 ] = $request->_tempo_total[$key];
            $item_push['REMESSA_TALAO_CONTROLE'] = $request->_tempo_talao[$key];

            array_push($tempos, (object)$item_push);  
        }

        /**
         * Processa talões acumulados (separa talões acumulados dos talões detalhados)
         */
        $taloes_aux				= $taloes_detalhe;  //Clona o array do request de talões
        $taloes					= [];               //Inicializa o array dos talões
        $i						= 0;                //Inicializa o controle dos talões da remessa
        $talao_id				= 0;                //Inicializa o id dos talões acumulado
        $quantidade				= 0;                //Inicializa a quantidade dos talões
		$quantidade_alternativa = 0;				//Inicializa a quantidade alternativa dos talões
        $incrementa				= true;				//Incializa o incremento do id do talão
        foreach($taloes_detalhe as $item)
        {
            //Avança o ponteiro do clone
            $next = next($taloes_aux);
            
            //Verifica se deve gerar o id/controle do talão acumulado
            if ( $incrementa ) {
                $i++;
                $talao_id   = _22040::gerarTalaoId();
                $incrementa = false;
            }
            
            //Define id's dos talões acumulados e detalhados
            $item->REMESSA_ID               = $id;                           //Atribui o id da remessa ao talão acumulado
            $item->REMESSA_TALAO_ID         = $talao_id;                     //Atribui o id do talão acumulado
            $item->REMESSA_TALAO_CONTROLE   = $i;                            //Atribui o controle do talão referente a remessa  
            $item->REMESSA_TALAO_DETALHE_ID = _22040::gerarTalaoDetalheId(); //Atribui o controle do talão referente a remessa  
            $item->DATA_PRODUCAO            = $remessa->DATA_PRODUCAO; //Atribui o controle do talão referente a remessa  
            
            //Define os id's para vincular os consumos com os talões
            foreach ( $consumo_vinculos as $consumo )
            {
                if ( $consumo->DIVISOR_TALAO == $item->DIVISOR_TALAO )
                {
                    $consumo->REMESSA_TALAO_ID         = $talao_id; 
                    $consumo->REMESSA_TALAO_CONTROLE   = $i;        
                    $consumo->REMESSA_TALAO_DETALHE_ID = $item->REMESSA_TALAO_DETALHE_ID;
                }
            }
            
            //Soma a quantidade
            $quantidade = $quantidade + $item->QUANTIDADE;
			
			//soma a quantidade alternativa
			$quantidade_alternativa = $quantidade_alternativa + $item->QUANTIDADE_ALTERNATIVA;
            
            //Verifica se o próximo valor identificador é diferente do atual
            if ( empty($next) || $next->DIVISOR != $item->DIVISOR ) {
                
                //Define o tempo do talão acumulado
                foreach ( $tempos as $tempo )
                {
                    if ( $tempo->REMESSA_TALAO_CONTROLE == $item->DIVISOR )
                    {
                        $item->TEMPO = $tempo->TEMPO;
                    }
                }
                
                //Define o valor do talão acumulado
                $item->QUANTIDADE_TALAO				= $quantidade;
				
                //Define o valor do talão acumulado
                $item->QUANTIDADE_TALAO_ALTERNATIVA	= $quantidade_alternativa;
				
				//Define a quantidade da programação
				$item->QUANTIDADE_TALAO_PROGRAMACAO	= ($item->QUANTIDADE_TALAO_ALTERNATIVA > 0) ? $item->QUANTIDADE_TALAO_ALTERNATIVA : $item->QUANTIDADE_TALAO;
                
				//Adiciona o talão no array
                array_push($taloes, $item);   
                
                //Reseta a quantidade
                $quantidade				= 0;
				$quantidade_alternativa = 0;
                $incrementa				= true;
            }                    
        }

        $param->REMESSA                 = $remessa         ;  
        $param->REMESSA_TALAO           = $taloes          ;  
        $param->REMESSA_TALAO_DETALHE   = $taloes_detalhe  ;  
        $param->REMESSA_CONSUMO_VINCULO = $consumo_vinculos;  
        $param->REQUISICAO              = $requisicoes     ;  
        
        return Response::json(_22040::gravar($param));
    }

    /**
     * Exibição de uma remessa
     * @param Request $request
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function show(Request $request,$id)
    {
		$permissaoMenu  = _11010::permissaoMenu($this->menu,'Visualizando remessa ' . $id);
        
        /**
         * Parametros da consulta
         */
        $param  = ['RETORNO'    => ['REMESSA','TALAO','TALAO_DETALHE','REMESSA_FAMILIA_CONSUMO']];    
        $param += ['REMESSA_ID' => $id];

        /**
         * Realiza a consulta
         */
        $dados = _22040::listar($param);

        if ( !isset($dados->REMESSA[0]) ) {
            log_erro('Remessa inexistente.');
        }  
        
        orderBy($dados->TALAO, 'UP_DESCRICAO', 'DATAHORA_INICIO');
        
        /**
         * Retira o array de UP/ESTACAO da consulta
         */
        $arr_up      = [];
        $arr_estacao = [];
        $arr_aux     = $dados->TALAO;
        foreach($dados->TALAO as $item){

            $next = next($arr_aux);

            if ( empty($next) || $next->UP_ID != $item->UP_ID ) {
                array_push($arr_up, (object)[
                    'ID'        => $item->UP_ID,
                    'DESCRICAO' => $item->UP_DESCRICAO
                ]);   
            }       

            if ( empty($next) || $next->ESTACAO != $item->ESTACAO || $next->UP_ID != $item->UP_ID ) {
                array_push($arr_estacao, (object)[
                    'ESTACAO'           => $item->ESTACAO,
                    'ESTACAO_DESCRICAO' => $item->ESTACAO_DESCRICAO,
                    'UP_ID'             => $item->UP_ID,
                ]);   
            }                
        }          
        
        $view  = strripos($request->url(), 'show') ? 'ppcp._22040.show.body' : 'ppcp._22040.show.body';
        
        $param = [
            'remessa'					=> $dados->REMESSA[0],
            'taloes'					=> $dados->TALAO,
            'taloes_detalhe'			=> $dados->TALAO_DETALHE,
            'remessa_familia_consumo'	=> $dados->REMESSA_FAMILIA_CONSUMO,
            'ups'						=> $arr_up,
            'estacoes'					=> $arr_estacao,
            'permissaoMenu'				=> $permissaoMenu,
            'menu'						=> $this->menu
		];
        
		return view($view,$param);
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
    
    /**
     * Executa listagem principal
     */
    public function filtrar(Request $request)
    {        
        /**
         * Retorno da consulta
         */
        $param = [
            'RETORNO'    => ['REMESSA'], 
            'NOT_PERFIL' => 'B'
        ];
        
        /**
         * Valida a data
         */
        $data = $request->data;
        if ( strtotime(str_replace('.', '-', $data[0])) > strtotime(str_replace('.', '-', $data[1])) ) {
            log_erro('Período inválido! Data inicial maior que a final');
        }       
        
        /**
         * Prepara os parametros da consulta
         */
        $param = $request->qtd_por_pagina     ? $param + ['FIRST'             => $request->qtd_por_pagina    ] : $param;
        $param = $request->pagina             ? $param + ['SKIP'              => $request->pagina            ] : $param;
        $param = $request->estabelecimento_id ? $param + ['ESTABELECIMENTO_ID'=> $request->estabelecimento_id] : $param;
        $param = $request->familia_id         ? $param + ['FAMILIA_ID'        => $request->familia_id        ] : $param;
        $param = $request->remessa_id         ? $param + ['REMESSA_ID'        => $request->remessa_id        ] : $param;
        $param = $request->remessa            ? $param + ['REMESSA'           => $request->remessa           ] : $param;
		$param = $request->perfil             ? $param + ['PERFIL'			  => $request->perfil            ] : $param;
        $param = $request->status >=0         ? $param + ['STATUS'            => $request->status            ] : $param;
        $param = $request->filtro             ? $param + ['FILTRO'            => $request->filtro            ] : $param;
        $param = ($data[0] && $data[1])       ? $param + ['DATA'              => $data                       ] : $param;

        /**
         * Realiza a consulta
         */
        $dados = _22040::listar($param)->REMESSA;
        
        /**
         * Verifica se o parametro "$request->retorno" solicita o retorno 
         * em formato de view, se não retorna os dados em um array.
         */
        if ($request->retorno == 'view') {
            $ret = view('ppcp._22040.index.filtrar', ['dados' => $dados]);
        } else {
            /**
             * Verifica se é uma chamada ajax para retornar em json
             */
            $ret = $request->ajax() ? Response::json($dados) : $dados;
        }
        
        return $ret;
    }

    /**
     * Abre informações do consumo da remessa
     * @param Request $request
     * @return type
     */
    public function remessaConsumo(Request $request)
    {   
        $param = [];
        
        $detalhe = $request->detalhe;
        array_push($detalhe, 'PERFIL');
		        
        !isset($request->estabelecimento_id) ?: $param += ['ESTABELECIMENTO_ID' => $request->estabelecimento_id];
        !isset($request->remessa           ) ?: $param += ['REMESSA'            => $request->remessa           ];
        !isset($request->remessa_id        ) ?: $param += ['REMESSA_ID'         => $request->remessa_id        ];
        !isset($request->familia           ) ?: $param += ['FAMILIA_ID'         => $request->familia           ];
        !isset($request->requisicao        ) ?: $param += ['REQUISICAO'         => $request->requisicao        ];
        $param += ['DETALHE'  => $detalhe];
                
        $ret = _22040::remessaConsumo($param);                 
        
        return $ret;
    }
    
    /**
     * Abre itens necessários para geração da remessa do consumo
     * @param Request $request
     * @return type
     */
    public function remessaConsumoCarga(Request $request)
    {    
        /**
         * Abre informações da remessa de consumo
         */
        $consumo = $this->remessaConsumo($request);
        /**
         * Consumo da Remessa
         */
		$remessa = $consumo->NECESSIDADE;
        
        /**
         * Abre informações das UP's / ESTAÇÕES do grupo de produção da remessa
         */
        $up_estacoes = _22030::listar([
            'RETORNO'   => ['GP_UP_ESTACAO'],
            'STATUS'    => [1],
            'UP_STATUS' => [1],
            'GP'        => [$request->gp],
            //'PERFIL'  => $consumo->PERFIL Todas estações deverão vir na consulta independente do perfil, por esse motivo essa linha está comentada.
        ])->GP_UP_ESTACAO;
        
        /**
         * Retira o array de UP da consulta de UP/ESTACAO
         */
        $arr_up = [];
        $up_estacoes_aux = $up_estacoes;

        foreach($up_estacoes as $item){

            $next = next($up_estacoes_aux);

            if ( empty($next) || $next->UP_ID != $item->UP_ID ) {
                array_push($arr_up, (object)[
                    'ID'        => $item->UP_ID,
                    'DESCRICAO' => $item->UP_DESCRICAO,
                    'STATUS'    => $item->UP_STATUS
                ]);   
            }                    
        }   

        return (object)[
            'REMESSA'     => $remessa,
            'UPS'         => $arr_up,
            'UP_ESTACOES' => $up_estacoes,
        ];
    }
    
    public function remessaGerarAuto(Request $request) {
        
        $request->detalhe = ['NECESSIDADE'];
        $ret = $this->remessaConsumoCarga($request);
        print_r($ret);
        exit;
    }

    public function pesqRemessa(Request $request) {
		
		$ret = $this->remessaConsumo($request);
		
		return $request->ajax() ? Response::json($ret) : $ret;
	}
	
	/**
	 * Verifica se a remessa a ser gerada já existe.
	 * @param Request $request
	 * @return array
	 */
	public function verificarRemessaExiste(Request $request) {
		
		$remessa = $request->remessa;
		
		if( $remessa !== 'REP' && $remessa !== 'REQ' ) {
			
			$ret =	_22040::verificarRemessaExiste([
						'REMESSA'	=> is_numeric($remessa) ? $remessa : substr($remessa, 2),
						'GP_ID'		=> $request->gp_id
					]);

			if( isset($ret[0]) ) {
//				log_erro('Remessa '. $ret[0]->REMESSA .' já existe.');
			}
			
		}
		
		return Response::json(false);
		
	}
	
	public function showNecessidade(Request $request) {

        //Realiza a carga dos dados principais para geração de remessa
        $ret = $this->remessaConsumoCarga($request);
        
        //Carrega os itens da projeção de consumo
        $param = (object) $request->all(); 
		
        $param->detalhe        = ['CONSUMO'];
        $param->status_consumo = [0];

        $consumo = _22040::remessaConsumo($param)->CONSUMO;
        
        orderBy($consumo,'DENSIDADE','ESPESSURA','TALAO_PRODUTO_ID','TALAO_TAMANHO', 'REMESSA_TALAO_ID');
        
        
        if ( !isset($ret->REMESSA[0]) ) {
            log_erro('Não há itens para a geração da remessa.');
        }
        
        if ( !isset($ret->UPS[0]) ) {
            log_erro('GP sem UP/Estações criadas ou configuradas com a familía/perfil da remessa.');
        }
        
		return view(
			'ppcp._22040.create.necessidade', [
				'remessa'			=> $ret->REMESSA,
				'ups'				=> $ret->UPS,
                'estacoes'			=> $ret->UP_ESTACOES,
				'menu'				=> $this->menu,
                'consumos'			=> $consumo,
				'um_alternativa'	=> $request->um_alternativa
			]
		);
	}
	
	public function tempo(Request $request)
    {   /**
         * Transforma o $request de modelos em (object)array
         */
        $tempo_param	= [];
		$ret			= [];
		$array_tam		= count($request->bloco);
		$i				= 0;
		$tempo			= [];
		$erros			= "Os seguintes produtos estão sem fluxo produtivo alimentado ou configurado:<br/>\n";
		$possui_erro	= false;
		
        foreach($request->bloco as $key => $item) {

            $item_push = [];
            $item_push['BLOCO'     ] = $request->bloco    [$key];
            $item_push['UP_ID'     ] = $request->_up      [$key];
            $item_push['MODELO_ID' ] = $request->_modelo  [$key];
            $item_push['COR_ID'    ] = $request->_cor     [$key];
            $item_push['TAMANHO'   ] = $request->_tamanho [$key];
            $item_push['QUANTIDADE'] = $request->_qtd_prog[$key];

            array_push($tempo_param, (object)$item_push);

			//se o bloco atual for diferente do próximo, deve processar o tempo
			if ( ($i === $array_tam-1) || ($request->bloco[$key] !== $request->bloco[$key+1]) ) {

				$tempo =	
                _22040::remessaProgramacao([
                    'RETORNO'     => ['TEMPO'],
                    'PARAM_TEMPO' => $tempo_param
                ])->TEMPO['0'];
				
				array_push(
					$ret, 
					$tempo
				);
				
				//verifica se existe tempo cadastrado para o bloco
				if ($tempo->TOTAL <= 0) {
					
					$possui_erro = true;
					$erros .= "MODELO: "	. $request->_modelo[$key] . " - " .  $request->modelo_desc[$key]
								.  " / "	. $request->up_desc[$key] . "<br/>";
				}
				
				$tempo_param = [];
				
			}
			
			$i++;
        }
		
		if ( $possui_erro === true ) {
			log_erro($erros);
		}
        elseif ( $request->ajax() )
        {
            $ret = Response::json($ret);
        }
        
        return $ret;
	}
    
    public function getPdfConsumo(Request $request)
    {
//    	_11010::permissaoMenu($this->menu);

        $remessa = _22040::listar([
            'RETORNO'    => ['REMESSA'],
            'REMESSA_ID' => $request->remessa_id
        ])->REMESSA;
        
        if ( !isset($remessa[0]) ) {
            log_erro('Remessa inválida ou inexiste!');
        }
		
        $consumos = _22040::remessaConsumo([
            'REMESSA_ID'		=> $request->remessa_id,
            'FAMILIA_ID_CONSUMO'=> $request->familia_id_consumo,
            'DETALHE'			=> ['CONSUMO']
        ])->CONSUMO;   
        
        $file_name = 'CONSUMO-REMESSA-' . $remessa[0]->REMESSA . '.pdf';
        $path_file = '/assets/temp/relatorios/';
        
        //caminho e nome do arquivo
        $arq_temp = public_path().$path_file.$file_name;

        //apaga arquivos de relatórios antigos
        deleleFilesTree(public_path().$path_file);
        
        //apagar arquivo, caso já exista
        if(file_exists($arq_temp) ) {
            unlink($arq_temp);
        }
        
        PDF::setPaper('A4','portrait')
            ->loadView('ppcp._22040.show.pdf.consumo.2_body', ['consumos'   => $consumos])  
            ->setOption('header-html',view('ppcp._22040.show.pdf.consumo.1_header', [
                'menu' => $this->menu,
                'remessa' => $remessa[0]
            ]),'html')     
            ->setOption('footer-html',view('ppcp._22040.show.pdf.consumo.3_footer'),'html')   
            ->save($arq_temp)
        ;  
        
        if ( $request->isMethod('post') ) {
            return $path_file.$file_name;
        } else {
            return view('ppcp._22040.show.pdf.consumo.2_body', ['consumos' => $consumos]);
        }
    }
    
    public function atualizarCotaCliente(Request $request)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Alterando Cota de Talão por Cliente');

        validator($request->all(), [
            'modelo_id'         => ['Modelo'            ,'required'     ],
            'cliente_id'        => ['Cliente'           ,'required'     ],
            'quantidade_cota'   => ['Quantidade Cota'   ,'required'     ],
        ],true);
                
        _22040::atualizarCotaCliente($request->all());
    }
}
