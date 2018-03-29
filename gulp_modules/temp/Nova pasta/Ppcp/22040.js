
		.sass('ppcp/22040.scss', cssPathPublic)
		.scripts('ppcp/_22040-index.js' , jsPathPublic)
		.scripts('ppcp/_22040-create.js' , jsPathPublic)
		.scripts([
			'ppcp/_22040/_22040.app.js',
			'ppcp/_22040/_22040.factory.reposicao.js',
			'ppcp/_22040/_22040.controller.js'			
		], jsPathPublic+'/_22040.ng.js')	
		.addarray([
			cssPathPublic+'/22040.css',
			jsPathPublic+'/_22040-index.js',
			jsPathPublic+'/_22040-create.js',
			jsPathPublic+'/_22040.ng.js'
		])