<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SadakaKuu;
use App\SadakaJumuiya;
use App\AinaZaMisa;
use Validator;
use Carbon;
use Auth;
use DB;

class SadakaKuuController extends Controller
{
    //
    public function sadaka_kuu_takwimu(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka $start_month - $current_month ($current_year)";

        //overall year sum of sadaka kuu

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $sadaka_kuu = SadakaKuu::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
            $sadaka_jumuiya = SadakaJumuiya::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
            $data_sadaka = SadakaKuu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereYear('tarehe', date('Y'))
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();
            
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
                $jumuiya = \App\Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
                $sadaka_kuu=0;
                $sadaka_jumuiya = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
                $data_sadaka = SadakaKuu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
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
             $sadaka_kuu=0;
             $sadaka_jumuiya = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
             $data_sadaka = SadakaKuu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
             ->whereYear('tarehe', date('Y'))
             ->orderBy('tarehe','asc')
             ->groupBy(DB::raw("MONTH(tarehe)"))
             ->get();
             
           $data_sadaka_jumuiya = SadakaJumuiya::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'))->select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
             ->whereYear('tarehe', date('Y'))
             ->orderBy('tarehe','asc')
             ->groupBy(DB::raw("MONTH(tarehe)"))
             ->get();
        
            } 

        }
        //overall total of sadaka kuu and jumuiya
        $sadaka_total = $sadaka_kuu + $sadaka_jumuiya;

        
        return view('backend.sadaka.sadaka_kuu_takwimu', compact(['title','data_sadaka','sadaka_kuu','sadaka_total','sadaka_jumuiya','data_sadaka_jumuiya']));
    }

    //getting more details about sadaka kuu zaidi
    public function sadaka_kuu_zaidi(){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka $start_month - $current_month ($current_year)";

        //overall year sum of sadaka
        $sadaka_kuu = SadakaKuu::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $data_sadaka = SadakaKuu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereYear('tarehe', date('Y'))
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();

        return view('backend.sadaka.sadaka_kuu_zaidi',compact(['title','data_sadaka','sadaka_kuu']));
    }

    //getting data for specific month
    public function sadaka_kuu_mwezi($id){
        $mwezi = $id;
        $title = "Sadaka za mwezi";
        $aina_za_misa = AinaZaMisa::latest()->get();
        $data_sadaka = SadakaKuu::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->get();
        $sadaka_mwezi = SadakaKuu::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->sum('kiasi');
        
        return view('backend.sadaka.sadaka_kuu_mwezi',compact(['data_sadaka','sadaka_mwezi','aina_za_misa','title','mwezi']));
    
    }

    //weka sadaka kanisa
    public function sadaka_kuu_index(Request $request){
        $aina_za_misa = AinaZaMisa::latest()->get();
    
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka $start_month - $current_month ($current_year)";

        $data_sadaka = SadakaKuu::whereBetween('tarehe',[$begin_with,Carbon::now()])->select(DB::raw("SUM(kiasi) as kiasi"),'misa')
            ->orderBy('kiasi','DESC')
            ->whereYear('tarehe', date('Y'))
            ->groupBy('misa')
            ->get();
        
        //overall year sum of sadaka
        $sadaka_total = SadakaKuu::whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
        return view('backend.sadaka.sadaka_kuu',compact(['aina_za_misa','data_sadaka','sadaka_total','title']));
    }

    public function sadaka_kuu_misa_husika($misa){
        $aina_za_misa = AinaZaMisa::latest()->get();
    
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka $misa $start_month - $current_month ($current_year)";

        $data_sadaka = SadakaKuu::where('misa',$misa)->whereBetween('tarehe',[$begin_with,Carbon::now()])->get();
            
        //overall year sum of sadaka
        $sadaka_total = SadakaKuu::where('misa',$misa)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
        return view('backend.sadaka.sadaka_kuu_misa_husika',compact(['aina_za_misa','data_sadaka','sadaka_total','title','misa']));
    }

    //controller to handle collection of sadaka
    public function sadaka_kuu_store(Request $request){
        //getting required fields
        $must = [
            'misa' => 'required',
            'kiasi' => 'required',
            'tarehe' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza tarehe, misa na kiasi."]);
        }
        else{
            //getting data
            $data_save = [
                'misa' => $request->misa,
                'tarehe' => $request->tarehe,
                'kiasi' => $request->kiasi,
                'imewekwa' => auth()->user()->email,
            ];

            $success = SadakaKuu::create($data_save);

            if($success){   
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la sadaka $request->kiasi";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Sadaka imewekwa kikamilifu.."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuweka sadaka jaribu tena.."]]);
            }    
        }
    }

    //sadaka kuu update
    public function sadaka_kuu_update(Request $request){
        
        //getting required fields
        $must = [
            'misa' => 'required',
            'kiasi' => 'required',
            'tarehe' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza tarehe, misa na kiasi."]);
        }
        else{
            //getting data
            $data_update = [
                'misa' => $request->misa,
                'tarehe' => $request->tarehe,
                'kiasi' => $request->kiasi,
                'imewekwa' => auth()->user()->email,
            ];

            $success = SadakaKuu::whereId($request->hidden_id)->update($data_update);

            if($success){   
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la sadaka $request->kiasi";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Sadaka imebadilishwa kikamilifu.."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili sadaka jaribu tena.."]]);
            }    
        }
    }

    //getting the id to edit sadaka kuu
    public function sadaka_kuu_edit($id){
        $data = SadakaKuu::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //public function delete sadaka kuu
    public function sadaka_kuu_delete($id){
        //getting data
        $data = SadakaKuu::findOrFail($id);
        $kiasi = $data->kiasi;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa sadaka $kiasi";
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
        $model = new SadakaKuu; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
