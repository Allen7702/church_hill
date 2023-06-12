<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\SadakaJumuiya;
use App\Jumuiya;
use Carbon;
use DB;
use Alert;

class SadakaJumuiyaController extends Controller
{
    //
    public function sadaka_jumuiya_index(){
 
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka za jumuiya $start_month - $current_month ($current_year)";


        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $jumuiyas = Jumuiya::latest()->get();

            $data_sadaka_total = SadakaJumuiya::whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
    
            $data_sadaka = SadakaJumuiya::whereBetween('tarehe',[$begin_with,Carbon::now()])->select(DB::raw("SUM(kiasi) as kiasi"),'jina_la_jumuiya')
                ->orderBy('kiasi','DESC')
                ->whereYear('tarehe', date('Y'))
                ->groupBy('jina_la_jumuiya')
                ->get();
        }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiya_d = \App\Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $jumuiyas=$jumuiya_d->get();
                $data_sadaka_total = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya_d->pluck('jina_la_jumuiya'))->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
    
                $data_sadaka = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya_d->pluck('jina_la_jumuiya'))->whereBetween('tarehe',[$begin_with,Carbon::now()])->select(DB::raw("SUM(kiasi) as kiasi"),'jina_la_jumuiya')
                    ->orderBy('kiasi','DESC')
                    ->whereYear('tarehe', date('Y'))
                    ->groupBy('jina_la_jumuiya')
                    ->get();
                
         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya = $jumuiya; 
             $jumuiyas=$jumuiya->get();
             $data_sadaka_total = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
    
             $data_sadaka = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->whereBetween('tarehe',[$begin_with,Carbon::now()])->select(DB::raw("SUM(kiasi) as kiasi"),'jina_la_jumuiya')
                 ->orderBy('kiasi','DESC')
                 ->whereYear('tarehe', date('Y'))
                 ->groupBy('jina_la_jumuiya')
                 ->get();
        
            } 
        }

        
        return view('backend.sadaka.sadaka_jumuiya', compact(['jumuiyas','data_sadaka','title','data_sadaka_total']));
    }

    public function sadaka_jumuiya_husika($jumuiya){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "utoaji wa sadaka jumuiya ya $jumuiya $start_month - $current_month ($current_year)";

        $data_sadaka_total = SadakaJumuiya::where('jina_la_jumuiya',$jumuiya)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $jumuiyas = Jumuiya::latest()->get();
        $data_sadaka = SadakaJumuiya::where('jina_la_jumuiya',$jumuiya)->latest()->whereBetween('tarehe',[$begin_with,Carbon::now()])->orderBy('jina_la_jumuiya')->get();
        return view('backend.sadaka.sadaka_jumuiya_husika', compact(['jumuiyas','data_sadaka','title','data_sadaka_total','jumuiya']));   
    }

    public function sadaka_zote(){
        $jumuiyas = Jumuiya::latest()->get();
        $data_sadaka = SadakaJumuiya::latest()->orderBy('jina_la_jumuiya')->get();
        return view('backend.sadaka.sadaka_jumuiya', compact(['jumuiyas','data_sadaka']));   
    }

    public function sadaka_jumuiya_store(Request $request){
        
        //validating data
        $must = [
            'kiasi' => 'required',
            'tarehe' => 'required',
            'jina_la_jumuiya' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza kiasi, tarehe na jumuiya husika."]);
        }
        else{
            //getting data
            $data_save = [
                'jina_la_jumuiya' => $request->jina_la_jumuiya,
                'tarehe' => $request->tarehe,
                'kiasi' => $request->kiasi,
                'maelezo' => $request->maelezo,
                'imewekwa_na' => auth()->user()->email,
            ];

            $success = SadakaJumuiya::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la sadaka jumuiya $request->jina_la_jumuiya";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Sadaka imeongezwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuongeza sadaka"]]);
            }
        }
    }

    //function to get the specific id to display in jumuiya sadaka
    public function sadaka_jumuiya_edit($id){
        $data = SadakaJumuiya::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //function to handle the update the of sadaka jumuiya
    public function sadaka_jumuiya_update(Request $request){
        //validating data
        $must = [
            'kiasi' => 'required',
            'tarehe' => 'required',
            'jina_la_jumuiya_update' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza kiasi, tarehe na jumuiya husika."]);
        }
        else{
            //getting data
            $data_update = [
                'jina_la_jumuiya' => $request->jina_la_jumuiya_update,
                'tarehe' => $request->tarehe,
                'kiasi' => $request->kiasi,
                'maelezo' => $request->maelezo,
                'imewekwa_na' => auth()->user()->email,
            ];

            $success = SadakaJumuiya::whereId($request->hidden_id)->update($data_update);

            if($success){
                
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la sadaka jumuiya $request->jina_la_jumuiya";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Sadaka imebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili sadaka"]]);
            }
        }
    }

    //function to handle the sadaka jumuiya zaidi
    public function sadaka_jumuiya_zaidi(){
        
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka za jumuiya $start_month - $current_month ($current_year)";

        //overall year sum of sadaka za jumuiya


        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $sadaka_jumuiya = SadakaJumuiya::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
            $data_sadaka_jumuiya = SadakaJumuiya::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereYear('tarehe', date('Y'))
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();
        }else{

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiya =\App\Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $sadaka_jumuiya = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
                $data_sadaka_jumuiya = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
                ->whereYear('tarehe', date('Y'))
                ->orderBy('tarehe','asc')
                ->groupBy(DB::raw("MONTH(tarehe)"))
                ->get();
                
            $data_sadaka_jumuiya = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
                ->whereYear('tarehe', date('Y'))
                ->orderBy('tarehe','asc')
                ->groupBy(DB::raw("MONTH(tarehe)"))
                ->get();
         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya = $jumuiya; 
         
             $sadaka_jumuiya = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
             $data_sadaka_jumuiya = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
             ->whereYear('tarehe', date('Y'))
             ->orderBy('tarehe','asc')
             ->groupBy(DB::raw("MONTH(tarehe)"))
             ->get();
        
            } 
        }
        
        return view('backend.sadaka.sadaka_jumuiya_zaidi',compact(['title','data_sadaka_jumuiya','sadaka_jumuiya']));
    }

    //getting data for sadaka jumuiya specific month
    public function sadaka_jumuiya_mwezi($id){

        $title = "Sadaka za jumuiya mwezi";

        $mwezi = $id;

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $data_sadaka_jumuiya = SadakaJumuiya::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"),'jina_la_jumuiya')
            ->whereMonth('tarehe',$id)
            ->whereYear('tarehe', date('Y'))
            ->orderBy('tarehe','asc')
            ->groupBy('jina_la_jumuiya')
            ->get();
    
            $sadaka_jumuiya_total = SadakaJumuiya::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->sum('kiasi');
        }else{
            
            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            if($user_ngazi=='Kanda'){
                $jumuiya = \App\Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $data_sadaka_jumuiya = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"),'jina_la_jumuiya')
                ->whereMonth('tarehe',$id)
                ->whereYear('tarehe', date('Y'))
                ->orderBy('tarehe','asc')
                ->groupBy('jina_la_jumuiya')
                ->get();
                $sadaka_jumuiya_total = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->sum('kiasi'); 
     
         }elseif($user_ngazi=='Jumuiya'){
             $jumuiya = $jumuiya; 
             $data_sadaka_jumuiya = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"),'jina_la_jumuiya')
             ->whereMonth('tarehe',$id)
             ->whereYear('tarehe', date('Y'))
             ->orderBy('tarehe','asc')
             ->groupBy('jina_la_jumuiya')
             ->get();
             $sadaka_jumuiya_total = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->sum('kiasi'); 
            } 
        }
        
        return view('backend.sadaka.sadaka_jumuiya_mwezi',compact(['sadaka_jumuiya_total','data_sadaka_jumuiya','title','mwezi']));
    
    }

    //getting data for sadaka jumuiya specific jumuiya and month
    public function sadaka_jumuiya_husika_mwezi($id, $jumuiya){
        $mwezi = $id."/".Carbon::now()->format('Y');

        $title = "Sadaka za jumuiya ya $jumuiya mwezi $mwezi";

        $data = Jumuiya::where('jina_la_jumuiya',$jumuiya)->get();

        if($data->isEmpty()){
            Alert::toast("Hakuna taarifa kwa jumuiya iliyochaguliwa","info");
            return back();
        }

        $data_sadaka_jumuiya = SadakaJumuiya::where('jina_la_jumuiya',$jumuiya)->whereMonth('tarehe',$id)->whereYear('tarehe',Carbon::now()->format('Y'))->get();
        
        $data_sadaka_total = SadakaJumuiya::where('jina_la_jumuiya',$jumuiya)->whereMonth('tarehe',$id)->whereYear('tarehe',Carbon::now()->format('Y'))->sum('kiasi');

        return view('backend.sadaka.sadaka_jumuiya_husika_mwezi',compact(['data_sadaka_jumuiya','data_sadaka_total','title']));
    }

    //function to handle destroy of sadaka jumuiya
    public function sadaka_jumuiya_destroy($id){
        //getting data
        $data = SadakaJumuiya::findOrFail($id);
        $jumuiya = $data->jina_la_jumuiya;
        $kiasi = $data->kiasi;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa sadaka jumuiya $jumuiya, kiasi $kiasi";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Sadaka imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Sadaka haijafutwa jaribu tena.."]]);
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

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new SadakaJumuiya; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }

}
