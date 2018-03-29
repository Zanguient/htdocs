
		.sass('vendas/12040.scss', cssPathPublic)

		.scripts([ 

			'vendas/_12040/_12040.app.js',

			'vendas/_12040/component/_12040-pedido-index.component.js',
			'vendas/_12040/service/_12040-pedido-index.service.js',
			'vendas/_12040/controller/_12040-pedido-index.controller.js',

			'vendas/_12060/component/_12060-consultar.component.js',
			'vendas/_12060/service/_12060-consultar.service.js',
			'vendas/_12060/controller/_12060-consultar.controller.js',

			'vendas/_12070/component/_12070-por-representante.component.js',
			'vendas/_12070/service/_12070-por-representante.service.js',
			'vendas/_12070/controller/_12070-por-representante.controller.js',

			'vendas/_12040/component/_12040-pedido-create.component.js',
			'vendas/_12040/service/_12040-pedido-create.service.js',
			'vendas/_12040/controller/_12040-pedido-create.controller.js',

			'vendas/_12040/component/_12040-info-geral.component.js',
			'vendas/_12040/service/_12040-info-geral.service.js',
			'vendas/_12040/controller/_12040-info-geral.controller.js',

			'vendas/_12040/component/_12040-pedido-item-escolhido.component.js',
			'vendas/_12040/service/_12040-pedido-item-escolhido.service.js',
			'vendas/_12040/controller/_12040-pedido-item-escolhido.controller.js',

			'vendas/_12040/component/_12040-pedido-item.component.js',
			'vendas/_12040/service/_12040-pedido-item.service.js',
			'vendas/_12040/controller/_12040-pedido-item.controller.js',

			'produto/_27020/component/_27020-por-cliente.component.js',
			'produto/_27020/service/_27020-por-cliente.service.js',
			'produto/_27020/controller/_27020-por-cliente.controller.js',

			'produto/_27030/component/_27030-por-modelo.component.js',
			'produto/_27030/service/_27030-por-modelo.service.js',
			'produto/_27030/controller/_27030-por-modelo.controller.js',

			'produto/_27050/service/_27050-por-modelo-e-cor.service.js',

			'produto/_27030/component/_27030-consultar-cor.component.js',
			'produto/_27030/service/_27030-consultar-cor.service.js',
			'produto/_27030/controller/_27030-consultar-cor.controller.js',

			'vendas/_12040/component/_12040-liberacao.component.js',
			'vendas/_12040/service/_12040-liberacao.service.js',
			'vendas/_12040/controller/_12040-liberacao.controller.js',

			'helper/include/chat/component/chat.component.js',
			'helper/include/chat/service/chat.service.js',
			'helper/include/chat/controller/chat.controller.js'

		], jsPathPublic+'/_12040.js')
		
		.addarray([
			cssPathPublic+'/12040.css',
			jsPathPublic+'/_12040.js'
		])