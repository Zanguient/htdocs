<?php
namespace resources\assets\js\doc_js\helper\includes;


/**
* Metodo de tratamento dos vampos e disparadores ajax par o controler de consultas
*
* 
* @version 1.0
* @package Includes
* @author Anderson Sousa <anderson@delfa.com.br>
* @api
*/
class Scrypt_consulta extends Scrypt
{		

       
       /** Retorna as classes dos campos que irão receber os valores que foram passados como parametro
        * 
        *@param json x imputs com as classes   
        *@returns array
		*@example <pre>
		*function getRecebeValor(x){
        * var obj = $(recebe_valor)[x];
        * 
        * var vclass = '.'+$(obj).attr('objClass');
        * var vcampo = ''+$(obj).attr('objCampo');
        * 
        * var ret = $(vclass);
        * 
        * $(ret).attr('objCampo',vcampo);
        * 
        * return ret;
        *}
		*</pre>
        **/
       function getRecebeValor($x){
       }
       
       /** Retorna os campos da consulta que foram passados como parametro
        *  
        *@returns array
        **/
       function getCampos(){
       }
       
       /** Retorna os as condições que foram passados como parametro
        *  
        *@returns array
        **/
       function getCondicao(){
       }
       
       /** Retorna os campos da tabela que sera montada com os parametros
        *  
        *@returns array
        **/
       function getTabela(){
       }
       
       /** Retorna os tamanhos dos campos da tabela que sera montada com os parametros
        *  
        *@returns array
        **/
       function getTamanhos(){
        }
       
       /** Retorna os titulos dos campos da tabela que sera montada com os parametros
        *  
        *@returns array
        **/
       function getTitulos(){
       }
       
       /** Seta os valores dos campos Ocultos, e Recebe valor como vazio
        **/
       function empytValores(){
       }
       
       /** adiciona os eventos dos items da lista que forão adicionados
        * 
        *@param json e objeto jquey do form-group  
        **/
       function trataItens($e){
		   
       }
        
       
		/** Chama a consulta com os parametros passados como parametros
        * 
        *@param json e objeto jquey que foi clicado  
        **/
		function filtrarConsulta($e) {
			
		}
        
        
        
        /** Chama a consulta da procima pagina a ser caregada
        * 
        *@param json e objeto jquey que foi clicado  
        **/
		function getMais($e) {

		}
        
        /** Chama a consulta com todas as paginas que estão faltando serem caregadas
        * 
        *@param json e objeto jquey que foi clicado  
        **/
		function getAll($e) {

		}

		/** Mostra lista com o resultado da consulta na tela
        * 
        *@param json consulta objeto jquey que contem a lista  
        **/
		function abreListaConsulta($consulta) {

		}

		/** Fecha lista com o resultado da consulta na tela
        * 
        *@param json consulta objeto jquey que contem a lista 
        *@param json e objeto jquey do form-group  
        **/
		function fechaListaConsulta($consulta,$e) {

		}

		/**
		 * Preencher campos de acordo com o item selecionado.
		 * 
		 * @param json itens Item que foi clicado
		 * @param json campo Campos que recebe valor
		 */
		function selecItemListaConsulta($itens, $campo) {
            

		}
		
		/**
		 * Se um item foi selecionado modifica os campos.
		 * 
		 * @param json e Objeto jquey do form-group
		 */
		function selecionadoConsulta($e) {
			
		}

		/**
		 * Inicializa os eventos que irão disparaar as chamadas dos metodos de consulta
		 */
		function iniciarFiltroConsulta() {
			
		}
		
		/**
		 * Chama o metodo getMais()
		 */
		function onClic_btn_caregar_mais() {
			
		}
  
?>