<?php

namespace App\Exports\jumuiya;

use App\Jumuiya;
use App\Mwanafamilia;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class JumuiyaWanajumuiyaHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($jumuiya){
        $this->jumuiya = $jumuiya;
    }

    public function view(): View
    {
        $jumuiya = $this->jumuiya;

        $title = "Orodha ya wanajumuiya $jumuiya";

        $data_wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('mwanafamilias.jina_kamili','ASC')
        ->get(['mwanafamilias.id',
            'mwanafamilias.jina_kamili',
            'mwanafamilias.cheo_familia',
            'mwanafamilias.namba_utambulisho',
            'mwanafamilias.mawasiliano',
            'mwanafamilias.jinsia',
            'mwanafamilias.ubatizo',
            'mwanafamilias.ekaristi',
            'mwanafamilias.kipaimara',
            'mwanafamilias.komunio',
            'mwanafamilias.ndoa',
            'mwanafamilias.aina_ya_ndoa',
            'mwanafamilias.taaluma',
            'mwanafamilias.dob',
            'mwanafamilias.namba_ya_cheti',
            'mwanafamilias.parokia_ya_ubatizo',
            'mwanafamilias.jimbo_la_ubatizo',

            ]);

        return view('backend.reports.jumuiya_wanajumuiya_husika',compact(['title','data_wanajumuiya','jumuiya']));
    }
}
