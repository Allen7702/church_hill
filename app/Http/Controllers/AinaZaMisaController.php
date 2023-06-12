<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AinaZaMisa;
use Validator;
use Auth;
use Str;

class AinaZaMisaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_misa = AinaZaMisa::latest()->get();
        return view('backend.mengineyo.aina_za_misa',compact(['data_misa']));
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
            'jina_la_misa' => 'required|unique:aina_za_misas',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Jina la misa limeshatumika."]);
        }
        else{
            //getting data
            $data_save = [
            'jina_la_misa' => $request->jina_la_misa,
            'maelezo' => $request->maelezo,
            'slug' => Str::slug($request->jina_la_misa),
            ];

            $success = AinaZaMisa::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la aina ya misa $request->jina_la_misa";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Misa imesajiliwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kusajili misa"]]);
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
        $data = AinaZaMisa::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AinaZaMisa $model)
    {
        $existing = $model::findOrFail($request->hidden_id);

        //
        $must = [
            'jina_la_misa' => 'sometimes|unique:aina_za_misas,jina_la_misa,'.$existing->id,
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Jina la misa limeshatumika."]);
        }
        else{
            //getting data
            $data_update = [
            'jina_la_misa' => $request->jina_la_misa,
            'maelezo' => $request->maelezo,
            'slug' => Str::slug($request->jina_la_misa),
            ];

            $success = $model::whereId($request->hidden_id)->update($data_update);

            if($success){
                
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la misa $request->jina_la_misa";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Misa imebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili misa"]]);
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
        $data = AinaZaMisa::findOrFail($id);
        $misa = $data->jina_la_misa;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa misa $misa";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Misa imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Misa haijafutwa jaribu tena.."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new AinaZaMisa; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
