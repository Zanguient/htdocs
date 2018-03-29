<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
	
    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            
			require app_path('Http/Routes/Admin/_11000.php'       );
			require app_path('Http/Routes/Admin/_11010.php'       );
			require app_path('Http/Routes/Admin/_11020.php'       );
			require app_path('Http/Routes/Admin/_11040.php'       );
			require app_path('Http/Routes/Admin/_11050.php'       );     
			require app_path('Http/Routes/Admin/_11060.php'		  );
			require app_path('Http/Routes/Auth/Auth.php'          );
			require app_path('Http/Routes/Chamados/_26010.php'    );
			require app_path('Http/Routes/Compras/_13010.php'     );
			require app_path('Http/Routes/Compras/_13020.php'     );
			require app_path('Http/Routes/Compras/_13021.php'     );
			require app_path('Http/Routes/Compras/_13030.php'     );
            require app_path('Http/Routes/Compras/_13040.php'     );
            require app_path('Http/Routes/Compras/_13050.php'     );
            require app_path('Http/Routes/Compras/_13060.php'     );
            require app_path('Http/Routes/Contabil/_17010.php'    );
			require app_path('Http/Routes/Estoque/_15010.php'     );
			require app_path('Http/Routes/Estoque/_15020.php'     );
			require app_path('Http/Routes/Estoque/_15040.php'     );
            require app_path('Http/Routes/Estoque/_15050.php'     );
			require app_path('Http/Routes/Patrimonio/_16010.php'  );
            require app_path('Http/Routes/Financeiro/_20010.php'  );   
            require app_path('Http/Routes/Financeiro/_20020.php'  );     
            require app_path('Http/Routes/Financeiro/_20030.php'  );       
            require app_path('Http/Routes/Fiscal/_21010.php'      );
			require app_path('Http/Routes/Helper/Arquivo.php'     );
			require app_path('Http/Routes/Helper/Chat.php'     	  );
            require app_path('Http/Routes/Helper/Print.php'       );
			require app_path('Http/Routes/Helper/ConsultaAll.php' );
            require app_path('Http/Routes/Helper/ConsultaTabs.php');
			require app_path('Http/Routes/Helper/Download.php'    );
			require app_path('Http/Routes/Helper/Historico.php'   );
			require app_path('Http/Routes/Helper/Menu.php'        );
			require app_path('Http/Routes/Helper/Msg.php'         );
			require app_path('Http/Routes/Helper/Turno.php'       );
			//require app_path('Http/Routes/Helper/Report.php'	  );
            require app_path('Http/Routes/Helper/DirectPrint.php' );
            require app_path('Http/Routes/Logistica/_14010.php'   );  
            require app_path('Http/Routes/Ppcp/_22020.php'        );  
			require app_path('Http/Routes/Opex/_25200.php'        );         
			require app_path('Http/Routes/Opex/_25600.php'        );
            require app_path('Http/Routes/Opex/_25800.php'        );
            require app_path('Http/Routes/Opex/_25700.php'        ); 
			require app_path('Http/Routes/Opex/_25900.php'		  );
			require app_path('Http/Routes/Pessoal/_23010.php'     );
			require app_path('Http/Routes/Ppcp/_22040.php'        );
			require app_path('Http/Routes/Ppcp/_22050.php'        );
			require app_path('Http/Routes/Ppcp/_22060.php'        );
			require app_path('Http/Routes/Ppcp/_22070.php'        );
			require app_path('Http/Routes/Ppcp/_22100.php'        );
			require app_path('Http/Routes/Ppcp/_22110.php'        );
			require app_path('Http/Routes/Produto/_27010.php'     );
			require app_path('Http/Routes/Produto/_27020.php'     );
			require app_path('Http/Routes/Produto/_27030.php'     );
			require app_path('Http/Routes/Produto/_27040.php'     );
			require app_path('Http/Routes/Produto/_27050.php'     );
			require app_path('Http/Routes/Vendas/_12010.php'      );
			require app_path('Http/Routes/Vendas/_12020.php'      );
            require app_path('Http/Routes/Vendas/_12030.php'      ); 
			require app_path('Http/Routes/Vendas/_12040.php'      );
			require app_path('Http/Routes/Ppcp/_22021.php'		  );
			require app_path('Http/Routes/Ppcp/_22120.php'		  );
			require app_path('Http/Routes/Admin/_11001.php'		  );
			require app_path('Http/Routes/Vendas/_12050.php'	  );
			require app_path('Http/Routes/Admin/_11070.php'		  );
			require app_path('Http/Routes/Opex/_25010.php'		  );
			require app_path('Http/Routes/Opex/_25011.php'	 	  );
			require app_path('Http/Routes/Admin/_11080.php'       );
			require app_path('Http/Routes/Admin/_11090.php'       );
			require app_path('Http/Routes/Relatorio/_28000.php');
			require app_path('Http/Routes/Estoque/_15060.php');  
			require app_path('Http/Routes/Ppcp/_22130.php');
			require app_path('Http/Routes/Ppcp/_22140.php');
			require app_path('Http/Routes/Ppcp/_22150.php');
			require app_path('Http/Routes/Vendas/_12060.php');
			require app_path('Http/Routes/Vendas/_12070.php'); 
			require app_path('Http/Routes/Workflow/_29010.php');
			require app_path('Http/Routes/Admin/_11100.php');
			require app_path('Http/Routes/Ppcp/_22010.php');
			require app_path('Http/Routes/Workflow/_29011.php');
			require app_path('Http/Routes/Admin/_11110.php');
			require app_path('Http/Routes/Workflow/_29012.php');
			require app_path('Http/Routes/Vendas/_12080.php');
			require app_path('Http/Routes/Admin/_11140.php');
			require app_path('Http/Routes/Admin/_11150.php');
			require app_path('Http/Routes/Admin/_11180.php');
			require app_path('Http/Routes/Estoque/_15070.php');
			require app_path('Http/Routes/Estoque/_15080.php');
			require app_path('Http/Routes/Ppcp/_22160.php');
            require app_path('Http/Routes/Ppcp/_22030.php');
            require app_path('Http/Routes/Ppcp/_22170.php');
            require app_path('Http/Routes/Ppcp/_22180.php');
			require app_path('Http/Routes/Admin/_11190.php');
			require app_path('Http/Routes/Vendas/_12090.php');
			require app_path('Http/Routes/Estoque/_15100.php');
			require app_path('Http/Routes/Estoque/_15090.php');
			require app_path('Http/Routes/Estoque/_15090.php');
			require app_path('Http/Routes/Vendas/_12100.php');
			require app_path('Http/Routes/Workflow/_29013.php');
			require app_path('Http/Routes/Estoque/_15110.php');
			require app_path('Http/Routes/Custo/_31010.php');
			require app_path('Http/Routes/Ppcp/_22190.php');
			require app_path('Http/Routes/Estoque/_15120.php');
			require app_path('Http/Routes/Financeiro/_20100.php');
			require app_path('Http/Routes/Financeiro/_20110.php');
			require app_path('Http/Routes/Ppcp/_22190.php');
			require app_path('Http/Routes/Pessoal/_23020.php');
			require app_path('Http/Routes/Admin/_11200.php');
			require app_path('Http/Routes/Chamados/_26021.php');
			require app_path('Http/Routes/Custo/_31020.php');
			require app_path('Http/Routes/Custo/_31030.php');
			require app_path('Http/Routes/Custo/_31040.php');
			require app_path('Http/Routes/Custo/_31050.php');
			require app_path('Http/Routes/Custo/_31060.php');
            require app_path('Http/Routes/Financeiro/_20120.php'  );
            require app_path('Http/Routes/Admin/_11005.php');
            require app_path('Http/Routes/Logistica/_14020.php');            
            require app_path('Http/Routes/Pessoal/_23030.php');
            require app_path('Http/Routes/Pessoal/_23031.php');
            require app_path('Http/Routes/Pessoal/_23032.php');
            require app_path('Http/Routes/Pessoal/_23033.php');
            require app_path('Http/Routes/Pessoal/_23034.php'); 
            require app_path('Http/Routes/Pessoal/_23035.php');
            require app_path('Http/Routes/Pessoal/_23036.php');
            require app_path('Http/Routes/Custo/_31070.php');
            require app_path('Http/Routes/Custo/_31080.php');            
            require app_path('Http/Routes/Admin/_11210.php');
            require app_path('Http/Routes/Pessoal/_23037.php');
            require app_path('Http/Routes/Patrimonio/_16020.php');
            require app_path('Http/Routes/Admin/_11220.php');require app_path('Http/Routes/Pessoal/_23038.php');#NOVALINHA#
        });
    }
	
	
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);
    }
}
