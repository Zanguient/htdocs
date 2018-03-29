
		.sass('workflow/29012.scss', cssPathPublic)
		.scripts([
			
			'helper/plugin/angular/ng-file-upload/ng-file-upload.min.js',

			'workflow/_29012/_29012.app.js',

			'workflow/_29012/component/_29012-index.component.js',
			'workflow/_29012/service/_29012-index.service.js',
			'workflow/_29012/controller/_29012-index.controller.js',

			'workflow/_29012/component/_29012-create.component.js',
			'workflow/_29012/service/_29012-create.service.js',
			'workflow/_29012/controller/_29012-create.controller.js',

			'workflow/_29012/component/_29012-info-geral.component.js',
			'workflow/_29012/service/_29012-info-geral.service.js',
			'workflow/_29012/controller/_29012-info-geral.controller.js',

			'workflow/_29012/component/_29012-tarefa.component.js',
			'workflow/_29012/service/_29012-tarefa.service.js',
			'workflow/_29012/controller/_29012-tarefa.controller.js'

		], jsPathPublic+'/_29012.js')
		.addarray([cssPathPublic+'/29012.css',jsPathPublic+'/_29012.js'])