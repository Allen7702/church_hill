<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CentreDetail;
use App\User;
use Alert;
use App\Familia;
use App\Kanda;
use App\Jumuiya;
use App\Mwanafamilia;
use App\MakundiRika;
use App\Kikundi;
use App\SadakaKuu;
use App\Zaka;
use DB;
use Carbon;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
         $user = auth()->user();

        /// dd($user->hasAnyPermission(['Kanda.read']));

        if((auth()->user()->ngazi == "administrator") || (auth()->user()->ngazi == "Parokia") || (auth()->user()->ngazi == "Jumuiya" || (auth()->user()->ngazi == "Kanda"))){
            $details = CentreDetail::get();

            if($details->isEmpty()){
                Alert::toast("Karibu kwenye mfumo wa Kanisa, Tafadhali jaza taarifa za parokia kabla ya kuendelea..","info");
                return redirect('centre_details');
            }
            else{

                //setting makundi rika
                $data_rikas = MakundiRika::latest()->get();

                if($data_rikas->isEmpty()){
                    Alert::toast("Kwa ufanisi wa mfumo tafadhali jaza makundi rika!","info")->autoClose(6000)->timerProgressBar(5500);
                    return redirect('makundi_rika');
                }

                $data_summary = $this->getSummary();
                $kanda = $data_summary['kanda'];
                $jumuiya = $data_summary['jumuiya'];
                $familia = $data_summary['familia'];
                $vyakitume = $data_summary['vyakitume'];
                $waumini_wote = $data_summary['wanafamilia'];

                $data_variable = $this->getVariables();

                $current_month = $data_variable['current_month'];
                $current_year = $data_variable['current_year'];
                $start_month = $data_variable['start_month'];
                $start_date = $data_variable['start_date'];
                $begin_with = Carbon::parse($start_date)->format('Y-m-d');
                $title = "utoaji wa sadaka $start_month - $current_month ($current_year)";

                // //overall year sum of sadaka
                // $sadaka_total = SadakaKuu::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

                $data_sadaka = SadakaKuu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
                    ->whereYear('tarehe', date('Y'))
                    ->orderBy('tarehe','asc')
                    ->groupBy(DB::raw("MONTH(tarehe)"))
                    ->get();
                
                $data_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
                    ->whereYear('tarehe', date('Y'))
                    ->orderBy('tarehe','asc')
                    ->groupBy(DB::raw("MONTH(tarehe)"))
                    ->get();  
                
                    $data_summary = $this->getSummary();
                    $kanda = $data_summary['kanda'];
                    $jumuiya = $data_summary['jumuiya'];
                    $familia = $data_summary['familia'];
                    $vyakitume = $data_summary['vyakitume'];
            
                //takwimu za kiroho kikanda
                $takwimu_za_kiroho = Kanda::get();

                return view('backend.admin_dashboard',compact(['kanda','jumuiya','familia','vyakitume','waumini_wote','data_sadaka','data_zaka','takwimu_za_kiroho']));
            }
        }
        else{
            return redirect('/login');
        }
    }

    private function getSummary(){
        //to be used by most methods

        $user = Auth::user();
        $user_ngazi = $user->ngazi;


        if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
        $kanda = Kanda::count();
        $jumuiya = Jumuiya::count();
        $familia = Familia::count();
        $wanafamilia = Mwanafamilia::count();
        $viongozi = User::whereNotNull('cheo')->where('ngazi','!=','administrator')->count();
        $vyakitume = Kikundi::count();

         }elseif($user_ngazi=='Kanda'){
        $mwanafamilia_d= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
        $familia_d = \App\Familia::find($mwanafamilia_d->familia);
        $jumuiya_d=\App\Jumuiya::where('jina_la_jumuiya',$familia_d->jina_la_jumuiya);
        $kanda_d=\App\Kanda::where('jina_la_kanda',$jumuiya_d->first()->jina_la_kanda);

            $jumuiya_data = Jumuiya::where('jina_la_kanda',$kanda_d->first()->jina_la_kanda);
            $familia_data=Familia::whereIn('jina_la_jumuiya',$jumuiya_data->pluck('jina_la_jumuiya'));

            $kanda = 1;
            $jumuiya = $jumuiya_data->count();
            $familia = $familia_data->count();
            $wanafamilia = Mwanafamilia::whereIn('familia',$familia_data->pluck('id'))->count();
            $viongozi = User::whereNotNull('cheo')->where('ngazi','!=','administrator')->count();
            $vyakitume = Kikundi::count();

         }elseif($user_ngazi=='Jumuiya'){
        $mwanafamilia_d= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
        $familia_d = \App\Familia::find($mwanafamilia_d->familia);
        $jumuiya_d=\App\Jumuiya::where('jina_la_jumuiya',$familia_d->jina_la_jumuiya);
        $kanda_d=\App\Kanda::where('jina_la_kanda',$jumuiya_d->first()->jina_la_kanda);


             $jumuiya_data = $jumuiya_d; 
             $familia_data = Familia::whereIn('jina_la_jumuiya',$jumuiya_data->pluck('jina_la_jumuiya'));
             $jumuiya = $jumuiya_data->count();
             $familia = $familia_data->count();
             $kanda = Kanda::count();
             $wanafamilia = Mwanafamilia::whereIn('familia',$familia_data->pluck('id'))->count();
             $viongozi = User::whereNotNull('cheo')->where('ngazi','!=','administrator')->count();
             $vyakitume = Kikundi::count();
          
         } 

        $data_summary = [
            'kanda' => $kanda,
            'jumuiya' => $jumuiya,
            'familia' => $familia,
            'wanafamilia' => $wanafamilia,
            'viongozi' => $viongozi,
            'vyakitume' => $vyakitume,
        ];

        return $data_summary;
    }

    //internal class variables
    private function getVariables(){
        $data_variable = [
            'current_year' => Carbon::now()->format('Y'),
            'current_month' => Carbon::now()->format('M'),
            'start_month' => "Jan",
            'start_date' => "01-01-".Carbon::now()->format('Y'), //this to get the first date of the year and current year
        ];    
        
        return $data_variable;
    }
}
