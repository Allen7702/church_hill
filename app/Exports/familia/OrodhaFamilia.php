<?php

namespace App\Exports\familia;

use App\Familia;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OrodhaFamilia implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya orodha ya familia";
        $data_familia = Familia::orderBy('jina_la_jumuiya')->get();
        return view('backend.reports.familia_orodha',compact(['title','data_familia']));
    }
}
