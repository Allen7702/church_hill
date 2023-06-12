<?php

namespace App\Exports\kanda;

use App\Kanda;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class KandaOrodha implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya orodha ya ".get_kanda()->name;
        $data_kanda = Kanda::orderBy('jina_la_kanda')->get();
        return view('backend.reports.kanda_orodha',compact(['title','data_kanda']));
    }
}
