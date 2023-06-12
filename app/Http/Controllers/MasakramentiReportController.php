<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use PDF;
use App\User;
use App\Jumuiya;
use App\Familia;
use App\Kikundi;
use App\Mwanafamilia;
use App\Kanda;
use App\Exports\masakramenti\MasakramentiJumla;
use App\Exports\masakramenti\MasakramentiKikanda;
use App\Exports\masakramenti\MasakramentiKandaHusika;
use App\Exports\masakramenti\MasakramentiJumuiya;
use App\Exports\masakramenti\MasakramentiJumuiyaHusika;
use App\SahihishaKanda;

class MasakramentiReportController extends Controller
{
    //masakramenti jumla
    public function masakramenti_kijumla_pdf(){

        $title = "Ripoti ya taarifa za masakramenti jumla";
        $data_summary = $this->getSummary();
        $kanda = $data_summary['kanda'];
        $jumuiya = $data_summary['jumuiya'];
        $familia = $data_summary['familia'];
        $vyakitume = $data_summary['vyakitume'];

        $takwimu_za_kiroho = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->groupBy('familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id','mwanafamilias.familia as familia_id','familias.jina_la_jumuiya']);

        // other statics to be used in bottom of the table as table footer;
        $wanajumuiya = Mwanafamilia::count();
        $waliobatizwa = Mwanafamilia::where('ubatizo','tayari')->count();
        $kipaimara = Mwanafamilia::where('kipaimara','tayari')->count();
        $ndoa = Mwanafamilia::where('ndoa','tayari')->count();
        $ekaristi = Mwanafamilia::where('ekaristi','anapokea')->count();
        $komunio = Mwanafamilia::where('komunio','tayari')->count();
        $jumuiya_zote = Jumuiya::count(); 

        $pdf = PDF::setPaper('a4')->loadView('backend.reports.masakramenti_jumla',compact(['title','vyakitume','kanda','jumuiya','familia','takwimu_za_kiroho','wanajumuiya','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
        return $pdf->stream("masakramenti_jumla.pdf");
    }

    //handling the pdf for kanda
  
    public function masakramenti_kikanda_pdf(){
        
        $sahihisha_kanda=SahihishaKanda::first();
        $title = "Ripoti ya taarifa za masakramenti $sahihisha_kanda->name";

        $data_summary = $this->getSummary();
        $kanda = $data_summary['kanda'];
        $jumuiya = $data_summary['jumuiya'];
        $familia = $data_summary['familia'];
        $wanafamilia = $data_summary['wanafamilia'];

        //takwimu za kiroho kikanda
        $takwimu_za_kiroho = Kanda::get();

        //other summaries to be used in tbale footer
        $wanajumuiya = Mwanafamilia::count();
        $waliobatizwa = Mwanafamilia::where('ubatizo','tayari')->count();
        $kipaimara = Mwanafamilia::where('kipaimara','tayari')->count();
        $ndoa = Mwanafamilia::where('ndoa','tayari')->count();
        $ekaristi = Mwanafamilia::where('ekaristi','anapokea')->count();
        $komunio = Mwanafamilia::where('komunio','tayari')->count();
        $jumuiya_zote = Jumuiya::count(); 

        $pdf = PDF::setPaper('a4')->loadView('backend.reports.masakramenti_kikanda',compact(['title','kanda','familia','wanafamilia','jumuiya','takwimu_za_kiroho','jumuiya_zote','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
        return $pdf->stream("masakramenti_kikanda.pdf");
    }

    //function to handle the details for kanda husika report
    public function masakramenti_kanda_husika_pdf($id){

        $jina_la_kanda = $id;

        $title = "Ripoti ya taarifa za masakramenti $jina_la_kanda";

        $takwimu_za_kiroho = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->groupBy('jumuiyas.jina_la_jumuiya')
        ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya','jumuiyas.idadi_ya_familia','familias.jina_la_familia','jumuiyas.id as jumuiya_id']);

        //processing the footer details for the kanda husika
        $jumuiya_zote = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->count();

        $wanajumuiya_wote = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $waliobatizwa = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->where('mwanafamilias.ubatizo','tayari')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $kipaimara = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->where('mwanafamilias.kipaimara','tayari')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $ndoa = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->where('mwanafamilias.ndoa','tayari')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $komunio = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->where('mwanafamilias.komunio','tayari')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $ekaristi = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->where('mwanafamilias.ekaristi','anapokea')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $pdf = PDF::setPaper('a4')->loadView('backend.reports.masakramenti_kanda_husika',compact(['title','takwimu_za_kiroho','jumuiya_zote','wanajumuiya_wote','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
        return $pdf->stream("masakramenti_kanda_$jina_la_kanda.pdf");
    }

    //function to handle the masakramenti jumuiya pdf
    public function masakramenti_jumuiya_pdf(){
        //setting the title
        $title = "Ripoti ya taarifa za masakramenti kijumuiya";

        //calling the summary
        $data_summary = $this->getSummary();
        
        $jumuiya = $data_summary['jumuiya'];
        $familia = $data_summary['familia'];
        $wanafamilia = $data_summary['wanafamilia'];
        $viongozi = $data_summary['viongozi'];

        $takwimu_za_kiroho = Jumuiya::leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->groupBy('jumuiyas.jina_la_jumuiya')
        ->get(['jumuiyas.jina_la_jumuiya','familias.id as familia_id','jumuiyas.id','jumuiyas.idadi_ya_familia']);

        // return $takwimu_za_kiroho;
        //other summaries to be used in tbale footer
        $familia_zote = Familia::count();
        $waliobatizwa = Mwanafamilia::where('ubatizo','tayari')->count();
        $kipaimara = Mwanafamilia::where('kipaimara','tayari')->count();
        $ndoa = Mwanafamilia::where('ndoa','tayari')->count();
        $ekaristi = Mwanafamilia::where('ekaristi','anapokea')->count();
        $komunio = Mwanafamilia::where('komunio','tayari')->count();
        $jumuiya_zote = Jumuiya::count(); 

        $pdf = PDF::setPaper('a4')->loadView('backend.reports.masakramenti_jumuiya',compact(['title','takwimu_za_kiroho','jumuiya','familia','wanafamilia','viongozi','familia_zote','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
        return $pdf->stream("masakramenti_jumuiya.pdf");   
    }

    //handling the report for masakramenti jumuiya husika pdf
    public function masakramenti_jumuiya_husika_pdf($id){
        //getting details
        $jina_la_jumuiya = $id;
            
        $title = "Ripoti ya taarifa za masakramenti jumuiya ya $jina_la_jumuiya";

        $takwimu_za_kiroho = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
        ->get(['mwanafamilias.jina_kamili','mwanafamilias.kipaimara','mwanafamilias.mawasiliano','mwanafamilias.id','mwanafamilias.ndoa','mwanafamilias.ubatizo','mwanafamilias.komunio','mwanafamilias.ekaristi']);

        if($takwimu_za_kiroho->isEmpty()){
            Alert::toast("Hakuna taarifa za kiroho kwa jumuiya ya $jina_la_jumuiya","info");
            return redirect()->back();
        }
        
        $jumuiya = $jina_la_jumuiya;

        //handling the details for table footer
        $wanajumuiya_wote = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
        ->count();

        $ndoa = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
        ->where('mwanafamilias.ndoa','tayari')
        ->count();

        $ubatizo = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
        ->where('mwanafamilias.ubatizo','tayari')
        ->count();

        $kipaimara = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
        ->where('mwanafamilias.kipaimara','tayari')
        ->count();

        $komunio = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
        ->where('mwanafamilias.komunio','tayari')
        ->count();

        $ekaristi = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
        ->where('mwanafamilias.ekaristi','anapokea')
        ->count();

        $pdf = PDF::setPaper('a4')->loadView('backend.reports.masakramenti_jumuiya_husika',compact(['title','takwimu_za_kiroho','jumuiya','wanajumuiya_wote','ndoa','ubatizo','kipaimara','ekaristi','komunio']));
        return $pdf->stream("masakramenti_jumuiya_$jina_la_jumuiya.pdf");   
    }

    //handling the excel for masakramenti kijumla
    public function masakramenti_kijumla_excel(){
        return Excel::download(new MasakramentiJumla(), "masakramenti_jumla.xlsx");
    }

    //handling the excel for kanda excel
    public function masakramenti_kikanda_excel(){
        return Excel::download(new MasakramentiKikanda(), "masakramenti_kikanda.xlsx");
    }

    //function to handle report for excel for kanda husika
    public function masakramenti_kanda_husika_excel($id){
        return Excel::download(new MasakramentiKandaHusika($id), "masakramenti_kanda_$id.xlsx");
    }

    //function to handle the masakramenti jumuiya excel 
    public function masakramenti_jumuiya_excel(){
        return Excel::download(new MasakramentiJumuiya(),"masakramenti_jumuiya.xlsx");
    }

    //handling the report for masakramenti jumuiya husika excel
    public function masakramenti_jumuiya_husika_excel($id){
        return Excel::download(new MasakramentiJumuiyaHusika($id),"masakramenti_jumuiya_$id.xlsx");
    }

    private function getSummary(){
        //to be used by most methods
        $kanda = Kanda::count();
        $jumuiya = Jumuiya::count();
        $familia = Familia::count();
        $wanafamilia = Mwanafamilia::count();
        $viongozi = User::whereNotNull('cheo')->where('ngazi','!=','administrator')->count();
        $vyakitume = Kikundi::count();

        $data_summary = [
            'kanda' => $kanda,
            'jumuiya' => $jumuiya,
            'familia' => $familia,
            'wanafamilia' => $wanafamilia,
            'viongozi' => $viongozi,
            'vyakitume' => $vyakitume,
        ];

        return $data_summary;
    }
}
