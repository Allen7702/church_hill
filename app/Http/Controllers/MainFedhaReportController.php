<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Carbon;
use App\AinaZaMichango;
use App\AinaZaMali;
use App\AinaZaMatumizi;
use App\MatumiziBenki;
use App\MatumiziTaslimu;
use App\MichangoBenki;
use App\MichangoTaslimu;
use App\SadakaKuu;
use App\SadakaJumuiya;
use App\Zaka;
use Alert;
use App\Exports\sadaka\SadakaMisa;
use App\SadakaZaMisa;
use DB;


class MainFedhaReportController extends Controller
{
    //
    public function fedha_ripoti(Request $request){
        //
        $title = "Ripoti za fedha";
        return view('backend.main_reports.fedha.fedha_ripoti',compact(['title']));
    }

    //processing of all the reports
    public function fedha_ripoti_generate(Request $request){

        

        //getting aina ya ripoti
        $aina_ya_ripoti = $request->aina_ya_ripoti;

        //getting the mchujo
        $mchujo = $request->mchujo;

        //making decision based on mchujo type
       /// if($mchujo == "tarehe_zote"){

            //switching the aina ya ripoti


            if($mchujo=='tarehe_zote'){
                      
                $kuanzia= "01-01-".Carbon::now()->format('Y'); //this to get the first date of the year and current year
               $ukomo= "31-12-".Carbon::now()->format('Y');
                    }else{
            //means tarehe specific
            $kuanzia = $request->kuanzia;
            $ukomo = $request->ukomo;

            
            

            if(($kuanzia || $ukomo) == ""){
                Alert::toast("Tafadhali hakikisha umechagua tarehe ya kuanzia na tarehe ya kuishia", "info")->autoClose(6000);
                return redirect()->back();
            }
        }

        $data_variable = $this->getVariables($kuanzia,$ukomo);

         $current_month = $data_variable['current_month'];
         $current_year = $data_variable['current_year'];
         $previous_year=$data_variable['previous_year'];
         $start_month = $data_variable['start_month'];
         $start_date = $data_variable['start_date'];
         $end_date=$data_variable['end_date'];
         $begin_with = Carbon::parse($start_date)->format('Y-m-d');
            switch ($aina_ya_ripoti) {
                //mapato
                case "mapato":{
                        
       
                        $title = "Ripoti ya mapato ya parokia $start_month ($previous_year) - $current_month ($current_year)";

                        $data_sadaka = SadakaKuu::whereBetween('tarehe',[$begin_with,$end_date])->select(DB::raw("SUM(kiasi) as kiasi"),'misa')
                            ->orderBy('kiasi','DESC')
                          
                            ->groupBy('misa')
                            ->get();
                        
                        //overall year sum of sadaka
                        $sadaka_total = SadakaKuu::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');

                        //overall year sum of sadaka za jumuiya
                        $sadaka_jumuiya = SadakaJumuiya::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');

                        //overall year sum of zaka
                        $zaka_total = Zaka::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');
                        
                        $mapato_kawaida_total = $zaka_total + $sadaka_jumuiya + $sadaka_total;


                        //================================= COMBINE THE MICHANGO OF BENKI AND TASLIMU TO FORM SINGLE TABLE=======================================================================================================================================================
                        $pato_taslimu = DB::table('michango_taslimus')->whereBetween('tarehe',[$begin_with,$end_date])->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_mchango')->groupBy('aina_ya_mchango');

                        $pato_benki = DB::table('michango_benkis')->whereBetween('tarehe',[$begin_with,$end_date])->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_mchango')->groupBy('aina_ya_mchango');

                        $combined = $pato_taslimu->unionAll($pato_benki);

                        $data_mapato_maendeleo = DB::table(DB::raw("({$combined->toSql()}) AS merged"))->mergeBindings($combined)->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_mchango')->groupBy('aina_ya_mchango')->get();
                        //========================================================================================================================================================================================
        
                        //the sum of all mapato maendeleo of the current year
                        
                        //mapato taslimu
                        $mapato_taslimu = MichangoTaslimu::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');

                        //mapato benki
                        $mapato_benki = MichangoBenki::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');

                        $mapato_michango_total = $mapato_taslimu + $mapato_benki;

                        $mapato_yote_total = $mapato_michango_total + $mapato_kawaida_total;

                       return view('backend.main_reports.fedha.mapato_ripoti',compact(['title','data_sadaka','sadaka_total','sadaka_jumuiya','zaka_total','mapato_kawaida_total','data_mapato_maendeleo','mapato_michango_total','mapato_yote_total']));
                        //--------------------------------------------//
                    }

                break;
                
                //matumizi
                case "matumizi":{
                        $title = "Ripoti ya matumizi ya parokia $start_month ($previous_year) - $current_month ($current_year)";
                        
                        //================================= KAWAIDA =====================================//
                        //getting the matumizi from taslimus on ankras where kundi is kawaida
                        $matumizi_taslimu_ankras_kawaida = DB::table('matumizi_taslimus')->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_taslimus.namba_ya_ankra')
                        ->whereNotNull('matumizi_taslimus.namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','kawaida')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_taslimus.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');
                        
                        //getting the matumizi taslimus on mengineyo
                        $matumizi_taslimu_mengineyo_kawaida = DB::table('matumizi_taslimus')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','kawaida')
                        ->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('aina_ya_matumizi');
                        
                        //combine the matumizi taslimus of both
                        $combined = $matumizi_taslimu_ankras_kawaida->unionAll($matumizi_taslimu_mengineyo_kawaida);

                        $data_matumizi_taslimu_kawaida = DB::table(DB::raw("({$combined->toSql()}) AS merged"))
                        ->mergeBindings($combined)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //============================================ MATUMIZI BENKIS ANKRAS KAWAIDA ======
                        //getting the matumizi from benkis on ankras
                        $matumizi_benki_ankras_kawaida = DB::table('matumizi_benkis')->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_benkis.namba_ya_ankra')
                        ->whereNotNull('matumizi_benkis.namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','kawaida')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_benkis.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');
                        
                        //getting the matumizi benkis on mengineyo
                        $matumizi_benki_mengineyo_kawaida = DB::table('matumizi_benkis')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','kawaida')
                        ->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'matumizi_benkis.aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('aina_ya_matumizi');

                        //combine the matumizi benki of both
                        $combined_benki = $matumizi_benki_ankras_kawaida->unionAll($matumizi_benki_mengineyo_kawaida);
                        
                        $data_matumizi_benki_kawaida = DB::table(DB::raw("({$combined_benki->toSql()}) AS merged"))
                        ->mergeBindings($combined_benki)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //========================== COMBINATION ==================================
                        $combined_kawaida = $data_matumizi_taslimu_kawaida->unionAll($data_matumizi_benki_kawaida);

                        $data_kawaida = DB::table(DB::raw("({$combined_kawaida->toSql()}) AS merged"))
                        ->mergeBindings($combined_kawaida)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi')
                        ->get();

                        //=========================================================================

                        //=================================== MAENDELEO ==============================//
                        $matumizi_taslimu_ankras_maendeleo = DB::table('matumizi_taslimus')->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_taslimus.namba_ya_ankra')
                        ->whereNotNull('matumizi_taslimus.namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','maendeleo')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_taslimus.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');

                        //getting the matumizi taslimus on mengineyo
                        $matumizi_taslimu_mengineyo_maendeleo = DB::table('matumizi_taslimus')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','maendeleo')
                        ->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('aina_ya_matumizi');

                        //================ combine the taslimu and ankras of maendeleo from matumizi taslimus                        
                        $combined_taslimu_maendeleo = $matumizi_taslimu_ankras_maendeleo->unionAll($matumizi_taslimu_mengineyo_maendeleo);

                        $data_matumizi_taslimu_maendeleo = DB::table(DB::raw("({$combined_taslimu_maendeleo->toSql()}) AS merged"))
                        ->mergeBindings($combined_taslimu_maendeleo)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //=========================== MAENDELEO ON BENKI ====================================================
                        //getting the matumizi from benkis on ankras
                        $matumizi_benki_ankras_maendeleo = DB::table('matumizi_benkis')->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_benkis.namba_ya_ankra')
                        ->whereNotNull('matumizi_benkis.namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','maendeleo')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_benkis.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');

                        //getting the matumizi benkis on mengineyo
                        $matumizi_benki_mengineyo_maendeleo = DB::table('matumizi_benkis')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','maendeleo')
                        ->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'matumizi_benkis.aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('aina_ya_matumizi');

                        //combine now the benkis matumizis
                        $combined_benki_maendeleo = $matumizi_benki_ankras_maendeleo->unionAll($matumizi_benki_mengineyo_maendeleo);

                        $data_matumizi_benki_maendeleo = DB::table(DB::raw("({$combined_benki_maendeleo->toSql()}) AS merged"))
                        ->mergeBindings($combined_benki_maendeleo)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //================= COMBINE THE MAENDELEO OF BOTH TASLIMU AND BENKI ========

                        $combined_maendeleo = $data_matumizi_taslimu_maendeleo->unionAll($data_matumizi_benki_maendeleo);

                        $data_maendeleo = DB::table(DB::raw("({$combined_maendeleo->toSql()}) AS merged"))
                        ->mergeBindings($combined_maendeleo)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi')
                        ->get();
                        
                        //===============================================================
                        
                        
                        //if this failed just expand the queries above and find the sum manually then total
                        $maendeleo_total = $data_maendeleo->sum('kiasi');

                        $kawaida_total = $data_kawaida->sum('kiasi');

                        $matumizi_total = $maendeleo_total + $kawaida_total;

                        return view('backend.main_reports.fedha.matumizi_ripoti',compact(['title','matumizi_total','maendeleo_total','kawaida_total','data_maendeleo','data_kawaida']));
                    }
                break;

                case 'mapato_matumizi': {
                    

                        //------------- SADAKA KUU -------------------//
                        //setting the beginning of the month in a year and current month
                        $data_variable = $this->getVariables($kuanzia,$ukomo);

                       // dd($data_variable);

                        $current_month = $data_variable['current_month'];
                        $current_year = $data_variable['current_year'];
                        $start_month = $data_variable['start_month'];
                        $start_date = $data_variable['start_date'];
                        $end_date=$data_variable['end_date'];
                        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
                       
                        $title = "Ripoti ya mapato na matumizi ya parokia $start_month ($previous_year) - $current_month ($current_year)";

                        // $data_sadaka = SadakaKuu::whereBetween('tarehe',[$begin_with,Carbon::now()])->select(DB::raw("SUM(kiasi) as kiasi"),'misa')
                        //     ->orderBy('kiasi','DESC')
                        //     ->whereYear('tarehe', date('Y'))
                        //     ->groupBy('misa')
                        //     ->get();

                        $data_sadaka = SadakaZaMisa::whereBetween('ilifanyika',[$begin_with,$end_date])->sum('kiasi');
                        
                      
                        //overall year sum of sadaka
                        $sadaka_total =SadakaZaMisa::whereBetween('ilifanyika',[$begin_with,$end_date])->sum('kiasi');

                        //overall year sum of sadaka za jumuiya
                        $sadaka_jumuiya = SadakaJumuiya::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');

                        //overall year sum of zaka
                        $zaka_total = Zaka::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');
                        
                        $mapato_kawaida_total = $zaka_total + $sadaka_jumuiya + $sadaka_total;


                        //================================= COMBINE THE MICHANGO OF BENKI AND TASLIMU TO FORM SINGLE TABLE=======================================================================================================================================================
                        $pato_taslimu = DB::table('michango_taslimus')->whereBetween('tarehe',[$begin_with,$end_date])->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_mchango')->groupBy('aina_ya_mchango');

                        $pato_benki = DB::table('michango_benkis')->whereBetween('tarehe',[$begin_with,$end_date])->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_mchango')->groupBy('aina_ya_mchango');

                        $combined = $pato_taslimu->unionAll($pato_benki);

                        $data_mapato_maendeleo = DB::table(DB::raw("({$combined->toSql()}) AS merged"))->mergeBindings($combined)->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_mchango')->groupBy('aina_ya_mchango')->get();
                        
                        
                        //========================================================================================================================================================================================
        
                        //the sum of all mapato maendeleo of the current year
                        
                        //mapato taslimu
                        $mapato_taslimu = MichangoTaslimu::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');

                        //mapato benki
                        $mapato_benki = MichangoBenki::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');

                        $mapato_michango_total = $mapato_taslimu + $mapato_benki;

                        $mapato_yote_total = $mapato_michango_total + $mapato_kawaida_total;
                        
                        //================================= KAWAIDA =====================================//
                        //getting the matumizi from taslimus on ankras where kundi is kawaida
                        $matumizi_taslimu_ankras_kawaida = DB::table('matumizi_taslimus')->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_taslimus.namba_ya_ankra')
                        ->whereNotNull('matumizi_taslimus.namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','kawaida')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_taslimus.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');
                        
                        //getting the matumizi taslimus on mengineyo
                        $matumizi_taslimu_mengineyo_kawaida = DB::table('matumizi_taslimus')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','kawaida')
                        ->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('aina_ya_matumizi');
                        
                        //combine the matumizi taslimus of both
                        $combined = $matumizi_taslimu_ankras_kawaida->unionAll($matumizi_taslimu_mengineyo_kawaida);

                        $data_matumizi_taslimu_kawaida = DB::table(DB::raw("({$combined->toSql()}) AS merged"))
                        ->mergeBindings($combined)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //============================================ MATUMIZI BENKIS ANKRAS KAWAIDA ======
                        //getting the matumizi from benkis on ankras
                        $matumizi_benki_ankras_kawaida = DB::table('matumizi_benkis')->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_benkis.namba_ya_ankra')
                        ->whereNotNull('matumizi_benkis.namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','kawaida')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_benkis.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');
                        
                        //getting the matumizi benkis on mengineyo
                        $matumizi_benki_mengineyo_kawaida = DB::table('matumizi_benkis')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','kawaida')
                        ->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'matumizi_benkis.aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('aina_ya_matumizi');

                        //combine the matumizi benki of both
                        $combined_benki = $matumizi_benki_ankras_kawaida->unionAll($matumizi_benki_mengineyo_kawaida);
                        
                        $data_matumizi_benki_kawaida = DB::table(DB::raw("({$combined_benki->toSql()}) AS merged"))
                        ->mergeBindings($combined_benki)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //========================== COMBINATION ==================================
                        $combined_kawaida = $data_matumizi_taslimu_kawaida->unionAll($data_matumizi_benki_kawaida);

                        $data_kawaida = DB::table(DB::raw("({$combined_kawaida->toSql()}) AS merged"))
                        ->mergeBindings($combined_kawaida)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi')
                        ->get();

                        //=========================================================================

                        //=================================== MAENDELEO ==============================//
                        $matumizi_taslimu_ankras_maendeleo = DB::table('matumizi_taslimus')->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_taslimus.namba_ya_ankra')
                        ->whereNotNull('matumizi_taslimus.namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','maendeleo')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_taslimus.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');

                        //getting the matumizi taslimus on mengineyo
                        $matumizi_taslimu_mengineyo_maendeleo = DB::table('matumizi_taslimus')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','maendeleo')
                        ->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('aina_ya_matumizi');

                        //================ combine the taslimu and ankras of maendeleo from matumizi taslimus                        
                        $combined_taslimu_maendeleo = $matumizi_taslimu_ankras_maendeleo->unionAll($matumizi_taslimu_mengineyo_maendeleo);

                        $data_matumizi_taslimu_maendeleo = DB::table(DB::raw("({$combined_taslimu_maendeleo->toSql()}) AS merged"))
                        ->mergeBindings($combined_taslimu_maendeleo)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //=========================== MAENDELEO ON BENKI ====================================================
                        //getting the matumizi from benkis on ankras
                        $matumizi_benki_ankras_maendeleo = DB::table('matumizi_benkis')->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_benkis.namba_ya_ankra')
                        ->whereNotNull('matumizi_benkis.namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','maendeleo')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_benkis.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');

                        //getting the matumizi benkis on mengineyo
                        $matumizi_benki_mengineyo_maendeleo = DB::table('matumizi_benkis')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','maendeleo')
                        ->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'matumizi_benkis.aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('aina_ya_matumizi');

                        //combine now the benkis matumizis
                        $combined_benki_maendeleo = $matumizi_benki_ankras_maendeleo->unionAll($matumizi_benki_mengineyo_maendeleo);

                        $data_matumizi_benki_maendeleo = DB::table(DB::raw("({$combined_benki_maendeleo->toSql()}) AS merged"))
                        ->mergeBindings($combined_benki_maendeleo)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //================= COMBINE THE MAENDELEO OF BOTH TASLIMU AND BENKI ========

                        $combined_maendeleo = $data_matumizi_taslimu_maendeleo->unionAll($data_matumizi_benki_maendeleo);

                        $data_maendeleo = DB::table(DB::raw("({$combined_maendeleo->toSql()}) AS merged"))
                        ->mergeBindings($combined_maendeleo)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi')
                        ->get();
                        
                        //===============================================================
                        //if this failed just expand the queries above and find the sum manually then total
                        $maendeleo_total = $data_maendeleo->sum('kiasi');

                        $kawaida_total = $data_kawaida->sum('kiasi');

                        $matumizi_total = $maendeleo_total + $kawaida_total;

                        $salio = $mapato_yote_total-$matumizi_total;

                        return view('backend.main_reports.fedha.mapato_matumizi_ripoti',compact(['title','matumizi_total','maendeleo_total','kawaida_total','data_maendeleo','data_kawaida','salio',
                        'data_sadaka','sadaka_total','sadaka_jumuiya','zaka_total','mapato_kawaida_total','data_mapato_maendeleo','mapato_michango_total','mapato_yote_total','kuanzia','ukomo']));
                    }
                break;

                case 'mizania': {
                    $title = "Ripoti ya mizania $start_month ($previous_year) - $current_month ($current_year)";
                    
                    return view('backend.main_reports.fedha.mizania_ripoti',compact(['title']));
                }
                break;

                default:{
                        //no choice we have hence return back
                        Alert::toast("Hakuna ripoti kwa uchaguzi uliofanywa..","info");
                        return back();
                    }
                break;
            }
    
    }



    public function fedha_ripoti_generate_print($print_type,$kuanzia,$ukomo)
    {
        $aina_ya_ripoti ='mapato_matumizi';

        //getting the mchujo
        $mchujo =  "tarehe_zote";

        $data_variable = $this->getVariables($kuanzia,$ukomo);

                        $current_month = $data_variable['current_month'];
                        $current_year = $data_variable['current_year'];
                        $start_month = $data_variable['start_month'];
                        $previous_year=$data_variable['previous_year'];
                        $start_date = $data_variable['start_date'];
                        $end_date=$data_variable['end_date'];
                        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
                        $title = "Ripoti ya mapato na matumizi ya parokia $start_month ($previous_year) - $current_month ($current_year)";

                        // $data_sadaka = SadakaKuu::whereBetween('tarehe',[$begin_with,Carbon::now()])->select(DB::raw("SUM(kiasi) as kiasi"),'misa')
                        //     ->orderBy('kiasi','DESC')
                        //     ->whereYear('tarehe', date('Y'))
                        //     ->groupBy('misa')
                        //     ->get();

                        $data_sadaka = SadakaZaMisa::whereBetween('ilifanyika',[$begin_with,$end_date])->sum('kiasi');
                        
                        
                        //overall year sum of sadaka
                        $sadaka_total = SadakaZaMisa::whereBetween('ilifanyika',[$begin_with,$end_date])->sum('kiasi');

                        //overall year sum of sadaka za jumuiya
                        $sadaka_jumuiya = SadakaJumuiya::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');

                        //overall year sum of zaka
                        $zaka_total = Zaka::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');
                        
                        $mapato_kawaida_total = $zaka_total + $sadaka_jumuiya + $sadaka_total;


                        //================================= COMBINE THE MICHANGO OF BENKI AND TASLIMU TO FORM SINGLE TABLE=======================================================================================================================================================
                        $pato_taslimu = DB::table('michango_taslimus')->whereBetween('tarehe',[$begin_with,$end_date])->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_mchango')->groupBy('aina_ya_mchango');

                        $pato_benki = DB::table('michango_benkis')->whereBetween('tarehe',[$begin_with,$end_date])->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_mchango')->groupBy('aina_ya_mchango');

                        $combined = $pato_taslimu->unionAll($pato_benki);

                        $data_mapato_maendeleo = DB::table(DB::raw("({$combined->toSql()}) AS merged"))->mergeBindings($combined)->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_mchango')->groupBy('aina_ya_mchango')->get();
                        
                        
                        //========================================================================================================================================================================================
        
                        //the sum of all mapato maendeleo of the current year
                        
                        //mapato taslimu
                        $mapato_taslimu = MichangoTaslimu::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');

                        //mapato benki
                        $mapato_benki = MichangoBenki::whereBetween('tarehe',[$begin_with,$end_date])->sum('kiasi');

                        $mapato_michango_total = $mapato_taslimu + $mapato_benki;

                        $mapato_yote_total = $mapato_michango_total + $mapato_kawaida_total;
                        
                        //================================= KAWAIDA =====================================//
                        //getting the matumizi from taslimus on ankras where kundi is kawaida
                        $matumizi_taslimu_ankras_kawaida = DB::table('matumizi_taslimus')->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_taslimus.namba_ya_ankra')
                        ->whereNotNull('matumizi_taslimus.namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','kawaida')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_taslimus.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');
                        
                        //getting the matumizi taslimus on mengineyo
                        $matumizi_taslimu_mengineyo_kawaida = DB::table('matumizi_taslimus')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','kawaida')
                        ->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('aina_ya_matumizi');
                        
                        //combine the matumizi taslimus of both
                        $combined = $matumizi_taslimu_ankras_kawaida->unionAll($matumizi_taslimu_mengineyo_kawaida);

                        $data_matumizi_taslimu_kawaida = DB::table(DB::raw("({$combined->toSql()}) AS merged"))
                        ->mergeBindings($combined)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //============================================ MATUMIZI BENKIS ANKRAS KAWAIDA ======
                        //getting the matumizi from benkis on ankras
                        $matumizi_benki_ankras_kawaida = DB::table('matumizi_benkis')->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_benkis.namba_ya_ankra')
                        ->whereNotNull('matumizi_benkis.namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','kawaida')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_benkis.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');
                        
                        //getting the matumizi benkis on mengineyo
                        $matumizi_benki_mengineyo_kawaida = DB::table('matumizi_benkis')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','kawaida')
                        ->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'matumizi_benkis.aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('aina_ya_matumizi');

                        //combine the matumizi benki of both
                        $combined_benki = $matumizi_benki_ankras_kawaida->unionAll($matumizi_benki_mengineyo_kawaida);
                        
                        $data_matumizi_benki_kawaida = DB::table(DB::raw("({$combined_benki->toSql()}) AS merged"))
                        ->mergeBindings($combined_benki)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //========================== COMBINATION ==================================
                        $combined_kawaida = $data_matumizi_taslimu_kawaida->unionAll($data_matumizi_benki_kawaida);

                        $data_kawaida = DB::table(DB::raw("({$combined_kawaida->toSql()}) AS merged"))
                        ->mergeBindings($combined_kawaida)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi')
                        ->get();

                        //=========================================================================

                        //=================================== MAENDELEO ==============================//
                        $matumizi_taslimu_ankras_maendeleo = DB::table('matumizi_taslimus')->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_taslimus.namba_ya_ankra')
                        ->whereNotNull('matumizi_taslimus.namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','maendeleo')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_taslimus.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');

                        //getting the matumizi taslimus on mengineyo
                        $matumizi_taslimu_mengineyo_maendeleo = DB::table('matumizi_taslimus')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_taslimus.kundi','maendeleo')
                        ->whereBetween('matumizi_taslimus.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','matumizi_taslimus.kundi')
                        ->groupBy('aina_ya_matumizi');

                        //================ combine the taslimu and ankras of maendeleo from matumizi taslimus                        
                        $combined_taslimu_maendeleo = $matumizi_taslimu_ankras_maendeleo->unionAll($matumizi_taslimu_mengineyo_maendeleo);

                        $data_matumizi_taslimu_maendeleo = DB::table(DB::raw("({$combined_taslimu_maendeleo->toSql()}) AS merged"))
                        ->mergeBindings($combined_taslimu_maendeleo)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //=========================== MAENDELEO ON BENKI ====================================================
                        //getting the matumizi from benkis on ankras
                        $matumizi_benki_ankras_maendeleo = DB::table('matumizi_benkis')->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_benkis.namba_ya_ankra')
                        ->whereNotNull('matumizi_benkis.namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','maendeleo')
                        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
                        //========== VERY IMPORTANT TWIST THE aina_ya_huduma INTO aina_ya_matumizi
                        ->select(DB::raw("SUM(matumizi_benkis.kiasi) as kiasi"),'watoa_hudumas.aina_ya_huduma as aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('watoa_hudumas.aina_ya_huduma');

                        //getting the matumizi benkis on mengineyo
                        $matumizi_benki_mengineyo_maendeleo = DB::table('matumizi_benkis')
                        ->whereNull('namba_ya_ankra')
                        ->where('matumizi_benkis.kundi','maendeleo')
                        ->whereBetween('matumizi_benkis.tarehe',[$begin_with,$end_date])
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'matumizi_benkis.aina_ya_matumizi','matumizi_benkis.kundi')
                        ->groupBy('aina_ya_matumizi');

                        //combine now the benkis matumizis
                        $combined_benki_maendeleo = $matumizi_benki_ankras_maendeleo->unionAll($matumizi_benki_mengineyo_maendeleo);

                        $data_matumizi_benki_maendeleo = DB::table(DB::raw("({$combined_benki_maendeleo->toSql()}) AS merged"))
                        ->mergeBindings($combined_benki_maendeleo)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi');

                        //================= COMBINE THE MAENDELEO OF BOTH TASLIMU AND BENKI ========

                        $combined_maendeleo = $data_matumizi_taslimu_maendeleo->unionAll($data_matumizi_benki_maendeleo);

                        $data_maendeleo = DB::table(DB::raw("({$combined_maendeleo->toSql()}) AS merged"))
                        ->mergeBindings($combined_maendeleo)
                        ->select(DB::raw("SUM(kiasi) as kiasi"),'aina_ya_matumizi','kundi')
                        ->groupBy('aina_ya_matumizi')
                        ->get();
                        
                        //===============================================================
                        //if this failed just expand the queries above and find the sum manually then total
                        $maendeleo_total = $data_maendeleo->sum('kiasi');

                        $kawaida_total = $data_kawaida->sum('kiasi');

                        $matumizi_total = $maendeleo_total + $kawaida_total;

                        $salio = $mapato_yote_total-$matumizi_total;
                          if($print_type=='pdf'){
                        $pdf = PDF::loadView('backend.main_reports.fedha.mapato_matumizi_ripoti_pdf',compact(['title','matumizi_total','maendeleo_total','kawaida_total','data_maendeleo','data_kawaida','salio',
                        'data_sadaka','sadaka_total','sadaka_jumuiya','zaka_total','mapato_kawaida_total','data_mapato_maendeleo','mapato_michango_total','mapato_yote_total']));
                        return $pdf->stream($title . ".pdf");
                          }else{

                          }

    }

    //internal class variables
    private function getVariables($tareheMwanzo,$tareheMwisho){
 
        $data_variable=[
            'previous_year'=>date('Y',strtotime($tareheMwanzo)),
            'current_year' => date('Y',strtotime($tareheMwisho)),
            'current_month' => date('F',strtotime($tareheMwisho)),
            'start_month' =>date('F', strtotime($tareheMwanzo)),
            'start_date' => date('Y-m-d',strtotime($tareheMwanzo)),
            'end_date'=>date('Y-m-d',strtotime($tareheMwisho))

        ];
    
        
        return $data_variable;
    
    }
}
