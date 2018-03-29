
		.sass('patrimonio/16010.scss', cssPathPublic)
		
		.scripts([
			'patrimonio/_16010/_16010.app.js',
			'patrimonio/_16010/_16010.factory.filtro.js',
			'patrimonio/_16010/_16010.factory.imobilizado.js',
			'patrimonio/_16010/_16010.factory.imobilizado-item.js',
			'patrimonio/_16010/_16010.factory.imobilizado-ccusto.js',
			'patrimonio/_16010/_16010.factory.demonstrativo-depreciacao-anual.js',
			'patrimonio/_16010/_16010.controller.js'			
		], jsPathPublic+'/_16010.js')
		
		.addarray([
			cssPathPublic+'/16010.css',
			jsPathPublic+'/_16010.js'
		])