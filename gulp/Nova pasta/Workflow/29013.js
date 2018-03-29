
		.sass('workflow/29013.scss', cssPathPublic)
		.scripts([

			'helper/plugin/angular/ng-file-upload/ng-file-upload.min.js',
			
			'helper/plugin/raphael.min.js',
			'helper/plugin/flowchart.min.js',

			'workflow/_29013/_29013.app.js',

			'workflow/_29013/component/_29013-index.component.js',
			'workflow/_29013/service/_29013-index.service.js',
			'workflow/_29013/controller/_29013-index.controller.js'

		], jsPathPublic+'/_29013.js')
		.addarray([cssPathPublic+'/29013.css',jsPathPublic+'/_29013.js'])