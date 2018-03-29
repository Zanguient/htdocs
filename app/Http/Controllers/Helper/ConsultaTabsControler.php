<?php
/**
    * Controler da consulta generica
    * 
    * Este metodo chama e trada o retorno do metodo ConsultaAll::ConsultaAll
    * 
    * @version 1.0
    * @package Helper
    * @author Anderson Sousa <anderson@delfa.com.br>
    * @api
    * @deprecated 
    */
namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Helper\ConsultaAll;

class ConsultaTabsControler extends Controller
{
    /**
    * Retorna o numero de registros por magina
    * 
    * @version 1.0
    * @author Anderson Sousa <anderson@delfa.com.br>
    * @return int
    * @api
    */
    public function getPages()
    {
       return 10; 
    }
    
    /**
    * Controler da consulta generica
    * 
    * Este metodo chama e trada o retorno do metodo ConsultaAll::ConsultaAll
    * 
    * @version 1.0
    * @package Helper
    * @author Anderson Sousa <anderson@delfa.com.br>
    * @param String $filtro string que foi digitada pelo usuário no campo de filtro
    * @param string $obj local do arquivo que retornara os dados para tratamento
    * @param array $campos Um array com os campos da tabela que foram passados como parametro
    * @param array $condicao Um array com os parametros que foram passados
    * @return array
    * @api
    */
    
    /**
    * Retorna uma string codificada para identificar o cache
    * 
    * @version 1.0
    * @author Anderson Sousa <anderson@delfa.com.br>
    * @return string
    * @api
    */
    public function getTagCache()
    {
        $str  = date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000));
        $str .= \Auth::user()->USUARIO;

        $tag = base64_encode($str);
        
        return $tag;
    }
    
    /**
    * Salva um array no cache
    * 
    * @version 1.0
    * @author Anderson Sousa <anderson@delfa.com.br>
    * @param array $dados informações que serão armasenas no cache
    * @return string que identifica o cache salvo
    * @api
    */    
    public function salveCache($dados)
    {
        $tag = self::getTagCache();
        \Cache::put($tag,$dados,20);
           
        return $tag;
    }
    
    /**
    * Salva um array no cache
    * 
    * @version 1.0
    * @author Anderson Sousa <anderson@delfa.com.br>
    * @param string $tag Identificação do cache
    * @return array
    * @api
    */   
    public function getCache($tag)
    {
        $dados = \Cache::get($tag);
           
        return $dados;
    }
    
    
    /**
    * Função que carega uma determinada pagina de uma consulta
    * 
    * @version 1.0
    * @author Anderson Sousa <anderson@delfa.com.br>
    * @param Request $request dados enviados via ajx Ex.: $tag e $pag
    * @return array
    * @api
    */ 
    public function consultaMais(Request $request)
    {
        /**
         * var string $tag Identificador do cache
         */
        $tag     = $request->get('tag');
        
        /**
         * var string $pag Ultima pagina caregada de uma consulta
         */
        $pag     = $request->get('pag'); 
        
        $dados   = self::getCache($tag);
        
        if( !empty($dados) ) {
            
                $retConsulta    = $dados['dados']; 
                $tabela         = $dados['tabela'];
                $tamanhos       = $dados['tamanhos']; 
                $campos         = $dados['campos']; 
                $conluna        = 'colunap';
                
                $page = self::getPages();
                
                $res  = '';
                $cont3 = 0;
                $contPag1 =  $pag * $page;
                $contPag2 =  $pag * ($page * 2) + 1;
                $pag = $pag + 1;
                
                $tot = count($retConsulta);
                
    			foreach ($retConsulta as $ret) {

                    if($cont3 > $contPag1){
                        
                        if($cont3 < $contPag2){ 
                        $arr = (array)$ret;

                        $res .= '<li>';
                        $res .= '<a href="#" >';

                            $cont = 0;
                            foreach ($tabela as $tab) {
                                  $res .= '<span class="span-consulta  '.$conluna.' span-'.$tab.' tipocoluna c'.$tamanhos[$cont].'">'.$arr[$tab].'</span>';
                                  $cont++; 
                            }

                            foreach ($campos as $campo) {
                            $res .= '<input type="hidden" class="_consulta_'.$campo.'" value="'.$arr[$campo].'" />';
                            }

                        $res .= '</a>';

                        $res .= '</li>';

                        }
         
                    }
                    
                    $cont3++;
                }
                
                if ($contPag2 < $tot){
                    $res .= '<button type="button" class="input-group-addon search-button btn-caregar-mais" style="display: inline-block;" tag='.$tag.' pag="'.$pag.'" ><span class="">Carregar mais</span></button>';
                    $res .= '<button type="button" class="input-group-addon search-button btn-caregar-mais-all" style="display: inline-block;" tag='.$tag.' pag="'.$pag.'" ><span class="">Carregar todos</span></button>';
                }
    		}
    
    		$res .= '</ul>';
    			
    		echo $res;
    }
    
    /**
    * Função que carega todas as pagina de uma consulta
    * 
    * @version 1.0
    * @author Anderson Sousa <anderson@delfa.com.br>
    * @param Request $request dados enviados via ajx Ex.: $tag e $pag
    * @return array
    * @api
    */
    public function consultaMaisAll(Request $request)
    {   
        /**
         * var string $tag Identificador do cache
         */
        $tag     = $request->get('tag');
        
         /**
         * var string $pag Ultima pagina caregada de uma consulta
         */
        $pag     = $request->get('pag'); 
        
        /**
         * var string $tab tab selecionada
         */
        $tab     = $request->get('tab'); 
        
        $dados   = self::getCache($tag);
        
        if( !empty($dados) ) {
            
                $retConsulta    = $dados['dados'][$tab]; 
                $tabela         = $dados['tabela'];
                $tamanhos       = $dados['tamanhos']; 
                $campos         = $dados['campos']; 
                $conluna        = 'colunap';
                
                $page = self::getPages();
                
                $res  = '';
                $cont3 = 0;
                $contPag1 =  $pag * $page;
                $pag = $pag + 1;
                $tot = count($retConsulta);
                $contPag2 = $tot+1;
                        
    			foreach ($retConsulta as $ret) {

                    if($cont3 > $contPag1){
                        
                        if($cont3 < $contPag2){ 
                        $arr = (array)$ret;

                        $res .= '<li class="tabe-gered-'.$tab.'">';
                        $res .= '<a href="#" >';

                            $cont = 0;
                            foreach ($tabela as $tab) {
                                  $res .= '<span class="span-consulta  '.$conluna.' span-'.$tab.' tipocoluna c'.$tamanhos[$cont].'">'.$arr[$tab].'</span>';
                                  $cont++; 
                            }

                            foreach ($campos as $campo) {
                            $res .= '<input type="hidden" class="_consulta_'.$campo.'" value="'.$arr[$campo].'" />';
                            }

                        $res .= '</a>';

                        $res .= '</li>';

                        }
         
                    }
                    
                    $cont3++;
                }
                
                
    		}
    
    		$res .= '</ul>';
    			
    		echo $res;
    }
    
    
    /**
    * Função que executa uma consulta com base nos parametros recebidos via ajax
    * 
    * @version 1.0
    * @author Anderson Sousa <anderson@delfa.com.br>
    * @param Request $request dados enviados via ajx Ex.: $filtro, $campos...
    * @return array
    * @api
    */
    public function consulta(Request $request)
    {
    	if( $request->ajax() ) {
            
            $filtro     = $request->get('filtro');
            $obj        = $request->get('obj');
            $campos     = $request->get('campos');
            $condicao   = $request->get('condicao');
            $tabela     = $request->get('tabela');
            $titulos    = $request->get('titulos');
            $tamanhos   = $request->get('tamanhos');
            $tabs       = $request->get('tabs');
            $tab_con    = $request->get('tabs_consulta');
            $limit      = $request->get('limit');
            
            $retConsulta = ConsultaAll::ConsultaAll($filtro,$obj,$campos,$condicao);
            
            $rets = [];
            $c    = 0;  
            foreach ($tab_con as $tap) {
                $retConsulta = ConsultaAll::ConsultaAll($filtro,$tap,$campos,$condicao);
                $rets[$c] = $retConsulta; 
                $c++;
            }
            
            
            $dados = [
                'dados'     => $rets, 
                'tabela'    => $tabela,
                'tamanhos'  => $tamanhos,
                'campos'    => $campos,
                'tab'       => $tabs,
                'tab_con'   => $tab_con,
            ];
            
            $tag = self::salveCache($dados);
 
            $conluna = 'colunap';
            
            $res = '<div class="pesquisa-res lista-consulta-tabs">';
            $d = 0;
                    foreach ($tabs as $tap) {
                        if($d == 0 ){
                            $res .= '<div class="tabs-consulta tab-selected" tabcont="'.$d.'">'.$tap.'</div>';
                        }else{
                            $res .= '<div class="tabs-consulta" tabcont="'.$d.'">'.$tap.'</div>';
                        }
                        $d++;
                    }
            $res .= '</div>';
    		$res .= '<ul class="nav consulta-lista">';
    
    		$d = 0;
            
                foreach ($tab_con as $tap) {
                    $retConsulta = $rets[$d];
                    
                    if( !empty($retConsulta) ) {
                        
                        if($d > 0){
                            $addclass = 'desabilitado-lista';
                        }else{
                            $addclass = '';    
                        }
                        
                        $res .= '<li class="tabe-gered-'.$d.' '.$addclass.'">';
                        $res .= '<div class="titulo-lista">';

                            $cont = 0;
                            foreach ($titulos as $titulo) {
                                $res .= '<span class="span-consulta '.$conluna.' span-'.$tabela[$cont].' tipocoluna c'.$tamanhos[$cont].'">'.$titulo.'</span>';
                                $cont++;
                            }

                        $res .= '</div>';
                        $res .= '</li>';

                        $page = self::getPages();

                        $cont1 = 0;
                        $cont2 = $page+1;
                        $tot = count($retConsulta);

                        if ($limit == 0){$cont2 = $tot;}


                        foreach ($retConsulta as $ret) {
                            $arr = (array)$ret;

                            if ($cont1 < $cont2){
                                $res .= '<li class="tabe-gered-'.$d.'  '.$addclass.'">';
                                $res .= '<a href="#" class="tem-lista">';

                                $cont = 0;
                                foreach ($tabela as $tab) {
                                      $res .= '<span class="span-consulta  '.$conluna.' span-'.$tab.' tipocoluna c'.$tamanhos[$cont].'">'.$arr[$tab].'</span>';
                                      $cont++; 
                                }

                                foreach ($campos as $campo) {
                                $res .= '<input type="hidden" class="_consulta_'.$campo.'" value="'.$arr[$campo].'" />';
                                }

                                $res .= '</a>';

                                $res .= '</li>';
                            }

                            $cont1++;
                        }

                        if ($cont2 < $tot){
                            $res .= '<button type="button" class="input-group-addon search-button btn-caregar-mais tabe-gered-'.$d.'  '.$addclass.'" style="display: inline-block;" tag='.$tag.' pag="1" ><span class="">Carregar mais</span></button>';
                            $res .= '<button type="button" class="input-group-addon search-button btn-caregar-mais-all tabe-gered-'.$d.'  '.$addclass.'" style="display: inline-block;" tag='.$tag.' pag="1" ><span class="">Carregar todos</span></button>';
                        }
                        
                    }else $res .= '<div class="nao-cadastrado tabe-gered-'.$d.'  '.$addclass.'">N&atildeo Encontrado.</div>';
    
                    
                    $d++;
                }
    		
    		$res .= '</ul>';
    			
    		echo $res;
            
    	}
    }

}
