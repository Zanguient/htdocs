(function($){

    var editAtivo;
    var validar = 0;  
    var keyb;
    
    function isMobile()
    {
        var userAgent = navigator.userAgent.toLowerCase();
        if( userAgent.search(/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i)!= -1 )
            return true;
    }

    function abilitarTeclado(){

        if(isMobile()){ 
            
            jQuery('.keyboard-numeric2').keyboard({
                lockInput: true,
                layout: 'custom',
                customLayout: {
                    'default' : [
                        '9 , 00',
                        '6 7 8',
                        '3 4 5',
                        '0 1 2',
                        '{b} {a} {clear}'
                    ]
                },
                restrictInput : true,
                preventPaste : true,
                autoAccept : true
            });
        }
    }

    function verificarAoGravar() {

        $('button.js-gravar').click(function(e) {

            if ( !($('._input_estab').val() > 0 )) {

                e.preventDefault();

                var conteiner =  $('.estab').parent();
                var html_estabs = $(conteiner).html();

                $(conteiner).html('');

                showAlert('Escolha um Estabelecimento.');

                $(conteiner).html(html_estabs);
                $('._input_estab').val(0);

                return false;

            }
            else if ( $('._ccusto_id').val() == '' ) {

                e.preventDefault();
                showAlert('Escolha um Centro de Custo.');
                return false;

            }           

        });

    }

    verificarAoGravar();        
    abilitarTeclado();
})(jQuery);
//# sourceMappingURL=_15050.js.map
