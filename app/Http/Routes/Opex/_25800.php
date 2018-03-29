<?php
  
use App\Models\DTO\Opex\_25800;

 /**
  * Rotas do objeto 25800 (BSC-TV)
  * @package Opex
  * @category Rotas
  */
    
    Route::get('/_25800'                        , 'Opex\_25800Controller@show'                  );
    Route::get('/_25800/auto/{cod}/{desc}'      , 'Opex\_25800Controller@auto'                  );
    Route::get('/_25800/slid/{cod}/{desc}'      , 'Opex\_25800Controller@slid'                  );
    
    Route::get('/_25800/auto/{estab}/{familia}/{cod}' , 'Opex\_25800Controller@auto2'           );
    Route::get('/_25800/slid/{estab}/{familia}/{cod}' , 'Opex\_25800Controller@slid2'           );
   
    Route::post('/_25800/consultaprod'          , 'Opex\_25800Controller@consultaprod'          );
    Route::post('/_25800/consultabsc'           , 'Opex\_25800Controller@consultabsc'           );
    Route::post('/_25800/consultacomparativo'   , 'Opex\_25800Controller@consultacomparativo'   );
    Route::post('/_25800/consultaranking'       , 'Opex\_25800Controller@consultaranking'       );
    Route::post('/_25800/consultaanuncio'       , 'Opex\_25800Controller@consultaanuncio'       );
    Route::post('/_25800/letreiro'              , 'Opex\_25800Controller@letreiro'              );
    Route::post('/_25800/horacepo'              , 'Opex\_25800Controller@listaHCEPO'            );
    
    Route::post('/_25800/consultacomparativoG1'   , 'Opex\_25800Controller@consultacomparativoG1');
    
    Route::get('/_25800/calctrofeu/{mes}/{estab}', 'Opex\_25800Controller@calctrofeu');
    
    Route::post('/_25800/trofeu', 'Opex\_25800Controller@consultatrofeu');
    Route::post('/_25800/trofeuall', 'Opex\_25800Controller@consultatrofeuallgp');

    //Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

		//Página inicial
//		$router->get('/home', function() { return view('welcome'); });
	
    });