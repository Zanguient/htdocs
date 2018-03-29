angular
    .module('app')
    .factory('TalaoConsumo', TalaoConsumo);
    

	TalaoConsumo.$inject = [        
        '$ajax',
        '$q',
        '$window',
        'gScope',
        'gcObject',
        'gcCollection'
    ];

function TalaoConsumo($ajax,$q,$window,gScope,gcObject,gcCollection) {

    /**
     * Constructor, with class name
     */
    function TalaoConsumo(data) {
        if (data) {
            this.setData(data);
        }
    }
        
    /**
     * Private property
     */
    var url_base        = '_22010/api/talao/consumo';
    var possibleRoles   = ['admin', 'editor', 'guest'];

    /**
     * Public method, assigned to prototype
     */
    TalaoConsumo.prototype = {
        selectionar : function (consumo) {
            this.SELECTED = consumo;
        },
        setData: function(data) {
            angular.extend(this, data);
        }
    };

    /**
     * Private function
     */
    function fn () {
        
    }

    /**
     * Static property
     * Using copy to prevent modifications to private property
     */
    TalaoConsumo.possibleRoles = angular.copy(possibleRoles);

    /**
     * Static method, assigned to class
     * Instance ('this') is not available in static context
     */
    TalaoConsumo.build = function (data) {
        
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
    return TalaoConsumo;
};