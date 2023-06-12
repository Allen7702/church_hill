<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MakundiRika;
use Validator;
use Auth;
use Str;

class MakundiRikaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //getting the data
        $title = "Makundi ya umri (rika)";
        $data_rika = MakundiRika::orderBy('umri_kuanzia','ASC')->get();
        return view('backend.mengineyo.makundi_rika',compact(['title','data_rika']));
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
            'rika' => 'required|unique:makundi_rikas',
            'umri_kuanzia' => 'required|unique:makundi_rikas',
            'umri_ukomo' => 'required|unique:makundi_rikas',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza sehemu muhimu, pia rika, umri wa kuanzia na ukomo visijirudie.."]);
        }
        else{
            //getting data
            $data_save = [
                'rika' => Str::ucfirst(strtolower($request->rika)),
                'umri_kuanzia' => $request->umri_kuanzia,
                'umri_ukomo' => $request->umri_ukomo,
                'maelezo' => $request->maelezo,
            ];

            $success = MakundiRika::create($data_save);

            if($success){
                
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la rika $request->rika";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Rika limesajiliwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kusajili rika"]]);
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
        //pulling data from makundi rika 
        $data = MakundiRika::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, MakundiRika $model)
    {
        //
        $existing = MakundiRika::findOrFail($request->hidden_id);

        //
        $must = [
            'rika' => 'required|unique:makundi_rikas,rika,'.$existing->id,
            'umri_kuanzia' => 'required|unique:makundi_rikas,umri_kuanzia,'.$existing->id,
            'umri_ukomo' => 'required|unique:makundi_rikas,umri_ukomo,'.$existing->id,
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza sehemu muhimu rika, umri wa kuanzia na ukomo visijirudie.."]);
        }
        else{

            //getting data
            $data_update = [
                'rika' => Str::ucfirst(strtolower($request->rika)),
                'umri_kuanzia' => $request->umri_kuanzia,
                'umri_ukomo' => $request->umri_ukomo,
                'maelezo' => $request->maelezo,
            ];

            $success = MakundiRika::whereId($request->hidden_id)->update($data_update);

            if($success){
                
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la rika $request->rika";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Rika limebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili rika"]]);
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
        $data = MakundiRika::findOrFail($id);
        $rika = $data->rika;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa rika $rika";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Rika limefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Rika halijafutwa jaribu tena.."]]);
        }
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new MakundiRika; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
