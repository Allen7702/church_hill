<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BajetiMakisioMatumizi;
use App\Huduma;
use App\AinaMatumizi;
use Validator;
use Auth;

class BajetiMakisioMatumiziController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //getting the index of it
        $title = "Makisio bajeti ya matumizi";
        $data_bajeti = BajetiMakisioMatumizi::latest()->get();
        $data_matumizi = AinaMatumizi::latest()->get();
        $data_huduma = Huduma::latest()->get();
        $data_bajeti_total = BajetiMakisioMatumizi::sum('kiasi');

        return view('backend.bajeti.bajeti_makisio_matumizi',compact(['title','data_bajeti','data_matumizi','data_huduma','data_bajeti_total']));
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
            'aina_ya_matumizi' => 'required',
            'mwaka' => 'required',
            'kiasi' => 'required',
            'kundi' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza aina ya matumizi, mwaka, kundi na kiasi.."]);
        }

        //validating if the bajeti exists
        $matumizi = $request->aina_ya_matumizi;
        $mwaka = $request->mwaka;

        if(BajetiMakisioMatumizi::where('aina_ya_matumizi',$matumizi)->where('mwaka',$mwaka)->exists()){
            return response()->json(['errors'=>"Bajeti ya $matumizi mwaka $mwaka ilishawekwa tayari.."]);
        }

        //everything ok
        $data_save = [
            'aina_ya_matumizi' => $matumizi,
            'mwaka' => $mwaka,
            'kiasi' => $request->kiasi,
            'kundi' => $request->kundi,
            'maelezo' => $request->maelezo,
        ];

        $success = BajetiMakisioMatumizi::create($data_save);

        if($success){
            //tracking
            $message = "Ongezo";
            $data = "Ongezo la bajeti ya $matumizi, kiasi $request->kiasi mwaka $mwaka";
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
        $data = BajetiMakisioMatumizi::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BajetiMakisioMatumizi $model)
    {
        //validating data
        $must = [
            'aina_ya_matumizi' => 'required',
            'mwaka' => 'required',
            'kiasi' => 'required',
            'kundi' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza aina ya matumizi, mwaka, kundi na kiasi.."]);
        }

        //validating if the bajeti exists
        $matumizi = $request->aina_ya_matumizi;
        $mwaka = $request->mwaka;

        if(BajetiMakisioMatumizi::where('aina_ya_matumizi',$matumizi)->where('mwaka',$mwaka)->where('id','!=',$request->hidden_id)->exists()){
            return response()->json(['errors'=>"Bajeti ya $matumizi mwaka $mwaka ilishawekwa tayari.."]);
        }

        //everything ok
        $data_update = [
            'aina_ya_matumizi' => $matumizi,
            'mwaka' => $mwaka,
            'kiasi' => $request->kiasi,
            'kundi' => $request->kundi,
            'maelezo' => $request->maelezo,
        ];

        $success = BajetiMakisioMatumizi::whereId($request->hidden_id)->update($data_update);

        if($success){
            //tracking
            $message = "Badiliko";
            $data = "Badiliko la bajeti ya $matumizi, kiasi $request->kiasi mwaka $mwaka";
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
        $data_delete = BajetiMakisioMatumizi::findOrFail($id);
        $matumizi = $data_delete->aina_ya_matumizi;
        $mwaka = $data_delete->mwaka;
        $kiasi = $data_delete->kiasi;

        if($data_delete->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa bajeti ya $matumizi, kiasi $kiasi mwaka $mwaka";
            $this->setActivity($message,$data);
            return response()->json(['success'=>"Bajeti imefutwa kikamilifu.."]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new BajetiMakisioMatumizi; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
