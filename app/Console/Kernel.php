<?php

namespace App\Console;

use App\Mwanafamilia;
use App\ZakaKilaMwezi;
use Carbon\Carbon;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
        $now= Carbon::now();
        $month=$now->month;

       $wanafamilia=Mwanafamilia::all();
       foreach($wanafamilia as $familia)
       {
           //check if mwanafilia exists in an existing month
           $zaka_kila_mwezi=ZakaKilaMwezi::where('mwanafamilia_id',$familia->id)
           ->where('mwezi',$month)->first();
          
           if(is_null($zaka_kila_mwezi)){
               ZakaKilaMwezi::create([
                  'mwanafamilia_id'=>$familia->id,
                   'mwezi'=>$month,
               ]);
           }
           
       }
    })->everyMinute();

    $schedule->command('queue:work')->everyMinute()->withoutOverlapping();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
