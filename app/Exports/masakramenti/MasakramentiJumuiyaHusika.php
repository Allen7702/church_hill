<?php

namespace App\Exports\masakramenti;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Jumuiya;
use App\Familia;
use App\Kikundi;
use App\Mwanafamilia;
use App\Kanda;

class MasakramentiJumuiyaHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        //getting details
        $jina_la_jumuiya = $this->id;
            
        $title = "Ripoti ya taarifa za masakramenti jumuiya ya $jina_la_jumuiya";

        $takwimu_za_kiroho = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
        ->get(['mwanafamilias.jina_kamili','mwanafamilias.kipaimara','mwanafamilias.mawasiliano','mwanafamilias.id','mwanafamilias.ndoa','mwanafamilias.ubatizo','mwanafamilias.komunio','mwanafamilias.ekaristi']);
        
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

        return view('backend.reports.masakramenti_jumuiya_husika',compact(['title','takwimu_za_kiroho','jumuiya','wanajumuiya_wote','ndoa','ubatizo','kipaimara','ekaristi','komunio']));
    }

    private function getSummary(){
        //to be used by most methods
        $kanda = Kanda::count();
        $jumuiya = Jumuiya::count();
        $familia = Familia::count();
        $wanafamilia = Mwanafamilia::count();
        $viongozi = Mwanafamilia::whereNotNull('cheo')->count();
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
