
		.sass('financeiro/20120.scss', cssPathPublic)
		
		.scripts([
			'financeiro/_20120/_20120.app.js',
			'financeiro/_20120/_20120.factory.rateio-tipo.js',
			'financeiro/_20120/_20120.controller.js'			
		], jsPathPublic+'/_20120.js')
		
		.addarray([
			cssPathPublic+'/20120.css',
			jsPathPublic+'/_20120.js'
		])