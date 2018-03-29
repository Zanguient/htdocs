/**
 * Retorna o ultimo dia do mês<br/>
 * Ex. de uso: lastDay(new Date(2016, 03-1, 01));<br/>
 * Resultado: 31
 * @param date
 * @returns int
 */
function lastDay(date){
   return (new Date(date.getFullYear(), date.getMonth() + 1, 0) ).getDate();
}

/**
 * Retorna a ultima data do mês<br/>
 * Ex. de uso: lastDate(new Date(2016, 03-1,01)); 
 * @param date
 * @returns Date
 */
function lastDate(date) {
   var ano = date.getFullYear();
   var mes = date.getMonth();
   return new Date(ano, mes, lastDay(date));       
}
//# sourceMappingURL=date.js.map
