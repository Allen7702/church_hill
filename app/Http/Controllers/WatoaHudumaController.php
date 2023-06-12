<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Huduma;
use App\WatoaHuduma;
use Validator;
use Auth;
use Str;

class WatoaHudumaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_watoahuduma = WatoaHuduma::latest()->get();
        $data_huduma = Huduma::latest()->get();
        return view('backend.mengineyo.watoa_huduma',compact(['data_huduma','data_watoahuduma']));
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
            'aina_ya_huduma' => 'required',
            'anwani' => 'required',
            'mawasiliano' => 'required',
            'jina_kamili' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza sehemu zinazohitajika.."]);
        }
        else{
            //getting data
            $data_save = [
                'aina_ya_huduma' => $request->aina_ya_huduma,
                'anwani' => $request->anwani,
                'mawasiliano' => $request->mawasiliano,
                'jina_kamili' => $request->jina_kamili,
                'maelezo' => $request->maelezo,
            ];

            //before creating ensure if huduma and mtoa huduma already exists with same name and huduma
            if(WatoaHuduma::where('aina_ya_huduma',$request->aina_ya_huduma)->where('jina_kamili',$request->jina_kamili)->exists()){
                return response()->json(['errors'=>["Mtoa na aina ya huduma tayari vimeshasajiliwa"]]);
            }

            $success = WatoaHuduma::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la mtoa huduma $request->jina_kamili";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Mtoa Huduma amesajiliwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kusajili mtoa huduma"]]);
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
        $data = WatoaHuduma::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WatoaHuduma $model)
    {
        $existing = $model::findOrFail($request->hidden_id);

        $must = [
            'aina_ya_huduma' => 'required',
            'anwani' => 'required',
            'mawasiliano' => 'required',
            'jina_kamili' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza sehemu zinazohitajika.."]);
        }
        else{
            //getting data
            $data_update = [
                'aina_ya_huduma' => $request->aina_ya_huduma,
                'anwani' => $request->anwani,
                'mawasiliano' => $request->mawasiliano,
                'jina_kamili' => $request->jina_kamili,
                'maelezo' => $request->maelezo,
            ];

            $success = WatoaHuduma::whereId($request->hidden_id)->update($data_update);

            if($success){
                
                //tracking
                $message = "Badiliko";
                $data = "badiliko la mtoa huduma $request->jina_kamili";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Taarifa zimebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili taarifa.."]]);
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
        $data = WatoaHuduma::findOrFail($id);
        $mtoa_huduma = $data->jina_kamili;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa mtoa huduma $mtoa_huduma";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Mtoa huduma amefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Mtoa huduma hajafutwa jaribu tena.."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new WatoaHuduma; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
