<?php

namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Helper\Turno;

class TurnoController extends Controller
{
            
    public function index()
    {
    	//
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
    
    public function filtrar(Request $request)
    {
    	if( $request->ajax() ) {
   
    		$turno = Turno::filtrar($request->get('filtro'));

    		$res = '<ul class="nav turno">';
    
    		if( !empty($turno) ) {
    				
    			foreach ($turno as $indicador) {
    				
    				$res .= '<li>';
    				$res .= '<a href="#">'. $indicador->MASK .' - '. $indicador->DESCRICAO .'</a>';
    				$res .= '<input type="hidden" class="turno-id" value="'. $indicador->ID .'"" />';
    				$res .= '<input type="hidden" class="turno-descricao" value="'. $indicador->DESCRICAO .'"" />';
    				$res .= '</li>';
    			}
    
    		}
    		else $res .= '<div class="nao-cadastrado">Turno n&atildeo cadastrado.</div>';
    
    		$res .= '</ul>';
    			
    		echo $res;
    	}
    }

}
