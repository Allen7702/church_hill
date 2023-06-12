<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kikundi;
use App\Mwanafamilia;
use App\Wanakikundi;
use Auth;
use Illuminate\Support\Facades\Storage;
use Validator;
use Str;

class KikundiController extends Controller
{
    //calling the index of kikundi
    public function kikundi_index(Request $request){
        //
        $data_kikundi = Kikundi::latest()->get();
        $wanafamilia = Mwanafamilia::latest('mwanafamilias.created_at')->leftJoin('familias','mwanafamilias.familia','=','familias.id')->get(['mwanafamilias.id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
        return view('backend.kikundi.kikundi',compact(['data_kikundi','wanafamilia']));
    }

    //function to handle storing of data
    public function kikundi_store(Request $request)
    {
        //validating data
        $must = [
            'jina_la_kikundi' => 'required|unique:kikundis',
        ];

        //validating errors
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza jina la chama, jina la chama lisijirudie.."]);
        }

        else{

            //getting data
            $data_save = [
                'jina_la_kikundi' => strtoupper($request->jina_la_kikundi),
                'slug' => Str::slug($request->jina_la_kikundi),
                'maoni' => $request->maoni,
            ];

            //saving data
            $success = Kikundi::create($data_save);

            if(!empty($request['photo'])){
                $image = $request->photo;
                $imageInfo = explode(";base64,", $image);
                $imgExt = str_replace('data:image/', '', $imageInfo[0]);
                $image = str_replace(' ', '+', $imageInfo[1]);
                $imageName = "kikundi-".time().".".$imgExt;
                Storage::disk('local')->put($imageName, base64_decode($image));

                $success->clearMediaCollection('photo');

                $success->addMediaFromDisk($imageName, 'local')

                    ->toMediaCollection('photo');

                Storage::disk('local')->delete($imageName);
            }


            if($success){
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la chama katika parokia";
                $this->setActivity($message,$data);
                return response()->json(['success'=>["Chama kimesajiliwa kikamilifu.."]]);
            }

            else{
                return response()->json(['errors'=>["Imeshindikana kusajili chama jaribu tena."]]);
            }
        }
    }

    //function to call the edition of data
    public function kikundi_edit($id)
    {
        //
        $data = Kikundi::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //function to handle the update
    public function kikundi_update(Request $request, Kikundi $model)
    {
         //getting old data first
        $existing = Kikundi::findOrFail($request->hidden_id);

        //validating data
        $must = [
            'jina_la_kikundi' => 'sometimes|unique:kikundis,jina_la_kikundi,'.$existing->id,
        ];

        //validating errors
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza jina la chama, jina la chama lisijirudie.."]);
        }

        else{

            //getting data
            $data_update = [
                'jina_la_kikundi' => strtoupper($request->jina_la_kikundi),
                'slug' => Str::slug($request->jina_la_kikundi),
                'maoni' => $request->maoni,
            ];

            //saving data
            $success = Kikundi::whereId($request->hidden_id)->update($data_update);

            if($success){

                $kikundi = Kikundi::whereId($request->hidden_id)->first();
                if(!empty($request['photo'])){
                    $image = $request->photo;
                    $imageInfo = explode(";base64,", $image);
                    $imgExt = str_replace('data:image/', '', $imageInfo[0]);
                    $image = str_replace(' ', '+', $imageInfo[1]);
                    $imageName = "kikundi-".time().".".$imgExt;
                    Storage::disk('local')->put($imageName, base64_decode($image));

                    $kikundi->clearMediaCollection('photo');

                    $kikundi->addMediaFromDisk($imageName, 'local')

                        ->toMediaCollection('photo');

                    Storage::disk('local')->delete($imageName);

                }
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la chama katika parokia";
                $this->setActivity($message,$data);
                return response()->json(['success'=>["Chama kimerekebishwa kikamilifu.."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili chama jaribu tena."]]);
            }
        }
    }

    //function to handle deletion of vikundi
    public function kikundi_destroy($id)
    {
        //getting data
        $data = Kikundi::findOrFail($id);
        $chama = $data->jina_la_kikundi;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa chama $chama katika parokia";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Chama kimefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Chama hakijafutwa jaribu tena.."]]);
        }
    }

    public function wanakikundi_store(Request $request){
        //validating data
        $must = [
            'kikundi' => 'required',
            'wanafamilia' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>["Hakikisha umechagua chama na wanachama"]]);
        }
        else{
            //
            $kikundi = $request->kikundi;
            $maoni = $request->maoni;

            // getting data to save
            $wanafamilia = $request->wanafamilia;

            foreach($wanafamilia as $key => $row){

                //checking if mwanafamilia exist in kikundi don't add him/her
                if(!(Wanakikundi::where('mwanafamilia',$row)->where('kikundi',$kikundi)->exists())){
                    $data_save = [
                        'mwanafamilia' => $row,
                        'kikundi' => $kikundi,
                        'maoni' =>$request->maoni,
                    ];
                    $success = Wanakikundi::create($data_save);
                    Kikundi::whereId($kikundi)->increment('idadi_ya_waumini',1);
                }
                else{
                    $success = false;
                    $count = "wapo";
                }
            }

            if($success != false){
                //tracking
                $jina = Kikundi::findOrFail($kikundi);
                $message = "Ongezo";
                $data = "Ongezo la wanachama $jina->jina_la_kikundi katika parokia";
                $this->setActivity1($message,$data);
                return response()->json(['success'=>["Wanachama wameongezwa kikamilifu.."]]);
            }

            elseif($count){
                return response()->json(['info'=>["Baadhi ya wanachama hawaongezwi kwakua ni wanachama tayari.."]]);
            }
            else{
                return response()->json(['success'=>["Wanachama hawajaongezwa jaribu tena.."]]);
            }
        }
    }

    //kikundi husika
    public function kikundi_husika($id){

        //getting a name of kikundi
        $data = Kikundi::findOrFail($id);

        $data_vikundi = Kikundi::get();

        $title = "Wanachama wa $data->jina_la_kikundi";

        $chama = $id;
        //getting wanakikundi based on id submitted
        $data_wanakikundi = Wanakikundi::where('wanakikundis.kikundi',$id)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','wanakikundis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->leftJoin('kikundis','kikundis.id','=','wanakikundis.kikundi')
        ->get(['wanakikundis.*','mwanafamilias.jina_kamili','mwanafamilias.mawasiliano','kikundis.jina_la_kikundi','mwanafamilias.id as mwanakikundi','familias.jina_la_jumuiya']);

        return view('backend.kikundi.wanakikundi',compact(['title','data_wanakikundi','data_vikundi','chama']));
    }

    //getting specific mwanakikundi data
    public function mwanakikundi_edit($id){
        $data = Wanakikundi::leftJoin('mwanafamilias','mwanafamilias.id','=','wanakikundis.mwanafamilia')
        ->leftJoin('kikundis','kikundis.id','=','wanakikundis.kikundi')
        ->select(['mwanafamilias.jina_kamili','wanakikundis.id as kikundi_id','kikundis.jina_la_kikundi','wanakikundis.maoni'])->findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //function to delete mwanakikundi
    public function mwanakikundi_destroy($id){
        //deleting data concerning mwanakikundi
        $data_delete = Wanakikundi::findOrFail($id);

        //confirming if we have data of that member
        if($data_delete == ""){
            return response()->json(['errors'=>["Hakuna taarifa za mwanachama jaribu tena.."]]);
        }
        else{
            //capture data deleting data now
            $mwanafamilia = Mwanafamilia::findOrFail($data_delete->mwanafamilia);
            $kikundi_data = Kikundi::findOrFail($data_delete->kikundi);

            //deleting and track the data
            if($data_delete->delete()){
                if(($mwanafamilia != "") && ($kikundi_data != "")){
                    $message = "Ufutaji";
                    $data = "Ufutaji wa mwanachama katika chama cha $kikundi_data->jina_la_kikundi katika parokia";
                    $this->setActivity1($message,$data);
                }
                else{
                    $message = "Ufutaji";
                    $data = "Ufutaji wa mwanachama katika chama parokiani";
                    $this->setActivity1($message,$data);
                }

                //minimizing number of members in kikundi
                Kikundi::whereId($kikundi_data->id)->where('idadi_ya_waumini','!=',0)->decrement('idadi_ya_waumini',1);

                return response()->json(['success'=>"Mwanachama amefutwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kufuta mwanachama"]]);
            }
        }
    }

    //mwanakikundi update
    public function mwanakikundi_update(Request $request){
        //getting the existing data first
        $existing = Wanakikundi::findOrFail($request->hidden_id);

        if($existing == ""){
            return response()->json(['errors'=>"Hakuna taarifa kwa chama kilichojazwa"]);
        }
        else{
            //continuing with validation and updating
            $must = [
                'kikundi' => 'required',
            ];

            $error = Validator::make($request->all(),$must);

            if($error->fails()){
                return response()->json(['errors'=>["Hakikisha umechagua chama husika"]]);
            }
            else{
                $data_update = [
                    'maoni' => $request->maoni,
                    'kikundi' => $request->kikundi,
                ];

                //update now
                $success = Wanakikundi::whereId($request->hidden_id)->update($data_update);

                if($success){
                    //minimizing or maximize the number of members
                    if($existing->kikundi !== $request->kikundi){
                        //incrementing the newly group
                        Kikundi::where('id',$request->kikundi)->increment('idadi_ya_waumini',1);

                        //decrement the old group
                        Kikundi::where('id',$existing->kikundi)->decrement('idadi_ya_waumini',1);
                    }

                    //getting the data for kikundi
                    $kikundi_data = Kikundi::findOrFail($existing->kikundi);

                    //tracking
                    $message = "Badiliko";
                    $data = "Badiliko la mwanachama katika chama cha $kikundi_data->jina_la_kikundi katika parokia";
                    return response()->json(['success'=>"Taarifa zimebadilishwa kikamilifu.."]);
                }

                else{
                    return response()->json(['errors'=>"Imeshindikana kubadili taarifa jaribu tena.."]);
                }
            }
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new Kikundi;
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }

    //another activity
    private function setActivity1($message,$data){
        $model = new Wanakikundi;
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
