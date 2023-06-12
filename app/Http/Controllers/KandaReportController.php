<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kanda;
use App\Jumuiya;
use Excel;
use PDF;
use App\Exports\kanda\KandaOrodha;
use App\Exports\kanda\JumuiyaKandaHusika;

class KandaReportController extends Controller
{
    //
    public function kanda_orodha_pdf(){
        $title = "Orodha ya kanda";
        $data_kanda = Kanda::orderBy('jina_la_kanda')->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.kanda_orodha',compact(['title','data_kanda']));
        return $pdf->stream("kanda_orodha.pdf");
    }

    //function to handle the list of jumuiya in specific kanda
    public function jumuiya_kanda_husika_pdf($kanda){
        $title = "Orodha ya jumuiya $kanda";
        $data_jumuiya = Jumuiya::where('jina_la_kanda',$kanda)->get(); 
        $pdf = PDF::loadView('backend.reports.jumuiya_kanda_husika',compact(['title','data_jumuiya','kanda']));
        return $pdf->stream("jumuiya_kanda_$kanda.pdf");
    }

    public function kanda_orodha_excel(){
        return Excel::download(new KandaOrodha(),"orodha_kanda.xlsx");
    }

    //function to handle the list of jumuiya in specific kanda
    public function jumuiya_kanda_husika_excel($kanda){
        return Excel::download(new JumuiyaKandaHusika($kanda),"jumuiya_kanda_$kanda.xlsx");
    }
}
