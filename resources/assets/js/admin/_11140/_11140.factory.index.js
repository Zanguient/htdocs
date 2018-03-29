      
angular
    .module('app')
    .factory('Index', Index);
    

	Index.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        'gScope'
    ];

function Index($ajax, $httpParamSerializer, $rootScope, gScope) {

    /**
     * Constructor, with class name
     */
    function Index(data) {
        if (data) {
            this.setData(data);
        }
    }
    
    var url_base  = '/_11140/';

    /**
     * Public method, assigned to prototype
     */
    Index.prototype = {
		PAINEIS : [],
        setData: function(data) {
            angular.extend(this, data);
        },        
        consultar : function (args) {

        },
        uriHistory : function() {
            window.history.replaceState('', '', encodeURI(urlhost + '/_11140?'+$httpParamSerializer(this)));
        },
		init: function (){
			var ds = {
				STATUS : 1
			};
			
			var that = this;

			$ajax.post('/_11140/Consultar',ds)
				.then(function(response) {
					that.PAINEIS = response;					
				}
			);
		},
        openLink:function(id){
            window.location.href = urlhost + url_base +  id;
        }
    };

    /**
     * Return the constructor function
     */
    return Index;
};