<?php

namespace App\Helpers;

use App\Models\DTO\Admin\_11010;
use App\Http\Controllers\Controller;
use App\Models\Conexao\_Conexao;
use Illuminate\Http\Request;

class UserControl extends Controller {
    
//    private $menu = '';

    private $msg = null;
    private $permissao = null;
    private $request = [];
    private $request_base = [];
    
    /**
     * @var \App\Models\Conexao\_Conexao
     */
    private $con = null;
    
    public function __construct(Request $request) {
        $this->request = obj_case(json_decode(json_encode((object) $request->all()), false));
        $this->request_base = $request;
        $this->con = new _Conexao;
    }
    
    /**
     * Inicializa os parametros para verificar a permissão de acesso aos menus;
     * Deverá ser invocada com os métodos de permissão<br/>
     * Exemplo:<br/>
     * $this->Menu()->consultar();<br/>
     * $this->Menu()->incluir();<br/>
     * $this->Menu()->alterar();<br/>
     * $this->Menu()->excluir();<br/>
     * $this->Menu()->imprimir();<br/>
     * @param $log Exibir log de acesso. Default: true
     * @return type
     */
    public function Menu($log = true) {
        
        if ( $log == false ) {
            $this->permissao = 0;
        }
        return $this;
    }
    
    /**
     * 
     * @param string $msg Mensagem a ser exibida no log
     * @return object
     */
    protected function consultar($msg = null) {
        return _11010::permissaoMenu($this->menu, $this->permissao, $msg);
    }
        
    /**
     * 
     * @param string $msg Mensagem a ser exibida no log
     * @return object
     */
    protected function incluir($msg = null) {
        return _11010::permissaoMenu($this->menu, 'INCLUIR', $msg);
    }
        
    /**
     * 
     * @param string $msg Mensagem a ser exibida no log
     * @return object
     */
    protected function alterar($msg = null) {
        return _11010::permissaoMenu($this->menu, 'ALTERAR', $msg);
    }
        
    /**
     * 
     * @param string $msg Mensagem a ser exibida no log
     * @return object
     */
    protected function excluir($msg = null) {
        return _11010::permissaoMenu($this->menu, 'EXCLUIR', $msg);
    }
        
    /**
     * 
     * @param string $msg Mensagem a ser exibida no log
     * @return object
     */
    protected function imprimir($msg = null) {
        return _11010::permissaoMenu($this->menu, 'IMPRIMIR', $msg);
    }
    
    /**
     * Método de acesso ao banco de dados<br/>
     * Se a instancia de conexão ainda não existir, será criada. Se já existir, será utilizada.
     * @return \App\Models\Conexao\_Conexao
     */
    public function con() {
        return $this->con;
    }
    
    /**
     * Retorna os dados de uma request
     * @param true $request_base Se <b>true</b> retorna o request na forma padrão, senão retorna um objeto com nome das chaves em UPPERCASE. Default: false
     * @return mixed
     */
    public function request($request_base = false) {
        
        if ( $request_base ) {
            $ret = $this->request_base;
        } else {
            $ret = $this->request;
        }
        
        return $ret;
    }
    
    /**
     * Registra log do menu
     * @param string $msg Mensagem de log
     * @return void
     */
    public function loginfo($msg) {
        log_info($msg,$this->menu);
    }
    
    
}