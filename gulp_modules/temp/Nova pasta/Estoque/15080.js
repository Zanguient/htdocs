
		.sass('estoque/15080.scss', cssPathPublic)
		.scripts([
			'estoque/_15080/_15080.app.js',
			'estoque/_15080/_15080.factory.lote.js',
			'estoque/_15080/_15080.factory.reposicao.js',
			'estoque/_15080/_15080.factory.produto.js',
			'estoque/_15080/_15080.factory.filtro.js',
			'estoque/_15080/_15080.controller.js'			
		], jsPathPublic+'/_15080.js')
		.addarray([cssPathPublic+'/15080.css',jsPathPublic+'/_15080.js'])