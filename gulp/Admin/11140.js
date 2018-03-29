	
		.sass('admin/11140.scss', cssPathPublic)
		.scripts([
			'admin/_11140/_11140.app.js',
			'admin/_11140/_11140.factory.create.js',
			'admin/_11140/_11140.factory.index.js',
			'admin/_11140/_11140.controller.js'
		], jsPathPublic+'/_11140.app.js')
		.addarray([cssPathPublic+'/11140.css',jsPathPublic+'/_11140.app.js'])