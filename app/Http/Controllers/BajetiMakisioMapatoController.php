<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BajetiMakisioMapato;
use App\AinaZaMisa;
use App\AinaZaMichango;
use Validator;
use Auth;

class BajetiMakisioMapatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //getting the index of it
        $title = "Makisio bajeti ya mapato";
        $data_bajeti = BajetiMakisioMapato::latest()->get();
        $data_michango = AinaZaMichango::latest()->get();
        $data_misa = AinaZaMisa::latest()->get();
        $data_bajeti_total = BajetiMakisioMapato::sum('kiasi');

        return view('backend.bajeti.bajeti_makisio_mapato',compact(['title','data_bajeti','data_michango','data_misa','data_bajeti_total']));
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
        //getting data
        $must = [
            'aina_ya_mapato' => 'required',
            'mwaka' => 'required',
            'kiasi' => 'required',
            'kundi' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza aina ya mapato, mwaka, kundi na kiasi.."]);
        }

        //validating if the bajeti exists
        $mapato = $request->aina_ya_mapato;
        $mwaka = $request->mwaka;

        if(BajetiMakisioMapato::where('aina_ya_mapato',$mapato)->where('mwaka',$mwaka)->exists()){
            return response()->json(['errors'=>"Bajeti ya $mapato mwaka $mwaka ilishawekwa tayari.."]);
        }

        //everything ok
        $data_save = [
            'aina_ya_mapato' => $mapato,
            'mwaka' => $mwaka,
            'kiasi' => $request->kiasi,
            'kundi' => $request->kundi,
            'maelezo' => $request->maelezo,
        ];

        $success = BajetiMakisioMapato::create($data_save);

        if($success){
            //tracking
            $message = "Ongezo";
            $data = "Ongezo la bajeti ya $mapato, kiasi $request->kiasi mwaka $mwaka";
            $this->setActivity($message,$data);
            return response()->json(['success'=>"Makisio ya Bajeti yameongezwa kikamilifu.."]);
        }
        else{
            return response()->json(['errors'=>"Imeshindikana kuongeza bajeti.."]);
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
        $data = BajetiMakisioMapato::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BajetiMakisioMapato $model)
    {
        //validating data
        $must = [
            'aina_ya_mapato' => 'required',
            'mwaka' => 'required',
            'kiasi' => 'required',
            'kundi' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza aina ya mapato, mwaka, kundi na kiasi.."]);
        }

        //validating if the bajeti exists
        $mapato = $request->aina_ya_mapato;
        $mwaka = $request->mwaka;

        if(BajetiMakisioMapato::where('aina_ya_mapato',$mapato)->where('mwaka',$mwaka)->where('id','!=',$request->hidden_id)->exists()){
            return response()->json(['errors'=>"Bajeti ya $mapato mwaka $mwaka ilishawekwa tayari.."]);
        }

        //everything ok
        $data_update = [
            'aina_ya_mapato' => $mapato,
            'mwaka' => $mwaka,
            'kiasi' => $request->kiasi,
            'kundi' => $request->kundi,
            'maelezo' => $request->maelezo,
        ];

        $success = BajetiMakisioMapato::whereId($request->hidden_id)->update($data_update);

        if($success){
            //tracking
            $message = "Badiliko";
            $data = "Badiliko la bajeti ya $mapato, kiasi $request->kiasi mwaka $mwaka";
            $this->setActivity($message,$data);
            return response()->json(['success'=>"Makisio ya Bajeti yamerekebishwa kikamilifu.."]);
        }
        else{
            return response()->json(['errors'=>"Imeshindikana kubadili bajeti.."]);
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
        //
        $data_delete = BajetiMakisioMapato::findOrFail($id);
        $mapato = $data_delete->aina_ya_mapato;
        $mwaka = $data_delete->mwaka;
        $kiasi = $data_delete->kiasi;

        if($data_delete->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa bajeti ya $mapato, kiasi $kiasi mwaka $mwaka";
            $this->setActivity($message,$data);
            return response()->json(['success'=>"Bajeti imefutwa kikamilifu.."]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new BajetiMakisioMapato; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
