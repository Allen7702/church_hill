<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AinaZaMali;
use App\MaliZaKanisa;
use Validator;
use Auth;
use Str;

class MaliZaKanisaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_mali = MaliZaKanisa::latest()->get();
        $aina_za_mali = AinaZaMali::latest()->get();
        $mali_total = MaliZaKanisa::sum('thamani');
        return view('backend.mengineyo.mali_za_kanisa',compact(['data_mali','aina_za_mali','mali_total']));
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
            'usajili' => 'required|unique:mali_za_kanisas',
            'jina_la_mali' => 'required',
            'thamani' => 'required',
            'hali_yake' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza usajili, jina la mali, thamani na hali ya mali pia usajili uwe wa kipekee."]);
        }
        else{
            //getting data
            $data_save = [
                'jina_la_mali' => $request->jina_la_mali,
                'aina_ya_mali' => $request->aina_ya_mali,
                'maelezo' => $request->maelezo,
                'slug' => Str::slug($request->usajili),
                'thamani' => $request->thamani,
                'usajili' => $request->usajili,
                'hali_yake' => $request->hali_yake,
            ];

            $success = MaliZaKanisa::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la mali ya kanisa $request->usajili, thamani $request->thamani";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Mali imeongezwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuongeza mali"]]);
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
        $data = MaliZaKanisa::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaliZaKanisa $model)
    {
        $existing = $model::findOrFail($request->hidden_id);

        //
        $must = [
            'usajili' => 'sometimes|unique:mali_za_kanisas,usajili,'.$existing->id,
            'jina_la_mali' => 'required',
            'thamani' => 'required',
            'hali_yake' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza usajili, jina la mali, thamani na hali ya mali pia usajili uwe wa kipekee."]);
        }
        else{
            //getting data
            $data_update = [
                'jina_la_mali' => $request->jina_la_mali,
                'aina_ya_mali' => $request->aina_ya_mali,
                'maelezo' => $request->maelezo,
                'slug' => Str::slug($request->usajili),
                'thamani' => $request->thamani,
                'usajili' => $request->usajili,
                'hali_yake' => $request->hali_yake,
            ];

            $success = $model::whereId($request->hidden_id)->update($data_update);

            if($success){
                
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la mali $request->jina_la_mali";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Mali imebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili mali"]]);
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
        $data = MaliZaKanisa::findOrFail($id);
        $mali = $data->jina_la_mali;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa mali $mali";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Mali imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Mali haijafutwa jaribu tena.."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new MaliZaKanisa; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
