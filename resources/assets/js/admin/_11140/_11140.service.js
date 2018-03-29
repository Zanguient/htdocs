/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


angular
    .module('app')
    .service('Consulta', Consulta);
    
    
	Consulta.$inject = [];

	function Consulta() {
        
    	// MÉTODOS (REFERÊNCIAS)
        this.consultarUsuario = consultarUsuario;
    	this.consultarTarefa  = consultarTarefa;

    	// MÉTODOS
    	
    	/**
    	 * Consultar usuário.
    	 */
	    function consultarUsuario() {

	    	return $ajax.post('/_11010/listarTodos', null, {contentType: 'application/json'});
		}

        /**
         * Consultar tarefa.
         */
        function consultarTarefa(param) {

            return $ajax.post('/_29010/consultarTarefa', JSON.stringify(param), {contentType: 'application/json'});
        }
	}    