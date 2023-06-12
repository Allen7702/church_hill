<?php

namespace App\Http\Controllers;

use App\AinaZaSadaka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AinaZaSadakaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_sadaka = AinaZaSadaka::latest()->get();
        return view('backend.mengineyo.aina_za_sadaka',compact(['data_sadaka']));
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
            'jina_la_sadaka' => 'required|unique:aina_za_sadakas',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Jina la sadaka limeshatumika."]);
        }
        else{
            //getting data
            $data_save = [
                'jina_la_sadaka' => $request->jina_la_sadaka,
                'maelezo' => $request->maelezo,
                'slug' => Str::slug($request->jina_la_sadaka),
            ];

            $success = AinaZaSadaka::create($data_save);

            if($success){

                //tracking
                $message = "Ongezo";
                $data = "Ongezo la aina ya sadaka $request->jina_la_sadaka";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Sadaka imesajiliwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kusajili sadaka"]]);
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
        $data = AinaZaSadaka::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AinaZaSadaka $model)
    {
        $existing = $model::findOrFail($request->hidden_id);

        //
        $must = [
            'jina_la_sadaka' => 'sometimes|unique:aina_za_sadakas,jina_la_sadaka,'.$existing->id,
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Jina la misa limeshatumika."]);
        }
        else{
            //getting data
            $data_update = [
                'jina_la_sadaka' => $request->jina_la_sadaka,
                'maelezo' => $request->maelezo,
                'slug' => Str::slug($request->jina_la_sadaka),
            ];

            $success = $model::whereId($request->hidden_id)->update($data_update);

            if($success){

                //tracking
                $message = "Badiliko";
                $data = "Badiliko la sadaka $request->jina_la_sadaka";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Sadaka imebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili sadaka"]]);
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
        $data = AinaZaSadaka::findOrFail($id);
        $sadaka = $data->jina_la_sadaka;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa sadaka $sadaka";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Sadaka imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Sadaka haijafutwa jaribu tena."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new AinaZaSadaka;
        activity()
            ->performedOn($model)
            ->causedBy(Auth::user())
            ->withProperties(["Taarifa" => "$data"])
            ->log($message);
    }
}
