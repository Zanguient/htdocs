<?php

namespace app\Http\Controllers\Ppcp;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Ppcp\_22130;
use App\Models\DTO\Admin\_11010;
use App\Models\Socket\Socket;
use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Cache;


/**
 * Controller do objeto _22130 - Conformacao
 */
class _22130Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'ppcp/_22130';
    
    public function index()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $dados = [];

        $filtro = \Cache::get(\Auth::user()->USUARIO . '22130');

        $ESTAB          = '';
        $GP             = '';
        $GPDESC         = '';
        $UP             = '';
        $UPDESC         = '';
        $ESTACAO        = '';
        $ESTACAODESC    = '';
        $SELECIONADO    = 0;

        if(count($filtro) > 0){
            $ESTAB          = $filtro['ESTABELECIMENTO'];
            $GP             = $filtro['GP_ID'];
            $GPDESC         = $filtro['GP_DESCRICAO'];
            $UP             = $filtro['UP_ID'];
            $UPDESC         = $filtro['UP_DESCRICAO'];
            $ESTACAO        = $filtro['ESTACAO_ID'];
            $ESTACAODESC    = $filtro['ESTACAO_DESCRICAO'];
            $SELECIONADO    = 1;
        }

        return view(
            'ppcp._22130.index', [
            'permissaoMenu'   => $permissaoMenu,
            'menu'            => $this->menu,
            'dados'           => $dados,
            'ESTAB'           => $ESTAB,
            'GP'              => $GP,
            'GPDESC'          => $GPDESC,
            'UP'              => $UP,
            'UPDESC'          => $UPDESC,
            'ESTACAO'         => $ESTACAO,
            'ESTACAODESC'     => $ESTACAODESC,
            'selecionado'     => $SELECIONADO
        ]);
          
    }
    
    public function auto($ESTAB,$GP,$GPDESC,$UP,$UPDESC,$ESTACAO,$ESTACAODESC)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        $dados = [];

        return view(
            'ppcp._22130.index', [
            'permissaoMenu'   => $permissaoMenu,
            'menu'            => $this->menu,
            'dados'           => $dados,
            'ESTAB'           => $ESTAB,
            'GP'              => $GP,
            'GPDESC'          => $GPDESC,
            'UP'              => $UP,
            'UPDESC'          => $UPDESC,
            'ESTACAO'         => $ESTACAO,
            'ESTACAODESC'     => $ESTACAODESC,
            'selecionado'     => 1
        ]);  
    }

    public function getProducao(Request $request)
    {   

        $con = new _Conexao();

        $filtro = $request->all();

        $producao  = _22130::getProducao($filtro,$con);
        $paradas_a = _22130::getParadas_a($filtro,$con);
        $paradas_s = _22130::getParadas_s($filtro,$con);

        $con->commit();

        $dados = (object) array(
                    'PRODUCAO'          => $producao,
                    'PARADAS_A'         => $paradas_a,
                    'PARADAS_S'         => $paradas_s,
                );

        return Response::json($dados);
    }

    public function getProducaoTalao(Request $request)
    {   

        $con = new _Conexao();

        $filtro = $request->all();

        $talao = $this->Taloes($filtro,true);

        $con->commit();

        return Response::json($talao);
    }

    public function consultarMatriz(Request $request)
    {       

        $dados = $request->all();

        $ferramenta = $dados['FERRAMENTA_ID'];
        $cod_barras = $dados['MATRIZ_BARRAS'];

        $matriz = _22130::getMatriz($dados);

        if(count($matriz) > 0){

            $validacao = true;
            $mensagem  = '';

        }else{

            $validacao = false;
            $mensagem  = 'Ferramenta não é a ferramenta programada para este talão, Ferramenta:'.$ferramenta.' Cod. Barras:'.$cod_barras;

        }

        $ret = [
            'VALIDACAO' => $validacao,
            'MENSAGEM'  => $mensagem
        ];

        return Response::json($ret);
    }

    public function getUp(Request $request)
    {   
        $dados = $request->all();

        $dados['FILTRO'] = '';
        $dados['FAMILIA'] = '3';
        $dados['UP'] = '1';

        $retorno = _22130::getConformacao($dados);
        return Response::json($retorno);
    }

    public function getSubUp(Request $request)
    {
        $dados = $request->all();

        $dados['FILTRO'] = '';
        $dados['FAMILIA'] = '3';
        $dados['UP'] = '1';

        $retorno = _22130::getMaquina($dados);
        return Response::json($retorno);
    }

    public function consulta (Request $request){

        $dados = $request->all();

        return view($dados['CONSULTA'],
        [   
            'dados' => []
        ]);

    }

    function atualizarTalao(Request $request){

        validator($request->all(), [
            'DATA_FINAL'       => ['Data Inicial'       , 'required|date'  ],
            'DATA_INICIAL2'    => ['Data Final'         , 'required|date'  ],
            'DATA_FINAL2'      => ['Data Inicial'       , 'required|date'  ],
            'DATA_INICIAL'     => ['Data Final'         , 'required|date'  ],
            'ESTABELECIMENTO'  => ['Estabelecimento'    , 'required|min:0' ],
            'GP_ID'            => ['GP'                 , 'required|min:0' ],
            'UP_ID'            => ['UP'                 , 'required|min:0' ],
            'TALAO_ID'         => ['Talão ID'           , 'required|min:0' ]
        ],true);

        $filtro = $request->all();

        $con = new _Conexao();

        $talao = _22130::getTaloes_producao($filtro,$con);

        $con->commit();

        return  Response::json($talao);

    }

    function filtarTaloes(Request $request){

        $filtro = $request->all();

        Cache::forever(\Auth::user()->USUARIO . '22130', $filtro);

        $taloes = $this->Taloes($filtro,false);

        return Response::json($taloes);

    }

    function pararEstacao(Request $request){

        $con    = new _Conexao();
        $filtro = $request->all();

        $status = _22130::pararEstacao($filtro,$con);

        $con->commit();

        log_info('Estação foi parada');

        return 0;
    }

    function Taloes($filtro,$separar = false){

        set_time_limit(300);

        validator($filtro, [
            'DATA_FINAL'       => ['Data Inicial'       , 'required|date'  ],
            'DATA_INICIAL'     => ['Data Final'         , 'required|date'  ],
            'DATA_FINAL2'      => ['Data Inicial'       , 'required|date'  ],
            'DATA_INICIAL2'    => ['Data Final'         , 'required|date'  ],
            'ESTABELECIMENTO'  => ['Estabelecimento'    , 'required|min:0' ],
            'GP_ID'            => ['GP'                 , 'required|min:0' ],
            'UP_ID'            => ['UP'                 , 'required|min:0' ]
        ],true);

        $con = new _Conexao();

        $filtro['DATA_INICIAL'] = date_format(date_create($filtro['DATA_INICIAL']) , 'd.m.Y');
        $filtro['DATA_FINAL']   = date_format(date_create($filtro['DATA_FINAL'])   , 'd.m.Y');

        $filtro['DATA_INICIAL2'] = date_format(date_create($filtro['DATA_INICIAL2']) , 'd.m.Y');
        $filtro['DATA_FINAL2']   = date_format(date_create($filtro['DATA_FINAL2'])   , 'd.m.Y');

        $metas                  = _22130::getMeta($filtro,$con);

        $turno                  = _22130::getTurnos($filtro,$con);

        $tempo_producao         = _22130::getTempo($filtro,$con);

        $eficiencia_t           = _22130::getEficiencia_t($filtro,$con);
        $producao_t             = _22130::getProducao_t($filtro,$con);
        $perdas_t               = _22130::getPerdas_t($filtro,$con);
        $metas_t                = _22130::getMeta_t($filtro,$con);

        $eficiencia_g           = _22130::getEficiencia_g($filtro,$con);
        $producao_g             = _22130::getProducao_g($filtro,$con);
        $perdas_g               = _22130::getPerdas_g($filtro,$con);
        $metas_g                = _22130::getMeta_g($filtro,$con);

        $estacoes               = _22130::getEstacoes($filtro,$con);

        $taloes_producao        = _22130::getTaloes_producao($filtro,$con);

        $parada_estacao         = _22130::consultaJustificativa('A',$con);
        $parada_talao           = _22130::consultaJustificativa('B',$con);
        $motivo_talao           = _22130::consultaJustificativa('C',$con);

        $taloes_parados1        = [];
        $taloes_parados2        = [];

        $producao = [
            'META'         => $metas,
            'META_T'       => $metas_t,
            'META_G'       => $metas_g,
            'TEMPO_PROD'   => $tempo_producao,
            'PERDA_T'      => $perdas_t,
            'PERDA_G'      => $perdas_g,
            'EFICIENCIA_T' => $eficiencia_t,
            'EFICIENCIA_G' => $eficiencia_g,
        ];

        $contador        = 0;
        foreach ($estacoes  as $key => $estacao) {

            $estacao->TALOES        = [];
            $cont = 0;

            foreach ($taloes_producao as $key => $talao) {
                $cont++;
                $segundos = [];

                $talao->QUANTIDADE_EXTRA       = 0;
                $talao->REMESSA_TALAO_ID_EXTRA = 0;
                $talao->REMESSA_ID_EXTRA       = 0;
                $talao->STATUS_EXTRA           = 0;
                $talao->HORA_PRODUCAO_EXTRA    = 0;
                $talao->HORA_LIBERACAO_EXTRA   = 0;

                if($talao->EXTRA != ''){
                    $arr =  explode("#@#",$talao->EXTRA);
                    if(count($arr) > 0){
                        $talao->QUANTIDADE_EXTRA       = $arr[0];
                        $talao->REMESSA_TALAO_ID_EXTRA = $arr[1];
                        $talao->REMESSA_ID_EXTRA       = $arr[2];
                        $talao->STATUS_EXTRA           = $arr[3];
                        $talao->HORA_PRODUCAO_EXTRA    = $arr[4];
                        $talao->HORA_LIBERACAO_EXTRA   = $arr[5];
                    }
                }

                //quebra as strings de requisicao em um array de objetos apenas no primeiro tratamento
                if($contador == 0){

                    //requisicao
                    $sp = explode("#",$talao->REQUISICAO);
                    $REQUISICAO = [];

                    $menor_status    = 999;
                    $soma_requisicao = 0;
                    foreach ($sp as $key => $value) {
                        $item =  explode("|",$value);

                        $status = '';
                        if($item[2] == 1){$status = 'NÃO FOI CORTADO';}
                        if($item[2] == 2){$status = 'FOI CORTADO E NÃO FOI ABASTECIDO';}
                        if($item[2] == 3){$status = 'FOI CORTADO E ABASTECIDO';}

                        if($item[2] < $menor_status ){$menor_status = $item[2];}

                        $soma_requisicao = $soma_requisicao + ($item[7] - $item[3]);
                        
                        $item = (object) array(
                            'REMESSA'       => $item[0],
                            'TALAO'         => $item[1],
                            'STATUS'        => $item[2],
                            'QUANTIDADE'    => $item[7] - $item[3],
                            'ID'            => $item[6],
                            'DESC_STATUS'   => $status
                        );

                        array_push($REQUISICAO,$item);
                    }

                    if($talao->SEGUNDOS != ''){
                        $a = explode('|',$talao->SEGUNDOS);
                        foreach ($a as $key => $linha) {
                            $HORA   = '';
                            $STATUS = '';

                            $b = explode('#',$linha);
                            $HORA   = $b[0];
                            $STATUS = $b[1];

                            $item = (object) array('HORA' => $HORA, 'STATUS' => $STATUS);

                            array_push($segundos,$item);
                        }    
                        
                        $talao->SEGUNDOS = $segundos;
                    }else{
                        $talao->SEGUNDOS = [];    
                    }

                    $talao->REQUISICAO = (object) array( 'ITENS' => $REQUISICAO, 'STATUS' => $menor_status);
                    $talao->QTD_REQUISICAO = $soma_requisicao;
                    $talao->REQUISICAO_STATUS = $menor_status;

                    //setup
                    $st = explode("#",$talao->SETUP);
                    $talao->SETUP = [[],[],[],'STATUS'=>0];
                    $SETUP = [];

                    $soma_setap1 = 0;
                    $soma_setap2 = 0;
                    $soma_setap3 = 0;
                    $soma_setap4 = 0;

                    $item1 = (object) array(
                        'DESCRICAO'         => 'TROCA DE FERRAMENTA',
                        'ID'                => 0,
                        'SETUP_ID'          => 1,
                        'SETUP_ID2'         => 2,
                        'DATAHORA_INICIO'   => '',
                        'TEMPO_DECORRIDO'   => $soma_setap1,
                        'FINALIZADO'        => 1,
                        'ULTIMO'            => 1,
                        'SETUP'             => $talao->TEMPO_SETUP_FERRAMENTA
                    );

                    $item2 = (object) array(
                        'DESCRICAO'         => 'LIMP. e AQUEC. DE FERRAMENTA',
                        'ID'                => 0,
                        'SETUP_ID'          => 2,
                        'SETUP_ID2'         => 3,
                        'DATAHORA_INICIO'   => '',
                        'TEMPO_DECORRIDO'   => $soma_setap2,
                        'FINALIZADO'        => 1,
                        'ULTIMO'            => 1,
                        'SETUP'             => ($talao->TEMPO_SETUP_AQUECIMENTO + $talao->TEMPO_SETUP_COR) 
                    );

                    $item3 = (object) array(
                        'DESCRICAO'         => 'APROVAÇÃO DE COR',
                        'ID'                => 0,
                        'SETUP_ID'          => 3,
                        'SETUP_ID2'         => 4,
                        'DATAHORA_INICIO'   => '',
                        'TEMPO_DECORRIDO'   => $soma_setap3,
                        'FINALIZADO'        => 1,
                        'ULTIMO'            => 1,
                        'SETUP'             => $talao->TEMPO_SETUP_APROVACAO
                    );

                    foreach ($st as $key => $value) {
                        $item =  explode("|",$value);

                        //ID|SETUP_ID|DATAHORA_INICIO|TEMPO|FIM#
                        
                        if($item[1] == 1){
                            $soma_setap1 = $item1->TEMPO_DECORRIDO + $item[3];
                            if($item1->FINALIZADO < $item[4]){$item[4]=$item1->FINALIZADO;}

                            $item1->ID              = $item[0];
                            $item1->SETUP_ID        = $item[1];
                            $item1->DATAHORA_INICIO = $item[2];
                            $item1->TEMPO_DECORRIDO = $soma_setap1;
                            $item1->FINALIZADO      = $item[4];
                        }
                        if($item[1] == 2){
                            $soma_setap2 = $item2->TEMPO_DECORRIDO + $item[3];
                            if($item2->FINALIZADO < $item[4]){$item[4]=$item2->FINALIZADO;}
                            
                            $item2->ID              = $item[0];
                            $item2->SETUP_ID        = $item[1];
                            $item2->DATAHORA_INICIO = $item[2];
                            $item2->TEMPO_DECORRIDO = $soma_setap2;
                            $item2->FINALIZADO      = $item[4];
                        }
                        if($item[1] == 3){
                            $soma_setap3 = $item3->TEMPO_DECORRIDO + $item[3];
                            if($item3->FINALIZADO < $item[4]){$item[4]=$item3->FINALIZADO;}
                            
                            $item3->ID              = $item[0];
                            $item3->SETUP_ID        = $item[1];
                            $item3->DATAHORA_INICIO = $item[2];
                            $item3->TEMPO_DECORRIDO = $soma_setap3;
                            $item3->FINALIZADO      = $item[4];
                        }

                    }

                    if($item2->SETUP > 0){$item1->ULTIMO =0;}
                    if($item3->SETUP > 0){$item2->ULTIMO =0; $item1->ULTIMO =0;}

                    $item1->ANTERIOR = 1;
                    $item2->ANTERIOR = $item1->FINALIZADO;
                    $item3->ANTERIOR = $item2->FINALIZADO;

                    $contSetups = 0;
                    if($item1->SETUP > 0){$contSetups++;}
                    if($item2->SETUP > 0){$contSetups++;}
                    if($item3->SETUP > 0){$contSetups++;}

                    $somaFinal = $item1->FINALIZADO + $item2->FINALIZADO + $item3->FINALIZADO;

                    $talao->SETUP[0] = $item1;
                    $talao->SETUP[1] = $item2;
                    $talao->SETUP[2] = $item3;

                    if($contSetups == $somaFinal){
                        $talao->SETUP['STATUS'] = $somaFinal;
                    }else{
                        $talao->SETUP['STATUS'] = $somaFinal;    
                    }

                }


                if($estacao->ESTACAO == $talao->ESTACAO){

                    if($separar){
                        $talao->ORDEM = count($estacao->TALOES);
                        array_push($estacao->TALOES,$talao);   
                    }else{
                        if($talao->STATUS_REQUISICAO == 1 && $talao->PROGRAMACAO_STATUS == 1){

                            if(count($taloes_parados1) < 6){
                                array_push($taloes_parados1,$talao);
                            }else{
                                array_push($taloes_parados2,$talao);
                            }

                        }else{
                            $talao->ORDEM = count($estacao->TALOES);
                            array_push($estacao->TALOES,$talao);    
                        }
                    }
                }

            }
            
            if(count($estacao->TALOES) < 6){
                for ($i=count($estacao->TALOES); $i < 7; $i++) { 
                    array_push($estacao->TALOES,['REQUISICAO_STATUS'=>0]);
                }
            }

            $contador++;
        }

        if(count($taloes_parados1) < 6){
            for ($i=count($taloes_parados1); $i < 7; $i++) { 
                array_push($taloes_parados1,['REQUISICAO_STATUS'=>0]);
            }
        }

        if(count($taloes_parados2) < 6){
            for ($i=count($taloes_parados2); $i < 7; $i++) { 
                array_push($taloes_parados2,['REQUISICAO_STATUS'=>0]);
            }
        } 

        if(count($estacoes) == 0){

            $estacao = (object) array(
                'TALOES'        => []
            );

            for ($i=count($estacao->TALOES); $i < 7; $i++) { 
                array_push($estacao->TALOES,['REQUISICAO_STATUS'=>0]);
            }

            array_push($estacoes,$estacao);
        }   

        $con->commit();

        $ret = [
            'ESTACOES'      => $estacoes,
            'PARADOS1'      => $taloes_parados1,
            'PARADOS2'      => $taloes_parados2,
            'JUST_ESTACAO'  => $parada_estacao,
            'JUST_TALAO'    => $parada_talao,
            'JUST_INEFC'    => $motivo_talao,
            'PRODUCAO'      => $producao,
            'TURNO'         => $turno
        ];

        return  $ret;

    }

    public function justIneficiencia(Request $request){

        $con    = new _Conexao();
        $filtro = $request->all();

        $status = _22130::justIneficiencia($filtro,$con);

        $con->commit();

        return $status;
    }

    public function getComposicao(Request $request){

        $con    = new _Conexao();
        $filtro = $request->all();

        $ret = _22130::getComposicao($filtro,$con);

        return $ret;
    }    

    

    public function getInfoTalao(Request $request){

        $dados = $request->all();
        $con   = new _Conexao();

        $PEDIDOS    = _22130::pedidosTalao($dados,$con);
        $ESPUMA     = _22130::espumaTalao($dados,$con);
        $MATRIZ     = _22130::matrizTalao($dados,$con);
        $TALAO      = _22130::dadosTalao($dados,$con);
        $SKU        = _22130::skuTalao($dados,$con);
        $TECIDO     = _22130::tecidoTalao($dados,$con);
        $COMPON     = _22130::getComponentes($dados,$con);
        $HISTORICO  = _22130::getHistoricoTalao($dados,$con);

        $ret = [
            'PEDIDOS'       => $PEDIDOS,
            'ESPUMA'        => $ESPUMA,
            'MATRIZ'        => $MATRIZ,
            'TALAO'         => $TALAO,
            'SKU'           => $SKU,
            'TECIDO'        => $TECIDO,
            'COMPONENTE'    => $COMPON,
            'HISTORICO'     => $HISTORICO 
        ];

        return Response::json($ret);
    }

    public function jornadaIntervalo(Request $request){

        $con = new _Conexao();

        $dados = $request->all();
        $ret   = _22130::jornadaIntervalo($dados,$con);

        $con->commit();

        return Response::json($ret);

    }

    public function jornadaGravar(Request $request){

        $dados = $request->all();
        _22130::jornadaGravar($dados);

        return 0;

    }

    public function trocarFerramenta(Request $request){

        $con = new _Conexao();

        $dados = $request->all();
        $ret   = _22130::trocarFerramenta($dados,$con);

        $con->commit();

        $taloes = $this->Taloes($dados['FILTRO']);

        $ret = [
            'INFO_STATUS'   => 1,
            'INFO_MENSAGE'  => 'Troca de Ferramenta efetuada!.',
            'DADOS'         => $taloes,
        ];

        log_info('Troca de ferramenta do talão de programação ID:'.$dados['TALAO']['PROGRAMACAO_ID']);

        return Response::json($ret);

    }

    public function ferramentasLivres(Request $request){

        $con = new _Conexao();

        $dados = $request->all();
        $ret   = _22130::ferramentasLivres($dados,$con);

        return Response::json($ret);
    } 

    public function acoesTaloes(Request $request){

        $dados = $request->all();
 
        $info_status  = 0;
        $info_mensage = '';

        $con = new _Conexao();

        switch ($dados['ACAO']) {
            case 'INICIAR':
                if($dados['TALAO']['FERRAMENTA_ID'] > 1 && $dados['TALAO']['TALAO_ENCERRADO'] == 0){
                    $info_mensage = 'Talão Iniciado';
                        _22130::iniciarTalao($dados,$con,1);
                    $info_status  = 1;
                }else{
                    $info_mensage = 'Talão finalizado automaticamente';
                        _22130::iniciarTalao($dados,$con,0);
                        _22130::finalizarTalao($dados,$con,1);
                    $info_status  = 1;    
                }
                break;
            case 'PAUSAR':
                $info_mensage = 'Talão Parado';
                    _22130::pararTalao($dados,$con,1);
                $info_status  = 1;
                break;

            case 'FINALIZAR':
                $info_mensage = 'Talão Finalizado';
                    _22130::finalizarTalao($dados,$con,1);
                $info_status  = 1;
                break;

            case 'TROCAR':
                
                $date  = date_create($dados['TALAO']['HORA_TALAO_ANTERIOR']);
                $dataf = date_format($date, 'd.m.Y H:i:s');
                $dataf = '\''.$dataf.'\'';

                $info_mensage = 'Troca de estação efetuada, Data/Hora:'.$dataf.'';

                
                    _22130::trocarEstacaoTalao($dados,$con,1);
                $info_status  = 1;
                break;
            
            default:
                $info_mensage = 'Ação não encontrada';
                $info_status  = 2;
                break;
        }

        log_info($info_mensage.', Remessa:'.$dados['TALAO']['REMESSA_ID'].' Talao:'.$dados['TALAO']['REMESSA_TALAO_ID'].' Programação ID:'.$dados['TALAO']['PROGRAMACAO_ID']);

        $taloes = $this->Taloes($dados['FILTRO']);

        $ret = [
            'INFO_STATUS'   => $info_status,
            'INFO_MENSAGE'  => $info_mensage,
            'DADOS'         => $taloes,
        ];

        return Response::json($ret);

    }


    public function iniciarSetup(Request $request){

        $dados = $request->all();
        $con = new _Conexao();

        _22130::iniciarSetup($dados,$con);

        $con->commit();

        return 0;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {    	
        //
    }
    
    public function show($id)
    {
    	//
    }
    
    public function edit($id)
    {
    	//
    }
    
    public function update(Request $request, $id)
    {
    	//
    }
    
    public function destroy($id)
    {
    	//
    }

}