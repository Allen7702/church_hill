<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Familia;
use App\Mwanafamilia;
use App\Jumuiya;
use App\Exports\familia\OrodhaFamilia;
use App\Exports\familia\OrodhaFamiliaKijumuiya;
use App\Exports\familia\OrodhaFamiliaJumuiyaHusika;
use App\Exports\familia\OrodhaWanafamilia;
use App\Exports\familia\OrodhaWanafamiliaHusika;
use PDF;
use Excel;

class FamiliaReportController extends Controller
{
    //
    public function familia_orodha_pdf(){
        $title = "Orodha ya familia";
        $data_familia = Familia::orderBy('jina_la_jumuiya')->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.familia_orodha',compact(['title','data_familia']));
        return $pdf->stream("familia.pdf");
    }

    public function wanafamilia_orodha_pdf(){
        $title = "Orodha ya wanafamilia";
        $data_wanafamilia = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')->orderBy('familias.jina_la_jumuiya')->get(['mwanafamilias.*','familias.jina_la_jumuiya']);

        $pdf = PDF::setPaper('a4','landscape')->loadView('backend.reports.wanafamilia_orodha',compact(['title','data_wanafamilia']));
        return $pdf->stream("wanafamilia.pdf");
    }

    public function wanafamilia_husika_pdf($id){
        $data_familia = Familia::findOrFail($id);
        $title = "Wanafamilia wa familia ya $data_familia->jina_la_familia";
        $data_wanafamilia = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.id',$id)
        ->orderBy('familias.jina_la_jumuiya')
        ->get(['mwanafamilias.*','familias.jina_la_jumuiya']);

        $pdf = PDF::setPaper('a4','landscape')->loadView('backend.reports.wanafamilia_orodha',compact(['title','data_wanafamilia']));
        return $pdf->stream("wanafamilia.pdf");
    }

    public function familia_orodha_kijumuiya_pdf(){
        $title = "Idadi ya Familia kijumuiya";
        $data_jumuiya = Jumuiya::latest()->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.familia_orodha_kijumuiya',compact(['title','data_jumuiya']));
        return $pdf->stream("familia_orodha_kijumuiya.pdf");
    }

    public function familia_orodha_jumuiya_husika_pdf($id){
        $title = "Orodha ya familia jumuiya ya $id";
        $data_familia = Familia::latest()->where('familias.jina_la_jumuiya',$id)
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->get(['mwanafamilias.mawasiliano','familias.*']);

        $pdf = PDF::setPaper('a4')->loadView('backend.reports.familia_orodha_jumuiya_husika',compact(['title','data_familia']));
        return $pdf->stream("familia_orodha_jumuiya_$id.pdf");
    }

    public function familia_orodha_excel(){
        return Excel::download(new OrodhaFamilia(),"familia.xlsx");
    }

    public function wanafamilia_orodha_excel(){
        return Excel::download(new OrodhaWanafamilia(),"wanafamilia.xlsx");
    }

    public function wanafamilia_husika_excel($id){
        return Excel::download(new OrodhaWanafamiliaHusika($id),"wanafamilia.xlsx");
    }

    public function familia_orodha_kijumuiya_excel(){
        return Excel::download(new OrodhaFamiliaKijumuiya(),"familia_orodha_kijumuiya.xlsx");
    }

    public function familia_orodha_jumuiya_husika_excel($id){
        return Excel::download(new OrodhaFamiliaJumuiyaHusika($id),"familia_jumuiya_husika.xlsx");
    }

}
