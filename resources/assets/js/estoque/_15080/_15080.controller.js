angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'gScope',
        'Filtro',
        'Produto',
        'Reposicao',
        'Lote'
    ];

	function Ctrl( $scope, $timeout, gScope, Filtro, Produto, Reposicao, Lote ) {

		var vm = this;

		vm.Filtro    = new Filtro();
		vm.Produto   = new Produto();
		vm.Reposicao = new Reposicao();
		vm.Lote      = new Lote();


        loading('.main-ctrl');        

        loading('hide');

        vm.Filtro.consultar().then(function(){
            
            for ( var i in vm.Produto.FAMILIAS ) {
                var familia = vm.Produto.FAMILIAS[i];
                
                familia.CHECKED = true;
            }
        });
        
        
        
//        $timeout(function () {
//            var container = $(".main-container");
//            var numberOfCol = 2;
//            $(".resize").css('height', 100/numberOfCol +'%');
//
//            var sibTotalHeight;
//            $(".resize-item").resizable({
//                handles: 's',
//                start: function(event, ui){
//                    sibTotalHeight = ui.originalSize.height + ui.originalElement.next().outerHeight();
//                },
//                stop: function(event, ui){     
//                    var cellPercentHeight=100 * ui.originalElement.outerHeight()/ container.innerHeight();
//                    
//                    ui.originalElement.css('height', cellPercentHeight + '%');  
//                    
//                    var nextCell = ui.originalElement.next();
//                    
//                    var nextPercentHeight=100 * nextCell.outerHeight()/container.innerHeight();
//                    
//                    nextCell.css('height', nextPercentHeight + '%');
//                },
//                resize: function(event, ui){ 
////                    $(this).mouseup();
//                    
//                    if ( ui.size.height > ( container.innerHeight() - 130 ) ) {
//                        $(this).mouseup();
//                        ui.originalElement.height(container.innerHeight() - 130);
//                    } else {
//                        ui.originalElement.next().height(container.innerHeight() - ui.size.height); 
//                    }
//                    console.log(ui.size.height + ' ' + ui.originalElement.next().height());
//                },
//                minHeight : 130
//            });
//            
//        });   

 
	}   
  