
		.sass('ppcp/22120.scss', cssPathPublic)
		.scripts([
			'helper/plugin/jquery-ui.min.js',
			'ppcp/_22120.js'
		], jsPathPublic+'/_22120.js')
		
		
		.scripts([
			'ppcp/_22120/_22120.app.js',
			'ppcp/_22120/_22120.factory.remessa-intermediaria.js',
			'ppcp/_22120/_22120.factory.remessa-componente.js',
			'ppcp/_22120/_22120.controller.js'			
		], jsPathPublic+'/_22120.ng.js')
		
		.addarray([
			cssPathPublic+'/22120.css',
			jsPathPublic+'/_22120.js',
			jsPathPublic+'/_22120.ng.js'
		])