<?php

namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Helper\Arquivo;
use Illuminate\Support\Facades\Response;
use Input;
use App\Models\Conexao\_Conexao;

class ArquivoController extends Controller
{
	
	/**
     * Grava arquivo no banco de dados.
     * Função chamada via Ajax.
     *
     * @param Request $request
     */
	public function enviarArquivo(Request $request)
    {

   	    if ( $request->ajax() ) {

    		$arq_id = Arquivo::gerarIdArquivo();

			//redireciona para tela de erro
			if ($arq_id['resposta'][0] === 'erro') {
				return view('errors.query', ['erro' => $arq_id['resposta'][1]]);
			}

			$obj = new Arquivo();
			$obj->setId($arq_id['id'][0]->ID);
            $obj->setVinculo($request->get('vinc'));
			$obj->setSequencia(1);
            $obj->setTabela($request->get('tabela'));

            

			switch ( $request->get('tipo') ) {
				case "application/pdf"					: $tipo = "pdf"; break;
				case "application/octet-stream"			: $tipo = "exe"; break;
				case "application/zip"					: $tipo = "zip"; break;
				case "application/msword"				: $tipo = "doc"; break;
				case "application/vnd.ms-excel"			: $tipo = "xls"; break;
				case "application/vnd.ms-powerpoint"	: $tipo = "ppt"; break;
				case "image/gif"						: $tipo = "gif"; break;
				case "image/png"						: $tipo = "png"; break;
				case "image/jpg"						: $tipo = "jpg"; break;
				case "image/jpeg"						: $tipo = "jpg"; break;
				case "audio/mpeg"                       : $tipo = "mp3"; break;
                case "text/plain"                       : $tipo = "txt"; break;

                case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"         : $tipo = "xls"; break;
                case "application/vnd.openxmlformats-officedocument.presentationml.presentation" : $tipo = "ppt"; break;
                case "application/vnd.openxmlformats-officedocument.wordprocessingml.document"   : $tipo = "doc"; break;
                
				default									: $tipo = 'unknown';
			}

    		$obj->setTipo($tipo);
			$obj->setTamanho($request->get('tamanho'));
    		$obj->setTmpName($_FILES['file-0']['tmp_name']);
            $obj->setNome($_FILES['file-0']['name']);
            $obj->setConteudo(file_get_contents($_FILES['file-0']['tmp_name']));
            
			$obj->setUsuarioId(!empty(\Auth::user()->CODIGO) ? \Auth::user()->CODIGO : 999);
			$obj->setData('now');

    		return Response::json( Arquivo::gravarArquivo($obj) );

    	}

    }
    
    /**
     * retorna um arquivo do banco pra um arquivo em disco, retornando o caminho do arquivo.
     * Função chamada via Ajax.
     * @param int $id id do arquivo
     * @param string $caminho caminho onde o arquivo sera salvo
     * @param Request $request
     */
	public function gerarFile($id,$caminho)
    {
        $Ret = Arquivo::gerarFile($id);

            $novoNome = $Ret['nome'];
            $conteudo = $Ret['conteudo'];
            $tamanho  = $Ret['tamanho'];
            $extensao = $Ret['extensao'];

            //$temp = substr(md5(uniqid(time())), 0, 10);
            //$novoNome = $temp . $novoNome;
            
            $novoNome = $caminho.$id.$extensao;
            
            $novoarquivo = fopen($novoNome, "a+");
            fwrite($novoarquivo, $conteudo);
            fclose($novoarquivo);

            return $novoNome;
    }




    /**
     * Grava arquivo no banco de dados.
     * OBS.: Utilizado por meio do Angular.
     *
     * @param json $arquivo
     */
    public static function gravarArquivo($arquivo, $tipo_retorno = null) {

        $arqId = Arquivo::gerarIdArquivo();

        //redireciona para tela de erro
        if ($arqId['resposta'][0] === 'erro') return view('errors.query', ['erro' => $arqId['resposta'][1]]);

        $obj = new Arquivo();
        $obj->setId($arqId['id'][0]->ID);
        $obj->setVinculo($arquivo['VINCULO']);
        $obj->setSequencia(1);
        $obj->setTabela($arquivo['TABELA']);
        
        // Se o tipo não vier reduzido.
        if (strlen($arquivo['TIPO']) > 3) {

            switch ( $arquivo['TIPO'] ) {
                case "application/pdf"                  : $tipo = "pdf"; break;
                case "application/octet-stream"         : $tipo = "exe"; break;
                case "application/zip"                  : $tipo = "zip"; break;
                case "application/msword"               : $tipo = "doc"; break;
                case "application/vnd.ms-excel"         : $tipo = "xls"; break;
                case "application/vnd.ms-powerpoint"    : $tipo = "ppt"; break;
                case "image/gif"                        : $tipo = "gif"; break;
                case "image/png"                        : $tipo = "png"; break;
                case "image/jpg"                        : $tipo = "jpg"; break;
                case "image/jpeg"                       : $tipo = "jpg"; break;
                case "audio/mpeg"                       : $tipo = "mp3"; break;

                case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"         : $tipo = "xls"; break;
                case "application/vnd.openxmlformats-officedocument.presentationml.presentation" : $tipo = "ppt"; break;
                case "application/vnd.openxmlformats-officedocument.wordprocessingml.document"   : $tipo = "doc"; break;

                default                                 : $tipo = 'unknown';
            }
        }
        else {
            $tipo = $arquivo['TIPO'];
        }

        $obj->setTipo($tipo);
        $obj->setTamanho($arquivo['TAMANHO']);
        $obj->setNome($arquivo['NOME']);

        $binUrl = realpath($arquivo['BINARIO']);
        $obj->setConteudo(file_get_contents($binUrl));
        
        $obj->setUsuarioId(!empty(\Auth::user()->CODIGO) ? \Auth::user()->CODIGO : 999);
        $obj->setData('NOW');

        $ret = Arquivo::gravarArquivo($obj);

        if($tipo_retorno == null){
            return Response::json( $ret );
        }else{
            return $ret;
        }
    }
    
    /**
     * Excluir arquivos do diretório temporário de acordo com o usuário, 
     * que deve estar no início do nome do arquivo.
     * Necessário passar o NOME DO DIRETÓRIO dos arquivos.
     * OBS.: Utilizado por meio do Angular.
     *
     * @param Request $request
     * @return json
     */
    public function excluirArquivoTmpPorUsuario(Request $request) {

        try {

            $param = json_decode(json_encode($request->all()));

            $dir = env('APP_TEMP', '') . $param->DIRETORIO . '/';
            $arq = \Auth::user()->CODIGO . '-*';

            foreach (glob($dir.$arq) as $arquivo) {

                if (is_file($arquivo))
                    unlink($arquivo);
            }
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Excluir arquivo.
     * OBS.: Utilizado por meio do Angular.
     * 
     * @param integer $arquivoId
     */
    public static function excluir($arquivoId) {

        Arquivo::excluir($arquivoId);
    }
    
    public function svnGenerate(Request $request) {
        
        $con = new _Conexao;
        
        try {
            set_time_limit(0);
            log_info('Trantando Commit SVN - Inicio');
            
            $endsWith = function ($haystack, $needle)
            {
                $length = strlen($needle);
                if ($length == 0) {
                    return true;
                }

                return (substr($haystack, -$length) === $needle);
            };
            
            $revision_base = $con->query('SELECT MAX(S.REVISION)-20 REVISION FROM TBSVN S');

            $revision_base = !isset($revision_base[0]->REVISION) || !($revision_base[0]->REVISION > 0)  ? 0 : $revision_base[0]->REVISION;

            if ( $revision_base == 0 ) {
                $svn_limit = '';
            } else {
                $svn_limit = '<S:limit>26</S:limit>';
            }
            
            $username='Emerson';
            $password='123';
            $URL='https://192.168.0.179:444/svn/GC_WEB';
            $headers = array();
            $headers[] = 'Accept: application/xml';
            $headers[] = 'Content-Type: application/xml';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$URL);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "REPORT");
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '<S:log-report xmlns:S="svn:"><S:end-revision>0</S:end-revision>' . $svn_limit . '<S:discover-changed-paths/><S:encode-binary-props /><S:path></S:path></S:log-report>');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
            $result=curl_exec ($ch);
            curl_close ($ch);

            $xml = simplexml_load_string($result);
            $ns = $xml->getNamespaces(true);
            foreach ($xml->children($ns['S'])->{'log-item'} as $rev) {

                $d_ns = $rev->children($ns['D']);
                $s_ns = $rev->children($ns['S']);

                $head = (object)[
                    'REVISION'  => (string)$d_ns->{'version-name'},
                    'AUTHOR'    => (string)$d_ns->{'creator-displayname'},
                    'DATE'      => date('Y.m.d H:i:s', strtotime((string)$s_ns->{'date'})),
                    'MSG'       => (string)$d_ns->{'comment'}
                ];
                
                Arquivo::svnHead($head, $con);

                foreach ( $rev->children($ns['S']) as $key => $file ) {

                    if ( $endsWith($key, '-path') ) {
                        
                        $action = '';

                        if ( $key == 'added-path' ) {
                            $action = 'A';
                        } else
                        if ( $key == 'replaced-path' ) {
                            $action = 'R';
                        } else
                        if ( $key == 'deleted-path' ) {
                            $action = 'D';
                        } else
                        if ( $key == 'modified-path' ) {
                            $action = 'M';
                        }

                        $body = (object) [
                            'REVISION'  => $head->REVISION,
                            'ACTION'    => $action,
                            'TYPE'      => (string)$file->attributes()->{'node-kind'},
                            'TEXT_MODS' => (string)$file->attributes()->{'text-mods'} == true ? 1 : 0,                        
                            'PROP_MODS' => (string)$file->attributes()->{'prop-mods'} == true ? 1 : 0,                        
                            'FILE'      => (string) $file
                        ];
                            
                        Arquivo::svnBody($body, $con);
                    }
                }
            }
            
            $con->commit();
            
            log_info('Trantando Commit SVN - Final - SUCESSO');
            return response()->json([
               'SUCCESS_MSG' => 'Dados do SVN atualizados com sucesso.'
            ]);
        }
        catch (Exception $e)
        {
            log_info('Trantando Commit SVN - Final - FALHA');
            $con->rollback();
			throw $e;
		}       

    }

}