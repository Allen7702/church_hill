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

class MasakramentiJumuiya implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
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

        return view('backend.reports.masakramenti_jumuiya',compact(['title','takwimu_za_kiroho','jumuiya','familia','wanafamilia','viongozi','familia_zote','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
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
