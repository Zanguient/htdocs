'use strict';

angular
	.module('app', [
		'vs-repeat', 
        'gc-find',
		'gc-ajax',
		'gc-transform',
		'gc-form',
		'gc-utils',
        'ngSanitize',
        'angular.filter',
        'datatables'        
	])
;
     
     

angular.module('app').directive('bsInit', function() {
    return function(scope, element, attrs) {         
        bootstrapInit();
    };
});

angular.module('app').directive('stringToNumber', function() {
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
});

angular.module('app').directive('ngUpdateHidden', function () {
    return {
        restrict: 'AE', //attribute or element
        scope: {},
        replace: true,
        require: 'ngModel',
        link: function (vm, elem, attr, ngModel) {
            vm.$watch(ngModel, function (nv) {
                elem.val(nv);
            });
            elem.change(function () { //bind the change event to hidden input
                vm.$apply(function () {
                    ngModel.$setViewValue(  elem.val());
                });
            });
        }
    };
});

angular.module('app').directive('ngRightClick', ['$parse',function($parse) {
    return function(scope, element, attrs) {
        var fn = $parse(attrs.ngRightClick);
        element.bind('contextmenu', function(event) {
            scope.$apply(function() {
                event.preventDefault();
                fn(scope, {$event:event});
            });
        });
    };
}]);


angular.module('app').filter('parseDate', function() {
    return function(input) {
        if ( input ) return new Date(input);
    };
});     