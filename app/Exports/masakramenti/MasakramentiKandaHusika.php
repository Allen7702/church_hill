<?php

namespace App\Exports\masakramenti;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Jumuiya;
use App\Familia;
use App\Kikundi;
use App\Mwanafamilia;
use App\Kanda;

class MasakramentiKandaHusika implements FromView
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
        //setting title
        $jina_la_kanda = $this->id;
        $title = "Ripoti ya taarifa za masakramenti kanda ya $jina_la_kanda";

        $takwimu_za_kiroho = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->groupBy('jumuiyas.jina_la_jumuiya')
        ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya','jumuiyas.idadi_ya_familia','familias.jina_la_familia','jumuiyas.id as jumuiya_id']);

        // return $takwimu_za_kiroho;

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

        return view('backend.reports.masakramenti_kanda_husika',compact(['title','takwimu_za_kiroho','jumuiya_zote','wanajumuiya_wote','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
    
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
