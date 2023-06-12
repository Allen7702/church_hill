<?php

namespace App\Http\Controllers;

use App\SahihishaKanda;
use Illuminate\Http\Request;
use Alert;
use Validator;

class SahihishaController extends Controller
{
    public function index(){

        $title='SAHIHISHA MFUMO';
         $sahihisha_kanda=SahihishaKanda::first();
        return view('backend.sahihisha.index',compact('title','sahihisha_kanda'));
    }

    public function update_sahihisha(Request $request)
    {
        $sahihisha_kanda=SahihishaKanda::first();

        $rules = [
            'kanda' => 'required',
        ];

        $errors = Validator::make($request->all(),$rules);

        if($errors->fails()){
            Alert::info("Tafadhali kisanduku akiwezi kua wazi");
            return back();
        }

        if(is_null($sahihisha_kanda)){
            SahihishaKanda::create([
                'center_detail_id'=>1,
                'name'=>$request->kanda
            ]);
            
        }else{
            $sahihisha_kanda->update([
                'name'=>$request->kanda
            ]);
        }
        Alert::toast("Taarifa zimebadilishwa kikamilifu.","success");
        return back();
    }
}
