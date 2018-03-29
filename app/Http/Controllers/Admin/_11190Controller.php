<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11190;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;
use App\Models\Socket\Socket;

/**
 * Controller do objeto _11190 - Notificacao
 */
class _11190Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11190';
	
	public function Consultar(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _11190::Consultar($filtro,$con);

        $con->commit();

        return  Response::json($ret);      
    }
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'admin._11190.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    public static function sendUserNotfification($tipo, $users, $msg, $titulo, $metodo, $request){

        
        $con   = new _Conexao();

        foreach ($users as $key => $user) {
            $ms = ['MENSAGEM'  => $msg, 'TITULO' => $titulo, 'AGENDAMENTO_ID' => 0];

            _11190Controller::gravarNotificacao($tipo, $user, $titulo, $msg, $con);

            $sc    = new Socket($request);
            $sc->sendNotification($ms, $tipo, $user, $metodo);
        }

        $con->commit();

        return ['OK']; 
    }

    public function excluirLembrete(Request $request){

        $con   = new _Conexao();
        $paran = $request->all();
        $id    = $paran['ID'];

        $sql = 'DELETE from TBNOTIFICACAO where ID = :ID';

        $args = array(
            ':ID'          => $id
        );
            
        $con->execute($sql, $args);

        $con->commit();
            
        return Response::json(['STATUS' => 'ok']);
    }

    public function gravarLembrete(Request $request){

        $con   = new _Conexao();
        $paran = $request->all();
        $id    = $paran['ID'];

        $date  = new \DateTime($paran['AGENDAMENTO']);
        $url   = url('/_11150/'.$paran['PAINEL_ID'].'/'.$paran['TABELA_ID'].'');
        $msg   = $paran['MENSAGEM'];

        if($id == 0){
            $id    = $con->gen_id('GTBNOTIFICACAO');
            $msg   = $msg .= '<br><a href="'.$url.'" target="_blank" >CASO:'.$paran['TABELA_ID'].' - Ver Este Caso</a>';
        }

        $sql = 'UPDATE OR INSERT INTO TBNOTIFICACAO
        (
            ID,
            TIPO,
            USUARIO_ID,
            TODOS,
            MENU_ID,
            TITULO,
            LEITURA,
            ENVIO,
            EMITENTE,
            AGENDAMENTO,
            EXECUTADO,
            TABELA,
            TABELA_ID,
            MENSAGEM
        )
        VALUES (
            :ID,
            :TIPO,
            :USUARIO_ID,
            :TODOS,
            :MENU_ID,
            :TITULO,
            :LEITURA,
            :ENVIO,
            :EMITENTE,
            dateadd(hour, -3, :AGENDAMENTO),
            :EXECUTADO,
            :TABELA,
            :TABELA_ID,
            \''.$msg.'\'
        )
        MATCHING (ID);';

        $args = array(
            ':ID'          => $id,
            ':TIPO'        => $paran['TIPO'],
            ':USUARIO_ID'  => \Auth::user()->CODIGO,
            ':TODOS'       => 0,
            ':MENU_ID'     => 0,
            ':TITULO'      => $paran['TITULO'],
            ':LEITURA'     => 0,
            ':ENVIO'       => 0,
            ':EMITENTE'    => \Auth::user()->CODIGO,
            ':AGENDAMENTO' => $date->format('d.m.Y H:i:s'),
            ':EXECUTADO'   => $paran['EXECUTADO'],
            ':TABELA'      => $paran['TABELA'],
            ':TABELA_ID'   => $paran['TABELA_ID']
        );
            
        $con->execute($sql, $args);

        $con->commit();
            
        return Response::json(['STATUS' => 'ok']);
    }

    public function getNotifCasos(Request $request){

        $con   = new _Conexao();
        $paran = $request->all();

        $sql = 'SELECT

                    ID,
                    TIPO,
                    USUARIO_ID,
                    TODOS,
                    MENU_ID,
                    TITULO,
                    MENSAGEM,
                    LEITURA,
                    ENVIO,
                    DATA_HORA,
                    EMITENTE,
                    AGENDAMENTO,
                    EXECUTADO,
                    TABELA,
                    TABELA_ID

                from tbnotificacao n
                where n.usuario_id = :USUARIO_ID
                and n.tabela       = :TABELA
                and n.tabela_id    = :TABELA_ID

                order by n.AGENDAMENTO desc';

        $args = array(
            ':USUARIO_ID' => \Auth::user()->CODIGO,
            ':TABELA_ID'  => $paran['TABELA_ID'],
            ':TABELA'     => $paran['TABELA']
        );
            
        $ret = $con->query($sql, $args);
            
        return $ret;
    }

    public static function gravarNotificacao($tipo, $user, $titulo, $mensagem, $con){

        $sql = 'INSERT INTO TBNOTIFICACAO (TIPO, USUARIO_ID, TODOS, MENU_ID, TITULO, LEITURA, ENVIO, MENSAGEM, EMITENTE)
                VALUES ('.$tipo.', '.$user.', 0, 0, \''.$titulo.'\', 0, 0, \''.$mensagem.'\', '.\Auth::user()->CODIGO.');';

        $con->execute($sql);
    }


    public function sendUserNotfi(Request $request){

        $paran = $request->all();
        $users = $paran['USERS'];
        $msg   = $paran['MSG'];

        _11190Controller::sendUserNotfification(0, $users, $msg['MENSAGEM'], $msg['TITULO'], 'NOTIFICACAO', $request);

        return ['OK']; 
    }

    public function updateTela(Request $request){

        $paran = $request->all();
        $users = $paran['USERS'];

        _11190Controller::sendUserNotfification(1, $users, 'ATUALIZAR', 'TELA ATUALIZADA', 'UPDATETELA', $request);

        return ['OK'];  
    }

    public function updateMenu(Request $request){

        $paran = $request->all();
        $users = $paran['USERS'];

        _11190Controller::sendUserNotfification(1, $users, 'ATUALIZAR', 'MENUS ATUALIZADOS', 'UPDATEMENUS', $request);

        return ['OK']; 
    }

    public function getNotificacao(Request $request){

        $con   = new _Conexao();

        $sql = 'SELECT first 100

                    ID,
                    TIPO,
                    USUARIO_ID,
                    TODOS,
                    MENU_ID,
                    TITULO,
                    MENSAGEM,
                    LEITURA,
                    ENVIO,
                    formatdatetime(DATA_HORA) as DATA_HORA,
                    EMITENTE,
                    formatdatetime(AGENDAMENTO) as AGENDAMENTO,
                    EXECUTADO

                from
                    tbnotificacao f
                where f.usuario_id = '.\Auth::user()->CODIGO;

        $ret = $con->query($sql);
            
        return $ret;

    }

    public function agendamento(Request $request){

        $con   = new _Conexao();
        $paran = $request->all();

        $sql = 'UPDATE tbnotificacao f set
                f.executado = 0,
                f.agendamento = dateadd(minute, :MINUTOS, current_timestamp)

                where f.id = :AGENDAMENTO_ID
                ';

        $args = array(
            ':AGENDAMENTO_ID'  => $paran['AGD_ID'],
            ':MINUTOS'         => $paran['AGD_TM']
        );
            
        $con->query($sql, $args);
        $con->commit();

        return [];

    }

    public function getUsuarios(Request $request){

        $con = new _Conexao();
        $filtro = $request->all();

        $ret = _11190::getUsuarios($filtro,$con);

        $con->commit();
        
        return Response::json($ret); 

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
    
    public function destroy($id)
    {
    	//
    }

}