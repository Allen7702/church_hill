<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Huduma;
use Validator;
use Auth;
use Str;

class HudumaAinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_huduma = Huduma::latest()->get();
        return view('backend.mengineyo.aina_za_huduma',compact(['data_huduma']));
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
        $must = [
            'aina_ya_huduma' => 'required|unique:hudumas',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Aina ya huduma imeshasajiliwa."]);
        }
        else{
            //getting data
            $data_save = [
            'aina_ya_huduma' => $request->aina_ya_huduma,
            'maelezo' => $request->maelezo,
            'slug' => Str::slug($request->aina_ya_huduma),
            ];

            $success = Huduma::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la huduma $request->aina_ya_huduma";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Huduma imesajiliwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kusajili huduma"]]);
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
        $data = Huduma::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Huduma $model)
    {
        $existing = $model::findOrFail($request->hidden_id);

        //
        $must = [
            'aina_ya_huduma' => 'sometimes|unique:hudumas,aina_ya_huduma,'.$existing->id,
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Huduma imeshasajiliwa."]);
        }
        else{
            //getting data
            $data_update = [
                'aina_ya_huduma' => $request->aina_ya_huduma,
                'maelezo' => $request->maelezo,
                'slug' => Str::slug($request->aina_ya_huduma),
            ];

            $success = $model::whereId($request->hidden_id)->update($data_update);

            if($success){
                
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la huduma $request->aina_ya_huduma";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Huduma imebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili huduma"]]);
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
        $data = Huduma::findOrFail($id);
        $huduma = $data->aina_ya_huduma;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa huduma $huduma";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Huduma imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Huduma haijafutwa jaribu tena.."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new Huduma; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
