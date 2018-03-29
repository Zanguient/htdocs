/**
 * _11100 - Qlik Sense
 */
(function($) {
    
    $(document).
        on('click','.abrir-qlik',function(){
            var arq = $(this).data('arquivo');

            var paran = {
                hub     : $(this).data('hub'),
                pastas  : $(this).data('pastas'),
                dados   : $(this).data('dados'),
                projeto : $(this).data('projeto'),
                usuario : $(this).data('usuario'),
            }

            var proj = $('.projeto');
            var url = proj.data('url');

            var link = $.param(paran);


            var src = url + arq + '?' + link;
            
            proj.attr('src', src);
            
            $('#modal-projeto').modal('show');
        })
    ;
    
//    $('#iframe-projeto').each(function(){
//        var that = $(this);
//        function injectCSS(){
//            $iframe.contents().find('head').append(
//                $('<link/>', { rel: 'stylesheet', href: that.data('style-css'), type: 'text/css' })
//            );
//        }
//
//        var $iframe = $(this);
//        $iframe.on('load', injectCSS);
//        injectCSS();
//    });
    
	$(function() {
		
	});
	
})(jQuery);

