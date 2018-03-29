<?php

namespace app\Http\Controllers\Opex;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Opex\_25900;
use App\Models\DTO\Admin\_11010;
use Illuminate\Http\Request;



/**
 * Controller do objeto _25900
 */
class _25900Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'opex/_25900';
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'opex._25900.index', [
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
    
    /**
	 * Consultar Setores
     * @param [] $request
	 */
    public function consultarSetor(Request $request) {
        
        $dados = $request->all();
        
        return _25900::consultarSetor($dados);
	}
    
    /**
	 * Consultar Perspectivas
     * @param [] $request
	 */
    public function consultarPerspectiva(Request $request) {
        
        $dados = $request->all();
        
        return _25900::consultarPerspectiva($dados);
    }
    
    /**
	 * Soma colunas
     * @param [] $dados
     * @param int $tipo tipo de tratamento 1:soma 2:media
	 */
    public function TratarColunasIndicador($dados,$tipo,&$data_prod,&$data_indicador) {
        
        $colunas = [];
        $add = 0;
        $menor_data   = $data_indicador;
        $data_tprod   = $data_prod;
        
        //producao dia e noite
        foreach ($dados as $item){
            
            $campo = $item->CAMPO;
            if($tipo == 2){
                $valor =  $this->formatarN($item->VALOR,4);
            }else{
                $valor = $item->VALOR;
            }
            
            $valor = $this->iif($valor == '',0,$valor);
            
            $data  = $item->DATA_TRATADO;
            $prod  = $item->DATA;
            
            $add = 0;
            foreach($colunas as $key => $value){
                if($key == $campo){
                    $add = 1;
                    
                    if($tipo == 1){
                        $colunas[$key]['VALOR'] = $value['VALOR'] + $valor;
                        $colunas[$key]['CONT']  = $value['CONT'] + 1;
                    }else{
                        if($tipo == 2){
                            $colunas[$key]['VALOR'] = $value['VALOR'] + $valor;
                            
                            if($valor > 0){
                                $colunas[$key]['CONT']  = $value['CONT'] + 1;
                            }else{
                                $colunas[$key]['CONT']  = $value['CONT'];
                            }
                            
                        }else{
                            log_erro('Tipo de tratamento do indicador invalido!');
                        }
                    }     
                }
            }
            
            if($add == 0){
                $colunas[$campo] = ['VALOR'=> $valor, 'CONT'=> 1];
            }
            
            if($menor_data > $data){
                $menor_data = $data;
            }
            
            if($data_tprod > $prod){
                $data_tprod = $prod;
            }
            
        }
        
        if($tipo == 2){
            foreach($colunas as $key => $value){
                $colunas[$key]['VALOR'] = number_format( (tofloat($value['VALOR']) / $value['CONT']) , 2, '.', '');
            }
        }
        
        $data_indicador = $menor_data;
        $data_prod      = $data_tprod;
        
        return ['INDICADOR' => $colunas,'DATA_TRATADO' => $menor_data];
    }

    /**
	 * Consultar Areas
     * @param [] $request
	 */
    public function consultarArea(Request $request) {
        
        $dados = $request->all();
        
        return _25900::consultarArea($dados);
	}
    
    /**
	 * Filtrar valor
     * @param [] $dados
     * @param string $campo
	 */
    public function filterCampo($dados,$campo) {
        
        $ret = [];
        
        foreach ($dados as $dado){

            if($dado->CAMPO == $campo){
                $ret = $dado;
            }

        }
        
        return $ret;
	}
    
    public function filterCampo_r($dados,$campo) {
        
        $ret = [];
        
        foreach ($dados as $dado){

            if($dado->CAMPO == $campo){
                $ret = $dado;
            }

        }
        
        return $ret;
	}
    
    function formatarN($valor,$casas){
        return  number_format( floatval( str_replace(',','.', $valor)),$casas,'.','');
    }
    
    function formatarN2($valor,$casas){
        return  number_format( floatval( str_replace(',','.', $valor)),$casas,',','');
    }
    
    /**
	 * Consultar cor
     * @param [] $faixa
     * @param numeric $valor
     * @param numeric $peso
     * @param numeric $nota
	 */
    public function getCor($faixa,$valor,&$cor,&$nota,$tipo,&$pesofaixa) {
        $pes_faixa = 0;
        $min_faixa = 0;
        $max_faixa = 0;
        $val_faixa = 0;
        $ord_faixa = 0;
        $fat_faixa = 0;
        
        $mv_faixa = 0;
        $mp_faixa = 0;

        $val = floatval( str_replace(',','.', $valor));
        
        foreach ($faixa as $faixa) {
            
            $min = floatval( str_replace(',','.', $faixa->VALOR_MINIMO));
            $max = floatval( str_replace(',','.', $faixa->VALOR_MAXIMO));

            if(($val >= $min) && ($val <= $max)){

                $cor       = $faixa->COR_ID;
                $pes_faixa = $faixa->PESO;
                $ord_faixa = $faixa->ORDEM;
                $min_faixa = $min;
                $max_faixa = $max;
                $val_faixa = $val;
                $fat_faixa = $faixa->FATOR_PESO;
            }
            
            if($faixa->ORDEM == 1){
                $mv_faixa = $max;
                $mp_faixa = $faixa->PESO;
            }
            
        }

        

        if($tipo == 1){
            if($valor < 0.0001){
                $nota = 0;
            }else{

                if($min_faixa < 0.0001){
                    $nota = ($val_faixa/$max_faixa) * $pes_faixa;
                }else{
                    if($ord_faixa == 1){
                        $nota = ($val_faixa/$min_faixa) * $pes_faixa;
                    }else{
                        $nota = ($val_faixa/$max_faixa) * $pes_faixa;       
                    }    
                } 
            }
        }else{
            

            if($valor < 0.0001){
                $nota = $pes_faixa;
            }else{
                if($ord_faixa == 1){
                    $nota = $pes_faixa;
                }else{
                    $nota = ($min_faixa/$val_faixa) * $pes_faixa;       
                }       
            }   
        }

        if($nota > $pes_faixa){
            $nota = $pes_faixa;
        } 

        $pesofaixa = number_format($pes_faixa , 2, '.', '');
        $nota_temp = number_format($nota      , 2, '.', '');

        $nota = $nota_temp;
	}
    
    /**
	 * iif
     * @param [] $request
	 */
    public function iif($codicao,$se_verdadeiro,$se_falso) {
        if($codicao){
            return $se_verdadeiro;
        }else{
            return $se_falso;
        }
    }
    
    /**
	 * Consultar um indicador
     * @param [] $request
	 */
    public function filtarIndicador(Request $request) {
        
        $dados = $request->all();
        
        if(!isset($dados['seto'])){
            log_erro('Selecione pelomenos um setor!');
        }
        
        $estabelecimento_id     = $dados['estb'];
        $area_id                = $dados['area'];
        $perspectiva            = $dados['pers'];
        $setores                = $dados['seto'];
        $grupos                 = $dados['grup'];
        $data_inicial           = $dados['data'];
        $data_final             = $dados['datb'];

        $familia                = '3'; //$dados['estb'];
        
        $setorList = '';
        $grupoList = '';
        
        //monta lista com setores 
        foreach($setores as $item){
            if($item > 0){
                if($setorList == ''){
                    $setorList = ''.$item;
                }else{
                    $setorList = $setorList.','.$item;
                }
            }
        }
        
        //monta lista com grupos 
        foreach($grupos as $item){
            if($item > 0){
                if($grupoList == ''){
                    $grupoList = ''.$item;
                }else{
                    $grupoList = $grupoList.','.$item;
                }
            }
        }
        


        //configurações da area do BSC (obj)
        //TELA - nome do arquivo que fara o tratamento do resultado da area
        //COMPONENTE_ID - id do compenente, o componente é um panel estra na tela (5s)
        //DESCRICAO - descricao que sera usada na tela
        $AreasConf = _25900::consultarAreaConf($area_id);
        
        //dados dos setores selecionados (array(obj))
        //VALOR - GPID, CCusto, ou dados para serem usados no filtro do indicador
        $SetoresConf = _25900::consultarSetoresConf($grupoList,$setorList);
        
        //Dados das perspectivas (array(obj))
        //INDICADOR_ID - id dos indicadores
        //ORDEM - ordem dos indicadores
        //AGRUPAMENTO_ID - id do grupo  do indicador
        $PerspectivaConf = _25900::consultarPerspectivaConf($perspectiva);
        
        //Indicadores do bsc (array(obj))
        //ID - id do indicador
        //INDICADOR - array(obj) indicadores
            //DESCRICAO - descricao do indicador 
            //TIPO - tipo do indicador 1-se o indicador é quanto maior melhor 2-se o indicador for quanto menor melhor
        //DADOS - (array(obj)) - valores dos indicadores
        $dadosConsulta = [
            'PerspectivaConf'       => $PerspectivaConf,
            'SetoresConf'           => $SetoresConf,
            'estabelecimento_id'    => $estabelecimento_id,
            'data_inicial'          => $data_inicial,
            'data_final'            => $data_final,
            'area'                  => $area_id,
            'perspectiva'           => $perspectiva
        ];
        
        //descricao dos setores selecionados
        $a = _25900::consultarDescricao($setorList,$grupoList);
        $descricao = $a[0];
        $ccusto    = $a[1];

        if(strlen($descricao)>10){
            $descricao = substr($descricao,0,40).'...';
        }
        
        $data_producao    = _25900::consultarDataProd($familia);
        
        $agrupamentos     = _25900::consultarAgrupamentos($perspectiva);
        
        $Indicadores      = _25900::consultarIndicadores($dadosConsulta);
        
        $ret              = _25900::execComponente($AreasConf->SQL_COMPONENTE,$ccusto,$AreasConf->FLAG);
        $res              = _25900::consultarIndicadorFaixa($AreasConf->FLAG);
        
        $dados_componente = [
          'DADOS' => $ret,
          'FAIXA' => $res,
          'TELA'  => $AreasConf->TELA_COMPONENTE
        ];
        
        if($AreasConf->TELA_COMPONENTE != 'padrao'){
            $dados_componente['DADOS'] = include ('../app/Http/Controllers/Opex/Include/Componentes/'.$AreasConf->TELA_COMPONENTE.'.php');
        }
        
        return include ('../app/Http/Controllers/Opex/Include/Telas/'.$AreasConf->TELA.'.php');

        //no arquivo de configuração da tela que foi definida para tratar o bsc podem ser usadas as variaveis abeixo

        //variaveis que podem ser usadas e suas hierarquias
        //$AreasConf
            //configurações da area do BSC (obj)
            //TELA - nome do arquivo que fara o tratamento do resultado da area
            //COMPONENTE_ID - id do compenente, o componente é um panel estra na tela (5s)
            //DESCRICAO - descricao que sera usada na tela

        //$SetoresConf
            //dados dos setores selecionados (array(obj))
            //VALOR - GPID, CCusto, ou dados para serem usados no filtro do indicador

        //$PerspectivaConf
            //Dados das perspectivas (array(obj))
            //INDICADOR_ID - id dos indicadores
            //ORDEM - ordem dos indicadores
            //AGRUPAMENTO_ID - id do grupo  do indicador

        //$$dadosConsulta
            //Indicadores do bsc (array(obj))
            //ID - id do indicador
            //INDICADOR - array(obj) indicadores
                //DESCRICAO - descricao do indicador 
                //TIPO - tipo do indicador 1-se o indicador é quanto maior melhor 2-se o indicador for quanto menor melhor
                //DADOS - (array(obj)) - valores dos indicadores
                

        //$Indicadores
            //indicadores e seus dados pre tratados
            //ID
            //INDICADOR 
                //ID
                //DESCRICAO
                //SQL_ID
                //TIPO
                //FAIXA_ID
                //ordem
            //DADOS
            //FAIXA
                //ID
                //FAIXA_ID
                //DESCRICAO
                //VALOR_MAXIMO
                //VALOR_MINIMO
                //DATA
                //DATA_TRATADO
                //COR_ID
                //ORDEM
                //SETOR_VALOR
                //AREA_ID
                //PERSPESCTIVA_ID
                //PESO
                //PESO_TOTAL

        //$dados_componente
            //dados da tela componente, ex: indicador do 5s, carinhas, termometro de qualidade...
            //DADOS
                //array montado da maneira que o arquivo de tratamento definido retornar
            //TELA
                //nome do arquivo de tratamento da tela de componente

        //$$descricao
            //descrição dos setores ou grupo de setores selecionados
            //ex: F01, F02, F03...
            //ex: Todos
            //exp: Coloridos

        //$data_producao
            //data de produção da familia configurada


	}
    
}