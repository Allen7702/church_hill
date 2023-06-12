<?php

namespace App\Exports\masakramenti;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Jumuiya;
use App\Familia;
use App\Kikundi;
use App\Mwanafamilia;
use App\Kanda;
use App\User;

class MasakramentiJumla implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya taarifa za ma sakramenti jumla";
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

        return view('backend.reports.masakramenti_jumla',compact(['title','vyakitume','kanda','jumuiya','familia','takwimu_za_kiroho','wanajumuiya','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
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
