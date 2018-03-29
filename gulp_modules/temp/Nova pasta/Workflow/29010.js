
		.sass('workflow/29010.scss', cssPathPublic)
		
		.scripts([

			'helper/plugin/angular/ng-file-upload/ng-file-upload.min.js',

			'workflow/_29010/_29010.app.js',

			'workflow/_29010/component/_29010-workflow-index.component.js',
			'workflow/_29010/service/_29010-workflow-index.service.js',
			'workflow/_29010/controller/_29010-workflow-index.controller.js',

			'workflow/_29010/component/_29010-workflow-create.component.js',
			'workflow/_29010/service/_29010-workflow-create.service.js',
			'workflow/_29010/controller/_29010-workflow-create.controller.js',

			'workflow/_29010/component/_29010-info-geral.component.js',
			'workflow/_29010/controller/_29010-info-geral.controller.js',

			'workflow/_29010/component/_29010-tarefa.component.js',
			'workflow/_29010/service/_29010-tarefa.service.js',
			'workflow/_29010/controller/_29010-tarefa.controller.js'

		], jsPathPublic+'/_29010.js')

		.addarray([cssPathPublic+'/29010.css',jsPathPublic+'/_29010.js'])