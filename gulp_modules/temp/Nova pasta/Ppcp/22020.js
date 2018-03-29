
		.sass('ppcp/22020.scss', cssPathPublic)
		.scripts([
			'helper/plugin/jquery-ui.min.js',
			'helper/plugin/jquery.alterclass.js',
			'ppcp/_22020.js'
		], jsPathPublic+'/_22020.js')
		.scripts('ppcp/_22020-Pronta-Entrega.js',jsPathPublic)
		.scripts('helper/lib/AutoUpdate.js',jsPathPublic)
		.addarray([cssPathPublic+'/22020.css',jsPathPublic+'/AutoUpdate.js',jsPathPublic+'/_22020.js',jsPathPublic+'/_22020-Pronta-Entrega.js'])