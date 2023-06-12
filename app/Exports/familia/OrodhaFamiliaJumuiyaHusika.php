<?php

namespace App\Exports\familia;

use App\Familia;
use App\Jumuiya;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OrodhaFamiliaJumuiyaHusika implements FromView
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
        $id = $this->id;
        $title = "Orodha ya familia jumuiya ya $id";
        $data_familia = Familia::latest()->where('familias.jina_la_jumuiya',$id)
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->get(['mwanafamilias.mawasiliano','familias.*']);

        return view('backend.reports.familia_orodha_jumuiya_husika',compact(['title','data_familia']));
    }
}
