   
    var available_printers = null;
    var selected_category = null;
    var default_printer = null;
    var selected_printer = null;
    var default_mode = true;
    var impressoras = [];

    function setup_web_print()
    {
        var OSName="Unknown OS";
        if (navigator.appVersion.indexOf("Win")!=-1) OSName="Windows";
        if (navigator.appVersion.indexOf("Mac")!=-1) OSName="MacOS";
        if (navigator.appVersion.indexOf("X11")!=-1) OSName="UNIX";
        if (navigator.appVersion.indexOf("Linux")!=-1) OSName="Linux";
        
        showLoading("Carregando informações da impressora...");
        default_mode = false;
        selected_printer = null;
        available_printers = null;
        selected_category = null;
        default_printer = null;

        BrowserPrint.getDefaultDevice('printer', function(printer)
        {
            default_printer = printer
            if((printer != null) && (printer.connection != undefined))
            {
                selected_printer = printer;
                var printer_details = $('#printer_details');
                var selected_printer_div = $('#selected_printer');

                selected_printer_div.text("Impressora: " + printer.name);
                hideLoading();
                printer_details.show();
                $('#print_form').show();

            }
            BrowserPrint.getLocalDevices(function(printers)
                {
                    available_printers = printers;
                    var sel = $('.list-printers');
                    var def_print = '';
                    var printers_available = false;
                    sel.innerHTML = "";
                    
                    
                    if (printers != undefined)
                    {
                        for(var i = 0; i < printers.length; i++)
                        {
                                var fabs = impressoras;
                                var cad = false;
                                
                                var opt = document.createElement("option");
                                
                                fabs.forEach(function(fab){
                                    
                                    if(fab[0].toUpperCase() == printers[i].uid.toUpperCase()){
                                        opt.innerHTML = fab[1];
                                        opt.value = printers[i].uid;
                                        sel.append(opt);
                                        printers_available = true;
                                        cad = true;
                                        
                                        if(fab[3] == 1){
                                            $(opt).addClass('defaltprint');
                                            def_print = printers[i].uid;
                                            $(opt).prop('selected','selected');
                                        }
                                        
                                    }
                                });
                                
                                if(cad == false){
                                    opt.innerHTML = printers[i].connection + ": " + printers[i].uid;
                                    opt.value = printers[i].uid;
                                    sel.append(opt);
                                    printers_available = true;
                                    cad = true;
                                }
                        }
                        
                        $('.defaltprint').prop('selected','selected');

                    }

                    if(!printers_available)
                    {
                        showErrorMessage("Não foi possível encontrar uma impressora");
                        hideLoading();
                        $('#print_form').hide();
                        return;
                    }
                    else if(selected_printer == null)
                    {
                        default_mode = false;
                        changePrinter();
                        $('#print_form').show();
                        hideLoading();
                    }
                }, undefined, 'printer');
        }, 
        function(error_response)
        {
            showBrowserPrintNotFound();
        });
    };
    
    function showBrowserPrintNotFound()
    {
        showErrorMessage("Ocorreu um erro ao tentar se conectar a sua impressora Zebra.");
    };
    
    function putCodPrint(codigo)
    {
        
        showLoading("Imprimindo...");
        checkPrinterStatus( function (text){
            if (text == "Ready to Print")
            {
                selected_printer.send(codigo, printComplete, printerError);
            }
            else
            {
                printerError(text);
            }
        });
    };
    
    function checkPrinterStatus(finishedFunction)
    {
        selected_printer.sendThenRead("~HQES", 
                    function(text){
                            var that = this;
                            var statuses = new Array();
                            var ok = false;
                            var is_error = text.charAt(70);
                            var media = text.charAt(88);
                            var head = text.charAt(87);
                            var pause = text.charAt(84);
                            // check each flag that prevents printing
                            if (is_error == '0')
                            {
                                ok = true;
                                statuses.push("Ready to Print");
                            }
                            if (media == '1')
                                statuses.push("Paper out");
                            if (media == '2')
                                statuses.push("Ribbon Out");
                            if (media == '4')
                                statuses.push("Media Door Open");
                            if (media == '8')
                                statuses.push("Cutter Fault");
                            if (head == '1')
                                statuses.push("Printhead Overheating");
                            if (head == '2')
                                statuses.push("Motor Overheating");
                            if (head == '4')
                                statuses.push("Printhead Fault");
                            if (head == '8')
                                statuses.push("Incorrect Printhead");
                            if (pause == '1')
                                statuses.push("Printer Paused");
                            if ((!ok) && (statuses.Count == 0))
                                statuses.push("Error: Unknown Error");
                            finishedFunction(statuses.join());
                }, printerError);
    };
    function hidePrintForm()
    {
        $('#print_form').hide();
    };
    function showPrintForm()
    {
        $('#print_form').show();
    };
    function showLoading(text)
    {
        $('#loading_message').text(text);
        $('#printer_data_loading').show();
        hidePrintForm();
        $('#printer_details').hide();
        $('#printer_select').hide();
    };
    function printComplete()
    {
        hideLoading();
        //alert ("Fim da impressão");
        $('.modal.confirm').prev('.modal-backdrop.confirm').remove();
        $('.modal.confirm').remove();
    }
    function hideLoading()
    {
        $('#printer_data_loading').hide();
        if(default_mode == true)
        {
            showPrintForm();
            $('#printer_details').show();
        }
        else
        {
            $('#printer_select').show();
            showPrintForm();
        }
    };
    function changePrinter()
    {
        default_mode = false;
        selected_printer = null;
        $('#printer_details').hide();
        if(available_printers == null)
        {
            showLoading("Carregando impressora...");
            $('#print_form').hide();
            setTimeout(changePrinter, 200);
            return;
        }
        $('#printer_select').show();
        onPrinterSelected();

    }
    function onPrinterSelected()
    {
        selected_printer = available_printers[$('.list-printers')[0].selectedIndex];
    }
    function showErrorMessage(text)
    {
        $('#main').hide();
        $('#error_div').show();
        $('#error_message').html(text);
    }
    function printerError(text)
    {
        showErrorMessage("Ocorreu um erro durante a impressão. Por favor, tente novamente." + text);
    }
    function trySetupAgain()
    {
        $('#main').show();
        $('#error_div').hide();
        setup_web_print();
        changePrinter();
    }
    
    function dialogPrint(codigo){
        
        addConfirme('Impressão',''
        +'<div id="printer_data_loading" style="display:none"><span id="loading_message">Carregando Detalhes da impressora...</span><br/>'
        +'  <div class="progress" style="width:100%">'
        +'    <div class="progress-bar progress-bar-striped active"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">'
        +'    </div>'
        +'  </div>'
        +'</div>'
        +''
        +'<div id="printer_select" style="display:none">'
        +'  Impressora: <select class="list-printers"></select>'
        +'</div>'
        +''
        +'<div id="error_div" style="padding: 16px;display:none"><div id="error_message"></div>'
        +'<button type="button" class="btn btn-success" onclick="trySetupAgain();">Tentar novamente</button></div>'
        +'<textarea style="display:none" name="dados-post-print" class="dados-post-print" rows="5" value="'+codigo+'" cols="100" required>'+codigo+'</textarea>',
            [
                {desc:'Imprimir',class:'btn-primary btn-confirm-sim',ret:'1',hotkey:'enter',glyphicon:'glyphicon-ok'},
                {desc:'Cancelar',class:'btn-danger btn-confirm-can btn-voltar',ret:'2',hotkey:'esc',glyphicon:'glyphicon-ban-circle'}
            ],
            [
                {ret:1,func:function(){
                    var codigo = $('.dados-post-print').val();
                    putCodPrint(codigo);
                }},
                {ret:2,func:function(){
                    $('.modal.confirm').prev('.modal-backdrop.confirm').remove();
                    $('.modal.confirm').remove();
                }}
            ],'N'    
        );
        
        setTimeout(function(){
            setup_web_print();
            
            $('#printer_select').on('change', onPrinterSelected);
            
            changePrinter();
            
        },200);
        
    }
    /*
    function postprint(codigo){
        dialogPrint(codigo);
    }
    */
(function ($) {
    
    var time;
    var iniciado = 0;
    
    $( ".postprint" ).on( "click", function(e) {
    
        var codigo = $('.codigo').val();
        postprint(codigo);
        
    });
    
    $(document).ready(function(){
        /*
            function success(data) {
                if(data) {
                    impressoras = data;
                } 
            }

            function erro(xhr){
                showErro(xhr);  
            }

            var dados = {
                'codigo'  : ''
            };

            execAjax1('POST','/getPrints',dados,success,erro);
        //*/
    });
    
})(jQuery);