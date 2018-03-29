<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Auth\CustomPassword;
use App\Models\DTO\Helper\Email;
use Session;

class CustomPasswordController extends Controller
{

    /**
     * Conexão.
     * @var _Conexao
     */
    private $con = null;


    /**
     * Página para definir e-mail para recuperação de senha.
     * @return view
     */
    public function getEmail() {

        return view('auth.password');
    }

    /**
     * Gravar o e-mail para recuperação de senha.
     * @param Request $request
     */
    public function postEmail(Request $request) {

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            $param->token = md5(uniqid(rand(), true));

            $verifEmail = CustomPassword::verificarEmailExiste($param, $this->con);

            if (empty($verifEmail))
                log_erro('E-mail não consta em nosso sistema.');

            CustomPassword::gravarEmailRecuperacao($param, $this->con);

            $obj = new Email();
            $obj->setEmail($param->email);
            $obj->setUsuarioId(0);
            $obj->setMensagem('Clique no bot&atilde;o abaixo para redefinir sua senha.');
            $obj->setUrl(env('URL_PRINCIPAL'));
            $obj->setAssunto('Recuperar senha');
            $obj->setCorpo( env('URL_PRINCIPAL'). '/password/reset/'.$param->token);
            $obj->setStatus('1');
            $obj->setDatahora(date('d.m.Y H:i:s'));
            $obj->setCodigo(3); //template

            Email::gravar($obj);

            $this->con->commit();

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Página para definir nova senha.
     * @param Request $request
     * @return view
     */
    public function getReset(Request $request) {

        $this->con = new _Conexao();

        try {

            $dadosToken     = CustomPassword::verificarToken($request->token, $this->con);

            $dataToken      = date_create($dadosToken[0]->DATAHORA);
            $dataAtual      = date_create();
            $dataDiferenca  = date_diff($dataAtual, $dataToken);

            // Validade do token: 30min.
            if ($dataDiferenca->i > 30)
                log_erro('Token expirado. Tente recuperar sua senha novamente.');

            $this->con->commit();

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

        return view('auth.reset', [
            'token' => $request->token,
            'email' => $dadosToken[0]->EMAIL
        ]);
    }

    /**
     * Gravar nova senha.
     * @param Request $request
     * @return json
     */
    public function postReset(Request $request) {

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));

            if ($param->password != $param->password_confirmation)
                log_erro('Senhas não conferem.');

            $param->password = bcrypt($param->password);

            CustomPassword::gravarNovaSenha($param, $this->con);

            $this->con->commit();

            $resposta = array('0' => 'sucesso');

        } catch (Exception $e) {
            $this->con->rollback();
            $resposta = array('0' => 'erro', '1' => $e->getMessage());
            throw $e;
        }

        return Response::json($resposta);
    }
}
