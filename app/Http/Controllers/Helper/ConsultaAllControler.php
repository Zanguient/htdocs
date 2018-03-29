<?php

namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Helper\ConsultaAll;
use \Zend\Validator\IsCountable;


class ConsultaAllControler extends Controller
{
    public function getPages()
    {
       return 30; 
    }
    
    public function getTagCache()
    {
        $str  = date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000));
        $str .= \Auth::user()->USUARIO;

        $tag = base64_encode($str);
        
        return $tag;
    }
    
    public function salveCache($dados)
    {
        $tag = self::getTagCache();
        \Cache::put($tag,$dados,20);
           
        return $tag;
    }
    
    public function getCache($tag)
    {
        $dados = \Cache::get($tag);
           
        return $dados;
    }
    
    public function consultaMais(Request $request)
    {
        $tag     = $request->get('tag');
        $pag     = $request->get('pag'); 
        
        $dados   = self::getCache($tag);

        $res  = '';
        
        if( !empty($dados) ) {
        
            $retConsulta    = $dados['dados']; 
            $tabela         = $dados['tabela'];
            $tamanhos       = $dados['tamanhos']; 
            $campos         = $dados['campos']; 
            $conluna        = 'colunap';
            
            $page = self::getPages();
            
            
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

            $res .= '</ul>';
		}
    
    	echo $res;
    }
    
    public function consultaMaisAll(Request $request)
    {
        $tag     = $request->get('tag');
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
                $pag = $pag + 1;
                $tot = count($retConsulta);
                $contPag2 = $tot+1;
                        
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
                
                
    		}
    
    		$res .= '</ul>';
    			
    		echo $res;
    }
           
    public function consulta(Request $request)
    {
    	if( $request->ajax() ) {
            $imputs = [];
            
            $filtro         = $request->get('filtro'        );
            $obj            = $request->get('obj'           );
            $campos         = $request->get('campos'        );
            $condicao       = $request->get('condicao'      );
            $condicao_campo = $request->get('condicao_campo');
            $tabela         = $request->get('tabela'        );
            $titulos        = $request->get('titulos'       );
            $tamanhos       = $request->get('tamanhos'      );
            $imput          = $request->get('imputhidden'   );
            $opcao_todos    = $request->get('opcao_todos'   );
            $set_todos		= $request->get('set_todos'     );
            $get_todos		= $request->get('get_todos'     );
            
            $ct = new IsCountable();
            
            foreach($imput as $value){
                    
                    if( $ct->isValid($value) && count($value) > 1){

                        $nome  = $value[0];
                        $valor = $value[1];

                        $imputs[$nome] = $valor;
                    }
            }        
            
            $retConsulta = ConsultaAll::ConsultaAll($filtro,$obj,$campos,$condicao,$condicao_campo,$imputs);
    
            $dados = [
                'dados' => $retConsulta, 
                'tabela' => $tabela,
                'tamanhos' => $tamanhos,
                'campos' => $campos,
            ];
            
            $tag = self::salveCache($dados);
 
            $conluna = 'colunap';
    		$res = '<ul class="nav consulta-lista">';
    
    		if( !empty($retConsulta) ) {
                
                $res .= '<li>';
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

                             
    			foreach ($retConsulta as $ret) {
                    
                    if ($cont1 < $cont2){
                        $res .= '<li>';
                        $res .= '<a href="#" class="tem-lista">';

                        $cont = 0;
                        foreach ($tabela as $tab) {
							
                            $res .= '<span class="span-consulta  '.$conluna.' span-'.$tab.' tipocoluna c'.$tamanhos[$cont].'">';
							
							//formata campos numéricos
							if(is_numeric($ret->$tab) && strpos($ret->$tab, '.')) {
								$res .= number_format($ret->$tab, 4, ',', '.');
							}
							else {
								$res .= $ret->$tab;
							}
							
							$res .= '</span>';
                            $cont++; 
							
                        }

                        foreach ($campos as $campo) {
							
							$res .= '<input type="hidden" class="_consulta_'.$campo.'" value="';
							
							//formata campos numéricos
							if(is_numeric($ret->$campo) && strpos($ret->$campo, '.')) {
								$res .= number_format($ret->$campo, 4, ',', '.');
							}
							else {
								$res .= $ret->$campo;
							}
							
							$res .= '" />';
							
                        }

                        $res .= '</a>';

                        $res .= '</li>';
                    }
                    
                    $cont1++;
    			}

				if (($opcao_todos === 'true' && $tot > 1) || ($set_todos == '1')) {
					$res .= '<li>';
					$res .= '<a href="#" class="tem-lista opcao_todos">';
					$res .= 'TODOS';                
					$res .= '<input type="hidden" class="_consulta_todos" value="todos_selecionado">';
					$res .= '</a>';                
					$res .= '</li>';
				}
                
                if ($cont2 < $tot){
                    $res .= '<button type="button" class="input-group-addon search-button btn-caregar-mais" style="display: inline-block;" tag='.$tag.' pag="1" ><span class="">Carregar mais</span></button>';
                    $res .= '<button type="button" class="input-group-addon search-button btn-caregar-mais-all" style="display: inline-block;" tag='.$tag.' pag="1" ><span class="">Carregar todos</span></button>';
                }
    		}
    		else{
				
				if ($set_todos == '1') {
					$res .= '<li>';
					$res .= '<a href="#" class="tem-lista opcao_todos">';
					$res .= 'TODOS';                
					$res .= '<input type="hidden" class="_consulta_todos" value="todos_selecionado">';
					$res .= '</a>';                
					$res .= '</li>';
				}
				else {
					$res .= '<div class="nao-cadastrado">N&atildeo Encontrado.</div>';
				}
			}
    
    		$res .= '</ul>';
    			
    		echo $res;
            
    	}
    }

}
