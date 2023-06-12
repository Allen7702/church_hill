<?php

namespace App\Exports\familia;

use App\Familia;
use App\Jumuiya;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OrodhaFamiliaKijumuiya implements FromView
{
    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
        $title = "Idadi ya Familia kijumuiya";
        $data_jumuiya = Jumuiya::latest()->get();
        return view('backend.reports.familia_orodha_kijumuiya',compact(['title','data_jumuiya']));
    }
}
