
		.sass('produto/27020.scss', cssPathPublic)
		.sass('produto/27020-modal.scss', cssPathPublic)
		
		.scripts([
			'produto/_27020/_27020.app.js',
			'produto/_27020/_27020.factory.filtro.js',
			'produto/_27020/_27020.factory.modelo.js',
			'produto/_27020/_27020.controller.js'			
		], jsPathPublic+'/_27020.js')
		
		.addarray([
			cssPathPublic+'/27020.css',
			cssPathPublic+'/27020-modal.css',
			jsPathPublic+'/_27020.js'
		])        