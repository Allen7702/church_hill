<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\MichangoTaslimu;
use App\MichangoBenki;
use App\AinaZaMichango;
use App\Zaka;
use App\Mwanafamilia;
use App\Jumuiya;
use App\Kanda;
use Alert;
use Carbon;
use DB;
use PDF;

class StakabadhiController extends Controller
{
    //
    public function stakabadhi_index(){
        $title_mkupuo = "Stakabadhi kwa mkupuo";
        $title_kawaida = "Stakabadhi kawaida";
        $kanda = Kanda::latest()->get();
        $aina_za_michango = AinaZaMichango::latest()->get();

        //populate data za waumini
        $waumini = DB::table('mwanafamilias')->leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->select('mwanafamilias.jina_kamili','mwanafamilias.id as mwanafamilia_id','familias.jina_la_jumuiya','mwanafamilias.namba_utambulisho')
            ->where('mwanafamilias.ubatizo','tayari')
            ->get();

        //getting the jumuiyas
        $jumuiyas = Jumuiya::latest()->get();
        
        //dealing with 
        return view('backend.risiti.stakabadhi',compact(['title_mkupuo','title_kawaida','waumini','kanda','aina_za_michango','jumuiyas']));
    }

    //function to handle the zaka mkupuo
    public function stakabadhi_zaka_mkupuo(Request $request){

    
        //validating data
        $tarehe = $request->tarehe;
        $ngazi = $request->ngazi;

        if($ngazi == ""){
            Alert::info("Taarifa!","Hakikisha umefanya uchaguzi wa ngazi kama ni jumuiya au wanajumuiya..");
            return back();
        }

        if($ngazi == "risiti_za_wanajumuiya"){
            $selected_jumuiya = $request->wanajumuiya_zaka_mkupuo;

            //selected jumuiya
            if(($selected_jumuiya == "") && ($tarehe == "")){
                Alert::info("Taarifa!","Hakikisha umefanya uchaguzi wa mwezi na jumuiya husika..");
                return back();
            }

            //tarehe na jumuiya 
            if(($tarehe == "") || ($selected_jumuiya == "")){
                Alert::info("Taarifa!","Hakikisha umefanya uchaguzi sahihi wa mwezi na jumuiya husika..");
                return back();
            }

            //getting the name of jumuiya
            $jumuiya_jina = Jumuiya::whereId($selected_jumuiya)->value('jina_la_jumuiya');

            if($jumuiya_jina == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa za utoaji uchaguzi wa jumuiya..");
                return back();
            }

            else{
                //executing the queries
                $data_zaka = Zaka::leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','zakas.jumuiya')
                    ->where('zakas.jumuiya',$jumuiya_jina)
                    ->whereMonth('zakas.tarehe',Carbon::parse($tarehe)->format('m'))
                    ->whereYear('zakas.tarehe',Carbon::parse($tarehe)->format('Y'))
                    ->select(DB::raw("SUM(zakas.kiasi) as kiasi"),'jumuiyas.jina_la_jumuiya','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                    ->groupBy('mwanafamilias.jina_kamili')
                    ->get();
            
                    if($data_zaka->isEmpty()){
                        $mwezi = Carbon::parse($tarehe)->format('F-Y');
                        Alert::info("<b class='text-primary'>Taarifa!</b>","Hakuna taarifa za matoleo ya zaka kwa mwezi $mwezi jumuiya ya $jumuiya_jina");
                        return back();
                    }
                    else{
                        //printing now
                        $mwezi = Carbon::parse($tarehe)->format('F-Y');
                        $customPaper = array(0,0,226,360);
                        $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.zaka_mkupuo_risiti',compact(['data_zaka','mwezi']));
                        $output = $pdf->output();

                        return new Response($output, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' =>  'inline; filename="zaka_mkupuo_risiti.pdf"',
                    ]);
                }
            }
    
        }
        else{

            $kanda = $request->kanda_zaka;
            
            //kanda
            if(($kanda == "") && ($tarehe == "")){
                Alert::info("Taarifa!","Hakikisha umefanya uchaguzi wa mwezi na kanda husika..");
                return back();
            }

            //tarehe na kanda
            if(($tarehe == "") || ($kanda == "")){
                Alert::info("Taarifa!","Hakikisha umefanya uchaguzi sahihi wa mwezi na kanda husika..");
                return back();
            }

            //querying for information
            $data_zaka = Zaka::leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','zakas.jumuiya')
                ->where('jumuiyas.jina_la_kanda',$kanda)
                ->whereMonth('zakas.tarehe',Carbon::parse($tarehe)->format('m'))
                ->whereYear('zakas.tarehe',Carbon::parse($tarehe)->format('Y'))
                ->select(DB::raw("SUM(zakas.kiasi) as kiasi"),'jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                ->groupBy('jumuiyas.jina_la_jumuiya')
                ->get();
            
                if($data_zaka->isEmpty()){
                    $mwezi = Carbon::parse($tarehe)->format('F-Y');
                    Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa za matoleo ya zaka kwa mwezi $mwezi");
                    return back();
                }
                else{
                    //printing now
                    $mwezi = Carbon::parse($tarehe)->format('F-Y');
                    $customPaper = array(0,0,226,360);
                    $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.zaka_mkupuo_jumuiya_risiti',compact(['data_zaka','mwezi']));
                    $output = $pdf->output();

                    return new Response($output, 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' =>  'inline; filename="zaka_mkupuo_jumuiya_risiti.pdf"',
                ]);
            }
        }

    }

    //function to handle the mchango mkupuo
    public function stakabadhi_mchango_mkupuo(Request $request){
        
        //validating data
        $aina_ya_mchango = $request->aina_ya_mchango;
        $tarehe = $request->tarehe;

        $ngazi = $request->ngazi;

        if($ngazi == ""){
            Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa ngazi kama ni jumuiya au wanajumuiya..");
            return back();
        }

        if($ngazi == "risiti_za_wanajumuiya"){

            //wanajumuiya ngazi
            $selected_jumuiya = $request->wanajumuiya_mchango_mkupuo;

            //selected jumuiya
            if(($selected_jumuiya == "") && ($tarehe == "")){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa mwezi na jumuiya husika..");
                return back();
            }

            //tarehe na jumuiya 
            if(($tarehe == "") || ($selected_jumuiya == "")){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi sahihi wa mwezi na jumuiya husika..");
                return back();
            }

            if($aina_ya_mchango == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa aina ya mchango..");
                return back();
            }

            //getting the name of jumuiya
            $jumuiya_jina = Jumuiya::whereId($selected_jumuiya)->value('jina_la_jumuiya');

            if($jumuiya_jina == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa za utoaji uchaguzi wa jumuiya..");
                return back();
            }

            //continue with execution
            else{
                //querying for information
                //================================= COMBINE THE MICHANGO OF BENKI AND TASLIMU TO FORM SINGLE TABLE================================================================================================================================================================
                $michango_taslimu = DB::table('michango_taslimus')->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"), DB::raw("MONTH(michango_taslimus.tarehe) as mwezi"),'michango_taslimus.mwanafamilia','michango_taslimus.aina_ya_mchango','jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                    ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->where('jumuiyas.jina_la_jumuiya',$jumuiya_jina)
                    ->where('aina_ya_mchango',$aina_ya_mchango)
                    ->whereMonth('michango_taslimus.tarehe',Carbon::parse($tarehe)->format('m'))
                    ->whereYear('michango_taslimus.tarehe',Carbon::parse($tarehe)->format('Y'))
                    ->groupBy('michango_taslimus.mwanafamilia');

                //dealing with benki
                $michango_benki = DB::table('michango_benkis')->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"), DB::raw("MONTH(michango_benkis.tarehe) as mwezi"),'michango_benkis.mwanafamilia','michango_benkis.aina_ya_mchango','jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                    ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->where('jumuiyas.jina_la_jumuiya',$jumuiya_jina)
                    ->where('aina_ya_mchango',$aina_ya_mchango)
                    ->whereMonth('michango_benkis.tarehe',Carbon::parse($tarehe)->format('m'))
                    ->whereYear('michango_benkis.tarehe',Carbon::parse($tarehe)->format('Y'))
                    ->groupBy('michango_benkis.mwanafamilia');

                $combined = $michango_taslimu->unionAll($michango_benki);

                $data_michango = DB::table(DB::raw("({$combined->toSql()}) AS merged"))
                    ->mergeBindings($combined)
                    ->select(DB::raw("SUM(merged.kiasi) as kiasi"),'merged.mwanafamilia','merged.aina_ya_mchango','merged.jina_la_jumuiya','merged.mwezi','merged.jina_la_kanda','mwanafamilias.mawasiliano','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho')
                    ->leftJoin('mwanafamilias','mwanafamilias.id','=','merged.mwanafamilia')
                    ->whereNotNull('merged.mwanafamilia')
                    ->groupBy('merged.mwanafamilia')
                    ->get();
                
                    if($data_michango->isEmpty()){
                        $mwezi = Carbon::parse($tarehe)->format('F-Y');
                        Alert::info("<b class='text-info'></b>Taarifa!","Hakuna taarifa za matoleo ya $aina_ya_mchango kwa mwezi $mwezi");
                        return back();
                    }
                    else{
                        //printing now
                        $mwezi = Carbon::parse($tarehe)->format('F-Y');
                        $customPaper = array(0,0,226,360);
                        $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.michango_mkupuo_risiti',compact(['data_michango','mwezi']));
                        $output = $pdf->output();

                        return new Response($output, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' =>  'inline; filename="michango_mkupuo_risiti.pdf"',
                    ]);
                }
            }
        }
        else{
            $kanda = $request->kanda_mchango;

            if($kanda == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa kanda..");
                return back();
            }
    
            if($aina_ya_mchango == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa aina ya mchango..");
                return back();
            }
    
            if($tarehe == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa mwezi..");
                return back();
            }

            //now we are good to go
            //querying for information
            //================================= COMBINE THE MICHANGO OF BENKI AND TASLIMU TO FORM SINGLE TABLE================================================================================================================================================================
            $michango_taslimu = DB::table('michango_taslimus')->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"), DB::raw("MONTH(michango_taslimus.tarehe) as mwezi"),'michango_taslimus.mwanafamilia','michango_taslimus.aina_ya_mchango','jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
                ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                ->where('jumuiyas.jina_la_kanda',$kanda)
                ->where('aina_ya_mchango',$aina_ya_mchango)
                ->whereMonth('michango_taslimus.tarehe',Carbon::parse($tarehe)->format('m'))
                ->whereYear('michango_taslimus.tarehe',Carbon::parse($tarehe)->format('Y'))
                ->groupBy('michango_taslimus.mwanafamilia');

            //dealing with benki
            $michango_benki = DB::table('michango_benkis')->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"), DB::raw("MONTH(michango_benkis.tarehe) as mwezi"),'michango_benkis.mwanafamilia','michango_benkis.aina_ya_mchango','jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
                ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                ->where('jumuiyas.jina_la_kanda',$kanda)
                ->where('aina_ya_mchango',$aina_ya_mchango)
                ->whereMonth('michango_benkis.tarehe',Carbon::parse($tarehe)->format('m'))
                ->whereYear('michango_benkis.tarehe',Carbon::parse($tarehe)->format('Y'))
                ->groupBy('michango_benkis.mwanafamilia');

            $combined = $michango_taslimu->unionAll($michango_benki);

            $data_michango = DB::table(DB::raw("({$combined->toSql()}) AS merged"))
                ->mergeBindings($combined)
                ->select(DB::raw("SUM(merged.kiasi) as kiasi"),'merged.aina_ya_mchango','merged.jina_la_jumuiya','merged.mwezi','merged.jina_la_kanda')
                ->leftJoin('mwanafamilias','mwanafamilias.id','=','merged.mwanafamilia')
                ->whereNotNull('merged.mwanafamilia')
                ->groupBy('merged.jina_la_jumuiya')
                ->get();
            
            if($data_michango->isEmpty()){
                $mwezi = Carbon::parse($tarehe)->format('F-Y');
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa za matoleo ya $aina_ya_mchango kwa mwezi $mwezi");
                return back();
            }
            else{
                //printing now
                $mwezi = Carbon::parse($tarehe)->format('F-Y');
                $customPaper = array(0,0,226,360);
                $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.michango_mkupuo_jumuiya_risiti',compact(['data_michango','mwezi']));
                $output = $pdf->output();

                return new Response($output, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' =>  'inline; filename="michango_mkupuo_jumuiya_risiti.pdf"',
            ]);
        }       
        }
    }

    //public function the stakabadhi of zaka single
    public function stakabadhi_zaka_kawaida(Request $request){

        $ngazi = $request->ngazi;
        $tarehe = $request->tarehe;

        if($ngazi == ""){
            Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa ngazi kama ni jumuiya au wanajumuiya..");
            return back();
        }

        if($ngazi == "jumuiya"){

            //wanajumuiya ngazi
            $selected_jumuiya = $request->jumuiya_zaka_kawaida;

            //selected jumuiya
            if(($selected_jumuiya == "") && ($tarehe == "")){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa mwezi na jumuiya husika..");
                return back();
            }

            //tarehe na jumuiya 
            if(($tarehe == "") || ($selected_jumuiya == "")){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi sahihi wa mwezi na jumuiya husika..");
                return back();
            }

            //getting the name of jumuiya
            $jumuiya_jina = Jumuiya::whereId($selected_jumuiya)->value('jina_la_jumuiya');

            if($jumuiya_jina == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa za utoaji uchaguzi wa jumuiya..");
                return back();
            }

            //now we are ok so continue
            //check first on zaka to avoid empty receipts
            $data_verify = Zaka::where('jumuiya',$jumuiya_jina)->whereMonth('tarehe',Carbon::parse($tarehe)->format('m'))->whereYear('tarehe',Carbon::parse($tarehe)->format('Y'))->get();

            if($data_verify->isEmpty()){
                $mwezi = Carbon::parse($tarehe)->format('F-Y');
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa za zaka kwa waamini wa jumuiya $jumuiya_jina kwa mwezi $mwezi");
                return back();
            }
            else{
                //proceed with querying
                $data_zaka = Zaka::leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
                    ->where('zakas.jumuiya',$jumuiya_jina)
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','zakas.jumuiya')
                    ->whereMonth('zakas.tarehe',Carbon::parse($tarehe)->format('m'))
                    ->whereYear('zakas.tarehe',Carbon::parse($tarehe)->format('Y'))
                    ->select(DB::raw("SUM(zakas.kiasi) as kiasi"),'jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                    ->get();

                if($data_zaka->isEmpty()){
                    $mwezi = Carbon::parse($tarehe)->format('F-Y');
                    Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa za matoleo ya zaka kwa mwezi $mwezi");
                    return back();
                }
                else{
                    //printing now
                        $mwezi = Carbon::parse($tarehe)->format('F-Y');
                        $customPaper = array(0,0,226,360);
                        $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.zaka_jumuiya_risiti',compact(['data_zaka','mwezi']));
                        $output = $pdf->output();

                        return new Response($output, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' =>  'inline; filename="zaka_mkupuo_jumuiya_risiti.pdf"',
                    ]);
                }                
            }
        }
        else{

            //validating data
            $muumini = $request->muumini;
            $tarehe = $request->tarehe;

            if($muumini == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa muumini..");
                return back();
            }

            if($tarehe == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchanguzi wa mwezi..");
                return back();
            }

            //check first on zaka to avoid empty receipts
            $data_verify = Zaka::where('mwanajumuiya',$muumini)->whereMonth('tarehe',Carbon::parse($tarehe)->format('m'))->whereYear('tarehe',Carbon::parse($tarehe)->format('Y'))->get();

            if($data_verify->isEmpty()){
                $mwezi = Carbon::parse($tarehe)->format('F-Y');
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa za zaka kwa muumini husika kwa mwezi $mwezi");
                return back();
            }
            else{
                //proceed with querying
                $data_zaka = Zaka::leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
                    ->where('zakas.mwanajumuiya',$muumini)
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','zakas.jumuiya')
                    ->whereMonth('zakas.tarehe',Carbon::parse($tarehe)->format('m'))
                    ->whereYear('zakas.tarehe',Carbon::parse($tarehe)->format('Y'))
                    ->select(DB::raw("SUM(zakas.kiasi) as kiasi"),'jumuiyas.jina_la_jumuiya','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','jumuiyas.jina_la_kanda')
                    ->get();
                
                if($data_zaka->isNotEmpty()){

                    //assigning data
                    foreach($data_zaka as $row){
                        $mwanajumuiya = $row->jina_kamili;
                        $namba_utambulisho = $row->namba_utambulisho;
                        $jumuiya = $row->jina_la_jumuiya;
                        $kanda = $row->jina_la_kanda;
                        $aina_ya_toleo = "Zaka";
                        $kiasi = number_format($row->kiasi);
                        $tarehe = Carbon::parse($tarehe)->format('F-Y');
                    }

                    //printing now
                    $customPaper = array(0,0,226,360);
                    $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.zaka_risiti',compact(['mwanajumuiya','kiasi','tarehe','aina_ya_toleo','namba_utambulisho','jumuiya','kanda']));
                    $output = $pdf->output();

                    return new Response($output, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' =>  'inline; filename="zaka_risiti.pdf"',
                    ]);
                }
            }
        }
    }

    //public function the stakabadhi of michango single 
    public function stakabadhi_mchango_kawaida(Request $request){

        $ngazi = $request->ngazi;
        $tarehe = $request->tarehe;

        if($ngazi == ""){
            Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa ngazi kama ni jumuiya au wanajumuiya..");
            return back();
        }

        if($ngazi == "jumuiya"){
            //wanajumuiya ngazi
            $selected_jumuiya = $request->jumuiya_mchango_kawaida;
            $aina_ya_mchango = $request->aina_ya_mchango;
            $mchango = $aina_ya_mchango;

            //selected jumuiya
            if(($selected_jumuiya == "") && ($tarehe == "")){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa mwezi na jumuiya husika..");
                return back();
            }

            //tarehe na jumuiya 
            if(($tarehe == "") || ($selected_jumuiya == "")){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi sahihi wa mwezi na jumuiya husika..");
                return back();
            }

            if($aina_ya_mchango == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","HTafadhali chagua aina ya mchango husika..");
                return back();
            }

            //getting the name of jumuiya
            $jumuiya_jina = Jumuiya::whereId($selected_jumuiya)->value('jina_la_jumuiya');

            if($jumuiya_jina == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa za utoaji uchaguzi wa jumuiya..");
                return back();
            }

            //means we are good to go
            //querying for data
            //================================= COMBINE THE MICHANGO OF BENKI AND TASLIMU TO FORM SINGLE TABLE================================================================================================================================================================
            $michango_taslimu = DB::table('michango_taslimus')->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"), DB::raw("MONTH(michango_taslimus.tarehe) as mwezi"),'michango_taslimus.mwanafamilia','michango_taslimus.aina_ya_mchango','jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
                ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                ->where('familias.jina_la_jumuiya',$jumuiya_jina)
                ->where('aina_ya_mchango',$mchango)
                ->whereMonth('michango_taslimus.tarehe',Carbon::parse($tarehe)->format('m'))
                ->whereYear('michango_taslimus.tarehe',Carbon::parse($tarehe)->format('Y'));

            //dealing with benki
            $michango_benki = DB::table('michango_benkis')->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"), DB::raw("MONTH(michango_benkis.tarehe) as mwezi"),'michango_benkis.mwanafamilia','michango_benkis.aina_ya_mchango','jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
                ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                ->where('familias.jina_la_jumuiya',$jumuiya_jina)
                ->where('aina_ya_mchango',$mchango)
                ->whereMonth('michango_benkis.tarehe',Carbon::parse($tarehe)->format('m'))
                ->whereYear('michango_benkis.tarehe',Carbon::parse($tarehe)->format('Y'))
                ->groupBy('michango_benkis.mwanafamilia');

            $combined = $michango_taslimu->unionAll($michango_benki);

            $data_michango = DB::table(DB::raw("({$combined->toSql()}) AS merged"))
                ->mergeBindings($combined)
                ->select(DB::raw("SUM(merged.kiasi) as kiasi"),'merged.mwanafamilia','merged.aina_ya_mchango','merged.jina_la_jumuiya','merged.mwezi','merged.jina_la_kanda')
                ->whereNotNull('merged.mwanafamilia')
                ->leftJoin('mwanafamilias','mwanafamilias.id','=','merged.mwanafamilia')
                ->groupBy('merged.jina_la_jumuiya')
                ->get();

            //
            if($data_michango->isEmpty()){
                $mwezi = Carbon::parse($tarehe)->format('F-Y');
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa za matoleo ya $mchango kwa mwezi $mwezi");
                return back();
            }
            else{

                //printing now
                    $mwezi = Carbon::parse($tarehe)->format('F-Y');
                    $customPaper = array(0,0,226,360);
                    $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.michango_jumuiya_risiti',compact(['data_michango','mwezi']));
                    $output = $pdf->output();

                    return new Response($output, 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' =>  'inline; filename="michango_jumuiya_risiti.pdf"',
                ]); 
            }
        }
        else{
            //validating if all the required datas have been filled
            $muumini = $request->muumini;
            $mchango = $request->aina_ya_mchango;
            $tarehe = $request->tarehe;

            if($muumini == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchaguzi wa muumini..");
                return back();
            }

            if($tarehe == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchanguzi wa mwezi..");
                return back();
            }

            if($mchango == ""){
                Alert::info("<b class='text-info'>Taarifa!</b>","Hakikisha umefanya uchanguzi wa mchango husika..");
                return back();
            }

            //we are good to go
            //querying for data
            //================================= COMBINE THE MICHANGO OF BENKI AND TASLIMU TO FORM SINGLE TABLE================================================================================================================================================================
            $michango_taslimu = DB::table('michango_taslimus')->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"), DB::raw("MONTH(michango_taslimus.tarehe) as mwezi"),'michango_taslimus.mwanafamilia','michango_taslimus.aina_ya_mchango','jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
                ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                ->where('michango_taslimus.mwanafamilia',$muumini)
                ->where('aina_ya_mchango',$mchango)
                ->whereMonth('michango_taslimus.tarehe',Carbon::parse($tarehe)->format('m'))
                ->whereYear('michango_taslimus.tarehe',Carbon::parse($tarehe)->format('Y'));

            //dealing with benki
            $michango_benki = DB::table('michango_benkis')->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"), DB::raw("MONTH(michango_benkis.tarehe) as mwezi"),'michango_benkis.mwanafamilia','michango_benkis.aina_ya_mchango','jumuiyas.jina_la_jumuiya','jumuiyas.jina_la_kanda')
                ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
                ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                ->where('michango_benkis.mwanafamilia',$muumini)
                ->where('aina_ya_mchango',$mchango)
                ->whereMonth('michango_benkis.tarehe',Carbon::parse($tarehe)->format('m'))
                ->whereYear('michango_benkis.tarehe',Carbon::parse($tarehe)->format('Y'))
                ->groupBy('michango_benkis.mwanafamilia');

            $combined = $michango_taslimu->unionAll($michango_benki);

            $data_michango = DB::table(DB::raw("({$combined->toSql()}) AS merged"))
                ->mergeBindings($combined)
                ->select(DB::raw("SUM(merged.kiasi) as kiasi"),'merged.mwanafamilia','merged.aina_ya_mchango','merged.jina_la_jumuiya','merged.mwezi','merged.jina_la_kanda','mwanafamilias.mawasiliano','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho')
                ->whereNotNull('merged.mwanafamilia')
                ->leftJoin('mwanafamilias','mwanafamilias.id','=','merged.mwanafamilia')
                ->groupBy('merged.mwanafamilia')
                ->get();

                //
                if($data_michango->isEmpty()){
                    $mwezi = Carbon::parse($tarehe)->format('F-Y');
                    Alert::info("Taarifa!","Hakuna taarifa za matoleo ya $mchango kwa mwezi $mwezi");
                    return back();
                }
                else{

                    foreach($data_michango as $data_row){
                        $mwanafamilia_id = $data_row->mwanafamilia;
                        $mwanajumuiya = $data_row->jina_kamili;
                        $aina_ya_mchango = $mchango;
                        $namba_utambulisho = $data_row->namba_utambulisho;
                        $tarehe =  $mwezi = Carbon::parse($tarehe)->format('F-Y');
                        $kiasi = number_format($data_row->kiasi,2);
                        $kanda = $data_row->jina_la_kanda;
                        $jumuiya = $data_row->jina_la_jumuiya;
                    }

                    //printing now
                    $customPaper = array(0,0,226,360);
                    $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.michango_taslimu_risiti',compact(['mwanajumuiya','kiasi','tarehe','aina_ya_mchango','namba_utambulisho','jumuiya','kanda']));
                    $output = $pdf->output();

                    return new Response($output, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' =>  'inline; filename="receipt.pdf"',
                    ]);

                //printing now
                //     $mwezi = Carbon::parse($tarehe)->format('F-Y');
                //     $customPaper = array(0,0,226,360);
                //     $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.michango_mkupuo_risiti',compact(['data_michango','mwezi']));
                //     $output = $pdf->output();

                //     return new Response($output, 200, [
                //     'Content-Type' => 'application/pdf',
                //     'Content-Disposition' =>  'inline; filename="michango_mkupuo_risiti.pdf"',
                // ]);
            }
        }
    }
}
