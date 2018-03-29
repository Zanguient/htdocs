/* global table_default */

/**
 * Script com funções do obj _22010. (Para utilização de sobras e materias já pronto, servindo com pronta entrega)
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
        var url        = '/_22010/alterarQtdTalaoDetalhe';
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
               
               showSuccess('Quantidade alterada com sucesso.');
       
               var ng            = angular.element('#AppCtrl').scope().vm;
               var talao_detalhe = ng.TalaoDetalhe;
                
               ng.TalaoDetalhe.SELECTED.EDITANDO_QUANTIDADE = false; 
               
                var index = talao_detalhe.QUANTIDADE_ALTERANDO.indexOf(ng.TalaoDetalhe.SELECTED);
                talao_detalhe.QUANTIDADE_ALTERANDO.splice(index, 1);   

                ng.TalaoComposicao.consultar();
               
           }

       );
    }
    
    function gravar_SobraMaterial(consumo_id,sobra){
        var input   = '';
        var url     = '/_22010/alterarQtdSobraMaterial';
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
               angular.element('#AppCtrl').scope().vm.TalaoComposicao.consultar();
//               $('.btn-filtrar').click();

           },
           function(data){
               angular.element('#AppCtrl').scope().vm.TalaoComposicao.consultar();
//               $('.btn-filtrar').click();
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

//                        if(arryTxt.length > 1){
//                            $(obj).text(data[key]['QUANTIDADE_PROJETADA']+' '+ arryTxt[1]);
//                        }else{
//                            $(obj).text(data[key]['QUANTIDADE_PROJETADA']);   
//                        }
                        
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
                                ' Foi calculado o uso menor do que o alocado de <input type="number" name="quantidade" class="qtd qtd-sobra-material" data-consumo-id="' + consumo_id + '"'+
                                ' step="1" value='+qtmp+' style="inline-block; width: 95px;" > de '+p+', Esta correto?',[obtn_sim,obtn_nao],
                                [{ret:1,func:function(e){
                                            
                                    var modal = $(e.target).closest('.modal-content');
                                            
                                    var sobra = $(modal).find('.qtd-sobra-material').val();
                                    var consumo_id = $(modal).find('.qtd-sobra-material').data('consumo-id');
                                    
//                                    var tr_consumo = $('tr[consumo-id="' + consumo_id + '"]');
                                    
//                                    $(tr_consumo).find('._sobra-material').val(sobra);                                            
                                            
//                                    var modal = $(e.target).closest('.modal-content');
//                                            
//                                    var sobra = $(modal).find('.qtd-sobra-material').val();
//                                    var consumo_id = $(modal).find('.qtd-sobra-material').data('consumo-id');
//                                    
//                                    $(sobra).val($('.qtd-sobra-material').attr('valor'));
                                    
                                    gravar_SobraMaterial(consumo_id,sobra);
                                        
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
                           angular.element('#AppCtrl').scope().vm.TalaoComposicao.consultar();
//                            $('.btn-filtrar').click();
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

//# sourceMappingURL=_22010-Pronta-Entrega.js.map
