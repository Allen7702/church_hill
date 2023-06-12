<?php

namespace App\Http\Controllers;

use App\Kikundi;
use App\RatibaYaMisa;
use Illuminate\Http\Request;
use App\Matukio;
use App\Kanda;
use App\Jumuiya;
use App\HistoriaLeo;
use App\Matangazo;
use Illuminate\Support\Facades\DB;

class WavutiController extends Controller
{
    //
    public function homepage(){
        //overall details of the church
        $mashirikas = Kikundi::latest()->limit(4)->get();
        $matukios = Matukio::latest()->where('uchapishaji','imechapishwa')->get();
        $kanda_zetu = Kanda::latest()->limit(4)->get();
        $jumuiya_zetu = Jumuiya::latest()->limit(3)->get();
        $matangazo = Matangazo::latest()->where('uchapishaji','imechapishwa')->limit(4)->get();
        $historia_leo = HistoriaLeo::latest()->whereMonth('tarehe','=',date('m-d'))->where('uchapishaji','imechapishwa')->limit(4)->get();
        return view('frontend.nyumbani',compact(['matukios','kanda_zetu','jumuiya_zetu','historia_leo','matangazo','mashirikas']));
    }

    public function kanda_zetu(){
        //overall details of the church
        $data_kanda = Kanda::latest()->get();
        $matukios = Matukio::latest()->where('uchapishaji','imechapishwa')->get();
        return view('frontend.kanda',compact(['matukios','data_kanda']));
    }

    public function jumuiya_zetu(){
        $data_jumuiya = Jumuiya::latest()->get();
        $matukios = Matukio::latest()->where('uchapishaji','imechapishwa')->get();
        return view('frontend.jumuiya',compact(['matukios','data_jumuiya']));
    }

    public function matangazo_yetu(){
        $data_matangazo = Matangazo::latest()->where('uchapishaji','imechapishwa')
                            ->where('alama', '!=', 'tukio')
                            ->get();
        $matukios = Matukio::latest()->where('uchapishaji','imechapishwa')->get();
        return view('frontend.matangazo',compact(['matukios','data_matangazo']));
    }

    public function historia_leo(){
        $data_historia = HistoriaLeo::where('uchapishaji','imechapishwa')->latest()->get();
        return view('frontend.historia',compact(['data_historia']));
    }

    public function mashirika_ya_kitume(){

        $data = Kikundi::all();
        $matukios = Matukio::latest()->where('uchapishaji','imechapishwa')->get();
        return view('frontend.kikundi',compact(['data', 'matukios']));
    }

    public function matukio(){
        $data = Matangazo::latest()->where('uchapishaji','imechapishwa')
            ->where('alama', 'tukio')
            ->get();
        $matukios = Matukio::latest()->where('uchapishaji','imechapishwa')->get();

        return view('frontend.matukio',compact(['data','matukios']));
    }

    public function misa(){
        $data = RatibaYaMisa::where(DB::raw('MONTH(date)'), now()->month)
            ->orderBy('created_at', 'DESC')->get();
        $matukios = Matukio::latest()->where('uchapishaji','imechapishwa')->get();
        return view('frontend.misa',compact(['data', 'matukios']));
    }
}
