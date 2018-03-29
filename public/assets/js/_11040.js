
(function($) {

    function requestFile() {    
        
        var file_name = $('li').find('.selected').data('file-name');
        
        if ( file_name.trim() === '' ) {
            ShowAlert('Selecione um log!');
        } else {
            return new Promise(function(resolve) {
                execAjax1(
                    'POST', 
                    '/_11040/requestFile',{file_name : file_name},
                    function(data){
                        $('#log').text(data);
                        resolve(true);
                    }
                );
            });
        }
    }
    
    function ancoraClick()
    {
        $('ul li a').click(function(e){
            e.preventDefault();
            
            $('li').find('.selected').removeClass('selected');
            
            $(this).addClass('selected');

            requestFile()
                .then(function(){
                }
            );
        });
    }
    
    function autoLoadLog() {
        $('ul li a')
            .first()
            .click()
        ;
    }
    
    function goToBottom() {
        
        $('button.bottom').click(function(){
            requestFile().then(function(){
                $('#log').scrollTop(
                    9999999999999999999999999
                );
            });
        });
    }
    
    /**
     * Document Ready
     */
	$(function() {
        ancoraClick();
        autoLoadLog();
        goToBottom();
	});
    
})(jQuery);


//# sourceMappingURL=_11040.js.map
