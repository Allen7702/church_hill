<?php

namespace App\Http\Controllers;

use App\AhadiAinayamichango;
use Illuminate\Http\Request;
use App\AinaZaMichango;
use Validator;
use Auth;
use Str;

class AinaZaMichangoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_michango = AinaZaMichango::latest()->get();
        return view('backend.michango.aina_za_michango',compact(['data_michango']));
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
            'aina_ya_mchango' => 'required|unique:aina_za_michangos',
        ];

          
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Imeshindikana kuongeza mchango."]);
        }
        else{
            //getting data
           
            $data_save = [
            'aina_ya_mchango' => $request->aina_ya_mchango,
            'maelezo' => $request->maelezo,
            'slug' => Str::slug($request->aina_ya_mchango),
            ];

            $success = AinaZaMichango::create($data_save);

       
            if($success){

                

                    if(!is_null($request->awamu)){
                AhadiAinayamichango::create([
                  'aina_ya_michango_id'=>$success->id,
                  'idadi_ya_awamu'=>$request->awamu,
                  'tarehe_ya_mwisho'=>$request->tarehe,
                  'kiasi'=> str_replace( ',', '',$request->kiasi)
                ]);
            }
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la aina ya mchango $request->aina_ya_mchango";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Aina ya mchango imeongezwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuongeza aina ya mchango"]]);
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
        $data = AinaZaMichango::findOrFail($id);
        $mchango_ahadi=$data->ahadi_ya_aina_za_mchango;
        return response()->json([
            'result'=>$data,
            'checkbox'=>is_null($mchango_ahadi)?'0':'1',
            'awamu'=>is_null($mchango_ahadi)?'':$mchango_ahadi->idadi_ya_awamu,
            'kiasi'=>is_null($mchango_ahadi)?'':$mchango_ahadi->kiasi,
            'tarehe'=>is_null($mchango_ahadi)?'':$mchango_ahadi->tarehe_ya_mwisho
    
    ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AinaZaMichango $model)
    {
        $existing = $model::findOrFail($request->hidden_id);

        //
        $must = [
            'aina_ya_mchango' => 'sometimes|unique:aina_za_michangos,aina_ya_mchango,'.$existing->id,
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Aina ya mchango imeshatumika."]);
        }
        else{
            //getting data
            $data_update = [
            'aina_ya_mchango' => $request->aina_ya_mchango,
            'maelezo' => $request->maelezo,
            'slug' => Str::slug($request->aina_ya_mchango),
            ];

            $success = AinaZaMichango::whereId($request->hidden_id)->update($data_update);

            if($success){
                if(!is_null($request->awamu)){

                   $mchango=AinaZaMichango::whereId($request->hidden_id)->first();
                   $mchango->ahadi_ya_aina_za_mchango->update([
                      'idadi_ya_awamu'=>$request->awamu,
                      'tarehe_ya_mwisho'=>$request->tarehe,
                      'kiasi'=> str_replace( ',', '',$request->kiasi)
                    ]);
                }
                
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la aina ya mchango $request->aina_ya_mchango";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Aina ya mchango imerekebishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili aina ya mchango"]]);
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
        $data = AinaZaMichango::findOrFail($id);
        $mchango = $data->aina_ya_mchango;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa aina ya mchango $mchango";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Aina ya mchango imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Aina ya mchango haijafutwa jaribu tena.."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new AinaZaMichango; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
