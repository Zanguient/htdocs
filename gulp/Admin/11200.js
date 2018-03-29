
		.sass('admin/11200.scss', cssPathPublic)
		.scripts([
			'admin/_11200/_11200.app.js',
			'admin/_11200/_11200.factory.gp.js',
			'admin/_11200/_11200.factory.operador.js',
			'admin/_11200/_11200.factory.talao.js',
			'admin/_11200/_11200.factory.filtro.js',
			'admin/_11200/_11200.controller.js'			
		], jsPathPublic+'/_11200.js')
		.addarray([cssPathPublic+'/11200.css',jsPathPublic+'/_11200.js'])