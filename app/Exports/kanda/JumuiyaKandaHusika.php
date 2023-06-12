<?php

namespace App\Exports\kanda;

use App\Jumuiya;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class JumuiyaKandaHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($kanda){
        $this->kanda = $kanda;
    }

    public function view(): View
    {
        $kanda = $this->kanda;

        $title = "Orodha ya jumuiya $kanda";
        $data_jumuiya = Jumuiya::where('jina_la_kanda',$kanda)->get(); 
        return view('backend.reports.jumuiya_kanda_husika',compact(['title','data_jumuiya','kanda']));
    
    }
}
