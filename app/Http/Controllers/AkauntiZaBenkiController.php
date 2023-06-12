<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AkauntiZaBenki;
use Validator;
use Auth;
use Str;

class AkauntiZaBenkiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_akaunti = AkauntiZaBenki::latest()->get();
        return view('backend.mengineyo.akaunti_za_benki',compact(['data_akaunti']));
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
            'jina_la_benki' => 'required',
            'jina_la_akaunti' => 'required',
            'akaunti_namba' => 'required|unique:akaunti_za_benkis',
            'tawi' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza sehemu zote zinazohitajika, namba ya akaunti isijirudie."]);
        }
        else{
            //getting data
            $data_save = [
            'jina_la_benki' => $request->jina_la_benki,
            'jina_la_akaunti' => $request->jina_la_akaunti,
            'tawi' => $request->tawi,
            'hali_ya_akaunti' => $request->hali_ya_akaunti,
            'akaunti_namba' => $request->akaunti_namba,
            'maelezo' => $request->maelezo,
            ];

            $success = AkauntiZaBenki::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la akaunti ya benki $request->akaunti_namba";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Akaunti imeongezwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuongeza akaunti"]]);
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
        $data = AkauntiZaBenki::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AkauntiZaBenki $model)
    {
        $existing = $model::findOrFail($request->hidden_id);

        //
        $must = [
            'jina_la_benki' => 'required',
            'jina_la_akaunti' => 'required',
            'akaunti_namba' => 'required|unique:akaunti_za_benkis,akaunti_namba,'.$existing->id,
            'tawi' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza sehemu zote zinazohitajika, namba ya akaunti isijirudie."]);
        }
        else{
            //getting data
            $data_update = [
            'jina_la_benki' => $request->jina_la_benki,
            'jina_la_akaunti' => $request->jina_la_akaunti,
            'tawi' => $request->tawi,
            'hali_ya_akaunti' => $request->hali_ya_akaunti,
            'akaunti_namba' => $request->akaunti_namba,
            'maelezo' => $request->maelezo,
            ];

            $success = AkauntiZaBenki::whereId($request->hidden_id)->update($data_update);

            if($success){
                
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la akaunti ya benki $request->akaunti_namba";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Akaunti imebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili akaunti"]]);
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
        $data = AkauntiZaBenki::findOrFail($id);
        $akaunti = $data->akaunti_namba;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa akaunti $akaunti";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Akaunti imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Akaunti haijafutwa jaribu tena.."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new AkauntiZaBenki; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
