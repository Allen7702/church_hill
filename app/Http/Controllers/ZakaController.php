<?php

namespace App\Http\Controllers;

use App\Familia;
use App\Receipt;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Zaka;
use App\Mwanafamilia;
use App\Jumuiya;
use App\Kanda;
use Validator;
use Alert;
use App\Imports\ZakaImport;
use App\ZakaKilaMwezi;
use Carbon;
use Auth;
use DB;
use PDF;

class ZakaController extends Controller
{
    //
    public function zaka_takwimu(){

        //setting the beginning of the month in a year and current month

        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa zaka $start_month - $current_month ($current_year)";

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $data_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereYear('tarehe', date('Y'))
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();
            $zaka_total = Zaka::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
        }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);

                    $data_zaka = Zaka::whereIn('jumuiya',$jumuiyas->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
                    ->whereYear('tarehe', date('Y'))
                    ->orderBy('tarehe','asc')
                    ->groupBy(DB::raw("MONTH(tarehe)"))
                    ->get();
                    $zaka_total = Zaka::whereIn('jumuiya',$jumuiyas->pluck('jina_la_jumuiya'))->whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
                
         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya = $jumuiya; 
             $data_zaka = Zaka::whereIn('jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
                    ->whereYear('tarehe', date('Y'))
                    ->orderBy('tarehe','asc')
                    ->groupBy(DB::raw("MONTH(tarehe)"))
                    ->get();
                    $zaka_total = Zaka::whereIn('jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
        
            } 
        }

        


        return view('backend.zaka.zaka_takwimu',compact(['title','data_zaka','zaka_total']));
    }

    //getting data for specific month
    public function zaka_mwezi($id){

        $title = "Zaka za mwezi ";

        $mwezi = $id;
        // $data_zaka = Zaka::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->get();

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $data_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"),'jumuiya')
            ->whereMonth('tarehe',$mwezi)
            ->whereYear('tarehe', date('Y'))
            ->orderBy('kiasi','desc')
            ->groupBy('jumuiya')
            ->get();

        $zaka_mwezi = Zaka::whereMonth('tarehe',$mwezi)->whereYear('tarehe',date('Y'))->sum('kiasi');
        }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);

                $data_zaka = Zaka::whereIn('jumuiya',$jumuiyas->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"),'jumuiya')
                    ->whereMonth('tarehe',$mwezi)
                    ->whereYear('tarehe', date('Y'))
                    ->orderBy('kiasi','desc')
                    ->groupBy('jumuiya')
                    ->get();
        
                $zaka_mwezi = Zaka::whereIn('jumuiya',$jumuiyas->pluck('jina_la_jumuiya'))->whereMonth('tarehe',$mwezi)->whereYear('tarehe',date('Y'))->sum('kiasi');
         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya = $jumuiya; 
             $data_zaka = Zaka::whereIn('jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"),'jumuiya')
             ->whereMonth('tarehe',$mwezi)
             ->whereYear('tarehe', date('Y'))
             ->orderBy('kiasi','desc')
             ->groupBy('jumuiya')
             ->get();
 
         $zaka_mwezi = Zaka::whereIn('jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->whereMonth('tarehe',$mwezi)->whereYear('tarehe',date('Y'))->sum('kiasi');
            } 
        }

        return view('backend.zaka.zaka_mwezi',compact(['data_zaka','zaka_mwezi','title','mwezi']));
    }

    //getting mwanajumuiya
    public function get_mwanajumuiya(Request $request){

        $jumuiya = $request->jumuiya;
        $data = Mwanafamilia::where('familias.jina_la_jumuiya',$jumuiya)->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->get(['mwanafamilias.jina_kamili','mwanafamilias.id']);
        return response()->json($data);
    }

    //function to handle zaka kijumuiya
    public function zaka_index(Request $request){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "utoaji wa zaka jumuiya $start_month - $current_month ($current_year)";
        //data for select 2 (filtering jumuiyas)

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $jumuiya = Kanda::latest('jumuiyas.created_at')->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
            ->get(['jumuiyas.jina_la_jumuiya','kandas.jina_la_kanda','jumuiyas.id']);
    
            //populate data for wanajumuiya
            $data_wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->get(['mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
            //overall year sum of zaka za jumuiya
            $zaka_total = Zaka::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
            $data_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"),'jumuiya')
                ->whereYear('tarehe', date('Y'))
                ->orderBy('kiasi','desc')
                ->groupBy('jumuiya')
                ->get();
    }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $kanda=$kanda->first();
                $familia=Familia::whereIn('jina_la_jumuiya',$jumuiyas->pluck('jina_la_jumuiya'));
               

                $jumuiya = Kanda::where('kandas.id',$kanda->id)->latest('jumuiyas.created_at')->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
                ->get(['jumuiyas.jina_la_jumuiya','kandas.jina_la_kanda','jumuiyas.id']);
        
                //populate data for wanajumuiya
                $data_wanajumuiya = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->get(['mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
                //overall year sum of zaka za jumuiya
                $zaka_total = Zaka::whereIn('jumuiya',$jumuiyas->pluck('jina_la_jumuiya'))->whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
                $data_zaka = Zaka::whereIn('jumuiya',$jumuiyas->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"),'jumuiya')
                    ->whereYear('tarehe', date('Y'))
                    ->orderBy('kiasi','desc')
                    ->groupBy('jumuiya')
                    ->get();

         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya_d = $jumuiya; 
             $kanda=$kanda->first();
             $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya_d->pluck('jina_la_jumuiya'));
            
             $jumuiya = Kanda::latest('jumuiyas.created_at')->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
             ->where('jumuiyas.id',$jumuiya_d->first()->id)
             ->get(['jumuiyas.jina_la_jumuiya','kandas.jina_la_kanda','jumuiyas.id']);
     
             //populate data for wanajumuiya
             $data_wanajumuiya = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->leftJoin('familias','familias.id','=','mwanafamilias.familia')
             ->get(['mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
             //overall year sum of zaka za jumuiya
             $zaka_total = Zaka::whereIn('jumuiya',$jumuiya_d->pluck('jina_la_jumuiya'))->whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
             $data_zaka = Zaka::whereIn('jumuiya',$jumuiya_d->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"),'jumuiya')
                 ->whereYear('tarehe', date('Y'))
                 ->orderBy('kiasi','desc')
                 ->groupBy('jumuiya')
                 ->get();
            } 
        }

        return view('backend.zaka.zaka',compact(['jumuiya','data_zaka','zaka_total','data_wanajumuiya','title']));
    }

    //function to handle the zaka of jumuiya husika (group by muumini)
    public function zaka_jumuiya_husika($id){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $jina_jumuiya = $id;

        //data for select 2 (filtering jumuiyas)
        $jumuiya = Kanda::latest('jumuiyas.created_at')->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->get(['jumuiyas.jina_la_jumuiya','kandas.jina_la_kanda','jumuiyas.id']);

        //populate data for wanajumuiya
        $data_wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->get(['mwanafamilias.jina_kamili','mwanafamilias.id as mwanajumuiya_id','familias.jina_la_jumuiya']);

        $title = "utoaji wa zaka jumuiya ya $jina_jumuiya $start_month - $current_month ($current_year)";

        $data_zaka = Zaka::leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
        ->where('zakas.jumuiya',trim(str_replace("\\t",'',$jina_jumuiya)))
        ->select(DB::raw("SUM(zakas.kiasi) as kiasi"),'mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','mwanafamilias.id as mwanajumuiya_id')
            ->whereBetween('zakas.tarehe', [$begin_with,Carbon::now()])
            ->groupBy('mwanafamilias.namba_utambulisho')
            ->orderBy('kiasi','desc')
            ->get();

           // dd($jina_jumuiya);
          //  dd();
            // return $data_zaka;

        //total for zaka for table footer
        $zaka_total = Zaka::where('jumuiya',$jina_jumuiya)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        return view('backend.zaka.zaka_jumuiya_husika',compact(['jumuiya','data_zaka','zaka_total','data_wanajumuiya','title','jina_jumuiya']));
    }

    //function to handle the collection of zaka in a jumuiya within a month
    public function zaka_jumuiya_husika_mwezi($jumuiya,$mwezi){

        //data for select 2 (filtering jumuiyas)
        $jumuiya_data = Kanda::latest('jumuiyas.created_at')->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->get(['jumuiyas.jina_la_jumuiya','kandas.jina_la_kanda','jumuiyas.id']);

        //populate data for wanajumuiya
        $data_wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->get(['mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        $title = "utoaji wa zaka jumuiya ya $jumuiya";

        $data_zaka = Zaka::leftJoin('mwanafamilias','mwanafamilias.jina_kamili','=','zakas.mwanajumuiya')
        ->where('zakas.jumuiya',$jumuiya)
        ->select(DB::raw("SUM(zakas.kiasi) as kiasi"),'mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','mwanafamilias.id')
            ->whereMonth('zakas.tarehe',$mwezi)
            ->whereYear('tarehe', date('Y'))
            ->orderBy('zakas.kiasi','desc')
            ->groupBy('mwanafamilias.namba_utambulisho')
            ->get();

        //total for zaka for table footer
        $zaka_total = Zaka::where('jumuiya',$jumuiya)->whereMonth('tarehe',$mwezi)->whereYear('tarehe',date('Y'))->sum('kiasi');

        return view('backend.zaka.zaka_jumuiya_mwezi_husika',compact(['data_zaka','jumuiya_data','data_wanajumuiya','zaka_total','title','jumuiya','mwezi']));
    }

    //function to handle zaka jumuiya zote
    public function zaka_jumuiya_zote(Request $request){

        //data for table

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $data_zaka = Zaka::leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
            ->get(['zakas.id','zakas.mwanajumuiya','zakas.kiasi','zakas.jumuiya','mwanafamilias.mawasiliano','mwanafamilias.jina_kamili','mwanafamilias.id as mwanajumuiya_id']);
            //populate data for wanajumuiya
            $data_wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->get(['mwanafamilias.jina_kamili','mwanafamilias.id as mwanajumuiya_id','familias.jina_la_jumuiya']);
            //data for select 2 (filtering jumuiyas)
            $jumuiya = Kanda::latest('jumuiyas.created_at')->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
            ->get(['jumuiyas.jina_la_jumuiya','kandas.jina_la_kanda','jumuiyas.id']);
            //total for zaka for table footer
            $zaka_total = Zaka::sum('kiasi');

    }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $data_zaka = Zaka::whereIn('jumuiya',$jumuiyas->pluck('jina_la_jumuiya'))->leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
                ->get(['zakas.id','zakas.mwanajumuiya','zakas.kiasi','zakas.jumuiya','mwanafamilias.mawasiliano','mwanafamilias.jina_kamili','mwanafamilias.id as mwanajumuiya_id']);
                //populate data for wanajumuiya
                $data_wanajumuiya = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->get(['mwanafamilias.jina_kamili','mwanafamilias.id as mwanajumuiya_id','familias.jina_la_jumuiya']);
                //data for select 2 (filtering jumuiyas)
                $jumuiya = Kanda::where('kandas.id',$kanda->first()->id)->latest('jumuiyas.created_at')->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
                ->get(['jumuiyas.jina_la_jumuiya','kandas.jina_la_kanda','jumuiyas.id']);
                //total for zaka for table footer
                $zaka_total = Zaka::whereIn('jumuiya',$jumuiyas->pluck('jina_la_jumuiya'))->sum('kiasi');

         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya_d = $jumuiya; 
             $kanda=$kanda->first();
             $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya_d->pluck('jina_la_jumuiya'));

                 $data_zaka = Zaka::whereIn('jumuiya',$jumuiya_d->pluck('jina_la_jumuiya'))->leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
                ->get(['zakas.id','zakas.mwanajumuiya','zakas.kiasi','zakas.jumuiya','mwanafamilias.mawasiliano','mwanafamilias.jina_kamili','mwanafamilias.id as mwanajumuiya_id']);
                //populate data for wanajumuiya
                $data_wanajumuiya = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                ->get(['mwanafamilias.jina_kamili','mwanafamilias.id as mwanajumuiya_id','familias.jina_la_jumuiya']);
                //data for select 2 (filtering jumuiyas)
                $jumuiya = Kanda::latest('jumuiyas.created_at')->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
                ->where('jumuiyas.id',$jumuiya_d->first()->id)
                ->get(['jumuiyas.jina_la_jumuiya','kandas.jina_la_kanda','jumuiyas.id']);
                //total for zaka for table footer
                $zaka_total = Zaka::whereIn('jumuiya',$jumuiya_d->pluck('jina_la_jumuiya'))->sum('kiasi');

            } 
        }

 
        return view('backend.zaka.zaka_jumuiya_zote',compact(['jumuiya','data_zaka','zaka_total','data_wanajumuiya']));
    }

    //populate data for zaka edition
    public function zaka_edit($id){
        $data = Zaka::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //function to handle storing of zaka
    public function zaka_store(Request $request){
        //validating data
        $must = [
            'jumuiya' => 'required',
            'mwanajumuiya' => 'required',
            'kiasi' => 'required',
            'tarehe' => 'required',
        ];

        //validating errors
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha jumuiya, mwanajumuiya, kiasi na tarehe vimejazwa."]);
        }
        else{

            //getting the data now
            $data_save = [
                'kiasi' => $request->kiasi,
                'tarehe' => $request->tarehe,
                'mwanajumuiya' => $request->mwanajumuiya,
                'jumuiya' => $request->jumuiya,
                'imewekwa' => auth()->user()->email,
            ];

            //saving data now
             $success = Zaka::create($data_save);
            
            //update column kiasi,status kweny  zakaKilaMwezi model
              $mwezi=Carbon::parse($request->tarehe)->format('m');
              $zaka_za_mwezi_huo=Zaka::where('mwanajumuiya',$request->mwanajumuiya)
              ->whereMonth('tarehe',$mwezi)
              ->sum('kiasi');
              
              $mwanafamilia_zaka=ZakaKilaMwezi::where('mwanafamilia_id',$request->mwanajumuiya)
              ->where('mwezi',$mwezi)
              ->first();
            
              if(!is_null($mwanafamilia_zaka)){

                 $mwanafamilia_zaka->update([
                    'kiasi'=>($zaka_za_mwezi_huo),
                    'status'=>'kalipa'
                 ]);
              }else{
                //do we have to create new field in zaka_kila_mwezi with that month.
                ZakaKilaMwezi::create([
                    'mwanafamilia_id'=>$request->mwanajumuiya,
                     'mwezi'=>$mwezi,
                     'kiasi'=>$zaka_za_mwezi_huo,
                     'status'=>'kalipa'
                 ]);
              }

            if($success){
                $message = "Ongezo";
                $data = "Ongezo la zaka ya $request->mwanajumuiya kiasi $request->kiasi";
                $this->setActivity($message,$data);
                return response()->json(['success'=>["Zaka imewekwa kikamilifu."]]);
            }
        }
    }


    public function zaka_import(Request $request){
        
        $file = $request->file('file')->store('import');

        $import = new ZakaImport;
        $import->import($file);

        $errors = $import->errors();

        if($import->failures()->isNotEmpty()){
            return back()->withFailures($import->failures());
        }

        if(!$errors){
            Alert::info("<b class='text-primary'>Taarifa</b>","Zaka hazijawekwa kwakua kuna tatizo kwenye taarifa ulizojaza..");
            return back();
        }

        return back()->withStatus('Zaka zimeongezwa kikamilifu.',compact(['errors']));
    }

    //function to update the zaka
    public function zaka_update(Request $request){

        $existing = Zaka::findOrFail($request->hidden_id);

        $must = [
            'jumuiya_update' => 'required',
            'mwanajumuiya_update' => 'required',
            'kiasi' => 'required',
            'tarehe' => 'required',
        ];

        //validating errors
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha jumuiya, mwanajumuiya, kiasi na tarehe vimejazwa."]);
        }

        else{
            //getting the data now
            $data_update = [
                'kiasi' => $request->kiasi,
                'tarehe' => $request->tarehe,
                'mwanajumuiya' => $request->mwanajumuiya_update,
                'jumuiya' => $request->jumuiya_update,
                'imewekwa' => auth()->user()->email,
            ];


            

            //updating data now
            $success = Zaka::whereId($request->hidden_id)->update($data_update);

            $mwezi=Carbon::parse($request->tarehe)->format('m');
              $zaka_za_mwezi_huo=Zaka::where('mwanajumuiya',$request->mwanajumuiya_update)
              ->whereMonth('tarehe',$mwezi)
              ->sum('kiasi');
              
              $mwanafamilia_zaka=ZakaKilaMwezi::where('mwanafamilia_id',$request->mwanajumuiya_update)
              ->where('mwezi',$mwezi)
              ->first();
            
              if(!is_null($mwanafamilia_zaka)){
                 $mwanafamilia_zaka->update([
                    'kiasi'=>($zaka_za_mwezi_huo),
                    'status'=>'kalipa'
                 ]);
              }else{
                //do we have to create new field in zaka_kila_mwezi with that month.
                ZakaKilaMwezi::create([
                    'mwanafamilia_id'=>$request->mwanajumuiya_update,
                     'mwezi'=>$mwezi,
                     'kiasi'=>$zaka_za_mwezi_huo,
                     'status'=>'kalipa'
                 ]);
              }


            if($success){
                $message = "Badiliko";
                $data = "Badiliko la zaka ya $request->mwanajumuiya_update kiasi $request->kiasi";
                $this->setActivity($message,$data);
                return response()->json(['success'=>["Zaka imerekebishwa kikamilifu."]]);
            }
        }
    }

    //handling zaka mkupuo index
    public function zaka_mkupuo_index(Request $request){

        $title = "Uwekaji zaka kwa jumuiya";

        $action = "mwanzo";

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
           
        $jumuiya = Kanda::latest('kandas.created_at')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya']);

    }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $kanda=$kanda->first();
                $familia=Familia::whereIn('jina_la_jumuiya',$jumuiyas->pluck('jina_la_jumuiya'));
        $jumuiya = Kanda::where('kandas.id',$kanda->id)->latest('kandas.created_at')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya']);
 

         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya_d = $jumuiya; 
             $kanda=$kanda->first();
             $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya_d->pluck('jina_la_jumuiya'));
             $jumuiya = Kanda::latest('kandas.created_at')
             ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
             ->where('jumuiyas.id',$jumuiya_d->first()->id)
             ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya']);

            } 
        }


        return view('backend.zaka.zaka_mkupuo',compact(['jumuiya','title','action']));
    }

    //function to pull jumuiya although displayed as kanda here
    public function zaka_mkupuo_vuta_kanda(Request $request){

        $selected_jumuiya = $request->jumuiya;

        if($selected_jumuiya ==""){
            Alert::toast("Tafadhali chagua jumuiya husika","info");
            return back();
        }
        //getting details of members from selected jumuiya
        $jumuiya_title = $selected_jumuiya;

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
           
        $jumuiya = Kanda::latest('kandas.created_at')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya']);

    }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiyas = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $kanda=$kanda->first();
                $familia=Familia::whereIn('jina_la_jumuiya',$jumuiyas->pluck('jina_la_jumuiya'));
        $jumuiya = Kanda::where('kandas.id',$kanda->id)->latest('kandas.created_at')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya']);
 

         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya_d = $jumuiya; 
             $kanda=$kanda->first();
             $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya_d->pluck('jina_la_jumuiya'));
             $jumuiya = Kanda::latest('kandas.created_at')
             ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
             ->where('jumuiyas.id',$jumuiya_d->first()->id)
             ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya']);

            } 

        }

        //setting title
        $title = "Uwekaji zaka jumuiya ya $jumuiya_title";

        //list of members
        $members = Mwanafamilia::where('familias.jina_la_jumuiya',$selected_jumuiya)
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->orderBy('mwanafamilias.jina_kamili','asc')
        ->get(['mwanafamilias.jina_kamili','mwanafamilias.id as mwanajumuiya_id']);

        //this handle the populatin of data to the table
        $action = "mkupuo";
        return view('backend.zaka.zaka_mkupuo', compact(['jumuiya','title','members','action','selected_jumuiya']));
    }

    //zaka mkupuo store
    public function zaka_mkupuo_store(Request $request){

        //getting data
        $sum = 0;
        $idadi = 0;
    
        foreach((array) $request->record as $key => $row){
    
            //creating record for saving into zaka table
            if(($row['mwanajumuiya'] && $row['kiasi']) != ""){
                $data_save = [
                    'jumuiya' => $request->jumuiya,
                    'tarehe' => $request->tarehe,
                    'mwanajumuiya' => $row['mwanajumuiya'],
                    'kiasi' => $row['kiasi'],
                    'imewekwa' => auth()->user()->email,
                ];
                Zaka::create($data_save);
                $sum = $sum + $row['kiasi'];
                $idadi = $idadi + 1;
    
    
                $mwezi=Carbon::parse($request->tarehe)->format('m');
                $zaka_za_mwezi_huo=Zaka::where('mwanajumuiya',$row['mwanajumuiya'])
                ->whereMonth('tarehe',$mwezi)
                ->sum('kiasi');
                
                $mwanafamilia_zaka=ZakaKilaMwezi::where('mwanafamilia_id',$row['mwanajumuiya'])
                ->where('mwezi',$mwezi)
                ->first();
              
                if(!is_null($mwanafamilia_zaka)){
                   $mwanafamilia_zaka->update([
                      'kiasi'=>($zaka_za_mwezi_huo),
                      'status'=>'kalipa'
                   ]);
                }else{
                  //do we have to create new field in zaka_kila_mwezi with that month.
                  ZakaKilaMwezi::create([
                      'mwanafamilia_id'=>$row['mwanajumuiya'],
                       'mwezi'=>$mwezi,
                       'kiasi'=>$zaka_za_mwezi_huo,
                       'status'=>'kalipa'
                   ]);
                }
            }
        }
    
        $message = "Ongezo";
        $data = "Ongezo la zaka za wanajumuiya $idadi kiasi $sum";
        $this->setActivity($message,$data);
    
        // Check if the request is an AJAX request
        if($request->ajax()){
            // Return a JSON response
            return response()->json(['success' => 'Zaka zimewekwa kikamilifu'], 200);
        } else {
            Alert::toast("Zaka zimewekwa kikamilifu","success")->autoClose(4200)->timerProgressBar(4200);
            //return response('Hello', 200)->header('Content-Type', 'text/plain');
            //return redirect('zaka');
            //return redirect()->route('zaka');
        }
    }
    

    //function to handle deletion of data
    public function zaka_delete($id){
        //getting data
        $data = Zaka::findOrFail($id);
        $kiasi = $data->kiasi;

        if($data->delete()){
            //tracking`
            $message = "Ufutaji";
            $data = "Ufutaji wa zaka $kiasi";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Zaka imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Zaka haijafutwa jaribu tena.."]]);
        }
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

    //function to handle the receipt for zaka
    public function zaka_risiti($id){

        $data = Zaka::whereId($id)->get();

        if($data->isEmpty()){
            Alert::info("Taarifa!","Hakuna taarifa za zaka kwa uchaguzi uliofanywa..");
            return back();
        }

        else{
            foreach($data as $dat){
            // Create a new receipt.
            $receipt = new Receipt;
            $receipt->save();
            // Use $receipt->id as your receipt identifier.
             $receiptId = $receipt->id;
             $receipt_number = str_pad($receiptId, 4, '0', STR_PAD_LEFT);
                $mwanafamilia_id = $dat->mwanajumuiya;
                $aina_ya_toleo = "Zaka";
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
            $pdf = PDF::setPaper($customPaper,'portrait')->loadView('backend.risiti.zaka_risiti',compact(['mwanajumuiya','kiasi','tarehe','aina_ya_toleo','namba_utambulisho','jumuiya','kanda','receipt_number']));
            $output = $pdf->output();

            return new Response($output, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' =>  'inline; filename="zaka_risiti.pdf"',
            ]);
            // return $pdf->stream("receipt.pdf");
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new Zaka;
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }

    public function zakaKwaMieziJumuiya(string $jumuiya, string $mwaka)
    {


        $data_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTHNAME(tarehe) as mwezi"), DB::raw("YEAR(tarehe) as mwaka"))
                    ->where('jumuiya',$jumuiya)
                    ->where('tarehe','like', $mwaka.'%' )
                    ->orderBy(DB::raw("MONTHNAME(tarehe)"),'asc')
                    ->groupBy(DB::raw("MONTH(tarehe)"))
                    ->get();

        $jumuiya_data = Jumuiya::firstWhere('jina_la_jumuiya', $jumuiya);

        return view('backend.zaka.zaka_kwa_miezi_jumuiya',['zaka_data' => $data_zaka, 'jumuiya' => $jumuiya_data, 'title' => 'Malipo ya zaka kwa jumuiya']);

    }

    public function zakaKwaMieziWanajumuiya(string $jumuiya, string $mwaka)
    {
        $all_month = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $familia = Familia::where('jina_la_jumuiya',$jumuiya)->with('wanafamilias')->get()->each(function ($query) use ($mwaka, $all_month){

           return $query->wanafamilias->each(function ($mwanafamila) use ($mwaka, $all_month){
               $zaka = Zaka::select('kiasi', DB::raw("MONTHNAME(tarehe) as mwezi"),DB::raw("YEAR(tarehe) as mwaka"), 'mwanafamilias.jina_kamili' )
                    ->where('mwanajumuiya',$mwanafamila->id)
                    ->where('tarehe','like', $mwaka.'%' )
                    ->join('mwanafamilias','zakas.mwanajumuiya', '=', 'mwanafamilias.id')
                    ->orderBy(DB::raw("MONTHNAME(tarehe)"),'asc')
//                    ->groupBy(DB::raw("MONTHNAME(tarehe)"))
                    ->get();

                   $missing_month = array_diff($all_month, array_column($zaka->toArray(),'mwezi'));
                    $index = 0;
                    $unavilable_month = [];
                   foreach ($missing_month as $month) {

                       $unavilable_month[$index] = [
                          'kiasi' => 0,
                          'mwezi' => $month,
                          'mwaka' => $mwaka,
                          'jina_kamili' => ''
                      ];


                       $index++;
                   }

                   $sorted_data = [];
                   $merged_data = array_merge($zaka->toArray(), $unavilable_month);

                   foreach($merged_data as $month){

                       if($month['mwezi'] == 'January') {
                           $sorted_data[0] = $month;
                       }

                       if($month['mwezi'] == 'February') {
                           $sorted_data[1] = $month;
                       }

                       if($month['mwezi'] == 'March') {
                           $sorted_data[2] = $month;
                       }

                       if($month['mwezi'] == 'April') {
                           $sorted_data[3] = $month;
                       }

                       if($month['mwezi'] == 'May') {
                           $sorted_data[4] = $month;
                       }

                       if($month['mwezi'] == 'June') {
                           $sorted_data[5] = $month;
                       }

                       if($month['mwezi'] == 'July') {
                           $sorted_data[6] = $month;
                       }

                       if($month['mwezi'] == 'August') {
                           $sorted_data[7] = $month;
                       }

                       if($month['mwezi'] == 'September') {
                           $sorted_data[8] = $month;
                       }

                       if($month['mwezi'] == 'October') {
                           $sorted_data[9] = $month;
                       }

                       if($month['mwezi'] == 'November') {
                           $sorted_data[10] = $month;
                       }

                       if($month['mwezi'] == 'December') {
                           $sorted_data[11] = $month;
                       }

                   }
                $new = collect($sorted_data);

               return  $mwanafamila->zaka = $new;
            });
        });


        $jumuiya_data = Jumuiya::firstWhere('jina_la_jumuiya', $jumuiya);
//        dd(array_column($familia->toArray(), 'wanafamilias'));

        return view('backend.zaka.zaka_kwa_miezi_wanajumuiya',['zaka_data' => array_column($familia->toArray(), 'wanafamilias'),  'jumuiya' => $jumuiya_data, 'mwaka' => $mwaka, 'title' => 'Malipo ya zaka kwa jumuiya']);
    }
}
