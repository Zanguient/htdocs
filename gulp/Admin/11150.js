	
		.sass('admin/11150.scss', cssPathPublic)
		.scripts([
			'admin/_11150/_11150.app.js',
			'admin/_11150/_11150.script.js',
			'admin/_11140/_11140.factory.create.js',
			'helper/plugin/angular/ng-file-upload/ng-file-upload.min.js',
			'admin/_11150/_11150.arquivos.js',
			'admin/_11150/_11150.controller.js'
		], jsPathPublic+'/_11150.app.js')
		.addarray([cssPathPublic+'/11150.css',jsPathPublic+'/_11150.app.js'])