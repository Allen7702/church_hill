<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AinaZaMichango;
use App\AkauntiZaBenki;
use App\MichangoTaslimu;
use App\MichangoBenki;
use App\Mwanafamilia;
use App\Kanda;
use App\Jumuiya;
use Carbon;
use Auth;
use Validator;
use DB;
use PDF;
use Alert;
use App\AhadiAinayamichango;
use App\AwamuMichango;
use App\Familia;
use App\Zaka;
use Illuminate\Http\Response;

class MichangoController extends Controller
{
    //the index of michango
    public function michango_takwimu(Request $request){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        
        $title = "Utoaji wa michango $start_month - $current_month ($current_year)";


        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $michango_benki = MichangoBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
            $michango_taslimu = MichangoTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
            $michango_total = $michango_benki + $michango_taslimu;
            //special for chart of benki
            $takwimu_benki = MichangoBenki::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereYear('tarehe', date('Y'))
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();  
            //special for chart of taslimu
            $takwimu_taslimu = MichangoTaslimu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereYear('tarehe', date('Y'))
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();
    //    $zaka_mwezi = Zaka::whereMonth('tarehe',$mwezi)->whereYear('tarehe',date('Y'))->sum('kiasi');
        }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $familia=Familia::whereIn('jina_la_jumuiya',$jumuiyas->pluck('jina_la_jumuiya'));

                $wanajumuiya = Mwanafamilia::whereIn('familia',$familia->pluck('id'));


                $michango_benki = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
        
                $michango_taslimu = MichangoTaslimu::whereIn('mwanafamilia',$wanajumuiya->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
                
                $michango_total = $michango_benki + $michango_taslimu;
        
                //special for chart of benki
                $takwimu_benki = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya->pluck('id'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
                ->whereYear('tarehe', date('Y'))
                ->orderBy('tarehe','asc')
                ->groupBy(DB::raw("MONTH(tarehe)"))
                ->get();  
        
                //special for chart of taslimu
                $takwimu_taslimu = MichangoTaslimu::whereIn('mwanafamilia',$wanajumuiya->pluck('id'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
                ->whereYear('tarehe', date('Y'))
                ->orderBy('tarehe','asc')
                ->groupBy(DB::raw("MONTH(tarehe)"))
                ->get();

         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya = $jumuiya; 

             $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
             $wanajumuiya = Mwanafamilia::whereIn('familia',$familia->pluck('id'));

             $michango_benki = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
     
             $michango_taslimu = MichangoTaslimu::whereIn('mwanafamilia',$wanajumuiya->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
             
             $michango_total = $michango_benki + $michango_taslimu;
     
             //special for chart of benki
             $takwimu_benki = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya->pluck('id'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
             ->whereYear('tarehe', date('Y'))
             ->orderBy('tarehe','asc')
             ->groupBy(DB::raw("MONTH(tarehe)"))
             ->get();  
             //special for chart of taslimu
             $takwimu_taslimu = MichangoTaslimu::whereIn('mwanafamilia',$wanajumuiya->pluck('id'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
             ->whereYear('tarehe', date('Y'))
             ->orderBy('tarehe','asc')
             ->groupBy(DB::raw("MONTH(tarehe)"))
             ->get();
            } 
        }  
        
        return view('backend.michango.michango_takwimu',compact(['title','michango_benki','michango_taslimu','michango_total','takwimu_benki','takwimu_taslimu']));
    }

    //function to handle michango kijumuiya
    public function michango_taslimu_kijumuiya(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango kijumuiya $start_month - $current_month ($current_year)";


        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
            ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
            ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
    
            //generate or populate the data interms of jumuiya
            $jumuiya_data = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
            ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya')
            ->whereBetween('michango_taslimus.tarehe',[$begin_with,Carbon::now()])
            ->orderBy('kiasi','DESC')
            ->groupBy('familias.jina_la_jumuiya')
            ->get();
    
            $michangos = AinaZaMichango::latest()->get();
            $michango_total = MichangoTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
            $michango_taslimu = MichangoTaslimu::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')->get(['michango_taslimus.*','mwanafamilias.jina_kamili']);

    }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $familia=Familia::whereIn('jina_la_jumuiya',$jumuiyas->pluck('jina_la_jumuiya'));

                $wanajumuiya_d = Mwanafamilia::whereIn('familia',$familia->pluck('id'));
                $wanajumuiya = Mwanafamilia::whereIn('id',$wanajumuiya_d->pluck('id'))->leftJoin('familias','familias.id','mwanafamilias.familia')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
                ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
        
                //generate or populate the data interms of jumuiya
                $jumuiya_data = MichangoTaslimu::whereIn('id',$wanajumuiya_d->pluck('id'))->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
                ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya')
                ->whereBetween('michango_taslimus.tarehe',[$begin_with,Carbon::now()])
                ->orderBy('kiasi','DESC')
                ->groupBy('familias.jina_la_jumuiya')
                ->get();
        
                $michangos = AinaZaMichango::latest()->get();
        
                $michango_total = MichangoTaslimu::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
        
                $michango_taslimu = MichangoTaslimu::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')->get(['michango_taslimus.*','mwanafamilias.jina_kamili']);

         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya = $jumuiya; 

             $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
             $wanajumuiya_d = Mwanafamilia::whereIn('familia',$familia->pluck('id'));

             $wanajumuiya = Mwanafamilia::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('familias','familias.id','mwanafamilias.familia')
             ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
             ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
     
             //generate or populate the data interms of jumuiya
             $jumuiya_data = MichangoTaslimu::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
             ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
             ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya')
             ->whereBetween('michango_taslimus.tarehe',[$begin_with,Carbon::now()])
             ->orderBy('kiasi','DESC')
             ->groupBy('familias.jina_la_jumuiya')
             ->get();
     
             $michangos = AinaZaMichango::latest()->get();
     
             $michango_total = MichangoTaslimu::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
          
             
             $michango_taslimu = MichangoTaslimu::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')->get(['michango_taslimus.*','mwanafamilias.jina_kamili']);

            } 
        } 

        return view('backend.michango.michango_taslimu_kijumuiya',compact(['title','michango_taslimu','wanajumuiya','michangos','michango_total','jumuiya_data']));
    }

    public function ajax_check_michango_ahadi(Request $request)
    {
         $aina_ya_mchango_jina= trim(str_replace("\\t",'',$request->aina_ya_mchango));
        $aina_ya_mchango=AinaZaMichango::where('aina_ya_mchango',$aina_ya_mchango_jina)->first();
               $html='';
               $chagua_awamu='Chagua awamu';
               $awamu='Awamu';
        if(!is_null($aina_ya_mchango->ahadi_ya_aina_za_mchango)){
             
            for($i = 1; $i <=$aina_ya_mchango->ahadi_ya_aina_za_mchango->idadi_ya_awamu; $i++){
            $html.='<option value="'.$i.'">'.$awamu." ".$i.'</option>';

            } 
               echo json_encode([
                 'options'=>$html,
                 'success'=>'found'
               ]);
        }else{
              echo json_encode([
                'success'=>'not found'
              ]);
        }
         
    }

    //function to handle michango pesa taslimu in jumuiya husika
    public function michango_taslimu_jumuiya_husika($jumuiya){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango pesa taslimu $jumuiya $start_month - $current_month ($current_year)";

        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        //generate or populate the data interms of jumuiya
        $jumuiya_michango = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'michango_taslimus.aina_ya_mchango')
        ->whereBetween('michango_taslimus.tarehe',[$begin_with,Carbon::now()])
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('kiasi','DESC')
        ->groupBy('michango_taslimus.aina_ya_mchango')
        ->get();

        $michangos = AinaZaMichango::latest()->get();

        $michango_total = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
        ->sum('michango_taslimus.kiasi');

        $michango_taslimu = MichangoTaslimu::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')->get(['michango_taslimus.*','mwanafamilias.jina_kamili']);
        return view('backend.michango.michango_taslimu_jumuiya_husika',compact(['title','michango_taslimu','wanajumuiya','michangos','michango_total','jumuiya_michango','jumuiya']));
    }

    //function to handle the mchango in specific jumuiya
    public function michango_taslimu_jumuiya_mchango_husika($mchango, $jumuiya){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango pesa taslimu $jumuiya $start_month - $current_month ($current_year)";

        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        //generate or populate the data interms of jumuiya
        $jumuiya_mchango_husika = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'michango_taslimus.aina_ya_mchango','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','mwanafamilias.id as mwanafamilia_id')
        ->whereBetween('michango_taslimus.tarehe',[$begin_with,Carbon::now()])
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_taslimus.aina_ya_mchango',$mchango)
        ->orderBy('kiasi','DESC')
        ->groupBy('mwanafamilias.jina_kamili')
        ->get();

        $michangos = AinaZaMichango::latest()->get();

        $michango_total = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_taslimus.aina_ya_mchango',$mchango)
        ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
        ->sum('michango_taslimus.kiasi');

        $michango_taslimu = MichangoTaslimu::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')->get(['michango_taslimus.*','mwanafamilias.jina_kamili']);
        return view('backend.michango.michango_taslimu_jumuiya_mchango_husika',compact(['title','michango_taslimu','wanajumuiya','michangos','michango_total','jumuiya_mchango_husika','jumuiya','mchango']));
    }

    //the index of michango taslimu
    public function michango_taslimu(Request $request){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "Utoaji wa michango pesa taslimu $start_month - $current_month ($current_year)";

       
        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
            ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
            ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
    
            $michangos = AinaZaMichango::latest()->get();
    
            $michango_total = MichangoTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
    
            $michango_taslimu = MichangoTaslimu::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')->get(['michango_taslimus.*','mwanafamilias.jina_kamili']);
                 
    }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $familia=Familia::whereIn('jina_la_jumuiya',$jumuiyas->pluck('jina_la_jumuiya'));
                $wanajumuiya_d = Mwanafamilia::whereIn('familia',$familia->pluck('id'));
                //new 

                $wanajumuiya = Mwanafamilia::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('familias','familias.id','mwanafamilias.familia')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
                ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
        
                $michangos = AinaZaMichango::latest()->get();
        
                $michango_total = MichangoTaslimu::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
          
                $michango_taslimu = MichangoTaslimu::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')->get(['michango_taslimus.*','mwanafamilias.jina_kamili']);
   
         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya = $jumuiya; 

             $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
             $wanajumuiya_d = Mwanafamilia::whereIn('familia',$familia->pluck('id'));

             $wanajumuiya = Mwanafamilia::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('familias','familias.id','mwanafamilias.familia')
             ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
             ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
     
             $michangos = AinaZaMichango::latest()->get();
     
             $michango_total = MichangoTaslimu::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
             
             $michango_taslimu = MichangoTaslimu::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')->get(['michango_taslimus.*','mwanafamilias.jina_kamili']);

            } 
        } 
        
        return view('backend.michango.michango_taslimu',compact(['title','michango_taslimu','wanajumuiya','michangos','michango_total']));
    }

    //the index of michango benki
    public function michango_benki(Request $request){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "Utoaji wa michango benki $start_month - $current_month ($current_year)";

        //new 

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
 
            $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
            ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
            ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
    
            //obtaining aina za michango
            $michangos = AinaZaMichango::latest()->get();
    
            //getting the akaunti za benki
            $akaunti = AkauntiZaBenki::latest()->get();
    
            $michango_total = MichangoBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
    
            $michango_benki = MichangoBenki::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
             
    }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $familia=Familia::whereIn('jina_la_jumuiya',$jumuiyas->pluck('jina_la_jumuiya'));
                $wanajumuiya_d = Mwanafamilia::whereIn('familia',$familia->pluck('id'));
                //new 

                $wanajumuiya = Mwanafamilia::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('familias','familias.id','mwanafamilias.familia')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
                ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
        
                $michangos = AinaZaMichango::latest()->get();

                $akaunti = AkauntiZaBenki::latest()->get();
        
                $michango_total = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
    
                $michango_benki = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
             
         
         
            }elseif($user_ngazi=='Jumuiya'){
             $jumuiya = $jumuiya; 

             $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
             $wanajumuiya_d = Mwanafamilia::whereIn('familia',$familia->pluck('id'));

             $wanajumuiya = Mwanafamilia::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('familias','familias.id','mwanafamilias.familia')
             ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
             ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
     
             $michangos = AinaZaMichango::latest()->get();
             $akaunti = AkauntiZaBenki::latest()->get();
        
             $michango_total = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
 
             $michango_benki = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
          
            } 
        } 

        return view('backend.michango.michango_benki',compact(['title','michango_benki','wanajumuiya','michangos','michango_total','akaunti']));
    }

    //function to handle the michango taslimu
    public function michango_taslimu_store(Request $request){

       // dd($request->awamu);
        //validating data
        $must = [
            'mwanafamilia' => 'required',
            'aina_ya_mchango' => 'required',
            'tarehe' => 'required',
            'kiasi' => 'required',
        ];




        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza tarehe, kiasi, aina ya mchango na mwanajumuiya.."]);
        }

        else{
            //getting data
            $data_save = [
            'aina_ya_mchango' => $request->aina_ya_mchango,
            'maelezo' => $request->maelezo,
            'tarehe' => $request->tarehe,
            'kiasi' => $request->kiasi,
            'mwanafamilia' => $request->mwanafamilia,
            'imewekwa' => auth()->user()->email,
            ];

            $success = MichangoTaslimu::create($data_save);

            if($success){

                if(!is_null($request->awamu)){

                  //  $mwanfamilia=Mwanafamilia::find($request->mwanafamilia);
                    $mwanafamilia=Mwanafamilia::find($request->mwanafamilia);
                    $familia=Familia::find($mwanafamilia->familia);
                     
                    $jumuiya=Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya)->first();

                    $aina_ya_mchango_jina= trim(str_replace("\\t",'',$request->aina_ya_mchango));
                    $aina_ya_mchango=AinaZaMichango::where('aina_ya_mchango',$aina_ya_mchango_jina)->first();
                    AwamuMichango::create([
                      'michango_taslimu_id'=>$success->id,
                      'awamu'=>$request->awamu,
                      'mwanafamilia_id'=>$request->mwanafamilia,
                      'jumuiya_id'=>$jumuiya->id,
                      'tarehe'=>$request->tarehe,
                      'ahadi_ainayamichango_id'=> $aina_ya_mchango->ahadi_ya_aina_za_mchango->id,
                      'kiasi'=>$request->kiasi
                    ]);

                }
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la mchango $request->mwanafamilia, kiasi $request->kiasi";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Mchango umeongezwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuongeza mchango"]]);
            }
        }
    }

    //function to handle the michango benki
    public function michango_benki_store(Request $request){
        //validating data
        $must = [
            'mwanafamilia' => 'required',
            'aina_ya_mchango' => 'required',
            'tarehe' => 'required',
            'kiasi' => 'required',
            'akaunti_namba' => 'required',
            'nambari_ya_nukushi' => 'required|unique:michango_benkis',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza tarehe, kiasi, aina ya mchango, akaunti, namba ya nukushi na mwanajumuiya.."]);
        }

        else{
            //getting data
            $data_save = [
                'aina_ya_mchango' => $request->aina_ya_mchango,
                'maelezo' => $request->maelezo,
                'tarehe' => $request->tarehe,
                'kiasi' => $request->kiasi,
                'mwanafamilia' => $request->mwanafamilia,
                'nambari_ya_nukushi' => $request->nambari_ya_nukushi,
                'akaunti_namba' => $request->akaunti_namba,
                'imewekwa' => auth()->user()->email,
            ];

            $success = MichangoBenki::create($data_save);

            if($success){   
                //tracking

                if(!is_null($request->awamu)){

                    $mwanafamilia=Mwanafamilia::find($request->mwanafamilia);
                    $familia=Familia::find($mwanafamilia->familia);
                     
                    $jumuiya=Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya)->first();
                    
                    $aina_ya_mchango_jina= trim(str_replace("\\t",'',$request->aina_ya_mchango));
                    $aina_ya_mchango=AinaZaMichango::where('aina_ya_mchango',$aina_ya_mchango_jina)->first();
                    AwamuMichango::create([
                      'michango_taslimu_id'=>$success->id,
                      'awamu'=>$request->awamu,
                      'mwanafamilia_id'=>$request->mwanafamilia,
                      'jumuiya_id'=>$jumuiya->id,
                      'tarehe'=>$request->tarehe,
                      'ahadi_ainayamichango_id'=> $aina_ya_mchango->ahadi_ya_aina_za_mchango->id,
                      'kiasi'=>$request->kiasi
                    ]);

                }
                
                $message = "Ongezo";
                $data = "Ongezo la mchango $request->mwanafamilia, kiasi $request->kiasi namba ya kumbukumbu $request->nambari_ya_nukushi";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Mchango umeongezwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuongeza mchango"]]);
            }
        }
    }

    //getting id to edit
    public function michango_taslimu_edit($id){
        $data = MichangoTaslimu::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //getting id to edit
    public function michango_benki_edit($id){
        $data = MichangoBenki::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //function to handle the updating the taslimu payment
    public function michango_taslimu_update(Request $request, MichangoTaslimu $model){
        //getting existing data first
        $existing = $model::findOrFail($request->hidden_id);

        $must = [
            'mwanafamilia_update' => 'required',
            'aina_ya_mchango' => 'required',
            'tarehe' => 'required',
            'kiasi' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza tarehe, kiasi, aina ya mchango na mwanajumuiya.."]);
        }

        else{
            //getting data
            $data_update = [
                'aina_ya_mchango' => $request->aina_ya_mchango,
                'maelezo' => $request->maelezo,
                'tarehe' => $request->tarehe,
                'kiasi' => $request->kiasi,
                'mwanafamilia' => $request->mwanafamilia_update,
                'imewekwa' => auth()->user()->email,
            ];

            $success = MichangoTaslimu::whereId($request->hidden_id)->update($data_update);

            if($success){
                
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la mchango $request->mwanafamilia_update, kiasi $request->kiasi";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Mchango umebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili mchango"]]);
            }
        }
    }

    //function to handle the updating the benki payment
    public function michango_benki_update(Request $request, MichangoBenki $model){
        //getting existing data first
        $existing = $model::findOrFail($request->hidden_id);

        //validating data
        $must = [
            'mwanafamilia_update' => 'required',
            'aina_ya_mchango' => 'required',
            'tarehe' => 'required',
            'kiasi' => 'required',
            'akaunti_namba' => 'required',
            'nambari_ya_nukushi' => 'required|unique:michango_benkis,nambari_ya_nukushi,'.$existing->id,
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza tarehe, kiasi, aina ya mchango, akaunti, namba ya nukushi na mwanajumuiya.."]);
        }

        else{
            //getting data
            $data_update = [
                'aina_ya_mchango' => $request->aina_ya_mchango,
                'maelezo' => $request->maelezo,
                'tarehe' => $request->tarehe,
                'kiasi' => $request->kiasi,
                'mwanafamilia' => $request->mwanafamilia_update,
                'nambari_ya_nukushi' => $request->nambari_ya_nukushi,
                'akaunti_namba' => $request->akaunti_namba,
                'imewekwa' => auth()->user()->email,
            ];

            $success = MichangoBenki::whereId($request->hidden_id)->update($data_update);

            if($success){   
                //tracking
                $message = "Badiliko";
                $data = "Ongezo la mchango wa $request->mwanafamilia, kiasi $request->kiasi nambari ya nukushi $request->nambari_ya_nukushi";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Mchango umebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili mchango"]]);
            }
        }
    }

    //function to handle the deletion of michango taslimu
    public function michango_taslimu_destroy($id){
        //getting data
        $data = MichangoTaslimu::findOrFail($id);
        $kiasi = $data->kiasi;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa mchango taslimu $kiasi";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Mchango umefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Mchango haujafutwa jaribu tena.."]]);
        }
    }

    //function to handle the deletion of michango benki
    public function michango_benki_destroy($id){
        //getting data
        $data = MichangoBenki::findOrFail($id);
        $kiasi = $data->kiasi;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa mchango benki $kiasi";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Mchango umefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Mchango haujafutwa jaribu tena.."]]);
        }
    }

    //function to handle the michango taslimu montly
    public function michango_taslimu_miezi(){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "Utoaji wa michango taslimu $start_month - $current_month ($current_year)";

        //summing the monthly interval
        $michango_total = MichangoTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_taslimu_miezi = MichangoTaslimu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();  
        
        return view('backend.michango.michango_taslimu_miezi',compact(['michango_taslimu_miezi','title','michango_total']));
    }

    //function to handle the specific month
    public function michango_taslimu_mwezi_husika($id){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango pesa taslimu mwezi $id/$current_year";

        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        $michangos = AinaZaMichango::latest()->get();

        $michango_total = MichangoTaslimu::whereMonth('tarehe',$id)->whereYear('tarehe',Carbon::now()->format('Y'))->sum('kiasi');

        $mwezi = $id;

        $michango_taslimu = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya')
        ->whereMonth('michango_taslimus.tarehe',$id)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->orderBy('kiasi','DESC')
        ->groupBy('familias.jina_la_jumuiya')
        ->get();

        return view('backend.michango.michango_taslimu_kijumuiya_mwezi_husika',compact(['title','michango_taslimu','wanajumuiya','michangos','michango_total','mwezi']));
    }

    //function to handle the michango taslimu mwezi jumuiya husika group by michango
    public function michango_taslimu_jumuiya_mwezi_husika($mwezi,$jumuiya){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango jumuiya $jumuiya mwezi $mwezi/$current_year";

        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        $michangos = AinaZaMichango::latest()->get();

        $michango_total = MichangoTaslimu::whereMonth('michango_taslimus.tarehe',$mwezi)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->sum('michango_taslimus.kiasi');

        $michango_taslimu = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya','michango_taslimus.aina_ya_mchango')
        ->whereMonth('michango_taslimus.tarehe',$mwezi)
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->orderBy('kiasi','DESC')
        ->groupBy('michango_taslimus.aina_ya_mchango')
        ->get();

        return view('backend.michango.michango_taslimu_jumuiya_mwezi_husika',compact(['title','michango_taslimu','wanajumuiya','michangos','michango_total','mwezi','jumuiya']));
    }

    //function to handle jumuiya mwezi mchango husika group by mtoaji
    public function michango_taslimu_jumuiya_mwezi_mchango_husika($mwezi,$jumuiya,$mchango){
        
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "$mchango jumuiya $jumuiya mwezi $mwezi/$current_year";

        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        $michangos = AinaZaMichango::latest()->get();

        $michango_total = MichangoTaslimu::whereMonth('michango_taslimus.tarehe',$mwezi)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_taslimus.aina_ya_mchango',$mchango)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->sum('michango_taslimus.kiasi');

        $michango_taslimu = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya','michango_taslimus.aina_ya_mchango','mwanafamilias.jina_kamili','mwanafamilias.id as mwanafamilia_id')
        ->whereMonth('michango_taslimus.tarehe',$mwezi)
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->where('michango_taslimus.aina_ya_mchango',$mchango)
        ->orderBy('kiasi','DESC')
        ->groupBy('mwanafamilias.id')
        ->get();

        return view('backend.michango.michango_taslimu_jumuiya_mwezi_mchango_husika',compact(['title','michango_taslimu','wanajumuiya','michangos','michango_total','mwezi','jumuiya','mchango']));

    }

    //function to handle the 
    public function michango_benki_kijumuiya(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango benki kijumuiya $start_month - $current_month ($current_year)";



        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
 
            $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
            ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
            ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
    
            //getting the akaunti za benki
            $akaunti = AkauntiZaBenki::latest()->get();
    
            //generate or populate the data interms of jumuiya
            $jumuiya_data = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
            ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'familias.jina_la_jumuiya')
            ->whereBetween('michango_benkis.tarehe',[$begin_with,Carbon::now()])
            ->orderBy('kiasi','DESC')
            ->groupBy('familias.jina_la_jumuiya')
            ->get();
    
            $michangos = AinaZaMichango::latest()->get();
    
            $michango_total = MichangoBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
    
            $michango_benki = MichangoBenki::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
            ->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
            
    }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $familia=Familia::whereIn('jina_la_jumuiya',$jumuiyas->pluck('jina_la_jumuiya'));
                $wanajumuiya_d = Mwanafamilia::whereIn('familia',$familia->pluck('id'));
                //new
                $wanajumuiya = Mwanafamilia::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('familias','familias.id','mwanafamilias.familia')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
                ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);


                $akaunti = AkauntiZaBenki::latest()->get();
    
                //generate or populate the data interms of jumuiya
                $jumuiya_data = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
                ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'familias.jina_la_jumuiya')
                ->whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))
                ->whereBetween('michango_benkis.tarehe',[$begin_with,Carbon::now()])
                ->orderBy('kiasi','DESC')
                ->groupBy('familias.jina_la_jumuiya')
                ->get();
        
                $michangos = AinaZaMichango::latest()->get();
                $michango_total = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
                $michango_benki = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
                ->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
                
         
            }elseif($user_ngazi=='Jumuiya'){
             $jumuiya = $jumuiya; 

             $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
             $wanajumuiya_d = Mwanafamilia::whereIn('familia',$familia->pluck('id'));

             $wanajumuiya = Mwanafamilia::whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))->leftJoin('familias','familias.id','mwanafamilias.familia')
             ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
             ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

             $akaunti = AkauntiZaBenki::latest()->get();
 
             //generate or populate the data interms of jumuiya
             $jumuiya_data = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
             ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
             ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'familias.jina_la_jumuiya')
             ->whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))
             ->whereBetween('michango_benkis.tarehe',[$begin_with,Carbon::now()])
             ->orderBy('kiasi','DESC')
             ->groupBy('familias.jina_la_jumuiya')
             ->get();
     
             $michangos = AinaZaMichango::latest()->get();
             $michango_total = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
             $michango_benki = MichangoBenki::whereIn('mwanafamilia',$wanajumuiya_d->pluck('id'))->latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
             ->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
             
          
            } 
        } 


        return view('backend.michango.michango_benki_kijumuiya',compact(['title','michango_benki','wanajumuiya','michangos','michango_total','akaunti','jumuiya_data']));
    }

    //function to handle michango of benki within a specific jumuiya
    public function michango_benki_jumuiya_husika($jumuiya){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "michango benki jumuiya $jumuiya $start_month - $current_month ($current_year)";
        
        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        //getting the akaunti za benki
        $akaunti = AkauntiZaBenki::latest()->get();

        //generate or populate the data interms of jumuiya
        $jumuiya_michango = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'michango_benkis.aina_ya_mchango')
        ->whereBetween('michango_benkis.tarehe',[$begin_with,Carbon::now()])
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('kiasi','DESC')
        ->groupBy('michango_benkis.aina_ya_mchango')
        ->get();

        $michangos = AinaZaMichango::latest()->get();

        $michango_total = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
        ->sum('michango_benkis.kiasi');

        $michango_benki = MichangoBenki::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
        return view('backend.michango.michango_benki_jumuiya_husika',compact(['title','michango_benki','wanajumuiya','michangos','michango_total','akaunti','jumuiya_michango','jumuiya']));
    }

    //function to handle michango_benki_jumuiya_mchango_husika group by mwanajumuiya
    public function michango_benki_jumuiya_mchango_husika($mchango, $jumuiya){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "michango benki jumuiya $jumuiya $start_month - $current_month ($current_year)";
        
        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        //getting the akaunti za benki
        $akaunti = AkauntiZaBenki::latest()->get();

        //generate or populate the data interms of jumuiya/mchango/mwanafamilia
        $jumuiya_michango_husika = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'michango_benkis.aina_ya_mchango','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','mwanafamilias.id as mwanafamilia_id')
        ->whereBetween('michango_benkis.tarehe',[$begin_with,Carbon::now()])
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_benkis.aina_ya_mchango',$mchango)
        ->orderBy('kiasi','DESC')
        ->groupBy('mwanafamilias.jina_kamili')
        ->get();

        $michangos = AinaZaMichango::latest()->get();

        $michango_total = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_benkis.aina_ya_mchango',$mchango)
        ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
        ->sum('michango_benkis.kiasi');

        $michango_benki = MichangoBenki::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
        return view('backend.michango.michango_benki_jumuiya_mchango_husika',compact(['title','michango_benki','wanajumuiya','michangos','michango_total','akaunti','jumuiya_michango_husika','jumuiya','mchango']));
    }

    //function to handle the michango benki montly
    public function michango_benki_miezi(){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "Utoaji wa michango benki $start_month - $current_month ($current_year)";

        //summing the monthly interval
        $michango_total = MichangoBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_benki_miezi = MichangoBenki::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();  
        
        return view('backend.michango.michango_benki_miezi',compact(['michango_benki_miezi','title','michango_total']));
    }

    //function to handle the specific month
    public function michango_benki_mwezi_husika($id){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango benki mwezi $id/$current_year";

        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        //obtaining aina za michango
        $michangos = AinaZaMichango::latest()->get();

        //getting the akaunti za benki
        $akaunti = AkauntiZaBenki::latest()->get();

        $michango_total = MichangoBenki::whereMonth('tarehe',$id)->whereYear('tarehe',Carbon::now()->format('Y'))->sum('kiasi');

        $michango_benki = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'familias.jina_la_jumuiya')
        ->whereMonth('michango_benkis.tarehe',$id)
        ->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))
        ->orderBy('kiasi','DESC')
        ->groupBy('familias.jina_la_jumuiya')
        ->get();

        $mwezi = $id;

        return view('backend.michango.michango_benki_kijumuiya_mwezi_husika',compact(['title','michango_benki','wanajumuiya','michangos','michango_total','akaunti','mwezi']));
    }

    //function to handle the michango taslimu mwezi jumuiya husika group by michango
    public function michango_benki_jumuiya_mwezi_husika($mwezi,$jumuiya){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango benki jumuiya $jumuiya mwezi $mwezi/$current_year";

        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        //obtaining aina za michango
        $michangos = AinaZaMichango::latest()->get();

        //getting the akaunti za benki
        $akaunti = AkauntiZaBenki::latest()->get();

        $michango_total = MichangoBenki::whereMonth('michango_benkis.tarehe',$mwezi)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))
        ->sum('michango_benkis.kiasi');

        $michango_benki = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'familias.jina_la_jumuiya','michango_benkis.aina_ya_mchango')
        ->whereMonth('michango_benkis.tarehe',$mwezi)
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))
        ->orderBy('kiasi','DESC')
        ->groupBy('michango_benkis.aina_ya_mchango')
        ->get();

        return view('backend.michango.michango_benki_jumuiya_mwezi_husika',compact(['title','michango_benki','wanajumuiya','michangos','michango_total','mwezi','jumuiya','akaunti']));
    }

    //function to handle benki michango within a specific jumuiya, specific month grouped by mwanafamilia
    public function michango_benki_jumuiya_mwezi_mchango_husika($mwezi,$jumuiya,$mchango){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "$mchango benki jumuiya $jumuiya mwezi $mwezi/$current_year";

        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        $michangos = AinaZaMichango::latest()->get();

        $michango_total = MichangoBenki::whereMonth('michango_benkis.tarehe',$mwezi)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_benkis.aina_ya_mchango',$mchango)
        ->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))
        ->sum('michango_benkis.kiasi');

        $michango_benki = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'familias.jina_la_jumuiya','michango_benkis.aina_ya_mchango','mwanafamilias.jina_kamili','mwanafamilias.id as mwanafamilia_id')
        ->whereMonth('michango_benkis.tarehe',$mwezi)
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))
        ->where('michango_benkis.aina_ya_mchango',$mchango)
        ->orderBy('kiasi','DESC')
        ->groupBy('mwanafamilias.id')
        ->get();

        //getting the akaunti za benki
        $akaunti = AkauntiZaBenki::latest()->get();

        return view('backend.michango.michango_benki_jumuiya_mwezi_mchango_husika',compact(['title','michango_benki','wanajumuiya','michangos','michango_total','mwezi','jumuiya','mchango','akaunti']));

    }

    //function to view zaka mkupuo
    public function michango_taslimu_mkupuo_index(){
        $title = "Uwekaji wa michango kwa mkupuo";
        $action = "mwanzo";
        $jumuiya = Jumuiya::latest()->get();

        return view('backend.michango.michango_taslimu_mkupuo',compact(['title','jumuiya','action']));
    }

    //function to pull jumuiyas
    public function michango_taslimu_mkupuo_vuta_jumuiya(Request $request){

        $selected_jumuiya = $request->jumuiya;

        if($selected_jumuiya ==""){
            Alert::toast("Tafadhali chagua jumuiya husika","info");
            return back();
        }
        //getting details of members from selected jumuiya
        $jumuiya_title = $selected_jumuiya;

        $jumuiya = Kanda::latest('kandas.created_at')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya']);

        //specifying aina za michango
        $aina_za_michango = AinaZaMichango::latest()->get();

        //setting title
        $title = "Uwekaji mchango jumuiya ya $jumuiya_title";

        //list of members
        $members = Mwanafamilia::where('familias.jina_la_jumuiya',$selected_jumuiya)
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->orderBy('mwanafamilias.jina_kamili','asc')
        ->get(['mwanafamilias.jina_kamili','mwanafamilias.id as mwanajumuiya_id']);

        //this handle the populatin of data to the table
        $action = "mkupuo";

        return view('backend.michango.michango_taslimu_mkupuo', compact(['jumuiya','title','members','action','selected_jumuiya','aina_za_michango']));
    }


    //michango mkupuo store
    public function michango_taslimu_mkupuo_store(Request $request){
        
        //getting data
        $sum = 0;
        $idadi = 0;

        foreach((array) $request->record as $key => $row){
        
            //creating record for saving into michango table
            if(($row['mwanajumuiya'] && $row['kiasi']) != ""){
                
                $data_save = [
                    'jumuiya' => $request->jumuiya,
                    'tarehe' => $request->tarehe,
                    'aina_ya_mchango' => $request->aina_ya_mchango,
                    'maelezo' => "mchango wa $request->aina_ya_mchango",
                    'mwanafamilia' => $row['mwanajumuiya'],
                    'kundi' => 'michango',
                    'kiasi' => $row['kiasi'],
                    'imewekwa' => auth()->user()->email,
                ];

                MichangoTaslimu::create($data_save);
                $sum = $sum + $row['kiasi'];
                $idadi = $idadi + 1;
            }
        }

        $message = "Ongezo";
        $data = "Ongezo la michango taslimu za wanajumuiya $idadi kiasi $sum";
        $this->setActivity($message,$data);

        Alert::toast("Michango imewekwa kikamilifu","success")->autoClose(4200)->timerProgressBar(4200);

        return redirect('michango_taslimu_kijumuiya');

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

    //function to handle michango taslimu risiti
    public function michango_taslimu_risiti($id){
        $data = MichangoTaslimu::whereId($id)->get();

        if($data->isEmpty()){
            Alert::info("Taarifa!","Hakuna mchango kwa uchaguzi uliofanywa..");
            return back();
        }

        else{
            foreach($data as $dat){
                $mwanafamilia_id = $dat->mwanafamilia;
                $aina_ya_mchango = $dat->aina_ya_mchango;
                $tarehe = Carbon::parse($dat->tarehe)->format('d-F-Y');
                $kiasi = number_format($dat->kiasi,2);
            }

            //combining with details of mwanajumuiya
            $data_mwanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
            ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
            ->where('mwanafamilias.id',$mwanafamilia_id)
            ->get(['mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','familias.jina_la_jumuiya','jumuiyas.jina_la_kanda']);

            if($data_mwanajumuiya->isEmpty()){
                Alert::info("Taarifa!","Hakuna taarifa za mwanajumuiya kwa ID $mwanafamilia_id");
                return back();
            }

            else{
                foreach($data_mwanajumuiya as $row){
                    $mwanajumuiya = $row->jina_kamili;
                    $namba_utambulisho = $row->namba_utambulisho;
                    $jumuiya = $row->jina_la_jumuiya;
                    $kanda = $row->jina_la_kanda;
                }
            }

            //printing now
            $customPaper = array(0,0,226,360);
            $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.michango_taslimu_risiti',compact(['mwanajumuiya','kiasi','tarehe','aina_ya_mchango','namba_utambulisho','jumuiya','kanda']));
            $output = $pdf->output();

            return new Response($output, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' =>  'inline; filename="receipt.pdf"',
            ]);

            // return $pdf->stream("receipt.pdf");
        }
        
    }

    //function to handle michango benki risiti
    public function michango_benki_risiti($id){
        $data = MichangoBenki::whereId($id)->get();

        if($data->isEmpty()){
            Alert::info("Taarifa!","Hakuna mchango kwa uchaguzi uliofanywa..");
            return back();
        }

        else{
            foreach($data as $dat){
                $mwanafamilia_id = $dat->mwanafamilia;
                $aina_ya_mchango = $dat->aina_ya_mchango;
                $tarehe = Carbon::parse($dat->tarehe)->format('d-F-Y');
                $kiasi = number_format($dat->kiasi,2);
                $nukushi = $dat->nambari_ya_nukushi;
            }

            //combining with details of mwanajumuiya
            $data_mwanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
            ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
            ->where('mwanafamilias.id',$mwanafamilia_id)
            ->get(['mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','familias.jina_la_jumuiya','jumuiyas.jina_la_kanda']);

            if($data_mwanajumuiya->isEmpty()){
                Alert::info("Taarifa!","Hakuna taarifa za mwanajumuiya kwa ID $mwanafamilia_id");
                return back();
            }

            else{
                foreach($data_mwanajumuiya as $row){
                    $mwanajumuiya = $row->jina_kamili;
                    $namba_utambulisho = $row->namba_utambulisho;
                    $jumuiya = $row->jina_la_jumuiya;
                    $kanda = $row->jina_la_kanda;
                }
            }

            //printing now
            $customPaper = array(0,0,226,360);
            $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.michango_benki_risiti',compact(['mwanajumuiya','kiasi','tarehe','aina_ya_mchango','nukushi','namba_utambulisho','jumuiya','kanda']));
            $output = $pdf->output();

            return new Response($output, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' =>  'inline; filename="receipt.pdf"',
            ]);

            // return $pdf->stream("receipt.pdf");
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new MichangoTaslimu; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }


    public function waliotoa_wasiotoa(Request $request)

    {

        $title='Matoleo ya Zaka';
        $mwanajumuiyas=[];
        $michangos=AinaZaMichango::all();

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

        if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $jumuiyas=Jumuiya::all();

            if($request->mchango=='zaka'){
            
                $mwanajumuiyas=DB::table('mwanafamilias')
                ->select('mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili as jina_kamili','jumuiyas.jina_la_jumuiya as jina_la_jumuiya', DB::raw('SUM(zakas.kiasi) as kiasi'))
                ->leftjoin('familias','familias.id','mwanafamilias.familia')
                ->leftjoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
                ->leftJoin('zakas', 'mwanafamilias.id', '=', 'zakas.mwanajumuiya')
                ->groupBy('mwanafamilias.id');
            
                $mchango_title='Zaka';
                if($request->jumuiya=='all'){
                    $title='Mchango wa zaka kwa jumuiya Zote';
                    $jumuiya_title='All';
                }else{
                    //dd($request->jumuiya);
                    $jumuiya_title=Jumuiya::find($request->jumuiya)->jina_la_jumuiya;
                   $manajumuiyas=$mwanajumuiyas->where('jumuiyas.id',$request->jumuiya);
                }
                
                if($request->wachangiaji=='all'){
                    $wachangiaji_title='All';
                }else{
                    $wachangiaji_title=$request->wachangiaji;
                    if($request->wachangiaji=='waliotoa'){
                        $mwanajumuiyas=$mwanajumuiyas->where('kiasi','>',0);
                    }else{
                        $mwanajumuiyas=$mwanajumuiyas->whereNull('kiasi');
                    }
                }
    
                if($request->startDate & $request->endDate){
                
                    $startDate=Carbon\Carbon::parse($request->startDate)->format('Y-m-d');
                    $endDate=Carbon\Carbon::parse($request->endDate)->format('Y-m-d');
            
                    $mwanajumuiyas=$mwanajumuiyas->whereBetween('zakas.tarehe',[$startDate,$endDate]);
                }
    
                $mwanajumuiyas= $mwanajumuiyas->get();
            }else{
                
                $mchango=AinaZaMichango::find($request->mchango);
                $mchango_title=$mchango->aina_ya_mchango;
                $title="Mchango wa $mchango->aina_ya_mchango";
                $jina_mchango=$mchango->aina_ya_mchango;
                $mwanajumuiyas=DB::table('mwanafamilias')
                ->select('mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili as jina_kamili','jumuiyas.jina_la_jumuiya as jina_la_jumuiya', 
                DB::raw('SUM(michango_taslimus.kiasi) as taslimu_kiasi'),DB::raw('SUM(michango_benkis.kiasi) as benki_kiasi'))
                ->leftjoin('familias','familias.id','mwanafamilias.familia')
                ->leftjoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
                ->leftjoin('michango_taslimus',function($leftjoin) use($jina_mchango){
                  $leftjoin->on('michango_taslimus.mwanafamilia','mwanafamilias.id');
                  $leftjoin->on(DB::raw('michango_taslimus.aina_ya_mchango'), DB::raw('='),DB::raw("'".$jina_mchango."'"));
    
                })
                ->leftjoin('michango_benkis',function($leftjoin) use($jina_mchango){
                    $leftjoin->on('michango_benkis.mwanafamilia','mwanafamilias.id');
                    $leftjoin->on(DB::raw('michango_benkis.aina_ya_mchango'), DB::raw('='),DB::raw("'".$jina_mchango."'"));
      
                  })
             //   ->leftjoin('michango_benkis','michango_benkis.mwanafamilia','mwanafamilias.id')
                ->groupBy('mwanafamilias.id');
               //->where('michango_taslimus.aina_ya_mchango','=',$mchango->aina_ya_mchango);
                //->orwhere('michango_benkis.aina_ya_mchango','=',$mchango->aina_ya_mchango);
                //$mwanajumuiyas=$mwanajumuiyas->get();
    
               // dd($mwanajumuiyas);
                if($request->jumuiya=='all'){
                   // $title='Mchango wa zaka kwa jumuiya Zote';
                    $jumuiya_title='All';
                }else{
                    //dd($request->jumuiya);
                    $jumuiya_title=Jumuiya::find($request->jumuiya)->jina_la_jumuiya;
                   $manajumuiyas=$mwanajumuiyas->where('jumuiyas.id',$request->jumuiya);
                }
                
                if($request->wachangiaji=='all'){
                    $wachangiaji_title='All';
                }else{
                    $wachangiaji_title=$request->wachangiaji;
                    if($request->wachangiaji=='waliotoa'){
                        $mwanajumuiyas=$mwanajumuiyas->orwhere('michango_taslimus.kiasi','>',0);
                       // ->orwhere('michango_benkis.kiasi','>',0);
                    }else{
                        $mwanajumuiyas=$mwanajumuiyas->whereNull('michango_taslimus.kiasi')
                        ->WhereNull('michango_benkis.kiasi');
                    }
                }
    
                if($request->startDate & $request->endDate){
               
                    $startDate=Carbon\Carbon::parse($request->startDate)->format('Y-m-d');
                    $endDate=Carbon\Carbon::parse($request->endDate)->format('Y-m-d');
            
                    $mwanajumuiyas=$mwanajumuiyas->whereBetween('michango_taslimus.tarehe',[$startDate,$endDate]);
                }
              
                $mwanajumuiyas= $mwanajumuiyas->get();
            }

    }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda' || $user_ngazi=='Jumuiya'){
                 if($user_ngazi=='Kanda'){
                   
                    $jumuiya = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                    $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
                    $wanajumuiya_d = Mwanafamilia::whereIn('familia',$familia->pluck('id'));
                    $jumuiyas=$jumuiya->get();
                 }
               

                if($user_ngazi=='Jumuiya'){
                    $jumuiya = $jumuiya; 
                    $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
                    $wanajumuiya_d = Mwanafamilia::whereIn('familia',$familia->pluck('id'));
                    $jumuiyas=$jumuiya->get();
                }



                if($request->mchango=='zaka'){
            
                    $mwanajumuiyas=DB::table('mwanafamilias')
                    ->whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))
                    ->select('mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili as jina_kamili','jumuiyas.jina_la_jumuiya as jina_la_jumuiya', DB::raw('SUM(zakas.kiasi) as kiasi'))
                    ->leftjoin('familias','familias.id','mwanafamilias.familia')
                    ->leftjoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
                    ->leftJoin('zakas', 'mwanafamilias.id', '=', 'zakas.mwanajumuiya')
                    ->groupBy('mwanafamilias.id');
                
                    $mchango_title='Zaka';
                    if($request->jumuiya=='all'){
                        $title='Mchango wa zaka kwa jumuiya Zote';
                        $jumuiya_title='All';
                    }else{
                        //dd($request->jumuiya);
                       // dd($mwanajumuiyas);
                        $jumuiya_title=Jumuiya::find($request->jumuiya)->jina_la_jumuiya;
                       $manajumuiyas=$mwanajumuiyas->where('jumuiyas.id',$request->jumuiya);
                    }
                    
                    if($request->wachangiaji=='all'){
                        $wachangiaji_title='All';
                    }else{
                        $wachangiaji_title=$request->wachangiaji;
                        if($request->wachangiaji=='waliotoa'){
                            $mwanajumuiyas=$mwanajumuiyas->where('kiasi','>',0);
                        }else{
                            $mwanajumuiyas=$mwanajumuiyas->whereNull('kiasi');
                        }
                    }
        
                    if($request->startDate & $request->endDate){
                    
                        $startDate=Carbon\Carbon::parse($request->startDate)->format('Y-m-d');
                        $endDate=Carbon\Carbon::parse($request->endDate)->format('Y-m-d');
                
                        $mwanajumuiyas=$mwanajumuiyas->whereBetween('zakas.tarehe',[$startDate,$endDate]);
                    }
        
                      
                    $mwanajumuiyas= $mwanajumuiyas->get();
                }else{
                    
                    $mchango=AinaZaMichango::find($request->mchango);
                    $mchango_title=$mchango->aina_ya_mchango;
                    $title="Mchango wa $mchango->aina_ya_mchango";
                    $jina_mchango=$mchango->aina_ya_mchango;
                    $mwanajumuiyas=DB::table('mwanafamilias')
                    ->whereIn('mwanafamilias.id',$wanajumuiya_d->pluck('id'))
                    ->select('mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili as jina_kamili','jumuiyas.jina_la_jumuiya as jina_la_jumuiya', 
                    DB::raw('SUM(michango_taslimus.kiasi) as taslimu_kiasi'),DB::raw('SUM(michango_benkis.kiasi) as benki_kiasi'))
                    ->leftjoin('familias','familias.id','mwanafamilias.familia')
                    ->leftjoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
                    ->leftjoin('michango_taslimus',function($leftjoin) use($jina_mchango){
                      $leftjoin->on('michango_taslimus.mwanafamilia','mwanafamilias.id');
                      $leftjoin->on(DB::raw('michango_taslimus.aina_ya_mchango'), DB::raw('='),DB::raw("'".$jina_mchango."'"));
        
                    })
                    ->leftjoin('michango_benkis',function($leftjoin) use($jina_mchango){
                        $leftjoin->on('michango_benkis.mwanafamilia','mwanafamilias.id');
                        $leftjoin->on(DB::raw('michango_benkis.aina_ya_mchango'), DB::raw('='),DB::raw("'".$jina_mchango."'"));
          
                      })
                 //   ->leftjoin('michango_benkis','michango_benkis.mwanafamilia','mwanafamilias.id')
                    ->groupBy('mwanafamilias.id');
                   //->where('michango_taslimus.aina_ya_mchango','=',$mchango->aina_ya_mchango);
                    //->orwhere('michango_benkis.aina_ya_mchango','=',$mchango->aina_ya_mchango);
                    //$mwanajumuiyas=$mwanajumuiyas->get();
        
                   // dd($mwanajumuiyas);
                    if($request->jumuiya=='all'){
                       // $title='Mchango wa zaka kwa jumuiya Zote';
                        $jumuiya_title='All';
                    }else{
                        //dd($request->jumuiya);
                        $jumuiya_title=Jumuiya::find($request->jumuiya)->jina_la_jumuiya;
                       $manajumuiyas=$mwanajumuiyas->where('jumuiyas.id',$request->jumuiya);
                    }
                    
                    if($request->wachangiaji=='all'){
                        $wachangiaji_title='All';
                    }else{
                        $wachangiaji_title=$request->wachangiaji;
                        if($request->wachangiaji=='waliotoa'){
                            $mwanajumuiyas=$mwanajumuiyas->orwhere('michango_taslimus.kiasi','>',0);
                           // ->orwhere('michango_benkis.kiasi','>',0);
                        }else{
                            $mwanajumuiyas=$mwanajumuiyas->whereNull('michango_taslimus.kiasi')
                            ->WhereNull('michango_benkis.kiasi');
                        }
                    }
        
                    if($request->startDate & $request->endDate){
                   
                        $startDate=Carbon\Carbon::parse($request->startDate)->format('Y-m-d');
                        $endDate=Carbon\Carbon::parse($request->endDate)->format('Y-m-d');
                
                        $mwanajumuiyas=$mwanajumuiyas->whereBetween('michango_taslimus.tarehe',[$startDate,$endDate]);
                    }
                  
                    $mwanajumuiyas= $mwanajumuiyas->get();
                }
         
            
            }
    
        }
    
 
        
         return view('backend.michango.waliotoa_wasiotoa',compact('wachangiaji_title','title','michangos','jumuiyas','mwanajumuiyas','mchango_title','jumuiya_title'));
    }
}
