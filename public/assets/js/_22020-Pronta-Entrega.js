/* global table_default */

/**
 * Script com funções do obj _22020. (Para utilização de sobras e materias já pronto, servindo com pronta entrega)
 */
    
    //variaveis de validação
    var _tid = 0;
    var _btn;
    var vp = 0;
    var vt = 0;
    var vs = 0;
    var cb;
    var REMESSA_ID = 0;
    var REMESSA_TALAO_ID = 0;
    
    function gravar_prod(){
        var input		= '';
        var url        = '/_22020/alterarQtdTalaoDetalhe';
        var retorno	= '';

        var sbr = (parseFloat(vs)).toFixed(4);
        var qtd = (vt).toFixed(4);
        
        var tid = _tid;
        var btn = _btn;
        
        if ( $(btn).parent('td').hasClass('qtd') ) {
            input	= 'input.qtd';
            retorno = 'QUANTIDADE';
        }
        else {
            input	= 'input.qtd-alternativa';
            retorno = 'QUANTIDADE_ALTERNATIVA';
        }

        execAjax1(
           'POST',
           url, 
           { 
               retorno			: retorno,
               qtd				: qtd,
               sbr				: sbr,
               talao_detalhe_id	: tid,
               REMESSA_ID       : REMESSA_ID,
               REMESSA_TALAO_ID : REMESSA_TALAO_ID
           },
           function(data) {
               
                if(qtd > 0){
                    validarRet(data,btn);
                }
               
               $(btn)
                   .siblings('span.valor')
                   .empty()
                   .text( formataPadraoBr(qtd) )
                   .show()
               ;
               
               $(cb).empty().text(sbr);

				var btn_parent	= $(btn).parent();
				var th_qtd = '';
				
				if (input === 'input.qtd') {
					
					th_qtd					= 'th.qtd';
					var input_qtd			= $(btn_parent).siblings('._quantidade');
					
					$(input_qtd)
						.val(qtd)
					;
					
				}
				else {
					
					th_qtd					= 'th.qtd-alternativa';
					var input_qtd			= $(btn_parent).siblings('._quantidade-alternativa');
					
					$(input_qtd)
						.val(qtd)
					;
					
				}
				
				$('#detalhe')
					.find(th_qtd)
					.removeClass('editando')
				;
				
				$(btn)
					.parent()
					.removeClass('editando')
				;
				

               $(btn)
                   .siblings(input)
                   .attr('max', qtd)
                   .hide()
               ;

               $(btn)
                   .hide()
               ;

               $(btn)
                   .siblings('button.qtd-cancelar')
                   .hide()
               ;

               $(btn)
                   .siblings('button.qtd-editar')
                   .show()
               ;
               
               showSuccess('Quantidade alterada com sucesso.');
               
               if(qtd == 0){
                   $('.btn-filtrar').click();
               }
               
           }

       );
    }
    
    function gravar_SobraMaterial(consumo_id,sobra){
        var input   = '';
        var url     = '/_22020/alterarQtdSobraMaterial';
        var retorno	= '';

        execAjax1(
           'POST',
           url, 
           { 
               consumo_id : consumo_id,
               sobra	  : sobra
           },
           function(data) {
               
               showSuccess('Quantidade alterada com sucesso.');
               $('.btn-filtrar').click();

           },
           function(data){
               $('.btn-filtrar').click();
           }

       );
    }
      
    function validar_prod(v1,v2,v3,v4,tm,tn,qtd_unim,tid,btn){
        var frase = '';
        _tid = tid;
        _btn = btn;
        
        if(v3 > 0 ){
            frase = '<strong>maior</strong> do que o';
        }else{
            frase = '<strong>menor</strong> do que o';
        }
        
        addConfirme('<h4>Registro de Produção</h4>',
                 ' Foi produzido <input type="number" name="quantidade" class="qtd qtd-prod-conf"'+
                 ' step="1" value="'+(v1).toFixed(2)+'" style="inline-block; width: 95px;" projetado="'+(v2).toFixed(2)+'" data-tm="'+tm+'" data-tn="'+tn+'" data-qtd-aproveitamento="'+v4+'"> '+
                 ' , sendo <span class="frase-validar">'+frase+'</span> projetado ou tolerância,<br>que resulta em '+
                 ' <strong class="sobras">'+v3+' '+qtd_unim+'</strong> '+
                 ' de <strong>SOBRA</strong>, deseja registrar esses valores?',[obtn_sim,obtn_cancelar],
                     [
                         {ret:1,func:function(){
                              gravar_prod();
                         }},
                         {ret:2,func:function(){

                         }}
                     ]     
                 );
         
                 $( ".qtd-prod-conf" ).trigger( "change" );
                 $('.qtd-prod-conf').select();
    }
    
    var ret = 0;
    var sobra_tipo = 'M';
    
    function validarRet(data,btn){
        
        ret = 0;
        
        var cont = 0;
        $.each(data, function(key, value){
                           
            $('._consumo-id').each(function( index ) {

                var consumo_id  =  $( this ).val();
//                var prod  =  $( this ).val();

                if(consumo_id == data[key]['CONSUMO_ID']){
                    var obj			= $( this ).parent().find('.qtd-total'); // td quantidade consumo
                    var prd			= $( this ).parent().find('.produto').attr('title'); //descrição do produto
                    var sobra		= $( this ).parent().find('._sobra-material');
//                    var consumo_id	= $( this ).parent().find('._consumo-id').val();                     
                    var txt			= $(obj).text();
                    
                    if(txt.length > 0){
                        arryTxt = txt.split(' ');
                        
                        arryPrd = prd.split(' - ');

                        if(arryTxt.length > 1){
                            $(obj).text(data[key]['QUANTIDADE_PROJETADA']+' '+ arryTxt[1]);
                        }else{
                            $(obj).text(data[key]['QUANTIDADE_PROJETADA']);   
                        }
                        
                        var p = prd;
                        if(arryPrd.length > 1){
                            p = arryPrd[1]; 
                        }
                       
                        if(data[key]['QUANTIDADE_SOBRA'] > 0){
                            
                            //if (sobra_tipo == 'M'){
                                
                                addConfirme('Registro de Produção',
                                ' Foi calculado uma sobra de '+p+' de <input type="number" name="quantidade" class="qtd qtd-sobra-material" data-consumo-id="' + consumo_id + '"'+
                                ' step="1" value='+data[key]['QUANTIDADE_SOBRA']+' style="inline-block; width: 95px;" > ',
                                [
                                 {desc:'Registrar Sobra',class:'btn-success btn-confirm-sim' ,ret:'1' ,hotkey:'alt+b',glyphicon:'glyphicon-th-large'},
                                 {desc:'Baixar total',class:'btn-primary btn-confirm-sim' ,ret:'3' ,hotkey:'alt+r',glyphicon:'glyphicon-th'},
                                 obtn_cancelar
                                ],
                                [{ret:1,func:function(e){
                                    
                                    var modal = $(e.target).closest('.modal-content');
                                            
                                    var sobra = $(modal).find('.qtd-sobra-material').val();
                                    var consumo_id = $(modal).find('.qtd-sobra-material').data('consumo-id');
                                    
                                    var tr_consumo = $('tr[consumo-id="' + consumo_id + '"]');
                                    
                                    $(tr_consumo).find('._sobra-material').val(sobra);
                                    
                                    gravar_SobraMaterial(consumo_id,sobra);
                                    
                                }},{ret:2,func:function(){
                                    
                                    vs = 0;
                                    vt = 0;
                                    
                                    gravar_prod();
                                    
                                }},{ret:3,func:function(e){
                                    
                                    var modal      = $(e.target).closest('.modal-content');
                                    var consumo_id = $(modal).find('.qtd-sobra-material').data('consumo-id');
                                    var tr_consumo = $('tr[consumo-id="' + consumo_id + '"]');
                                    
                                    $(tr_consumo).find('._sobra-material').val(0);
                                    
                                    gravar_SobraMaterial(consumo_id,0);
                                    
                                }}]);
                                
                                setTimeout(function(){
                                    $('.qtd-sobra-material').focus();
                                    $('.qtd-sobra-material').trigger('change');
                                    $('.qtd-sobra-material').select();  
                                },1000);
                                
                                
                                
                            //}else{
                            //    showErro('Este produto não permite sobra de Meteria prima! SOBRA:' + data[key]['QUANTIDADE']);
                            //    ret = -1;
                            //}
                            
                        }else{
                            
                            if(data[key]['QUANTIDADE_SOBRA'] < 0){
                                var qtmp = parseFloat(data[key]['QUANTIDADE_SOBRA']);
                                
                                addConfirme('Registro de Produção',
                                ' Foi calculado o uso maior do que o alocado de <input type="number" name="quantidade" class="qtd qtd-sobra-material"'+
                                ' step="1" value='+qtmp+' style="inline-block; width: 95px;" > de '+p+', Esta correto?',[obtn_sim,obtn_nao],
                                [{ret:1,func:function(){
                                    $(sobra).val($('.qtd-sobra-material').attr('valor'));
                                    
                                    gravar_SobraMaterial(consumo_id,$('.qtd-sobra-material').attr('valor'));
                                        
                                }},
                                {ret: 2, func: function () {
                                        
                                }}]);

                                $('.qtd-sobra-material').trigger('change');
                                $('.qtd-sobra-material').select();
                            }else{

                            }
                        }  
                        
                    }else{
                       if(ret == 0){  
                            $('.btn-filtrar').click();
                       }
                    }
                    
                }else{
                   if(ret == 0){  
                   //     $('.btn-filtrar').click();
                   }
                }

            });
            
            cont++;
        });
        
    }

    /**
    * Gravar quantidade.
    * 
    * @param {button} btn
    */
   function gravar_quantidade_produzida(btn) {
       
       
       var qtd		          = '';
       var detalhe            = angular.element('#AppCtrl').scope().vm.TalaoDetalhe.SELECTED;
       var talao_id	          = $(btn).parent().nextAll('._talao-id').val();
       var qtd_proj	          = (detalhe.QUANTIDADE - detalhe.QUANTIDADE_DEFEITO).toFixed(4);
       var qtd_unim	          = $(btn).parent().nextAll('._quantidade-projetada-um').val();
       var qtd_max	          = $(btn).parent().nextAll('._tolerancia-max').val();
       var qtd_min	          = $(btn).parent().nextAll('._tolerancia-min').val();
       var qtd_tip	          = $(btn).parent().nextAll('._tolerancia-tip').val();
           sobra_tipo         = $(btn).parent().nextAll('._sobra-tipo').val();
       var qtd_aproveitamento = $(btn).parent().nextAll('._quantidade-aproveitamento').val();
       
       REMESSA_ID             = $(btn).parent().nextAll('._remessa-id').val();
       REMESSA_TALAO_ID       = $(btn).parent().nextAll('._remessa-talao-id').val();
       
       var cmp_sob = $(btn).parent().nextAll('.sobra-prod');
       
       cb = cmp_sob;
       
       var input		= '';
       var url			= '/_22020/alterarQtdTalaoDetalhe';
       var retorno		= '';
       
       if ( $(btn).parent('td').hasClass('qtd') ) {
           input	= 'input.qtd';
           retorno = 'QUANTIDADE';
       }
       else {
           input	= 'input.qtd-alternativa';
           retorno = 'QUANTIDADE_ALTERNATIVA';
       }

       qtd	= $(btn).siblings(input).val();

       //converter antes para reutilizar
       var valide1 = isNaN(parseFloat(qtd));
       var valide2 = isNaN(parseFloat(qtd_proj));
       var valide3 = isNaN(parseFloat(qtd_aproveitamento));
       
       //converter antes para reutilizar
       var v1 = parseFloat(qtd);
       var v2 = parseFloat(qtd_proj);
       var v4 = parseFloat(qtd_aproveitamento);
       
	   // Qtd. Proj.  |  Qtd. Aprov.  |  Qtd. Prod.
	   //        50   |          20   |         10
	   //	
	   // 10 - (50 - 20) = -20 SOBRA      
	   //  
	   // Qtd. Proj.  |  Qtd. Aprov.  |  Qtd. Prod.
	   //        50   |          20   |         30
	   //	
	   // 30 - (50 - 20) = 0 SOBRA
	   //  
	   // Qtd. Proj.  |  Qtd. Aprov.  |  Qtd. Prod.
	   //        50   |          20   |         40
	   //	
	   // 40 - (50 - 20) = 10 SOBRA

       var v3 = (v1-(v2-v4)).toFixed(2);
       
       if ( (valide1 === false) && (valide2 === false) && (valide3 === false)){
        
            if( qtd_tip == 'Q'){
                var toleranciamais = parseFloat(qtd_max);
                var toleranciamens = parseFloat(qtd_min) * -1;
            }else{
                if( qtd_tip == 'P'){
                    var toleranciamais = parseFloat((qtd_max/100)*v2);
                    var toleranciamens = parseFloat((qtd_min/100)*v2) * -1;
                }else{
                    var toleranciamais = parseFloat(999999);
                    var toleranciamens = parseFloat(999999) * -1;
                }
            }
            
            console.log('Tolerancia Mais:'+toleranciamais);
            console.log('Tolerancia Menos:'+toleranciamens);
            console.log('Dif:'+v3);
        
            if ((v3 > toleranciamais) || (v3 < toleranciamens) && (v1 > 0)) {
                 
                if (sobra_tipo == 'P'){
                    if (v1 > 0){
                        validar_prod(v1,v2,v3,v4,toleranciamais,toleranciamens,qtd_unim,talao_id,btn);
                    }
                }else{
                    showErro('Este produto não permite sobra de Produção!');
                }

            }else{

                execAjax1(
                    'POST',
                    url, 
                    { 
                        retorno				: retorno,
                        qtd					: qtd,
                        sbr					: 0,
                        talao_detalhe_id	: talao_id,
                        REMESSA_ID          : REMESSA_ID,
                        REMESSA_TALAO_ID    : REMESSA_TALAO_ID
                    },
                    function(data) {
                        
                        if(v1 > 0){
                            validarRet(data,btn);
                        }
                        
                        if(ret == 0){
                            
                            $(btn)
                                .siblings('span.valor')
                                .empty()
                                .text( formataPadraoBr(qtd) )
                                .show()
                            ;

                            $(cb).empty().text(0);

                            var btn_parent	= $(btn).parent();
                            var th_qtd = '';

                            if (input === 'input.qtd') {

                                th_qtd					= 'th.qtd';
                                var input_qtd			= $(btn_parent).siblings('._quantidade');

                                $(input_qtd)
                                    .val(qtd)
                                ;

                            }
                            else {

                                th_qtd							= 'th.qtd-alternativa';
                                var input_qtd					= $(btn_parent).siblings('._quantidade-alternativa');
                                var qtd_projetada_altern_val	= $(btn_parent).siblings('._quantidade-projetada-altern').val();
                                var qtd_produzida_altern_val	= qtd;
                                var aproveitamento_altern_val	= $(btn_parent).siblings('._quantidade-aproveitamento-altern').val();

                                var saldo_altern				= qtd_projetada_altern_val - qtd_produzida_altern_val - aproveitamento_altern_val;
                                saldo_altern					= formataPadraoBr(saldo_altern.toFixed('4'));

                                $(btn_parent)
                                    .siblings('.saldo')
                                    .text( saldo_altern )
                                ;

                                $(input_qtd)
                                    .val(qtd)
                                ;

                            }

                            $('#detalhe')
                                .find(th_qtd)
                                .removeClass('editando')
                            ;

                            $(btn)
                                .parent()
                                .removeClass('editando')
                            ;

                            $(btn)
                                .hide()
                            ;

                            $(btn)
                                .siblings('button.qtd-cancelar')
                                .hide()
                            ;

                            $(btn)
                                .siblings(input)
                                .attr('max', qtd)
                                .hide()
                            ;

                            $(btn)
                                .siblings('button.qtd-editar')
                                .show()
                            ;

                            $('.table-talao-produzir')
                                .trigger('resize')
                            ; 

                            $('.btn-filtrar').click();

                            showSuccess('Quantidade alterada com sucesso.');
                        }else{
                            ret = 0;
                        }
                    }

                );
            }
       }
   }

(function($) {
    
    function tratarprod(e){
        var prod = $(e).val();
        var proj = $(e).attr('projetado');
        var qtd_aproveitamento = $(e).data('qtd-aproveitamento');
        
        var tm = parseFloat($(e).data('tm'));
        var tn = parseFloat($(e).data('tn'));
        
        var v1 = parseFloat(prod);
        var v2 = parseFloat(proj);
		var v4 = parseFloat(qtd_aproveitamento);
        
        var valide1 = isNaN(v1);
        var valide2 = isNaN(v2);
        var valide3 = isNaN(v4);
        
        var v3 = (v1-(v2-v4)).toFixed(2);
       
       if ( (valide1 === false) && (valide2 === false) && (valide3 === false)){
            
            if(v3 >= 0){
                var v1 = (v3 < tm);
            }else{
                var v1 = (v3 > tn);
            }

            if (v1 == true) {
                
               $('.sobras').html(0);
               vp = prod;
               vt = parseFloat(prod);
               vs = 0;
                    
               $('.btn-confirm-sim').removeAttr('disabled');
               $('.frase-validar').html('<strong>igual</strong> ao');
               //$('.qtd-gravar').removeAttr('disabled');
               
            }else{
                //showErro('Valor fora da tolerância!');
                
                if(v3 > 0){
                    $('.sobras').html(v3);
                    vp = prod;
                    vt = parseFloat(prod)-parseFloat(v3);
                    vs = v3;
                    
                    $('.btn-confirm-sim').removeAttr('disabled');
                    $('.frase-validar').html('<strong>maior</strong> do que o');
                    //$('.qtd-gravar').removeAttr('disabled');
                }else{
                    $('.sobras').html(0);
                    vp = prod;
                    vt = parseFloat(prod);
                    vs = 0;
                    
                    $('.btn-confirm-sim').attr('disabled','disabled');
                    $('.frase-validar').html('<strong>menor</strong> do que o');
                    //$('.qtd-gravar').attr('disabled','disabled');
                }
                
                return false;
            }
            
       }else{
            //showErro('Valor invalido na projeção ou na quantidade produsida!');
            return false;
       }
        
    };
    
    $(document).on('change','.qtd-prod-conf', function(e) {

        tratarprod(this);
    
    });
    
    $(document).on('change','.qtd', function(e) {
        
        //tratarprod(this);
    
    });
    
    $(document).on('change','.qtd-sobra-material', function(e) {
        
        $('.qtd-sobra-material').attr('valor',$(this).val());
    
    });
    
    
    
	
})(jQuery);

//# sourceMappingURL=_22020-Pronta-Entrega.js.map
