<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11150;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;
use App\Http\Controllers\Helper\ArquivoController;
use App\Http\Controllers\Admin\_11190Controller;

/**
 * Controller do objeto _11150 - Registro de Casos
 */
class _11150Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'Admin/_11150';

    public function sendNotificationNovo($request, $dados, $desc){

        $date = new \DateTime();

        $url = url('/_11150/'.$dados['PAINEL'].'/'.$dados['CASO'].'');

        $corpo  = '';
        $corpo .= 'FOI REGISTRADO UM NOVO '.$desc.' POR '.\Auth::user()->NOME.'<br>';
        $corpo .= '<a href="'.$url.'" target="_blank" >CASO:'.$dados['CASO'].' - Ver Este Caso</a><br><br>';
        $corpo .= $date->format('d/m/Y H:i');

        $con = new _Conexao();
        $usuarios = _11150::getUserNotification($dados['PAINEL'], $dados['CASO'], $con);
        $arr = [];

        foreach($usuarios as $i => $usuario){
            array_push($arr, $usuario->USUARIO_ID);    
        }

        if(count($arr) > 0){
            _11190Controller::sendUserNotfification(0, $arr, $corpo, 'NOVO '.$desc.'', 'NOTIFICACAO', $request);
        }
    }
    
	public function excluirCaso(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $painel_id = $filtro['PAINEL_ID'];
        $caso_id   = $filtro['CASO_ID'];
        
        $ret = _11150::excluirCaso($painel_id,$caso_id,$con);

        $con->commit();

        return [];      
    }

    public function gravarFeed(Request $request){

        $con = new _Conexao();
        $dados = $request->all();
        
        $ret = _11150::gravarFeed($dados,$con);

        if($dados['FEED_ID'] == 0){
            _11150Controller::sendNotificationNovo($request, ['CASO' => $dados['CASO_ID'], 'PAINEL' => $dados['PAINEL_ID']], 'FEED');
        }

        $con->commit();

        return [];    
    }

    public function excluirFeed(Request $request){

        $con = new _Conexao();
        $dados = $request->all();
        
        $ret = _11150::excluirFeed($dados,$con);

        $con->commit();

        return [];    
    }

    public function gostei(Request $request){

        $con = new _Conexao();
        $dados = $request->all();
        
        $ret = _11150::gostei($dados,$con);

        $con->commit();

        return Response::json($ret);    
    }

    public function getEnvolvidos(Request $request){

        $con = new _Conexao();
        $dados = $request->all();
        
        $ret = _11150::getEnvolvidos($dados,$con);

        $con->commit();

        return Response::json($ret);    
    }

    public function rmvEnvolvidos(Request $request){

        $con = new _Conexao();
        $dados = $request->all();
        
        $ret = _11150::rmvEnvolvidos($dados,$con);

        $con->commit();

        return Response::json($ret);    
    }

    public function grvEnvolvidos(Request $request){

        $con = new _Conexao();
        $dados = $request->all();
        
        $ret = _11150::grvEnvolvidos($dados,$con);

        $con->commit();

        return Response::json($ret);    
    }

    public function listEnvolvidos(Request $request){

        $con = new _Conexao();
        $dados = $request->all();
        
        $ret = _11150::listEnvolvidos($dados,$con);

        $con->commit();

        return Response::json($ret);    
    }

    public function getCasos(Request $request){
        
        $filtro = $request->all();

        if(array_key_exists('PAINEL_ID', $filtro) == true){
            if($filtro['PAINEL_ID'] != '{{ arq.BINARIO }}'){

                $con = new _Conexao();
                $painel_id = $filtro['PAINEL_ID'];
                $status = $filtro['STATUS'];
                $id = \Auth::user()->CODIGO;

                $casos = _11150::getCasos($painel_id, $status, $filtro,$con);
                $user  = _11150::usuario($painel_id,$id,$con);
                $paran = _11150::usuario_parametros($painel_id,$id,$con);

                $filtro['FILTRO' ] = '';
                $filtro['OPTIONS'] = [];
                $filtro['OPTIONS']['PAINEL_CASO'] = [];
                $filtro['OPTIONS']['PAINEL_CASO']['ID'] = $painel_id;
                $filtro['PARAN'  ] = [];
                $filtro['PARAN'  ]['ID'] = 0;

                $status = _11150::Status($filtro,$con);
                
                $ret = [
                        'USUARIO'   => $user,
                        'CASOS'     => $casos['ITENS'],
                        'STATUS'    => $status,
                        'CONF'      => $casos['CONF'],
                        'PARAMETRO' => $paran,
                    ];

            }else{
                $ret = [];    
            }

            return  Response::json($ret);
        }      
    }

	public function Consultar(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _11150::Consultar($filtro,$con);

        return  Response::json($ret);      
    }

    public function historico(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $painel_id = $filtro['PAINEL_ID'];
        $caso_id   = $filtro['CASO_ID'];

        $ret = _11150::historico($painel_id,$caso_id,$con);

        return  Response::json($ret);      
    }

    public function comentario(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $painel_id = $filtro['PAINEL_ID'];
        $caso_id   = $filtro['CASO_ID'];

        $ret = _11150::consultarFeed($painel_id,$caso_id,1,$con);

        return  Response::json($ret);      
    }

    public function Consultas(Request $request){

        $con   = new _Conexao();
        $param = $request->all();

        $id  = $param['OPTIONS'][0]['SQL_ID'];
        $painel_id  = $param['OPTIONS'][0]['PAINEL_ID'];

        $sql   = _11150::getSql($id,$con);
        $paran = _11150::usuario_parametros($painel_id,\Auth::user()->CODIGO,$con);

        $filter = strtoupper($param['FILTRO']);
        $filter = !empty($filter) ? '\'%'.  str_replace(' ', '%', $filter) .'%\'' : '\'%\'';

        $sql = str_replace(":FILTRO", $filter, $sql);

        if(count($paran) > 0){
            foreach ($paran as $key => $iten) {
                $val = '';
                if(is_numeric($iten->VALOR)){
                    $val = $iten->VALOR;
                }else{
                    $val = "'".$iten->VALOR."'";
                }
                
                $sql = str_replace(":".$iten->NOME, $val, $sql);
            }
        }

        if(count($param['OPTIONS']) > 1){
            foreach ($param['OPTIONS']  as $key => $iten) {
                if($key > 0){
                    foreach ($iten['dados']  as $k => $i) {
                        $sql = str_replace(":".$k, $i, $sql);
                    }
                }
            }
        }

        if(count($param['PARAN']) > 0){
            foreach ($param['PARAN']  as $key => $valor) {
                $sql = str_replace("/*GETREGISTRO*/", ' and '.$key.' = '.$valor, $sql);
            }
        }

        //log_info($sql);
        
        $ret = $con->query($sql);

        return  Response::json($ret);      
    }

    public function Status(Request $request)
    {
        $paran = $request->all();
        $con   = new _Conexao();
        $paran = $request->all();
        $ret   = _11150::Status($paran,$con);
        return  Response::json($ret);      
    }
	
	public function index($painel_id)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        return view(
            'admin._11150.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'painel_id'     => $painel_id,
            'caso_id'       => 0
        ]);  
    }

    public function show($painel_id,$caso_id)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        return view(
            'admin._11150.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu,
            'painel_id'     => $painel_id,
            'caso_id'       => $caso_id
        ]); 
    }

    public function confPainel($painel_id,$con)
    {
        $dados = _11150::confPainel($painel_id,$con);
        return $dados; 
    }

    public function confPainelItens($painel_id,$con)
    {  
        $dados = _11150::confPainelItens($painel_id,$con);
        return $dados; 
    }

    public function vinculos($painel_id,$con)
    {  
        $dados = _11150::vinculos($painel_id,$con);
        return $dados; 
    }

    public function itensVinculo($painel_id,$con)
    {  
        $dados = _11150::itensVinculo($painel_id,$con);
        return $dados; 
    }

    public function getPainel(Request $request)
    {

        $painel_id = $request['PAINEL_ID'];
        $caso_id   = $request['CASO_ID'];

        $con       = new _Conexao();

        

        if( is_numeric($caso_id) ){

            $caso_registro = _11150::casoRegistro($painel_id,$caso_id,$con);
            $caso_detalhe  = _11150::casoDetalhe($painel_id,$caso_id,$con);
            $caso_itens    = _11150::casoItens($painel_id,$caso_id,$con);
            $caso_feed     = _11150::consultarFeed($painel_id,$caso_id,0,$con);

            if(count($caso_registro) > 0 || $caso_id == 0){

                if($caso_id > 0){
                    $paran = $request->all();
                    $paran['PARAN'] = [];
                    $paran['FILTRO'] = '';
                    $paran['OPTIONS'] = [];
                    $paran['OPTIONS']['PAINEL_CASO'] =[];
                    $paran['OPTIONS']['PAINEL_CASO']['ID'] = $painel_id;
                    $paran['OPTIONS'][1] = [];
                    $paran['OPTIONS'][1]['dados'] = [];
                    $paran['OPTIONS']['dados'] = [];

                    /////////////////////////////////

                    $sql   = _11150::getSql(7,$con);
                    $sql   = str_replace(':PAINEL_ID',$painel_id, $sql); 
                    $sql   = str_replace(':PAINEL_ID',$painel_id, $sql); 
                    $sql   = str_replace(':PAINEL_ID',$painel_id, $sql); 
                    $sql   = str_replace(':PAINEL_ID',$painel_id, $sql); 
                    $sql   = str_replace(':PAINEL_ID',$painel_id, $sql); 
                    $email = $con->query($sql);
                    $contatos = $email;
                    ////////////////////////////////

                    $paran['PARAN']['ID'] = $caso_registro->MOTIVO_ID;
                    $motivo = _11150::Motivos($paran,$con);

                    $paran['PARAN']['ID'] = $caso_registro->RESPONSAVEL_ID;
                    $responsavel = _11150::Responsavel($paran,$con);

                    $paran['PARAN']['ID'] = $caso_registro->STATUS_ID;
                    $status = _11150::Status($paran,$con);

                    $paran['PARAN']['ID'] = $caso_registro->TIPO_ID;
                    $paran['OPTIONS']['dados']['ID'] = $caso_registro->MOTIVO_ID;
                    $tipo = _11150::Tipos($paran,$con);

                    $paran['PARAN']['ID'] = $caso_registro->ORIGEM_ID;
                    $paran['OPTIONS'][1]['dados']['ID'] = $caso_registro->TIPO_ID;
                    $origen = _11150::Origens($paran,$con);

                    $motivo      = $motivo[0];
                    $status      = $status[0];
                    $tipo        = $tipo[0];
                    $origen      = $origen[0];

                    if(count($responsavel) > 0){
                        $responsavel = $responsavel[0];   
                    }else{
                        $responsavel = [];
                    }

                    if($caso_registro->CONTATO_ID > 0){
                        $paran['PARAN']['ID'] = $caso_registro->CONTATO_ID;
                        $conf  = _11150::confContato($paran['OPTIONS'],$con);
                        $contato = $this->getContatos($paran,$paran['PARAN'],$conf,$con);
                        $contato = $contato[0];
                    }else{
                        $contato = [];
                    }
                }else{
                    $motivo      = [];
                    $responsavel = [];
                    $status      = [];
                    $tipo        = [];
                    $origen      = [];
                    $contato     = [];
                    $contatos    = [];
                }

                $painel_caso  = _11150::getPainelCaso($painel_id,$con);
                $painel_conf  = _11150::confPainel($painel_id,$con);
                $painel_itens = _11150::confPainelItens($painel_id,$con);
                $painel_agrup = _11150::camposAgrupamentos2($painel_id,$con);

                $campo_vinculos = _11150::vinculos($painel_id,$con);
                $itens_vinculos = _11150::itensVinculo($painel_id,$con);

                $itens_validacao = _11150::validacao($painel_id,$con);

                if(count($painel_caso) > 0){

                    $imputs = [];

                    foreach ($painel_conf  as $key => $iten) {

                        $iten->ITENS = [];
                        $iten->VINCULO_CAMPO = [];
                        $iten->VINCULO_DESCRICAO = '#';
                        $iten->AGRUP = '';
                        $iten->VINCULO_ITENS = [];
                        $iten->JSON = '';

                        $vinculo_id = 0;

                        foreach ($campo_vinculos  as $key => $obj) {
                            if($iten->ID == $obj->CAMPO_ID){

                                array_push($iten->VINCULO_CAMPO, $obj->CAMPO_VINCULO);
                                $iten->VINCULO_DESCRICAO = $iten->VINCULO_DESCRICAO. ', ' .$obj->DESCRICAO; 
                                $vinculo_id = $obj->ID;  

                            }    
                        }

                        $iten->VINCULO_DESCRICAO = str_replace("#, ","",$iten->VINCULO_DESCRICAO);

                        foreach ($caso_detalhe  as $key => $obj) {
                            if($iten->ID == $obj->CAMPO_ID){
                                $iten->DEFAULT = $obj->VALOR;
                                $iten->JSON    = $obj->JSON;
                            }    
                        }

                        if($vinculo_id > 0){
                            foreach ($itens_vinculos  as $key => $obj) {
                                if($vinculo_id == $obj->DEPENDENCIA_ID){
                                    array_push($iten->VINCULO_ITENS, $obj);  
                                }    
                            }
                        }

                        foreach ($painel_agrup  as $key => $obj) {
                            if($iten->GRUPO_ID == $obj->ID){
                                $iten->AGRUP = $obj->DESCRICAO;    
                            }    
                        }

                        $itens_ret = [];
                        foreach ($caso_itens  as $key => $caso) {
                            if($caso->CAMPO_ID == $iten->ID){
                                array_push($itens_ret,$caso);    
                            }
                        }

                        foreach ($painel_itens  as $key => $obj) {

                            if($obj->SELECIONADO == 0){$selecionado = false;}else{$selecionado = true;}

                            foreach ($itens_ret  as $key => $caso) {
                                if($caso->VALOR == $obj->VALOR){
                                    if($caso->SELECIONADO == 0){$selecionado = false;}else{$selecionado = true;}   
                                }
                            }

                            if($iten->ID == $obj->CAMPO_ID){
                                array_push($iten->ITENS,(object)
                                    [
                                        'TEXTO'    => $obj->DESCRICAO,
                                        'SELECTED' => $selecionado,
                                        'VALOR'    => $obj->VALOR
                                    ]
                                );
                            }
                        }

                        array_push($imputs,$iten);
                    }

                    $ret = [
                        'PAINEl_CASO' => $painel_caso[0],
                        'PAINEl_CONF' => $imputs,
                        'VALIDACAO'   => $itens_validacao,
                        'CASO_ITEN'   => $caso_registro,
                        'MOTIVO'      => $motivo,
                        'RESPONSAVEL' => $responsavel,
                        'STATUS'      => $status,
                        'TIPO'        => $tipo,
                        'ORIGEM'      => $origen,
                        'CONTATO'     => $contato,
                        'FEED'        => $caso_feed,
                        'CONTATOS'    => $contatos
                    ];

                }else{
                    $ret = [
                        'PAINEl_CASO' => [],
                        'PAINEl_CONF' => [],
                        'VALIDACAO'   => [],
                        'CASO_ITEN'   => [],
                        'MOTIVO'      => [],
                        'RESPONSAVEL' => [],
                        'STATUS'      => [],
                        'TIPO'        => [],
                        'ORIGEM'      => [],
                        'CONTATO'     => [],
                        'FEED'        => [],
                        'CONTATOS'    => [],
                    ];   
                }

            }else{
                log_erro('Caso não encontrado');
            }

        }else{
            log_erro('Caso não encontrado');
        }

        return Response::json($ret);  
    }

    public function paineisCasos()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);

        $con           = new _Conexao();
        $paineis       = _11150::paineisCasos($con);

        return view(
            'admin._11150.paineisCasos', [
            'menu'        => $this->menu,
            'paineis'     => $paineis
        ]);  
    }

    public function gravarContato(Request $request)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        $param         = $request->all();
        $itens         = $param['ITENS'];
        $painel_id     = $param['PAINEL_ID'];

        $con           = new _Conexao();

        $item_id = _11150::gravarContato($con,$itens,$painel_id);
        $con->commit();

        return $item_id;
    }

    public function gravarCaso(Request $request)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        $param         = $request->all();
        $itens         = $param['ITENS'];
        $campos        = $param['CAMPOS'];
        $painel_id     = $param['PAINEL_ID'];
        $caso_id       = $param['CASO_ID'];

        $con           = new _Conexao();

        $item_id = _11150::gravarCaso($con,$itens,$campos,$painel_id,$caso_id );

        $con->commit();

        if($caso_id == 0 && $item_id > 0){
            _11150Controller::sendNotificationNovo($request, ['CASO' => $item_id, 'PAINEL' => $painel_id], 'CASO');
        }

        return $item_id;
    }

    public function confContato(Request $request)
    {

        $paran  = $request->all();
        $con    = new _Conexao();
        $dados  = _11150::confContato($paran,$con);
        $itens  = _11150::confItens($paran,$con);
        $agrup  = _11150::camposAgrupamentos($paran,$con);
        
        $paran['OPTIONS'] = $paran;
        $paran['FILTRO']  = '';

        $id = [];
        $id['ID'] = 0;

        $contatos = $this->getContatos($paran,$id,$dados,$con);

        $imputs = [];

        foreach ($dados  as $key => $iten) {

            $iten->ITENS = [];
            $iten->AGRUP = '';

            foreach ($agrup  as $key => $obj) {
                if($iten->GRUPO_ID == $obj->ID){
                    $iten->AGRUP = $obj->DESCRICAO;    
                }    
            }

            foreach ($itens  as $key => $obj) {
               if($iten->ID == $obj->CAMPO_ID){
                array_push($iten->ITENS,(object)
                    [
                        'TEXTO'    => $obj->DESCRICAO,
                        'SELECTED' => $obj->SELECIONADO == 1,
                        'VALOR'    => $obj->VALOR
                    ]
                );
               }
            }

           array_push($imputs,$iten);
        }

        return Response::json(['IMPUTS' => $imputs, 'CONTATOS' => $contatos]);
    }

    public function Motivos(Request $request)
    {

        $paran  = $request->all();
        $con    = new _Conexao();
        $dados  = _11150::Motivos($paran,$con);

        return Response::json($dados);  
    }

    public function Tipos(Request $request)
    {

        $paran  = $request->all();
        $con    = new _Conexao();
        $dados  = _11150::Tipos($paran,$con);

        return Response::json($dados);  
    }

    public function Origens(Request $request)
    {

        $paran = $request->all();
        $con   = new _Conexao();
        $dados = _11150::Origens($paran,$con);

        return Response::json($dados);  
    }

    public function Responsavel(Request $request)
    {

        $paran = $request->all();
        $con   = new _Conexao();
        $dados = _11150::Responsavel($paran,$con);

        return Response::json($dados);  
    }

    public function getContatos($paran,$id,$conf,$con ){

        $param['PAINEL_CASO'] = $paran['OPTIONS']['PAINEL_CASO'];
        $paran['PARAN'] = $id;

        $dados = _11150::Contatos($paran, $con);
        $itens = _11150::confItens($paran,$con);

        $contato_iten       = [];
        $contatos           = [];
        $contato_item['ID'] = 0;
        $desc               = 0;

        foreach ($conf  as $key => $iten) {
           $contato_iten[strtoupper($iten->ID)] = '';
           if($iten->MOSTRAR){$desc = $iten->ID;}
        }

        foreach ($dados  as $key => $iten) {
            $pos = -1;
            foreach ($contatos  as $i => $user) {
                if($user['ID'] == $iten->REG_ID){
                    $pos = $i;
                }
            }

            if($pos < 0){
                $pos = count($contatos);
                array_push($contatos,$contato_item);
            }

            $contatos[$pos]['ID']   = $iten->REG_ID;
            $contatos[$pos]['TIPO'] = $iten->TIPO;
            $contatos[$pos][$iten->CAMPO] = [];
            $contatos[$pos][$iten->CAMPO]['VALOR'] = $iten->VALOR;

            $contatos[$pos][$iten->CAMPO]['ITENS'] = [];
            
            foreach ($itens as $y => $obj) {

                if($iten->CAMPO == $obj->CAMPO_ID){

                    $select = false;
                    if($iten->TIPO == 4){

                        $split = explode(",", $iten->VAL_ITEN);

                        foreach ($split as $k => $o) {
                            $a = explode("|", $o);

                            if($obj->VALOR == $a[0]){
                                $select = $a[1] == 1;      
                            }    
                        }
                    }else{
                        $select = $iten->VALOR == $obj->VALOR;
                    }

                    array_push($contatos[$pos][$iten->CAMPO]['ITENS'],(object)
                        [
                            'TEXTO'    => $obj->DESCRICAO,
                            'SELECTED' => $select,
                            'VALOR'    => $obj->VALOR,
                            'CAMPO_ID' => $obj->CAMPO_ID
                        ]
                    );
                }
            }

            if($iten->CAMPO == $desc){
                $contatos[$pos]['DESCRICAO'] = $iten->VALOR;
            }

        }

        return $contatos;  
    }

    
    public function finalizar(Request $request)
    {

        $con   = new _Conexao();
        $paran = $request->all();

        $ret   = _11150::finalizar($paran,$con);

        $con->commit();

        return Response::json($ret);  
    }

    public function Contatos(Request $request)
    {

        $con   = new _Conexao();
        $paran = $request->all();

        $conf  = _11150::confContato($paran['OPTIONS'],$con);

        $id = [];
        $id['ID'] = 0;

        $contatos = $this->getContatos($paran,$id,$conf,$con);

        return Response::json($contatos);  
    }

    public function store(Request $request)
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