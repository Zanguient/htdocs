<?php

/**####################################################
*  #                                                  #
*  #	Tenha muita atenção ao alterar este arquivo   #
*  #                                                  #
*/ ####################################################

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\Agd_Email::class,
        \App\Console\Commands\Agd_Sql::class,
        \App\Console\Commands\Env_Email::class,
        \App\Console\Commands\Agd_Log::class,
        \App\Console\Commands\Agd_Agd::class,
        \App\Console\Commands\Agd_Est::class,
        \App\Console\Commands\Con_dolar::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		
		$log_agd_path = app_path() . '/Console/Commands/Saidas/';
        
		$schedule->command('Agd_Email')->withoutOverlapping()->sendOutputTo($log_agd_path.'Agd_Email.txt')->before(function () {
            echo "Agd_Email Iniciado em: ".date("d/m/Y H:i:s"). "\n";
         })
         ->after(function () {
            echo "Agd_Email Finalizada em: ".date("d/m/Y H:i:s"). "\n";
         }); //de 1 em 1 minutos
         
         
		$schedule->command('Agd_Sql')->withoutOverlapping()->sendOutputTo($log_agd_path.'Agd_Sql.txt')->before(function () {
            echo "Agd_Sql Iniciado em: ".date("d/m/Y H:i:s"). "\n";
         })
         ->after(function () {
             echo "Agd_Sql Finalizada em: ".date("d/m/Y H:i:s"). "\n";
         }); // de 1 em 1 minuto
         
         
		$schedule->command('Env_Email')->withoutOverlapping()->sendOutputTo($log_agd_path.'Env_Email.txt')->before(function () {
            echo "Env_Email Iniciado em: ".date("d/m/Y H:i:s"). "\n";
         })
         ->after(function () {
            echo "Env_Email Finalizada em: ".date("d/m/Y H:i:s"). "\n";
         }); //de 1 em 1 minutos
         
         $schedule->command('Agd_Log')->withoutOverlapping()->sendOutputTo($log_agd_path.'Agd_Log.txt')->before(function () {
            echo "Agd_Log Iniciado em: ".date("d/m/Y H:i:s"). "\n";
         })
         ->after(function () {
             echo "Agd_Log Finalizada em: ".date("d/m/Y H:i:s"). "\n";
         }); // de 1 em 1 minuto

         $schedule->command('Agd_Agd')->withoutOverlapping()->sendOutputTo($log_agd_path.'Agd_Agd.txt')->before(function () {
            echo "Agd_Agd Iniciado em: ".date("d/m/Y H:i:s"). "\n";
         })
         ->after(function () {
             echo "Agd_Agd Finalizada em: ".date("d/m/Y H:i:s"). "\n";
         }); // de 1 em 1 minuto

         $schedule->command('Agd_Est')->everyFiveMinutes()->withoutOverlapping()->sendOutputTo($log_agd_path.'Agd_Est.txt')
         ->before(function () {
            echo "Agd_Est Iniciado em: ".date("d/m/Y H:i:s"). "\n";
         })
         ->after(function () {
             echo "Agd_Est Finalizada em: ".date("d/m/Y H:i:s"). "\n";
         }); // de 1 em 1 minuto

         $schedule->command('Con_dolar')->withoutOverlapping()->sendOutputTo($log_agd_path.'Con_dolar.txt')->before(function () {
            echo "Consulta Dólar Iniciado em: ".date("d/m/Y H:i:s"). "\n";
         })
         ->after(function () {
             echo "Consulta Dólar Iniciado em: ".date("d/m/Y H:i:s"). "\n";
         }); // de 1 em 1 minuto
			
    }
}
