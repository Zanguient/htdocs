
		.sass('estoque/15070.scss', cssPathPublic)
		.scripts([
			'estoque/_15070/_15070.app.js',
			'estoque/_15070/_15070.factory.remessa.js',
			'estoque/_15070/_15070.factory.talao.js',
			'estoque/_15070/_15070.factory.consumo.js',
			'estoque/_15070/_15070.factory.filtro.js',
			'estoque/_15070/_15070.controller.js'			
		], jsPathPublic+'/_15070.js')
		.addarray([cssPathPublic+'/15070.css',jsPathPublic+'/_15070.js'])