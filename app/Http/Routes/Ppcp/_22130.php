<?php

 /**
  * Rotas do objeto _22130 - Conformacao
  * @package Ppcp
  * @category Rotas
  */

	//Rotas protegidas.
  Route::group(['middleware' => 'auth'], function($router) {
        
    $router->resource('_22130', 'Ppcp\_22130Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY

    $router->POST('_22130/getSubUp'		       , 'Ppcp\_22130Controller@getSubUp'           );
    $router->POST('_22130/getUp'		       , 'Ppcp\_22130Controller@getUp'              );

    $router->POST('_22130/consulta'		       , 'Ppcp\_22130Controller@consulta'           );

    $router->GET('socket'                      , 'SocketController@index'                   );
    $router->POST('sendmessage'                , 'SocketController@sendMessage'             );
    $router->GET('writemessage'                , 'SocketController@writemessage'            );

    $router->POST('_22130/filtarTaloes'        , 'Ppcp\_22130Controller@filtarTaloes'       );
    $router->POST('_22130/atualizarTalao'      , 'Ppcp\_22130Controller@atualizarTalao'     );

    $router->POST('_22130/acoesTaloes'         , 'Ppcp\_22130Controller@acoesTaloes'        );
    $router->POST('_22130/pararEstacao'        , 'Ppcp\_22130Controller@pararEstacao'       );
    $router->POST('_22130/getInfoTalao'        , 'Ppcp\_22130Controller@getInfoTalao'       );

    $router->POST('_22130/ferramentasLivres'   , 'Ppcp\_22130Controller@ferramentasLivres'  );
    $router->POST('_22130/trocarFerramenta'    , 'Ppcp\_22130Controller@trocarFerramenta'   );

    $router->POST('_22130/consultarMatriz'     , 'Ppcp\_22130Controller@consultarMatriz'    );

    $router->POST('_22130/iniciarSetup'        , 'Ppcp\_22130Controller@iniciarSetup'       );
    $router->POST('_22130/jornadaIntervalo'    , 'Ppcp\_22130Controller@jornadaIntervalo'   );
    $router->POST('_22130/jornadaGravar'       , 'Ppcp\_22130Controller@jornadaGravar'      );   

    $router->POST('_22130/getProducao'         , 'Ppcp\_22130Controller@getProducao'        );
    $router->POST('_22130/getProducaoTalao'    , 'Ppcp\_22130Controller@getProducaoTalao'   );

    $router->POST('_22130/justIneficiencia'    , 'Ppcp\_22130Controller@justIneficiencia'   );
    $router->POST('_22130/getComposicao'       , 'Ppcp\_22130Controller@getComposicao'      );

    

    $router->GET('_22130/{ESTAB}/{GP}/{GPDESC}/{UP}/{UPDESC}/{ESTACAO}/{ESTACAODESC}' , 'Ppcp\_22130Controller@auto');

	});
