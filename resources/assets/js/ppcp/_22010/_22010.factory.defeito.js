
        
angular
    .module('app')
    .factory('Defeito', Defeito);
    

	Defeito.$inject = [
        '$ajax'
    ];

function Defeito($ajax) {

    /**
     * Constructor, with class name
     */
    function Defeito(firstName, lastName, role) {
        
        // Public properties, assigned to the instance ('this')
        this.firstName      = firstName;
        this.lastName       = lastName;
        this.role           = role;
    }
    
    /**
     * Private property
     */
    var url_base        = '_22010/defeitos';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    Defeito.prototype = {
        
        load : function (args) {
            return $ajax.post(url_base+'all', JSON.stringify(args), {contentType: 'application/json'});
        }
    };

    /**
     * Private function
     */
    function checkRole(role) {
      return possibleRoles.indexOf(role) !== -1;
    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    Defeito.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    Defeito.build = function (data) {
        
        if (!checkRole(data.role)) {
          return;
        }
        
        return new Defeito(
            data.first_name,
            data.last_name,
            data.role
//            Organisation.build(data.organisation) // another model
        );
    };

    /**
     * Return the constructor function
     */
    return Defeito;
};