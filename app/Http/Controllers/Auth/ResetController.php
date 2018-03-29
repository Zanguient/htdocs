<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Hash;
use App\Models\DTO\Auth\Reset;

class ResetController extends Controller {
	
	/**
     * Exibe a tela para primeiro acesso.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPrimeiroAcesso() {

        return view('auth.primeiroAcesso');
    }

    /**
     * Grava a senha do usuário, caso seja o seu primeiro acesso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPrimeiroAcesso(Request $request) {

		//validar campos
		$val = $this->validarCampo($request);
		if ( !empty($val) ) {
			return Response::json( $val );
		}
		
		$obj = new Reset();

		$tipo = '';
		if (is_numeric($request->login)) {
			$tipo = 'cnpj';
			$value = $request->login;
		}
		else if (strlen($request->login) <= 10) {
			$tipo = 'usuario';
			$value = strtoupper($request->login);
		}
		else {
			$tipo = 'email';
			$value = strtolower($request->login);
		}

		$obj->setUsuario( $tipo == 'usuario' ? $value : '#' );
		$obj->setEmail( $tipo == 'email' ? $value : '#' );
		$obj->setCnpj( $tipo == 'cnpj' ? $value : '#' );
		$obj->setSenha( bcrypt($request->password) );
		
		//verifica se o usuário existe
		$res = $this->verificarUsuarioExiste($obj);
		
		if ( empty($res) ) {	
			//alterar (cadastrar) senha
			$res = Reset::alterarSenha($obj);
		}
		
		return Response::json( $res );
	}
	
	/**
	 * Verifica se o usuário existe.
	 * 
	 * @param Reset $obj
	 * @return string
	 */
	public function verificarUsuarioExiste(Reset $obj) {
		
		$verif = Reset::verificarUsuario($obj);

		//verifica se houve erro
		if ($verif['resposta'][0] === 'erro') {
			$res = array('0' => 'erro', '1' => $verif['resposta'][1]);
		}
		//Usuário não existe
		else if ( empty($verif['existe_usuario']) ) {
			$res = array('0' => 'erro', '1' => 'Usuário não existe. Contacte o administrador do sistema.');
		}
		//Usuário já possui senha
		else if ( !empty($verif['existe_senha'][0]->PASSWORD) ) {
			$res = array('0' => 'erro', '1' => 'Usuário já possui senha.');
		}
		else {
			$res = '';
		}
		
		return $res;
	}
	
	/**
     * Exibe a tela para resetar a senha.
     *
     * @return \Illuminate\Http\Response
     */
    public function getResetarSenha() {

        return view('auth.resetarSenha');
    }

    /**
     * Resetar a senha do usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postResetarSenha(Request $request) {

		//validar campos
		$val = $this->validarCampo($request);
		if ( !empty($val) ) {
			return Response::json( $val );
		}
		
		$obj = new Reset();
		$obj->setUsuario($request->usuario);
		$obj->setSenha(bcrypt($request->password));
		$obj->setSenhaAntiga($request->password_old);
		
		//verifica se o usuário existe
		$res = $this->compararUsuario($obj);
		
		if ( empty($res) ) {	
			//alterar (cadastrar) senha
			$res = Reset::alterarSenha($obj);
		}
		
		return Response::json( $res );		
	}
	
	/**
	 * Compara a senha antiga com a nova do usuário.
	 * 
	 * @param Reset $obj
	 * @return string
	 */
	public function compararUsuario(Reset $obj) {
		
		$verif = Reset::verificarUsuario($obj);
		
		//verifica se houve erro
		if ($verif['resposta'][0] === 'erro') {
			$res = array('0' => 'erro', '1' => $verif['resposta'][1]);
		}
		//Usuário não existe
		else if ( empty($verif['existe_usuario']) ) {
			$res = array('0' => 'erro', '1' => 'Usuário não existe.');
		}
		//Verifica se a senha antiga digitada pelo usuário confere com a cadastrada no banco.
		else if ( empty($verif['existe_senha'][0]->PASSWORD) || !Hash::check($obj->getSenhaAntiga(), $verif['existe_senha'][0]->PASSWORD) ) {
			$res = array('0' => 'erro', '1' => 'Senha antiga não confere.');
		}		
		else {
			$res = '';
		}
		
		return $res;
	}
	
	/**
	 * Validar campos.
	 * 
	 * @param Request $request
	 * @return mixed
	 */
	public function validarCampo(Request $request) {
		
		// if ( strlen($request->usuario) > 10 ) {
		// 	$res = array('0' => 'erro', '1' => 'O nome de usuário deve ter no máximo 10 caracteres.');
		// }
		// else if ( strlen($request->password) > 10 ) {
		// 	$res = array('0' => 'erro', '1' => 'A senha deve ter no máximo 10 caracteres.');
		// }
		if ($request->password !== $request->password_confirmation) {
			$res = array('0' => 'erro', '1' => 'Senhas não conferem.');
		}
		else {
			$res = '';
		}
		
		return $res;
	}
}
