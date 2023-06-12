<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kanda;
use App\Jumuiya;
use App\Familia;
use App\Mwanafamilia;
use App\MakundiRika;
use Excel;
use PDF;
use Alert;
use Carbon;

class MainMasakramentiReport extends Controller
{
    //
    public function masakramenti_ripoti(){
        $title = "Ripoti za masakramenti";
        $orodha_jumuiya = Jumuiya::latest()->get();
        $orodha_familia = Familia::latest()->get();
        
        return view('backend.main_reports.masakramenti.masakramenti_ripoti',compact(['title','orodha_jumuiya','orodha_familia']));
    }

    public function masakramenti_ripoti_generate(Request $request){

        //getting aina ya ripoti
        $aina_ya_ripoti = $request->aina_ya_ripoti;

        //getting ngazi
        $ngazi = $request->ngazi;

        //validating if no empty field based on ngazi
        if($ngazi == "Familia"){
            //getting the selected familia
            $familia = $request->familia;

            if($familia == ""){
                Alert::toast("Hakikisha umechagua familia kwakua ulichagua ngazi ya familia","info")->autoClose(6000);
                return back();
            }
        }

        //what if is jumuiya
        if($ngazi == "Jumuiya"){
            $jumuiya = $request->jumuiya;

            if($jumuiya == ""){
                Alert::toast("Hakikisha umechagua jumuiya kwakua ulichagua ngazi ya jumuiya","info")->autoClose(6000);
                return back();
            }
        }

        //verifying the dates
        $mchujo = $request->mchujo;

        if($mchujo == "tarehe_husika"){
            //forcing user to input the beginning and ending dates
            $kuanzia = $request->kuanzia;

            $kuishia = $request->ukomo_tarehe;

            //tarehe ya kuanzia
            if($kuanzia == ""){
                Alert::info("Hakikisha umechajaza tarehe ya kuanzia kwakua ulichagua tarehe husika...","info")->autoClose(6000);
                return back();
            }

            //tarehe ya kuishia
            if($kuanzia == ""){
                Alert::info("Hakikisha umechajaza tarehe ya kuishia kwakua ulichagua tarehe husika...","info")->autoClose(6000);
                return back();
            }
        }

        switch ($aina_ya_ripoti) {
            case 'sensa_waamini':
                
                # code...
                if($ngazi == "Familia"){

                    $title = "Sensa ya waamini ngazi ya familia";

                    $data_familia = Familia::whereId($familia)->first();

                    //details for wazazi
                    $data_wazazi = Familia::leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
                    ->where('familias.id',$familia)
                    ->whereIn('mwanafamilias.cheo_familia',['Baba','Mama'])
                    ->get(['mwanafamilias.jina_kamili','mwanafamilias.ndoa','mwanafamilias.aina_ya_ndoa','mwanafamilias.cheo_familia','mwanafamilias.dhehebu']);

                    //details for familia
                    $data_wanafamilia = Familia::leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
                    ->where('familias.id',$familia)
                    ->whereNotIn('mwanafamilias.cheo_familia',['Baba','Mama'])
                    ->get(['mwanafamilias.jina_kamili','familias.jina_la_jumuiya','mwanafamilias.cheo_familia','mwanafamilias.ndoa','mwanafamilias.komunio','mwanafamilias.ubatizo','mwanafamilias.kipaimara','mwanafamilias.dob']);

                    $rika = MakundiRika::latest()->get();
                    
                    return view('backend.main_reports.masakramenti.sensa_waamini_familia',compact(['title','data_familia','rika','data_wanafamilia','data_wazazi']));
                }
                else{
                    //getting the name of jumuiya and kanda
                    $kanda = Jumuiya::where('jina_la_jumuiya',$jumuiya)->value('jina_la_kanda');

                    $title = "Sensa ya waamini ngazi ya jumuiya - Majumuisho (Fomu No:2)";

                    $kichwa = "Jumuiya ya $jumuiya kanda $kanda";

                    //-------NDOA ZA KIKATOLIKI------//
                    $data_ndoa_kikatoliki = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->where('familias.jina_la_jumuiya',$jumuiya)
                    ->where('mwanafamilias.aina_ya_ndoa','kikatoliki')
                    ->where('mwanafamilias.cheo_familia','Baba')
                    ->count();

                    //-------- NDOA ZA MSETO -------//
                    $data_ndoa_mseto = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->where('familias.jina_la_jumuiya',$jumuiya)
                    ->where('mwanafamilias.aina_ya_ndoa','mseto')
                    ->where('mwanafamilias.cheo_familia','Baba')
                    ->count();

                    //-------- BILA NDOA YOYOTE -------//
                    $data_bila_ndoa = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->where('familias.jina_la_jumuiya',$jumuiya)
                    ->whereNull('mwanafamilias.aina_ya_ndoa')
                    ->where('mwanafamilias.cheo_familia','Baba')
                    ->count();


                    $data_vijana = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                        ->whereNull('mwanafamilias.ndoa')
                        ->where('familias.jina_la_jumuiya',$jumuiya)
                        ->where('mwanafamilias.rika','like','%jana%')->count();

                    $data_watoto = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                        ->whereNull('mwanafamilias.ndoa')
                        ->where('familias.jina_la_jumuiya',$jumuiya)
                        ->where('mwanafamilias.rika','like','%toto%')->count();

                    //populated data for jumuiya
                    $data_jumuiya = Mwanafamilia::latest('mwanafamilias.created_at')
                        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                        ->where('familias.jina_la_jumuiya',$jumuiya)
                        ->get(['mwanafamilias.ndoa','mwanafamilias.aina_ya_ndoa','mwanafamilias.jinsia','mwanafamilias.dob']);
                    
                    $data_wanaume = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->where('familias.jina_la_jumuiya',$jumuiya)
                    ->where('mwanafamilias.jinsia','Mwanaume')
                    ->count('*');

                    $data_wanawake = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->where('familias.jina_la_jumuiya',$jumuiya)
                    ->where('mwanafamilias.jinsia','Mwanamke')
                    ->count('*');

                    $data_jumla_kaya = Familia::where('jina_la_jumuiya',$jumuiya)->count();

                    //data wajane
                    $data_wajane = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->where('familias.jina_la_jumuiya',$jumuiya)
                    ->where('mwanafamilias.ndoa','mjane')
                    ->count('*');

                    //data wagane
                    $data_wagane = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->where('familias.jina_la_jumuiya',$jumuiya)
                    ->where('mwanafamilias.ndoa','mgane')
                    ->count('*');

                    //data pekee
                    $data_pekee = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->where('familias.jina_la_jumuiya',$jumuiya)
                    ->where('mwanafamilias.ndoa','pekee')
                    ->count('*');

                    //wanajumuiya wote
                    $data_wanajumuiya_wote = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->where('familias.jina_la_jumuiya',$jumuiya)
                    ->count('*');

                    return view('backend.main_reports.masakramenti.sensa_waamini_jumuiya',compact(['title','kanda','jumuiya','kichwa','data_jumuiya','data_wanaume',
                    'data_wanawake','data_jumla_kaya','data_wajane','data_wagane','data_pekee','data_wanajumuiya_wote','data_ndoa_kikatoliki','data_ndoa_mseto','data_bila_ndoa',
                    'data_watoto','data_vijana']));
                }
                break;
            
            default:
                # code...
                return back();
                break;
        }
        
    }
}
