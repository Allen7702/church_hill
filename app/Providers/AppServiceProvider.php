<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\CentreDetail;
use App\Matangazo;
use App\SahihishaKanda;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

    
        view()->composer('*', function ($view) {

            $sahihisha_kanda=SahihishaKanda::first();
            $matangazo = Matangazo::latest()->where('uchapishaji','imechapishwa')->limit(4)->get();
         
            if(Auth::check()){
                //overall details of the church
                $church_details = CentreDetail::first();
                $view->with([
                    'church_details'=>$church_details,
                    'sahihisha_kanda'=>$sahihisha_kanda,
                    'matangazo'=>$matangazo
                    ]
                );
            }
            elseif(!(Auth::check())){
                $church_details = CentreDetail::first();
                $view->with([  'church_details'=>$church_details,
                'sahihisha_kanda'=>$sahihisha_kanda,
                'matangazo'=>$matangazo
            ]);
            }
            else{
                $church_details = CentreDetail::select('centre_name');
                $sahihisha_kanda=SahihishaKanda::first();
                return redirect('/home');
            }
        });
    }
}
