<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\MatumiziTaslimu;
use App\AinaMatumizi;
use App\AnkraMadeni;
use Auth;
use Carbon;

class MatumiziTaslimuController extends Controller
{
    //getting the index of matumizi taslimus
    public function matumizi_taslimu_index(Request $request){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "Matumizi pesa taslimu $start_month - $current_month ($current_year)";
        $total_matumizi_taslimu = MatumiziTaslimu::whereBetween('tarehe',[$begin_with, Carbon::now()->format('Y-m-d')])->sum('kiasi');
        $data_taslimu = MatumiziTaslimu::latest()->whereBetween('tarehe',[$begin_with, Carbon::now()->format('Y-m-d')])->get();

        $ankra_madeni = AnkraMadeni::where('ankra_madenis.status','haijalipwa')
        ->whereBetween('ankra_madenis.tarehe',[$begin_with, Carbon::now()->format('Y-m-d')])
        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
        ->where('ankra_madenis.deni','>',0)
        ->get(['ankra_madenis.id as madeni_id','ankra_madenis.deni','ankra_madenis.namba_ya_ankra','watoa_hudumas.jina_kamili','watoa_hudumas.aina_ya_huduma']);
        $aina_matumizi = AinaMatumizi::latest()->get();
        return view('backend.matumizi.matumizi_taslimu',compact(['ankra_madeni','aina_matumizi','data_taslimu','title','total_matumizi_taslimu']));
    }

    //handling the mengineyo and ankra storing
    public function matumizi_taslimu_store(Request $request){

        //getting the aina first
        $aina = $request->aina;

        if($aina === "Ankra"){
            //validating data
            $must = [
                'tarehe' => 'required',
                'kiasi' => 'required',
                'ankra' => 'required',
            ];

            $error = Validator::make($request->all(),$must);

            if($error->fails()){
                return response()->json(['errors'=>["Hakikisha umejaza tarehe, kiasi na mtoa huduma.."]]);
            }
        }

        else{
            //validating data
            $must = [
                'tarehe' => 'required',
                'kiasi' => 'required',
                'aina_matumizi' => 'required',
                'kundi' => 'required',
            ];

            $error = Validator::make($request->all(),$must);

            if($error->fails()){
                return response()->json(['errors'=>["Hakikisha umejaza tarehe, aina ya matumizi, kundi na kiasi.."]]);
            }
        }

        //checking the debt it should not exceed
        $amount = $request->kiasi;

        //saving data now
        if($aina === "Ankra"){

            $data_deni = AnkraMadeni::findOrFail($request->ankra);

            if($data_deni == ""){
                return response()->json(['errors'=>["Hakuna taarifa kwa mtoa huduma aliyechaguliwa.."]]);
            }

            if($amount > $data_deni->deni){
                $gap = number_format($amount - $data_deni->deni,2);
                $deni = number_format($data_deni->deni,2);
                return response()->json(['errors'=>["Umezidisha kiasi cha shilingi $gap, deni halisi ni $deni"]]);
            }

            else{
                $data_save = [ 
                    'namba_ya_ankra' => $data_deni->namba_ya_ankra,
                    'kiasi' => $amount,
                    'imewekwa_na' => auth()->user()->email,
                    'tarehe' => $request->tarehe,
                    'aina_ya_ulipaji' => $aina,
                    'aina_ya_matumizi' => NULL,
                    'maelezo' => $request->maelezo,
                    'kundi' => $request->kundi,
                ];

                $success = MatumiziTaslimu::create($data_save);

                if($success){
                    //updating the debt in ankra madeni
                    AnkraMadeni::where('namba_ya_ankra',$data_deni->namba_ya_ankra)->decrement('deni',$amount);

                    AnkraMadeni::where('namba_ya_ankra',$data_deni->namba_ya_ankra)->where('deni','<=',0)->update(['status'=>'imelipwa']);

                    //tracking
                    $message = "Ongezo";
                    $data = "Ongezo la matumizi ankra namba $data_deni->namba_ya_ankra kiasi $amount";
                    $this->setActivity($message,$data);

                    return response()->json(['success'=>["Matumizi yamewekwa kikamilifu.."]]);
                }
                else{
                    return response()->json(['errors'=>["Imeshindikana kuweka matumizi jaribu tena baadae.."]]);
                }
            }
        }

        else{
            $data_save = [ 
                'namba_ya_ankra' => NULL,
                'kiasi' => $amount,
                'imewekwa_na' => auth()->user()->email,
                'tarehe' => $request->tarehe,
                'aina_ya_ulipaji' => $aina,
                'aina_ya_matumizi' => $request->aina_matumizi,
                'maelezo' => $request->maelezo,
                'kundi' => $request->kundi,
            ];

            $success = MatumiziTaslimu::create($data_save);

            if($success){
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la matumizi aina ya $request->aina_matumizi kiasi $amount";
                $this->setActivity($message,$data);
                return response()->json(['success'=>["Matumizi yamewekwa kikamilifu.."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuweka matumizi jaribu tena baadae.."]]);
            }
        }
    }

    //function to edit the matumizi taslimu
    public function edit($id){
        $data = MatumiziTaslimu::findOrFail($id);

        if($data->aina_ya_ulipaji === "Mengineyo"){
            return response()->json(['result'=>$data]);
        }
        else{
            
            $data = MatumiziTaslimu::leftJoin('ankra_madenis','ankra_madenis.namba_ya_ankra','=','matumizi_taslimus.namba_ya_ankra')
            ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
            ->select(['matumizi_taslimus.*','ankra_madenis.id as madeni_id','ankra_madenis.deni','watoa_hudumas.jina_kamili','watoa_hudumas.aina_ya_huduma'])
            ->findOrFail($id);
            return response()->json(['result'=>$data]);
        }
    }

    //function to update the matumizi taslimu
    public function matumizi_taslimu_update(Request $request){
        //getting the existing data
        $existing = MatumiziTaslimu::findOrFail($request->hidden_id);

        //getting aina ya ulipaji
        $aina = $request->aina;
        
        //validating
        if($aina === "Ankra"){
            //validating data
            $must = [
                'tarehe' => 'required',
                'kiasi' => 'required',
                'ankra_update' => 'required',
                'kundi' => 'required',
            ];

            $error = Validator::make($request->all(),$must);

            if($error->fails()){
                return response()->json(['errors'=>["Hakikisha umejaza tarehe, kiasi na mtoa huduma.."]]);
            }

            //we are clear with validation
            $new_amount = $request->kiasi;
            $old_amount = $existing->kiasi;

            //getting the deni from ankra
            $data_deni = AnkraMadeni::where('namba_ya_ankra',$existing->namba_ya_ankra)->get();

            if($data_deni->isEmpty()){
                return response()->json(['errors'=>["Hakuna taarifa za madeni kwa mtoa huduma husika.."]]);
            }
            else{
                foreach($data_deni as $madeni){
                    $deni = $madeni->deni;
                    $namba_ya_ankra = $madeni->namba_ya_ankra;
                }

                //comparing 
                if($new_amount > ($deni + $old_amount)){
                    $difference = $new_amount - ($deni + $old_amount);

                    return $difference;
                }
                else{
                    if($new_amount > $old_amount){
                        $difference = $new_amount - $old_amount;
                    }
                    else{
                        $difference = $old_amount - $new_amount;
                    }
                }
                
                //saving data
                $data_update = [
                    'namba_ya_ankra' => $namba_ya_ankra,
                    'kiasi' => $new_amount,
                    'imewekwa_na' => auth()->user()->email,
                    'tarehe' => $request->tarehe,
                    'aina_ya_ulipaji' => $aina,
                    'aina_ya_matumizi' => NULL,
                    'maelezo' => $request->maelezo,
                    'kundi' => $request->kundi,
                ];

                $success = MatumiziTaslimu::whereId($request->hidden_id)->update($data_update);

                if($success){
                    
                    if($new_amount > $old_amount){
                        $update = AnkraMadeni::where('namba_ya_ankra',$namba_ya_ankra)->decrement('deni',$difference);

                        if($update){
                            //tracking
                            $message = "Badiliko";
                            $data = "Badiliko la matumizi ankra namba $namba_ya_ankra kiasi $request->kiasi";
                            $this->setActivity($message,$data);
                            return response()->json(['success'=>["Marekebisho yamefanyika kikamilifu.."]]);
                        }
                        else{
                            $message = "Badiliko";
                            $data = "Badiliko la matumizi ankra namba $namba_ya_ankra kiasi $request->kiasi";
                            $this->setActivity($message,$data);
                            return response()->json(['success'=>"Malipo yamerekebishwa ila deni halijarekebishwa."]);
                        }
                    }

                    else if($old_amount > $new_amount){
                        $update = AnkraMadeni::where('namba_ya_ankra',$namba_ya_ankra)->increment('deni',$difference);

                        if($update){
                            $message = "Badiliko";
                            $data = "Badiliko la matumizi ankra namba $namba_ya_ankra kiasi $request->kiasi";
                            $this->setActivity($message,$data);
                            return response()->json(['success'=>["Marekebisho yamefanyika kikamilifu.."]]);
                        }
                        else{
                            $message = "Badiliko";
                            $data = "Badiliko la matumizi ankra namba $namba_ya_ankra kiasi $request->kiasi";
                            $this->setActivity($message,$data);
                            return response()->json(['success'=>"Malipo yamerekebishwa ila deni halijarekebishwa."]);
                        }
                    }

                    else{
                        $message = "Badiliko";
                        $data = "Badiliko la matumizi ankra namba $namba_ya_ankra";
                        $this->setActivity($message,$data);
                        return response()->json(['success'=>["Marekebisho yamefanyika kikamilifu.."]]);
                    }
                }
            }
        }

        else{
            //validating data
            $must = [
                'tarehe' => 'required',
                'kiasi' => 'required',
                'aina_matumizi' => 'required',
                'kundi' => 'required',
            ];

            $error = Validator::make($request->all(),$must);

            if($error->fails()){
                return response()->json(['errors'=>["Hakikisha umejaza tarehe, aina ya matumizi na kiasi.."]]);
            }

            else{
                //updating data
                $data_update = [ 
                    'namba_ya_ankra' => NULL,
                    'kiasi' => $request->kiasi,
                    'imewekwa_na' => auth()->user()->email,
                    'tarehe' => $request->tarehe,
                    'aina_ya_ulipaji' => $aina,
                    'aina_ya_matumizi' => $request->aina_matumizi,
                    'maelezo' => $request->maelezo,
                    'kundi' => $request->kundi,
                ];
    
                $success = MatumiziTaslimu::whereId($request->hidden_id)->update($data_update);
    
                if($success){
                    //tracking
                    $message = "Badiliko";
                    $data = "Badiliko la matumizi aina ya $request->aina_matumizi kiasi $request->kiasi";
                    $this->setActivity($message,$data);
                    return response()->json(['success'=>["Matumizi yamerekebishwa kikamilifu.."]]);
                }
                else{
                    return response()->json(['errors'=>["Imeshindikana kubadili matumizi jaribu tena baadae.."]]);
                }
            }
        }
    }

    //getting the deletion of matumizi taslimu
    public function matumizi_taslimu_destroy($id){
        //getting data to know the specific aina so as to rollback the paid amount
        $data_deletion = MatumiziTaslimu::findOrFail($id);

        $aina = $data_deletion->aina_ya_ulipaji;
        $kiasi = $data_deletion->kiasi;

        if($aina === "Ankra"){
            //getting the namba and amount
            $namba_ya_ankra = $data_deletion->namba_ya_ankra;
            
            //rolling back
            $process = AnkraMadeni::where('namba_ya_ankra',$namba_ya_ankra)->increment('deni',$kiasi);

            if($process){
                if($data_deletion->delete()){
                    //tracking
                    $message = "Ufutaji";
                    $data = "Ufutaji wa matumizi ankra namba $namba_ya_ankra kiasi $kiasi";
                    $this->setActivity($message,$data);
                    return response()->json(['success'=>["Kiasi cha TZS.$kiasi kimerejeshwa kikamilifu"]]);
                }

                else{
                    return response()->json(['success'=>["Kiasi kimerudishwa kikamilifu ila taarifa haijafutwa kwenye mfumo"]]);
                }
            }
        }
        else{
            //deleting data
            if($data_deletion->delete()){
                //tracking
                $message = "Ufutaji";
                $data = "Ufutaji wa matumizi kiasi $kiasi";
                $this->setActivity($message,$data);
                return response()->json(['success'=>["Taarifa zimefutwa kikamilifu.."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kufuta taarifa.."]]);
            }
        }
    }

    //function to handle the matumizi of taslimu in mwezi
    public function matumizi_taslimu_mwezi($id){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = " Matumizi pesa taslimu mwezi $id/$current_year ";
        $total_matumizi_taslimu = MatumiziTaslimu::whereMonth('tarehe',$id)->whereYear('tarehe',$current_year)->sum('kiasi');

        $data_taslimu = MatumiziTaslimu::latest()->whereMonth('tarehe',$id)->whereYear('tarehe',$current_year)->get();

        $ankra_madeni = AnkraMadeni::where('ankra_madenis.status','haijalipwa')
        ->whereBetween('ankra_madenis.tarehe',[$begin_with, Carbon::now()->format('Y-m-d')])
        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')
        ->get(['ankra_madenis.id as madeni_id','ankra_madenis.deni','ankra_madenis.namba_ya_ankra','watoa_hudumas.jina_kamili','watoa_hudumas.aina_ya_huduma']);
        $aina_matumizi = AinaMatumizi::latest()->get();
        return view('backend.matumizi.matumizi_taslimu',compact(['ankra_madeni','aina_matumizi','data_taslimu','title','total_matumizi_taslimu']));   
    }

    //function to handle the matumizi ya taslimu zaidi
    public function matumizi_taslimu_zaidi(){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "Matumizi pesa taslimu $start_month - $current_month ($current_year)";

        //summing the monthly interval
        $matumizi_taslimu_total = MatumiziTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        //data to be displayed
        $matumizi_taslimu_data = MatumiziTaslimu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get(); 
            
        return view('backend.matumizi.matumizi_taslimu_zaidi',compact(['title','matumizi_taslimu_data','matumizi_taslimu_total']));   
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
        $model = new MatumiziTaslimu; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
