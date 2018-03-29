<?php

namespace App\Http\Controllers\Opex;

use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11010;
use App\Http\Controllers\Controller;
use App\Models\DTO\Opex\_25600;
use Illuminate\Support\Facades\Response;



/**
 * Controler do objeto 25600
 * @package Opex
 * @category Controller
 */
class _25600Controller extends Controller {
    
    public function removeZerosD($Str){
        $cont = 0;
        $tot = strlen($Str) - 1;
        $valor = $Str;
        for ($i = 0; $i <= $tot ; $i++) {
         
          $sub = substr($valor,-1);

          if($sub == 0){
            $valor = substr($valor,0,-1);
            $cont++;
          }else{
            $i = strlen($Str); 
          }
        }
        
        if($cont !== 0){
            return $valor; 
        }else{
            return $Str;
        }
    }
    
        
    /**
     * show do objeto 25600
     * return view
     * @package Opex
     * @category Controller
     */
    public function sucessoGravar() {
        _11010::permissaoMenu(25600);
        $permissaoMenu = _11010::permissaoMenu(25600);

        return view('opex._25600.show', ['permissaoMenu' => $permissaoMenu]);
    }
    
    /**
     * show do objeto 25600
     * return view
     * @package Opex
     * @category Controller
     */
    public function show(Request $request) {
        _11010::permissaoMenu(25600);
        $permissaoMenu = _11010::permissaoMenu(25600);

        return view('opex._25600.show', ['permissaoMenu' => $permissaoMenu]);
    }

    /**
     * Consulta os registros de indicadores do objeto 25600
     * return tabela html
     * @package Opex
     * @category Controller
     */
    public function consultaAlditorias(Request $request) {
        _11010::permissaoMenu(25600);

        if ($request->ajax()) {
            $ccusto = $request->get('CCUSTO');
            $data = $request->get('DATA');
            
            $indicadores = _25600::consultarIndicadores($ccusto, $data);
            
            $res ='';
            $cont = 0;
            
            foreach ($indicadores as $indicador) {
                             
                $cont++;
            
                $res .= "<tr indID=".$indicador->ID.">";
                $res .= "  <td>".$indicador->ID."</td>";
                $res .= "  <td>".$indicador->DESCRICAO."</td>";
                $res .= "  <td>".$indicador->DATA_HORA."</td>";
                $res .= "  <td>".$indicador->TURNO."</td>";
                $res .= " </tr>";
            }
            
            if ( $res === "" ){
                $res = '<tr><td cellspacing="4" colspan="4"> <div class="tabela-vazia"> Sem registros </div> </td></tr>';
            }
            
            return Response::json($res);
        }
    }

    
    
    /**
     * Consulta valores do registro de um indicador do objeto 25600
     * return tabela html
     * @package Opex
     * @category Controller
     */
    public function consultaAlditoria(Request $request) {
        _11010::permissaoMenu(25600);

        if ($request->ajax()) {
            $id = $request->get('id');

            $indicadores = _25600::consultarRegistroIndicadores($id);
            
            $res ='';
            $cont = 0;
            $soma = 0;
            foreach ($indicadores as $indicador) {
                             
            $cont++;
                
                $valor = $this->removeZerosD($indicador->VALOR);
                
                $cor = '';

                if ($indicador->PLANACAO_STATUS == 0){$cor = 'verde';}
                if ($indicador->PLANACAO_STATUS == 1){$cor = 'vermelho';}
                if ($indicador->PLANACAO_STATUS == 2){$cor = 'amarelo';}
                if ($indicador->PLANACAO_STATUS == 3){$cor = 'laranja';}
                if ($indicador->PLANACAO_STATUS == 4){$cor = 'azul';}
                
                
                $res .= "<tr class='valor'>";
                $res .= "  <td>";
                $res .= "    <div class='table-color'>";
                $res .= "       <div class='table-color-border'>";
                $res .= "           <div class='Square-Color ".$cor."'>";
                $res .= "           </div>";
                $res .= "       </div>";
                $res .= "    </div>";
                $res .= "  </td>";
                $res .= "  <td >".$indicador->SEQUENCIA."</td>";
                $res .= "  <td >".$indicador->DESCRICAO."</td>";
                $res .= "  <td><input type='text' size='7' class='media-valor form-control'value='".$valor."' readonly></td>";
                $res .= " <td><button indid='".$indicador->ID."' type='button' class='btn btn-primary btn-sm editar-nota' data-toggle='modal' data-target='#modal-editar' ><span class='glyphicon glyphicon-edit'></span> Alterar</button></td>";
                $res .= "<input type='hidden' class='_id_item' name='_id_item' value='".$indicador->ID."'>";
                $res .= "<input type='hidden' class='_id_indicador' name='_id_indicador' value='".$id."'>";
                $res .= "<input type='hidden' class='_id_detalhe' name='_id_detalhe' value='".$indicador->BSC_DETALHE_ID."'>";
                $res .= "<input type='hidden' class='_id_indicador_' name='_id_indicador_' value='".$indicador->BSC_ID."'>";
                $res .= "</tr>";
                $soma = $soma+$valor;
            }
                if ($cont > 0){
                    $media= $soma/$cont;
                }else{
                    $media = 0;    
                }
                
                $res .= "<tr>";
                $res .= "  <td colspan='5'><input type='text' size='7' class='edit-media form-control' value='".$media."' readonly> </td>";
                $res .= "</tr>";
                
            if ( $res === "" ){
                $res = '<tr><td cellspacing="4" colspan="5"> <div class="tabela-vazia"> Sem registros </div> </td></tr>';
            }
            
            return Response::json($res);

        }
    }
    
    /**
     * Consulta faixas de um indicador do objeto 25600
     * return tabela html
     * @package Opex
     * @category Controller
     */
    public function listaFaixas(Request $request) {
        _11010::permissaoMenu(25600);

        if ($request->ajax()) {
            $id = $request->get('id');

            $indicadores = _25600::listaFaixas($id);
            
            $res ='';
            $cont = 0;
            
            foreach ($indicadores as $indicador) {
                             
            $cont++;
               
                $cor='';
                
                $res .= "<tr class='valor'>";
                $res .= "  <td>";
                $res .= "    <div class='table-color'>";
                $res .= "       <div class='table-color-border'>";
                $res .= "           <div class='Square-Color'>";
                $res .= "           </div>";
                $res .= "       </div>";
                $res .= "    </div>";
                $res .= "  </td>";
                $res .= "  <td >".$indicador->DESCRICAO."</td>";
                $res .= "  <td >".$indicador->PESO."</td>";
                $res .= "  <td><input type='text' size='7' class='keyboard-numeric2 imput-editar-valor qtd mask-numero no-atualiza-lista media-valor";
                $res .= " validar-valor form-control' type='number' min='1' decimal='4' autofocus ";
                $res .= " min=".$indicador->MENOR." max=".$indicador->MAIOR." A1='".$indicador->MAIOR_1."' A2='".$indicador->MENOR_1."' plano='' addplano='0' required> </td>";
                $res .= "<input type='hidden' class='_id_item' name='_id_item' value='".$indicador->ID."'>";
                $res .= "<input type='hidden' class='_id_indicador' name='_id_indicador' value='".$id."'>";
                $res .= "<input type='hidden' class='_id_detalhe_item' name='_id_detalhe_item' value='".$indicador->BSC_DETALHE_ID."'>";
                $res .= "<input type='hidden' class='_peso_indicador' name='_peso_indicador' value='".$indicador->PESO."'>";
                $res .= "</tr>";
                
            }
            
                $res .= "<tr>";
                $res .= "  <td colspan='4'><input type='text' size='7' class='edit-media form-control' readonly> </td>";
                $res .= "</tr>";
            
            if ( $res === "" ){
                $res = '<tr><td cellspacing="4" colspan="5"> <div class="tabela-vazia"> Sem registros </div> </td></tr>';
            }
            
            return Response::json($res);

        }
    }

    /**
     * create do objeto 25600
     * return view
     * @package Opex
     * @category Controller
     */
    public function create(Request $request) {
        _11010::permissaoMenu(25600, 'INCLUIR');
        return view('opex._25600.create');
    }
    
    /**
     * store do objeto 25600
     * return view
     * @package Opex
     * @category Controller
     */
    public function store(Request $request) {
        _11010::permissaoMenu(25600, 'INCLUIR');
        
        if ($request->ajax()) {
            
            $dados   = $request->get('DADOS');
            $bsc     = $request->get('BSC');
            $data    = $request->get('DATA');
            $ccusto  = $request->get('CCUSTO');
            
            $indicadores = _25600::store($dados,$bsc,$data,$ccusto);

            return $indicadores;
        }
    }
    
    /**
     * store do objeto 25600
     * return view
     * @package Opex
     * @category Controller
     */
    public function alterarNota(Request $request) {
        
        _11010::permissaoMenu(25600, 'ALTERAR');

        if ($request->ajax()) {
            
            $id = $request->get('ID');
            $valor = $request->get('VALOR');
            $indicador = $request->get('INDICADOR');
            $plano = $request->get('PLANO');
            $idDetalhe = $request->get('IDDETALHE');
            $descPlano = $request->get('DESCPLANO');
            $idIndicador = $request->get('IDINDICADOR');
            
            $indicadores = _25600::alterarIndicador($id,$valor,$indicador,$plano,$idDetalhe,$descPlano,$idIndicador);
            return $indicadores;
        }

    }
    
    /**
     * Consulta descrição de uma faixa que não é verde
     * return view
     * @package Opex
     * @category Controller
     */
    public function consultaDescricaoFaixa(Request $request){
        
        if ($request->ajax()) {
            
            $valor = $request->get('VALOR');
            $indicador = $request->get('INDICADOR');
            $idDetalhe = $request->get('IDDETALHE');
            
            $ret = _25600::consultaDescricaoFaixa($indicador,$idDetalhe,$valor);
            return $ret;
        }
        
    }
    
    /**
     * Consulta descrição de uma faixa que não é verde
     * return view
     * @package Opex
     * @category Controller
     */
    public function consultaDescricaoFaixas(Request $request){
        
        if ($request->ajax()) {
            
            $indicador = $request->get('INDICADOR');
            $idDetalhe = $request->get('IDDETALHE');
            $class     = $request->get('CLASS');
            
            $ret = _25600::consultaDescricaoFaixas($indicador,$idDetalhe);
            
            $res = '<table class="table table-bordered table-striped table-hover tabulado2 table-selectable tabela-4">
                        <thead>
                            <tr>
                                <th class="coll-descricao"></th>
                            </tr>
                        </thead>
                        <tbody class="corpo-tabela-4">';
            
            foreach ($ret as $item) {
                $valor = self::removeZerosD($item->FAIXA_1);
                $desx = $item->DESCRICAO;
                
                $res .= "<tr class='".$class."' valor='".$valor."' desc='".$desx."' >";
                //$res .= "  <td>".$valor."</td>";
                $res .= "  <td>".$desx."</td>";
                $res .= "</tr>";
                
            }
            
            $res .= "</tbody>";
            $res .= "</table>";
                
            return Response::json($res);
        }
              
    }
    
}
