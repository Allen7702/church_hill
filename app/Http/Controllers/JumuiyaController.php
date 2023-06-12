<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kanda;
use App\Jumuiya;
use App\Mwanafamilia;
use Auth;
use Validator;
use Str;
use App\Imports\JumuiyaImport;
use Alert;
use App\Familia;

class JumuiyaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $user = Auth::user();
        $user_ngazi = $user->ngazi;
        //
        $title = "Orodha ya jumuiya";

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $data_jumuiya=Jumuiya::latest();
            $kanda=Kanda::latest();
         }elseif($user_ngazi=='Kanda'){
            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);

            $data_jumuiya = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
         }elseif($user_ngazi=='Jumuiya'){
            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);

             $data_jumuiya = $jumuiya; 
          
         } 
         
        $data_jumuiya=$data_jumuiya->get();
        $kanda=$kanda->get();

        return view('backend.jumuiya.jumuiya',compact(['data_jumuiya','kanda','title']));
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
        //validating data
        $must = [
            'jina_la_jumuiya' => 'required|unique:jumuiyas',
        ];

        //validating errors
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Jina la jumuiya limeshatumika."]);
        }
        else{

            //getting kanda
            $kanda = $request->jina_la_kanda;

            if($kanda == ""){
                return response()->json(['errors'=>"Hakikisha umechagua kanda.."]);   
            }

            //getting data
            $data_save = [
                'jina_la_kanda' => $kanda,
                'jina_la_jumuiya' => strtoupper($request->jina_la_jumuiya),
                'slug' => Str::slug($request->jina_la_jumuiya),
                'comment' => $request->comment,
            ];

            //saving data
            $success = Jumuiya::create($data_save);

            if($success){
                //incrementing the number of jumuiya in kanda
                Kanda::where('jina_la_kanda',$kanda)->increment('idadi_ya_jumuiya',1);

                //tracking
                $message = "Ongezo";
                $jina_jumuiya = strtoupper($request->jina_la_jumuiya);
                $data = "Ongezo la jumuiya $jina_jumuiya";
                $this->setActivity($message,$data);
                return response()->json(['success'=>["Jumuiya imeongezwa kikamilifu.."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kusajili jumuiya jaribu tena."]]);
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
        //getting data
        $data = Jumuiya::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jumuiya $model)
    {
        //getting data
        $existing = $model::findOrFail($request->hidden_id);

        $must = [
            'jina_la_jumuiya' => 'sometimes|unique:jumuiyas,jina_la_jumuiya,'.$existing->id,
        ];

        //validating errors
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Jina la jumuiya limeshatumika."]);
        }
        else{
            //getting kanda
            $kanda = $request->kanda_update;

            if($kanda == ""){
                return response()->json(['errors'=>"Hakikisha umechagua kanda."]);   
            }

            //getting data
            $data_update = [
                'jina_la_kanda' => $kanda,
                'jina_la_jumuiya' => strtoupper($request->jina_la_jumuiya),
                'slug' => Str::slug($request->jina_la_jumuiya),
                'comment' => $request->comment,
            ];

            //saving data
            $success = Jumuiya::whereId($request->hidden_id)->update($data_update);

            if($success){
                //incrementing/decrementing the number of jumuiya in kanda

                if($existing->jina_la_kanda != $kanda){
                    //increment since it is new kanda
                    Kanda::where('jina_la_kanda',$kanda)->increment('idadi_ya_jumuiya',1);

                    //decrement the old kanda
                    Kanda::where('jina_la_kanda',$existing->jina_la_kanda)->where('idadi_ya_jumuiya','!=',0)->decrement('idadi_ya_jumuiya',1);
                }

                //tracking
                $jina_jumuiya = strtoupper($request->jina_la_jumuiya);
                $message = "Badiliko";
                $data = "Badiliko la jumuiya $jina_jumuiya";
                $this->setActivity($message,$data);
                return response()->json(['success'=>["Jumuiya imebadilishwa kikamilifu.."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili jumuiya jaribu tena."]]);
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
        $data = Jumuiya::findOrFail($id);
        $kanda = $data->jina_la_kanda;
        $jumuiya = $data->jina_la_jumuiya;

        if($data->delete()){
            //decrementing the number of jumuiya in kanda
            Kanda::where('jina_la_kanda',$kanda)->where('idadi_ya_jumuiya','!=',0)->decrement('idadi_ya_jumuiya',1);
            
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa jumuiya $jumuiya";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Jumuiya imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Jumuiya haijafutwa jaribu tena.."]]);
        }
    }

    public function jumuiya_import(Request $request){
        $file = $request->file('file')->store('import');

        // Excel::import(new JumuiyaImport, $file);
        $import = new JumuiyaImport;
        $import->import($file);

        $errors = $import->errors();

        if(!$errors){
            Alert::info("<b class='text-primary'>Taarifa</b>","Jumuiya hazijawekwa kwakua kuna tatizo kwenye taarifa ulizojaza..");
            return back();
        }

        if($import->failures()->isNotEmpty()){
            return back()->withFailures($import->failures());
        }

        return back()->withStatus('Jumuiya zimeongezwa kikamilifu.',compact(['errors']));
    }

    //function to handle members of specific jumuiya
    public function wanajumuiya_husika($jumuiya){

        
        $jumuiya=trim(str_replace("\\t",'',$jumuiya));
      //  dd($jumuiya);
        $title = "Orodha ya wanajumuiya $jumuiya";
        $familia = Familia::latest()->get();
         $kanda = Kanda::latest()->get();
        $data_wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->leftjoin('users','users.mwanajumuiya_id','=','mwanafamilias.id')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('users.cheo','DESC')
         ->orderBy('mwanafamilias.jina_kamili','ASC')
        ->get(['mwanafamilias.id','mwanafamilias.jina_kamili','users.ngazi as user_ngazi','users.cheo as user_cheo','mwanafamilias.cheo_familia','mwanafamilias.namba_utambulisho','mwanafamilias.mawasiliano']);


        return view('backend.jumuiya.wanajumuiya_husika',compact(['title','data_wanajumuiya','jumuiya','kanda','familia']));
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new Jumuiya; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
