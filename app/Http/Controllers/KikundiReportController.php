<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use PDF;
use App\Kikundi;
use App\Wanakikundi;
use App\Exports\vyama\VyamaKitume;
use App\Exports\vyama\ChamaKitume;

class KikundiReportController extends Controller
{
    //
    public function vyama_vya_kitume_pdf(){
        $title = "Orodha ya vyama vya kitume";
        $data_vyama = Kikundi::orderBy('jina_la_kikundi')->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.vyama_vya_kitume',compact(['title','data_vyama']));
        return $pdf->stream("vyama_vya_kitume.pdf");
    }

    public function chama_cha_kitume_pdf($id){
        //getting a name of kikundi
        $data = Kikundi::findOrFail($id);

        $title = "Wanachama wa chama cha ($data->jina_la_kikundi)";

        $chama = $id;
        //getting wanakikundi based on id submitted
        $data_wanachama = Wanakikundi::where('wanakikundis.kikundi',$id)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','wanakikundis.mwanafamilia')
        ->leftJoin('kikundis','kikundis.id','=','wanakikundis.kikundi')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->get(['wanakikundis.*','mwanafamilias.jina_kamili','mwanafamilias.mawasiliano','kikundis.jina_la_kikundi','familias.jina_la_jumuiya','familias.jina_la_familia']);

        $pdf = PDF::setPaper('a4')->loadView('backend.reports.chama_cha_kitume',compact(['title','data_wanachama']));
        return $pdf->stream("chama_cha_kitume.pdf");
    }

    public function vyama_vya_kitume_excel(){
        return Excel::download(new VyamaKitume(),"vyama_vya_kitume.xlsx");
    }

    public function chama_cha_kitume_excel($id){
        return Excel::download(new ChamaKitume($id),"chama_cha_kitume.xlsx");
    }
    
}
