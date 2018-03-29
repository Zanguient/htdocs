<?php

namespace App\Http\Controllers\Opex;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Opex\_25800;
use App\Models\DTO\Helper\Arquivo;
use App\Http\Controllers\Helper\ArquivoController;
use App\Helpers\Helpers;
use App\Helpers\console;

/**
  * Controler do objeto 25800 (BSC-TV), ete objeto retorna as tela jÃ¡ com valores processados e calculos de cor
  * @package Opex
  * @category Controller
*/
class _25800Controller extends Controller
{

    /**
    * show do objeto 25800 (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function show()
    {   
        return view('opex._25800.show', ['auto' => '0','id' => '0','desc' => '','estab' => 0,'familia' => 0]);  
    }
    
    /**
    * show do objeto 25800 (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function auto(Request $request)
    {   
        $id = $request->cod;
        $desc = $request->desc;
        $familia = $request->familia;
        
        return view('opex._25800.show', ['auto' => '1','id' => $id,'desc' => $desc,'estab' => 0,'familia' => $familia]);  
    }
    
    /**
    * show do objeto 25800 (BSC-TV)
    * com estabelecimento
    * return view
    * @package Opex
    * @category Controller
    */
    public function slid(Request $request)
    {   
        $id = $request->cod;
        $desc = $request->desc;
        
        return view('opex._25800.show', ['auto' => '2','id' => $id,'desc' => $desc,'estab' => 0,'familia' => 0]);  
    }
    
    /**
    * show do objeto 25800 (BSC-TV)
    * com estabelecimento
    * return view
    * @package Opex
    * @category Controller
    */
    public function auto2(Request $request)
    {   
        $id = $request->cod;
        $desc = _25800::descfabrica($id);
        $estab = $request->estab;
        $familia = $request->familia;
        
        return view('opex._25800.show', ['auto' => '1','id' => $id,'desc' => $desc,'estab' => $estab,'familia' => $familia]);  
    }
    
    /**
    * show do objeto 25800 (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function slid2(Request $request)
    {   
        $id = $request->cod;
        $desc = _25800::descfabrica($id);
        $estab = $request->estab;
        
        return view('opex._25800.show', ['auto' => '2','id' => $id,'desc' => $desc,'estab' => $estab,'familia' => 0]);  
    }
    
    
    /**
    * calcula cor das perdas
    * return int
     * @param int $var1 valor percentual das perdas
     * @param int $var2 meta das perdas
    * @package Opex
    * @category Controller
    */
    public function getCorPerdas($var1, $var2)
    {   
        if($var1 < $var2){
           return 1; 
        }else{
           return 3; 
        } 
    }
    
    /**
    * calcula cor das perdas
    * return int
    * @param float $var1 perdas
    * @param float $dif producao
    * @param float $meta meta
    * @package Opex
    * @category Controller
    * @deprecated
    */
    public function getCorValor($var1,$dif, $meta)
    {   
        if($meta > 0){
           if(($var1 >= $meta) && ($var1 < 100)){
              $ret = 1; 
           }else{
                if($dif > 0){
                    $ret = 2; 
                }else{
                    $ret = 3;
                } 
           }
        }else{
            if($dif > 0){
                $ret = 2; 
            }else{
                $ret = 3;
            }   
        }
        
        return $ret;
    }
    
    /**
    * calcula cor das perdas
    * return int
     * @param int $meta1 meta 1
     * @param int $meta2 meta 2
     * @param int $valor valor
    * @package Opex
    * @category Controller
    */
    public function getCorValor2($meta1,$meta2, $valor)
    {   
        if($meta2 > 0){
           if($valor > $meta2){
              $ret = 1; 
           }else{
                if($valor < $meta1){
                    $ret = 3; 
                }else{
                    $ret = 2;
                } 
           }
        }else{
            $ret = 3;  
        }
        
        return $ret;
    }
    
    /**
    * calcula cor dos defeitos
    * return int
     * @param int $meta1 meta 1
     * @param int $meta2 meta 2
     * @param int $valor valor
    * @package Opex
    * @category Controller
    */
    public function getCorValor3($meta1,$meta2, $valor)
    {   
        if($meta1 > 0){
           if($valor < $meta1){
              $ret = 1; 
           }else{
                if($valor < $meta2){
                    $ret = 2; 
                }else{
                    $ret = 3;
                } 
           }
        }else{
            $ret = 3;  
        }
        
        return $ret;
    }
    
    /**
    * tela de producao do objeto 25800 (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function consultaprod(Request $request)
    {   
        if( $request->ajax() ) {
            
            $id         = $request->get('id');
            $grupo      = $request->get('grupo');
            $eficiencia = $request->get('eficiencia');
            $desc       = _25800::descfabrica($id);
            $ccusto     = $request->get('ccusto');
            $estab      = $request->get('estab');
            $data       = $request->get('data');
            $auto       = $request->get('autoinicia');
            $familia    = $request->get('familia');
            
            if(intval($auto) == 1){
                $data = _25800::dataproducao($familia);
            }
            
            $dados = [
                'id'            => $id,
                'grupo'         => $grupo,
                'eficiencia'    => $eficiencia,
                'desc'          => $desc,
                'ccusto'        => $ccusto,
                'estab'         => $estab,
                'data'          => $data
            ];
            
            $horasRanking = _25800::horasRanking($dados);

            $ret = _25800::consultaprod($dados);
            
            $tot = count($ret);
            
            if($tot > 0){
                $arr = (array)$ret[0];
            
                //descrição das eficiencias do mes
                $descEfic1 = 'ACIMA DE '.$arr['EFICBMEZ'];
                $descEfic2 = 'ENTRE '.$arr['EFICAMEZ'].' e '.$arr['EFICBMEZ'];
                $descEfic3 = 'ABAIXO DE '.$arr['EFICAMEZ'];

                //descriÃ§Ãµes das eficiencias de perda do mes
                $descEficP1 = 'ABAIXO DE '.$arr['PERDAAMEZ'];
                $descEficP2 = 'ENTRE '.$arr['PERDAAMEZ'].' e '.$arr['PERDABMEZ'];
                $descEficP3 = 'ACIMA DE '.$arr['PERDABMEZ'];

                //percentual de perda do dia
                $perc1 = $arr['PERDABDIA'];

                //percentual de perda da noite
                $perc2 = $arr['PERDABNOITE'];

                //percentual de perda geral
                if($arr['PERDABNOITE'] > 0){
                    $perc3 = ($arr['PERDABDIA'] + $arr['PERDABNOITE'])/2;
                }else{
                    $perc3 = $arr['PERDABDIA']; 
                }

                //percentual de perda da semana
                $perc4 = $arr['PERDABSEMA'];

                //percentual de perda do mes
                $perc5 = $arr['PERDABSEMA'];
                
                $data          = date('Y-m-d');
                $data_consulta = $arr['DATA_CONSULTA'];
                $data_exec     = $arr['DATA_EXEC'];
                
                if ($data == $data_consulta){ $flag = 1;}else{$flag = 0;}
                
                if($flag == 1){
                    
                    if ($arr['PERDAANOITE'] > 0){ $perc10 = ($arr['PERDAADIA'] + $arr['PERDAANOITE'])/2;}else{$perc10 = ($arr['PERDAADIA']);}
                    if ($arr['PERDABNOITE'] > 0){ $perc11 = ($arr['PERDABDIA'] + $arr['PERDABNOITE'])/2;}else{$perc11 = ($arr['PERDABDIA']);}

                    //cor perda hora turno 1
                    $corP1 = self::getCorValor3($arr['PERDAADIA'],$arr['PERDABDIA'],$arr['PEP1']);
                    //cor perda geral turno 1
                    $corP2 = self::getCorValor3($arr['PERDAADIA'],$arr['PERDABDIA'],$arr['PEP2']);
                    //cor poerda hora turno 2
                    $corP3 = self::getCorValor3($arr['PERDAANOITE'],$arr['PERDABNOITE'],$arr['PEP3']);
                    //cor perda geral turno 2
                    $corP4 = self::getCorValor3($arr['PERDAANOITE'],$arr['PERDABNOITE'],$arr['PEP4']);
                    //cor perda geral dos turnos
                    $corP5 = self::getCorValor3($perc10,$perc11,$arr['PEP5']);
                    //cor perda geral da semana
                    $corP6 = self::getCorValor3($arr['PERDAASEMA'],$arr['PERDABSEMA'],$arr['PEP_SEMANA']);
                    //cor perda geral mez
                    $corP7 = self::getCorValor3($arr['PERDAAMEZ'],$arr['PERDABMEZ'],$arr['PEP_MEZ']);

                    //percentual geral
                    if ($arr['EFICANOITE'] > 0){ $perc10 = ($arr['EFICADIA'] + $arr['EFICANOITE'])/2;}else{$perc10 = ($arr['EFICADIA']);}
                    if ($arr['EFICBNOITE'] > 0){ $perc11 = ($arr['EFICBDIA'] + $arr['EFICBNOITE'])/2;}else{$perc11 = ($arr['EFICBDIA']);}

                    $corV1 = self::getCorValor2($arr['EFICADIA'],$arr['EFICBDIA'],$arr['EF1']);             //cor coluna 1
                    $corV2 = self::getCorValor2($arr['EFICADIA'],$arr['EFICBDIA'],$arr['EF2']);             //cor coluna 1
                    $corV3 = self::getCorValor2($arr['EFICANOITE'],$arr['EFICBNOITE'],$arr['EF3']);         //cor coluna 1
                    $corV4 = self::getCorValor2($arr['EFICANOITE'],$arr['EFICBNOITE'],$arr['EF4']);         //cor coluna 1
                    $corV5 = self::getCorValor2($perc10,$perc11,$arr['EF5']);                               //cor coluna 1
                    $corV6 = self::getCorValor2($arr['EFICASEMA'],$arr['EFICBSEMA'],$arr['EF_SEMANA']);     //cor coluna 1
                    $corV7 = self::getCorValor2($arr['EFICAMEZ'],$arr['EFICBMEZ'],$arr['EF_MEZ']);          //cor coluna 1

                    $desc = 'HORA';
                }else{
                    
                    if ($arr['PERDAANOITE'] > 0){ $perc10 = ($arr['PERDAADIA'] + $arr['PERDAANOITE'])/2;}else{$perc10 = ($arr['PERDAADIA']);}
                    if ($arr['PERDABNOITE'] > 0){ $perc11 = ($arr['PERDABDIA'] + $arr['PERDABNOITE'])/2;}else{$perc11 = ($arr['PERDABDIA']);}
                    
                    //cor perda hora turno 1
                    $corP1 = self::getCorValor3($arr['PERDAADIA'],$arr['PERDABDIA'],$arr['MPEP1']);
                    //cor perda geral turno 1
                    $corP2 = self::getCorValor3($arr['PERDAADIA'],$arr['PERDABDIA'],$arr['PEP2']);
                    //cor poerda hora turno 2
                    $corP3 = self::getCorValor3($arr['PERDAANOITE'],$arr['PERDABNOITE'],$arr['MPEP3']);
                    //cor perda geral turno 2
                    $corP4 = self::getCorValor3($arr['PERDAANOITE'],$arr['PERDABNOITE'],$arr['PEP4']);
                    //cor perda geral dos turnos
                    $corP5 = self::getCorValor3($perc10,$perc11,$arr['PEP5']);
                    //cor perda geral da semana
                    $corP6 = self::getCorValor3($arr['PERDAASEMA'],$arr['PERDABSEMA'],$arr['PEP_SEMANA']);
                    //cor perda geral mez
                    $corP7 = self::getCorValor3($arr['PERDAAMEZ'],$arr['PERDABMEZ'],$arr['PEP_MEZ']);
                    

                    //percentual geral
                    if ($arr['EFICANOITE'] > 0){ $perc10 = ($arr['EFICADIA'] + $arr['EFICANOITE'])/2;}else{$perc10 = ($arr['EFICADIA']);}
                    if ($arr['EFICBNOITE'] > 0){ $perc11 = ($arr['EFICBDIA'] + $arr['EFICBNOITE'])/2;}else{$perc11 = ($arr['EFICBDIA']);}

                    $corV1 = self::getCorValor2($arr['EFICAMEZ'],$arr['EFICBMEZ'],$arr['MEF1']);            //cor coluna 1
                    $corV2 = self::getCorValor2($arr['EFICADIA'],$arr['EFICBDIA'],$arr['EF2']);             //cor coluna 1
                    $corV3 = self::getCorValor2($arr['EFICAMEZ'],$arr['EFICBMEZ'],$arr['MEF3']);            //cor coluna 1
                    $corV4 = self::getCorValor2($arr['EFICANOITE'],$arr['EFICBNOITE'],$arr['EF4']);         //cor coluna 1
                    $corV5 = self::getCorValor2($perc10,$perc11,$arr['EF5']);                               //cor coluna 1
                    $corV6 = self::getCorValor2($arr['EFICASEMA'],$arr['EFICBSEMA'],$arr['EF_SEMANA']);     //cor coluna 1
                    $corV7 = self::getCorValor2($arr['EFICAMEZ'],$arr['EFICBMEZ'],$arr['EF_MEZ']);          //cor coluna 1
                    
                    $desc = 'MÊS';
                }
                
                $adicional = [
                    'corP1'         => $corP1,
                    'corP2'         => $corP2,
                    'corP3'         => $corP3,
                    'corP4'         => $corP4,
                    'corP5'         => $corP5,
                    'corP6'         => $corP6,
                    'corP7'         => $corP7,
                    'corV1'         => $corV1,
                    'corV2'         => $corV2,
                    'corV3'         => $corV3,
                    'corV4'         => $corV4,
                    'corV5'         => $corV5,
                    'corV6'         => $corV6,
                    'corV7'         => $corV7,
                    'descEfic1'     => $descEfic1,
                    'descEfic2'     => $descEfic2,
                    'descEfic3'     => $descEfic3,
                    'descEficP1'    => $descEficP1,
                    'descEficP2'    => $descEficP2,
                    'descEficP3'    => $descEficP3,
                    'desc'          => $request->get('desc'),
                    'data_consulta' => date('d/m/Y', strtotime($data_consulta)),
                    'data_exec'     => date('d/m/Y h:i', strtotime($data_exec)),
                    'desc_flag'     => $desc,
                    'flag'          => $flag
                ];
                
                //return view('opex._25800.include.producao2',['ret' => $arr, 'adic' => $adicional, 'HoraRanking' => $horasRanking]); 
				return view('opex._25800.include.producao',['ret' => $arr, 'adic' => $adicional, 'HoraRanking' => $horasRanking]); 				
            }else{
                return view('opex._25800.include.semmeta');
            }
            
            //return $arr;
        }
    }
    
    /**
    * calcula cor de um item do bsc
    * return view
    * @package Opex
     * @param float $valor valor do indicador
     * @param float $eficA valor da eficiencia (valor menor)
     * @param float $eficB valor da eficiencia (valor maior)
    * @category Controller
    */
    public function calcCorBSCA($valor,$eficA,$eficB){    
        
            if(floatval($valor) > floatval($eficA)){
                $cor = 1;
            }else{
                if(floatval($valor) >=  floatval($eficB)){
                    $cor = 2;
                }else{
                    $cor = 3;
                }
            }

        return $cor;
    }
    
    /**
    * calcula cor das perdas do bsc
    * return int
    * @package Opex
    * @param float $valor valor do indicador
    * @param float $perdaA valor % para perda (valor menor)
    * @param float $perdaB valor % para perda (valor maior)
    * @category Controller
    */
    public function calcCorPerda($valor,$perdaA,$perdaB){

        if (floatval($valor) < floatval($perdaB)){
            $cor = 1;
        }else{
            if (floatval($valor) > floatval($perdaA)){
                $cor = 3;
            }else{
                $cor = 2;
            }
        }


        return $cor;
    }
    
    /**
    * calcula cor das perdas do bsc
    * return int
    * @package Opex
     * @param float $valor valor obtido no indicador
     * @param float $m1 valor da meta verde (menor)
     * @param float $m2 valor da meta verde (maior)
     * @param float $m3 valor da meta amarela (menor)
     * @param float $m4 valor da meta amarela (maior)
     * @param float $m5 valor da meta vermelha (menor)
     * @param float $m6 valor da meta vermelha (maior)
    * @category Controller
    */
    public function calcCorGeral($valor,$m1,$m2,$m3,$m4,$m5,$m6){

        if ((floatval($valor) >= floatval($m1)) && (floatval($valor) <= floatval($m2))){
            $cor = 1;
        }else{
            if ((floatval($valor) >= floatval($m3)) && (floatval($valor) <= floatval($m4))){
                $cor = 2;
            }else{
                if ((floatval($valor) >= floatval($m5)) && (floatval($valor) <= floatval($m6))){
                    $cor = 3;
                }else{
                    $cor = 0;
                }
            }
        }

        return $cor;
    }
    
    /**
    * calcula percentual obtido de um peso
    * return float
    * @package Opex
     * @param float $nota nota obitida no indicador
     * @param float $peso peso do indicador
     * @param int $cor cor obitida no indicador
     * @param int $tipo tipo do indicador, 1 - nota quanto maior melhor, 2 - nota quanto menor melhor
     * @param float $meta1 menor meta verde
     * @param float $meta2 maior meta amarelo
     * @param float $meta3 maior meta vermelho
    * @category Controller
    */
    public function calcNota($nota,$peso,$cor,$tipo,$meta1,$meta2,$meta3){
        $perc_peso = 0;
        $meta      = 0;
        
        if($cor == 1){$perc_peso = $peso;       $meta = $meta1;   }else{
        if($cor == 2){$perc_peso = $peso*0.75;  $meta = $meta2;   }else{
        if($cor == 3){$perc_peso = $peso*0.5;   $meta = $meta3;   }else{}}}

        if($cor != 1){
            if($cor != 0){    
                if(intval($tipo) < 2){

                    if($meta > 0){
                        $ret = ($nota/$meta) * $perc_peso;  
                    }else{
                        $ret = 999; 
                    }
        
                }else{

                    if($cor == 1){$perc_peso = $peso;       $meta = $meta1;   }else{
                    if($cor == 2){$perc_peso = $peso*0.75;  $meta = $meta1;   }else{
                    if($cor == 3){$perc_peso = $peso*0.5;   $meta = $meta2;   }else{}}}

                    if($meta == 0){
                        $ret = 999;  
                    }else{
                        //validar nota se 0
                        //verificar no futuro se a nota 0 retorno 0 ou 100
                        if($nota == 0){
                            if($meta1 == 0){
                                $ret = 99;
                            }else{
                                $ret = 0; 
                            }  
                        }else{
                            $ret = ($meta/$nota) * $perc_peso;
                        }
                    }

                }
            }else{
                $ret =  0;
            }    
        }else{
            $ret =  $peso;
        }
        
        if($ret > $peso){$ret =  $peso;}
        
        return $ret;
    }
    
    /**
    * tela de BSC do objeto 25800 (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function consultabsc(Request $request)
    {
        set_time_limit(120);

        if( $request->ajax() ) {
            $id         = $request->get('id');
            $grupo      = $request->get('grupo');
            $eficiencia = $request->get('eficiencia');
            $desc       = $request->get('desc');
            $ccusto     = $request->get('ccusto');
            $estab      = $request->get('estab');
            $data       = $request->get('data');
            $auto       = $request->get('autoinicia');
            $familia    = $request->get('familia');
                    
            if(intval($auto) == 1){
                $data = _25800::dataproducao($familia);
            }
            
            
            $gp = $id;
                    
            $dados = [
                'id'            => $id,
                'grupo'         => $grupo,
                'eficiencia'    => $eficiencia,
                'desc'          => $desc,
                'ccusto'        => $ccusto,
                'estab'         => $estab,
                'data'          => $data
            ];
            
            $descgp = $request->get('desc');                
            return self::bscpp($dados,$gp,$descgp,1,$data);
        }
        
    } 
        
    /**
    * tela de BSC do objeto 25800 (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function bscpp($dados,$gp,$descgp,$dia,$data)
    {
        set_time_limit(120);

        $ret = _25800::consultabsc($dados,$gp,$dia,$data);
        
        $tot = count($ret);
        
        $removegerals = 0;
        $removegeralm = 0;
        $removegeralt = 0;
        $somapesos = 0;
        $soma_nota_semana   = 0;
        $soma_nota_mes      = 0;
        $soma_nota_semestre = 0;

        $meta1 = 0;
        $meta2 = 0;
        $meta3 = 0;
        $meta4 = 0;
        $meta5 = 0;
        $meta6 = 0;

        $corimg1 = 0;
        $corimg2 = 0;
        $corimg3 = 0;
        
        $vs = 0;
        $vm = 0;
        $vt = 0;

        if($tot > 0){
            $arr = (array)$ret[0];

            $data_consulta = $arr['DATA_CONSULTA'];
            $data_exec     = $arr['DATA_EXEC'];

            //ids dos indicadores
            $ids = [2,3,4,7,5,1,8,9,13,11];
            $contador = 0;
            $indicadores = [];

            //$nun_indicadores = count($ids) - 1;

            foreach ($ids as $id){
                $vs = 0;
                $vm = 0;
                $vt = 0;
                
                $indicador = _25800::consultaIndicador($id);
                $cont = count($indicador);

                if($cont > 0){
                    $ind = (array)$indicador[0];

                    if($contador > 2){
                        $efic1A = $ind['PERFIL1_A'];
                        $efic1B = $ind['PERFIL1_B'];
                        $efic2A = $ind['PERFIL2_A'];
                        $efic2B = $ind['PERFIL2_B'];
                        $efic3A = $ind['PERFIL3_A'];
                        $efic3B = $ind['PERFIL3_B'];

                        if($contador == 5){
                            $meta1 = $efic1A;
                            $meta2 = $efic1B;
                            $meta3 = $efic2A;
                            $meta4 = $efic2B;
                            $meta5 = $efic3A;
                            $meta6 = $efic3B;   
                        }

                        $desc_efic1 = $ind['PERFIL1_DESCRICAO'];
                        $desc_efic2 = $ind['PERFIL2_DESCRICAO'];
                        $desc_efic3 = $ind['PERFIL3_DESCRICAO'];

                        $peso = $ind['PESO'];

                        if($contador < 9){
                            $somapesos = $somapesos + $ind['PESO'];
                        }

                        if($contador == 3){
                            $rrs = $arr['VALORSEMANAINDICADOR4_1'];
                            $prs = $arr['VALORSEMANAINDICADOR4_2'];

                            $rrm = $arr['VALORMESINDICADOR4_1'];
                            $prm = $arr['VALORMESINDICADOR4_2'];

                            $rrt = $arr['VALORSEMESTREINDICADOR4_1'];
                            $prt = $arr['VALORSEMESTREINDICADOR4_2'];

                            if(intval($rrs) > 0){$vs = (($prs/$rrs)*100);}else{$vs=0;}
                            if(intval($rrm) > 0){$vm = (($prm/$rrm)*100);}else{$vs=0;}
                            if(intval($rrt) > 0){$vt = (($prt/$rrt)*100);}else{$vs=0;}

                            $cor1 = self::calcCorGeral($vs,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);
                            $cor2 = self::calcCorGeral($vm,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);
                            $cor3 = self::calcCorGeral($vt,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);

                            if ($cor1 == 0){$removegerals = $removegerals + $peso;}
                            if ($cor2 == 0){$removegeralm = $removegeralm + $peso;}
                            if ($cor3 == 0){$removegeralt = $removegeralt + $peso;} 

                            $valor_semana   = number_format($vs, 2, '.', '');
                            $valor_mes      = number_format($vm, 2, '.', '');
                            $valor_semestre = number_format($vt, 2, '.', '');

                            $perc_notas = self::calcNota($vs,$peso,$cor1,1,$efic1B,$efic2B,$efic3B);
                            $perc_notam = self::calcNota($vm,$peso,$cor2,1,$efic1B,$efic2B,$efic3B);
                            $perc_notat = self::calcNota($vt,$peso,$cor3,1,$efic1B,$efic2B,$efic3B);

                            $soma_nota_semana   = $soma_nota_semana   + $perc_notas;
                            $soma_nota_mes      = $soma_nota_mes      + $perc_notam;
                            $soma_nota_semestre = $soma_nota_semestre + $perc_notat;

                            $perc_notas = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notas, 2, '.', ',');
                            $perc_notam = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notam, 2, '.', ',');
                            $perc_notat = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notat, 2, '.', ',');
                        }

                        if(($contador == 4) || ($contador == 5)){

                            $efic1A = (($efic1A/5) * 100);
                            $efic1B = (($efic1B/5) * 100);
                            $efic2A = (($efic2A/5) * 100);
                            $efic2B = (($efic2B/5) * 100);
                            $efic3A = (($efic3A/5) * 100);
                            $efic3B = (($efic3B/5) * 100);

                            if($contador == 4){
                                $vs = $arr['VALORSEMANAINDICADOR5'];
                                $vm = $arr['VALORMESINDICADOR5'];
                                $vt = $arr['VALORSEMESTREINDICADOR5'];
                            }else{
                                $vs = $arr['VALORSEMANAINDICADOR6'];
                                $vm = $arr['VALORMESINDICADOR6'];
                                $vt = $arr['VALORSEMESTREINDICADOR6'];  
                            }

                            $cor1 = self::calcCorGeral($vs,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);
                            $cor2 = self::calcCorGeral($vm,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);
                            $cor3 = self::calcCorGeral($vt,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);

                            if ($cor1 == 0){$removegerals = $removegerals + $peso;}
                            if ($cor2 == 0){$removegeralm = $removegeralm + $peso;}
                            if ($cor3 == 0){$removegeralt = $removegeralt + $peso;}

                            $valor_semana       = number_format($vs, 2, '.', '');
                            $valor_mes          = number_format($vm, 2, '.', '');
                            $valor_semestre     = number_format($vt, 2, '.', '');

                            $perc_notas = self::calcNota($vs,$peso,$cor1,1,$efic1B,$efic2B,$efic3B);
                            $perc_notam = self::calcNota($vm,$peso,$cor2,1,$efic1B,$efic2B,$efic3B);
                            $perc_notat = self::calcNota($vt,$peso,$cor3,1,$efic1B,$efic2B,$efic3B);

                            $soma_nota_semana   = $soma_nota_semana   + $perc_notas;
                            $soma_nota_mes      = $soma_nota_mes      + $perc_notam;
                            $soma_nota_semestre = $soma_nota_semestre + $perc_notat;

                            $perc_notas = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notas, 2, '.', ',');
                            $perc_notam = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notam, 2, '.', ',');
                            $perc_notat = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notat, 2, '.', ',');

                            if($contador == 5){
                                $corimg1 = $cor1;
                                $corimg2 = $cor2;
                                $corimg3 = $cor3;
                            }

                        }

                        if($contador == 6){

                            $vs1 = $arr['VALORSEMANAINDICADOR7'];
                            $vm1 = $arr['VALORMESINDICADOR7'];
                            $vt1 = $arr['VALORSEMESTREINDICADOR7'];

                            $valorr_semana   = intval($arr['VALORSEMANAINDICADOR1']);
                            $valorr_mes      = intval($arr['VALORMESINDICADOR1']);
                            $valorr_semestre = intval($arr['VALORSEMESTREINDICADOR1']);

                            $valorp_semana   = intval($arr['VALORSEMANAINDICADOR3']);
                            $valorp_mes      = intval($arr['VALORMESINDICADOR3']);
                            $valorp_semestre = intval($arr['VALORSEMESTREINDICADOR3']);

                            if (intval($valorp_semana)   > 0){ $vs = ($vs1/$valorp_semana    ) * 100; }else{$vs=0;}
                            if (intval($valorp_mes)      > 0){ $vm = ($vm1/$valorp_mes       ) * 100; }else{$vm=0;}
                            if (intval($valorp_semestre) > 0){ $vt = ($vt1/$valorp_semestre  ) * 100; }else{$vt=0;}

                            if (intval($valorr_semana)   > 0){ $vs2 = (($vs1/$valorr_semana    ) * 100); }else{$vs2=0;}
                            if (intval($valorr_mes)      > 0){ $vm2 = (($vm1/$valorr_mes       ) * 100); }else{$vm2=0;}
                            if (intval($valorr_semestre) > 0){ $vt2 = (($vt1/$valorr_semestre  ) * 100); }else{$vt2=0;}

                            $cor1 = self::calcCorGeral($vs,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);
                            $cor2 = self::calcCorGeral($vm,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);
                            $cor3 = self::calcCorGeral($vt,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);

                            if ($cor1 == 0){$removegerals = $removegerals + $peso;}
                            if ($cor2 == 0){$removegeralm = $removegeralm + $peso;}
                            if ($cor3 == 0){$removegeralt = $removegeralt + $peso;}

                            $valor_semana       = number_format($vs2, 2, '.', '');
                            $valor_mes          = number_format($vm2, 2, '.', '');
                            $valor_semestre     = number_format($vt2, 2, '.', '');

                            $perc_notas = self::calcNota($vs,$peso,$cor1,2,$efic1B,$efic2A,$efic2B);
                            $perc_notam = self::calcNota($vm,$peso,$cor2,2,$efic1B,$efic2A,$efic2B);
                            $perc_notat = self::calcNota($vt,$peso,$cor3,2,$efic1B,$efic2A,$efic2B);

                            $soma_nota_semana   = $soma_nota_semana   + $perc_notas;
                            $soma_nota_mes      = $soma_nota_mes      + $perc_notam;
                            $soma_nota_semestre = $soma_nota_semestre + $perc_notat;

                            $perc_notas = 'Meta A:'.number_format($efic2A, 2, '.', ',').'&#10;Meta B:'.number_format($efic2B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notas, 2, '.', ',').'&#10;P.Perda:'.number_format($vs, 2, '.', ',');
                            $perc_notam = 'Meta A:'.number_format($efic2A, 2, '.', ',').'&#10;Meta B:'.number_format($efic2B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notam, 2, '.', ',').'&#10;P.Perda:'.number_format($vm, 2, '.', ',');
                            $perc_notat = 'Meta A:'.number_format($efic2A, 2, '.', ',').'&#10;Meta B:'.number_format($efic2B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notat, 2, '.', ',').'&#10;P.Perda:'.number_format($vt, 2, '.', ',');
                        }

                        if($contador == 7){
                            $vs1 = $arr['VALORSEMANAINDICADOR8'];
                            $vm1 = $arr['VALORMESINDICADOR8'];
                            $vt1 = $arr['VALORSEMESTREINDICADOR8'];

                            $vs2 = $arr['VALORSEMANAINDICADOR8_2'];
                            $vm2 = $arr['VALORMESINDICADOR8_2'];
                            $vt2 = $arr['VALORSEMESTREINDICADOR8_2'];

                            if (intval($vs1) > 0){ $vs = (($vs2/$vs1 ) * 100); }else{$vs=0;}
                            if (intval($vm1) > 0){ $vm = (($vm2/$vm1 ) * 100); }else{$vm=0;}
                            if (intval($vt1) > 0){ $vt = (($vt2/$vt1 ) * 100); }else{$vt=0;}

                            $cor1 = self::calcCorGeral($vs,$efic1B,$efic1A,$efic2B,$efic2A,$efic3B,$efic3A);
                            $cor2 = self::calcCorGeral($vm,$efic1B,$efic1A,$efic2B,$efic2A,$efic3B,$efic3A);
                            $cor3 = self::calcCorGeral($vt,$efic1B,$efic1A,$efic2B,$efic2A,$efic3B,$efic3A);

                            if ($cor1 == 0){$removegerals = $removegerals + $peso;}
                            if ($cor2 == 0){$removegeralm = $removegeralm + $peso;}
                            if ($cor3 == 0){$removegeralt = $removegeralt + $peso;}

                            $valor_semana       = number_format($vs, 2, '.', '');
                            $valor_mes          = number_format($vm, 2, '.', '');
                            $valor_semestre     = number_format($vt, 2, '.', '');

                            $perc_notas = self::calcNota($vs,$peso,$cor1,2,$efic1A,$efic2A,$efic2A);
                            $perc_notam = self::calcNota($vm,$peso,$cor2,2,$efic1A,$efic2A,$efic2A);
                            $perc_notat = self::calcNota($vt,$peso,$cor3,2,$efic1A,$efic2A,$efic2A);

                            $soma_nota_semana   = $soma_nota_semana   + $perc_notas;
                            $soma_nota_mes      = $soma_nota_mes      + $perc_notam;
                            $soma_nota_semestre = $soma_nota_semestre + $perc_notat;

                            $perc_notas = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notas, 2, '.', ',');
                            $perc_notam = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notam, 2, '.', ',');
                            $perc_notat = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notat, 2, '.', ',');


                        }

                        if($contador == 8){
                            $vs1 = $arr['VALORSEMANAINDICADOR9'];
                            $vm1 = $arr['VALORMESINDICADOR9'];
                            $vt1 = $arr['VALORSEMESTREINDICADOR9'];

                            $valorr_semana   = intval($arr['VALORSEMANAINDICADOR1']);
                            $valorr_mes      = intval($arr['VALORMESINDICADOR1']);
                            $valorr_semestre = intval($arr['VALORSEMESTREINDICADOR1']);

                            if (intval($valorr_semana)   > 0){ $vs = (($vs1/$valorr_semana    ) * 100); }else{$vs=0;}
                            if (intval($valorr_mes)      > 0){ $vm = (($vm1/$valorr_mes       ) * 100); }else{$vm=0;}
                            if (intval($valorr_semestre) > 0){ $vt = (($vt1/$valorr_semestre  ) * 100); }else{$vt=0;}

                            $cor1 = self::calcCorGeral($vs,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);
                            $cor2 = self::calcCorGeral($vm,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);
                            $cor3 = self::calcCorGeral($vt,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);

                            if ($cor1 == 0){$removegerals = $removegerals + $peso;}
                            if ($cor2 == 0){$removegeralm = $removegeralm + $peso;}
                            if ($cor3 == 0){$removegeralt = $removegeralt + $peso;}

                            $valor_semana       = number_format($vs, 2, '.', '');
                            $valor_mes          = number_format($vm, 2, '.', '');
                            $valor_semestre     = number_format($vt, 2, '.', '');

                            $perc_notas = self::calcNota($vs,$peso,$cor1,2,$efic1B,$efic2A,$efic2B);
                            $perc_notam = self::calcNota($vm,$peso,$cor2,2,$efic1B,$efic2A,$efic2B);
                            $perc_notat = self::calcNota($vt,$peso,$cor3,2,$efic1B,$efic2A,$efic2B);

                            $soma_nota_semana   = $soma_nota_semana   + $perc_notas;
                            $soma_nota_mes      = $soma_nota_mes      + $perc_notam;
                            $soma_nota_semestre = $soma_nota_semestre + $perc_notat;

                            $perc_notas = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notas, 2, '.', ',');
                            $perc_notam = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notam, 2, '.', ',');
                            $perc_notat = 'Meta A:'.number_format($efic2B, 2, '.', ',').'&#10;Meta B:'.number_format($efic3B, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notat, 2, '.', ',');
                        }

                        if($contador == 9){
                            $peso = $somapesos;

                            if($peso > 0){
                              $vs = ($soma_nota_semana   / ($peso-$removegerals)) * 100;
                              $vm = ($soma_nota_mes      / ($peso-$removegeralm)) * 100;
                              $vt = ($soma_nota_semestre / ($peso-$removegeralt)) * 100;
                            }else{
                              $vs = 0;
                              $vm = 0;
                              $vt = 0;
                            }

                            $cor1 = self::calcCorGeral($vs,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);
                            $cor2 = self::calcCorGeral($vm,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);
                            $cor3 = self::calcCorGeral($vt,$efic1A,$efic1B,$efic2A,$efic2B,$efic3A,$efic3B);

                            $valor_semana       = number_format($vs, 2, '.', '');
                            $valor_mes          = number_format($vm, 2, '.', '');
                            $valor_semestre     = number_format($vt, 2, '.', '');

                            $perc_notas = 'Soma:'.number_format($soma_nota_semana, 2, '.', '').'&#10;'
                                        . 'Peso:'.number_format($peso-$removegerals, 2, '.', ',').'&#10;'
                                        . 'Tot.:'.$valor_semana;

                            $perc_notam = 'Soma:'.number_format($soma_nota_mes, 2, '.', '').'&#10;'
                                        . 'Peso:'.number_format($peso-$removegeralm, 2, '.', ',').'&#10;'
                                        . 'Tot.:'.$valor_mes;

                            $perc_notat = 'Soma:'.number_format($soma_nota_semestre, 2, '.', '').'&#10;'
                                        . 'Peso:'.number_format($peso-$removegeralt, 2, '.', ',').'&#10;'
                                        . 'Tot.:'.$valor_semestre;
                        }

                    }else{
                        $peso = $ind['PESO'];
                        $somapesos = $somapesos + $ind['PESO'];

                        $valorr_semana   = intval($arr['VALORSEMANAINDICADOR1']);
                        $valorr_mes      = intval($arr['VALORMESINDICADOR1']);
                        $valorr_semestre = intval($arr['VALORSEMESTREINDICADOR1']);

                        $valorp_semana   = intval($arr['VALORSEMANAINDICADOR3']);
                        $valorp_mes      = intval($arr['VALORMESINDICADOR3']);
                        $valorp_semestre = intval($arr['VALORSEMESTREINDICADOR3']);

                        $prev_semana    = $arr['PREV_SEMANA'];
                        $prev_mes       = $arr['PREV_MES'];
                        $prev_semestre  = $arr['PREV_SEMESTRE'];

                        $efic1As = $arr['EFICSEMANAB'];
                        $efic1Bs = $arr['EFICSEMANAA'];

                        $efic1Am = $arr['EFICMEZB'];
                        $efic1Bm = $arr['EFICMEZA'];

                        $efic1At = $arr['EFICSEMESTREB'];
                        $efic1Bt = $arr['EFICSEMESTREA'];

                        $perdaAs = $arr['PERDASEMANAB'];
                        $perdaBs = $arr['PERDASEMANAA'];

                        $perdaAm = $arr['PERDAMEZB'];
                        $perdaBm = $arr['PERDAMEZA'];

                        $perdaAt = $arr['PERDASEMESTREB'];
                        $perdaBt = $arr['PERDASEMESTREA'];

                        $valor_mes = 0;
                        $valor_semana = 0;
                        $valor_semestre = 0;

                        $efica = 0;
                        $eficb = 0;

                        if($contador < 2){

                            if (intval($prev_semana) > 0){
                                //calcula cor com base na meta de produção e meta de eficiencia

                                $efica = intval(($prev_semana/100)*$efic1As) - 1;
                                $eficb = intval(($prev_semana/100)*$efic1Bs);

                                if(intval($prev_semana) > 0){
                                  $aux1 = ($valorr_semana/($prev_semana/100));
                                }else{
                                  $aux1 = 0;  
                                }

                                //valor prod semana e efic semana
                                if($contador == 1){
                                    $valor_semana = number_format($aux1, 2, '.', ',');
                                    if(floatval($valor_semana) > 100){$valor_semana = 100;}
                                }else{
                                    $valor_semana = number_format($valorr_semana, 0, '.', '');
                                }

                                $cor1 =  self::calcCorBSCA($valorr_semana,$efica,$eficb);

                            }else{
                                $cor1  = 0;
                            }

                            $perc_notas = self::calcNota($valorr_semana,$peso,$cor1,1,$efica,$efica,$eficb);
                            $soma_nota_semana   = $soma_nota_semana   + $perc_notas;
                            $perc_notas = 'Meta A:'.$efica.'&#10;Meta B:'.$eficb.'&#10;P.Nota:'.number_format($perc_notas, 2, '.', ',');

                            if (intval($prev_mes) > 0){
                                //calcula cor com base na meta de produção e meta de eficiencia

                                $efica = intval(($prev_mes/100)*$efic1Am) - 1;
                                $eficb = intval(($prev_mes/100)*$efic1Bm);

                                if($contador == 0){
                                    $desc_efic1 = 'ACIMA DE '.$efica;
                                    $desc_efic2 = 'ENTRE '.$eficb.' E '.$efica;
                                    $desc_efic3 = 'ABAIXO DE '.$eficb;
                                }else{
                                    $desc_efic1 = 'ACIMA DE '.number_format($efic1Am, 2, '.', ',');
                                    $desc_efic2 = 'ENTRE '.number_format($efic1Am, 2, '.', ',').' E '.number_format($efic1Bm, 2, '.', ',');
                                    $desc_efic3 = 'ABAIXO DE '.number_format($efic1Bm, 2, '.', ',');
                                }

                                if(intval($prev_mes) > 0){
                                  $aux1 = ($valorr_mes/($prev_mes/100));
                                }else{
                                  $aux1 = 0;  
                                }

                                //valor prod mes e efic mes
                                if($contador == 1){
                                    $valor_mes = number_format($aux1, 2, '.', ',');
                                    if(floatval($valor_mes) > 100){$valor_mes = 100;}
                                }else{
                                    $valor_mes = number_format($valorr_mes, 0, '.', '');
                                }

                                $cor2 =  self::calcCorBSCA($valorr_mes,$efica,$eficb);

                            }else{
                                $desc_efic1 = 'ACIMA DE 0';
                                $desc_efic2 = 'ENTRE 0 E 0';
                                $desc_efic3 = 'ABAIXO DE 0';

                                $cor2  = 0;
                            }

                            $perc_notam = self::calcNota($valorr_mes,$peso,$cor2,1,$efica,$efica,$eficb);
                            $soma_nota_mes      = $soma_nota_mes      + $perc_notam;
                            $perc_notam = 'Meta A:'.$efica.'&#10;Meta B:'.$eficb.'&#10;P.Nota:'.number_format($perc_notam, 2, '.', ',');

                            if (intval($valorr_semestre) > 0){
                                //calcula cor com base na meta de produção e meta de eficiencia

                                $efica = intval(($prev_semestre/100)*$efic1At) - 1;
                                $eficb = intval(($prev_semestre/100)*$efic1Bt);
                                
                                if(intval($prev_semestre) > 0){
                                  $aux1 = ($valorr_semestre/$prev_semestre)*100;
                                }else{
                                  $aux1 = 0;  
                                }

                                //valor prod semestre e efic semestre
                                if($contador == 1){
                                    $valor_semestre = number_format($aux1, 2, '.', ',');
                                    if(floatval($valor_semestre) > 100){$valor_semestre = 100;}
                                }else{
                                    $valor_semestre = number_format($valorr_semestre, 0, '.', '');
                                }

                                $cor3 =  self::calcCorBSCA($valorr_semestre,$efica,$eficb);

                            }else{
                                $cor3  = 0;
                            }

                            if ($cor1 == 0){$removegerals = $removegerals + $peso;}
                            if ($cor2 == 0){$removegeralm = $removegeralm + $peso;}
                            if ($cor3 == 0){$removegeralt = $removegeralt + $peso;} 

                            $perc_notat = self::calcNota($valorr_semestre,$peso,$cor3,1,$efica,$eficb,$eficb);
                            $soma_nota_semestre  = $soma_nota_semestre   + $perc_notat;
                            $perc_notat = 'Meta A:'.$efica.'&#10;Meta B:'.$eficb.'&#10;P.Nota:'.number_format($perc_notat, 2, '.', ',');


                        }else{
                            if($contador == 2){

                                $desc_efic1 = 'ABAIXO DE '.number_format($perdaBm, 2, '.', ',');
                                $desc_efic2 = 'ENTRE '.number_format($perdaBm, 2, '.', ',').' E '.number_format($perdaAm, 2, '.', ',');
                                $desc_efic3 = 'ACIMA DE '.number_format($perdaAm, 2, '.', ',');
                                //cor perda semana
                                if(intval($valorr_semana) > 0){
                                    $perc_perda = (($valorp_semana/$valorr_semana)*100);
                                }else{if(intval($valorp_semana) > 0){$perc_perda = 99.9;}else{$perc_perda = 0;}}
                                $cor1 = self::calcCorPerda($perc_perda,$perdaAs,$perdaBs);

                                //valor perda semana
                                $valor_semana = number_format($perc_perda, 2, '.', ',');
                                $perc_notas = self::calcNota($valor_semana,$peso,$cor1,2,$perdaBs,$perdaBs,$perdaAs);
                                $soma_nota_semana   = $soma_nota_semana   + $perc_notas;
                                $perc_notas = 'Meta A:'.number_format($perdaAs, 2, '.', ',').'&#10;Meta B:'.number_format($perdaBs, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notas, 2, '.', ',');


                                //cor perda mes
                                if(intval($valorr_mes) > 0){
                                    $perc_perda = (($valorp_mes/$valorr_mes)*100);
                                }else{if(intval($valorp_mes) > 0){$perc_perda = 99.9;}else{$perc_perda = 0;}}
                                $cor2 = self::calcCorPerda($perc_perda,$perdaAm,$perdaBm);
                                //valor perda mes
                                $valor_mes = number_format($perc_perda, 2, '.', ',');
                                $perc_notam = self::calcNota($valor_mes,$peso,$cor2,2,$perdaBm,$perdaBs,$perdaAm);
                                $soma_nota_mes      = $soma_nota_mes      + $perc_notam;
                                $perc_notam = 'Meta A:'.number_format($perdaAm, 2, '.', ',').'&#10;Meta B:'.number_format($perdaBm, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notam, 2, '.', ',');


                                //cor perda semestre
                                if(intval($valorr_semestre) > 0){
                                    $perc_perda = (($valorp_semestre/$valorr_semestre)*100);
                                }else{if(intval($valorp_semestre) > 0){$perc_perda = 99.9;}else{$perc_perda = 0;}}
                                $cor3 = self::calcCorPerda($perc_perda,$perdaAt,$perdaBt);
                                //valor perda semestre
                                $valor_semestre = number_format($perc_perda, 2, '.', ',');
                                $perc_notat = self::calcNota($valor_semestre,$peso,$cor3,2,$perdaBt,$perdaBs,$perdaAt);
                                $soma_nota_semestre = $soma_nota_semestre + $perc_notat;
                                $perc_notat = 'Meta A:'.number_format($perdaAt, 2, '.', ',').'&#10;Meta B:'.number_format($perdaBt, 2, '.', ',').'&#10;P.Nota:'.number_format($perc_notat, 2, '.', ',');

                                if ($cor1 == 0){$removegerals = $removegerals + $peso;}
                                if ($cor2 == 0){$removegeralm = $removegeralm + $peso;}
                                if ($cor3 == 0){$removegeralt = $removegeralt + $peso;} 

                            }else{ 
                                $desc_efic1 = '';
                                $desc_efic2 = '';
                                $desc_efic3 = '';
                            }
                        }

                    }

                    $indicadores[$contador] = [
                    'DESC'  =>  $ind['DESCRICAO'],
                    'PESO'  =>  $peso,
                    'DEF1'  =>  $desc_efic1,
                    'DEF2'  =>  $desc_efic2,
                    'DEF3'  =>  $desc_efic3,
                    'COR1'  =>  'cor-bsc'.$cor1,
                    'COR2'  =>  'cor-bsc'.$cor2,
                    'COR3'  =>  'cor-bsc'.$cor3,
                    'VALOR1'=>  $valor_semana,
                    'VALOR2'=>  $valor_mes,
                    'VALOR3'=>  $valor_semestre,
                    'P_NOTS'=> $perc_notas,
                    'P_NOTM'=> $perc_notam,
                    'P_NOTT'=> $perc_notat
                    ];

                }else{
                    $indicadores[$contador] = [
                    'DESC'  =>  'NÃƒO ENCONTRADO',
                    'PESO'  =>  '0',
                    'DEF1'  =>  'NÃƒO ENCONTRADO',
                    'DEF2'  =>  'NÃƒO ENCONTRADO',
                    'DEF3'  =>  'NÃƒO ENCONTRADO',
                    'COR1'  =>  'cor-bsc0',
                    'COR2'  =>  'cor-bsc0',
                    'COR3'  =>  'cor-bsc0',
                    'VALOR1'=>  '0',
                    'VALOR2'=>  '0',
                    'VALOR3'=>  '0',
                    'P_NOTS'=>  '0',
                    'P_NOTM'=>  '0',
                    'P_NOTT'=>  '0'
                    ];
                }

                $contador++;
            }

            $termometro = _25800::consultaFaixasIndicador(1,$gp);

             $c = 0;
             $term = [];
             $soma = 0;
             $soma2 = 0;
             $media = 0;
             $media2 = 0;
             $media3 = 0;
             $indicadorVermelho = 0;

             $desc = '';

             foreach ($termometro as $t){
                $valor = $t->VALOR2;
                $valor2 = $t->VALOR;
                $soma = $soma + $valor;
                $soma2 = $soma2 + $valor2;

                if($c < 9){ $a = '0'.($c+1);  } else{ $a = ($c+1); }

                $desc .= $a.'-Cri. Nota:'.number_format($valor2, 2, '.', ',').'&#10;';


                if($valor == 1){
                    $indicadorVermelho = 1;
                }

                if((intval($valor) >= intval($meta1)) && (intval($valor) <= intval($meta2))){
                    $cor = 1;
                }else{
                    if((intval($valor) >= intval($meta3)) && (intval($valor) <= intval($meta4))){
                        $cor = 2;
                    }else{
                        if((intval($valor) >= intval($meta5)) && (intval($valor) <= intval($meta6))){
                            $cor = 3;
                        }else{
                            $cor = 0;
                        }
                    }
                }

                $term[$c] = [
                    'DESC' => $t->DESCRICAO,
                    'COR1' => 'cor-tab-'.$cor,
                    'NOT1' => 'VALOR',
                    'NOT2' => 'VALOR1'
                ];

                $c++;
             }

             if($c > 0){
                $media = $soma/$c;
                $valor = $media;

                $media2 = $soma2/$c;
                $media3 = intval(($media/$meta2) * 100);

                if((intval($valor) >= intval($meta1)) && (intval($valor) <= intval($meta2))){
                    $cor = 1;
                }else{
                    if((intval($valor) >= intval($meta3)) && (intval($valor) <= intval($meta4))){
                        $cor = 2;
                    }else{
                        if((intval($valor) >= intval($meta5)) && (intval($valor) <= intval($meta6))){
                            $cor = 3;
                        }else{
                            $cor = 0;
                        }
                    }
                }

                if ($indicadorVermelho == 1){
                    $cor = 3;
                }

             }else{
                $media = 0;
                $media2 = 0;
                $media3 = 0;
                $cor = 0;
             }

             $desc .= 'Media-1:'.number_format($media, 2, '.', ',').'&#10;';
             $desc .= 'Media-2:'.number_format($media2, 2, '.', ',').'&#10;';

            return view('opex._25800.include.BSC',[
                'termometro'  => $term,
                'corimgterm'  => 'img-bsc-'.$cor,
                'indicadores' => $indicadores,
                'MEDIA1' => number_format($media, 2, '.', ','),
                'MEDIA2' => number_format($media2, 2, '.', ','),
                'DCST'   => $desc,
                'PERC'   => $media3,
                'desc'          => $descgp,
                'data_consulta' => date('d/m/Y', strtotime($data_consulta)),
                'data_exec'     => date('d/m/Y h:i', strtotime($data_exec))

            ]);

        }else{
            return view('opex._25800.include.semmeta');
        }
    }
    
    /**
    * tela de producao do objeto 25800 (BSC-TV - tela )
    * return view
    * @package Opex
    * @category Controller
    */
    public function includeProducao()
    {   
        return view('opex._25800.include.producao');  
    }
    
    /**
    * tela de consultacomparativo do bsc(BSC-TV - tela )
    * return view
    * @package Opex
    * @category Controller
    */
    public function consultacomparativo(Request $request)
    {   
        set_time_limit(120);

        if( $request->ajax() ) {
            $id         = $request->get('id');
            $grupo      = $request->get('grupo');
            $eficiencia = $request->get('eficiencia');
            $desc       = $request->get('desc');
            $ccusto     = $request->get('ccusto');
            $estab      = $request->get('estab');
            $data       = $request->get('data');
            $perildo    = $request->get('perildo');
            $total      = $request->get('total');
            $auto       = $request->get('autoinicia');
            $familia    = $request->get('familia');
                    
            if(intval($auto) == 1){
                $data = _25800::dataproducao($familia);
            }
            
            $dados = [
                'id'            => $id,
                'grupo'         => $grupo,
                'eficiencia'    => $eficiencia,
                'desc'          => $desc,
                'ccusto'        => $ccusto,
                'estab'         => $estab,
                'data'          => $data
            ];
            
            $gpss = _25800::selectListGP($id);
            $descgp = $gpss[0]->DESC;
            $gp     = $gpss[0]->COD;
            
            $descs = explode(',',$descgp);
            
            $gps = explode(",", $gp);
            $col = count($gps)+2;
            
            if(($total == 1) && ($col > 3)){
                
                if($col < 10){
                    $desc_ind = 'INDICADORES';
                    $p = 'TOTAL';
                }else{
                    $p = 'TOT.';
                    $desc_ind = 'INDI.';
                }
                
                array_push($gps,$gp);
                array_push($descs,$p);
            }else{
                $col = count($gps)+1;
                
                if($col < 10){
                    $desc_ind = 'INDICADORES';
                    $p = 'TOTAL';
                }else{
                    $p = 'TOT.';
                    $desc_ind = 'INDI.';
                }
                
            }
            
            $indicadores = 10 + 1;
            
            $font = 3 - ($col*0.11);
            
            $perc = 96/$indicadores;
            for($i = 0; $i < $indicadores; $i++){
                $arrayL[$i] = $perc;
            }

            $perc2 = 100/($col+1);
            for($i = 0; $i < $col; $i++){
                if($i == 0){
                    $arrayC[$i] = $perc2*2;
                }else{
                    $arrayC[$i] = $perc2;
                }
            }
            
            $c = 0;
            $r = [];
            $var = [];
            $val = [];
            
            //$perildo = 2;
            
            $dia = 1;

            if(intval($perildo) == 1){$chec1 = 'value=1 checked'; $dia = 1;}else{$chec1 = 'value=0';}
            if(intval($perildo) == 2){$chec2 = 'value=1 checked'; $dia = 1;}else{$chec2 = 'value=0';}
            if(intval($perildo) == 3){$chec3 = 'value=1 checked'; $dia = 1;}else{$chec3 = 'value=0';}             
            if(intval($perildo) == 0){$chec0 = 'value=1 checked'; $perildo = 1; $dia = 2;}else{$chec0 = 'value=0';}
            
            if(intval($total) == 1){$chec4 = 'value=1 checked';}else{$chec4 = 'value=0';}
            
            foreach ($gps as $a){
                
                $obj = self::bscpp($dados,$a,'',$dia,$data);
                
                $s = $obj['indicadores'];
                $ss = 'Data '.$obj['data_consulta'].' tratada em '.$obj['data_exec'];

                    for($i = 0;$i < ($indicadores - 1) ; $i++ ){
                        $r[$i] = $s[$i]['DESC'];
                        $r[$i] = $s[$i]['DESC'];
                        
                        $cor = $s[$i]['COR'.$perildo];
                        $valor =  $s[$i]['VALOR'.$perildo];
                        
                        $emp = ['valor' => $valor , 'cor' => $cor, 'font' => $font ];
                        
                        $val[$i] = $emp;
                    }
                
                $var[$c] = $val;
                
                $c++;
            }     
            
            return view('opex._25800.include.comparativo',[
                'coll'  => $arrayC,
                'linha' => $arrayL,
                'desc'  => $descs,
                'indi'  => $r,
                'var'   => $var,
                'dc_dat'=> $ss,
                'chec0'=> $chec0,
                'chec1'=> $chec1,
                'chec2'=> $chec2,
                'chec3'=> $chec3,
                'chec4'=> $chec4,
                'ds_in'=> $desc_ind
            ]);  
        }
    }
    
    /**
    * tela de consultacomparativo do bsc(BSC-TV - tela )
    * return view
    * @package Opex
    * @category Controller
    */
    public function consultacomparativoG1(Request $request)
    {   
        set_time_limit(120);

        if( $request->ajax() ) {
            $id         = $request->get('id');
            $grupo      = $request->get('grupo');
            $eficiencia = $request->get('eficiencia');
            $desc       = $request->get('desc');
            $ccusto     = $request->get('ccusto');
            $estab      = $request->get('estab');
            $data       = $request->get('data');
            $perildo    = $request->get('perildo');
            $total      = $request->get('total');
            $auto       = $request->get('autoinicia');
            $familia    = $request->get('familia');
                    
            if(intval($auto) == 1){
                $data = _25800::dataproducao($familia);
            }
            
            $dados = [
                'id'            => $id,
                'grupo'         => $grupo,
                'eficiencia'    => $eficiencia,
                'desc'          => $desc,
                'ccusto'        => $ccusto,
                'estab'         => $estab,
                'data'          => $data
            ];
            
            $gpss = _25800::selectListGP($id);
            $descgp = $gpss[0]->DESC;
            $gp     = $gpss[0]->COD;
            
            $descs = explode(',',$descgp);
            
            $gps = explode(",", $gp);
            $col = count($gps)+2;
            
            if(($total == 1) && ($col > 3)){
                
                if($col < 10){
                    $desc_ind = 'INDICADORES';
                    $p = 'TOTAL';
                }else{
                    $p = 'TOT.';
                    $desc_ind = 'INDI.';
                }
                
                array_push($gps,$gp);
                array_push($descs,$p);
            }else{
                $col = count($gps)+1;
                
                if($col < 10){
                    $desc_ind = 'INDICADORES';
                    $p = 'TOTAL';
                }else{
                    $p = 'TOT.';
                    $desc_ind = 'INDI.';
                }
                
            }
            
            $indicadores = 10 + 1;
            
            $font = 3 - ($col*0.11);
            
            $perc = 96/$indicadores;
            for($i = 0; $i < $indicadores; $i++){
                $arrayL[$i] = $perc;
            }

            $perc2 = 100/($col+1);
            for($i = 0; $i < $col; $i++){
                if($i == 0){
                    $arrayC[$i] = $perc2*2;
                }else{
                    $arrayC[$i] = $perc2;
                }
            }
            
            $c = 0;
            $r = [];
            $var = [];
            $val = [];
            
            //$perildo = 2;
            
            $dia = 1;

            if(intval($perildo) == 1){$chec1 = 'value=1 checked'; $dia = 1;}else{$chec1 = 'value=0';}
            if(intval($perildo) == 2){$chec2 = 'value=1 checked'; $dia = 1;}else{$chec2 = 'value=0';}
            if(intval($perildo) == 3){$chec3 = 'value=1 checked'; $dia = 1;}else{$chec3 = 'value=0';}             
            if(intval($perildo) == 0){$chec0 = 'value=1 checked'; $perildo = 1; $dia = 2;}else{$chec0 = 'value=0';}
            
            if(intval($total) == 1){$chec4 = 'value=1 checked';}else{$chec4 = 'value=0';}
            
            
            $val_garf = [];
            
            foreach ($gps as $a){
                
                $obj = self::bscpp($dados,$a,'',$dia,$data);
                
                $s = $obj['indicadores'];
                
                $ss = 'Data '.$obj['data_consulta'].' tratada em '.$obj['data_exec'];

                    for($i = 0;$i < ($indicadores - 1) ; $i++ ){
                        $r[$i] = $s[$i]['DESC'];
                        $r[$i] = $s[$i]['DESC'];
                        
                        $cor = $s[$i]['COR'.$perildo];
                        $valor =  $s[$i]['VALOR'.$perildo];
                        
                        $emp = ['valor' => $valor , 'cor' => $cor, 'font' => $font ];
                        
                        array_push($val_garf,[$a,$emp,$s[$i]['DESC']]);
                        
                        $val[$i] = $emp;
                    }
                
                $var[$c] = $val;
                
                $c++;
            }
            
            $grafico =[];
            $i = 0;
            $tempo = 1138683600000;
            $inc_tempo = 10000; 
            $cont = -1;
            
            
            foreach ($gps as $a){
                $cont++;
                $valores = [];
                
                $b = $descs[$cont];
                
                $filtered = array_filter($val_garf,
                function($v, $k) use ($a) {
                    return $v[0] == $a;

                }, ARRAY_FILTER_USE_BOTH);
                
                $i = 0;
                foreach ($filtered as $val){
                    $i++;
                    if($i > 1){
                        array_push($valores,[$tempo + ($inc_tempo * $i),$val[1]['valor']]);
                    }
                }
                
                array_push($grafico,[$b,$valores]);
            }
            
            return view('opex._25800.include.comparativoG1',[
                'coll'  => $arrayC,
                'linha' => $arrayL,
                'desc'  => $descs,
                'indi'  => $r,
                'var'   => $var,
                'dc_dat'=> $ss,
                'chec0'=> $chec0,
                'chec1'=> $chec1,
                'chec2'=> $chec2,
                'chec3'=> $chec3,
                'chec4'=> $chec4,
                'ds_in'=> $desc_ind,
                'graficos' => $grafico
            ]);  
        }
    }
    
    /**
    * dados de ranking do bsc(BSC-TV - tela )
    * return view
    * @package Opex
    * @category Controller
    */
    public function consultarankingDados($id,$grupo,$eficiencia,$desc,$ccusto,$estab,$data,$perildo,$total)
    {   
            set_time_limit(120);
            
            $dados = [
                'id'            => $id,
                'grupo'         => $grupo,
                'eficiencia'    => $eficiencia,
                'desc'          => $desc,
                'ccusto'        => $ccusto,
                'estab'         => $estab,
                'data'          => $data
            ];

            $gpss = _25800::selectListGP($id);
            
            if(count($gpss) > 0){
                $descgp = $gpss[0]->DESC;
                $gp     = $gpss[0]->COD;

                $descs = explode(',',$descgp);

                $gps = explode(",", $gp);
                $col = count($gps)+1;

                $indicadores = 10 + 1;

                $perc2 = 100/($col+1);
                for($i = 0; $i < $col; $i++){
                    if($i == 0){
                        $arrayC[$i] = $perc2*2;
                    }else{
                        $arrayC[$i] = $perc2;
                    }
                }

                $c = 0;
                $r = [];
                $var = [];
                $var2 = [];

                //$perildo = 2;

                $dia = 1;
                $media1 = 0;
                $media2 = 0;
                $media3 = 0;

                foreach ($gps as $a){

                    $obj = self::bscpp($dados,$a,'',$dia,$data);

                    $mes = date("m", strtotime($data));
                    $ano = date("y", strtotime($data));

                    if(intval($mes) < 2){$mes = '12'; $ano = strval(intval($ano)-1); }else{ $mes = strval(intval($mes)-1); }
                    $data2 = date("t.m.y", mktime(0,0,0,$mes,'01',$ano));       
                    $obj2 = self::bscpp($dados,$a,'',$dia,$data2);

                    $s = $obj['indicadores'];
                    $ss = $obj2['indicadores'];
                    $desc = 'Data:'.$obj['data_consulta'].' tratada em '.$obj['data_exec'].' (Cop.:'.$obj2['data_consulta'].')'  ;

                        for($i = ($indicadores - 2);$i < ($indicadores - 1) ; $i++ ){

                            $valor  =  $s[$i]['VALOR2'];
                            $valor2 =  $s[$i]['VALOR1'];
                            $valor3 =  $valor - $ss[$i]['VALOR2'];

                            $emp  = [ 'fab' => $descs[$c], 'valor' => number_format($valor, 2, '.', ','),   'ordem' => 0,'cod' => $a];
                            $emp2 = [ 'fab' => $descs[$c], 'valor' => number_format($valor2, 2, '.', ',') , 'ordem' => 0,'cod' => $a];
                            $emp3 = [ 'fab' => $descs[$c], 'valor' => number_format($valor3, 2, '.', ',') , 'ordem' => 0,'cod' => $a];

                            $media1 = $media1 + $valor2;
                            $media2 = $media2 + $valor;
                            $media3 = $media3 + $valor3;
                        }

                    $var[$c]  = $emp;
                    $var2[$c] = $emp2;
                    $var3[$c] = $emp3;

                    $c++;
                }

                $media1 = number_format(($media1 / $c), 2, '.', ',');
                $media2 = number_format(($media2 / $c), 2, '.', ',');
                $media3 = number_format(($media3 / $c), 2, '.', ',');

                $var  = Helpers::orderBy2($var, ['valor|desc']);
                $var2 = Helpers::orderBy2($var2,['valor|desc']);            
                $var3 = Helpers::orderBy2($var3,['valor|desc']);

                return [
                    'semana' => $var2,
                    'mes_1'  => $var,
                    'mes_2'  => $var3,
                    'media1' => $media1,
                    'media2' => $media2,
                    'media3' => $media3,
                    'desc'   => $desc,
                    'css'    => ''
                ];
            
            }else{
                
                return [
                    'semana' => [],
                    'mes_1'  => [],
                    'mes_2'  => [],
                    'media1' => [],
                    'media2' => [],
                    'media3' => [],
                    'desc'   => [],
                    'css'    => ''
                ];
                
            }
        
    }
    
    
    /**
    * show produção do objeto 25800 (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function showProducao()
    {   
        return view('opex._25800.show', ['tela' => 'producao']);     
    }
    
    
    /**
     * Lista horarios de troca do CEPO 
     * @access public
     * @return string
     * @static
    */
    public function listaHCEPO(Request $request)
    {   
        
        if( $request->ajax() ) {

            $estab      = $request->get('estab');

            $ret = _25800::listaHCEPO($estab);
            
            return $ret;
        }
    }
    
    /**
    * show TV do objeto 25800 (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function showBSCFull()
    {
        return view('opex._25800.showFull', ['tela' => 'bsc']);  
    }
    
    /**
    * show produção em toda a tela do objeto 25800 (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function showProducaoFull()
    {
        return view('opex._25800.showFull', ['tela' => 'producao']);  
    }
    
    /**
    * show TV em toda a tela do objeto 25800 (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function showBSC()
    {
        return view('opex._25800.show', ['tela' => 'bsc']);  
    }
    
    private function validarHora($hora){
        $intervalos = explode(';', $hora);
        $c = count($intervalos);
        
        if($c > 0){
            foreach ($intervalos as $intervalo){
                $horas = explode('-', $intervalo);
                $hora1 = $horas[0];
                $hora2 = $horas[1];
                
                $atual = strtotime(date('H:i'));
                
                if ( (strtotime($hora1) <= $atual) && (strtotime($hora2) >= $atual) ){
                    return 1;
                }else{
                    return 0;
                }
            }
        }else{
            return 2;
        }
    }
    
    /**
     * consulta todo os trofeis de uma fabrica durante um ano
     * @access public
     * @return array
     * @static
    */
    public static function consultatrofeuallgp(Request $request){
        if( $request->ajax() ) {
            
            $estab = $request->get('estab');
            $id = $request->get('id');
            $ano = date('Y',strtotime($request->get('data')));
            
            $ret = _25800::consultatrofeuallgp($ano,$estab,$id);
            
            $res = '';
            if(count($ret) > 0){
                foreach ($ret as $mes){
                    $trofeu  = $mes->TROFEU;
                    $perildo = $mes->MES;
                    
                    $res .= '<div class="tpfel-rodape trofeu'.$trofeu.'-fogos"><div class="img'.$perildo.'"></div></div>';
                    
                }
            }
            
            return $res;
            
        }
    }
    
    /**
     * consulta trofeis
     * @access public
     * @param int $mes
     * @param int $ano
     * @param int $estab Estabelecimento
     * @return string
     * @static
    */
    public static function consultatrofeu(Request $request)
    {
        if( $request->ajax() ) {
            $estab = $request->get('estab');
            $mes = intval(date('m',strtotime($request->get('mes')))) - 1;
            $ano = date('Y',strtotime($request->get('mes')));
            
            if($mes < 1){
                $mes = 12;
                $ano = intval($ano) - 1;
            }
            
            $gp  = intval($request->get('gp'));
            
            $ret = _25800::consultatrofeu($mes,$ano, $estab);
            
            if(count($ret) > 0){
                
               $o = $ret[0]->GP_IDO;
               $p = $ret[0]->GP_IDP;
               $b = $ret[0]->GP_IDB;
               
               $r = 0;
               
                switch ($gp) {
                    case $o: $r = 1; break;
                    case $p: $r = 2; break;
                    case $b: $r = 3; break;
                }
                
            }else{
                $r = 0;
            }
            
            return $r;
            
        }  
    }

    public function geraranuncio($id){
        $dir = env('APP_TEMP', '').'/anuncios/';

        deleleFilesTree2($dir);


        $est = ['.JPG','.BMP','.PNG','.JPEG','.PDF','.ZIP','.TXT'];
        $ez = 0;
        $name = '';
        $res = [];
        
            $ret = Arquivo::getFile($id,'TBGP');
            
            
            
            foreach ($ret as $file){
                $fid = $file->ARQUIVO_ID;
                
                $hora = $file->OBSERVACAO;
                
                $h = self::validarHora($hora);
                
                if($h == 1){
                    foreach ($est as $e){

                        if(file_exists($dir.$fid.$e)){
                            $ez = 1;
                            $name = $fid.$e;
                        }
                    }

                    if($ez == 0){
                        $arquivo = Arquivo::gerarFile($fid,$dir);
                        array_push($res,$arquivo);
                    }else{
                        array_push($res,$name);
                    }

                    $ez = 0;
                    
                }
            }
            
            if(count($res) < 1){
                //array_push($res,'DEFULL.jpg');
            }
            
        return $res;
        
    }
    
    /**
    * tela de anuncios (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function consultaanuncio(Request $request)
    {
        if( $request->ajax() ) {
            
            $id         = $request->get('id');
            $grupo      = $request->get('grupo');
            $eficiencia = $request->get('eficiencia');
            $desc       = $request->get('desc');
            $ccusto     = $request->get('ccusto');
            $estab      = $request->get('estab');
            $data       = $request->get('data');
            $perildo    = $request->get('perildo');
            $total      = $request->get('total');
            
            $imgs = self::geraranuncio($id,$grupo,$eficiencia,$desc,$ccusto,$estab,$data,$perildo,$total);
            
            if(count($imgs) > 0){
                return view('opex._25800.include.anuncios',['imgs' => $imgs]);
            }else{
                return '0';
            }
        }  
    }
    
    /**
    * tela de ranking do bsc (BSC-TV)
    * return view
    * @package Opex
    * @category Controller
    */
    public function consultaranking(Request $request)
    {
        if( $request->ajax() ) {
            
            $id = _25800::selectTodasGPS();
            
            //$id         = $request->get('id');
            $grupo      = $request->get('grupo');
            $eficiencia = $request->get('eficiencia');
            $desc       = $request->get('desc');
            $ccusto     = $request->get('ccusto');
            $estab      = $request->get('estab');
            $data       = $request->get('data');
            $perildo    = $request->get('perildo');
            $total      = $request->get('total');
            $auto       = $request->get('autoinicia');
            $familia    = $request->get('familia');
                    
            if(intval($auto) == 1){
                $data = _25800::dataproducao($familia);
            }
            
            $ret = self::consultarankingDados($id,$grupo,$eficiencia,$desc,$ccusto,$estab,$data,$perildo,$total);
            
            return view('opex._25800.include.ranking',$ret);
        }
        
    }
    
    /**
    * calcula os trofeis de um mes
    * return view
    * @package Opex
    * @category Controller
    */
    public function calctrofeu(Request $request)
    {
           
            $mes    = $request->mes;
            $ano    = date("Y");
            $estab  = $request->estab;
            
            $id = _25800::selectTodasGPS();
            
            $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
            
            $grupo      = 0;
            $eficiencia = 0;
            $desc       = 0;
            $ccusto     = 0;
            $data       = $ultimo_dia.'.'.$mes.'.'.$ano;
            $perildo    = 0;
            $total      = 0; 
            
            $ret = self::consultarankingDados($id,$grupo,$eficiencia,$desc,$ccusto,$estab,$data,$perildo,$total);
            
            $ouro = $ret['mes_1'][0]['cod'];
            $prata = $ret['semana'][0]['cod'];
            $bronze = $ret['mes_2'][0]['cod'];
            
            print_r('ouro:  '.$ret['mes_1'][0]['fab'].' -> '.$ouro.' ');
            print_r('Prata: '.$ret['semana'][0]['fab'].' -> '.$prata.' ');
            print_r('Bronze:'.$ret['mes_2'][0]['fab'].' -> '.$bronze.' ');
            
            _25800::gravatrofeu($ouro,$prata,$bronze,$mes,$ano,$estab);
            
            print_r('[gravado]');
            
            $css = 'display: inline-flex; border: 1px solid; padding: 3px;';
            
            $ret['css'] = $css;
            
            //Helpers::log_rr($ret);
            
            return view('opex._25800.include.ranking',$ret);
    }
    
    /**
    * letreiro
    * return view
    * @package Opex
    * @category Controller
    */
    public function letreiro(Request $request)
    {
        if( $request->ajax() ) {
            
            $id     = $request->get('id');
            $ccusto = $request->get('ccusto');
            $estba  = $request->get('estab');
            
            return  _25800::letreiro($id,$ccusto,$estba);

        }
    }
    
    
    
    
}
