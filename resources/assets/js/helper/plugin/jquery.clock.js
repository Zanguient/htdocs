
(function($){
	
	// A global array used by the functions of the plug-in:
	var gVars = {};

	// Extending the jQuery core:
	$.fn.addClock = function(opts){
	
		// "this" contains the elements that were selected when calling the plugin: $('elements').tzineClock();
		// If the selector returned more than one element, use the first one:
		
		var container = this.eq(0);
	
		if(!container)
		{
			try{
				console.log("Invalid selector!");
			} catch(e){}
			
			return false;
		}
		
		if(!opts) opts = {}; 
		
		var defaults = {
			/* Additional options will be added in future versions of the plugin. */
		};
		
		/* Merging the provided options with the default ones (will be used in future versions of the plugin): */
		$.each(defaults,function(k,v){
			opts[k] = opts[k] || defaults[k];
		})

		// Calling the setUp function and passing the container,
		// will be available to the setUp function as "this":
		setUp.call(container);
		
		return this;
	}
	
	function setUp()
	{
		// The colors of the dials:
		var colors = ['orange','blue','green'];
		
		var tmp;
		var progressInicio = '<div class="c100 p50 small"><span>';
		var progressFim = '</span><div class="slice"><div class="bar"></div><div class="fill"></div></div></div>';
		
		for(var i=0;i<3;i++)
		{
			// Creating a new element and setting the color as a class name:
			progressInicio = '<div class="c100 p50 small "><span>';
			progressFim = '</span><div class="slice"><div class="bar color-progress-'+colors[i]+'"></div><div class="fill  color-progress-'+colors[i]+'"></div></div></div>';
		
			tmp = $('<div>').attr('class',colors[i]+' clock').html(progressInicio+'<div class="display"></div>'+progressFim);
			
			// Appending to the container:
			$(this).append( tmp );
			
			// Assigning some of the elements as variables for speed:
			tmp.rotateLeft = tmp.find('.rotate.left');
			tmp.rotateRight = tmp.find('.rotate.right');
			tmp.display = tmp.find('.display');
			tmp.progress = tmp.find('.c100');
			
			// Adding the dial as a global variable. Will be available as gVars.colorName
			gVars[colors[i]] = tmp;
		}
		
		// Setting up a interval, executed every 1000 milliseconds:
		setInterval(function(){
		
			var currentTime = new Date();
			var h = currentTime.getHours();
			var m = currentTime.getMinutes();
			var s = currentTime.getSeconds();
			
			animation(gVars.green, s, 60);
			animation(gVars.blue, m, 60);
			animation(gVars.orange, h, 24);
		
		},1000);
	}
	
	function removeAllClass(e)
	{
		$(e).removeClass("p1"); $(e).removeClass("p2"); $(e).removeClass("p3"); $(e).removeClass("p4"); $(e).removeClass("p5");
		$(e).removeClass("p6"); $(e).removeClass("p7"); $(e).removeClass("p8"); $(e).removeClass("p9"); $(e).removeClass("p10");
		
		$(e).removeClass("p11"); $(e).removeClass("p12"); $(e).removeClass("p13"); $(e).removeClass("p14"); $(e).removeClass("p15");
		$(e).removeClass("p16"); $(e).removeClass("p17"); $(e).removeClass("p18"); $(e).removeClass("p19"); $(e).removeClass("p20");
		
		$(e).removeClass("p21"); $(e).removeClass("p22"); $(e).removeClass("p23"); $(e).removeClass("p24"); $(e).removeClass("p25");
		$(e).removeClass("p26"); $(e).removeClass("p27"); $(e).removeClass("p28"); $(e).removeClass("p29"); $(e).removeClass("p30");
		
		$(e).removeClass("p31"); $(e).removeClass("p32"); $(e).removeClass("p33"); $(e).removeClass("p34"); $(e).removeClass("p35");
		$(e).removeClass("p36"); $(e).removeClass("p37"); $(e).removeClass("p38"); $(e).removeClass("p39"); $(e).removeClass("p40");
		
		$(e).removeClass("p41"); $(e).removeClass("p42"); $(e).removeClass("p43"); $(e).removeClass("p44"); $(e).removeClass("p45");
		$(e).removeClass("p46"); $(e).removeClass("p47"); $(e).removeClass("p48"); $(e).removeClass("p49"); $(e).removeClass("p50");
		
		$(e).removeClass("p51"); $(e).removeClass("p52"); $(e).removeClass("p53"); $(e).removeClass("p54"); $(e).removeClass("p55");
		$(e).removeClass("p56"); $(e).removeClass("p57"); $(e).removeClass("p58"); $(e).removeClass("p59"); $(e).removeClass("p60");
		
		$(e).removeClass("p61"); $(e).removeClass("p62"); $(e).removeClass("p63"); $(e).removeClass("p64"); $(e).removeClass("p65");
		$(e).removeClass("p66"); $(e).removeClass("p67"); $(e).removeClass("p68"); $(e).removeClass("p69"); $(e).removeClass("p70");
		
		$(e).removeClass("p71"); $(e).removeClass("p72"); $(e).removeClass("p73"); $(e).removeClass("p74"); $(e).removeClass("p75");
		$(e).removeClass("p76"); $(e).removeClass("p77"); $(e).removeClass("p78"); $(e).removeClass("p79"); $(e).removeClass("p80");
		
		$(e).removeClass("p81"); $(e).removeClass("p82"); $(e).removeClass("p83"); $(e).removeClass("p84"); $(e).removeClass("p85");
		$(e).removeClass("p86"); $(e).removeClass("p87"); $(e).removeClass("p88"); $(e).removeClass("p89"); $(e).removeClass("p90");
		
		$(e).removeClass("p91"); $(e).removeClass("p92"); $(e).removeClass("p93"); $(e).removeClass("p94"); $(e).removeClass("p95");
		$(e).removeClass("p96"); $(e).removeClass("p97"); $(e).removeClass("p98"); $(e).removeClass("p99"); $(e).removeClass("p100");
		
		$(e).removeClass("p0");
		
	}
	
	function animation(clock, current, total)
	{
		var per = parseInt((current/total)*100);
		
		removeAllClass(clock.progress);
		$(clock.progress).addClass("p"+per);
		
		clock.display.html(current<10?'0'+current:current);
	}
	
})(jQuery)