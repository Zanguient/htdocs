/**
 * Fechar a visualização do pdf.
 */
function fecharPdf() {

   if ( $('.pdf-fechar').length > 0 ) {

	   $('.pdf-fechar')
		   .click(function() {

			   $('.pdf-ver')
				   .fadeOut();

		   });
   }
}

function printPdf(caminho){
    $('.pdf-ver object')
        .attr('data', caminho)
        .parent()
        .fadeIn(200)
    ;
    /*
    $('.pdf-ver iframe')
        .attr('src','js/PDFJS/web/viewer.html?file=' + caminho)
        .parent()
        .fadeIn(200)
    ;
    //*/
    
}

function getUrlPDf(){
    return  $('.pdf-ver object').attr('data');
}

(function($) {
	$(function() {
		fecharPdf();
	});
})(jQuery);
//# sourceMappingURL=pdf.js.map
