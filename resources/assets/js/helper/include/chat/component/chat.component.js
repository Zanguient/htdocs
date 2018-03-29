		
angular
	.module('app')
	.service('ChatService', ChatService)
	.controller('ChatController', ChatController)
	.component('chat', {
		templateUrl: '/chat/viewIndex',
		controller: 'ChatController'
	})
;