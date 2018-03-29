/**
 * Bunch of useful filters for angularJS(with no external dependencies!)
 * @version v0.5.15 - 2017-01-17 * @link https://github.com/a8m/angular-filter
 * @author Ariel Mashraki <ariel@mashraki.co.il>
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */!function(a,b,c){"use strict";function d(a){return E(a)?a:Object.keys(a).map(function(b){return a[b]})}function e(a){return null===a}function f(a,b){var d=Object.keys(a);return d.map(function(d){return b[d]!==c&&b[d]==a[d]}).indexOf(!1)==-1}function g(a,b){function c(a,b,c){for(var d=0;b+d<=a.length;){if(a.charAt(b+d)==c)return d;d++}return-1}for(var d=0,e=0;e<=b.length;e++){var f=c(a,d,b.charAt(e));if(f==-1)return!1;d+=f+1}return!0}function h(a,b,c){var d=0;return a.filter(function(a){var e=y(c)?d<b&&c(a):d<b;return d=e?d+1:d,e})}function i(a,b){return Math.round(a*Math.pow(10,b))/Math.pow(10,b)}function j(a,b,c){b=b||[];var d=Object.keys(a);return d.forEach(function(d){if(D(a[d])&&!E(a[d])){var e=c?c+"."+d:c;j(a[d],b,e||d)}else{var f=c?c+"."+d:d;b.push(f)}}),b}function k(a){return a&&a.$evalAsync&&a.$watch}function l(){return function(a,b){return a>b}}function m(){return function(a,b){return a>=b}}function n(){return function(a,b){return a<b}}function o(){return function(a,b){return a<=b}}function p(){return function(a,b){return a==b}}function q(){return function(a,b){return a!=b}}function r(){return function(a,b){return a===b}}function s(){return function(a,b){return a!==b}}function t(a){return function(b,c){return b=D(b)?d(b):b,!(!E(b)||z(c))&&b.some(function(b){return B(c)&&D(b)||A(c)?a(c)(b):b===c})}}function u(a,b){return b=b||0,b>=a.length?a:E(a[b])?u(a.slice(0,b).concat(a[b],a.slice(b+1)),b):u(a,b+1)}function v(a){return function(b,c){function e(a,b){return!z(b)&&a.some(function(a){return I(a,b)})}if(b=D(b)?d(b):b,!E(b))return b;var f=[],g=a(c);return z(c)?b.filter(function(a,b,c){return c.indexOf(a)===b}):b.filter(function(a){var b=g(a);return!e(f,b)&&(f.push(b),!0)})}}function w(a,b,c){return b?a+c+w(a,--b,c):a}function x(){return function(a){return B(a)?a.split(" ").map(function(a){return a.charAt(0).toUpperCase()+a.substring(1)}).join(" "):a}}var y=b.isDefined,z=b.isUndefined,A=b.isFunction,B=b.isString,C=b.isNumber,D=b.isObject,E=b.isArray,F=b.forEach,G=b.extend,H=b.copy,I=b.equals;String.prototype.contains||(String.prototype.contains=function(){return String.prototype.indexOf.apply(this,arguments)!==-1}),b.module("a8m.angular",[]).filter("isUndefined",function(){return function(a){return b.isUndefined(a)}}).filter("isDefined",function(){return function(a){return b.isDefined(a)}}).filter("isFunction",function(){return function(a){return b.isFunction(a)}}).filter("isString",function(){return function(a){return b.isString(a)}}).filter("isNumber",function(){return function(a){return b.isNumber(a)}}).filter("isArray",function(){return function(a){return b.isArray(a)}}).filter("isObject",function(){return function(a){return b.isObject(a)}}).filter("isEqual",function(){return function(a,c){return b.equals(a,c)}}),b.module("a8m.conditions",[]).filter({isGreaterThan:l,">":l,isGreaterThanOrEqualTo:m,">=":m,isLessThan:n,"<":n,isLessThanOrEqualTo:o,"<=":o,isEqualTo:p,"==":p,isNotEqualTo:q,"!=":q,isIdenticalTo:r,"===":r,isNotIdenticalTo:s,"!==":s}),b.module("a8m.is-null",[]).filter("isNull",function(){return function(a){return e(a)}}),b.module("a8m.after-where",[]).filter("afterWhere",function(){return function(a,b){if(a=D(a)?d(a):a,!E(a)||z(b))return a;var c=a.map(function(a){return f(b,a)}).indexOf(!0);return a.slice(c===-1?0:c)}}),b.module("a8m.after",[]).filter("after",function(){return function(a,b){return a=D(a)?d(a):a,E(a)?a.slice(b):a}}),b.module("a8m.before-where",[]).filter("beforeWhere",function(){return function(a,b){if(a=D(a)?d(a):a,!E(a)||z(b))return a;var c=a.map(function(a){return f(b,a)}).indexOf(!0);return a.slice(0,c===-1?a.length:++c)}}),b.module("a8m.before",[]).filter("before",function(){return function(a,b){return a=D(a)?d(a):a,E(a)?a.slice(0,b?--b:b):a}}),b.module("a8m.chunk-by",["a8m.filter-watcher"]).filter("chunkBy",["filterWatcher",function(a){return function(b,c,d){function e(a,b){for(var c=[];a--;)c[a]=b;return c}function f(a,b,c){return E(a)?a.map(function(a,d,f){return d*=b,a=f.slice(d,d+b),!z(c)&&a.length<b?a.concat(e(b-a.length,c)):a}).slice(0,Math.ceil(a.length/b)):a}return a.isMemoized("chunkBy",arguments)||a.memoize("chunkBy",arguments,this,f(b,c,d))}}]),b.module("a8m.concat",[]).filter("concat",[function(){return function(a,b){if(z(b))return a;if(E(a))return D(b)?a.concat(d(b)):a.concat(b);if(D(a)){var c=d(a);return D(b)?c.concat(d(b)):c.concat(b)}return a}}]),b.module("a8m.contains",[]).filter({contains:["$parse",t],some:["$parse",t]}),b.module("a8m.count-by",[]).filter("countBy",["$parse",function(a){return function(b,c){var e,f={},g=a(c);return b=D(b)?d(b):b,!E(b)||z(c)?b:(b.forEach(function(a){e=g(a),f[e]||(f[e]=0),f[e]++}),f)}}]),b.module("a8m.defaults",[]).filter("defaults",["$parse",function(a){return function(b,c){if(b=D(b)?d(b):b,!E(b)||!D(c))return b;var e=j(c);return b.forEach(function(b){e.forEach(function(d){var e=a(d),f=e.assign;z(e(b))&&f(b,e(c))})}),b}}]),b.module("a8m.every",[]).filter("every",["$parse",function(a){return function(b,c){return b=D(b)?d(b):b,!(E(b)&&!z(c))||b.every(function(b){return D(b)||A(c)?a(c)(b):b===c})}}]),b.module("a8m.filter-by",[]).filter("filterBy",["$parse",function(a){return function(b,e,f,g){var h;return f=B(f)||C(f)?String(f).toLowerCase():c,b=D(b)?d(b):b,!E(b)||z(f)?b:b.filter(function(b){return e.some(function(c){if(~c.indexOf("+")){var d=c.replace(/\s+/g,"").split("+");h=d.map(function(c){return a(c)(b)}).join(" ")}else h=a(c)(b);return!(!B(h)&&!C(h))&&(h=String(h).toLowerCase(),g?h===f:h.contains(f))})})}}]),b.module("a8m.first",[]).filter("first",["$parse",function(a){return function(b){var e,f,g;return b=D(b)?d(b):b,E(b)?(g=Array.prototype.slice.call(arguments,1),e=C(g[0])?g[0]:1,f=C(g[0])?C(g[1])?c:g[1]:g[0],g.length?h(b,e,f?a(f):f):b[0]):b}}]),b.module("a8m.flatten",[]).filter("flatten",function(){return function(a,b){return b=b||!1,a=D(a)?d(a):a,E(a)?b?[].concat.apply([],a):u(a,0):a}}),b.module("a8m.fuzzy-by",[]).filter("fuzzyBy",["$parse",function(a){return function(b,c,e,f){var h,i,j=f||!1;return b=D(b)?d(b):b,!E(b)||z(c)||z(e)?b:(i=a(c),b.filter(function(a){return h=i(a),!!B(h)&&(h=j?h:h.toLowerCase(),e=j?e:e.toLowerCase(),g(h,e)!==!1)}))}}]),b.module("a8m.fuzzy",[]).filter("fuzzy",function(){return function(a,b,c){function e(a,b){var c,d,e=Object.keys(a);return 0<e.filter(function(e){return c=a[e],!!d||!!B(c)&&(c=f?c:c.toLowerCase(),d=g(c,b)!==!1)}).length}var f=c||!1;return a=D(a)?d(a):a,!E(a)||z(b)?a:(b=f?b:b.toLowerCase(),a.filter(function(a){return B(a)?(a=f?a:a.toLowerCase(),g(a,b)!==!1):!!D(a)&&e(a,b)}))}}),b.module("a8m.group-by",["a8m.filter-watcher"]).filter("groupBy",["$parse","filterWatcher",function(a,b){return function(c,d){function e(a,b){var c,d={};return F(a,function(a){c=b(a),d[c]||(d[c]=[]),d[c].push(a)}),d}return!D(c)||z(d)?c:b.isMemoized("groupBy",arguments)||b.memoize("groupBy",arguments,this,e(c,a(d)))}}]),b.module("a8m.is-empty",[]).filter("isEmpty",function(){return function(a){return D(a)?!d(a).length:!a.length}}),b.module("a8m.join",[]).filter("join",function(){return function(a,b){return z(a)||!E(a)?a:(z(b)&&(b=" "),a.join(b))}}),b.module("a8m.last",[]).filter("last",["$parse",function(a){return function(b){var e,f,g,i=H(b);return i=D(i)?d(i):i,E(i)?(g=Array.prototype.slice.call(arguments,1),e=C(g[0])?g[0]:1,f=C(g[0])?C(g[1])?c:g[1]:g[0],g.length?h(i.reverse(),e,f?a(f):f).reverse():i[i.length-1]):i}}]),b.module("a8m.map",[]).filter("map",["$parse",function(a){return function(b,c){return b=D(b)?d(b):b,!E(b)||z(c)?b:b.map(function(b){return a(c)(b)})}}]),b.module("a8m.omit",[]).filter("omit",["$parse",function(a){return function(b,c){return b=D(b)?d(b):b,!E(b)||z(c)?b:b.filter(function(b){return!a(c)(b)})}}]),b.module("a8m.pick",[]).filter("pick",["$parse",function(a){return function(b,c){return b=D(b)?d(b):b,!E(b)||z(c)?b:b.filter(function(b){return a(c)(b)})}}]),b.module("a8m.range",[]).filter("range",function(){return function(a,b,c,d,e){c=c||0,d=d||1;for(var f=0;f<parseInt(b);f++){var g=c+f*d;a.push(A(e)?e(g):g)}return a}}),b.module("a8m.remove-with",[]).filter("removeWith",function(){return function(a,b){return z(b)?a:(a=D(a)?d(a):a,a.filter(function(a){return!f(b,a)}))}}),b.module("a8m.remove",[]).filter("remove",function(){return function(a){a=D(a)?d(a):a;var b=Array.prototype.slice.call(arguments,1);return E(a)?a.filter(function(a){return!b.some(function(b){return I(b,a)})}):a}}),b.module("a8m.reverse",[]).filter("reverse",[function(){return function(a){return a=D(a)?d(a):a,B(a)?a.split("").reverse().join(""):E(a)?a.slice().reverse():a}}]),b.module("a8m.search-field",[]).filter("searchField",["$parse",function(a){return function(b){var c,e;b=D(b)?d(b):b;var f=Array.prototype.slice.call(arguments,1);return E(b)&&f.length?b.map(function(b){return e=f.map(function(d){return(c=a(d))(b)}).join(" "),G(b,{searchField:e})}):b}}]),b.module("a8m.to-array",[]).filter("toArray",function(){return function(a,b){return D(a)?b?Object.keys(a).map(function(b){return G(a[b],{$key:b})}):d(a):a}}),b.module("a8m.unique",[]).filter({unique:["$parse",v],uniq:["$parse",v]}),b.module("a8m.where",[]).filter("where",function(){return function(a,b){return z(b)?a:(a=D(a)?d(a):a,a.filter(function(a){return f(b,a)}))}}),b.module("a8m.xor",[]).filter("xor",["$parse",function(a){return function(b,c,e){function f(b,c){var d=a(e);return c.some(function(a){return e?I(d(a),d(b)):I(a,b)})}return e=e||!1,b=D(b)?d(b):b,c=D(c)?d(c):c,E(b)&&E(c)?b.concat(c).filter(function(a){return!(f(a,b)&&f(a,c))}):b}}]),b.module("a8m.math.abs",[]).filter("abs",function(){return function(a){return Math.abs(a)}}),b.module("a8m.math.byteFmt",[]).filter("byteFmt",function(){var a=[{str:"B",val:1024}];return["KB","MB","GB","TB","PB","EB","ZB","YB"].forEach(function(b,c){a.push({str:b,val:1024*a[c].val})}),function(b,c){if(C(c)&&isFinite(c)&&c%1===0&&c>=0&&C(b)&&isFinite(b)){for(var d=0;d<a.length-1&&b>=a[d].val;)d++;return b/=d>0?a[d-1].val:1,i(b,c)+" "+a[d].str}return"NaN"}}),b.module("a8m.math.degrees",[]).filter("degrees",function(){return function(a,b){if(C(b)&&isFinite(b)&&b%1===0&&b>=0&&C(a)&&isFinite(a)){var c=180*a/Math.PI;return Math.round(c*Math.pow(10,b))/Math.pow(10,b)}return"NaN"}}),b.module("a8m.math.kbFmt",[]).filter("kbFmt",function(){var a=[{str:"KB",val:1024}];return["MB","GB","TB","PB","EB","ZB","YB"].forEach(function(b,c){a.push({str:b,val:1024*a[c].val})}),function(b,c){if(C(c)&&isFinite(c)&&c%1===0&&c>=0&&C(b)&&isFinite(b)){for(var d=0;d<a.length-1&&b>=a[d].val;)d++;return b/=d>0?a[d-1].val:1,i(b,c)+" "+a[d].str}return"NaN"}}),b.module("a8m.math.max",[]).filter("max",["$parse",function(a){function b(b,c){var d=b.map(function(b){return a(c)(b)});return d.indexOf(Math.max.apply(Math,d))}return function(a,c){return E(a)?z(c)?Math.max.apply(Math,a):a[b(a,c)]:a}}]),b.module("a8m.math.min",[]).filter("min",["$parse",function(a){function b(b,c){var d=b.map(function(b){return a(c)(b)});return d.indexOf(Math.min.apply(Math,d))}return function(a,c){return E(a)?z(c)?Math.min.apply(Math,a):a[b(a,c)]:a}}]),b.module("a8m.math.percent",[]).filter("percent",function(){return function(a,b,c){var d=B(a)?Number(a):a;return b=b||100,c=c||!1,!C(d)||isNaN(d)?a:c?Math.round(d/b*100):d/b*100}}),b.module("a8m.math.radians",[]).filter("radians",function(){return function(a,b){if(C(b)&&isFinite(b)&&b%1===0&&b>=0&&C(a)&&isFinite(a)){var c=3.14159265359*a/180;return Math.round(c*Math.pow(10,b))/Math.pow(10,b)}return"NaN"}}),b.module("a8m.math.radix",[]).filter("radix",function(){return function(a,b){var c=/^[2-9]$|^[1-2]\d$|^3[0-6]$/;return C(a)&&c.test(b)?a.toString(b).toUpperCase():a}}),b.module("a8m.math.shortFmt",[]).filter("shortFmt",function(){return function(a,b){return C(b)&&isFinite(b)&&b%1===0&&b>=0&&C(a)&&isFinite(a)?a<1e3?""+a:a<1e6?i(a/1e3,b)+" K":a<1e9?i(a/1e6,b)+" M":i(a/1e9,b)+" B":"NaN"}}),b.module("a8m.math.sum",[]).filter("sum",function(){return function(a,b){return E(a)?a.reduce(function(a,b){return a+b},b||0):a}}),b.module("a8m.ends-with",[]).filter("endsWith",function(){return function(a,b,c){var d,e=c||!1;return!B(a)||z(b)?a:(a=e?a:a.toLowerCase(),d=a.length-b.length,a.indexOf(e?b:b.toLowerCase(),d)!==-1)}}),b.module("a8m.latinize",[]).filter("latinize",[function(){function a(a){return a.replace(/[^\u0000-\u007E]/g,function(a){return c[a]||a})}for(var b=[{base:"A",letters:"AⒶＡÀÁÂẦẤẪẨÃĀĂẰẮẴẲȦǠÄǞẢÅǺǍȀȂẠẬẶḀĄȺⱯ"},{base:"AA",letters:"Ꜳ"},{base:"AE",letters:"ÆǼǢ"},{base:"AO",letters:"Ꜵ"},{base:"AU",letters:"Ꜷ"},{base:"AV",letters:"ꜸꜺ"},{base:"AY",letters:"Ꜽ"},{base:"B",letters:"BⒷＢḂḄḆɃƂƁ"},{base:"C",letters:"CⒸＣĆĈĊČÇḈƇȻꜾ"},{base:"D",letters:"DⒹＤḊĎḌḐḒḎĐƋƊƉꝹ"},{base:"DZ",letters:"ǱǄ"},{base:"Dz",letters:"ǲǅ"},{base:"E",letters:"EⒺＥÈÉÊỀẾỄỂẼĒḔḖĔĖËẺĚȄȆẸỆȨḜĘḘḚƐƎ"},{base:"F",letters:"FⒻＦḞƑꝻ"},{base:"G",letters:"GⒼＧǴĜḠĞĠǦĢǤƓꞠꝽꝾ"},{base:"H",letters:"HⒽＨĤḢḦȞḤḨḪĦⱧⱵꞍ"},{base:"I",letters:"IⒾＩÌÍÎĨĪĬİÏḮỈǏȈȊỊĮḬƗ"},{base:"J",letters:"JⒿＪĴɈ"},{base:"K",letters:"KⓀＫḰǨḲĶḴƘⱩꝀꝂꝄꞢ"},{base:"L",letters:"LⓁＬĿĹĽḶḸĻḼḺŁȽⱢⱠꝈꝆꞀ"},{base:"LJ",letters:"Ǉ"},{base:"Lj",letters:"ǈ"},{base:"M",letters:"MⓂＭḾṀṂⱮƜ"},{base:"N",letters:"NⓃＮǸŃÑṄŇṆŅṊṈȠƝꞐꞤ"},{base:"NJ",letters:"Ǌ"},{base:"Nj",letters:"ǋ"},{base:"O",letters:"OⓄＯÒÓÔỒỐỖỔÕṌȬṎŌṐṒŎȮȰÖȪỎŐǑȌȎƠỜỚỠỞỢỌỘǪǬØǾƆƟꝊꝌ"},{base:"OI",letters:"Ƣ"},{base:"OO",letters:"Ꝏ"},{base:"OU",letters:"Ȣ"},{base:"OE",letters:"Œ"},{base:"oe",letters:"œ"},{base:"P",letters:"PⓅＰṔṖƤⱣꝐꝒꝔ"},{base:"Q",letters:"QⓆＱꝖꝘɊ"},{base:"R",letters:"RⓇＲŔṘŘȐȒṚṜŖṞɌⱤꝚꞦꞂ"},{base:"S",letters:"SⓈＳẞŚṤŜṠŠṦṢṨȘŞⱾꞨꞄ"},{base:"T",letters:"TⓉＴṪŤṬȚŢṰṮŦƬƮȾꞆ"},{base:"TZ",letters:"Ꜩ"},{base:"U",letters:"UⓊＵÙÚÛŨṸŪṺŬÜǛǗǕǙỦŮŰǓȔȖƯỪỨỮỬỰỤṲŲṶṴɄ"},{base:"V",letters:"VⓋＶṼṾƲꝞɅ"},{base:"VY",letters:"Ꝡ"},{base:"W",letters:"WⓌＷẀẂŴẆẄẈⱲ"},{base:"X",letters:"XⓍＸẊẌ"},{base:"Y",letters:"YⓎＹỲÝŶỸȲẎŸỶỴƳɎỾ"},{base:"Z",letters:"ZⓏＺŹẐŻŽẒẔƵȤⱿⱫꝢ"},{base:"a",letters:"aⓐａẚàáâầấẫẩãāăằắẵẳȧǡäǟảåǻǎȁȃạậặḁąⱥɐ"},{base:"aa",letters:"ꜳ"},{base:"ae",letters:"æǽǣ"},{base:"ao",letters:"ꜵ"},{base:"au",letters:"ꜷ"},{base:"av",letters:"ꜹꜻ"},{base:"ay",letters:"ꜽ"},{base:"b",letters:"bⓑｂḃḅḇƀƃɓ"},{base:"c",letters:"cⓒｃćĉċčçḉƈȼꜿↄ"},{base:"d",letters:"dⓓｄḋďḍḑḓḏđƌɖɗꝺ"},{base:"dz",letters:"ǳǆ"},{base:"e",letters:"eⓔｅèéêềếễểẽēḕḗĕėëẻěȅȇẹệȩḝęḙḛɇɛǝ"},{base:"f",letters:"fⓕｆḟƒꝼ"},{base:"g",letters:"gⓖｇǵĝḡğġǧģǥɠꞡᵹꝿ"},{base:"h",letters:"hⓗｈĥḣḧȟḥḩḫẖħⱨⱶɥ"},{base:"hv",letters:"ƕ"},{base:"i",letters:"iⓘｉìíîĩīĭïḯỉǐȉȋịįḭɨı"},{base:"j",letters:"jⓙｊĵǰɉ"},{base:"k",letters:"kⓚｋḱǩḳķḵƙⱪꝁꝃꝅꞣ"},{base:"l",letters:"lⓛｌŀĺľḷḹļḽḻſłƚɫⱡꝉꞁꝇ"},{base:"lj",letters:"ǉ"},{base:"m",letters:"mⓜｍḿṁṃɱɯ"},{base:"n",letters:"nⓝｎǹńñṅňṇņṋṉƞɲŉꞑꞥ"},{base:"nj",letters:"ǌ"},{base:"o",letters:"oⓞｏòóôồốỗổõṍȭṏōṑṓŏȯȱöȫỏőǒȍȏơờớỡởợọộǫǭøǿɔꝋꝍɵ"},{base:"oi",letters:"ƣ"},{base:"ou",letters:"ȣ"},{base:"oo",letters:"ꝏ"},{base:"p",letters:"pⓟｐṕṗƥᵽꝑꝓꝕ"},{base:"q",letters:"qⓠｑɋꝗꝙ"},{base:"r",letters:"rⓡｒŕṙřȑȓṛṝŗṟɍɽꝛꞧꞃ"},{base:"s",letters:"sⓢｓßśṥŝṡšṧṣṩșşȿꞩꞅẛ"},{base:"t",letters:"tⓣｔṫẗťṭțţṱṯŧƭʈⱦꞇ"},{base:"tz",letters:"ꜩ"},{base:"u",letters:"uⓤｕùúûũṹūṻŭüǜǘǖǚủůűǔȕȗưừứữửựụṳųṷṵʉ"},{base:"v",letters:"vⓥｖṽṿʋꝟʌ"},{base:"vy",letters:"ꝡ"},{base:"w",letters:"wⓦｗẁẃŵẇẅẘẉⱳ"},{base:"x",letters:"xⓧｘẋẍ"},{base:"y",letters:"yⓨｙỳýŷỹȳẏÿỷẙỵƴɏỿ"},{base:"z",letters:"zⓩｚźẑżžẓẕƶȥɀⱬꝣ"}],c={},d=0;d<b.length;d++)for(var e=b[d].letters.split(""),f=0;f<e.length;f++)c[e[f]]=b[d].base;return function(b){return B(b)?a(b):b}}]),b.module("a8m.ltrim",[]).filter("ltrim",function(){return function(a,b){var c=b||"\\s";return B(a)?a.replace(new RegExp("^"+c+"+"),""):a}}),b.module("a8m.match",[]).filter("match",function(){return function(a,b,c){var d=new RegExp(b,c);return B(a)?a.match(d):null}}),b.module("a8m.phoneUS",[]).filter("phoneUS",function(){return function(a){return a+="","("+a.slice(0,3)+") "+a.slice(3,6)+"-"+a.slice(6)}}),b.module("a8m.repeat",[]).filter("repeat",[function(){return function(a,b,c){var d=~~b;return B(a)&&d?w(a,--b,c||""):a}}]),b.module("a8m.rtrim",[]).filter("rtrim",function(){return function(a,b){var c=b||"\\s";return B(a)?a.replace(new RegExp(c+"+$"),""):a}}),b.module("a8m.slugify",[]).filter("slugify",[function(){return function(a,b){var c=z(b)?"-":b;return B(a)?a.toLowerCase().replace(/\s+/g,c):a}}]),b.module("a8m.split",[]).filter("split",function(){function a(a){return a.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,"\\$&")}return function(b,c,d){var f,g,h,i;return z(b)||!B(b)?null:(z(c)&&(c=""),isNaN(d)&&(d=0),f=new RegExp(a(c),"g"),g=b.match(f),e(g)||d>=g.length?[b]:0===d?b.split(c):(h=b.split(c),i=h.splice(0,d+1),h.unshift(i.join(c)),h))}}),b.module("a8m.starts-with",[]).filter("startsWith",function(){return function(a,b,c){var d=c||!1;return!B(a)||z(b)?a:(a=d?a:a.toLowerCase(),!a.indexOf(d?b:b.toLowerCase()))}}),b.module("a8m.stringular",[]).filter("stringular",function(){return function(a){var b=Array.prototype.slice.call(arguments,1);return a.replace(/{(\d+)}/g,function(a,c){return z(b[c])?a:b[c]})}}),b.module("a8m.strip-tags",[]).filter("stripTags",function(){return function(a){return B(a)?a.replace(/<\S[^><]*>/g,""):a}}),b.module("a8m.test",[]).filter("test",function(){return function(a,b,c){var d=new RegExp(b,c);return B(a)?d.test(a):a}}),b.module("a8m.trim",[]).filter("trim",function(){return function(a,b){var c=b||"\\s";return B(a)?a.replace(new RegExp("^"+c+"+|"+c+"+$","g"),""):a}}),b.module("a8m.truncate",[]).filter("truncate",function(){return function(a,b,c,d){return b=z(b)?a.length:b,d=d||!1,c=c||"",!B(a)||a.length<=b?a:a.substring(0,d?a.indexOf(" ",b)===-1?a.length:a.indexOf(" ",b):b)+c}}),b.module("a8m.ucfirst",[]).filter({ucfirst:x,titleize:x}),b.module("a8m.uri-component-encode",[]).filter("uriComponentEncode",["$window",function(a){return function(b){return B(b)?a.encodeURIComponent(b):b}}]),b.module("a8m.uri-encode",[]).filter("uriEncode",["$window",function(a){return function(b){return B(b)?a.encodeURI(b):b}}]),b.module("a8m.wrap",[]).filter("wrap",function(){return function(a,b,c){return B(a)&&y(b)?[b,a,c||b].join(""):a}}),b.module("a8m.filter-watcher",[]).provider("filterWatcher",function(){this.$get=["$window","$rootScope",function(a,b){function c(b,c){function d(){var b=[];return function(c,d){if(D(d)&&!e(d)){if(~b.indexOf(d))return"[Circular]";b.push(d)}return a==d?"$WINDOW":a.document==d?"$DOCUMENT":k(d)?"$SCOPE":d}}return[b,JSON.stringify(c,d())].join("#").replace(/"/g,"")}function d(a){var b=a.targetScope.$id;F(l[b],function(a){delete j[a]}),delete l[b]}function f(){m(function(){b.$$phase||(j={})},2e3)}function g(a,b){var c=a.$id;return z(l[c])&&(a.$on("$destroy",d),l[c]=[]),l[c].push(b)}function h(a,b){var d=c(a,b);return j[d]}function i(a,b,d,e){var h=c(a,b);return j[h]=e,k(d)?g(d,h):f(),e}var j={},l={},m=a.setTimeout;return{isMemoized:h,memoize:i}}]}),b.module("angular.filter",["a8m.ucfirst","a8m.uri-encode","a8m.uri-component-encode","a8m.slugify","a8m.latinize","a8m.strip-tags","a8m.stringular","a8m.truncate","a8m.starts-with","a8m.ends-with","a8m.wrap","a8m.trim","a8m.ltrim","a8m.rtrim","a8m.repeat","a8m.test","a8m.match","a8m.split","a8m.to-array","a8m.concat","a8m.contains","a8m.unique","a8m.is-empty","a8m.after","a8m.after-where","a8m.before","a8m.before-where","a8m.defaults","a8m.where","a8m.reverse","a8m.remove","a8m.remove-with","a8m.group-by","a8m.count-by","a8m.chunk-by","a8m.search-field","a8m.fuzzy-by","a8m.fuzzy","a8m.omit","a8m.pick","a8m.every","a8m.filter-by","a8m.xor","a8m.map","a8m.first","a8m.last","a8m.flatten","a8m.join","a8m.range","a8m.math.max","a8m.math.min","a8m.math.abs","a8m.math.percent","a8m.math.radix","a8m.math.sum","a8m.math.degrees","a8m.math.radians","a8m.math.byteFmt","a8m.math.kbFmt","a8m.math.shortFmt","a8m.angular","a8m.conditions","a8m.is-null","a8m.filter-watcher"])}(window,window.angular);



/*!
angular-xeditable - 0.5.0
Edit-in-place for angular.js
Build date: 2016-10-27 
*/
angular.module("xeditable",[]).value("editableOptions",{theme:"default",icon_set:"default",buttons:"right",blurElem:"cancel",blurForm:"ignore",activate:"focus",isDisabled:!1,activationEvent:"click",submitButtonTitle:"Submit",submitButtonAriaLabel:"Submit",cancelButtonTitle:"Cancel",cancelButtonAriaLabel:"Cancel",clearButtonTitle:"Clear",clearButtonAriaLabel:"Clear",displayClearButton:!1}),angular.module("xeditable").directive("editableBsdate",["editableDirectiveFactory",function(a){return a({directiveName:"editableBsdate",inputTpl:"<div></div>",render:function(){this.parent.render.call(this);var a=angular.element('<input type="text" class="form-control" data-ng-model="$parent.$data"/>');a.attr("uib-datepicker-popup",this.attrs.eDatepickerPopupXEditable||"yyyy/MM/dd"),a.attr("is-open",this.attrs.eIsOpen),a.attr("date-disabled",this.attrs.eDateDisabled),a.attr("uib-datepicker-popup",this.attrs.eDatepickerPopup),a.attr("year-range",this.attrs.eYearRange||20),a.attr("show-button-bar",this.attrs.eShowButtonBar||!0),a.attr("current-text",this.attrs.eCurrentText||"Today"),a.attr("clear-text",this.attrs.eClearText||"Clear"),a.attr("close-text",this.attrs.eCloseText||"Done"),a.attr("close-on-date-selection",this.attrs.eCloseOnDateSelection||!0),a.attr("datepicker-append-to-body",this.attrs.eDatePickerAppendToBody||!1),a.attr("date-disabled",this.attrs.eDateDisabled),a.attr("name",this.attrs.eName),a.attr("on-open-focus",this.attrs.eOnOpenFocus||!0),a.attr("ng-readonly",this.attrs.eReadonly||!1),this.attrs.eNgChange&&(a.attr("ng-change",this.attrs.eNgChange),this.inputEl.removeAttr("ng-change")),this.attrs.eStyle&&(a.attr("style",this.attrs.eStyle),this.inputEl.removeAttr("style")),this.scope.dateOptions={formatDay:this.attrs.eFormatDay||"dd",formatMonth:this.attrs.eFormatMonth||"MMMM",formatYear:this.attrs.eFormatYear||"yyyy",formatDayHeader:this.attrs.eFormatDayHeader||"EEE",formatDayTitle:this.attrs.eFormatDayTitle||"MMMM yyyy",formatMonthTitle:this.attrs.eFormatMonthTitle||"yyyy",showWeeks:this.attrs.eShowWeeks?"true"===this.attrs.eShowWeeks.toLowerCase():!0,startingDay:this.attrs.eStartingDay||0,minMode:this.attrs.eMinMode||"day",maxMode:this.attrs.eMaxMode||"year",initDate:this.scope.$eval(this.attrs.eInitDate)||new Date,datepickerMode:this.attrs.eDatepickerMode||"day",maxDate:this.scope.$eval(this.attrs.eMaxDate)||null,minDate:this.scope.$eval(this.attrs.eMinDate)||null};var b=angular.isDefined(this.attrs.eShowCalendarButton)?this.attrs.eShowCalendarButton:"true";if("true"===b){var c=angular.element('<button type="button" class="btn btn-default"><i class="glyphicon glyphicon-calendar"></i></button>'),d=angular.element('<span class="input-group-btn"></span>');c.attr("ng-click",this.attrs.eNgClick),d.append(c),this.inputEl.append(d)}else a.attr("ng-click",this.attrs.eNgClick);a.attr("datepicker-options","dateOptions"),this.inputEl.prepend(a),this.inputEl.removeAttr("class"),this.inputEl.removeAttr("ng-click"),this.inputEl.removeAttr("is-open"),this.inputEl.removeAttr("init-date"),this.inputEl.removeAttr("datepicker-popup"),this.inputEl.removeAttr("required"),this.inputEl.removeAttr("ng-model"),this.inputEl.removeAttr("date-picker-append-to-body"),this.inputEl.removeAttr("name"),this.inputEl.attr("class","input-group")}})}]),angular.module("xeditable").directive("editableBstime",["editableDirectiveFactory",function(a){return a({directiveName:"editableBstime",inputTpl:"<uib-timepicker></uib-timepicker>",render:function(){this.parent.render.call(this);var a=angular.element('<div class="well well-small" style="display:inline-block;"></div>');a.attr("ng-model",this.inputEl.attr("ng-model")),this.inputEl.removeAttr("ng-model"),this.attrs.eNgChange&&(a.attr("ng-change",this.inputEl.attr("ng-change")),this.inputEl.removeAttr("ng-change")),this.inputEl.wrap(a)}})}]),angular.module("xeditable").directive("editableCheckbox",["editableDirectiveFactory",function(a){return a({directiveName:"editableCheckbox",inputTpl:'<input type="checkbox">',render:function(){this.parent.render.call(this),this.attrs.eTitle&&(this.inputEl.wrap("<label></label>"),this.inputEl.parent().append("<span>"+this.attrs.eTitle+"</span>"))},autosubmit:function(){var a=this;a.inputEl.bind("change",function(){setTimeout(function(){a.scope.$apply(function(){a.scope.$form.$submit()})},500)})}})}]),angular.module("xeditable").directive("editableChecklist",["editableDirectiveFactory","editableNgOptionsParser",function(a,b){return a({directiveName:"editableChecklist",inputTpl:"<span></span>",useCopy:!0,render:function(){this.parent.render.call(this);var a=b(this.attrs.eNgOptions),c="",d="";this.attrs.eNgChange&&(c=' ng-change="'+this.attrs.eNgChange+'"'),this.attrs.eChecklistComparator&&(d=' checklist-comparator="'+this.attrs.eChecklistComparator+'"');var e='<label ng-repeat="'+a.ngRepeat+'"><input type="checkbox" checklist-model="$parent.$parent.$data" checklist-value="'+a.locals.valueFn+'"'+c+d+'><span ng-bind="'+a.locals.displayFn+'"></span></label>';this.inputEl.removeAttr("ng-model"),this.inputEl.removeAttr("ng-options"),this.inputEl.removeAttr("ng-change"),this.inputEl.removeAttr("checklist-comparator"),this.inputEl.html(e)}})}]),angular.module("xeditable").directive("editableCombodate",["editableDirectiveFactory","editableCombodate",function(a,b){return a({directiveName:"editableCombodate",inputTpl:'<input type="text">',render:function(){this.parent.render.call(this);var a={value:new Date(this.scope.$data)},c=this;angular.forEach(["format","template","minYear","maxYear","yearDescending","minuteStep","secondStep","firstItem","errorClass","customClass","roundTime","smartDays"],function(b){var d="e"+b.charAt(0).toUpperCase()+b.slice(1);d in c.attrs&&(a[b]=c.attrs[d])});var d=b.getInstance(this.inputEl,a);d.$widget.find("select").bind("change",function(a){c.scope.$data=new Date(d.getValue()).toISOString()})}})}]),function(){var a=function(a){return a.toLowerCase().replace(/-(.)/g,function(a,b){return b.toUpperCase()})},b="text|password|email|tel|number|url|search|color|date|datetime|datetime-local|time|month|week|file".split("|");angular.forEach(b,function(b){var c=a("editable-"+b);angular.module("xeditable").directive(c,["editableDirectiveFactory",function(a){return a({directiveName:c,inputTpl:'<input type="'+b+'">',render:function(){if(this.parent.render.call(this),this.attrs.eInputgroupleft||this.attrs.eInputgroupright){if(this.inputEl.wrap('<div class="input-group"></div>'),this.attrs.eInputgroupleft){var a=angular.element('<span class="input-group-addon">'+this.attrs.eInputgroupleft+"</span>");this.inputEl.parent().prepend(a)}if(this.attrs.eInputgroupright){var b=angular.element('<span class="input-group-addon">'+this.attrs.eInputgroupright+"</span>");this.inputEl.parent().append(b)}}if(this.attrs.eLabel){var c=angular.element("<label>"+this.attrs.eLabel+"</label>");this.attrs.eInputgroupleft||this.attrs.eInputgroupright?this.inputEl.parent().parent().prepend(c):this.inputEl.parent().prepend(c)}this.attrs.eFormclass&&this.editorEl.addClass(this.attrs.eFormclass)}})}])}),angular.module("xeditable").directive("editableRange",["editableDirectiveFactory","$interpolate",function(a,b){return a({directiveName:"editableRange",inputTpl:'<input type="range" id="range" name="range">',render:function(){this.parent.render.call(this),this.inputEl.after("<output>"+b.startSymbol()+"$data"+b.endSymbol()+"</output>")}})}])}(),angular.module("xeditable").directive("editableTagsInput",["editableDirectiveFactory","editableUtils",function(a,b){var c=a({directiveName:"editableTagsInput",inputTpl:"<tags-input></tags-input>",render:function(){this.parent.render.call(this),this.inputEl.append(b.rename("auto-complete",this.attrs.$autoCompleteElement)),this.inputEl.removeAttr("ng-model"),this.inputEl.attr("ng-model","$parent.$data")}}),d=c.link;return c.link=function(a,b,c,e){var f=b.find("editable-tags-input-auto-complete");return c.$autoCompleteElement=f.clone(),f.remove(),d(a,b,c,e)},c}]),angular.module("xeditable").directive("editableRadiolist",["editableDirectiveFactory","editableNgOptionsParser","$interpolate",function(a,b,c){return a({directiveName:"editableRadiolist",inputTpl:"<span></span>",render:function(){this.parent.render.call(this);var a=b(this.attrs.eNgOptions),d="";this.attrs.eNgChange&&(d='ng-change="'+this.attrs.eNgChange+'"');var e='<label data-ng-repeat="'+a.ngRepeat+'"><input type="radio" data-ng-disabled="::'+this.attrs.eNgDisabled+'" data-ng-model="$parent.$parent.$data" data-ng-value="'+c.startSymbol()+"::"+a.locals.valueFn+c.endSymbol()+'"'+d+'><span data-ng-bind="::'+a.locals.displayFn+'"></span></label>';this.inputEl.removeAttr("ng-model"),this.inputEl.removeAttr("ng-options"),this.inputEl.removeAttr("ng-change"),this.inputEl.html(e)},autosubmit:function(){var a=this;a.inputEl.bind("change",function(){setTimeout(function(){a.scope.$apply(function(){a.scope.$form.$submit()})},500)})}})}]),angular.module("xeditable").directive("editableSelect",["editableDirectiveFactory",function(a){return a({directiveName:"editableSelect",inputTpl:"<select></select>",render:function(){if(this.parent.render.call(this),this.attrs.ePlaceholder){var a=angular.element('<option value="">'+this.attrs.ePlaceholder+"</option>");this.inputEl.append(a)}},autosubmit:function(){var a=this;a.inputEl.bind("change",function(){a.scope.$apply(function(){a.scope.$form.$submit()})})}})}]),angular.module("xeditable").directive("editableTextarea",["editableDirectiveFactory",function(a){return a({directiveName:"editableTextarea",inputTpl:"<textarea></textarea>",addListeners:function(){var a=this;a.parent.addListeners.call(a),a.single&&"no"!==a.buttons&&a.autosubmit()},autosubmit:function(){var a=this;a.inputEl.bind("keydown",function(b){(b.ctrlKey||b.metaKey)&&13===b.keyCode&&a.scope.$apply(function(){a.scope.$form.$submit()})})}})}]),angular.module("xeditable").directive("editableUiSelect",["editableDirectiveFactory","editableUtils",function(a,b){var c=a({directiveName:"editableUiSelect",inputTpl:"<ui-select></ui-select>",render:function(){this.parent.render.call(this),this.inputEl.append(b.rename("ui-select-match",this.attrs.$matchElement)),this.inputEl.append(b.rename("ui-select-choices",this.attrs.$choicesElement)),this.inputEl.removeAttr("ng-model"),this.inputEl.attr("ng-model","$parent.$parent.$data")}}),d=c.link;return c.link=function(a,b,c,e){var f=b.find("editable-ui-select-match"),g=b.find("editable-ui-select-choices");return c.$matchElement=f.clone(),c.$choicesElement=g.clone(),f.remove(),g.remove(),d(a,b,c,e)},c}]),angular.module("xeditable").factory("editableController",["$q","editableUtils",function(a,b){function c(a,c,d,e,f,g,h,i,j,k){var l,m,n=this;n.scope=a,n.elem=d,n.attrs=c,n.inputEl=null,n.editorEl=null,n.single=!0,n.error="",n.theme=f[c.editableTheme]||f[h.theme]||f["default"],n.parent={},n.icon_set="default"===h.icon_set?g["default"][h.theme]:g.external[h.icon_set],n.inputTpl="",n.directiveName="",n.useCopy=!1,n.single=null,n.buttons="right",n.init=function(b){if(n.single=b,n.name=c.eName||c[n.directiveName],!c[n.directiveName])throw"You should provide value for `"+n.directiveName+"` in editable element!";l=e(c[n.directiveName]),n.single?n.buttons=n.attrs.buttons||h.buttons:n.buttons="no",c.eName&&n.scope.$watch("$data",function(a){n.scope.$form.$data[c.eName]=a}),c.onshow&&(n.onshow=function(){return n.catchError(e(c.onshow)(a))}),c.onhide&&(n.onhide=function(){return e(c.onhide)(a)}),c.oncancel&&(n.oncancel=function(){return e(c.oncancel)(a)}),c.onbeforesave&&(n.onbeforesave=function(){return n.catchError(e(c.onbeforesave)(a))}),c.onaftersave&&(n.onaftersave=function(){return n.catchError(e(c.onaftersave)(a))}),a.$parent.$watch(c[n.directiveName],function(a,b){n.setLocalValue(),n.handleEmpty()})},n.render=function(){var a=n.theme;n.inputEl=angular.element(n.inputTpl),n.controlsEl=angular.element(a.controlsTpl),n.controlsEl.append(n.inputEl),"no"!==n.buttons&&(n.buttonsEl=angular.element(a.buttonsTpl),n.submitEl=angular.element(a.submitTpl),n.resetEl=angular.element(a.resetTpl),n.cancelEl=angular.element(a.cancelTpl),n.submitEl.attr("title",h.submitButtonTitle),n.submitEl.attr("aria-label",h.submitButtonAriaLabel),n.cancelEl.attr("title",h.cancelButtonTitle),n.cancelEl.attr("aria-label",h.cancelButtonAriaLabel),n.resetEl.attr("title",h.clearButtonTitle),n.resetEl.attr("aria-label",h.clearButtonAriaLabel),n.icon_set&&(n.submitEl.find("span").addClass(n.icon_set.ok),n.cancelEl.find("span").addClass(n.icon_set.cancel),n.resetEl.find("span").addClass(n.icon_set.clear)),n.buttonsEl.append(n.submitEl).append(n.cancelEl),h.displayClearButton&&n.buttonsEl.append(n.resetEl),n.controlsEl.append(n.buttonsEl),n.inputEl.addClass("editable-has-buttons")),n.errorEl=angular.element(a.errorTpl),n.controlsEl.append(n.errorEl),n.editorEl=angular.element(n.single?a.formTpl:a.noformTpl),n.editorEl.append(n.controlsEl);for(var d in c.$attr)if(!(d.length<=1)){var e=!1,f=d.substring(1,2);if("e"===d.substring(0,1)&&f===f.toUpperCase()&&(e=d.substring(1),"Form"!==e&&"NgSubmit"!==e)){var g=e.substring(0,1),i=e.substring(1,2);e=i===i.toUpperCase()&&g===g.toUpperCase()?g.toLowerCase()+"-"+b.camelToDash(e.substring(1)):g.toLowerCase()+b.camelToDash(e.substring(1));var j="value"!==e&&""===c[d]?e:c[d];n.inputEl.attr(e,j)}}n.inputEl.addClass("editable-input"),n.inputEl.attr("ng-model","$parent.$data"),n.editorEl.addClass(b.camelToDash(n.directiveName)),n.single&&(n.editorEl.attr("editable-form","$form"),n.editorEl.attr("blur",n.attrs.blur||("no"===n.buttons?"cancel":h.blurElem))),angular.isFunction(a.postrender)&&a.postrender.call(n)},n.setLocalValue=function(){n.scope.$data=n.useCopy?angular.copy(l(a.$parent)):l(a.$parent)};var o=null;n.show=function(){return n.setLocalValue(),n.render(),d.after(n.editorEl),o=a.$new(),j(n.editorEl)(o),n.addListeners(),d.addClass("editable-hide"),n.onshow()},n.hide=function(){return o.$destroy(),n.controlsEl.remove(),n.editorEl.remove(),d.removeClass("editable-hide"),n.onhide()},n.cancel=function(){n.oncancel()},n.addListeners=function(){n.inputEl.bind("keyup",function(a){if(n.single)switch(a.keyCode){case 27:n.scope.$apply(function(){n.scope.$form.$cancel()})}}),n.single&&"no"===n.buttons&&n.autosubmit(),n.editorEl.bind("click",function(a){a.which&&1!==a.which||n.scope.$form.$visible&&(n.scope.$form._clicked=!0)})},n.setWaiting=function(a){a?(m=!n.inputEl.attr("disabled")&&!n.inputEl.attr("ng-disabled")&&!n.inputEl.attr("ng-enabled"),m&&(n.inputEl.attr("disabled","disabled"),n.buttonsEl&&n.buttonsEl.find("button").attr("disabled","disabled"))):m&&(n.inputEl.removeAttr("disabled"),n.buttonsEl&&n.buttonsEl.find("button").removeAttr("disabled"))},n.activate=function(a,b){setTimeout(function(){var c=n.inputEl[0];"focus"===h.activate&&c.focus?(a&&(b=b||a,c.onfocus=function(){var c=this;setTimeout(function(){c.setSelectionRange(a,b)})}),"editableRadiolist"==n.directiveName||"editableChecklist"==n.directiveName||"editableBsdate"==n.directiveName||"editableTagsInput"==n.directiveName?c.querySelector(".ng-pristine").focus():c.focus()):"select"===h.activate&&(c.select?c.select():c.focus&&c.focus())},0)},n.setError=function(b){angular.isObject(b)||(a.$error=b,n.error=b)},n.catchError=function(a,b){return angular.isObject(a)&&b!==!0?k.when(a).then(angular.bind(this,function(a){this.catchError(a,!0)}),angular.bind(this,function(a){this.catchError(a,!0)})):b&&angular.isObject(a)&&a.status&&200!==a.status&&a.data&&angular.isString(a.data)?(this.setError(a.data),a=a.data):angular.isString(a)&&this.setError(a),a},n.save=function(){l.assign(a.$parent,n.useCopy?angular.copy(n.scope.$data):n.scope.$data)},n.handleEmpty=function(){var b=l(a.$parent),c=null===b||void 0===b||""===b||angular.isArray(b)&&0===b.length;d.toggleClass("editable-empty",c)},n.autosubmit=angular.noop,n.onshow=angular.noop,n.onhide=angular.noop,n.oncancel=angular.noop,n.onbeforesave=angular.noop,n.onaftersave=angular.noop}return c.$inject=["$scope","$attrs","$element","$parse","editableThemes","editableIcons","editableOptions","$rootScope","$compile","$q"],c}]),angular.module("xeditable").factory("editableDirectiveFactory",["$parse","$compile","editableThemes","$rootScope","$document","editableController","editableFormController","editableOptions",function(a,b,c,d,e,f,g,h){return function(b){return{restrict:"A",scope:!0,require:[b.directiveName,"?^form"],controller:f,link:function(c,f,i,j){var k,l=j[0],m=!1;if(j[1])k=j[1],m=void 0===i.eSingle;else if(i.eForm){var n=a(i.eForm)(c);if(n)k=n,m=!0;else if(f&&"function"==typeof f.parents&&f.parents().last().find("form[name="+i.eForm+"]").length)k=null,m=!0;else for(var o=0;o<e[0].forms.length;o++)if(e[0].forms[o].name===i.eForm){k=null,m=!0;break}}angular.forEach(b,function(a,b){void 0!==l[b]&&(l.parent[b]=l[b])}),angular.extend(l,b);var p=function(){return angular.isDefined(i.editDisabled)?c.$eval(i.editDisabled):h.isDisabled};if(l.init(!m),c.$editable=l,f.addClass("editable"),m)if(k){if(c.$form=k,!c.$form.$addEditable)throw"Form with editable elements should have `editable-form` attribute.";c.$form.$addEditable(l)}else d.$$editableBuffer=d.$$editableBuffer||{},d.$$editableBuffer[i.eForm]=d.$$editableBuffer[i.eForm]||[],d.$$editableBuffer[i.eForm].push(l),c.$form=null;else c.$form=g(),c.$form.$addEditable(l),i.eForm&&(a(i.eForm).assign||angular.noop)(c.$parent,c.$form),(!i.eForm||i.eClickable)&&(f.addClass("editable-click"),f.bind(h.activationEvent,function(a){a.preventDefault(),a.editable=l,p()||c.$apply(function(){c.$form.$show()})}))}}}}]),angular.module("xeditable").factory("editableFormController",["$parse","$document","$rootScope","editablePromiseCollection","editableUtils",function(a,b,c,d,e){var f=[],g=function(a,b){if(b==a)return!0;for(var c=b.parentNode;null!==c;){if(c==a)return!0;c=c.parentNode}return!1},h=function(a,b){var c=!0,d=a.$editables;return angular.forEach(d,function(a){var d=a.editorEl[0];g(d,b.target)&&(c=!1)}),c};b.bind("click",function(a){if(!a.which||1===a.which){for(var b=[],d=[],e=0;e<f.length;e++)f[e]._clicked?f[e]._clicked=!1:f[e].$waiting||("cancel"===f[e]._blur&&h(f[e],a)&&b.push(f[e]),"submit"===f[e]._blur&&h(f[e],a)&&d.push(f[e]));(b.length||d.length)&&c.$apply(function(){angular.forEach(b,function(a){a.$cancel()}),angular.forEach(d,function(a){a.$submit()})})}}),c.$on("closeEdit",function(){for(var a=0;a<f.length;a++)f[a].$hide()});var i={$addEditable:function(a){this.$editables.push(a),a.elem.bind("$destroy",angular.bind(this,this.$removeEditable,a)),a.scope.$form||(a.scope.$form=this),this.$visible&&a.catchError(a.show()),a.catchError(a.setWaiting(this.$waiting))},$removeEditable:function(a){for(var b=0;b<this.$editables.length;b++)if(this.$editables[b]===a)return void this.$editables.splice(b,1)},$show:function(){if(!this.$visible){this.$visible=!0;var a=d();a.when(this.$onshow()),this.$setError(null,""),angular.forEach(this.$editables,function(b){a.when(b.show())}),a.then({onWait:angular.bind(this,this.$setWaiting),onTrue:angular.bind(this,this.$activate),onFalse:angular.bind(this,this.$activate),onString:angular.bind(this,this.$activate)}),setTimeout(angular.bind(this,function(){this._clicked=!1,-1===e.indexOf(f,this)&&f.push(this)}),0)}},$activate:function(a){var b;if(this.$editables.length){if(angular.isString(a))for(b=0;b<this.$editables.length;b++)if(this.$editables[b].name===a)return void this.$editables[b].activate();for(b=0;b<this.$editables.length;b++)if(this.$editables[b].error)return void this.$editables[b].activate();this.$editables[0].activate(this.$editables[0].elem[0].selectionStart,this.$editables[0].elem[0].selectionEnd)}},$hide:function(){this.$visible&&(this.$visible=!1,this.$onhide(),angular.forEach(this.$editables,function(a){a.hide()}),e.arrayRemove(f,this))},$cancel:function(){this.$visible&&(this.$oncancel(),angular.forEach(this.$editables,function(a){a.cancel()}),this.$hide())},$setWaiting:function(a){this.$waiting=!!a,angular.forEach(this.$editables,function(b){b.setWaiting(!!a)})},$setError:function(a,b){angular.forEach(this.$editables,function(c){a&&c.name!==a||c.setError(b)})},$submit:function(){function a(a){var b=d();b.when(this.$onbeforesave()),b.then({onWait:angular.bind(this,this.$setWaiting),onTrue:a?angular.bind(this,this.$save):angular.bind(this,this.$hide),onFalse:angular.bind(this,this.$hide),onString:angular.bind(this,this.$activate)})}if(!this.$waiting){this.$setError(null,"");var b=d();angular.forEach(this.$editables,function(a){b.when(a.onbeforesave())}),b.then({onWait:angular.bind(this,this.$setWaiting),onTrue:angular.bind(this,a,!0),onFalse:angular.bind(this,a,!1),onString:angular.bind(this,this.$activate)})}},$save:function(){angular.forEach(this.$editables,function(a){a.save()});var a=d();a.when(this.$onaftersave()),angular.forEach(this.$editables,function(b){a.when(b.onaftersave())}),a.then({onWait:angular.bind(this,this.$setWaiting),onTrue:angular.bind(this,this.$hide),onFalse:angular.bind(this,this.$hide),onString:angular.bind(this,this.$activate)})},$onshow:angular.noop,$oncancel:angular.noop,$onhide:angular.noop,$onbeforesave:angular.noop,$onaftersave:angular.noop};return function(){return angular.extend({$editables:[],$visible:!1,$waiting:!1,$data:{},_clicked:!1,_blur:null},i)}}]),angular.module("xeditable").directive("editableForm",["$rootScope","$parse","editableFormController","editableOptions",function(a,b,c,d){return{restrict:"A",require:["form"],compile:function(){return{pre:function(b,d,e,f){var g,h=f[0];e.editableForm?b[e.editableForm]&&b[e.editableForm].$show?(g=b[e.editableForm],angular.extend(h,g)):(g=c(),b[e.editableForm]=g,angular.extend(g,h)):(g=c(),angular.extend(h,g));var i=a.$$editableBuffer,j=h.$name;j&&i&&i[j]&&(angular.forEach(i[j],function(a){g.$addEditable(a)}),delete i[j])},post:function(a,c,e,f){var g;g=e.editableForm&&a[e.editableForm]&&a[e.editableForm].$show?a[e.editableForm]:f[0],e.onshow&&(g.$onshow=angular.bind(g,b(e.onshow),a)),e.onhide&&(g.$onhide=angular.bind(g,b(e.onhide),a)),e.oncancel&&(g.$oncancel=angular.bind(g,b(e.oncancel),a)),e.shown&&b(e.shown)(a)&&g.$show(),g._blur=e.blur||d.blurForm,e.ngSubmit||e.submit||(e.onbeforesave&&(g.$onbeforesave=function(){return b(e.onbeforesave)(a,{$data:g.$data})}),e.onaftersave&&(g.$onaftersave=function(){return b(e.onaftersave)(a,{$data:g.$data})}),c.bind("submit",function(b){b.preventDefault(),a.$apply(function(){g.$submit()})})),c.bind("click",function(a){a.which&&1!==a.which||g.$visible&&(g._clicked=!0)})}}}}}]),angular.module("xeditable").factory("editablePromiseCollection",["$q",function(a){function b(){return{promises:[],hasFalse:!1,hasString:!1,when:function(b,c){if(b===!1)this.hasFalse=!0;else if(!c&&angular.isObject(b))this.promises.push(a.when(b));else{if(!angular.isString(b))return;this.hasString=!0}},then:function(b){function c(){h.hasString||h.hasFalse?!h.hasString&&h.hasFalse?e():f():d()}b=b||{};var d=b.onTrue||angular.noop,e=b.onFalse||angular.noop,f=b.onString||angular.noop,g=b.onWait||angular.noop,h=this;this.promises.length?(g(!0),a.all(this.promises).then(function(a){g(!1),angular.forEach(a,function(a){h.when(a,!0)}),c()},function(a){g(!1),f()})):c()}}}return b}]),angular.module("xeditable").factory("editableUtils",[function(){return{indexOf:function(a,b){if(a.indexOf)return a.indexOf(b);for(var c=0;c<a.length;c++)if(b===a[c])return c;return-1},arrayRemove:function(a,b){var c=this.indexOf(a,b);return c>=0&&a.splice(c,1),b},camelToDash:function(a){var b=/[A-Z]/g;return a.replace(b,function(a,b){return(b?"-":"")+a.toLowerCase()})},dashToCamel:function(a){var b=/([\:\-\_]+(.))/g,c=/^moz([A-Z])/;return a.replace(b,function(a,b,c,d){return d?c.toUpperCase():c}).replace(c,"Moz$1")},rename:function(a,b){if(b[0]&&b[0].attributes){var c=angular.element("<"+a+"/>");c.html(b.html());for(var d=b[0].attributes,e=0;e<d.length;++e)c.attr(d.item(e).nodeName,d.item(e).value);return c}}}}]),angular.module("xeditable").factory("editableNgOptionsParser",[function(){function a(a){var c;if(!(c=a.match(b)))throw"ng-options parse error";var d,e=c[2]||c[1],f=c[4]||c[6],g=c[5],h=(c[3]||"",c[2]?c[1]:f),i=c[7],j=c[8],k=j?c[8]:null;return void 0===g?(d=f+" in "+i,void 0!==j&&(d+=" track by "+k)):d="("+g+", "+f+") in "+i,{ngRepeat:d,locals:{valueName:f,keyName:g,valueFn:h,displayFn:e}}}var b=/^\s*(.*?)(?:\s+as\s+(.*?))?(?:\s+group\s+by\s+(.*))?\s+for\s+(?:([\$\w][\$\w]*)|(?:\(\s*([\$\w][\$\w]*)\s*,\s*([\$\w][\$\w]*)\s*\)))\s+in\s+(.*?)(?:\s+track\s+by\s+(.*?))?$/;return a}]),angular.module("xeditable").factory("editableCombodate",[function(){function a(a,b){if(this.$element=angular.element(a),"INPUT"!=this.$element[0].nodeName)throw"Combodate should be applied to INPUT element";var c=(new Date).getFullYear();this.defaults={format:"YYYY-MM-DD HH:mm",template:"D / MMM / YYYY   H : mm",value:null,minYear:1970,maxYear:c,yearDescending:!0,minuteStep:5,secondStep:1,firstItem:"empty",errorClass:null,customClass:"",roundTime:!0,smartDays:!0},this.options=angular.extend({},this.defaults,b),this.init()}return a.prototype={constructor:a,init:function(){if(this.map={day:["D","date"],month:["M","month"],year:["Y","year"],hour:["[Hh]","hours"],minute:["m","minutes"],second:["s","seconds"],ampm:["[Aa]",""]},this.$widget=angular.element('<span class="combodate"></span>').html(this.getTemplate()),this.initCombos(),this.options.smartDays){var a=this;this.$widget.find("select").bind("change",function(b){(angular.element(b.target).hasClass("month")||angular.element(b.target).hasClass("year"))&&a.fillCombo("day")})}this.$widget.find("select").css("width","auto"),this.$element.css("display","none").after(this.$widget),this.setValue(this.$element.val()||this.options.value)},getTemplate:function(){var a=this.options.template,b=this.options.customClass;return angular.forEach(this.map,function(b,c){b=b[0];var d=new RegExp(b+"+"),e=b.length>1?b.substring(1,2):b;a=a.replace(d,"{"+e+"}")}),a=a.replace(/ /g,"&nbsp;"),angular.forEach(this.map,function(c,d){c=c[0];var e=c.length>1?c.substring(1,2):c;a=a.replace("{"+e+"}",'<select class="'+d+" "+b+'"></select>')}),a},initCombos:function(){for(var a in this.map){var b=this.$widget[0].querySelectorAll("."+a);this["$"+a]=b.length?angular.element(b):null,this.fillCombo(a)}},fillCombo:function(a){var b=this["$"+a];if(b){var c="fill"+a.charAt(0).toUpperCase()+a.slice(1),d=this[c](),e=b.val();b.html("");for(var f=0;f<d.length;f++)b.append('<option value="'+d[f][0]+'">'+d[f][1]+"</option>");b.val(e)}},fillCommon:function(a){var b,c=[];if("name"===this.options.firstItem){b=moment.relativeTime||moment.langData()._relativeTime;var d="function"==typeof b[a]?b[a](1,!0,a,!1):b[a];d=d.split(" ").reverse()[0],c.push(["",d])}else"empty"===this.options.firstItem&&c.push(["",""]);return c},fillDay:function(){var a,b,c=this.fillCommon("d"),d=-1!==this.options.template.indexOf("DD"),e=31;if(this.options.smartDays&&this.$month&&this.$year){var f=parseInt(this.$month.val(),10),g=parseInt(this.$year.val(),10);isNaN(f)||isNaN(g)||(e=moment([g,f]).daysInMonth())}for(b=1;e>=b;b++)a=d?this.leadZero(b):b,c.push([b,a]);return c},fillMonth:function(){var a,b,c=this.fillCommon("M"),d=-1!==this.options.template.indexOf("MMMM"),e=-1!==this.options.template.indexOf("MMM"),f=-1!==this.options.template.indexOf("MM");for(b=0;11>=b;b++)a=d?moment().date(1).month(b).format("MMMM"):e?moment().date(1).month(b).format("MMM"):f?this.leadZero(b+1):b+1,c.push([b,a]);return c},fillYear:function(){var a,b,c=[],d=-1!==this.options.template.indexOf("YYYY");for(b=this.options.maxYear;b>=this.options.minYear;b--)a=d?b:(b+"").substring(2),c[this.options.yearDescending?"push":"unshift"]([b,a]);return c=this.fillCommon("y").concat(c)},fillHour:function(){var a,b,c=this.fillCommon("h"),d=-1!==this.options.template.indexOf("h"),e=(-1!==this.options.template.indexOf("H"),-1!==this.options.template.toLowerCase().indexOf("hh")),f=d?1:0,g=d?12:23;for(b=f;g>=b;b++)a=e?this.leadZero(b):b,c.push([b,a]);return c},fillMinute:function(){var a,b,c=this.fillCommon("m"),d=-1!==this.options.template.indexOf("mm");for(b=0;59>=b;b+=this.options.minuteStep)a=d?this.leadZero(b):b,c.push([b,a]);return c},fillSecond:function(){var a,b,c=this.fillCommon("s"),d=-1!==this.options.template.indexOf("ss");for(b=0;59>=b;b+=this.options.secondStep)a=d?this.leadZero(b):b,c.push([b,a]);return c},fillAmpm:function(){var a=-1!==this.options.template.indexOf("a"),b=(-1!==this.options.template.indexOf("A"),[["am",a?"am":"AM"],["pm",a?"pm":"PM"]]);return b},getValue:function(a){var b,c={},d=this,e=!1;return angular.forEach(this.map,function(a,b){if("ampm"!==b){var f="day"===b?1:0;return c[b]=d["$"+b]?parseInt(d["$"+b].val(),10):f,isNaN(c[b])?(e=!0,!1):void 0}}),e?"":(this.$ampm&&(12===c.hour?c.hour="am"===this.$ampm.val()?0:12:c.hour="am"===this.$ampm.val()?c.hour:c.hour+12),b=moment([c.year,c.month,c.day,c.hour,c.minute,c.second]),this.highlight(b),a=void 0===a?this.options.format:a,null===a?b.isValid()?b:null:b.isValid()?b.format(a):"")},setValue:function(a){function b(a,b){var c={};return angular.forEach(a.children("option"),function(a,d){var e=angular.element(a).attr("value");if(""!==e){var f=Math.abs(e-b);("undefined"==typeof c.distance||f<c.distance)&&(c={value:e,distance:f})}}),c.value}if(a){var c="string"==typeof a?moment(a,this.options.format,!0):moment(a),d=this,e={};c.isValid()&&(angular.forEach(this.map,function(a,b){"ampm"!==b&&(e[b]=c[a[1]]())}),this.$ampm&&(e.hour>=12?(e.ampm="pm",e.hour>12&&(e.hour-=12)):(e.ampm="am",0===e.hour&&(e.hour=12))),angular.forEach(e,function(a,c){d["$"+c]&&("minute"===c&&d.options.minuteStep>1&&d.options.roundTime&&(a=b(d["$"+c],a)),"second"===c&&d.options.secondStep>1&&d.options.roundTime&&(a=b(d["$"+c],a)),d["$"+c].val(a))}),this.options.smartDays&&this.fillCombo("day"),this.$element.val(c.format(this.options.format)).triggerHandler("change"))}},highlight:function(a){a.isValid()?this.options.errorClass?this.$widget.removeClass(this.options.errorClass):this.$widget.find("select").css("border-color",this.borderColor):this.options.errorClass?this.$widget.addClass(this.options.errorClass):(this.borderColor||(this.borderColor=this.$widget.find("select").css("border-color")),this.$widget.find("select").css("border-color","red"))},leadZero:function(a){return 9>=a?"0"+a:a},destroy:function(){this.$widget.remove(),this.$element.removeData("combodate").show()}},{getInstance:function(b,c){return new a(b,c)}}}]),angular.module("xeditable").factory("editableIcons",function(){var a={"default":{bs2:{ok:"icon-ok icon-white",cancel:"icon-remove",clear:"icon-trash"},bs3:{ok:"glyphicon glyphicon-ok",cancel:"glyphicon glyphicon-remove",clear:"glyphicon glyphicon-trash"}},external:{"font-awesome":{ok:"fa fa-check",cancel:"fa fa-times",clear:"fa fa-trash"}}};return a}),angular.module("xeditable").factory("editableThemes",function(){var a={"default":{formTpl:'<form class="editable-wrap"></form>',noformTpl:'<span class="editable-wrap"></span>',controlsTpl:'<span class="editable-controls"></span>',inputTpl:"",errorTpl:'<div class="editable-error" data-ng-if="$error" data-ng-bind="$error"></div>',buttonsTpl:'<span class="editable-buttons"></span>',submitTpl:'<button type="submit">save</button>',cancelTpl:'<button type="button" ng-click="$form.$cancel()">cancel</button>',resetTpl:'<button type="reset">clear</button>'},bs2:{formTpl:'<form class="form-inline editable-wrap" role="form"></form>',noformTpl:'<span class="editable-wrap"></span>',controlsTpl:'<div class="editable-controls controls control-group" ng-class="{\'error\': $error}"></div>',inputTpl:"",errorTpl:'<div class="editable-error help-block" data-ng-if="$error" data-ng-bind="$error"></div>',buttonsTpl:'<span class="editable-buttons"></span>',submitTpl:'<button type="submit" class="btn btn-primary"><span></span></button>',cancelTpl:'<button type="button" class="btn" ng-click="$form.$cancel()"><span></span></button>',resetTpl:'<button type="reset" class="btn btn-danger">clear</button>'},bs3:{formTpl:'<form class="form-inline editable-wrap" role="form"></form>',noformTpl:'<span class="editable-wrap"></span>',
controlsTpl:'<div class="editable-controls form-group" ng-class="{\'has-error\': $error}"></div>',inputTpl:"",errorTpl:'<div class="editable-error help-block" data-ng-if="$error" data-ng-bind="$error"></div>',buttonsTpl:'<span class="editable-buttons"></span>',submitTpl:'<button type="submit" class="btn btn-primary"><span></span></button>',cancelTpl:'<button type="button" class="btn btn-default" ng-click="$form.$cancel()"><span></span></button>',resetTpl:'<button type="reset" class="btn btn-danger">clear</button>',buttonsClass:"",inputClass:"",postrender:function(){switch(this.directiveName){case"editableText":case"editableSelect":case"editableTextarea":case"editableEmail":case"editableTel":case"editableNumber":case"editableUrl":case"editableSearch":case"editableDate":case"editableDatetime":case"editableBsdate":case"editableTime":case"editableMonth":case"editableWeek":case"editablePassword":case"editableDatetimeLocal":if(this.inputEl.addClass("form-control"),this.theme.inputClass){if(this.inputEl.attr("multiple")&&("input-sm"===this.theme.inputClass||"input-lg"===this.theme.inputClass))break;this.inputEl.addClass(this.theme.inputClass)}break;case"editableCheckbox":this.editorEl.addClass("checkbox")}this.buttonsEl&&this.theme.buttonsClass&&this.buttonsEl.find("button").addClass(this.theme.buttonsClass)}},semantic:{formTpl:'<form class="editable-wrap ui form" ng-class="{\'error\': $error}" role="form"></form>',noformTpl:'<span class="editable-wrap"></span>',controlsTpl:'<div class="editable-controls ui fluid input" ng-class="{\'error\': $error}"></div>',inputTpl:"",errorTpl:'<div class="editable-error ui error message" data-ng-if="$error" data-ng-bind="$error"></div>',buttonsTpl:'<span class="mini ui buttons"></span>',submitTpl:'<button type="submit" class="ui primary button"><i class="ui check icon"></i></button>',cancelTpl:'<button type="button" class="ui button" ng-click="$form.$cancel()"><i class="ui cancel icon"></i></button>',resetTpl:'<button type="reset" class="ui button">clear</button>'}};return a});
                                                        
                                                        
//
// Copyright Kamil Pękala http://github.com/kamilkp
// Angular Virtual Scroll Repeat v1.1.7 2016/03/08
//
!function(a,b){"use strict";function c(){if("pageYOffset"in a)return{scrollTop:pageYOffset,scrollLeft:pageXOffset};var b,c,d=document,e=d.documentElement,f=d.body;return b=e.scrollLeft||f.scrollLeft||0,c=e.scrollTop||f.scrollTop||0,{scrollTop:c,scrollLeft:b}}function d(b,c){return b===a?"clientWidth"===c?a.innerWidth:a.innerHeight:b[c]}function e(b,d){return b===a?c()[d]:b[d]}function f(b,d,e){return b.getBoundingClientRect()[e?"left":"top"]-(d===a?0:d.getBoundingClientRect()[e?"left":"top"])+(d===a?c():d)[e?"scrollLeft":"scrollTop"]}var g=document.documentElement,h=g.matches?"matches":g.matchesSelector?"matchesSelector":g.webkitMatches?"webkitMatches":g.webkitMatchesSelector?"webkitMatchesSelector":g.msMatches?"msMatches":g.msMatchesSelector?"msMatchesSelector":g.mozMatches?"mozMatches":g.mozMatchesSelector?"mozMatchesSelector":null,i=b.element.prototype.closest||function(a){for(var c=this[0].parentNode;c!==document.documentElement&&null!=c&&!c[h](a);)c=c.parentNode;return c&&c[h](a)?b.element(c):b.element()},j=b.module("vs-repeat",[]).directive("vsRepeat",["$compile","$parse",function(c,g){return{restrict:"A",scope:!0,compile:function(h,j){var k,l,m,n,o,p,q=b.isDefined(j.vsRepeatContainer)?b.element(h[0].querySelector(j.vsRepeatContainer)):h,r=q.children().eq(0),s=r[0].outerHTML,t="$vs_collection",u=!1,v={vsRepeat:"elementSize",vsOffsetBefore:"offsetBefore",vsOffsetAfter:"offsetAfter",vsScrolledToEndOffset:"scrolledToEndOffset",vsScrolledToBeginningOffset:"scrolledToBeginningOffset",vsExcess:"excess"};if(r.attr("ng-repeat"))p="ng-repeat",k=r.attr("ng-repeat");else if(r.attr("data-ng-repeat"))p="data-ng-repeat",k=r.attr("data-ng-repeat");else if(r.attr("ng-repeat-start"))u=!0,p="ng-repeat-start",k=r.attr("ng-repeat-start");else{if(!r.attr("data-ng-repeat-start"))throw new Error("angular-vs-repeat: no ng-repeat directive on a child element");u=!0,p="data-ng-repeat-start",k=r.attr("data-ng-repeat-start")}if(l=/^\s*(\S+)\s+in\s+([\S\s]+?)(track\s+by\s+\S+)?$/.exec(k),m=l[1],n=l[2],o=l[3],u)for(var w=0,x=q.children().eq(0);null==x.attr("ng-repeat-end")&&null==x.attr("data-ng-repeat-end");)w++,x=q.children().eq(w),s+=x[0].outerHTML;return q.empty(),{pre:function(h,j,k){function l(){if(!G||G.length<1)h[t]=[],C=0,h.sizesCumulative=[0];else if(C=G.length,L){h.sizes=G.map(function(a){var c=h.$new(!1);b.extend(c,a),c[m]=a;var d=k.vsSize||k.vsSizeProperty?c.$eval(k.vsSize||k.vsSizeProperty):h.elementSize;return c.$destroy(),d});var a=0;h.sizesCumulative=h.sizes.map(function(b){var c=a;return a+=b,c}),h.sizesCumulative.push(a)}else q();y()}function q(){K&&h.$$postDigest(function(){if(D[0].offsetHeight||D[0].offsetWidth){for(var a=D.children(),b=0,c=!1,d=!1;b<a.length;){if(null!=a[b].attributes[p]||d){if(c||(h.elementSize=0),c=!0,a[b][P]&&(h.elementSize+=a[b][P]),!u)break;if(null!=a[b].attributes["ng-repeat-end"]||null!=a[b].attributes["data-ng-repeat-end"])break;d=!0}b++}c&&(y(),K=!1,h.$root&&!h.$root.$$phase&&h.$apply())}else var e=h.$watch(function(){(D[0].offsetHeight||D[0].offsetWidth)&&(e(),q())})})}function r(){var a="tr"===F?"":"min-";return H?a+"width":a+"height"}function w(){B()&&h.$digest()}function x(){void 0!==k.vsAutoresize&&(K=!0,q(),h.$root&&!h.$root.$$phase&&h.$apply()),B()&&h.$apply()}function y(){R=void 0,S=void 0,T=C,U=0,z(L?h.sizesCumulative[C]:h.elementSize*C),B(),h.$emit("vsRepeatReinitialized",h.startIndex,h.endIndex)}function z(a){h.totalSize=h.offsetBefore+a+h.offsetAfter}function A(){var a=d(M[0],O);a!==V&&(y(),h.$root&&!h.$root.$$phase&&h.$apply()),V=a}function B(){var a=e(M[0],Q),b=d(M[0],O),c=D[0]===M[0]?0:f(D[0],M[0],H),i=h.startIndex,j=h.endIndex;if(L){for(i=0;h.sizesCumulative[i]<a-h.offsetBefore-c;)i++;for(i>0&&i--,i=Math.max(Math.floor(i-h.excess/2),0),j=i;h.sizesCumulative[j]<a-h.offsetBefore-c+b;)j++;j=Math.min(Math.ceil(j+h.excess/2),C)}else i=Math.max(Math.floor((a-h.offsetBefore-c)/h.elementSize)-h.excess/2,0),j=Math.min(i+Math.ceil(b/h.elementSize)+h.excess,C);T=Math.min(i,T),U=Math.max(j,U),h.startIndex=N.latch?T:i,h.endIndex=N.latch?U:j,U<h.startIndex&&(h.startIndex=U);var l=!1;if(null==R?l=!0:null==S&&(l=!0),l||(N.hunked?Math.abs(h.startIndex-R)>=h.excess/2||0===h.startIndex&&0!==R?l=!0:(Math.abs(h.endIndex-S)>=h.excess/2||h.endIndex===C&&S!==C)&&(l=!0):l=h.startIndex!==R||h.endIndex!==S),l){h[t]=G.slice(h.startIndex,h.endIndex),h.$emit("vsRepeatInnerCollectionUpdated",h.startIndex,h.endIndex,R,S);var m;k.vsScrolledToEnd&&(m=G.length-(h.scrolledToEndOffset||0),(h.endIndex>=m&&S<m||G.length&&h.endIndex===G.length)&&h.$eval(k.vsScrolledToEnd)),k.vsScrolledToBeginning&&(m=h.scrolledToBeginningOffset||0,h.startIndex<=m&&R>h.startIndex&&h.$eval(k.vsScrolledToBeginning)),R=h.startIndex,S=h.endIndex;var n=L?"(sizesCumulative[$index + startIndex] + offsetBefore)":"(($index + startIndex) * elementSize + offsetBefore)",o=g(n),p=o(h,{$index:0}),q=o(h,{$index:h[t].length}),s=h.totalSize;I.css(r(),p+"px"),J.css(r(),s-q+"px")}return l}var C,D=b.isDefined(k.vsRepeatContainer)?b.element(j[0].querySelector(k.vsRepeatContainer)):j,E=b.element(s),F=E[0].tagName.toLowerCase(),G=[],H=void 0!==k.vsHorizontal,I=b.element("<"+F+' class="vs-repeat-before-content"></'+F+">"),J=b.element("<"+F+' class="vs-repeat-after-content"></'+F+">"),K=!k.vsRepeat,L=!!k.vsSize||!!k.vsSizeProperty,M=k.vsScrollParent?"window"===k.vsScrollParent?b.element(a):i.call(D,k.vsScrollParent):D,N="vsOptions"in k?h.$eval(k.vsOptions):{},O=H?"clientWidth":"clientHeight",P=H?"offsetWidth":"offsetHeight",Q=H?"scrollLeft":"scrollTop";if(h.totalSize=0,!("vsSize"in k)&&"vsSizeProperty"in k&&console.warn("vs-size-property attribute is deprecated. Please use vs-size attribute which also accepts angular expressions."),0===M.length)throw"Specified scroll parent selector did not match any element";h.$scrollParent=M,L&&(h.sizesCumulative=[]),h.elementSize=+k.vsRepeat||d(M[0],O)||50,h.offsetBefore=0,h.offsetAfter=0,h.excess=2,H?(I.css("height","100%"),J.css("height","100%")):(I.css("width","100%"),J.css("width","100%")),Object.keys(v).forEach(function(a){k[a]&&k.$observe(a,function(b){h[v[a]]=+b,y()})}),h.$watchCollection(n,function(a){G=a||[],l()}),E.eq(0).attr(p,m+" in "+t+(o?" "+o:"")),E.addClass("vs-repeat-repeated-element"),D.append(I),D.append(E),c(E)(h),D.append(J),h.startIndex=0,h.endIndex=0,M.on("scroll",w),b.element(a).on("resize",x),h.$on("$destroy",function(){b.element(a).off("resize",x),M.off("scroll",w)}),h.$on("vsRepeatTrigger",l),h.$on("vsRepeatResize",function(){K=!0,q()});var R,S,T,U;h.$on("vsRenderAll",function(){N.latch&&setTimeout(function(){var a=C;U=Math.max(a,U),h.endIndex=N.latch?U:a,h[t]=G.slice(h.startIndex,h.endIndex),S=h.endIndex,h.$$postDigest(function(){I.css(r(),0),J.css(r(),0)}),h.$apply(function(){h.$emit("vsRenderAllDone")})})});var V;h.$watch(function(){"function"==typeof a.requestAnimationFrame?a.requestAnimationFrame(A):A()})}}}}}]);"undefined"!=typeof module&&module.exports&&(module.exports=j.name)}(window,window.angular);


/* ng-infinite-scroll - v1.3.0 - 2016-06-30 */
angular.module("infinite-scroll",[]).value("THROTTLE_MILLISECONDS",null).directive("infiniteScroll",["$rootScope","$window","$interval","THROTTLE_MILLISECONDS",function(a,b,c,d){return{scope:{infiniteScroll:"&",infiniteScrollContainer:"=",infiniteScrollDistance:"=",infiniteScrollDisabled:"=",infiniteScrollUseDocumentBottom:"=",infiniteScrollListenForEvent:"@"},link:function(e,f,g){var h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z;return z=angular.element(b),u=null,v=null,j=null,k=null,r=!0,y=!1,x=null,i=!1,q=function(a){return a=a[0]||a,isNaN(a.offsetHeight)?a.document.documentElement.clientHeight:a.offsetHeight},s=function(a){if(a[0].getBoundingClientRect&&!a.css("none"))return a[0].getBoundingClientRect().top+t(a)},t=function(a){return a=a[0]||a,isNaN(window.pageYOffset)?a.document.documentElement.scrollTop:a.ownerDocument.defaultView.pageYOffset},p=function(){var b,d,g,h,l;return k===z?(b=q(k)+t(k[0].document.documentElement),g=s(f)+q(f)):(b=q(k),d=0,void 0!==s(k)&&(d=s(k)),g=s(f)-d+q(f)),y&&(g=q((f[0].ownerDocument||f[0].document).documentElement)),h=g-b,l=h<=q(k)*u+1,l?(j=!0,v?e.$$phase||a.$$phase?e.infiniteScroll():e.$apply(e.infiniteScroll):void 0):(i&&c.cancel(i),j=!1)},w=function(a,b){var d,e,f;return f=null,e=0,d=function(){return e=(new Date).getTime(),c.cancel(f),f=null,a.call()},function(){var g,h;return g=(new Date).getTime(),h=b-(g-e),h<=0?(c.cancel(f),f=null,e=g,a.call()):f?void 0:f=c(d,h,1)}},null!=d&&(p=w(p,d)),e.$on("$destroy",function(){if(k.unbind("scroll",p),null!=x&&(x(),x=null),i)return c.cancel(i)}),n=function(a){return u=parseFloat(a)||0},e.$watch("infiniteScrollDistance",n),n(e.infiniteScrollDistance),m=function(a){if(v=!a,v&&j)return j=!1,p()},e.$watch("infiniteScrollDisabled",m),m(e.infiniteScrollDisabled),o=function(a){return y=a},e.$watch("infiniteScrollUseDocumentBottom",o),o(e.infiniteScrollUseDocumentBottom),h=function(a){if(null!=k&&k.unbind("scroll",p),k=a,null!=a)return k.bind("scroll",p)},h(z),e.infiniteScrollListenForEvent&&(x=a.$on(e.infiniteScrollListenForEvent,p)),l=function(a){if(null!=a&&0!==a.length){if(a.nodeType&&1===a.nodeType?a=angular.element(a):"function"==typeof a.append?a=angular.element(a[a.length-1]):"string"==typeof a&&(a=angular.element(document.querySelector(a))),null!=a)return h(a);throw new Error("invalid infinite-scroll-container attribute.")}},e.$watch("infiniteScrollContainer",l),l(e.infiniteScrollContainer||[]),null!=g.infiniteScrollParent&&h(angular.element(f.parent())),null!=g.infiniteScrollImmediateCheck&&(r=e.$eval(g.infiniteScrollImmediateCheck)),i=c(function(){return r&&p(),c.cancel(i)})}}}]),"undefined"!=typeof module&&"undefined"!=typeof exports&&module.exports===exports&&(module.exports="infinite-scroll");


/*! ngstorage 0.3.6 | Copyright (c) 2015 Gias Kay Lee | MIT License */!function(a,b){"use strict";return"function"==typeof define&&define.amd?void define("ngStorage",["angular"],function(a){return b(a)}):b(a)}("undefined"==typeof angular?null:angular,function(a){"use strict";function b(b){return["$rootScope","$window","$log","$timeout",function(c,d,e,f){function g(a){var b;try{b=d[a]}catch(c){b=!1}if(b&&"localStorage"===a){var e="__"+Math.round(1e7*Math.random());try{localStorage.setItem(e,e),localStorage.removeItem(e)}catch(c){b=!1}}return b}var h,i,j=g(b)||(e.warn("This browser does not support Web Storage!"),{setItem:function(){},getItem:function(){}}),k={$default:function(b){for(var c in b)a.isDefined(k[c])||(k[c]=b[c]);return k},$reset:function(a){for(var b in k)"$"===b[0]||delete k[b]&&j.removeItem("ngStorage-"+b);return k.$default(a)}};try{j=d[b],j.length}catch(l){e.warn("This browser does not support Web Storage!"),j={}}for(var m,n=0,o=j.length;o>n;n++)(m=j.key(n))&&"ngStorage-"===m.slice(0,10)&&(k[m.slice(10)]=a.fromJson(j.getItem(m)));return h=a.copy(k),c.$watch(function(){var b;i||(i=f(function(){if(i=null,!a.equals(k,h)){b=a.copy(h),a.forEach(k,function(c,d){a.isDefined(c)&&"$"!==d[0]&&j.setItem("ngStorage-"+d,a.toJson(c)),delete b[d]});for(var c in b)j.removeItem("ngStorage-"+c);h=a.copy(k)}},100,!1))}),"localStorage"===b&&d.addEventListener&&d.addEventListener("storage",function(b){"ngStorage-"===b.key.slice(0,10)&&(b.newValue?k[b.key.slice(10)]=a.fromJson(b.newValue):delete k[b.key.slice(10)],h=a.copy(k),c.$apply())}),k}]}a.module("ngStorage",[]).factory("$localStorage",b("localStorage")).factory("$sessionStorage",b("sessionStorage"))});

/* 
 ng Coockie
*/
(function(n,c){'use strict';function l(b,a,g){var d=g.baseHref(),k=b[0];return function(b,e,f){var g,h;f=f||{};h=f.expires;g=c.isDefined(f.path)?f.path:d;c.isUndefined(e)&&(h="Thu, 01 Jan 1970 00:00:00 GMT",e="");c.isString(h)&&(h=new Date(h));e=encodeURIComponent(b)+"="+encodeURIComponent(e);e=e+(g?";path="+g:"")+(f.domain?";domain="+f.domain:"");e+=h?";expires="+h.toUTCString():"";e+=f.secure?";secure":"";f=e.length+1;4096<f&&a.warn("Cookie '"+b+"' possibly not set or overflowed because it was too large ("+f+" > 4096 bytes)!");k.cookie=e}}c.module("ngCookies",["ng"]).provider("$cookies",[function(){var b=this.defaults={};this.$get=["$$cookieReader","$$cookieWriter",function(a,g){return{get:function(d){return a()[d]},getObject:function(d){return(d=this.get(d))?c.fromJson(d):d},getAll:function(){return a()},put:function(d,a,m){g(d,a,m?c.extend({},b,m):b)},putObject:function(d,b,a){this.put(d,c.toJson(b),a)},remove:function(a,k){g(a,void 0,k?c.extend({},b,k):b)}}}]}]);c.module("ngCookies").factory("$cookieStore",["$cookies",function(b){return{get:function(a){return b.getObject(a)},put:function(a,c){b.putObject(a,c)},remove:function(a){b.remove(a)}}}]);l.$inject=["$document","$log","$browser"];c.module("ngCookies").provider("$$cookieWriter",function(){this.$get=l})})(window,window.angular);



(function(window, angular) {
    'use strict';

    var ajax = function ($q) {

        var vm = this;
        
        vm.ajax = null;
                
        var sanitize = function(type,url,params,options,resolve,reject) {
                            
            params              = (typeof(params             ) === "undefined") ? null : params             ;
            options             = (typeof(options            ) === "undefined") ? {}   : options            ;
            options.complete    = (typeof(options.complete   ) === "undefined") ? null : options.complete   ;
            options.progress    = (typeof(options.progress   ) === "undefined") ? true : options.progress   ;
            options.async       = (typeof(options.async      ) === "undefined") ? true : options.async      ;
            options.cache       = (typeof(options.cache      ) === "undefined") ? true : options.cache      ;
            options.contentType = (typeof(options.contentType) === "undefined") ? null : options.contentType;

            if ( params != null && typeof(params) == 'object') {
                
                params = JSON.stringify(params);
                
                options.contentType = 'application/json';
            }            
            
            return {
                type    : type,
                url     : url,
                params  : params,
                options : options,
                resolve : resolve,
                reject  : reject
            }
        };
        
        var xhr = function(type,url,params,options) {
            return $q(function(resolve, reject) {
                
                var data = sanitize(type,url,params,options,resolve, reject);

                vm.ajax = execAjax1(
                    data.type,
                    data.url,
                    data.params,
                    data.resolve,
                    data.reject,
                    data.options.complete,
                    data.options.progress,
                    data.options.async,
                    data.options.cache,
                    data.options.contentType
                );
            });
        }
        
        vm.post = function (url,params,options) {
            return xhr('POST',url,params,options);
        };
        
        vm.get = function (url,params,options) {
            return xhr('GET',url,params,options);
        };
        
        vm.abort = function () {
            vm.ajax.abort();
        }
    };
    
    ajax.$inject = ['$q'];

    var ajaxModule = angular.module('gc-ajax', []);
    
    ajaxModule.service('$ajax', ajax);

    if (typeof module !== 'undefined' && module.exports) {
        module.exports = ajaxModule.name;
    }
})(window, window.angular);


(function(window, angular) {
    'use strict';

	Consulta.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        'gScope',
        '$compile',
        '$timeout'
    ];

	function Consulta($ajax, $httpParamSerializer, $rootScope, gScope, $compile,$timeout) {

		var lista = [];
	    /**
	     * Constructor, with class name
	     */
	    function Consulta(data) {
	        if (data) {
	            this.setData(data);
	        }
	    }

	    /**
	     * Public method, assigned to prototype
	     */
	    var obj_Consulta = {

	        Consulta: function(data) {
	            if (data) {
	                this.setData(data);
	            }
	        },
	        MontarHtml: function(obj,flag){

	            var html = '';
	            
	            html += '<div class="consulta-container">';
	            html += '    <div class="consulta">';
	            html += '        <div class="form-group '+obj.getClassForm()+'">';
	            html += '           <label for="consulta-descricao">'+obj.option.label_descricao+'</label>';
	            html += '           <div class="input-group '+obj.option.class+'">';
	            html += '               <input type="search" ng-focus="'+obj.model+'.Input.focus" ng-blur="'+obj.model+'.InputBlur($event)" ng-keydown="'+obj.model+'.InputKeydown($event)" name="consulta_descricao" class="form-control consulta-descricao '+obj.option.tamanho_input+' objConsulta '+obj.getClassInput()+'" autocomplete="off" ng-required="'+obj.model+'.option.required" ng-readonly="'+obj.model+'.Input.readonly" ng-disabled="'+obj.model+'.Input.disabled" ng-model="'+obj.model+'.Input.value" />';            
	            html += '               <button type="button" ng-click="'+obj.model+'.apagar(true)" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-consulta search-button" style="display: block !important;" ng-if="'+obj.model+'.btn_apagar_filtro.visivel" ng-disabled="'+obj.model+'.btn_apagar_filtro.disabled"  tabindex="-1" ><span class="fa fa-close"></span></button>';
	            html += '               <button type="button" ng-click="'+obj.model+'.setFocusInput(); '+obj.model+'.filtrar()" class="input-group-addon btn-filtro btn-filtro-consulta search-button '+obj.getClassButton()+'" disabled tabindex="-1"  style="display: block !important;" ng-if="'+obj.model+'.btn_filtro.visivel" ng-disabled="'+obj.model+'.btn_filtro.disabled"><span class="fa fa-search"></span></button>';
	            html += '               <div ng-style="'+obj.model+'.tabela.style" ng-if="'+obj.model+'.tabela.visivel" style="width:'+obj.option.tamanho_tabela+'px; max-height: 300px;" class="pesquisa-res-container ativo lista-consulta-container ">';
	            html += '                   <div class="pesquisa-res lista-consulta table-ec">';

	            html += '                       <table class="table table-condensed table-striped table-bordered table-hover selectable '+obj.getClassTabela()+'">';
	            html += '                           <thead>';
	            html += '                               <tr ng-focus="'+obj.model+'.focus()" >';

	            angular.forEach(obj.option.campos_tabela, function(iten, key) {
	            html += '                                   <th>'+iten[1]+'</th>';
	            });


	            html += '                               </tr>';
	            html += '                           </thead>';

	            var tamanho = obj.option.campos_tabela.length;
	            html += '                           <tr ng-if="'+obj.model+'.dados.length == 0" ng-Keydown="'+obj.model+'.selecionarKeydown($event,null)" ng-click="'+obj.model+'.selecionarItem(null)" ng-focus="'+obj.model+'.focus()" class="selectable" tabindex="0">';
	            html += '                                   <td style="text-align:center;" colspan="'+tamanho+'">SEM REGISTROS</td>';
	            html += '                           </tr>';


	            html += '                           <tr ng-class="{\'selected\' : '+ obj.model +'.focused == consultaTblItem}" ng-Keydown="'+obj.model+'.selecionarKeydown($event,consultaTblItem)" ng-click="'+obj.model+'.selecionarItem(consultaTblItem)" ng-focus="'+obj.model+'.focus(); ' + obj.model + '.focused = consultaTblItem" ng-blur="' + obj.model + '.focused = false " class="selectable" tabindex="0" ng-repeat="consultaTblItem in '+obj.model+'.dados track by $index">';
	            
	            angular.forEach(obj.option.campos_tabela, function(iten, key) {
	            html += '                                   <td style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{consultaTblItem.'+iten[0]+'}}</td>';
	            });

	            html += '                           </tr>';
	            html += '                       </table>';
	            html += '                   </div>';
	            html += '               </div>';
	            html += '           </div>';
	            html += '        </div>';
	            html += '    </div>';
	            html += '</div>';  

	            if(flag == 0){
		            var obj   = $(obj.componente);
                    if ( obj.length > 0 ) {
                        var scope = obj.scope(); 
                        obj.html(html);
                        $compile(obj.contents())(scope);
                    }
		        }else{
		        	return html;	
		        }

	        },
	        consultar: function(obj){
	        	var that = this;
	        	
	            var btn_filtro = $(this.element_input_group).find('.'+this.getClassButton());

	            function beforeSend() {
	                if (btn_filtro !== false){
	                $(btn_filtro)
	                    .children()
	                    .addClass('fa-circle-o-notch');
	                }
	            }

	            function complete(){
	                if (btn_filtro !== false) {
	                    $(btn_filtro)
	                        .children()
	                        .removeClass('fa-circle-o-notch');
	                }
	            }

                function isEmpty(obj) {
                    for(var prop in obj) {
                        if(obj.hasOwnProperty(prop))
                            return false;
                    }

                    return JSON.stringify(obj) === JSON.stringify({});
                }

	            beforeSend();

	            var filtro = obj.Input.value;
	            if(filtro == undefined){
	            	filtro = "";	
	            }

	            var paran = Object.assign({},obj.option.paran);

                var dados = {FILTRO : filtro};
                
                if ( isEmpty(this.option.data_request) && isEmpty(this.option.require_request) ) {
                    dados.OPTIONS = obj.option.filtro_sql;
                    dados.PARAN   = paran;
                } else {
                    
                    if ( !isEmpty(this.option.data_request) ) {                   
                        var target = this.option.data_request;

                        var props = {};
                        for (var k in target){
                            if (target.hasOwnProperty(k)) {

                                if ( Array.isArray(target[k]) ) {
                                    var model = target[k][0];
                                    var prop  = target[k][1];
                                    props[k] = model[prop];
                                } else {
                                    props[k] = target[k];
                                }

                            }
                        }

                        angular.extend(dados, props);
                    }
                    if ( !isEmpty(this.option.require_request) ) {    
                        var target = this.option.require_request;

                        var props = {};
                        for (var k in target){
                            if (target.hasOwnProperty(k)) {

                                var model = target[k][0].item.dados;
                                var prop  = target[k][1];
                                props[k] = model[prop];

                            }
                        }
                        angular.extend(dados, props);
                    }
 
                }

                
                
	            $ajax.post(obj.option.obj_consulta, dados, {progress:false,complete:complete})
	                .then(function(response) {

	                	if(that.onFilter != null){
			        		that.onFilter();
			        	}
	                    obj.dados = response;

	                    if(obj.dados.length == 1){
	                        obj.selecionarItem(obj.dados[0]);   
	                    }
                        else{
	                        obj.setFocusTabela();
	                    }              
	                },
	                function(e){
	                    //showErro(e);
	                }
	            );

	        },
	        setData: function(data) {
	            angular.extend(this, data);
	        },
	        InputKeydown: function($event){
	            if($event.key == 'Delete' && this.Input.readonly == true){
	                this.apagar();   
	            }

	            if($event.key == 'Enter' && this.Input.readonly == false){
	                this.filtrar();   
	            }

	            if($event.key == 'ArrowDown' && this.tabela.visivel == true){
	                
                    var table = $($event.target).nextAll('.pesquisa-res-container').first();
                    var tr    = table.find('tbody tr:focusable').first();
                    tr.focus();
	            }
	        },
	        InputBlur: function($event){
                
                var that = this;
                                
                var input_group = $($event.relatedTarget).closest('.input-group');

                if ( !that.item.selected ) { 

                    if ( input_group.length == 0 || (input_group.length > 0 && input_group[0] != that.element_input_group)  ) {
                        if ( !that.tabela.visivel ) {
                            that.apagar();
                        }
                    }
                }
	        },
	        selecionarKeydown: function($event,item){
	            if($event.key == 'Enter'){
	                this.selecionarItem(item);
	            }   

	            if($event.key == 'Escape'){
	                this.tabela.visivel = false;
	                this.setFocusInput();
	            }
	        },
	        vincular:function(){

	            var that = this;

	            if(this.require != null){
	                if(Array.isArray(this.require)){
	                	that.option.filtro_sql = [];
	                    angular.forEach(this.require, function(item, key) {
	                        that.option.filtro_sql.push(item.item);
	                    });

	                    this.require.reverse();

	                    angular.forEach(this.require, function(item, key) {

	                        item.actionsSelct.push(function(){
	                            if(that.validar() && that.autoload == true){
                                    that.setFocusInput();
	                                that.filtrar();
	                            }   
	                        });
	                        item.actionsClear.push(function(){
	                            that.apagar(); 
	                        });
	                    });

	                }else{

	                    this.option.filtro_sql = this.require.item;

	                    this.require.actionsSelct.push(function(){
                            if(that.validar() && that.autoload == true){
                                that.setFocusInput();
                                that.filtrar();
                            }   
	                    });
	                    this.require.actionsClear.push(function(){
	                        that.apagar();    
	                    });
	                }
	            }
	        },
	        selecionarItem:function(item){

	        	var tamanho = 0;
	        	if(this.dados != undefined){
		        	if(typeof this.dados == 'object'){
		        		tamanho = Object.keys(this.dados).length;
		        	}else{
		        		tamanho = this.dados.length;
		        	}
		        }else{
		        	tamanho = 0;	
		        }

	            if(tamanho > 0){
                                        
	                this.tabela.visivel = false;

	                this.btn_apagar_filtro.disabled = false;
	                this.btn_apagar_filtro.visivel  = true;

	                this.btn_filtro.disabled = true;
	                this.btn_filtro.visivel  = false;

	                this.Input.readonly = true;

	                var valor = '';

	                angular.forEach(this.option.obj_ret, function(campo, key) {
	                    if(valor == ''){
	                        valor  = item[campo];
	                    }else{
	                        valor += ' - ' + item[campo];    
	                    }
	                });

	                this.selected = item;

	                this.item.selected = true;
	                this.item.dados = item;
                    
                    if ( this.set_this ) {
                        angular.extend(this, item);
                    }

	                this.Input.value = valor;
	                this.setFocusInput();
	                this.setDefalt();

	                if(this.onSelect != null && this.vinculoEnabled == true){
	                    this.onSelect();
	                }

	                if(this.vinculoEnabled == true){
		                if(this.actionsSelct != null){
		                    angular.forEach(this.actionsSelct, function(item, key) {
		                        if(item != null){
		                            item();
		                        }
		                    });
		                }
		            }

	            }else{
	                this.item.selected = false;
	                this.item.dados = {};
                    
	                this.tabela.visivel = false;
	                this.selected = null;
	                this.setFocusInput();
	            }
	        },
	        setFocusTabela: function() {
	            this.tabela.visivel = true;
                
	            var that = this;

                var input_height = that.element_input.offsetHeight;
                var table_pos = input_height+1;
                
	            var altura    = window.innerHeight;
	            var registros = this.dados.length + 1; if(registros>8){registros = 8.7}
	            var tamanho   = (registros) * table_pos;
	            var imput     = $(this.element_form_group).find('.'+that.getClassInput());
	            var pos       = $(imput).offset();
	            var topo      = table_pos;

	            if(((pos.top + table_pos + tamanho) > altura)  &&  (window.innerWidth > 500)){
	            	topo = tamanho * -1;
	            };

	            this.tabela.style = {'max-height':'300px', 'top':topo, 'z-index': '99'};
                
                $timeout(function(){
                    
                    var tabela = $(that.element_form_group).find('.'+that.getClassTabela());

	                var tr     = $(tabela).find('tr');
	                if(tr.length > 1){
	                    $(tr[1]).focus();
	                }

                    that.element_table_container = $(that.componente).find('.pesquisa-res-container')[0];

                    var closeTable = function (e) {

                        var input_group_click = $(e.target).closest('.input-group');
                        var table_container_click = $(e.target).closest('.pesquisa-res-container');

                        if ( !that.tabela.visivel || table_container_click.length == 0 || (table_container_click.length > 0 && table_container_click[0] != that.element_table_container) ) {
                            
                            $(this).off(e);
                            
                            $timeout(function(){
                                $rootScope.$apply(function () {                            

                                    if ( !that.item.selected && !$(that.element_input).is(':focus') && !$(that.element_button_search).is(':focus')  ) {
                                        that.apagar();
                                    }

                                    that.tabela.visivel = false;
                                });
                            });
                        }
                    };
                    
                    $('body').focusin(closeTable);
                    $('body').click(closeTable);

                });

	            /*
	            that.pageScroll = $(document).scrollTop();
            	
            	$(document).on('mouseenter','.pesquisa-res-container.ativo',function(){
            		$(document).scroll(function() {
		                var obj = $('.pesquisa-res-container.ativo');
		                if ($(obj).length > 0) {
		                    $(document).scrollTop(that.pageScroll);
		                }
		            });
            	});
            	*/	            

	        },
	        pageScroll:0,
	        setFocusInput: function() {
	            $(this.element_form_group).find('.'+this.getClassInput()).focus();
	        },
	        focus:function(){
	            clearTimeout(this.timeFechar);
	        },     
	        compile : function (montar_html) {
                
                this.MontarHtml(this,0);
                this.getScale();
                
                if ( this.option.tamanho_tabela == undefined || this.option.tamanho_tabela == null ) {
                    var input_width = $(this.element_input).width();
                    
                    if ( input_width < 300 ) {
                        this.option.tamanho_tabela = 300
                    } else {
                        this.option.tamanho_tabela = input_width;
                    }
                }
	        },
            getScale : function () {
                this.element_input = $(this.componente).find('.consulta-descricao')[0];
                this.element_button_search = $(this.componente).find('[type="button"].search-button')[0];
                this.element_input_group = $(this.componente).find('.input-group')[0];                
                this.element_form_group = $(this.componente).find('.form-group')[0];   
            },
	        html : function () {
	            return this.MontarHtml(this,1);
	        },
	        validate:function(){

	            var ret = true;

	            if(this.require != null){
	                if(Array.isArray(this.require)){

	                    this.require.reverse();

	                    angular.forEach(this.require, function(item, key) {
	                        if(item.selected == null){
	                            item.setErro();
	                            item.setFocusInput();
	                            ret = false;
	                        }
	                    });

	                }else{
	                    if(this.require.selected == null){
	                        this.require.setErro();
	                        this.require.setFocusInput();
	                        ret = false;
	                    }
	                }
	            }

	            return ret;

	        },
	        validar:function(){

	            var ret = true;

	            if(this.require != null){
	                if(Array.isArray(this.require)){

	                    this.require.reverse();

	                    angular.forEach(this.require, function(item, key) {
	                        if(item.selected == null){
	                            ret = false;
	                        }
	                    });

	                }else{
	                    if(this.require.selected == null){
	                        ret = false;
	                    }
	                }
	            }

	            return ret;

	        },
	        apagar : function (focus) {

                if ( focus ) {
                    if ( $(this.element_input).is(':focusable') ) {
                        $(this.element_input).focus();
                    }
                }
                
                var target = this.item.dados;
                  
                for (var k in target){
                    if (target.hasOwnProperty(k)) {

                        delete this[k];
                    }
                }                
                
	            this.item.selected = false;
	            this.item.dados = {};

	            this.tabela.visivel = false;

	            this.btn_apagar_filtro.disabled = true;
	            this.btn_apagar_filtro.visivel  = false;

	            this.btn_filtro.disabled = false;
	            this.btn_filtro.visivel  = true;

	            this.Input.disabled = false;
	            this.Input.readonly = false;
	            this.Input.value = '';

	            this.selected = null;

	            if(this.onClear != null && this.vinculoEnabled == true){
	                this.onClear();
	            }

	            if(this.vinculoEnabled == true){
		            if(this.actionsClear != null){
		                angular.forEach(this.actionsClear, function(item, key) {
		                    if(item != null){
		                        item();
		                    }
		                });
		            }
		        }
	        },
	        filtrar : function () {

	            var validar = true;

	            if(this.validarInput != null){
	                validar = this.validarInput();
	            }

	            if(this.validate()){
	                if(validar){
	                    this.consultar(this);
	                }
	            }
	        },
	        disable: function(status){
	        	if(status){
		        	this.vinculoEnabled 			= false;
					this.Input.disabled             = true;
					this.btn_apagar_filtro.visivel  = true;
					this.btn_apagar_filtro.disabled = true;
					this.disabled                   = true;
					this.btn_filtro.visivel         = false;
				}else{

					if(this.item.selected == true){
						this.btn_filtro.visivel         = false;
						this.btn_apagar_filtro.visivel  = true;
						this.Input.disabled             = true;
					}else{
						this.btn_filtro.visivel         = true;
						this.btn_apagar_filtro.visivel  = false;
						this.Input.disabled             = false;

					}

					this.vinculoEnabled 			= true;
					this.btn_apagar_filtro.disabled = false;
					this.disabled                   = false;

				}
	        },
	        setSelected:function(dasos,descricao = false){

	        	if(dasos != null){
		        	if(Object.keys(dasos).length > 0){
			        	this.Input.value    = descricao;

			        	this.selected       = dasos;   
			            this.item.dados     = dasos;
			            this.item.selected  = true;
			            this.selecionado    = true;
			            this.Input.readonly = true;
			            this.Input.readonly = true;

			            this.btn_apagar_filtro.visivel = true;
			            this.btn_apagar_filtro.disabled= false;
			            this.btn_filtro.visivel        = false;

			            if(descricao == false){
				            var valor = '';

			                angular.forEach(this.option.obj_ret, function(campo, key) {
			                    if(valor == ''){
			                        valor  = dasos[campo];
			                    }else{
			                        valor += ' - ' + dasos[campo];    
			                    }
			                });

			                this.Input.value = valor;
			            }
			        }
	            }
	        },
	        setErro:function(msg){
	            $(this.element_form_group).addClass('has-error');
	        },
	        setAlert:function(msg){
	            $(this.element_form_group).addClass('has-error');
	        },
	        setDefalt:function(){
	            $(this.element_form_group).removeClass('has-error');
	        },
            setRequireRequest : function (data) {
                this.option.require_request = data;
            },            
            setDataRequest : function (data) {
                this.option.data_request = data;
            },            
	        getClassTabela:function(){
	            return this.option.class+'_tabela';
	        },
	        getClassForm:function(){
	            return this.option.class+'_forme';
	        },
	        getClassInput:function(){
	            return this.option.class+'_Input';
	        },
	        getClassButton:function(){
	            return this.option.class+'_button';
	        },
	        vinculoEnabled:true,
	        actionsSelct  : [],
	        actionsClear  : [],
	        onSelect      : null,
	        onFilter      : null,
	        onClear       : null,
	        require       : null,
	        validarInput  : null,
	        timeFechar    : null,
	        selected      : null,
            focused       : false,
	        item          : {selected: false, dados: {}},   
	        model         : '',
	        componente    : '',
	        dados         : [],
	        tabela:{
	            disabled  : true,
	            visivel   : false,
	            style     : {'max-height':'300px'}    
	        },
	        btn_apagar_filtro: { 
	            disabled  : true,
	            visivel   : false,
	        },
	        btn_filtro: { 
	            disabled  : false,
	            visivel   : true,
	        },
	        Input: {
	            disabled  : false,
	            readonly  : false,
	            focus     : false,
	            value     : ''
	        },
	        autoload : true,
	        option : {
	            label_descricao   : 'DEFAULT:',
	            obj_consulta      : 'Ppcp/include/_22030-gp',
	            obj_ret           : ['ID','DESCRICAO'],
	            campos_sql        : ['ID','DESCRICAO'],
	            campos_inputs     : [['_id','ID'],['_descricao','DESCRICAO']],
	            filtro_sql        : [['STATUS','1'],['ORDER','DESCRICAO,ID']],
	            campos_tabela     : [['ID','ID'],['DESCRICAO','DESCRIÇÃO']],
	            tamanho_input     : 'input-medio',
	            tamanho_tabela    : null,
	            required          : true,
	            class             : 'consulta_gp_grup',
	            autofocus         : false,
	            selecionado       : false,
	            paran             : [],
                data_request      : {},
                require_request   : {}
	        }
	    };

	    /**
	     * Public method, assigned to prototype
	     */
	    Consulta.prototype = {
	        Consulta: function(data) {
	            if (data) {
	                this.setData(data);
	            }
	        },
	        getNew: function(set_this) {
	        	var item = angular.copy(obj_Consulta);
	        	lista.push(item);

	        	item.pageScroll = $(document).scrollTop();
                
                if ( set_this != undefined ) {
                    item.set_this = true;
                }

	        	return item;
	        },
	        clearHistory:function(url){
                window.history.replaceState('', '', encodeURI(urlhost + url));
	        },
	        postHistory:function(obj,url){
	        	var paran = {
                    CONSULTA : []
                };

                angular.forEach(lista, function(item, key) {
	                paran.CONSULTA.push(item.item);
	            });

                paran.CONSULTA.push(obj);

                window.history.replaceState('', '', encodeURI(urlhost + url + '?' + JSON.stringify(paran)));
	        },
	        getHistory:function(){
	        	var ret =[];

	        	try {

	                var search = location.search.substring(1);
	                search = decodeURI(search);
	                search = JSON.parse(search);

	                if(!(typeof search.CONSULTA == 'undefined')){
	                    ret = search.CONSULTA[search.CONSULTA.length - 1];

	                    angular.forEach(lista, function(item, key) {
			                item.item = search.CONSULTA[key];

			                if(item.item.selected){
				                
					            item.btn_apagar_filtro.disabled = false;
					            item.btn_apagar_filtro.visivel  = true;
					            item.btn_filtro.disabled = true;
					            item.btn_filtro.visivel  = false;
					            item.Input.readonly = true;
					            item.selected = item.item.dados;

					            var valor = '';
					            angular.forEach(item.option.obj_ret, function(campo, key) {
				                    if(valor == ''){
				                        valor  = item.item.dados[campo];
				                    }else{
				                        valor += ' - ' + item.item.dados[campo];    
				                    }
				                });

				                item.Input.value = valor;
					        }
			            });
	                }   

	            } catch(err) {}

	            return ret;
	        }
	    }

	    /**
	     * Return the constructor function
	     */
	    return Consulta;
	};


	Historico.$inject = [
        '$ajax',
        '$q',
        '$rootScope',
        '$compile',
        '$timeout'
    ];

	function Historico($ajax, $q, $rootScope, $compile,$timeout) {

		var lista = [];
	    /**
	     * Constructor, with class name
	     */
	    function Historico(object,arg_scope) {

            this.DADOS = [];
            this.ARGS  = {};
            this.FILTRO = '';
            
            this._button_attr  = '[data-consulta-historico]';
            this._button       = null;
            this._modal        = function(){return $('#modal-historico')};
            this._controller   = function(){
            	var scope = $('#main').find('[ng-controller]').first();

            	if(scope.length == 0){
            		scope = $('#main').find('.ng-isolate-scope').first();
            	}

            	return scope; 

            };


            
            if ( object != undefined ) {
                this._object = object;
            } else {
                this._object = 'vm.Historico';
            }
                
            var that = this;

            var scope = null;

        	if ( arg_scope != undefined ) {
        		scope = arg_scope;
        	} else {
            	scope = that._controller().scope();
        	}

            
            that._controller().append($compile(that.html())(scope));
            
            $(document).on('click',that._button_attr,function(){
                var button = $(this);
                $rootScope.$apply(function(){
                    
                    that._button = button;
            
                    that.ARGS = {
                        TABELA    : button.attr('data-tabela'),
                        TABELA_ID : button.attr('data-tabela-id'),
                    };     
                    
                    that.getHistorico().then(function(){
                        that.FILTRO = '';
                        that._modal().modal('show');
                    });
                });
            });
            
            
	    }
        
        Historico.prototype.getArgs = function(){
                    
            var that = this;

            
        }
        
        Historico.prototype.getHistorico = function(){
            
            var that = this;
            
//            that.getArgs();
            
            return $q(function(resolve,reject){
                $ajax.post('/api/historico', that.ARGS)
                    .then(function(response) {
                        that.DADOS = response;
                        resolve(response);
                    },
                    function(e){
                        reject(e);
                    }
                );
            });
        }

	    /**
	     * Public method, assigned to prototype
	     */
	    Historico.prototype.html = function(){

            var that = this;
            
            var html = '';
            
            html += '<div class="modal fade" id="modal-historico" role="dialog" data-keyboard="false" data-backdrop="static">                         ';
            html += '	<div class="modal-dialog modal-lg" role="document" style="height: calc(100% - 70px);">                                        ';
            html += '		<div class="modal-content" style="height: 100%;">                                                                         ';
            html += '			<div class="modal-header">                                                                                            ';
            html += '				<div class="modal-header-left">                                                                                   ';
            html += '					<h4 class="modal-title" id="myModalLabel">Histórico</h4>                                                      ';
            html += '				</div>                                                                                                            ';
            html += '				<div class="modal-header-center">                                                                                 ';
            html += '				</div>                                                                                                            ';
            html += '				<div class="modal-header-right">                                                                                  ';
            html += '					<button type="button" class="btn btn-default btn-voltar" data-hotkey="f11" data-dismiss="modal">              ';
            html += '						<span class="glyphicon glyphicon-chevron-left"></span>                                                    ';
            html += '						Voltar                                                                                                    ';
            html += '					</button>                                                                                                     ';
            html += '				</div>                                                                                                            ';
            html += '			</div>                                                                                                                ';
            html += '			<div class="modal-body" style="height: 100%;">                                                                        ';
            html += '               <input type="text" class="form-control" ng-model="' + that._object + '.FILTRO" placeholder="Filtragem rápida..."> ';
            html += '				<div class="table-ec" style="height: calc(100% - 26px);">                                                         ';
            html += '					<table class="table table-striped table-bordered table-condensed">                                            ';
            html += '						<thead>                                                                                                   ';
            html += '							<tr>                                                                                                  ';
            html += '								<th class="text-center">Data/Hora</th>                                                            ';
            html += '								<th>Usuário</th>                                                                                  ';
            html += '								<th style="min-width: 280px;">Histórico</th>                                                      ';
            html += '								<th>End. Ip</th>                                                                                  ';
            html += '								<th>Vs. Sist.</th>                                                                                ';
            html += '							</tr>                                                                                                 ';
            html += '						</thead>                                                                                                  ';
            html += '						<tbody>                                                                                                   ';
            html += '							<tr ng-repeat="historico in ' + that._object + '.DADOS                                                ';
            html += '								| find: {                                                                                         ';
            html += '									model : ' + that._object + '.FILTRO,                                                          ';
            html += '									fields : [                                                                                    ';
            html += '										\'DATAHORA_TEXT\',                                                                        ';
            html += '										\'USUARIO\',                                                                              ';
            html += '										\'HISTORICO\',                                                                            ';
            html += '										\'IP\',                                                                                   ';
            html += '										\'VERSAO\'                                                                                ';
            html += '									]                                                                                             ';
            html += '								}                                                                                                 ';
            html += '								| orderBy : [\'-DATAHORA\']                                                                       ';
            html += '								"							                                                                      ';
            html += '							>                                                                                                     ';
            html += '								<td class="text-center">{{ historico.DATAHORA_TEXT }}</td>                                        ';
            html += '								<td>{{ historico.USUARIO }}</td>                                                                  ';
            html += '								<td>{{ historico.HISTORICO }}</td>                                                                ';
            html += '								<td>{{ historico.IP }}</td>                                                                       ';
            html += '								<td>{{ historico.VERSAO }}</td>                                                                   ';
            html += '							</tr>                                                                                                 ';
            html += '						</tbody>                                                                                                  ';
            html += '					</table>                                                                                                      ';
            html += '				</div>                                                                                                            ';
            html += '				                                                                                                                  ';
            html += '			</div>                                                                                                                ';
            html += '			                                                                                                                      ';
            html += '		</div>                                                                                                                    ';
            html += '	</div>                                                                                                                        ';
            html += '</div>                                                                                                                 	      ';

            return html;	

        };    

	    /**
	     * Return the constructor function
	     */
	    return Historico;
	};

    
    var find = function () {
        return function (items,array) {
            var model  = array.model;
            var fields = array.fields;
            var clearOnEmpty = array.clearOnEmpty || false;
            var filtered = [];

            var inFields = function(row,query) {
                var finded = false;
                for ( var i in fields ) {
                    var field = row[fields[i]];
                    if ( field != undefined ) {

                    	var val = row[fields[i]];

                    	if(!(typeof val == 'string')){
                    		val = val.toString();
                    	}

                        finded = angular.lowercase(val).indexOf(query || '') !== -1;

                    }
                    if ( finded ) break;
                }
                return finded;
            };

            if ( clearOnEmpty && model == "" ) return filtered;

            for (var i in items) {
                var row = items[i];                
                var query = angular.lowercase(model);

                if (query != undefined && query != null && query.indexOf(" ") > 0) {
                    var query_array = query.split(" ");
                    var x;
                    for (x in query_array) {
                        query = query_array[x];
                        var search_result = true;
                        if ( !inFields(row,query) ) {
                            search_result = false;
                            break;
                        }
                    }
                } else {
                    search_result = inFields(row,query);
                }                
                if ( search_result ) {
                    filtered.push(row);
                }
            }
            return filtered;
        };
    };   

    var AngularFindModule = angular.module('gc-find', []);

    AngularFindModule.filter('find', find);
    AngularFindModule.factory('$consulta', Consulta);
    AngularFindModule.factory('Historico', Historico);

    if (typeof module !== 'undefined' && module.exports) {
        module.exports = AngularFindModule.name;
    }
})(window, window.angular);

/**
 * Modulo de Forms
 */
(function(window, angular) {
    'use strict';

    var ngUpdateHidden = function () {
        return {
            restrict: 'AE', //attribute or element
            scope: {},
            replace: true,
            require: 'ngModel',
            link: function ($scope, elem, attr, ngModel) {
                $scope.$watch(ngModel, function (nv) {
                    if ( nv != undefined ) {
                        elem.val(nv);
                    }
                });
                $(document).ready(function() {
                    
                    ngModel.$setViewValue(  elem.val());
                    
                    $(elem).change(function () { //bind the change event to hidden input
                        $scope.$apply(function () {
                            ngModel.$setViewValue(  elem.val());
                        });
                    });
                });
            }
        };
    };

    // Remover validação de email do AngularJS.
    var removeNgEmailValidation = function() {
	    return {
	        require : 'ngModel',
	        link : function(scope, element, attrs, ngModel) {
	            ngModel.$validators["email"] = function () {
	                return true;
	            };
	        }
	    }
	};

    var toDate = function () {
        return function(input) {
            if ( input ) return new Date(input);
        };
    };

    var GcForm = angular.module('gc-form', []);

    GcForm.directive('ngUpdateHidden'			, ngUpdateHidden);
    GcForm.directive('removeNgEmailValidation'	, removeNgEmailValidation);
    GcForm.filter   ('toDate'        			, toDate);

    if (typeof module !== 'undefined' && module.exports) {
        module.exports = GcForm.name;
    }
})(window, window.angular);


/**
 * Modulo de Transform
 */
(function(window, angular) {
    'use strict';

    var toColor = function() {
        return function(input, n) {
            
            if( input == undefined ) return '';

            if ( typeof input == 'string' ) {
                input = input.replace("$", "");
            }

            var r = input & 0xFF;
            var g = (input >> 8) & 0xFF;
            var b = (input >> 16) & 0xFF;
            
            var array = [r, g, b];
            
            return 'rgb(' + array.join(",") + ')';

        };
    };

    var lpad = function() {
        return function(input, n) {
            
            input = ''+input;
            
            var tamanho   = ( n[0] == undefined ) ? n   : n[0];
            var caractere = ( n[1] == undefined ) ? " " : n[1];
            
            if(input === undefined || input === null )
                input = "";
            if(input.length >= tamanho)
                return input;
            var zeros = String(caractere).repeat(tamanho);
            return (zeros + input).slice(-1 * tamanho);
        };
    };

    var stringToNumber = function() {
        return {
            require: 'ngModel',
            link: function(scope, element, attrs, ngModel) {
                ngModel.$parsers.push(function(value) {
                    return '' + value;
                });
                ngModel.$formatters.push(function(value) {
                    return parseFloat(value);
                });
            }
        };
    };
    
    var GcTransform = angular.module('gc-transform', []);

    GcTransform.filter('toColor', toColor);
    GcTransform.filter('lpad'   , lpad);
    GcTransform.directive('stringToNumber', stringToNumber);

    if (typeof module !== 'undefined' && module.exports) {
        module.exports = GcTransform.name;
    }
})(window, window.angular);

/**
 * Modulo de Array
 */
(function(window, angular) {
    'use strict';

    var gcCollection = function () {

        var vm = this;
        
        /**
         * Extende/mescla propriedades de uma nova coleção de dados para uma coleção de dados já existente
         * @param {array} collection_main Coleção de Dados principal
         * @param {array} collection_new Nova Coleção de Dados para ser extendida para a existente
         * @param {boolean} preserve_main <b>Opicional</b> Verifica se os dados que não vieram na nova coleção deverão ser removidos da já existente. Default: false
         * @param {boolean} push_new <b>Opicional</b> Verifica se os dados que vieram na nova coleção deverão ser inseridos na coleção já existente. Default: true
         */
        vm.merge = function (collection_main, collection_new, field_identifier, preserve_main, push_new) {

//            try {
                if ( !Array.isArray(collection_main) ) {
                    
                    throw new 'Os dados principal devem ser do tipe array.';
                }
                
                collection_new = !Array.isArray(collection_new) ? [] : collection_new;
                
                // Verifica se na mesclagem deverá manter os objetos principais, caso não exista na nova coleção de dados
                var preserve_main = (preserve_main == undefined) ? false : preserve_main;

                // Verifica se insere os novos objetos que não existem na coleção de dados principal
                var push_new = (push_new == undefined) ? true : push_new;

                // Laço que busca os itens com refências iguais e extende o dados do objeto novo para o principal
                for ( var i in collection_main ) {
                    var item_main = collection_main[i];

                    for ( var j = 0; j < collection_new.length; j++) {
                        var item_new = collection_new[j];


                        var condicao_true = true;

                        if ( typeof field_identifier == 'string' ) {
                            if ( item_main[field_identifier] != item_new[field_identifier] ) {
                                condicao_true = false;
                            }
                        } else {
                            for ( var k in field_identifier ) {
                                var condicao = field_identifier[k];

                                if ( item_main[condicao] != item_new[condicao] ) {
                                    condicao_true = false;
                                    break;
                                }
                            }
                        }

                        // Verifica se os dois objetos possuem o mesmo valor no campo identificador
                        if ( condicao_true ) {

                            // Extende os objetos ( insere ou atualiza as propriedades do novo objeto para o principal )
                            angular.extend(item_main, item_new);

                            // Marca o item principal como localizado na coleção de dados principal
                            item_main.found = true;

                            // Remove da nova coleção de dados o objeto já utilizado
                            collection_new.splice(j,1);
                            j--;

                            break;
                        }
                    }
                }

                // Prepara a coleção principal de dados para saída
                for ( var i = 0; i < collection_main.length; i++) {
                    var item = collection_main[i];

                    // Verifica se preserva o objeto da coleção principal, caso não exista na nova coleção de dados
                    if ( preserve_main ) {
                        // Limpa a propriedade "found" criada para tratamento
                        if ( item.found != undefined ) {
                            delete item.found;
                        }
                    } else {
                        // Remove o item da coleção principal caso não exista na nova coleção de dados
                        if ( item.found == undefined ) {
                            collection_main.splice(i,1);
                            i--;
                        } else {
                            delete item.found;
                        }
                    }
                }

                // Verifica se insere os novos objetos na coleção principal de dados
                if ( push_new ) {
                    // Insere os novos objetos na coleção principal de dados
                    for ( var i in collection_new ) {
                        var item = collection_new[i];
                        collection_main.push(item);
                    }
                }

                return collection_main;
//            } catch (err) {
//                showErro(err.message + ' Operação cancelada.');
//            }
        };
        
        vm.groupBy = function (collection, fields, collection_name,callOnGroup) {
            var gps = [];


            if ( typeof collection_name != 'string' ) {
                collection_name = 'COLLECTION';
            }
            
            for (var i in collection) {
                var estacao = collection[i];


                var localized = false;
                for ( var y in gps ) {
                    var gp = gps[y];

                    var condicao_true = true;
                    if ( typeof fields == 'string' ) {
                        if ( gp[fields] != estacao[fields] ) {
                            condicao_true = false;
                        }
                    } else {
                        for ( var j in fields ) {
                            var condicao = fields[j];

                            if ( gp[condicao] != estacao[condicao] ) {
                                condicao_true = false;
                                break;
                            }
                        }
                    }

                    if ( condicao_true ) {
                        localized = true;
                        gp[collection_name].push(estacao);
                        callOnGroup != undefined ? callOnGroup(gp,estacao) : '';
                        break;
                    }
                }
                if ( localized == false ) {

                    var gp = {};
                    
                    gp[collection_name] = [estacao];

                    if ( typeof fields == 'string' ) {
                        gp[fields] = estacao[fields];
                    } else {
                        for ( var j in fields ) {
                            var condicao = fields[j];

                            gp[condicao] = estacao[condicao];
                        }
                    }
                    
                    callOnGroup != undefined ? callOnGroup(gp,estacao) : '';

                    gps.push(gp);
                }
            }
            
            return gps;
        };
        
        vm.distinct = function (collection, fields) {
            var gps = [];

            for (var i in collection) {
                var estacao = collection[i];


                var localized = false;
                for ( var y in gps ) {
                    var gp = gps[y];

                    var condicao_true = true;
                    for ( var j in fields ) {
                        var condicao = fields[j];

                        if ( gp[condicao] != estacao[condicao] ) {
                            condicao_true = false;
                            break;
                        }
                    }

                    if ( condicao_true ) {
                        localized = true;
                        break;
                    }
                }
                if ( localized == false ) {

                    gp = {};
                    
                    for ( var j in fields ) {
                        var condicao = fields[j];

                        gp[condicao] = estacao[condicao];
                    }

                    gps.push(gp);
                }
            }
            
            return gps;
        }
        
        /**
         * Liga dois arrays; Um pai e um filho
         * @param {Array} collection_main Array principal (Pai)
         * @param {Array} collection_bind Array a ser ligado (Filho)
         * @param {String} field_identifier Nome do atributo que liga os dois array
         * @param {String} name_bind Nome da relação entre os arrays. Default: BINDED
         * @return {Array} Retorna o array principal ligado ao array filho
         */
        vm.bind = function ( collection_main, collection_bind, field_identifier, name_bind ) {
            
            // Aplica o nome padrão para para o nome da relação
            var name_bind = (name_bind == undefined) ? 'BINDED' : name_bind;
                      
            // Passa por todos os itens da coleção principal
            for ( var i in collection_main ) {
                
                // Cria a variavel que recebe o item da coleção principal
                var item_main = collection_main[i];
                
                item_main[name_bind] = [];

                for ( var j = 0; j < collection_bind.length; j++) {
                    var item_bind = collection_bind[j];                

                    // Verifica se os dois objetos possuem o mesmo valor no campo identificador
                    if ( equalsByField(item_main,item_bind,field_identifier) ) {          
                        item_main[name_bind].push(item_bind);
                    }
                }
                
            }
            
            return collection_main;
        }
        
        vm.extendObjByField = function ( collection_main, obj_new, field ) {
                 
            var localized = false;
            for ( var i in collection_main ) {
                var item_main = collection_main[i];       
                
                var condicao_true = true;

                if ( typeof field == 'string' ) {
                    if ( item_main[field] != obj_new[field] ) {
                        condicao_true = false;
                    }
                } else {
                    for ( var k in field ) {
                        var condicao = field[k];

                        if ( item_main[condicao] != obj_new[condicao] ) {
                            condicao_true = false;
                            break;
                        }
                    }
                }
                
                if ( condicao_true ) {
                    localized = true;
                    angular.extend(item_main, obj_new);
                    break;
                }                
            }
            
            if ( localized == false ) {            
                collection_main.push(obj_new);
            }
            
            return collection_main;            
        }
        
    };
    
   
    /** @ngInject */
    var gcObject = function() {
        return {
            start: function (propertyName, prototype, func) {
                var propertyAccessor = '_' + propertyName;
                
                Object.defineProperty(prototype,
                propertyName, {
                get: function () {
                    return prototype[propertyAccessor];
                },
                set: function (newValue) {
                    prototype[propertyAccessor] = newValue;
                    if (angular.isFunction(func)) {
                        func(newValue);
                    }
                },
                enumerable: true,
                configurable: true
                });
            },
            calcField: function(propertyName, prototype, func) {
                Object.defineProperty(prototype,
                propertyName, {
                get: function () {
                    return func(prototype);
                },
                enumerable: true,
                configurable: true
                });
            },
            /**
             * Liga um array a um objecto; Liga filhos a um pai
             * @param {Array} collection_main Array principal (Pai)
             * @param {Array} collection_bind Array a ser ligado (Filho)
             * @param {String} field_identifier Nome do atributo que liga os dois array
             * @param {String} name_bind Nome da relação entre os arrays. Default: BINDED
             * @param {boolean} bind_if_exists Faz a ligação se o array pai possuir a propriedade do filho
             * @return {Array} Retorna o array principal ligado ao array filho
             */
            bind : function ( objectMain, collection_bind, field_identifier, name_bind ) {

                // Aplica o nome padrão para para o nome da relação
                var name_bind = (name_bind == undefined) ? 'BINDED' : name_bind;

                objectMain[name_bind] = [];

                for ( var j = 0; j < collection_bind.length; j++) {
                    var item_bind = collection_bind[j];                

                    // Verifica se os dois objetos possuem o mesmo valor no campo identificador
                    if ( equalsByField(objectMain,item_bind,field_identifier) ) {          
                        objectMain[name_bind].push(item_bind);
                    }
                }

                return objectMain;
            }                 
        };
    }    
    
    var gcClock = function($rootScope) {
        
        var vm = this;
        
        vm.TimeServer = function () {
//            $rootScope.$watch(function () {;
//               return Clock.DATETIME_SERVER;
//            }, function(val) {
////                $rootScope.$apply(function () {
//                            
//                    var time_server = moment(val).add(1, 's');
//                    return moment(time_server).format("YYYY-MM-DD HH:mm:ss"); 
////                });
//            });  
        };
        
    };
    
    gcClock.$inject = ['$rootScope'];
    
    var onFinishRender = function ($timeout) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                if (scope.$last === true) {
                    
                    var time = 0;
                    if ( attr.renderTime != undefined ) {
                        time = attr.renderTime;
                    }
                    
                    $timeout(function () {
                        scope.$emit(attr.onFinishRender);
                    },time);
                }
            }
        };
    };
    
    onFinishRender.$inject = ['$timeout'];
    
    function arraysEqual(a, b) {
      if (a === b) return true;
      if (a == null || b == null) return false;
      if (a.length != b.length) return false;

      // If you don't care about the order of the elements inside
      // the array, you should sort both arrays here.

      for (var i = 0; i < a.length; ++i) {
        if (a[i] !== b[i]) return false;
      }
      return true;
    }

    var gcOrderBy = function () {
        return {
            restrict: 'A',
            scope: {
                    model: '=gcOrderBy',
            },            
            link: function (scope, element, attr) {
                
                element.find('[field]').bind('click', function() {

                    var that = $(this);
                    $(this).closest('[gc-order-by').find('.span-order-by').remove();
                    
                    var fields = $(this).attr('field').split(',');
                    
                    scope.$apply(function(){
                        
                        if ( ! arraysEqual(fields, scope.model) ) {
                            scope.model = fields;
                            
                            $(that).append('<span style="margin-left: 5px;" class="glyphicon glyphicon-sort-by-attributes span-order-by"></span>');
                        } else {
                            
                            var ret = [];
                            for ( var i in fields ) {
                                var field = fields[i];
                                
                                field = '-'+field;
                                
                                ret.push(field);
                            }
                            
                            scope.model = ret;
                            
                            $(that).append('<span style="margin-left: 5px;" class="glyphicon glyphicon-sort-by-attributes-alt span-order-by"></span>');
                        }
                        
//                        for ( var i in fields ) {
//                            var field = fields[i];
                            
//                            if( scope.model == '-'+field){
//                                scope.model = field;
//
//                                $(that).append('<span style="margin-left: 5px;" class="glyphicon glyphicon-sort-by-attributes span-order-by"></span>');
//                            }else{
//                                scope.model = '-'+field;
//                                $(that).append('<span style="margin-left: 5px;" class="glyphicon glyphicon-sort-by-attributes-alt span-order-by"></span>');
//                            }
//                        }
                        
                    });
                });
                
//                  var src = elem.find('img').attr('src');
//
//                  // call your SmoothZoom here
//                  angular.element(attrs.options).css({'background-image':'url('+ scope.item.src +')'});
//                });             
            }
        };
    };


    var GcUtils = angular.module('gc-utils', []);
    
    GcUtils.service  ('gcCollection'  , gcCollection  );
    GcUtils.service  ('gcObject'      , gcObject      );
    GcUtils.service  ('gcClock'       , gcClock       );
    GcUtils.directive('onFinishRender', onFinishRender) ;
    GcUtils.directive('gcOrderBy'     , gcOrderBy     ) ;

    if (typeof module !== 'undefined' && module.exports) {
        module.exports = GcUtils.name;
    }
})(window, window.angular);