
(function($) {
    
    function gravar() {
        
        $('#gravar').click(function(){
            execAjax1('POST','/_11000/gravarEnv',{
                texto : $('#env').val()
            });
        });
        
    }
    
	$(function() {       
        gravar();
	});
    
})(jQuery);
