<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VyeoKanisa;
use Validator;
use Auth;
use Str;

class VyeoKanisaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_vyeo = VyeoKanisa::latest()->get();
        return view('backend.mengineyo.vyeo_kanisa',compact(['data_vyeo']));
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
            'jina_la_cheo' => 'required|unique:vyeo_kanisas',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Jina la cheo limeshatumika."]);
        }
        else{
            //getting data
            $data_save = [
            'jina_la_cheo' => Str::ucfirst(strtolower($request->jina_la_cheo)),
            'maelezo' => $request->maelezo,
            'slug' => Str::slug($request->jina_la_cheo),
            ];

            
            $success = VyeoKanisa::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la cheo $request->jina_la_cheo";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Cheo kimesajiliwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kusajili cheo"]]);
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
        $data = VyeoKanisa::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VyeoKanisa $model)
    {
        $existing = VyeoKanisa::findOrFail($request->hidden_id);

        //
        $must = [
            'jina_la_cheo' => 'sometimes|unique:vyeo_kanisas,jina_la_cheo,'.$existing->id,
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Jina la cheo limeshatumika."]);
        }
        else{
            //getting data
            $data_update = [
                'jina_la_cheo' => Str::ucfirst(strtolower($request->jina_la_cheo)),
                'maelezo' => $request->maelezo,
                'slug' => Str::slug($request->jina_la_cheo),
            ];

            $success = VyeoKanisa::whereId($request->hidden_id)->update($data_update);

            if($success){
                
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la cheo $request->jina_la_cheo";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Cheo kimebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili cheo"]]);
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
        $data = VyeoKanisa::findOrFail($id);
        $cheo = $data->jina_la_cheo;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa cheo $cheo";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Cheo kimefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Cheo hakijafutwa jaribu tena.."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new VyeoKanisa; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
