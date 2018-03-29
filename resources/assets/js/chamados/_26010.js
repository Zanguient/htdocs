(function($) {
    
    function showingChamado() {
        if ( pathname.indexOf('_26010/show/') == -1 ) return false;
   
        $('.navbar-toggle').remove();
        $('.sessao'       ).remove();
        $('.navbar-brand' ).attr('href','#');
    } 
    
    function chamado() {
        var wins = new Array();
                
        function showChamado() {
            $('.win-popup').click(function(e){
                e.preventDefault();
                var chamado_id = parseInt($(this).text());

                if ( wins[chamado_id] != null && !wins[chamado_id].closed ) {
                    wins[chamado_id].focus();
                } else {
                    wins[chamado_id] = winPopUp(pathname+'/show/'+chamado_id,chamado_id, {width:890,height:660}); 
                }
            });
        }

        function closeChamado() {
            window.onbeforeunload = function() {

                for (i = 0; i < wins.length; i++) { 
                    if ( wins[i] != null && !wins[i].closed ) wins[i].close();
                }  
            };
        }

        showChamado();
        closeChamado();
    }     
    
    $(function() {
        chamado();
        showingChamado();
    });   
})(jQuery);