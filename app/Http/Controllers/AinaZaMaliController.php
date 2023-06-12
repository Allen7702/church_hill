<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AinaZaMali;
use Validator;
use Str;
use Auth;

class AinaZaMaliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_aina = AinaZaMali::latest()->get();
        return view('backend.mengineyo.aina_za_mali',compact(['data_aina']));
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
            'aina_ya_mali' => 'required|unique:aina_za_malis',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Aina ya mali imeshatumika."]);
        }
        else{
            //getting data
            $data_save = [
                'aina_ya_mali' => $request->aina_ya_mali,
                'maelezo' => $request->maelezo,
                'slug' => Str::slug($request->aina_ya_mali),
            ];

            $success = AinaZaMali::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la aina ya mali $request->aina_ya_mali";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Aina ya mali imeongezwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuongeza aina ya mali"]]);
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
        $data = AinaZaMali::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AinaZaMali $model)
    {
        $existing = $model::findOrFail($request->hidden_id);

        //
        $must = [
            'aina_ya_mali' => 'sometimes|unique:aina_za_malis,aina_ya_mali,'.$existing->id,
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Aina ya mali imeshatumika."]);
        }
        else{
            //getting data
            $data_update = [
                'aina_ya_mali' => $request->aina_ya_mali,
                'maelezo' => $request->maelezo,
                'slug' => Str::slug($request->aina_ya_mali),
            ];

            $success = $model::whereId($request->hidden_id)->update($data_update);

            if($success){
                
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la aina ya mali $request->aina_ya_mali";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Aina ya mali imebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili aina ya mali"]]);
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
        $data = AinaZaMali::findOrFail($id);
        $aina = $data->aina_ya_mali;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa aina ya mali $aina";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Aina ya mali imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Aina ya mali haijafutwa jaribu tena.."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new AinaZaMali; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
