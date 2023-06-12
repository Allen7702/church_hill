<?php

namespace App\Exports\familia;

use App\Mwanafamilia;
use App\Familia;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OrodhaWanafamiliaHusika implements FromView
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
        $data_familia = Familia::findOrFail($this->id);
        $title = "Ripoti ya wanafamilia wa familia ya $data_familia->jina_la_familia";
        $data_wanafamilia = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.id',$this->id)
        ->orderBy('familias.jina_la_jumuiya')
        ->get(['mwanafamilias.*','familias.jina_la_jumuiya']);

        return view('backend.reports.wanafamilia_orodha',compact(['title','data_wanafamilia']));
    }
}
