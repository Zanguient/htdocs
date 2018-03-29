<?php

namespace app\Http\Controllers\Ppcp;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Ppcp\_22021;
use App\Models\DTO\Admin\_11010;
use PDF;

/**
 * Controller do objeto _22021 - Relatório de peças disponíveis para consumo
 */
class _22021Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'ppcp/_22021';
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'ppcp._22021.index', [
            'permissaoMenu' => $permissaoMenu,
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
	
	public function relatorioPecaDisponivel(Request $request) {
		
		//talao
		$param = (object)[];
		
		isset($request->estabelecimento_id)										? $param->ESTABELECIMENTO_ID	= $request->estabelecimento_id	: null;
		isset($request->gp_id)													? $param->GP_ID					= $request->gp_id				: null;
		isset($request->data_ini) && !empty($request->data_ini)					? $param->DATA_INI				= $request->data_ini			: null;
		isset($request->data_fim) && !empty($request->data_fim)					? $param->DATA_FIM				= $request->data_fim			: null;
		isset($request->status)													? $param->STATUS				= $request->status				: null;
		isset($request->somente_sobra) && $request->somente_sobra === 'true'	? $param->SOMENTE_SOBRA			= $request->somente_sobra		: null;
		
		$header = (object)[
			'ESTABELECIMENTO_ID'	=> $request->estabelecimento_id,
			'GP_ID'					=> $request->gp_id,
			'GP_DESCRICAO'			=> $request->gp_descricao,
			'DATA_INI'				=> $request->data_ini,
			'DATA_FIM'				=> $request->data_fim,
			'STATUS'				=> $request->status,
			'SOMENTE_SOBRA'			=> $request->somente_sobra === 'true' ? 'Sim' : 'Não'
		];
		
        $talao = _22021::relatorioPecaDisponivelTalao($param);
		
		$file_name = 'PECAS-DISPONIVEIS-' . rand() . '.pdf';
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
            ->loadView('ppcp._22021.index.pdf.peca-disponivel.2_body', [
				'talao'				=> $talao
                //'peca_disponivel'	=> $pecas_disponiveis
			])  
            ->setOption('header-html',view('ppcp._22021.index.pdf.peca-disponivel.1_header', [
                'menu'				=> $this->menu,
                'header'			=> $header				
            ]),'html')     
            ->setOption('footer-html',view('ppcp._22021.index.pdf.peca-disponivel.3_footer'),'html')   
            ->save($arq_temp)
        ;  
        
        if ( $request->isMethod('post') ) {
            return $path_file.$file_name;
        } else {
            return view('ppcp._22021.index.pdf.peca-disponivel.2_body', ['talao' => $talao]);
        }		
		
	}

}