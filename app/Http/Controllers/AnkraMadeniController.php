<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AnkraMadeni;
use App\WatoaHuduma;
use Validator;
use Auth;

class AnkraMadeniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_watoahuduma = WatoaHuduma::latest()->get();
        $data_ankra = AnkraMadeni::latest('ankra_madenis.created_at')
        ->leftJoin('watoa_hudumas','watoa_hudumas.id','=','ankra_madenis.mtoa_huduma')->get(['ankra_madenis.*','watoa_hudumas.aina_ya_huduma','watoa_hudumas.jina_kamili']);

        $madeni_total = AnkraMadeni::sum('kiasi');
        $salio = AnkraMadeni::sum('deni');
        return view('backend.mengineyo.ankra_madeni',compact(['data_watoahuduma','data_ankra','madeni_total','salio']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $must = [
            'namba_ya_ankra' => 'required|unique:ankra_madenis',
            'tarehe' => 'required',
            'kiasi' => 'required',
            'mtoa_huduma' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza tarehe, kiasi, mtoa huduma, namba ya ankra iwe ya kipekee."]);
        }
        else{
            //getting data
            $data_save = [
                'mtoa_huduma' => $request->mtoa_huduma,
                'tarehe' => $request->tarehe,
                'kiasi' => $request->kiasi,
                'deni' => $request->kiasi,
                'maelezo' => $request->maelezo,
                'namba_ya_ankra' => $request->namba_ya_ankra,
            ];

            $success = AnkraMadeni::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la ankra ya madeni $request->namba_ya_ankra";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Ankra imeongezwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuongeza ankra.."]]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = AnkraMadeni::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AnkraMadeni $model)
    {
        //
        $existing = AnkraMadeni::findOrFail($request->hidden_id);

        //taking the previously issued amounts
        $old_debt = $existing->deni;
        $old_amount = $existing->kiasi;

        $must = [
            'namba_ya_ankra' => 'sometimes|unique:ankra_madenis,namba_ya_ankra,'.$existing->id,
            'tarehe' => 'required',
            'kiasi' => 'required',
            'mtoa_huduma_update' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza tarehe, kiasi, mtoa huduma, namba ya ankra iwe ya kipekee."]);
        }
        else{

            //updating new debt
            if($request->kiasi > $old_amount){
                $difference = $request->kiasi - $old_amount;
                $new_debt = $old_debt + $difference;
            }
            elseif($request->kiasi < $old_amount){
                $difference = $old_amount - $request->kiasi;
                $new_debt = $old_debt - $difference;
            }
            else{
                $new_debt = $old_debt;
            }

            //getting data
            $data_update = [
                'mtoa_huduma' => $request->mtoa_huduma_update,
                'tarehe' => $request->tarehe,
                'kiasi' => $request->kiasi,
                'deni' => $new_debt,
                'maelezo' => $request->maelezo,
                'namba_ya_ankra' => $request->namba_ya_ankra,
            ];

            $success = AnkraMadeni::whereId($request->hidden_id)->update($data_update);

            if($success){       
                //tracking
                $message = "Badiliko";
                $data = "Ongezo la ankra ya madeni $request->namba_ya_ankra";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Ankra imebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili ankra.."]]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //deleting data
        $data = AnkraMadeni::findOrFail($id);
        $ankra = $data->namba_ya_ankra;

        //deleting
        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa ankra namba $ankra";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Ankra imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['errors'=>["Tafadhali jaribu tena kufuta ankra."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new AnkraMadeni; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
