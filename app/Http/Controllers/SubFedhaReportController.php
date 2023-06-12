<?php

namespace App\Http\Controllers;

use App\Jumuiya;
use App\Kanda;
use App\MatumiziBenki;
use App\MatumiziTaslimu;
use App\MichangoBenki;
use App\MichangoTaslimu;
use App\SadakaJumuiya;
use App\SadakaKuu;
use App\SadakaZaMisa;
use App\SahihishaKanda;
use App\Zaka;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class SubFedhaReportController extends Controller
{
    //


    public function mapato_ya_kawaida_zaka_wanajumuiya(Request $request)

    { 
       $kuanzia=$request->kuanzia;
       $ukomo=$request->ukomo;
       $jumuiya_jina = $request->jumuiya;
       $aina_ya_mapato = $request->aina_ya_mapato;
       
       
       $jumuiya=Jumuiya::where('jina_la_jumuiya',trim(str_replace("\\t",'',$jumuiya_jina)))->first();
    
       $begin_with = search_by_date($kuanzia,$ukomo)['begin_with'];
       $start_month = search_by_date($kuanzia,$ukomo)['start_month'];
       $current_month = search_by_date($kuanzia,$ukomo)['current_month'];
       $current_year = search_by_date($kuanzia,$ukomo)['current_year'];
       $previous_year = search_by_date($kuanzia,$ukomo)['previous_year'];
       $end_date=search_by_date($kuanzia,$ukomo)['end_date'];
          if($aina_ya_mapato=='mapato_ya_zaka_wanajumuiya'){
                $title = "Mchango wa zaka Jumuiya ya  $jumuiya_jina, Kuanzia $start_month ($previous_year)- $current_month ($current_year)";
              $name = $aina_ya_mapato;
       $data = Zaka::leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
       ->where('zakas.jumuiya',$jumuiya->jina_la_jumuiya)
       ->select(DB::raw("SUM(zakas.kiasi) as kiasi"),'mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','mwanafamilias.id as mwanajumuiya_id')
           ->whereBetween('zakas.tarehe', [$begin_with, $end_date])
           ->groupBy('mwanafamilias.id')
           ->orderBy('kiasi','desc')
           ->get();


          }else{
            $title = "Mchango wa $aina_ya_mapato Jumuiya ya  $jumuiya_jina, Kuanzia $start_month ($previous_year)- $current_month ($current_year)";
            $name = $aina_ya_mapato;
           
            $michango_taslimu = MichangoTaslimu::select(DB::raw("SUM(kiasi) as kiasi"),DB::raw("tarehe"), DB::raw("jumuiyas.jina_la_jumuiya as jumuiya"),'aina_ya_mchango','mwanafamilias.jina_kamili as jina_kamili')
            ->join('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
            ->join('familias','familias.id','=','mwanafamilias.familia')
            ->join('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
            ->groupBy('mwanafamilias.id')
            ->where('michango_taslimus.aina_ya_mchango',$name)
            ->whereBetween('michango_taslimus.tarehe', [$begin_with, $end_date])
            ->get();
           
  $michango_benki =  MichangoBenki::select(DB::raw("SUM(kiasi) as kiasi"),DB::raw("tarehe"), DB::raw("jumuiyas.jina_la_jumuiya as jumuiya"),'aina_ya_mchango','mwanafamilias.jina_kamili as jina_kamili')
->join('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
->join('familias','familias.id','=','mwanafamilias.familia')
->join('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
->groupBy('mwanafamilias.id')
->where('michango_benkis.aina_ya_mchango',$name)
->whereBetween('michango_benkis.tarehe', [$begin_with, $end_date])
->get();

     $data = $michango_taslimu; 

 }


     if($request->print=='pdf'){
     
        $pdf = PDF::loadView('backend.main_reports.fedha.wanajumuiya_print', compact(['data','title','name','kuanzia','ukomo']));
        return $pdf->stream($title . ".pdf");

     }else{
          return view('backend.main_reports.fedha.wanajumuiya',compact('data','title','name','kuanzia','ukomo'));
     }
       
    }

    public function mapato_ya_kawaida(Request $request, $name,$kuanzia,$ukomo)

    {


       
        $begin_with = search_by_date($kuanzia,$ukomo)['begin_with'];
        $start_month = search_by_date($kuanzia,$ukomo)['start_month'];
        $current_month = search_by_date($kuanzia,$ukomo)['current_month'];
        $current_year = search_by_date($kuanzia,$ukomo)['current_year'];
        $previous_year = search_by_date($kuanzia,$ukomo)['previous_year'];
        $end_date=search_by_date($kuanzia,$ukomo)['end_date'];
        $kandas=Kanda::all();
        

        $sadaka_za_misa = SadakaZaMisa::orderBy('ilifanyika', 'desc')
        ->whereBetween('ilifanyika', [$begin_with, $end_date])->get();
       
        $mapato_ya_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"), DB::raw('jumuiya'))
          ->whereBetween('tarehe', [$begin_with, $end_date])
       // ->whereYear('tarehe', $current_year)
            ->orderBy('tarehe', 'asc');
            

             // dd($request->group);
            if($request->group=='jumuiya_list')
            {
                $mapato_ya_zaka=$mapato_ya_zaka->groupBy(DB::raw('jumuiya'));
            }else{
                $mapato_ya_zaka=$mapato_ya_zaka->groupBy(DB::raw("MONTH(tarehe)"));
            }

      if($request->kanda=='all' || !$request->kanda){
        $title_kanda=SahihishaKanda::first()->name;
        $jumuiyas=Jumuiya::all();
      }elseif($request->kanda){

        $kanda=Kanda::find($request->kanda);
         $title_kanda=$kanda->jina_la_kanda;
       //return (Jumuiya::where('jina_la_kanda','=',$kanda->jina_la_kanda)->pluck('jina_la_jumuiya')->toArray());
          $mapato_ya_zaka=$mapato_ya_zaka->whereIn('jumuiya',Jumuiya::where('jina_la_kanda','=',$kanda->jina_la_kanda)->pluck('jina_la_jumuiya')->toArray());
        $jumuiyas=Jumuiya::where('jina_la_kanda','=',$kanda->jina_la_kanda)->get();
       
      }

      if($request->jumuiya=='all' || !$request->jumuiya){
         $title_jumuiya='Jumuiya zote';
      }else{
        $jumuiya=Jumuiya::find($request->jumuiya);
         $title_jumuiya=$jumuiya->jina_la_jumuiya;
      
        $mapato_ya_zaka=$mapato_ya_zaka->where('jumuiya',$title_jumuiya);
      }
      

      $mapato_ya_zaka=$mapato_ya_zaka->get();

 
    
      
        if ($name == 'sadaka_za_misa') {$title = "Sadaka za misa $start_month ($previous_year) - $current_month ($current_year)";
            $name = 'sadaka_za_misa';
        }

        $sadaka_za_jumuiya = SadakaJumuiya::whereBetween('tarehe', [$begin_with, $end_date])->select(DB::raw("SUM(kiasi) as kiasi"), 'jina_la_jumuiya', 'tarehe')
            ->orderBy('kiasi', 'DESC')
            ->whereBetween('tarehe', [$begin_with, $end_date])
          //  ->whereYear('tarehe', $current_year)
            ->groupBy('jina_la_jumuiya')
            ->get();
        if ($name == 'sadaka_za_jumuiya') {
            $title = "Sadaka za jumuiya $start_month ($previous_year) - $current_month ($current_year)";
        }
        ///   dd($sadaka_za_jumuiya);

        
             $zaka_words_kanda=$request->kanda=='all' ||!$request->kanda ?$title_kanda:SahihishaKanda::first()->name.' cha '.$title_kanda;
             $zaka_words_jumuiya=$request->jumuiya=='all' ||!$request->jumuiya ?$title_jumuiya:'Jumuiya ya ' .$title_jumuiya;
            // dd($title_kanda);
        if ($name == 'mapato_ya_zaka') {
            $title = "Mapato ya zaka $zaka_words_kanda  $zaka_words_jumuiya $start_month ($previous_year)- $current_month ($current_year)";
        }
        //  dd($mapato_ya_zaka);
        if ($name == 'jumla_mapato_ya_kawaida') {
            $title = "Jumla ya mapato ya kawaida $start_month ($previous_year) - $current_month ($current_year)";
        }

        return view('backend.main_reports.fedha.mapato_ya_kawaida', compact('title', 'sadaka_za_misa', 'sadaka_za_jumuiya', 'mapato_ya_zaka', 'name','kuanzia','ukomo','jumuiyas','kandas','title_kanda','title_jumuiya'));
    }

    public function mapato_ya_kawaida_print(Request $request, $type, $name,$kuanzia,$ukomo)
    {


          $kanda_final=null;
          $jumuiya_final=null;
        $url=str_replace(url('/'), '', url()->previous());
        $explode_url=explode('/',$url);
        $exploded_last=$explode_url[4];

        $filter=$explode_url=explode('?',$exploded_last);
        $filter_length=count($filter);
        if($filter_length>=2){
            $last_part=explode('&',$filter[1]);
             if(count($last_part)>=2){

                $string_1=explode('=',$last_part[0]);
                if(strpos('kanda',$string_1[0]) !== FALSE){
                    $kanda_final=$string_1[1];
                }else{
                    $jumuiya_final=$string_1[1];
                }

                $string_2=explode('=',$last_part[1]);
                if(strpos('kanda',$string_2[0]) !== FALSE){
                    $kanda_final=$string_2[1];
                }else{
                    $jumuiya_final=$string_2[1];
                }
                //   $kanda=$last_part[1];
                //  $explod_kanda=explode('=',$kanda);
                //  $kanda_final=$explod_kanda[1];
                //  $jumuiya=$last_part[0];
                //  $explod_jumuiya=explode('=',$jumuiya);
                //  dd($explod_jumuiya);
                //  $jumuiya_final=$explod_jumuiya[1];

             }elseif(count($last_part)==1){
                    $string=explode('=',$last_part[0]);
                 if(strpos('kanda',$string[0]) !== FALSE){
                     $kanda_final=$string[1];
                 }else{
                     $jumuiya_final=$string[1];
                 }
             }else{
                
             }
        }

       // dd($filter);

        $begin_with = search_by_date($kuanzia,$ukomo)['begin_with'];
        $start_month = search_by_date($kuanzia,$ukomo)['start_month'];
        $current_month = search_by_date($kuanzia,$ukomo)['current_month'];
        $current_year = search_by_date($kuanzia,$ukomo)['current_year'];
        $previous_year = search_by_date($kuanzia,$ukomo)['previous_year'];
        $end_date=search_by_date($kuanzia,$ukomo)['end_date'];


      $sadaka_za_misa = SadakaZaMisa::orderBy('ilifanyika', 'desc')
        ->whereBetween('ilifanyika', [$begin_with, $end_date])->get();
        $mapato_ya_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
          ->whereBetween('tarehe', [$begin_with, $end_date])
       // ->whereYear('tarehe', $current_year)
            ->orderBy('tarehe', 'asc')
            ->groupBy(DB::raw("MONTH(tarehe)"));

      if($kanda_final=='all' || !$kanda_final){
        
        $title_kanda=SahihishaKanda::first()->name;
        $jumuiyas=Jumuiya::all();
      }elseif($kanda_final){

        
        $kanda=Kanda::find($kanda_final);
        
         $title_kanda=$kanda->jina_la_kanda;
       //return (Jumuiya::where('jina_la_kanda','=',$kanda->jina_la_kanda)->pluck('jina_la_jumuiya')->toArray());
          $mapato_ya_zaka=$mapato_ya_zaka->whereIn('jumuiya',Jumuiya::where('jina_la_kanda','=',$kanda->jina_la_kanda)->pluck('jina_la_jumuiya')->toArray());
        $jumuiyas=Jumuiya::where('jina_la_kanda','=',$kanda->jina_la_kanda)->get();
       
      }

      
      if($jumuiya_final=='all' || !$jumuiya_final){
         $title_jumuiya='Jumuiya zote';
      }elseif($jumuiya_final){
        
        $jumuiya=Jumuiya::find($jumuiya_final);
         $title_jumuiya=$jumuiya->jina_la_jumuiya;
          // dd($jumuiya);
        $mapato_ya_zaka=$mapato_ya_zaka->where('jumuiya','=',$title_jumuiya);
       // dd($mapato_ya_zaka=$mapato_ya_zaka->get());
       
      }
      

      $mapato_ya_zaka=$mapato_ya_zaka->get();
      

      
    
      
        if ($name == 'sadaka_za_misa') {$title = "Sadaka za misa $start_month ($previous_year) - $current_month ($current_year)";
            $name = 'sadaka_za_misa';
        }

        $sadaka_za_jumuiya = SadakaJumuiya::whereBetween('tarehe', [$begin_with, $end_date])->select(DB::raw("SUM(kiasi) as kiasi"), 'jina_la_jumuiya', 'tarehe')
            ->orderBy('kiasi', 'DESC')
            ->whereBetween('tarehe', [$begin_with, $end_date])
          //  ->whereYear('tarehe', $current_year)
            ->groupBy('jina_la_jumuiya')
            ->get();
        if ($name == 'sadaka_za_jumuiya') {
            $title = "Sadaka za jumuiya $start_month ($previous_year) - $current_month ($current_year)";
        }
        ///   dd($sadaka_za_jumuiya);

        
             $zaka_words_kanda=$kanda_final=='all' || !$kanda_final?$title_kanda:SahihishaKanda::first()->name.' cha '.$title_kanda;
             $zaka_words_jumuiya=$jumuiya_final=='all' || !$jumuiya_final ?$title_jumuiya:'Jumuiya ya ' .$title_jumuiya;
            // dd($title_kanda);
        if ($name == 'mapato_ya_zaka') {
            $title = "Mapato ya zaka $zaka_words_kanda  $zaka_words_jumuiya $start_month ($previous_year)- $current_month ($current_year)";
        }
        //  dd($mapato_ya_zaka);
        if ($name == 'jumla_mapato_ya_kawaida') {
            $title = "Jumla ya mapato ya kawaida $start_month ($previous_year) - $current_month ($current_year)";
        }

        if ($type == 'pdf') {
            $pdf = PDF::loadView('backend.main_reports.fedha.mapato_ya_kawaida_print', compact(['title', 'sadaka_za_misa', 'name', 'sadaka_za_jumuiya', 'mapato_ya_zaka']));
            return $pdf->stream($title . ".pdf");
        }
        if ($type == 'excel') {

        }

    }

    public function mapato_ya_maendeleo(Request $request,$aina_ya_mchango,$print_type,$kuanzia,$ukomo)
    {

     
        $begin_with = search_by_date($kuanzia,$ukomo)['begin_with'];
        $start_month = search_by_date($kuanzia,$ukomo)['start_month'];
        $current_month = search_by_date($kuanzia,$ukomo)['current_month'];
        $current_year = search_by_date($kuanzia,$ukomo)['current_year'];
        $previous_year = search_by_date($kuanzia,$ukomo)['previous_year'];
        $end_date=search_by_date($kuanzia,$ukomo)['end_date'];
        $aina_ya_mchango_c = underscore_to_space(strtoupper($aina_ya_mchango));
        $sadaka_za_misa = [];
        $sadaka_za_jumuiya = [];
        $mapato_ya_zaka = [];
         
        
        if($request->group=='jumuiya_list')
        { 
        $michango_taslimu = MichangoTaslimu::select(DB::raw("SUM(kiasi) as kiasi"),DB::raw("tarehe"), DB::raw("jumuiyas.jina_la_jumuiya as jumuiya"),'aina_ya_mchango')
                            ->join('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
                            ->join('familias','familias.id','=','mwanafamilias.familia')
                            ->join('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                            ->groupBy('jumuiyas.id')
                            ->whereBetween('michango_taslimus.tarehe', [$begin_with, $end_date]);
                           
        $michango_benki =  MichangoBenki::select(DB::raw("SUM(kiasi) as kiasi"),DB::raw("tarehe"), DB::raw("jumuiyas.jina_la_jumuiya as jumuiya"),'aina_ya_mchango')
        ->join('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->join('familias','familias.id','=','mwanafamilias.familia')
        ->join('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
        ->groupBy('jumuiyas.id')
        ->whereBetween('michango_benkis.tarehe', [$begin_with, $end_date]);

        }else{
        $michango_taslimu = MichangoTaslimu::whereBetween('tarehe', [$begin_with, $end_date]);
        $michango_benki = MichangoBenki::whereBetween('tarehe', [$begin_with, $end_date]);
        }
        if ($aina_ya_mchango == 'jumla_mapato_maendeleo') {
            $michango_taslimu = $michango_taslimu->get();
            $michango_benki = $michango_benki->get();
            $aina_ya_mchango_c = 'Jumla ya mapato maendeleo';
            $aina_ya_michango_jumla = $michango_taslimu->merge($michango_benki)
                ->mapToGroups(function ($item, $key) {
                    return [$item['aina_ya_mchango'] => $item];
                });

        } elseif ($aina_ya_mchango == 'jumla_ya_mapato_yote') {
            $michango_taslimu = $michango_taslimu->get();
            $michango_benki = $michango_benki->get();
            $sadaka_za_misa = SadakaZaMisa::orderBy('ilifanyika', 'desc')->whereBetween('ilifanyika', [$begin_with, $end_date])->get();
            $sadaka_za_jumuiya = SadakaJumuiya::whereBetween('tarehe', [$begin_with, $end_date])->select(DB::raw("SUM(kiasi) as kiasi"), 'jina_la_jumuiya', 'tarehe')
                ->orderBy('kiasi', 'DESC')
                ->whereYear('tarehe', date('Y'))
                ->groupBy('jina_la_jumuiya')
                ->get();

            $mapato_ya_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereBetween('tarehe', [$begin_with, $end_date])
                ->orderBy('tarehe', 'asc')
                ->groupBy(DB::raw("MONTH(tarehe)"))
                ->get();

            $aina_ya_michango_jumla = $michango_taslimu->merge($michango_benki)
                ->mapToGroups(function ($item, $key) {
                    return [$item['aina_ya_mchango'] => $item];
                });

        } else {
         
            $michango_taslimu = $michango_taslimu->where('aina_ya_mchango', '=', $aina_ya_mchango_c)
                ->get();

              //  dd( $michango_taslimu);
            $michango_benki = $michango_benki->where('aina_ya_mchango', '=', $aina_ya_mchango_c)
                ->get();
           $aina_ya_michango_jumla = $michango_taslimu->merge($michango_benki); 
      
        }      

        $title = "$aina_ya_mchango_c $start_month ($previous_year)- $current_month ($current_year)";
        $name = $aina_ya_mchango;
          if($print_type=='null'){
        return view('backend.main_reports.fedha.mapato_ya_maendeleo', compact('aina_ya_michango_jumla', 'title', 'name', 'sadaka_za_misa', 'sadaka_za_jumuiya', 'mapato_ya_zaka','kuanzia','ukomo'));
    }elseif($print_type=='pdf')
    {
        $pdf = PDF::loadView('backend.main_reports.fedha.mapato_ya_maendeleo_pdf', compact(['aina_ya_michango_jumla', 'title', 'name', 'sadaka_za_misa', 'sadaka_za_jumuiya', 'mapato_ya_zaka','kuanzia','ukomo']));
            return $pdf->stream($title . ".pdf"); 
    }else{

    }
}

    public function matumizi($kundi, $aina_ya_matumizi, $print_type,$kuanzia,$ukomo)
    {

        $begin_with = search_by_date($kuanzia,$ukomo)['begin_with'];
        $start_month = search_by_date($kuanzia,$ukomo)['start_month'];
        $current_month = search_by_date($kuanzia,$ukomo)['current_month'];
        $previous_year = search_by_date($kuanzia,$ukomo)['previous_year'];
        $current_year = search_by_date($kuanzia,$ukomo)['current_year'];
        $end_date=search_by_date($kuanzia,$ukomo)['end_date'];

        $matumizi_taslimu = MatumiziTaslimu::where('kundi', '=', $kundi)
            ->whereBetween('tarehe', [$begin_with, $end_date]);

        $matumizi_benki = MatumiziBenki::where('kundi', '=', $kundi)
            ->whereBetween('tarehe', [$begin_with, $end_date]);

        if ($aina_ya_matumizi == 'jumla') {
            $matumizi_taslimu = $matumizi_taslimu->get();
            $matumizi_benki = $matumizi_benki->get();
            $matumizi = $matumizi_taslimu->merge($matumizi_benki);

            $title = "Jumla matumizi ya $kundi $start_month ($previous_year) - $current_month ($current_year)";
        }elseif($aina_ya_matumizi=='matumizi_jumla'){
           
            $matumizi_taslimu = MatumiziTaslimu::whereBetween('tarehe', [$begin_with, $end_date])->get();
            $matumizi_benki = MatumiziBenki::whereBetween('tarehe', [$begin_with, $end_date])->get();
            $matumizi = $matumizi_taslimu->merge($matumizi_benki)
            ->mapToGroups(function ($item, $key) {
                return [$item['kundi'] => $item];
            });
            $title = "Jumla ya matumizi $start_month ($previous_year) - $current_month ($current_year)";

            } 
       
        else {
            $matumizi_taslimu = $matumizi_taslimu
                ->where('aina_ya_matumizi', '=', $aina_ya_matumizi)->get();

            $matumizi_benki = $matumizi_benki
                ->where('aina_ya_matumizi', '=', $aina_ya_matumizi)->get();

            $matumizi = $matumizi_taslimu->merge($matumizi_benki);
            $title = "$aina_ya_matumizi $start_month ($previous_year) - $current_month ($current_year)";
        }

        // dd($matumizi);
         if($print_type=='null'){
        return view('backend.main_reports.fedha.sub_matumizi', compact('title','kundi', 'matumizi','aina_ya_matumizi','kuanzia','ukomo'));
         }elseif($print_type=='pdf'){
         $pdf = PDF::loadView('backend.main_reports.fedha.sub_matumizi_pdf', compact(['title','kundi', 'matumizi','aina_ya_matumizi','kuanzia','ukomo']));
         return $pdf->stream($title . ".pdf"); }
         
         else{

         }
    }

    public function salio($print_type,$kuanzia,$ukomo)

    {
        $begin_with = search_by_date($kuanzia,$ukomo)['begin_with'];
        $start_month = search_by_date($kuanzia,$ukomo)['start_month'];
        $current_month = search_by_date($kuanzia,$ukomo)['current_month'];
        $current_year = search_by_date($kuanzia,$ukomo)['current_year'];
        $previous_year = search_by_date($kuanzia,$ukomo)['previous_year'];
        $end_date=search_by_date($kuanzia,$ukomo)['end_date'];

        $michango_taslimu = MichangoTaslimu::whereBetween('tarehe', [$begin_with, $end_date])->get();
        $michango_benki = MichangoBenki::whereBetween('tarehe', [$begin_with, $end_date])->get();
        $sadaka_za_misa = SadakaZaMisa::orderBy('ilifanyika', 'desc')->whereBetween('ilifanyika', [$begin_with, $end_date])->get();
        $sadaka_za_jumuiya = SadakaJumuiya::whereBetween('tarehe', [$begin_with, $end_date])->select(DB::raw("SUM(kiasi) as kiasi"), 'jina_la_jumuiya', 'tarehe')
            ->orderBy('kiasi', 'DESC')
           -> whereBetween('tarehe', [$begin_with, $end_date])
            ->groupBy('jina_la_jumuiya')
            ->get();

        $mapato_ya_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
           ->whereBetween('tarehe', [$begin_with, $end_date])
            ->orderBy('tarehe', 'asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();

        $aina_ya_michango_jumla = $michango_taslimu->merge($michango_benki)
            ->mapToGroups(function ($item, $key) {
                return [$item['aina_ya_mchango'] => $item];
            });

            $matumizi_taslimu = MatumiziTaslimu::whereBetween('tarehe', [$begin_with, $end_date])->get();
            $matumizi_benki = MatumiziBenki::whereBetween('tarehe', [$begin_with, $end_date])->get();
            $matumizi = $matumizi_taslimu->merge($matumizi_benki)
            ->mapToGroups(function ($item, $key) {
                return [$item['kundi'] => $item];
            });

            $title = "Jumla ya mapato na matumizi(SALIO) $start_month ($previous_year) - $current_month ($current_year)";

            if($print_type=='null'){
            return view('backend.main_reports.fedha.salio', compact('sadaka_za_misa','sadaka_za_jumuiya','mapato_ya_zaka','aina_ya_michango_jumla','title', 'matumizi','kuanzia','ukomo'));
            }elseif($print_type=='pdf'){
                $pdf = PDF::loadView('backend.main_reports.fedha.salio_pdf', compact(['sadaka_za_misa','sadaka_za_jumuiya','mapato_ya_zaka','aina_ya_michango_jumla','title', 'matumizi','kuanzia','ukomo']));
                return $pdf->stream($title . ".pdf"); }
                
                else{
       
                }
        
        
    }

}
