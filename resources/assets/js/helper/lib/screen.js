(function($) {
	//funções para alterar a cor de um objeto
    function RgbToHsv(r, g, b) {
        var min = Math.min(r, g, b),
            max = Math.max(r, g, b),
            delta = max - min,
            h, s, v = max;

        v = Math.floor(max / 255 * 100);
        if (max == 0) return { h: 0, s: 0, v: 0 };
        s = Math.floor(delta / max * 100);
        var deltadiv = delta == 0 ? 1 : delta;
        if( r == max ) h = (g - b) / deltadiv;
        else if(g == max) h = 2 + (b - r) / deltadiv;
        else h = 4 + (r - g) / deltadiv;
        h = Math.floor(h * 60);
        if( h < 0 ) h += 360;
        return { h: h, s: s, v: v }
    }
    
    function HsvToRgb(h, s, v) {
        h = h / 360;
        s = s / 100;
        v = v / 100;

        if (s == 0)
        {
            var val = Math.round(v * 255);
            return {r:val,g:val,b:val};
        }
        hPos = h * 6;
        hPosBase = Math.floor(hPos);
        base1 = v * (1 - s);
        base2 = v * (1 - s * (hPos - hPosBase));
        base3 = v * (1 - s * (1 - (hPos - hPosBase)));
        if (hPosBase == 0) {red = v; green = base3; blue = base1}
        else if (hPosBase == 1) {red = base2; green = v; blue = base1}
        else if (hPosBase == 2) {red = base1; green = v; blue = base3}
        else if (hPosBase == 3) {red = base1; green = base2; blue = v}
        else if (hPosBase == 4) {red = base3; green = base1; blue = v}
        else {red = v; green = base1; blue = base2};

        red = Math.round(red * 255);
        green = Math.round(green * 255);
        blue = Math.round(blue * 255);
        return { r: red, g: green, b: blue };
    } 
    
    function mustureCor(paleta,light){
        var fator = light;
        var temp = 0;
        var cor = parseInt(paleta);

        if(cor > fator){
            temp = cor - fator;
            cor  = cor - parseInt(temp / 2);
        }else{
           temp = fator - cor;
            cor  = cor + parseInt(temp / 2); 
        }
        
        return cor;
        
    }
    
    // escurece uma cor somando a com outra cor
    // elemento = classe id  e etc
    // light % de brilho
    function AppendColor(elemento,light) {
        $(elemento).each(function(i){
            
            if(light == 0){
                
                var img = $(this).css("background-image");
                
                if(img == 'none'){
                    $(this).css("background", $(this).attr('oldCor'));
                }
                
            }else{
                
                // obtem a cor em RGB do elemento
                var color = $(this).css("background-color");
                var img = $(this).css("background-image");
                
                $(this).attr('oldCor',color);
                
                if((color == 'rgba(0, 0, 0, 0)') || (color == 'rgb(255, 255, 255)') || img != 'none'){
                    
                }else{
                    color = color.replace(/[^0-9,]+/g, "");
                    var red = color.split(",")[0];
                    var gre = color.split(",")[1];
                    var blu = color.split(",")[2];

                    red = mustureCor(red,light);
                    gre = mustureCor(gre,light);
                    blu = mustureCor(blu,light);
                    
                    var rgb = { r: red, g: gre, b: blu };
                    
                    // converte rgb para hsv
                    //var hsv = RgbToHsv(red,gre,blu);

                    // converte hsv para rgb modificando `v`
                    //var rgb = HsvToRgb(hsv.h, hsv.s, light);

                    //seta a nova cor
                    color = "rgb(" + rgb.r + "," + rgb.g + "," + rgb.b + ")";
                    $(this).css("background", color);
                    
                }
            }   
        });
    }
     
	/** 
	 * Coloca um elemento em tela cheia
	 * @param id string
	 * */
	function goFullscreen(id) {
        
        //AppendColor('button',100);

		var element = document.getElementById(id);
        
		if(element.requestFullscreen) {
            element.requestFullscreen();
          } else if(element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
          } else if(element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
          } else if(element.msRequestFullscreen) {
            element.msRequestFullscreen();
          }
	}

	/** 
	 * Retira um elemento da tela cheia
	 * */
	function lowFullscreen() {
        
        //AppendColor('button',0);
        
		if(document.exitFullscreen) {
            document.exitFullscreen();
          } else if(document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
          } else if(document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
          }
	}

    /** 
	 * chama o evento de fullscreen
     * @param xml e botão que foi clicado
	 **/
    var tela;
    
	function eventoFullscreen(e) {
        
		var id = $(e).attr('gofullscreen');

		if (id === 'esc'){
			$(e).attr('gofullscreen',tela);
			lowFullscreen();
		}else{
			tela = id;
			goFullscreen(id);
            $(e).attr('gofullscreen','esc');
		}

	}
	
	$(function() {
	
		$(document).on('click','.go-fullscreen', function() {
			eventoFullscreen(this);
		});
        
        $(document).on('click','.a_resizable', function(e) {
            $('.obj_resizable').addClass('resizable_ativo');
            $('.a_resizable').addClass('d_resizable');
            $('.a_resizable').removeClass('a_resizable');
        });
        
        $(document).on('click','.d_resizable', function(e) {
            $('.obj_resizable').removeClass('resizable_ativo');
            $('.d_resizable').addClass('a_resizable');
            $('.d_resizable').removeClass('d_resizable');
        });
        
        //*
        $(document).on('click','.resizable_ativo', function(e) {

            var element = this;

            if(element){
                
                if(element.requestFullscreen) {
                    element.requestFullscreen();
                  } else if(element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                  } else if(element.webkitRequestFullscreen) {
                    element.webkitRequestFullscreen();
                  } else if(element.msRequestFullscreen) {
                    element.msRequestFullscreen();
                  }
            }
        });
		//*/
	});
    
})(jQuery);