/* 
 * Scripts relacionados à ajax.
 */
var win_login;
var requestRunning = 0;
var ajax_tipo = 'auto';

/**
 * ExcellentExport.
 */
var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
var fromCharCode = String.fromCharCode;
var INVALID_CHARACTER_ERR = ( function() {
        // fabricate a suitable error object
        try {
            document.createElement('$');
        } catch (error) {
            return error;
        }
    }());

// encoder
window.btoa || (window.btoa = function(string) {
    var a, b, b1, b2, b3, b4, c, i = 0, len = string.length, max = Math.max, result = '';

    while (i < len) {
        a = string.charCodeAt(i++) || 0;
        b = string.charCodeAt(i++) || 0;
        c = string.charCodeAt(i++) || 0;

        if (max(a, b, c) > 0xFF) {
            throw INVALID_CHARACTER_ERR;
        }

        b1 = (a >> 2) & 0x3F;
        b2 = ((a & 0x3) << 4) | ((b >> 4) & 0xF);
        b3 = ((b & 0xF) << 2) | ((c >> 6) & 0x3);
        b4 = c & 0x3F;

        if (!b) {
            b3 = b4 = 64;
        } else if (!c) {
            b4 = 64;
        }
        result += characters.charAt(b1) + characters.charAt(b2) + characters.charAt(b3) + characters.charAt(b4);
    }
    return result;
});

// decoder
window.atob || (window.atob = function(string) {
    string = string.replace(/=+$/, '');
    var a, b, b1, b2, b3, b4, c, i = 0, len = string.length, chars = [];

    if (len % 4 === 1)
        throw INVALID_CHARACTER_ERR;

    while (i < len) {
        b1 = characters.indexOf(string.charAt(i++));
        b2 = characters.indexOf(string.charAt(i++));
        b3 = characters.indexOf(string.charAt(i++));
        b4 = characters.indexOf(string.charAt(i++));

        a = ((b1 & 0x3F) << 2) | ((b2 >> 4) & 0x3);
        b = ((b2 & 0xF) << 4) | ((b3 >> 2) & 0xF);
        c = ((b3 & 0x3) << 6) | (b4 & 0x3F);

        chars.push(fromCharCode(a));
        b && chars.push(fromCharCode(b));
        c && chars.push(fromCharCode(c));
    }
    return chars.join('');
});


ExcellentExport = (function() {
    var version = "1.3";
    var uri = {excel: 'data:application/vnd.ms-excel;base64,', csv: 'data:application/csv;base64,'};
    var template = {excel: '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'};
    var base64 = function(s) {
        return window.btoa(unescape(encodeURIComponent(s)));
    };
    var format = function(s, c) {
        return s.replace(/{(\w+)}/g, function(m, p) {
            return c[p];
        });
    };

    var get = function(element) {
        if (!element.nodeType) {
            return document.getElementById(element);
        }
        return element;
    };

    var fixCSVField = function(value) {
        var fixedValue = value;
        var addQuotes = (value.indexOf(',') !== -1) || (value.indexOf('\r') !== -1) || (value.indexOf('\n') !== -1);
        var replaceDoubleQuotes = (value.indexOf('"') !== -1);

        if (replaceDoubleQuotes) {
            fixedValue = fixedValue.replace(/"/g, '""');
        }
        if (addQuotes || replaceDoubleQuotes) {
            fixedValue = '"' + fixedValue + '"';
        }
        return fixedValue;
    };

    var tableToCSV = function(table) {
        var data = "";
        for (var i = 0, row; row = table.rows[i]; i++) {
            for (var j = 0, col; col = row.cells[j]; j++) {
                var valor = fixCSVField(col.innerHTML);

                valor = (valor + '').replace(/("|,|\n)/g,' ')
                valor = '"'+valor+'"'

                data = data + (j ? ',' : '') + valor;
            }
            data = data + "\r\n";
        }
        return data;
    };

    var ee = {
        /** @expose */
        excel: function(anchor, table, name) {
            table = get(table);
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML};
            var hrefvalue = uri.excel + base64(format(template.excel, ctx));
            anchor.href = hrefvalue;
            // Return true to allow the link to work
            return hrefvalue;
        },

        excel2: function(anchor, table, name) {
            table = get(table);
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML};
            var hrefvalue = uri.excel + base64(format(template.excel, ctx));
            anchor.href = hrefvalue;
            // Return true to allow the link to work
            return hrefvalue;
        },

        excel3: function(anchor, html, name) {
            
            var ctx = {worksheet: name || 'Worksheet', table: html};
            var hrefvalue = uri.excel + base64(format(template.excel, ctx));
            anchor.href = hrefvalue;
            // Return true to allow the link to work
            return hrefvalue;
        },

        /** @expose */
        csv: function(anchor, table) {
            table = get(table);
            var csvData = tableToCSV(table);
            var hrefvalue = uri.csv + base64(csvData);
            anchor.href = hrefvalue;
            return csvData;
        }
    };

    return ee;
}());

function printPDF(nome, elemento, titulo, filtro, user, versao, pagina, css){

    //var conteudo = document.getElementById('sua_div').innerHTML,
    //var conteudo = $(elemento).html();
    //tela_impressao = window.open('about:blank');

    //tela_impressao.document.write(conteudo);
    //tela_impressao.window.print();
    //tela_impressao.window.close();

    //var conteudo = document.getElementById('sua_div').innerHTML,
    //var conteudo = $(elemento).html();
    //tela_impressao = window.open('about:blank');

    //tela_impressao.document.write(conteudo);
    //tela_impressao.window.print();
    //tela_impressao.window.close();

        var tipo_print = 0;    

        var top = '<tr><th colspan="100">'+
                    '<section id="top" style="border: 1px solid;">'+
                        '<div class="center">'+
                            '<label style="font-size: 12px; font-weight: 600;">GESTÃO CORPORATIVA - DELFA</label>'+
                            '<label style="font-size: 12px; font-weight: 600;">'+titulo+'</label>'+
                            '<label style="font-size: 12px; font-weight: 600;">Filtro: '+filtro+'</label>'+
                        '</div>'+
                        '<div class="right">'+
                            '<label style="font-size: 12px; font-weight: 600;">'+moment().format('L')+' '+moment().format('LT')+'</label>'+
                            '<label style="font-size: 12px;" class="pagina">'+user+'</label>'+
                            '<label style="font-size: 12px;" class="pagina">Vr:'+versao+'</label>'+
                        '</div>'+
                    '</section> '+      
                '</th></tr>';

        var fim1 = '<tfoot><tr><th colspan="100"><div style="background-color:black;width:100%;height:2px;"></div></th></tr></tfoot>';
        var fim2 = ''; //<div style="border: 2px solid; border-style: dotted; width:100%;height:2px;"></div><br><div style="text-align:center">'+user+' / '+moment().format('L')+' '+moment().format('LT')+'</div>';

        var conteudo = $('#'+elemento).html();
        var win = window.open();  
        win.document.write('<page size="A4" style="width: 710px;"">' + conteudo + fim2 + '</page>'); 

        $(win.document.body).css('margin', 'unset'); 

        if(pagina == 1){
            var head = $(win.document.head).html() + '<link rel="stylesheet" href="'+urlhost+'/build/assets/images/page_l.css">';
        }else{
            var head = $(win.document.head).html() + '<link rel="stylesheet" href="'+urlhost+'/build/assets/images/page.css">';
        }

        head = head + $(win.document.head).html() + ' <script src="'+urlhost+'/build/assets/images/print.js"></script>';

        if(css != ''){
            head =  '<link rel="stylesheet" href="'+css+'">'    
        }

        $(win.document.head).html(head);

        var obj = $(win.document).find('table')[0];
        var tmp = $(obj).html();
        
        $(obj).html(fim1 + tmp);

        obj = $(win.document).find('thead')[0];
        tmp = $(obj).html();

        $(obj).html(top + tmp);

        //setTimeout(function(){ 
        //  win.print();  
        //    win.close();
        //}, 500);

        var scaleBy = 2;
        var w = 1000;
        var h = 1000;
        var div = win.document.body;
        var canvas = document.createElement('canvas');
        canvas.width = w * scaleBy;
        canvas.height = h * scaleBy;
        canvas.style.width = w + 'px';
        canvas.style.height = h + 'px';
        var context = canvas.getContext('2d');
        context.scale(scaleBy, scaleBy);

        html2canvas(div,{
            canvas:canvas,
            onrendered:function(canvas){

               var fator =  canvas.width / 270;

               var img = canvas.toDataURL("image/png");
               var doc = new jsPDF();
               doc.addImage(img,'JPEG',10,10, canvas.width / fator, canvas.height / fator);
               doc.save(nome+'.pdf');
               win.close();
            }
       });
    
}

function printHtml(elemento, titulo, filtro, user, versao, pagina, css){

    //var conteudo = document.getElementById('sua_div').innerHTML,
    //var conteudo = $(elemento).html();
    //tela_impressao = window.open('about:blank');

    //tela_impressao.document.write(conteudo);
    //tela_impressao.window.print();
    //tela_impressao.window.close();

        var tipo_print = 0;    

        var top = '<tr><th colspan="100">'+
                    '<section id="top" style="border: 1px solid;">'+
                        '<div class="center">'+
                            '<label style="font-size: 12px; font-weight: 600;">GESTÃO CORPORATIVA - DELFA</label>'+
                            '<label style="font-size: 12px; font-weight: 600;">'+titulo+'</label>'+
                            '<label style="font-size: 12px; font-weight: 600;">Filtro: '+filtro+'</label>'+
                        '</div>'+
                        '<div class="right">'+
                            '<label style="font-size: 12px; font-weight: 600;">'+moment().format('L')+' '+moment().format('LT')+'</label>'+
                            '<label style="font-size: 12px;" class="pagina">'+user+'</label>'+
                            '<label style="font-size: 12px;" class="pagina">Vr:'+versao+'</label>'+
                        '</div>'+
                    '</section> '+      
                '</th></tr>';

        var fim1 = '<tfoot><tr><th colspan="100"><div style="background-color:black;width:100%;height:2px;"></div></th></tr></tfoot>';
        var fim2 = ''; //<div style="border: 2px solid; border-style: dotted; width:100%;height:2px;"></div><br><div style="text-align:center">'+user+' / '+moment().format('L')+' '+moment().format('LT')+'</div>';

        if(tipo_print == 1){

            var $print = $('#'+elemento)
            .clone()
            .prepend(top)
            .addClass('print')
            .addClass('printable')
            .prependTo('body');

            $('.navbar').css('display', 'none'); 
            $('.container-fluid').css('display', 'none');

            // Stop JS execution
            window.print();

            $('.navbar').css('display', 'block'); 
            $('.container-fluid').css('display', 'block'); 

            // Remove div once printed
            $print.remove();
        }else{

            var conteudo = $('#'+elemento).html();
            var win = window.open();  
            win.document.write('<page size="A4">' + top + conteudo + fim1 + '</page>');  

            if(pagina == 1){
                var head = $(win.document.head).html() + '<link rel="stylesheet" href="'+urlhost+'/build/assets/images/page_l.css">';
            }else{
                var head = $(win.document.head).html() + '<link rel="stylesheet" href="'+urlhost+'/build/assets/images/page.css">';
            }

            if(css != ''){
                head += '<link rel="stylesheet" href="'+css+'">';
            }

            head = head + $(win.document.head).html() + ' <script src="'+urlhost+'/build/assets/images/print.js"></script>';

            $(win.document.head).html(head);

            // var obj = $(win.document).find('table')[0];
            // var tmp = $(obj).html();
            
            // $(obj).html(fim1 + tmp);

            // obj = $(win.document).find('thead')[0];
            // tmp = $(obj).html();

            // $(obj).html(top + tmp);

            setTimeout(function(){ 
                win.print();  
                win.close();
            }, 500);

        }
}

function exportTableToCsv(filename, table) {
    
    var str = ExcellentExport.csv(filename,table);

    //var link = window.document.createElement("a");
    //link.setAttribute("href", "data:text/csv;charset=utf-8,%EF%BB%BF" + encodeURI(str));
    //link.setAttribute("download", filename);
    //link.click();

    var link = window.document.createElement("a");
    link.setAttribute("href", "data:attachment/file;charset=utf-8,%EF%BB%BF" + encodeURI(str));
    link.setAttribute("download", filename);
    link.click();
}

function exportTableToXls(filename, table) {
    
    var str = ExcellentExport.excel(filename,table);

    //var link = window.document.createElement("a");
    //link.setAttribute("href", "data:application/vnd.ms-excel;base64" + encodeURI(str));
    //link.setAttribute("download", filename);
    //link.click();

    var link = window.document.createElement("a");
    link.setAttribute("href", "data:attachment/file;base64" + encodeURI(str));
    link.setAttribute("download", filename);
    link.click();
}

function exportToXls(filename, rows, coll) {
    /*
        coll = [
            {COLUNA: 'ID', DESCRICAO:'ID', TIPO: 'INTEIRO'},
            {COLUNA: 'DESCRICAO', DESCRICAO:'DESCRIÇÃO', TIPO: 'STRING'},
            {COLUNA: 'DATA', DESCRICAO:'DATA', TIPO: 'DATA'},
        ];

        TIPOS:INTEIRO, STRING, DATA, NUMERICO

    */

    exportTo(filename, rows, coll, 1);
}

function exportToCsv(filename, rows, coll) {
    /*
        coll = [
            {COLUNA: 'ID', DESCRICAO:'ID', TIPO: 'INTEIRO'},
            {COLUNA: 'DESCRICAO', DESCRICAO:'DESCRIÇÃO', TIPO: 'STRING'},
            {COLUNA: 'DATA', DESCRICAO:'DATA', TIPO: 'DATA'},
        ];

        TIPOS:INTEIRO, STRING, DATA, NUMERICO

    */

    exportTo(filename, rows, coll, 2);
}

function exportTo(filename, rows, coll, tipo) {

    /*
        coll = [
            {COLUNA: 'ID', DESCRICAO:'ID', TIPO: 'INTEIRO'},
            {COLUNA: 'DESCRICAO', DESCRICAO:'DESCRIÇÃO', TIPO: 'STRING'},
            {COLUNA: 'DATA', DESCRICAO:'DATA', TIPO: 'DATA'},
        ];

        TIPOS:INTEIRO, STRING, DATA, NUMERICO, COR

    */

    var tag_abrir  = '';
    var tag_fechar = '';
    var lin_abrir  = '';
    var lin_fechar = '';
    var col_abrir  = '';
    var col_fechar = '';

    if(tipo == 1){
        tag_abrir  = '<tr>';
        tag_fechar = '</tr>';
        lin_abrir  = '<td style="background: #337ab7;color: white;">';
        lin_fechar = '</td>';
        col_abrir  = '<td>';
        col_fechar = '</td>';
    }else{
        tag_abrir  = '';
        tag_fechar = '';
        lin_abrir  = '"';
        lin_fechar = '",';
        col_abrir  = '"';
        col_fechar = '",';
    }

    var formatar = 0;

    if(coll){
        if(coll.length > 0){
            formatar = 1;
        }   
    }

    var csvFile = '';

    if(Object.keys(rows).length > 0){
        var l = tag_abrir;

        if(formatar == 1){
            angular.forEach(coll, function(coluna, key) {

                angular.forEach(rows[0], function(valor, campo) {
                    if(coluna.COLUNA == campo){
                        campo = coluna.DESCRICAO;
                        l += lin_abrir + campo + lin_fechar; 
                    }
                });
                
            });
        }else{
            angular.forEach(rows[0], function(valor, campo) {
                l += lin_abrir + campo + lin_fechar;
            });   
        }

        l += tag_fechar;

        csvFile += l;

        if(tipo == 2){
            csvFile = (csvFile + '').substring(0 , (csvFile + '').length -1);
        }
        csvFile += '\n';
    }


    angular.forEach(rows, function(linha, key) {

        var l = tag_abrir;
        
        if(formatar == 1){
            angular.forEach(coll, function(coluna, key) {
            //angular.forEach(linha, function(valor, campo) {

                var cor = '';

                //angular.forEach(coll, function(coluna, key) {
                angular.forEach(linha, function(valor, campo) {
                    if(coluna.COLUNA == campo){

                        if(coluna.TIPO == 'INTEIRO'){
                            //valor = valor.replace(".", "");
                            //valor = valor.replace(".", "");
                            //valor = valor.replace(",", ".");

                            valor = parseInt(valor);
                        }

                        if(coluna.TIPO == 'NUMERICO'){
                            //valor = valor.replace(".", "");
                            //valor = valor.replace(".", "");
                            //valor = valor.replace(",", ".");

                            valor = Number(valor);
                            valor = (valor+'').replace(".", ",");
                        }

                        if(coluna.TIPO == 'DATA'){
                            valor = valor;
                        }

                        if(coluna.TIPO == 'COR'){
                            cor = 'style="color: '+valor+';"';
                        }

                        if(coluna.TIPO == 'STRING'){
                            valor = (''+ valor + '');
                        }

                        if(tipo == 2){
                            valor = (valor + '').replace(/("|,|\n)/g,' ');
                        }

                        l += col_abrir + valor + col_fechar; 
                    }
                });

            });
        }else{
            angular.forEach(linha, function(valor, campo) {

                valor = (valor + '').replace(/("|,|\n)/g,' ');

                l += col_abrir + valor + col_fechar; 

            });
        }

        l += tag_fechar;

        csvFile += l;

        if(tipo == 2){
            csvFile = (csvFile + '').substring(0 , (csvFile + '').length -1);
        }

        csvFile += '\n';
    });

    html = csvFile;

    

    if(tipo == 1){
        var str = ExcellentExport.excel3(filename,html);
        var link = window.document.createElement("a");
        link.setAttribute("href", "data:application/vnd.ms-excel;base64" + encodeURI(str));
        link.setAttribute("download", filename);
        link.click();
    }else{
        var link = window.document.createElement("a");
        link.setAttribute("href", "data:text/csv;charset=utf-8,%EF%BB%BF" + encodeURI(csvFile));
        link.setAttribute("download", filename);
        link.click();
    }
}

function Errojs(mensagem) {
  this.mensagem = mensagem;
  this.nome = "Erro";
}

Errojs.prototype.toString = function() {
  return this.name + ': "' + this.message + '"';
};

/** 
 * Definir o crsf token para o ajax
 */
function ajaxSetup() {
	var token      = $('meta[name="csrf_token"]').attr('content');
        
	$.ajaxSetup({
		headers: {
            'X-CSRF-TOKEN': token
        }
	});
    
    $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
        

        var socket_con = $('[name="_socket_token"]').val();
        if ( socket_con != undefined && socket_con != "" ) {
            xhr.setRequestHeader('SOCKET-TOKEN', socket_con);
        }    
        
        var token = $('meta[name="csrf_token"]').attr('content');
        if (token) {
            return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
        }
    });
}

/**
 * Setar valor do progressbar.
 * @param {event} e
 */
function progressPagina(e) {
    
    var percentual = 100;
    var socket = parseFloat($('._socket_token').val());
    
    if (socket > 0 && ajax_tipo == 'manual') {
        $('.progress-bar').css('transition','10s');
        percentual = 30;
    }
        
    
	if(e.lengthComputable) {
		$('.carregando-pagina .progress-bar')
			.attr({'aria-valuenow': e.loaded,'aria-valuemax': e.total})
			.css('width', (e.loaded*percentual) / e.total+'%');
    
	}
}

/**
 * Setar valor do progressbar.
 * @param {event} e
 */
function progressPagina2(e) {    
    
    var percentual = 100;
    var socket = parseFloat($('._socket_token').val());
    
    if (socket > 0 && ajax_tipo == 'manual') {
        $('.progress-bar').css('transition','10s');
        percentual = 30;
    }
    
	if(e.lengthComputable) {
		$('.carregando-pagina2 .progress-bar')
			.attr({'aria-valuenow': e.loaded,'aria-valuemax': e.total})
			.css('width', (e.loaded*percentual) / e.total+'%');
	}
}
    
/**
 * Executa ação ajax, com progressbar.
 * 
 * @param type Tipo de consulta. Ex.: POST, GET
 * @param url_action Rota da consulta
 * @param data Dados a ser enviado
 * @param funcSuccess Função a ser executada no sucesso | Default = null
 * @param funcErro Função a ser executada no erro | Default = null
 * @param funcComplete Função a ser executada no complete | Default = null
 * @param progress
 * @param async Assincrono | Default = true
 * @param cache Default = true
 * @param contentType Default = 'application/x-www-form-urlencoded; charset=UTF-8'
 * @param processData Default = true
 */
function execAjax1(type,url_action,data,funcSuccess,funcErro,funcComplete,progress,async,cache,contentType,processData){
    
    requestRunning = 1;
    

    if(typeof(progress) === "undefined"){
        progress = true;  
    }
    
    var upload = true;
    
    var socket = parseFloat($('._socket_token').val());
    
    if (socket > 0 && progress == 'manual') {
        $('.progress-bar').css('transition','0s');
        $('.progress-bar').css('background-color','rgb(0, 150, 136)');
    }
    
    if ( progress == 'manual' ) {
        progress = true;
        ajax_tipo = 'manual';
//        upload   = false;
    }

    
    
    async		= async			|| true;
    cache		= (cache		!== null && typeof(cache)		!== 'undefined') ? cache		: true;
    contentType = (contentType	!== null && typeof(contentType)	!== 'undefined') ? contentType	: 'application/x-www-form-urlencoded; charset=UTF-8';
    
        return $.ajax({
            async		: async,
            type		: type,
            url			: url_action,
            data		: data,
            cache		: cache,
            contentType	: contentType,
            processData	: processData,
            xhr			: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload && upload) {
                    if(progress){
                        myXhr.upload.addEventListener('progress', progressPagina, false);
                    }else{
                        myXhr.upload.addEventListener('progress', progressPagina2, false);
                    }
                }

                return myXhr;
            },
            beforeSend	: function () {
                if(progress){
                    $('.carregando-pagina').fadeIn(200);
                }else{
                    $('.carregando-pagina2').fadeIn(200);
                }
            },
            success		: function (data) {
                
                funcSuccess ? funcSuccess(data) : null;                                         
                bootstrapInit();

                var  msg = data.SUCCESS_MSG || data.success_msg;
                if ( msg != undefined ) {
                    showSuccess(msg);
                }
            },
            error		: function (xhr) {
                
                if (xhr.statusText !='abort') {
                    showErro(xhr);
                    funcErro ? funcErro(xhr) : null;
                }

                //sessão expirada
                if (xhr.status === 401) {

                    if ( win_login != null && !win_login.closed ) {

                        setTimeout(function() {
                            win_login.close();
                            win_login = winPopUp('/', 'login-modal', {width:400,height:560});
                        }, 500);

                    } else {

                        setTimeout(function() {
                            win_login = winPopUp('/', 'login-modal', {width:400,height:560});
                        }, 500);

                    }
                }

            },
            complete	: function () {

                    //progress 1
                    $('.carregando-pagina').fadeOut(200);

                    setTimeout(function() {
                        $('.carregando-pagina .progress .progress-bar')
                            .attr({'aria-valuenow': 0,'aria-valuemax': 0})
                            .css('width', 0);
                    }, 300);

                    //progress 1
                    $('.carregando-pagina2').fadeOut(200);

                    setTimeout(function() {
                        $('.carregando-pagina2 .progress .progress-bar')
                            .attr({'aria-valuenow': 0,'aria-valuemax': 0})
                            .css('width', 0);
                    }, 300);


                funcComplete ? funcComplete() : null;

                $(window).trigger('resize');  
                
                requestRunning = 0;
                ajax_tipo      = 'auto';
            }
        });
}

/**
 * Executa ação ajax, com ícone refresh.
 * 
 * @param type Tipo de consulta. Ex.: POST, GET
 * @param url_action Rota da consulta
 * @param data Dados a ser enviado
 * @param funcSuccess Função a ser executada no sucesso | Default = null
 * @param funcErro Função a ser executada no erro | Default = null
 * @param btn_filtro Botão que contém o ícone refresh
 * @param cache Default = true
 * @param contentType Default = 'application/x-www-form-urlencoded; charset=UTF-8'
 * @param processData Default = true
 */
function execAjax2(type,url_action,data,funcSuccess,funcErro,btn_filtro,cache,contentType,processData){
    
    requestRunning = 1;
    
	cache		= (cache		!== null && typeof(cache)		!== 'undefined') ? cache		: true;
	contentType = (contentType	!== null && typeof(contentType)	!== 'undefined') ? contentType	: 'application/x-www-form-urlencoded; charset=UTF-8';
	processData = (contentType	!== null && typeof(processData)	!== 'undefined') ? processData	: true;
        
        $.ajax({
            type		: type,
            url			: url_action,
            data		: data,
            cache		: cache,
            contentType	: contentType,
            processData	: processData,
            beforeSend	: function () {
                if (btn_filtro !== false){
                $(btn_filtro)
                    .children()
                    .addClass('fa-circle-o-notch');
                }
            },
            success		: function (data) {

                funcSuccess ? funcSuccess(data) : null;
                bootstrapInit();

            },
            error		: function (xhr) {

                showErro(xhr);
                funcErro ? funcErro(xhr) : null;

                //sessão expirada
                if (xhr.status === 401) {

                    if ( win_login != null && !win_login.closed ) {

                        setTimeout(function() {
                            win_login.close();
                            win_login = winPopUp('/', 'login-modal', {width:400,height:560});
                        }, 500);

                    } else {

                        setTimeout(function() {
                            win_login = winPopUp('/', 'login-modal', {width:400,height:560});
                        }, 500);

                    }
                }
            },
            complete	: function() {

                if (btn_filtro !== false) {

                    $(btn_filtro)
                        .children()
                        .removeClass('fa-circle-o-notch');
                }
                
                requestRunning = 0;

            }
        });
}


/**
 * Executa ação ajax, com progressbar via socket.
 * 
 * @param type Tipo de consulta. Ex.: POST, GET
 * @param url_action Rota da consulta
 * @param data Dados a ser enviado
 * @param funcSuccess Função a ser executada no sucesso | Default = null
 * @param funcErro Função a ser executada no erro | Default = null
 * @param funcComplete Função a ser executada no complete | Default = null
 * @param progress
 * @param async Assincrono | Default = true
 * @param cache Default = true
 * @param contentType Default = 'application/x-www-form-urlencoded; charset=UTF-8'
 * @param processData Default = true
 */
function execAjax3(type,url_action,data,funcSuccess,funcErro,funcComplete,progress,async,cache,contentType,processData){
    
    var _socket_token = $('._socket_token').val();

    requestRunning = 1;
    if(typeof(progress) === "undefined"){
        progress = true;  
    }

    data['_socket_token'] = _socket_token;

    async       = async         || true;
    cache       = (cache        !== null && typeof(cache)       !== 'undefined') ? cache        : true;
    contentType = (contentType  !== null && typeof(contentType) !== 'undefined') ? contentType  : 'application/x-www-form-urlencoded; charset=UTF-8';
    processData = (contentType  !== null && typeof(processData) !== 'undefined') ? processData  : true;

        return $.ajax({
            async       : async,
            type        : type,
            url         : url_action,
            data        : data,
            cache       : cache,
            contentType : contentType,
            processData : processData,
            xhr         : function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    if(progress){
                        //myXhr.upload.addEventListener('progress', progressPagina, false);
                    }else{
                        //myXhr.upload.addEventListener('progress', progressPagina2, false);
                    }
                }

                return myXhr;
            },
            beforeSend  : function () {
                if(progress){
                    $('.carregando-pagina').fadeIn(200);
                }else{
                    $('.carregando-pagina2').fadeIn(200);
                }
            },
            success     : function (data) {

                funcSuccess ? funcSuccess(data) : null;                                         
                bootstrapInit();

            },
            error       : function (xhr) {

                showErro(xhr);
                funcErro ? funcErro(xhr) : null;

                //sessão expirada
                if (xhr.status === 401) {

                    if ( win_login != null && !win_login.closed ) {

                        setTimeout(function() {
                            win_login.close();
                            win_login = winPopUp('/', 'login-modal', {width:400,height:560});
                        }, 500);

                    } else {

                        setTimeout(function() {
                            win_login = winPopUp('/', 'login-modal', {width:400,height:560});
                        }, 500);

                    }
                }

            },
            complete    : function () {

                    //progress 1
                    $('.carregando-pagina').fadeOut(200);

                    setTimeout(function() {
                        $('.carregando-pagina .progress .progress-bar')
                            .attr({'aria-valuenow': 0,'aria-valuemax': 0})
                            .css('width', 0);
                    }, 300);

                    //progress 1
                    $('.carregando-pagina2').fadeOut(200);

                    setTimeout(function() {
                        $('.carregando-pagina2 .progress .progress-bar')
                            .attr({'aria-valuenow': 0,'aria-valuemax': 0})
                            .css('width', 0);
                    }, 300);


                funcComplete ? funcComplete() : null;

                $(window).trigger('resize');  
                
                requestRunning = 0;
            }
        });
}

function requestPost(param)
{     
    return new Promise(function(resolve, reject) {
        execAjax1('POST',param.rota_ajax,param.dados,
        function(resposta) {
            resolve(resposta);
        },
        function(xhr){
            reject(xhr);
        });      
    });
}

var loading_element = false;

function loading(element_loading) {
    
    if ( typeof element_loading == 'string' && element_loading == 'hide' ) {
        $(loading_element).fadeIn(2000);
        $('.bubblingG').fadeOut(2000,function(){
            $(this).remove();
        });
    } else {
    
        var loading =  '<div class="bubblingG" style="display: none; position: fixed; left: calc(50% - 95px); top: calc(50% - 60px); z-index: 99;"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>';
        loading_element = element_loading;
        $(loading_element).parent().append(loading);
        $(loading_element).fadeOut();
        $('.bubblingG').fadeIn(2000);
    }
}

(function($) {
	
	$(function() {
		
		ajaxSetup();
        
});
    
})(jQuery);