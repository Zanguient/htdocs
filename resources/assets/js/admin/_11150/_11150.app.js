/**
 * _11140 - Cadastro de paineis de Casos
 */
'use strict';

angular
	.module('app', [
		'vs-repeat', 
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils',
		'gc-find',
        'ngFileUpload',
        'ngSanitize'
	])

	.filter('inArray', function($filter){
	    return function(list, arrayFilter, element){
	        if(arrayFilter){
	            return $filter("filter")(list, function(listItem){
	                return arrayFilter.indexOf(listItem[element]) != -1;
	            });
	        }
	    };
	});
;