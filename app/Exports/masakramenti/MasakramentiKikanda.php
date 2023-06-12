<?php

namespace App\Exports\masakramenti;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Jumuiya;
use App\Familia;
use App\Kikundi;
use App\Mwanafamilia;
use App\Kanda;
use App\SahihishaKanda;
use App\User;

class MasakramentiKikanda implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
   
    public function view(): View
    {

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

        return view('backend.reports.masakramenti_kikanda',compact(['title','kanda','familia','wanafamilia','jumuiya','takwimu_za_kiroho','jumuiya_zote','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
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
