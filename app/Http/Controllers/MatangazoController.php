<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use App\Matangazo;
use Validator;

class MatangazoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_matangazo = Matangazo::latest()->get();
        return view('backend.wavuti.matangazo',compact(['data_matangazo']));
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
            'kichwa' => 'required|unique:matangazos',
            'tarehe' => 'required',
            'maelezo' => 'required',
            'uchapishaji' => 'required',
            'alama' => 'required',
        ];

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors'=>["Hakikisha umejaza kichwa cha tangazo,alama, tarehe, maelezo, na uchapishaji.. kichwa kisijirudie.."]]);
        }

        //getting the file details
        $files = $request->file('attachment');

        if($files != ""){

            //validating files
            $files_rules = [
                'attachment' => 'required|mimes:png,gif,jpeg,jpg,pdf,xlsx,doc,docx,xls|max:5192',
            ];

            $file_errors = Validator::make($request->all(),$files_rules);

            if($file_errors->fails()){
                return response()->json(['errors'=>"Faili linapaswa kuwa ya aina ya PNG,JPEG,JPG, PDF, DOC, XLSX, XLS isiyozidi 5MB.."]);
            }

            $file_destination = public_path('/uploads/images/');
            $attachment_file = time().uniqid().".".$files->getClientOriginalExtension();//to save in db
            $files->move($file_destination, $attachment_file);
        }

        else{
            $attachment_file = "NULL";
        }

        $data_save = [
            'tarehe' => $request->tarehe,
            'uchapishaji' => $request->uchapishaji,
            'kichwa' => $request->kichwa,
            'maelezo' => $request->maelezo,
            'attachment' => $attachment_file,
            'alama' => $request->alama,
            'imewekwa_na' => auth()->user()->email,
        ];

        $success = Matangazo::create($data_save);

        if($success){
            return response()->json(['success'=>["Tangazo limerekodiwa kikamilifu.."]]);
        }
        else{
            return response()->json(['errors'=>["Imeshindikana kurekodi tangazo.."]]);
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
        $data = Matangazo::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Matangazo $model)
    {
        //getting old data
        $existing = $model::findOrFail($request->hidden_id);

        //getting old file
        $old_file = $existing->attachment;

        //first validate other data
        $rules = [
            'kichwa' => 'sometimes|unique:matangazos,kichwa,'.$existing->id,
            'tarehe' => 'required',
            'maelezo' => 'required',
            'alama' =>'required',
            'uchapishaji' => 'required',
        ];

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors'=>["Hakikisha umejaza kichwa cha tangazo,alama, tarehe, maelezo, na uchapishaji.. kichwa kisijirudie.."]]);
        }

        else{

            //getting the file details
            $files = $request->file('attachment');

            if($files != ""){

                //validating files
                $files_rules = [
                    'attachment' => 'required|mimes:png,gif,jpeg,jpg,pdf,xlsx,doc,docx,xls|max:5192',
                ];

                $file_errors = Validator::make($request->all(),$files_rules);

                if($file_errors->fails()){
                    return response()->json(['errors'=>"Faili linapaswa kuwa ya aina ya PNG,JPEG,JPG, PDF, DOC, XLSX, XLS isiyozidi 5MB.."]);
                }

                $file_destination = public_path('/uploads/images/');
                $attachment_file = time().uniqid().".".$files->getClientOriginalExtension();//to save in db
                $files->move($file_destination, $attachment_file);

                //deleting the old file
                $old_file = public_path('/uploads/images/').$old_file;
                @unlink($old_file);
            }

            else{
                $attachment_file = $old_file;
            }

            //data to update
            $data_update = [
                'tarehe' => $request->tarehe,
                'uchapishaji' => $request->uchapishaji,
                'kichwa' => $request->kichwa,
                'maelezo' => $request->maelezo,
                'attachment' => $attachment_file,
                'alama' => $request->alama,
                'imewekwa_na' => auth()->user()->email,
            ];

            $success = $model::whereId($request->hidden_id)->update($data_update);

            if($success){
                return response()->json(['success'=>["Taarifa za tangazo zimebadilishwa.."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili taarifa za tangazo.."]]);
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
        $data = Matangazo::findOrFail($id);
        $attachment = $data->attachment;

        if($data->delete()){

            if($attachment != ""){
                $files = public_path('/uploads/images/').$attachment;
                //deleting the old image
                @unlink($files);
            }

            return response()->json(['success'=>["Tangazo limefutwa kikamilifu.."]]);
        }
        else{
            return response()->json(['errors'=>["Imeshindikana kufuta tangazo.."]]);
        }
    }
}
