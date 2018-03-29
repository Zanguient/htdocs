<?php

namespace app\Http\Controllers\vendas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Vendas\_12050;
use App\Models\DTO\Admin\_11010;
use PDF;

/**
 * Controller do objeto _12050 - RELATORIO DE PEDIDOS X FATURAMENTO X PRODUCAO
 */
class _12050Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'vendas/_12050';
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'vendas._12050.index', [
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
    
    public function relatorio(Request $request) {

        set_time_limit(0);
        
		//talao
		$param = $request->all();
		$header = [];
        
        $dados = _12050::relatorio($param);
        
        $soma_pedidos      = 0;
        $soma_faturamento  = 0;
        $soma_devolucao    = 0;
        $soma_defeito      = 0;
        $soma_defeito_1    = 0;
        $soma_defeito_2    = 0;
        $soma_producao     = [];
        $soma_producao_1   = [];
        $soma_producao_2   = [];

        $soma_defeitos     = [];
        
        $datas = [];
        
        foreach ( $dados['PRODUCAO'] as $prod ){
            foreach ( $prod['DADO'] as $dia ){
                $add=true;
                foreach ( $datas as $data ){
                    if($data == $dia->DATA_PRODUCAO){
                        $add = false;
                    }
                }
                if($add){
                    array_push($datas, $dia->DATA_PRODUCAO);
                }
            }
        }

        foreach ( $dados['FATURAMENTO'] as $item ){
            $add=true;
            foreach ( $datas as $data ){
                if($data == $item->DATA_EMISSAO){
                    $add = false;
                }
            }
            if($add){
                array_push($datas, $item->DATA_EMISSAO);
            }
        }

        foreach ( $dados['PEIDOS'] as $item ){
            $add=true;
            foreach ( $datas as $data ){
                if($data == $item->DATA){
                    $add = false;
                }
            }
            if($add){
                array_push($datas, $item->DATA);
            }
        }
        
        asort($datas);
        
        $faturamento = [];
        $devolucao   = [];
        $defeito     = [];
        $defeitos_g  = [];
        $producao    = [];
        $pedido      = [];
        $itens_prod  = [];
        
        $retorno = [];
        $retorno['DADOS'] = [];
        
        $unidade = '';
        
        foreach ( $datas as $data ){
            unset($faturamento);
            unset($devolucao);
            unset($defeito);
            unset($defeitos);
            unset($pedido);
            
            foreach ( $dados['FATURAMENTO'] as $faturametos ){
                if($data == $faturametos->DATA_EMISSAO){
                     $faturamento = $faturametos;
                }
            }
            if(!isset($faturamento)){
                 $faturamento = (object) array('QUANTIDADE' => '0');
            }
            
            foreach ( $dados['DEVOLUCAO'] as $devolucaes ){
                if($data == $devolucaes->DATA_ENTRADA){
                     $devolucao = $devolucaes;
                }
            }
            if(!isset($devolucao)){
                 $devolucao = (object) array('QUANTIDADE' => '0');
            }
            
            foreach ( $dados['DEFEITO'] as $defeitos ){
                if($data == $defeitos->DATA){
                     $defeito = $defeitos;
                }
            }
            if(!isset($defeito)){
                 $defeito = (object) array('QUANTIDADE' => '0','QTD_TURNO1' => '0', 'QTD_TURNO2' => '0');
            }
            
            foreach ( $dados['PEIDOS'] as $pedidos ){
                if($data == $pedidos->DATA){
                     $pedido = $pedidos;
                }
            }
            if(!isset($pedido)){
                 $pedido = (object) array('QUANTIDADE' => '0','DATA' => $data);
            }
            
            $soma_pedidos      = $soma_pedidos     + $pedido->QUANTIDADE;
            $soma_faturamento  = $soma_faturamento + $faturamento->QUANTIDADE;
            $soma_devolucao    = $soma_devolucao   + $devolucao->QUANTIDADE;
            $soma_defeito      = $soma_defeito     + $defeito->QUANTIDADE;
            $soma_defeito_1    = $soma_defeito_1   + $defeito->QTD_TURNO1;
            $soma_defeito_2    = $soma_defeito_2   + $defeito->QTD_TURNO2;
            
            $desc = '';

            foreach ( $dados['PRODUCAO'] as $prod ){

                $desc = $prod['DESC'];
                $code = $prod['CODE'];

                unset($itens_prod);
                if(!array_key_exists($prod['CODE'],$producao)){$producao[$prod['CODE']] = [];}
                
                foreach ( $prod['DADO'] as $dia ){
                    if($data == $dia->DATA_PRODUCAO){

                        $dia->DESC  = $desc;
                        $dia->CODE  = $code;
                        $itens_prod = $dia;

                    } 
                }
                if(!isset($itens_prod)){
                    $itens_prod = (object) array('QUANTIDADE' => '0','QTD_TURNO1' => '0', 'QTD_TURNO2' => '0', 'UNIDADE' => '','DEFEITO'=> 0, 'TALOES' => 0,'DESC' => $desc,'CODE' => $code);
                }
                
                if(!array_key_exists($prod['CODE'],$soma_producao)){
                    $soma_producao[$prod['CODE']] = (object) array(
                        'QUANTIDADE' => 0,
                        'QTD_TURNO1' => 0,
                        'QTD_TURNO2' => 0,
                        'UNIDADE'    => '',
                        'TALOES'     => 0,
                        'DEFEITO'    => 0
                    );
                }
                
                if($itens_prod->UNIDADE != ''){
                    $unidade = $itens_prod->UNIDADE;
                }
                
                $soma_producao[$prod['CODE']] = (object) array(
                    'QUANTIDADE' => $soma_producao[$prod['CODE']]->QUANTIDADE + $itens_prod->QUANTIDADE,
                    'QTD_TURNO1' => $soma_producao[$prod['CODE']]->QTD_TURNO1 + $itens_prod->QTD_TURNO1,
                    'QTD_TURNO2' => $soma_producao[$prod['CODE']]->QTD_TURNO2 + $itens_prod->QTD_TURNO2,
                    'TALOES'     => $soma_producao[$prod['CODE']]->TALOES     + $itens_prod->TALOES,
                    'UNIDADE'    => $unidade,
                    'DEFEITO'    => $soma_producao[$prod['CODE']]->DEFEITO    + $itens_prod->DEFEITO
                );
                
                array_push($producao[$prod['CODE']], $itens_prod);
            }

            $itens_def;

            foreach ( $dados['DEFEITOS'] as $prod ){

                $desc = $prod['DESC'];
                $code = $prod['CODE'];

                $defeitos_g[$code] = [];

                unset($itens_def);
                if(!array_key_exists($prod['CODE'],$defeitos_g)){$defeitos_g[$code] = [];}
                
                foreach ( $prod['DADO'] as $dia ){
                    if($data == $dia->DATA_PRODUCAO){

                        $dia->DESC  = $desc;
                        $dia->CODE  = $code;
                        $itens_def  = $dia;

                    } 
                }

                if(!isset($itens_def)){
                    $itens_def = (object) array('QUANTIDADE' => '0','QTD_TURNO1' => '0', 'QTD_TURNO2' => '0', 'QTD_1'=> 0, 'QTD_2' => 0, 'QTD_3' => 0,'DESC' => $desc,'CODE' => $code);
                }
                
                if(!array_key_exists($prod['CODE'],$soma_defeitos)){
                    $soma_defeitos[$prod['CODE']] = (object) array(
                        'QUANTIDADE' => 0,
                        'QTD_TURNO1' => 0,
                        'QTD_TURNO2' => 0,
                        'QTD_1'      => 0,
                        'QTD_2'      => 0,
                        'QTD_3'      => 0,
                        'CODE'       => $code,
                        'DESC'       => $desc
                    );
                }
                
                $soma_defeitos[$prod['CODE']] = (object) array(
                    'QUANTIDADE' => $soma_defeitos[$code]->QUANTIDADE + $itens_def->QUANTIDADE,
                    'QTD_TURNO1' => $soma_defeitos[$code]->QTD_TURNO1 + $itens_def->QTD_TURNO1,
                    'QTD_TURNO2' => $soma_defeitos[$code]->QTD_TURNO2 + $itens_def->QTD_TURNO2,
                    'QTD_1'      => $soma_defeitos[$code]->QTD_1      + $itens_def->QTD_1,
                    'QTD_2'      => $soma_defeitos[$code]->QTD_2      + $itens_def->QTD_2,
                    'QTD_3'      => $soma_defeitos[$code]->QTD_3      + $itens_def->QTD_3,
                    'CODE'       => $code,
                    'DESC'       => $desc
                );
                
                array_push($defeitos_g[$prod['CODE']], $itens_def);
            }
            
            $res = [
                'FATURAMENTO' => $faturamento,
                'DEVOLUCAO'   => $devolucao,
                'DEFEITO'     => $defeito,
                'PRODUCAO'    => $producao,
                'PEDIDOS'     => $pedido,
                'DATA'        => $data,
                'DEFEITOS'    => $defeitos_g,
            ];
            
            array_push($retorno['DADOS'], $res);
        }
        
        $retorno['TOTAL'] = [
                'FATURAMENTO'   => $soma_faturamento,
                'DEVOLUCAO'     => $soma_devolucao,
                'DEFEITO'       => $soma_defeito,
                'PRODUCAO'      => $soma_producao,
                'PEDIDOS'       => $soma_pedidos,
                'DEFEITO1'      => $soma_defeito_1,
                'DEFEITO2'      => $soma_defeito_2,
                'DEFEITOS'      => $soma_defeitos,
            ];
        
        $retorno['FAMILIAS']   = $dados['FAMILIAS'];
        $retorno['FAMILIA']    = $param['familias'];
        $retorno['FATFAMILIA'] = $dados['FATFAMILIA'];
        
        return view(
            'vendas._12050.show.relatorio', [
            'dados' => $retorno
		]);	
		
	}
    
    public function detalharFamilia(Request $request) {
        $param = $request->all();
        $header = [];
        
        $dados = _12050::detalharFamilia($param);
        
        return view(
            'vendas._12050.show.FamilhaDetalhado', [
            'dado1' => $dados[0],
            'dado2' => $dados[1],
            'dado3' => $dados[2]
        ]);
        
    }

    public function detalharFamilia2(Request $request) {
        $param = $request->all();
        $header = [];
        
        $dados = _12050::detalharFamilia2($param);
        
        return view(
            'vendas._12050.show.FamilhaDetalhado', [
            'dado1' => $dados[0],
            'dado2' => $dados[1],
            'dado3' => $dados[2]
        ]);
        
    }
    
    public function relatorioPDF(Request $request) {
        
		//talao
		$param = $request->all();
		$header = [];
        
        $dados = _12050::relatorio($param);
        
        $entrada = '';
        if($param['periodo_pedido'] == 'd'){
            $entrada = 'Dat. de Emição';
        }else{
            $entrada = 'Prev. Faturamento';
        }
        
        $perfil = '';
        if($param['perfil_grupo'] == 't'){
            $perfil = 'Todos';
        }else{
            if($param['perfil_grupo'] == 'n'){
                $perfil = 'Normal(N)';
            }else{
                if($param['perfil_grupo'] == 'e'){
                    $perfil = 'Especial(E)';
                }else{
                    $perfil = 'null';
                }
            }
        }
        
        $familia = 'Todas';
        if($param['familias'] != ''){$familia = $param['familias'];}
        
        $filtro = "ESTABELECIMENTO:".$param['estabelecimento']." FAMÍLIA:".$familia." PEDIDOS:".$entrada." PERFIL:".$perfil;
        $vercao = "v2016.11.30/".\Auth::user()->USUARIO;
        
		$file_name = 'Faturamento_x_Producao_x_Pedidos-' . rand() . '.pdf';
        $path_file = '/assets/temp/relatorios/';
        
        //caminho e nome do arquivo
        $arq_temp = public_path().$path_file.$file_name;

        //apaga arquivos de relatórios antigos
        deleleFilesTree(public_path().$path_file);
        
        //apagar arquivo, caso já exista
        if(file_exists($arq_temp) ) {
            unlink($arq_temp);
        }
        
        $pasta_corpo = 'vendas._12050.show.pdf.consumo';
        
        PDF::setPaper('A4','portrait')
            ->loadView($pasta_corpo.'.2_body', [
				'dados'				=> $dados
			])  
            ->setOption('header-html',view($pasta_corpo.'.1_header', [
                'menu'				=> $this->menu,
                'header'			=> $header,
                'filtro'            => $filtro,
                'vercao'            => $vercao
            ]),'html')     
            ->setOption('footer-html',view($pasta_corpo.'.3_footer'),'html')   
            ->save($arq_temp)
        ;  
        
        if ( $request->isMethod('post') ) {
            return $path_file.$file_name;
        } else {
            return view($pasta_corpo.'.2_body', ['dados' => $dados]);
        }		
		
	}

    public function faturamentoDia(Request $request) {
        $param = $request->all();
        $header = [];
        
        $dados = _12050::faturamentoDia($param);
        
        return view(
            'vendas._12050.show.detalhar', [
            'base'  => $param['val_base'],
            'dados' => $dados[0],
            'dado2' => $dados[1],
            'dado3' => $dados[2],
            'dado4' => $dados[3],
            'dado5' => $dados[4],
            'dado6' => $dados[5]
        ]);
        
    }

    public function pedidosDia(Request $request) {
        $param = $request->all();
        $header = [];
        
        $dados = _12050::pedidosDia($param);

        return view(
            'vendas._12050.show.detalhar', [
            'base'  => $param['val_base'],
            'dados' => $dados[0],
            'dado2' => $dados[1],
            'dado3' => $dados[2],
            'dado4' => $dados[3],
            'dado5' => $dados[4],
            'dado6' => $dados[5]
        ]);
        
    }

    public function devolucaoDia(Request $request) {
        $param = $request->all();
        $header = [];
        
        $dados = _12050::devolucaoDia($param);
        
        return view(
            'vendas._12050.show.detalhar', [
            'base'  => $param['val_base'],
            'dados' => $dados[0],
            'dado2' => $dados[1],
            'dado3' => $dados[2],
            'dado4' => $dados[3],
            'dado5' => $dados[4],
            'dado6' => $dados[5]
        ]);
        
    }

    public function defeitoDia(Request $request) {

        set_time_limit(0);

        $param = $request->all();
        $header = [];
        
        $dados = _12050::defeitoDia2($param);

        $data_inicio = date('d.m.Y', strtotime($param['periodo_inicial']));
        $data_fim    = date('d.m.Y', strtotime($param['periodo_final']));

        $ret = [
            'defeito'      => $dados[0],
            'producao'     => $dados[1],
            'filtro'       => $param,
            'data_inicio'  => $data_inicio,
            'data_fim'     => $data_fim,
        ];

        return Response::json($ret);       
    }

    public function producaoDia(Request $request) {
        $param = $request->all();
        $header = [];
        
        $dados = _12050::producaoDia($param);
        
        return view(
            'vendas._12050.show.detalhar2', [
            'base'  => $param['val_base'],
            'dados' => $dados[0],
            'dado2' => $dados[1],
            'dado3' => $dados[2],
            'dado6' => $dados[3],
            'dado7' => $dados[4],
            'dado8' => $dados[5],
            'filtro'=> $param,
            'mostrar_linha' => 1 //mostrar tab de detalhamento por linha se 0 não mostra
        ]);
    } 

    public function producaoDia2(Request $request) {

        $param = $request->all();
        $header = [];
        
        $dados = _12050::producaoDia2($param);
        
        return view(
            'vendas._12050.show.detalhar2', [
            'base'  => $param['val_base'],
            'dados' => $dados[0],
            'dado2' => $dados[1],
            'dado6' => $dados[2],
            'filtro'=> $param,
            'mostrar_linha' => 0 //mostrar tab de detalhamento por linha se 0 não mostra
        ]);
        
    }
}