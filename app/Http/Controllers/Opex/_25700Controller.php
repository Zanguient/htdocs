<?php

namespace App\Http\Controllers\Opex;

use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11010;
use App\Http\Controllers\Controller;
use App\Models\DTO\Opex\_25700;
use Exception;
use App\Models\DTO\Financeiro\_20030;
use App\Models\DTO\Admin\_11030;
use App\Models\DTO\Opex\_25200;

/**
 * Controler do objeto 25700
 * @package Opex
 * @category Controller
 */
class _25700Controller extends Controller {

    public function prodgp(Request $request) {
        if ( $request->ajax() ) {
            
            $dados = $request->all();
            
            $dados = _25700::prodgp($dados);
            
            $res = [];
            
            foreach ($dados as $dado){
                array_push($res ,[strtotime($dado->DATA_CONSULTA),$dado->PR2]);    
            }
            
            $ret = ['key' => 'F01' , 'values' => $res, 'mean' => 250];

            return $ret;
           
        }
    }
    
    /**
     * Gravar dados
     * @return array
     * @package Opex
     * @category Controller
     */
    public function store(Request $request) {
        if ( $request->ajax() ) {
            
            $dados = $request->all();
            
            $dados = _25700::store($dados);
            
            return self::listar($request);
           
        }
    }
    
    /**
     * Gravar dados
     * @return array
     * @package Opex
     * @category Controller
     */
    public function alterar(Request $request) {
        if ( $request->ajax() ) {
            
            $dados = $request->all();
            
            $dados = _25700::alterar($dados);
            
            return self::showitem($request);
           
        }
    }
    
    /**
     * show do objeto 25600
     * return view
     * @package Opex
     * @category Controller
     */
    public function listar(Request $request) {
        _11010::permissaoMenu(25700);
        $permissaoMenu = _11010::permissaoMenu(25700);
        
        $dado = $request->all();
        
        $tabela = _25700::getListaPA($dado);
        
        return view('opex._25700.include.listar', [
            'permissaoMenu' => $permissaoMenu,
            'imputs'        => $dado,
            'popup'         => 1,
            'turno'         => 1,
            'dados'         => $tabela
        ]);
    }
    
    /**
     * show do objeto 25600
     * return view
     * @package Opex
     * @category Controller
     */
    public function getTela(Request $request) {
        _11010::permissaoMenu(25700);
        $permissaoMenu = _11010::permissaoMenu(25700);
        
        $dado = $request->all();
        
        $ccusto = _20030::getCCusto($dado['ccusto']);
        
        if(count($ccusto) > 0){
            $desc_ccusto = $ccusto[0]->MASK.' - '.$ccusto[0]->DESCRICAO;
            $id_ccusto = $ccusto[0]->ID;
        }else{
            $id_ccusto = $dado['ccusto'];
            log_erro('Centro de custo '.$id_ccusto.' não liberado.');
        }
                
        $indicador = _25200::indicadorcontrole($dado['controlen']);
        
        if(count($indicador) > 0){
            $desc_indicador = $indicador[0]->MASK.' - '.$indicador[0]->DESCRICAO;
            $id_indicador   = $indicador[0]->ID;
        }else{
            log_erro('Controle  '.$dado['controlen'].' para Indicador não cadastrado.');
        }
        
        return view('opex._25700.include.store', [
            'permissaoMenu' => $permissaoMenu,
            'grupo'         => 1,
            'ccusto'        => $id_ccusto,
            'vinculo'       => $dado['vinculo'],
            'selecionado'   => 1,
            'valor1'        => $desc_ccusto,
            'valor2'        => $desc_indicador,
            'autofocus'     => 'autofocus',
            'readonly'      => 'readonly',
            'popup'         => 1,
            'turno'         => 1,
            'indicador'     => $id_indicador,
            'imputs'        => $dado
        ]);
    }
    
    /**
     * show do objeto 25600
     * return view
     * @package Opex
     * @category Controller
     */
    public function show(Request $request) {

        return view('opex._25700.index');
    }
    
    
    /**
     * show do objeto 25600
     * return view
     * @package Opex
     * @category Controller
     */
    public function show1(Request $request) {

        $dados = $request->all();

        $ret = _25700::pendente(['INV'=>4,'TIPO'=>1]);
        
        return view('helper.olt.index', ['dados' => $ret]);
    }
    
    public function show2(Request $request) {

        $dados = $request->all();

        $ret = _25700::pendente(['INV'=>4,'TIPO'=>2]);
        
        return view('helper.olt.index', ['dados' => $ret]);
    }
    
    public function show3(Request $request) {

        $dados = $request->all();

        $ret = _25700::coletado(['INV'=>4,'TIPO'=>1]);
        
        return view('helper.olt.index', ['dados' => $ret]);
    }
    
    public function show4(Request $request) {

        $dados = $request->all();

        $ret = _25700::coletado(['INV'=>4,'TIPO'=>2]);
        
        return view('helper.olt.index', ['dados' => $ret]);
    }
    
    /**
     * Excluir plano de ação
     * return view
     * @package Opex
     * @category Controller
     */
    public function excluir(Request $request) {
        _11010::permissaoMenu(25700);
        $permissaoMenu = _11010::permissaoMenu(25700);
        
        $dado = $request->all();
        
        _25700::excluir($dado);
        
        return self::listar($request);
    }
    
    /**
     * Mostra um plano de ação
     * return view
     * @package Opex
     * @category Controller
     */
    public function showitem(Request $request) {
        _11010::permissaoMenu(25700);
        $permissaoMenu = _11010::permissaoMenu(25700);
        
        $dado = $request->all();
        
        $ccusto = _20030::getCCusto($dado['ccusto']);
        
        if(count($ccusto) > 0){
            $desc_ccusto = $ccusto[0]->MASK.' - '.$ccusto[0]->DESCRICAO;
            $id_ccusto = $ccusto[0]->ID;
        }else{
            $id_ccusto = $dado['ccusto'];
            log_erro('Centro de custo '.$id_ccusto.' não liberado.');
        }
        
        $indicador = _25200::indicadorcontrole($dado['controlen']);
        
        if(count($indicador) > 0){
            $desc_indicador = $indicador[0]->MASK.' - '.$indicador[0]->DESCRICAO;
            $id_indicador   = $indicador[0]->ID;
        }else{
            log_erro('Controle  '.$dado['controlen'].' para Indicador não cadastrado.');
        }
        
        $tabela = _25700::showitem($dado);
        
        return view('opex._25700.include.show', [
            'permissaoMenu' => $permissaoMenu,
            'tabela'        => $tabela,
            'grupo'         => 1,
            'ccusto'        => $id_ccusto,
            'vinculo'       => $dado['vinculo'],
            'selecionado'   => 1,
            'valor1'        => $desc_ccusto,
            'valor2'        => $desc_indicador,
            'autofocus'     => 'autofocus',
            'readonly'      => 'readonly',
            'popup'         => 1,
            'turno'         => 1,
            'indicador'     => $id_indicador,
            'imputs'        => $dado    
            ]);
    }
    
    
    /**
     * Mostra um plano de ação
     * return view
     * @package Opex
     * @category Controller
     */
    public function alteritem(Request $request) {
        _11010::permissaoMenu(25700);
        $permissaoMenu = _11010::permissaoMenu(25700);
        
        $dado = $request->all();
        
        $ccusto = _20030::getCCusto($dado['ccusto']);
        
        if(count($ccusto) > 0){
            $desc_ccusto = $ccusto[0]->MASK.' - '.$ccusto[0]->DESCRICAO;
            $id_ccusto = $ccusto[0]->ID;
        }else{
            $id_ccusto = $dado['ccusto'];
            log_erro('Centro de custo '.$id_ccusto.' não liberado.');
        }
        
        $indicador = _25200::indicadorcontrole($dado['controlen']);
        
        if(count($indicador) > 0){
            $desc_indicador = $indicador[0]->MASK.' - '.$indicador[0]->DESCRICAO;
            $id_indicador   = $indicador[0]->ID;
        }else{
            log_erro('Controle  '.$dado['controlen'].' para Indicador não cadastrado.');
        }
        
        $tabela = _25700::showitem($dado);
        
        return view('opex._25700.include.alterar', [
            'permissaoMenu' => $permissaoMenu,
            'tabela'        => $tabela,
            'grupo'         => 1,
            'ccusto'        => $id_ccusto,
            'vinculo'       => $dado['vinculo'],
            'selecionado'   => 1,
            'valor1'        => $desc_ccusto,
            'valor2'        => $desc_indicador,
            'autofocus'     => 'autofocus',
            'readonly'      => 'readonly',
            'popup'         => 1,
            'turno'         => 1,
            'indicador'     => $id_indicador,
            'imputs'        => $dado    
            ]);
    }
    
}
