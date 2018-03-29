// jQuery Mask Plugin v1.13.4
// github.com/igorescobar/jQuery-Mask-Plugin
(function(b){"function"===typeof define&&define.amd?define(["jquery"],b):"object"===typeof exports?module.exports=b(require("jquery")):b(jQuery||Zepto)})(function(b){var y=function(a,c,d){a=b(a);var g=this,k=a.val(),l;c="function"===typeof c?c(a.val(),void 0,a,d):c;var e={invalid:[],getCaret:function(){try{var q,b=0,e=a.get(0),f=document.selection,c=e.selectionStart;if(f&&-1===navigator.appVersion.indexOf("MSIE 10"))q=f.createRange(),q.moveStart("character",a.is("input")?-a.val().length:-a.text().length),
b=q.text.length;else if(c||"0"===c)b=c;return b}catch(d){}},setCaret:function(q){try{if(a.is(":focus")){var b,c=a.get(0);c.setSelectionRange?c.setSelectionRange(q,q):c.createTextRange&&(b=c.createTextRange(),b.collapse(!0),b.moveEnd("character",q),b.moveStart("character",q),b.select())}}catch(f){}},events:function(){a.on("input.mask keyup.mask",e.behaviour).on("paste.mask drop.mask",function(){setTimeout(function(){a.keydown().keyup()},100)}).on("change.mask",function(){a.data("changed",!0)}).on("blur.mask",
function(){k===a.val()||a.data("changed")||a.triggerHandler("change");a.data("changed",!1)}).on("blur.mask",function(){k=a.val()}).on("focus.mask",function(a){!0===d.selectOnFocus&&b(a.target).select()}).on("focusout.mask",function(){d.clearIfNotMatch&&!l.test(e.val())&&e.val("")})},getRegexMask:function(){for(var a=[],b,e,f,d,h=0;h<c.length;h++)(b=g.translation[c.charAt(h)])?(e=b.pattern.toString().replace(/.{1}$|^.{1}/g,""),f=b.optional,(b=b.recursive)?(a.push(c.charAt(h)),d={digit:c.charAt(h),
pattern:e}):a.push(f||b?e+"?":e)):a.push(c.charAt(h).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"));a=a.join("");d&&(a=a.replace(new RegExp("("+d.digit+"(.*"+d.digit+")?)"),"($1)?").replace(new RegExp(d.digit,"g"),d.pattern));return new RegExp(a)},destroyEvents:function(){a.off("input keydown keyup paste drop blur focusout ".split(" ").join(".mask "))},val:function(b){var c=a.is("input")?"val":"text";if(0<arguments.length){if(a[c]()!==b)a[c](b);c=a}else c=a[c]();return c},getMCharsBeforeCount:function(a,
b){for(var e=0,f=0,d=c.length;f<d&&f<a;f++)g.translation[c.charAt(f)]||(a=b?a+1:a,e++);return e},caretPos:function(a,b,d,f){return g.translation[c.charAt(Math.min(a-1,c.length-1))]?Math.min(a+d-b-f,d):e.caretPos(a+1,b,d,f)},behaviour:function(a){a=a||window.event;e.invalid=[];var c=a.keyCode||a.which;if(-1===b.inArray(c,g.byPassKeys)){var d=e.getCaret(),f=e.val().length,n=d<f,h=e.getMasked(),k=h.length,m=e.getMCharsBeforeCount(k-1)-e.getMCharsBeforeCount(f-1);e.val(h);!n||65===c&&a.ctrlKey||(8!==
c&&46!==c&&(d=e.caretPos(d,f,k,m)),e.setCaret(d));return e.callbacks(a)}},getMasked:function(a){var b=[],k=e.val(),f=0,n=c.length,h=0,l=k.length,m=1,p="push",u=-1,t,w;d.reverse?(p="unshift",m=-1,t=0,f=n-1,h=l-1,w=function(){return-1<f&&-1<h}):(t=n-1,w=function(){return f<n&&h<l});for(;w();){var x=c.charAt(f),v=k.charAt(h),r=g.translation[x];if(r)v.match(r.pattern)?(b[p](v),r.recursive&&(-1===u?u=f:f===t&&(f=u-m),t===u&&(f-=m)),f+=m):r.optional?(f+=m,h-=m):r.fallback?(b[p](r.fallback),f+=m,h-=m):e.invalid.push({p:h,
v:v,e:r.pattern}),h+=m;else{if(!a)b[p](x);v===x&&(h+=m);f+=m}}a=c.charAt(t);n!==l+1||g.translation[a]||b.push(a);return b.join("")},callbacks:function(b){var g=e.val(),l=g!==k,f=[g,b,a,d],n=function(a,b,c){"function"===typeof d[a]&&b&&d[a].apply(this,c)};n("onChange",!0===l,f);n("onKeyPress",!0===l,f);n("onComplete",g.length===c.length,f);n("onInvalid",0<e.invalid.length,[g,b,a,e.invalid,d])}};g.mask=c;g.options=d;g.remove=function(){var b=e.getCaret();e.destroyEvents();e.val(g.getCleanVal());e.setCaret(b-
e.getMCharsBeforeCount(b));return a};g.getCleanVal=function(){return e.getMasked(!0)};g.init=function(c){c=c||!1;d=d||{};g.byPassKeys=b.jMaskGlobals.byPassKeys;g.translation=b.jMaskGlobals.translation;g.translation=b.extend({},g.translation,d.translation);g=b.extend(!0,{},g,d);l=e.getRegexMask();!1===c?(d.placeholder&&a.attr("placeholder",d.placeholder),b("input").length&&!1==="oninput"in b("input")[0]&&"on"===a.attr("autocomplete")&&a.attr("autocomplete","off"),e.destroyEvents(),e.events(),c=e.getCaret(),
e.val(e.getMasked()),e.setCaret(c+e.getMCharsBeforeCount(c,!0))):(e.events(),e.val(e.getMasked()))};g.init(!a.is("input"))};b.maskWatchers={};var A=function(){var a=b(this),c={},d=a.attr("data-mask");a.attr("data-mask-reverse")&&(c.reverse=!0);a.attr("data-mask-clearifnotmatch")&&(c.clearIfNotMatch=!0);"true"===a.attr("data-mask-selectonfocus")&&(c.selectOnFocus=!0);if(z(a,d,c))return a.data("mask",new y(this,d,c))},z=function(a,c,d){d=d||{};var g=b(a).data("mask"),k=JSON.stringify;a=b(a).val()||
b(a).text();try{return"function"===typeof c&&(c=c(a)),"object"!==typeof g||k(g.options)!==k(d)||g.mask!==c}catch(l){}};b.fn.mask=function(a,c){c=c||{};var d=this.selector,g=b.jMaskGlobals,k=b.jMaskGlobals.watchInterval,l=function(){if(z(this,a,c))return b(this).data("mask",new y(this,a,c))};b(this).each(l);d&&""!==d&&g.watchInputs&&(clearInterval(b.maskWatchers[d]),b.maskWatchers[d]=setInterval(function(){b(document).find(d).each(l)},k));return this};b.fn.unmask=function(){clearInterval(b.maskWatchers[this.selector]);
delete b.maskWatchers[this.selector];return this.each(function(){var a=b(this).data("mask");a&&a.remove().removeData("mask")})};b.fn.cleanVal=function(){return this.data("mask").getCleanVal()};b.applyDataMask=function(a){a=a||b.jMaskGlobals.maskElements;(a instanceof b?a:b(a)).filter(b.jMaskGlobals.dataMaskAttr).each(A)};var p={maskElements:"input,td,span,div",dataMaskAttr:"*[data-mask]",dataMask:!0,watchInterval:300,watchInputs:!0,watchDataMask:!1,byPassKeys:[9,16,17,18,36,37,38,39,40,91],translation:{0:{pattern:/\d/},
9:{pattern:/\d/,optional:!0},"#":{pattern:/\d/,recursive:!0},A:{pattern:/[a-zA-Z0-9]/},S:{pattern:/[a-zA-Z]/}}};b.jMaskGlobals=b.jMaskGlobals||{};p=b.jMaskGlobals=b.extend(!0,{},p,b.jMaskGlobals);p.dataMask&&b.applyDataMask();setInterval(function(){b.jMaskGlobals.watchDataMask&&b.applyDataMask()},p.watchInterval)});

/*
* maskMoney plugin for jQuery
* http://plentz.github.com/jquery-maskmoney/
* version: 2.0.1
* Licensed under the MIT license
*/
;(function($) {
	if(!$.browser){
		$.browser = {};
		$.browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
		$.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
		$.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
		$.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());
	}

	var methods = {
		destroy : function(){
			var input = $(this);
			input.unbind('.maskMoney');

			if ($.browser.msie) {
				this.onpaste = null;
			} else if ($.browser.mozilla) {
				this.removeEventListener('input', blurEvent, false);
			}
			return this;
		},

		mask : function(){
			return this.trigger('mask');
		},
		
		init : function(settings) {
			settings = $.extend({
				symbol: 'US$',
				showSymbol: false,
				symbolStay: false,
				thousands: ',',
				decimal: '.',
				precision: 2,
				defaultZero: true,
				allowZero: false,
				allowNegative: false
			}, settings);

			return this.each(function() {
				var input = $(this);
				var dirty = false;

				function markAsDirty() {
					dirty = true;
				}

				function clearDirt(){
					dirty = false;
				}

				function keypressEvent(e) {
					e = e || window.event;
					var k = e.which || e.charCode || e.keyCode;
					if (k == undefined) return false; //needed to handle an IE "special" event
					if (k < 48 || k > 57) { // any key except the numbers 0-9
						if (k == 45) { // -(minus) key
							markAsDirty();
							input.val(changeSign(input));
							return false;
						} else if (k == 43) { // +(plus) key
							markAsDirty();
							input.val(input.val().replace('-',''));
							return false;
						} else if (k == 13 || k == 9) { // enter key or tab key
							if(dirty){
								clearDirt();
								$(this).change();
							}
							return true;
						} else if ($.browser.mozilla && (k == 37 || k == 39) && e.charCode == 0) {
							// needed for left arrow key or right arrow key with firefox
							// the charCode part is to avoid allowing '%'(e.charCode 0, e.keyCode 37)
							return true;
						} else { // any other key with keycode less than 48 and greater than 57
							preventDefault(e);
							return true;
						}
					} else if (input.val().length >= input.attr('maxlength')) {
						return false;
					} else {
						preventDefault(e);

						var key = String.fromCharCode(k);
						var x = input.get(0);
						var selection = getInputSelection(x);
						var startPos = selection.start;
						var endPos = selection.end;
						x.value = x.value.substring(0, startPos) + key + x.value.substring(endPos, x.value.length);
						maskAndPosition(x, startPos + 1);
						markAsDirty();
						return false;
					}
				}

				function keydownEvent(e) {
					e = e||window.event;
					var k = e.which || e.charCode || e.keyCode;
					if (k == undefined) return false; //needed to handle an IE "special" event

					var x = input.get(0);
					var selection = getInputSelection(x);
					var startPos = selection.start;
					var endPos = selection.end;

					if (k==8) { // backspace key
						preventDefault(e);

						if(startPos == endPos){
							// Remove single character
							x.value = x.value.substring(0, startPos - 1) + x.value.substring(endPos, x.value.length);
							startPos = startPos - 1;
						} else {
							// Remove multiple characters
							x.value = x.value.substring(0, startPos) + x.value.substring(endPos, x.value.length);
						}
						maskAndPosition(x, startPos);
						markAsDirty();
						return false;
					} else if (k==9) { // tab key
						if(dirty) {
							$(this).change();
							clearDirt();
						}
						return true;
					} else if ( k==46 || k==63272 ) { // delete key (with special case for safari)
						preventDefault(e);
						if(x.selectionStart == x.selectionEnd){
							// Remove single character
							x.value = x.value.substring(0, startPos) + x.value.substring(endPos + 1, x.value.length);
						} else {
							//Remove multiple characters
							x.value = x.value.substring(0, startPos) + x.value.substring(endPos, x.value.length);
						}
						maskAndPosition(x, startPos);
						markAsDirty();
						return false;
					} else { // any other key
						return true;
					}
				}

				function focusEvent(e) {
					var mask = getDefaultMask();
					if (input.val() == mask) {
						input.val('');
					} else if (input.val()=='' && settings.defaultZero) {
						input.val(setSymbol(mask));
					} else {
						input.val(setSymbol(input.val()));
					}
					if (this.createTextRange) {
						var textRange = this.createTextRange();
						textRange.collapse(false); // set the cursor at the end of the input
						textRange.select();
					}
				}

				function blurEvent(e) {
					if ($.browser.msie) {
						keypressEvent(e);
					}

					if (input.val() == '' || input.val() == setSymbol(getDefaultMask()) || input.val() == settings.symbol) {
						if(!settings.allowZero) input.val('');
						else if (!settings.symbolStay) input.val(getDefaultMask());
						else input.val(setSymbol(getDefaultMask()));
					} else {
						if (!settings.symbolStay) input.val(input.val().replace(settings.symbol,''));
						else if (settings.symbolStay&&input.val()==settings.symbol) input.val(setSymbol(getDefaultMask()));
					}
				}

				function preventDefault(e) {
					if (e.preventDefault) { //standard browsers
						e.preventDefault();
					} else { // internet explorer
						e.returnValue = false
					}
				}

				function maskAndPosition(x, startPos) {
					var originalLen = input.val().length;
					input.val(maskValue(x.value));
					var newLen = input.val().length;
					startPos = startPos - (originalLen - newLen);
					setCursorPosition(input, startPos);
				}
				
				function mask(){
					var value = input.val();
					input.val(maskValue(value));
				}

				function maskValue(v) {
					v = v.replace(settings.symbol, '');

					var strCheck = '0123456789';
					var len = v.length;
					var a = '', t = '', neg='';

					if(len != 0 && v.charAt(0)=='-'){
						v = v.replace('-','');
						if(settings.allowNegative){
							neg = '-';
						}
					}

					if (len==0) {
						if (!settings.defaultZero) return t;
						t = '0.00';
					}

					for (var i = 0; i<len; i++) {
						if ((v.charAt(i)!='0') && (v.charAt(i)!=settings.decimal)) break;
					}

					for (; i < len; i++) {
						if (strCheck.indexOf(v.charAt(i))!=-1) a+= v.charAt(i);
					}
					var n = parseFloat(a);

					n = isNaN(n) ? 0 : n/Math.pow(10,settings.precision);
					t = n.toFixed(settings.precision);

					i = settings.precision == 0 ? 0 : 1;
					var p, d = (t=t.split('.'))[i].substr(0,settings.precision);
					for (p = (t=t[0]).length; (p-=3)>=1;) {
						t = t.substr(0,p)+settings.thousands+t.substr(p);
					}

					return (settings.precision>0)
					? setSymbol(neg+t+settings.decimal+d+Array((settings.precision+1)-d.length).join(0))
					: setSymbol(neg+t);
				}

				function getDefaultMask() {
					var n = parseFloat('0')/Math.pow(10,settings.precision);
					return (n.toFixed(settings.precision)).replace(new RegExp('\\.','g'),settings.decimal);
				}

				function setSymbol(value){
					if (settings.showSymbol){
						var operator = '';
						if(value.length != 0 && value.charAt(0) == '-'){
							value = value.replace('-', '');
							operator = '-';
						}

						if(value.substr(0, settings.symbol.length) != settings.symbol){
							value = operator + settings.symbol + value;
						}
					}
					return value;
				}

				function changeSign(i){
					if (settings.allowNegative) {
						var vic = i.val();
						if (i.val()!='' && i.val().charAt(0)=='-'){
							return i.val().replace('-','');
						} else{
							return '-'+i.val();
						}
					} else {
						return i.val();
					}
				}

				function setCursorPosition(input, pos) {
					// I'm not sure if we need to jqueryfy input
					$(input).each(function(index, elem) {
						if (elem.setSelectionRange) {
							elem.focus();
							elem.setSelectionRange(pos, pos);
						} else if (elem.createTextRange) {
							var range = elem.createTextRange();
							range.collapse(true);
							range.moveEnd('character', pos);
							range.moveStart('character', pos);
							range.select();
						}
					});
					return this;
				};

				function getInputSelection(el) {
					var start = 0, end = 0, normalizedValue, range, textInputRange, len, endRange;

					if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
						start = el.selectionStart;
						end = el.selectionEnd;
					} else {
						range = document.selection.createRange();

						if (range && range.parentElement() == el) {
							len = el.value.length;
							normalizedValue = el.value.replace(/\r\n/g, "\n");

							// Create a working TextRange that lives only in the input
							textInputRange = el.createTextRange();
							textInputRange.moveToBookmark(range.getBookmark());

							// Check if the start and end of the selection are at the very end
							// of the input, since moveStart/moveEnd doesn't return what we want
							// in those cases
							endRange = el.createTextRange();
							endRange.collapse(false);

							if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
								start = end = len;
							} else {
								start = -textInputRange.moveStart("character", -len);
								start += normalizedValue.slice(0, start).split("\n").length - 1;

								if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
									end = len;
								} else {
									end = -textInputRange.moveEnd("character", -len);
									end += normalizedValue.slice(0, end).split("\n").length - 1;
								}
							}
						}
					}

					return {
						start: start,
						end: end
					};
				} // getInputSelection

				if (!input.attr("readonly")){
					input.unbind('.maskMoney');
					input.bind('keypress.maskMoney', keypressEvent);
					input.bind('keydown.maskMoney', keydownEvent);
					input.bind('blur.maskMoney', blurEvent);
					input.bind('focus.maskMoney', focusEvent);
					input.bind('mask.maskMoney', mask);
				}
			})
		}
	}

	$.fn.maskMoney = function(method) {
		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
		}
	};
})(jQuery);

/**
 * Script com funções para definir máscaras.
 */

/**
 * Aplica máscara de telefone.
 * @param {input} campo
 */
function mascaraFone(campo) {

    var MascaraFone = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        foneOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(MascaraFone.apply({}, arguments), options);
            }
        };

    jQuery(campo).mask(MascaraFone, foneOptions);

}


/**
 * Aplica a mascara do tipo R$
 * <br />
 * Obs 1: Caso o simbolo da moeda não seja atribudo, por padrão, o simbolo da moeda será igual a R$
 * Ex.: 'U$' => U$ 1000,00
 * <br />
 * Obs 2: Caso o atributo "simbolo" não seja "true", por padrão, o simbolo da moeda não será exibido
 * Ex.: true => R$ 1000,00 ; false => 1000,00
 * <br />
 * Obs 3: Caso o atributo "precisao" não seja atribuido, por padrão, as casas decimais serão igual 2
 * Ex.: 2 => 1000,00 ; 3 => 1000,000
 * 
 * @param {input} campo
 */
function mascaraDinheiro(campo) {

	jQuery(campo).each(function() {
		
		var currency_symbol		= (typeof jQuery(this).attr('simbolo')			!== typeof undefined && jQuery(this).attr('simbolo') 		    !== false) ? jQuery(this).attr('simbolo') : 'R$';
		var currency_showSymbol	= (typeof jQuery(this).attr('exibir-simbolo')	!== typeof undefined && jQuery(this).attr('exibir-simbolo')     !== false) ? true : false;
		var currency_precision	= (typeof jQuery(this).attr('precisao')			!== typeof undefined && jQuery(this).attr('precisao')		    !== false) ? parseInt(jQuery(this).attr('precisao')) : 2;

		jQuery(this).maskMoney({
			symbol: 	currency_symbol+ ' ',
			showSymbol: currency_showSymbol,
			precision: 	currency_precision,
			thousands:	'.',
			decimal: 	',',
			symbolStay: true
		});
		
	});

}


/**
 * Máscara para números.
 */
function mascNum() {
	
	$(document).on('keypress','.mask-numero', function() {

		var decimal = $(this).attr('decimal');

		if (decimal < 1){
		   decimal = 0;
		}

		return SomenteNumeroePonto(this,event,decimal);

	});
	
}

(function($) {
	$(function() {

		//mascaraDinheiro('.currency');
		//mascaraDinheiro('.mask-dinheiro');
		//mascaraDinheiro('.mask-qtd');
		mascaraFone('.fone');
		mascNum();

	});	
})(jQuery);
//# sourceMappingURL=mask.js.map
