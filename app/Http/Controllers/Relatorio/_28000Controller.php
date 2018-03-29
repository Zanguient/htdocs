<?php

namespace app\Http\Controllers\Relatorio;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Relatorio\_28000;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Admin\_11080;

/**
 * Controller do objeto _28000 - Relatorios Personalizados
 */
class _28000Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'relatorio/_28000';
	
	public function index()
    {
		return view(
            'relatorio._28000.index', [
            'permissaoMenu' => [],
            'menu'          => $this->menu
		]);  
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
    	//$permissaoMenu = _11010::permissaoMenu($this->menu);

        $dados = _11080::show($id);
        
        return view(
            'relatorio._28000.show', [
            'permissaoMenu' => [],
            'menu'          => $this->menu,
            'dados'         => $dados
        ]);     
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

    public function exportcsv(Request $request){

        if ($request->ajax()) {

            $param      = $request->all();
            $conteudo   = $param['CONTEUDO'];

            $conteudo = str_replace('\\n', PHP_EOL , $conteudo);

            $Ret = array(
                "nome"      => 'TESTE',
                "tamanho"   => '1024',
                'conteudo'  => $conteudo
            );

            //$novoNome = $Ret['nome'];
            $novoNome = str_replace(" ","_",preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($Ret['nome'])))) . '.csv';
            
            $conteudo = $Ret['conteudo'];
            $tamanho = $Ret['tamanho'];

            $temp = substr(md5(uniqid(time())), 0, 10);
            $novoNome = $temp . $novoNome;

            $Dir = env('APP_TEMP', '');

            $n = $Dir . $novoNome;

            $novoarquivo = fopen($n , "a+");
            fwrite($novoarquivo, $conteudo);
            fclose($novoarquivo);

            $tamanho = filesize($n);

            $var = array(
                "nome" => $novoNome,
                "tamanho" => $tamanho
            );

            
            $headers = array(
                'Content-Type'   => 'binary/octet-stream',
                'Content-Length' => $tamanho
            );
            
            return \Response::download($n, $novoNome, $headers);
                
        }
    }

}