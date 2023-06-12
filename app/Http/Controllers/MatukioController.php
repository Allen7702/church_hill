<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use File;
use App\Matukio;
use Validator;

class MatukioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_matukio = Matukio::latest()->get();
        return view('backend.wavuti.matukio',compact(['data_matukio']));
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
        //first validate other data
        $rules = [
            'kichwa' => 'required|unique:matukios',
            'tarehe' => 'required',
            'maelezo' => 'required',
            'uchapishaji' => 'required',
        ];

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors'=>["Hakikisha umejaza kichwa cha tukio, tarehe, maelezo, na uchapishaji.. kichwa kisijirudie.."]]);
        }

        //getting the photo details
        $photo = $request->file('picha');

        if($photo != ""){

            //validating photo
            $photo_rules = [
                'picha' => 'required|mimes:png,gif,jpeg,jpg|max:2048',
            ];

            $photo_errors = Validator::make($request->all(),$photo_rules);

            if($photo_errors->fails()){
                return response()->json(['errors'=>"Picha inapaswa kuwa ya aina ya PNG,JPEG,JPG isiyozidi 2M.."]);
            }

            $photo_image = Image::make($photo);
            $photo_destination = public_path('/uploads/images/');
            $photo_file = time().uniqid().".".$photo->getClientOriginalExtension();//to save in db
            $photo->move($photo_destination, $photo_file);
        }

        $data_save = [
            'tarehe' => $request->tarehe,
            'uchapishaji' => $request->uchapishaji,
            'kichwa' => $request->kichwa,
            'maelezo' => $request->maelezo,
            'picha' => $photo_file,
            'imewekwa_na' => auth()->user()->email,
        ];

        $success = Matukio::create($data_save);

        if($success){
            return response()->json(['success'=>["Tukio limewekwa kikamilifu.."]]);
        }
        else{
            return response()->json(['errors'=>["Imeshindikana kuweka tukio.."]]);
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
        $data = Matukio::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Matukio $model)
    {
        //getting old data
        $existing = $model::findOrFail($request->hidden_id);

        //getting old photo
        $old_photo = $existing->picha;

        //first validate other data
        $rules = [
            'kichwa' => 'sometimes|unique:matukios,kichwa,'.$existing->id,
            'tarehe' => 'required',
            'maelezo' => 'required',
            'uchapishaji' => 'required',
        ];

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors'=>["Hakikisha umejaza kichwa cha tukio, tarehe, maelezo, na uchapishaji.. kichwa kisijirudie.."]]);
        }

        else{

            //getting the photo details
            $photo = $request->file('picha');

            if($photo != ""){

                //validating photo
                $photo_rules = [
                    'picha' => 'required|mimes:png,gif,jpeg,jpg|max:2048',
                ];

                $photo_errors = Validator::make($request->all(),$photo_rules);

                if($photo_errors->fails()){
                    return response()->json(['errors'=>"Picha inapaswa kuwa ya aina ya PNG,JPEG,JPG isiyozidi 2M.."]);
                }

                $photo_image = Image::make($photo);
                $photo_destination = public_path('/uploads/images/');
                $photo_file = time().uniqid().".".$photo->getClientOriginalExtension();//to save in db
                $photo->move($photo_destination, $photo_file);

                //deleting the old image
                $old_image = public_path('/uploads/images/').$old_photo;
                @unlink($old_image);
            }

            else{
                $photo_file = $old_photo;
            }

            //data to update
            $data_update = [
                'tarehe' => $request->tarehe,
                'uchapishaji' => $request->uchapishaji,
                'kichwa' => $request->kichwa,
                'maelezo' => $request->maelezo,
                'picha' => $photo_file,
                'imewekwa_na' => auth()->user()->email,
            ];

            $success = $model::whereId($request->hidden_id)->update($data_update);

            if($success){
                return response()->json(['success'=>["Taarifa za tukio zimebadilishwa.."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili taarifa za tukio.."]]);
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
        //
        $data = Matukio::findOrFail($id);
        $picha = $data->picha;

        if($data->delete()){
            $image = public_path('/uploads/images/').$picha;
            //deleting the old image
            @unlink($image);

            return response()->json(['success'=>["Tukio limefutwa kikamilifu.."]]);
        }
        else{
            return response()->json(['errors'=>["Imeshindikana kufuta tukio.."]]);
        }
    }
}
