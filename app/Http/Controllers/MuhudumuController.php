<?php

namespace App\Http\Controllers;

use App\CentreDetail;
use App\Jumuiya;
use App\Kanda;
use App\Kikundi;
use App\Mwanafamilia;
use App\RatibaYaMisa;
use App\SadakaZaMisa;
use App\Wahudumu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MuhudumuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $request->validate([
            'type' => ['required', 'max:255', 'string', Rule::in(['Msimamizi','Msoma Somo', 'Msoma Matangazo', 'Msoma Matangazo'])],
            'wahudumu-type' => ['required', 'max:255', 'string', Rule::in(['pandre','shirika', 'jumuiya', 'kanda', 'mwanajumuiya'])],
            'description' => ['required', 'max:6000', 'string'],
            'jumuiya' => ['required_if:wahudumu-type,jumuiya', 'numeric','exists:jumuiyas,id'],
            'kanda' => ['required_if:wahudumu-type,kanda', 'numeric','exists:kandas,id'],
            'shirika' => ['required_if:wahudumu-type,shirika', 'numeric','exists:kikundis,id'],
            'mwanafamila' => ['required_if:wahudumu-type,mwanajumuiya', 'string','exists:mwanafamilias,namba_utambulisho'],
            'ratiba_ya_misa_id' => ['required', 'numeric', 'exists:ratiba_ya_misas,id']
        ]);

        $misaable_tpye = null;
        $misaable_id = null;

        if($request['wahudumu-type'] == 'jumuiya'){
            $misaable_tpye = Jumuiya::class;
            $misaable_id = $request['jumuiya'];
        }

        if($request['wahudumu-type'] == 'kanda'){
            $misaable_tpye = Kanda::class;
            $misaable_id = $request['kanda'];
        }

        if($request['wahudumu-type'] == 'shirika'){
            $misaable_tpye = Kikundi::class;
            $misaable_id = $request['shirika'];
        }

         if($request['wahudumu-type'] == 'mwanajumuiya'){
             $misaable_tpye = Mwanafamilia::class;
             $misaable_id = (Mwanafamilia::where('namba_utambulisho', $request['mwanafamila'])->first())->id;
         }

   Wahudumu::create([
       'description' => $request['description'],
       'type' => $request['type'],
       'ratiba_ya_misa_id' => $request['ratiba_ya_misa_id'],
       'muhudumuable_type' => $misaable_tpye,
       'muhudumuable_id' => $misaable_id,
   ]);


        return response()->json(['success'=>"Wahudumu imesajiliwa kikamilifu."]);
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    }
}
