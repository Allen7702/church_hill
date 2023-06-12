<?php

namespace App\Http\Controllers;

use App\AinaZaMisa;
use App\Mwanafamilia;
use App\RatibaYaMisa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RatibaYaMisaController extends Controller
{

    public function index()
    {
        $wanafamilia = Mwanafamilia::latest('mwanafamilias.created_at')->leftJoin('familias','mwanafamilias.familia','=','familias.id')->get(['mwanafamilias.id', 'mwanafamilias.namba_utambulisho','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);
        return view('backend.mengineyo.ratiba_ya_misa',
            [
                'ratiba'=> RatibaYaMisa::orderBy('created_at','DESC')->get(),
                'wanafamilia' => $wanafamilia
                ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:6000', 'string'],
            'date' => ['required', 'date'],
            'aina_za_misa_id' => ['required', 'numeric', 'exists:aina_za_misas,id']
        ]);

        RatibaYaMisa::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'date' => Carbon::make($request['date'])->toDateTimeString(),
            'aina_za_misa_id' => $request['aina_za_misa_id']
        ]);

        return response()->json(['success'=>"Sadaka imesajiliwa kikamilifu."]);
    }


    public function show(RatibaYaMisa $ratiba_ya_misa)
    {
        return response()->json(['result'=> $ratiba_ya_misa]);
    }


    public function edit(RatibaYaMisa $ratiba_ya_misa)
    {

        return response()->json(['result'=> $ratiba_ya_misa]);
    }


    public function update(Request $request, RatibaYaMisa $ratiba_ya_misa)
    {
        $request->validate([
            'title' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:6000', 'string'],
            'date' => ['required', 'date'],
            'aina_za_misa_id' => ['required', 'numeric', 'exists:aina_za_misas,id']
        ]);

        $ratiba_ya_misa->update([
            'title' => $request['title'],
            'description' => $request['description'],
            'date' => Carbon::make($request['date'])->toDateTimeString(),
            'aina_za_misa_id' => $request['aina_za_misa_id']
        ]);

        return response()->json(['success'=>"Data imebadilishwa kikamilifu."]);
    }


    public function destroy(RatibaYaMisa $ratiba_ya_misa)
    {
        $ratiba_ya_misa->delete();

        return response()->json(['success'=>"Data imefutwa kikamilifu."]);
    }
}
