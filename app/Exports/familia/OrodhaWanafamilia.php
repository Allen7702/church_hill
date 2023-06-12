<?php

namespace App\Exports\familia;

use App\Mwanafamilia;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OrodhaWanafamilia implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya orodha ya wanafamilia";
        $data_wanafamilia = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')->orderBy('familias.jina_la_jumuiya')->get(['mwanafamilias.*','familias.jina_la_jumuiya']);

        return view('backend.reports.wanafamilia_orodha',compact(['title','data_wanafamilia']));
    }
}
