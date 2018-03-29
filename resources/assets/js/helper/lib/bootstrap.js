/**
 * Inicializa funções nativas do bootstrap
 */
function bootstrapInit() {
	    
}

function ativarBtnSwitch() {
	
	if ( $('.chk-switch').length > 0 ) {
	
		$('.chk-switch')
			.bootstrapSwitch()
		;
		
	}	

}

(function($) {
	$(function() {
    
        
        $('body')
        .on('mouseenter', '[data-toggle="popover"]', function () {
          
            $(this)
            .popover({
                html: true,
                container: 'body',
                content: function () {
                    var elem = $(this).attr('data-element-content');
                    return $(elem).html();
                },
                trigger: 'manual'
            });
                
            var old_popover = $(this).attr('aria-describedby');
            
            if ( old_popover == undefined ) {

                $(this).popover("show");
                var popover_show_id = $(this).attr('aria-describedby');

                $('body').children('.popover').each(function(){
                    if ( $(this).attr('id') != popover_show_id ) {
                        $(this).popover('hide');
                    }
                });
            }
        })
        .on('mouseleave', '[data-toggle="popover"]', function () {
			var _this = this;

			setTimeout(function () {                
                
                if ((!$(".popover:hover").length) && (!$(_this).is(":hover"))) {
                    $(".popover").popover("hide");
                }

				$('[id="'+ $(_this).attr('aria-describedby') + '"]').on("mouseleave", function () {
						if ((!$(".popover:hover").length) && (!$(_this).is(":hover"))) {
							$(this).popover("hide");
						}
				});

			}, 100);
        })
        .on('mouseenter', '[data-toggle="tooltip"]', function () {   
            
            $('.tooltip').remove();
    
            $(this)
                .attr('data-base-title', $(this).attr('title'))
                .attr('data-original-title', $(this).attr('title'))
                .removeAttr('title')
                .tooltip({
                    trigger: 'hover',
                    container: "body",
                    html: true
                })
                .tooltip('show')
            ;
            
            $(this)
            .on('hidden.bs.tooltip', function () {
                $(this)
                .attr('title', $(this).attr('data-base-title'))
                .removeAttr('data-base-title')
                .removeAttr('data-original-title')
                ;
            });
        })
        ;  
        

        $(document)
        .on('mouseenter', '.tooltip-field, .limit-width, [auto-title], [data-auto-title], [autotitle], [data-autotitle]', function () {

            $('.tooltip').remove();
            var offsetWidth = this.offsetWidth;
            var scrollWidth = this.scrollWidth;

            if ( offsetWidth < scrollWidth ) {
                $(this)
                    .attr('data-toggle'        , 'tooltip')
                    .attr('data-base-title', $(this).text())
                    .attr('data-original-title', $(this).text())
                    .tooltip({
                        trigger: 'hover',
                        container: "body",
                        html: true
                    })
                    .tooltip('show')
                ;

                $(this)
                .on('hidden.bs.tooltip', function () {
                    $(this)
                    .removeAttr('title')
                    .removeAttr('data-base-title')
                    .removeAttr('data-original-title')
                    ;
                });
            }
            else {
                $(this)
                    .removeAttr('data-toggle'   )
                    .removeAttr('title'         )
                    .removeAttr('data-original-title')
                    .removeAttr('data-base-title')
                    .tooltip('hide')
                ;
            }
        })
        .on('mouseenter', '[ttitle]', function () {

            $('.tooltip').remove();

            $(this)
                .attr('data-toggle'        , 'tooltip')
                .attr('data-base-title', $(this).attr('ttitle'))
                .attr('data-original-title', $(this).attr('ttitle'))
                .tooltip({
                    trigger: 'hover',
                    container: "body",
                    html: true
                })
                .tooltip('show')
            ;

            $(this)
            .on('hidden.bs.tooltip', function () {
                $(this)
                .removeAttr('title')
                .removeAttr('data-base-title')
                .removeAttr('data-original-title')
                ;
            });
                
        })
        .on('mouseenter', '[t-title]', function () {

            $('.tooltip').remove();

            $(this)
                .attr('data-toggle'        , 'tooltip')
                .attr('data-base-title', $(this).attr('t-title'))
                .attr('data-original-title', $(this).attr('t-title'))
                .tooltip({
                    trigger: 'hover',
                    container: "body",
                    html: true
                })
                .tooltip('show')
            ;

            $(this)
            .on('hidden.bs.tooltip', function () {
                $(this)
                .removeAttr('title')
                .removeAttr('data-base-title')
                .removeAttr('data-original-title')
                ;
            });
                
        })
        .on('mouseleave', '[ttitle], [t-title], [data-toggle="tooltip"], [bs-title], .tooltip-field, .limit-width, [auto-title], [data-auto-title], [autotitle], [data-autotitle]', function () {
            $('.tooltip').remove();
        });


		bootstrapInit();
		ativarBtnSwitch();
		
	});
})(jQuery);