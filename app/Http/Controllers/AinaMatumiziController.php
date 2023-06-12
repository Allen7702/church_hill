<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AinaMatumizi;
use Validator;
use Auth;
use Str;

class AinaMatumiziController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_matumizi = AinaMatumizi::latest()->get();
        return view('backend.mengineyo.aina_za_matumizi',compact(['data_matumizi']));
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
            'aina_ya_matumizi' => 'required|unique:aina_matumizis',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Aina ya matumizi imeshatumika."]);
        }
        else{
            //getting data
            $data_save = [
                'aina_ya_matumizi' => $request->aina_ya_matumizi,
                'maelezo' => $request->maelezo,
            ];

            $success = AinaMatumizi::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la aina ya matumizi $request->aina_ya_matumizi";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Aina imesajiliwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kusajili aina ya matumizi.."]]);
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
        $data = AinaMatumizi::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AinaMatumizi $model)
    {
        //
        $existing = AinaMatumizi::findOrFail($request->hidden_id);

        $must = [
            'aina_ya_matumizi' => 'required|unique:aina_matumizis,aina_ya_matumizi,'.$existing->id,
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Aina ya matumizi imeshatumika."]);
        }
        else{
            //getting data
            $data_update = [
                'aina_ya_matumizi' => $request->aina_ya_matumizi,
                'maelezo' => $request->maelezo,
            ];

            $success = AinaMatumizi::whereId($request->hidden_id)->update($data_update);

            if($success){
                
            //tracking
                $message = "Badiliko";
                $data = "Badiliko la aina ya matumizi $request->aina_ya_matumizi";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Aina imebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili aina ya matumizi.."]]);
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
        //getting data
        $data = AinaMatumizi::findOrFail($id);
        $aina = $data->aina_ya_matumizi;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa aina ya matumizi $aina";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Aina ya matumizi imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Aina ya matumizi haijafutwa jaribu tena.."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new AinaMatumizi; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
