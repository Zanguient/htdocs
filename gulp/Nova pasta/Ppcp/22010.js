
		.sass('ppcp/22010.scss', cssPathPublic)
		.scripts([
			'ppcp/_22010/_22010.app.js',
			'ppcp/_22010/_22010.service.js',
			'ppcp/_22010/_22010.factory.totalizador-diario.js',
			'ppcp/_22010/_22010.factory.talao-tempo.js',
			'ppcp/_22010/_22010.factory.talao-ficha.js',
			'ppcp/_22010/_22010.factory.talao-defeito.js',
			'ppcp/_22010/_22010.factory.talao-detalhe.js',
			'ppcp/_22010/_22010.factory.talao-consumo.js',
			'ppcp/_22010/_22010.factory.talao-historico.js',
			'ppcp/_22010/_22010.factory.talao-composicao.js',
			'ppcp/_22010/_22010.factory.talao-produzido.js',
			'ppcp/_22010/_22010.factory.talao-produzir.js',
			'ppcp/_22010/_22010.factory.defeito.js',
			'ppcp/_22010/_22010.factory.filtro.js',
			'ppcp/_22010/_22010.factory.acao.js',
			'ppcp/_22010/_22010.controller.js'
		], jsPathPublic+'/_22010.app.js')
		.scripts([
			'helper/plugin/jquery-ui.min.js',
			'helper/plugin/jquery.alterclass.js',
			'ppcp/_22010.js'
		], jsPathPublic+'/_22010.js')
		.scripts('ppcp/_22010-Pronta-Entrega.js',jsPathPublic)
		.scripts('helper/lib/AutoUpdate.js',jsPathPublic)
		.addarray([
			cssPathPublic+'/22010.css',
			jsPathPublic+'/AutoUpdate.js',
			jsPathPublic+'/_22010.js',
			jsPathPublic+'/_22010.app.js',
			jsPathPublic+'/_22010-Pronta-Entrega.js'
		])