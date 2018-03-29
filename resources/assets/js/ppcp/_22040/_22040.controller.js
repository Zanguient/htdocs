angular
    .module('app')
    .value('gScope', {
        indexOfAttr : function(array,attr, value) {
            for(var i in array) {
                if(array[i][attr] === value) {
                    return i;
                }
            }
            return -1;
        }
    })
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$compile',
        '$timeout',
        'gScope',
        'Reposicao'
    ];

	function Ctrl( $scope, $compile, $timeout, gScope, Reposicao ) {

		var vm = this;

        vm.Reposicao         = new Reposicao();
        vm.gScope            = gScope;
	}   
    