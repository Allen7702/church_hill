<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Denomination;
use Auth;
use Alert;
use Validator;

class DenominationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

  
        //if the noti or sarafu is empty assign zero
        $noti_10000 = $request->noti_10000;
        $noti_5000 = $request->noti_5000;
        $noti_2000 = $request->noti_2000;
        $noti_1000 = $request->noti_1000;
        $noti_500 = $request->noti_500;
        $sarafu_500 = $request->sarafu_500;
        $sarafu_200 = $request->sarafu_200;
        $sarafu_100 = $request->sarafu_100;
        $sarafu_50 = $request->sarafu_50;

        //validate 10000
        if($noti_10000 == ""){
            $noti_10000 = 0;
        }

        //validate 5000
        if($noti_5000 == ""){
            $noti_5000 = 0;
        }

        //validate 2000
        if($noti_2000 == ""){
            $noti_2000 = 0;
        }

        //validate 1000
        if($noti_1000 == ""){
            $noti_1000 = 0;
        }

        //validate 500
        if($noti_500 == ""){
            $noti_500 = 0;
        }

        //validate sarafu 500
        if($sarafu_500 == ""){
            $sarafu_500 = 0;
        }

        //validate sarafu 200
        if($sarafu_200 == ""){
            $sarafu_200 = 0;
        }

        //validate sarafu 100
        if($sarafu_100 == ""){
            $sarafu_100 = 0;
        }

        //validate sarafu 50
        if($sarafu_50 == ""){
            $sarafu_50 = 0;
        }

        $aina_ya_toleo = $request->hidden_aina;

        $tarehe = $request->hidden_date;

        //getting data
        $data_save = [
            'noti_10000' => $noti_10000,
            'noti_5000' => $noti_5000,
            'noti_2000' => $noti_2000,
            'noti_1000' => $noti_1000,
            'noti_500' => $noti_500,
            'sarafu_500' => $sarafu_500,
            'sarafu_200' => $sarafu_200,
            'sarafu_100' => $sarafu_100,
            'sarafu_50' => $sarafu_200,
            'mwekaji' => auth()->user()->jina_kamili,
            'tarehe' => $tarehe,
            'aina_ya_toleo' => $aina_ya_toleo,
            'nukushi' => uniqid(),
        ];

        $success = Denomination::create($data_save);

        $jumla = $request->noti_10000 + $request->noti_5000 + $request->noti_2000 + $request->noti_1000 + $request->noti_500 + $request->sarafu_500 
        + $request->sarafu_200 + + $request->sarafu_100 + $request->sarafu_50;

        if($success){
            //tracking
            $message = "Ongezo";
            $data = "Uwekaji wa denomination kwa ajili ya $aina_ya_toleo, kiasi $jumla tarehe $tarehe";
            $this->setActivity($message,$data);
            return response()->json(['success'=>"Denomination za $aina_ya_toleo zimewekwa kikamilifu.."]);
        }
        else{
            return response()->json(['errors'=>"Imeshindikana kuongeza denomination za $aina_ya_toleo .."]);
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new Denomination; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
