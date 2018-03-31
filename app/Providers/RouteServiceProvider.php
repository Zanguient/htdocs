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
            
			require app_path('Http/Routes/Auth/Auth.php'          );
            
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
            require app_path('Http/Routes/Helper/DirectPrint.php' );

            require app_path('Http/Routes/Admin/_11000.php'     );
            require app_path('Http/Routes/Admin/_11040.php'     );
			require app_path('Http/Routes/Admin/_11001.php');require app_path('Http/Routes/Admin/_11002.php');#NOVALINHA#
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
