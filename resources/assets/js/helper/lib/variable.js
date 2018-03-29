/**
 * Script com variáveis globais.
 */

var $ = jQuery;
var hostname = window.location.hostname;
/**
 * • Caminho acessado<br/>
 * Ex.: Caminho acessado: http://localhost/_13030/create<br/>
 * Retorno: /_13030/dre
 * @type string
 */
var pathname = window.location.pathname;
var urlhost  = document.location.origin;
var urlhash  = document.URL.substr(document.URL.indexOf('#')+1);

//valores padrões para DataTable
var table_default =
{
    scrollY  : "70vh", // Altura da tabela 
    scrollX  : true  , // Habilita a rolagem horizontal
    bSort    : false , // Desativa a ordenação
    bFilter  : false , // Desativa o filtro
    bInfo    : false , // Desativa as informações de registro
    bPaginate: false , // Desativa a paginação
    language : {emptyTable : "Não há registros para listar"}
};


