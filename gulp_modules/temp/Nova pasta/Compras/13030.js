
		.sass('compras/13030.scss', cssPathPublic)
		.scripts(  'compras/_13030.js' , jsPathPublic)
		.addarray([cssPathPublic+'/13030.css',jsPathPublic+'/_13030.js'])
		

		.sass('compras/13030.ng.scss', cssPathPublic)
		.scripts([
			'compras/_13030/_13030.app.js',
			'compras/_13030/_13030.factory.filtro.js',
			'compras/_13030/_13030.factory.cota.js',
			'compras/_13030/_13030.factory.cota-ggf.js',
			'compras/_13030/_13030.factory.cota-extra.js',
			'compras/_13030/_13030.factory.cota-reducao.js',
			'compras/_13030/_13030.factory.cota-incluir.js',
			'compras/_13030/_13030.factory.cota-ccusto.js',
			'compras/_13030/_13030.factory.cota-ccontabil.js',
			'compras/_13030/_13030.factory.cota-periodo.js',
			'compras/_13030/_13030.factory.cota-detalhe.js',
			'compras/_13030/_13030.controller.js'			
		], jsPathPublic+'/_13030.ng.js')
		.addarray([cssPathPublic+'/13030.ng.css',jsPathPublic+'/_13030.ng.js'])		