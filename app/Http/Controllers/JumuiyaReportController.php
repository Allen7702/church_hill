<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use PDF;
use App\Jumuiya;
use App\Mwanafamilia;
use App\Exports\jumuiya\JumuiyaOrodha;
use App\Exports\jumuiya\JumuiyaWanajumuiyaHusika;

class JumuiyaReportController extends Controller
{
    //
    public function jumuiya_orodha_pdf(){
        $title = "Orodha ya jumuiya";
        $data_jumuiya = Jumuiya::latest('jumuiyas.created_at')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->orderBy('jumuiyas.jina_la_jumuiya','ASC')
        ->groupBy('jumuiyas.jina_la_jumuiya')
        ->get(['jumuiyas.*','familias.id as familia_id']);

        $pdf = PDF::setPaper('a4')->loadView('backend.reports.jumuiya_orodha',compact(['title','data_jumuiya','title']));
        return $pdf->stream("jumuiya.pdf");
    }

    //function to handle wanajumuiya jumuiya husika
    public function jumuiya_wanajumuiya_husika_pdf($jumuiya){
        $title = "Orodha ya wanajumuiya $jumuiya";
    
        $data_wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('mwanafamilias.jina_kamili','ASC')
        ->get(['mwanafamilias.id','mwanafamilias.jina_kamili','mwanafamilias.cheo_familia','mwanafamilias.namba_utambulisho','mwanafamilias.mawasiliano']);

        $pdf = PDF::loadView('backend.reports.jumuiya_wanajumuiya_husika',compact(['title','data_wanajumuiya','jumuiya']));

        return $pdf->stream("wanajumuiya_wa_$jumuiya.pdf");
    }

    public function jumuiya_orodha_excel(){
        return Excel::download(new JumuiyaOrodha(),"jumuiya_orodha.xlsx");
    }

    //function to handle wanajumuiya jumuiya husika
    public function jumuiya_wanajumuiya_husika_excel($jumuiya){
        return Excel::download(new JumuiyaWanajumuiyaHusika($jumuiya),"wanajumuiya_$jumuiya.xlsx");
    }
}
