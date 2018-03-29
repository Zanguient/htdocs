<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	//Extende o blade para declaração de variável na view
    	//Ex.: @php $i = 1
         Blade::extend(function($value) {
 		    return preg_replace('/\@php(.+)/', '<?php ${1}; ?>', $value);
 		});

        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
