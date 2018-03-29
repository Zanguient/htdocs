
		.sass('workflow/29011.scss', cssPathPublic)
		.scripts([

			'helper/plugin/angular/ng-file-upload/ng-file-upload.min.js',

			'workflow/_29011/_29011.app.js',

			'workflow/_29011/component/_29011-index.component.js',
			'workflow/_29011/service/_29011-index.service.js',
			'workflow/_29011/controller/_29011-index.controller.js',

			'workflow/_29011/component/_29011-create.component.js',
			'workflow/_29011/service/_29011-create.service.js',
			'workflow/_29011/controller/_29011-create.controller.js',

			'workflow/_29011/component/_29011-info-geral.component.js',
			'workflow/_29011/service/_29011-info-geral.service.js',
			'workflow/_29011/controller/_29011-info-geral.controller.js',

			'workflow/_29010/component/_29010-consulta.component.js',
			'workflow/_29010/service/_29010-consulta.service.js',
			'workflow/_29010/controller/_29010-consulta.controller.js',

			'workflow/_29011/component/_29011-tarefa.component.js',
			'workflow/_29011/service/_29011-tarefa.service.js',
			'workflow/_29011/controller/_29011-tarefa.controller.js'

		], jsPathPublic+'/_29011.js')
		.addarray([cssPathPublic+'/29011.css',jsPathPublic+'/_29011.js'])