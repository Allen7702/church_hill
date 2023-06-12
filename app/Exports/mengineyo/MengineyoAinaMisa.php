<?php

namespace App\Exports\mengineyo;

use App\AinaZaMisa;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MengineyoAinaMisa implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya aina za misa";
        $data_misa = AinaZaMisa::latest()->get();
        return view('backend.reports.mengineyo_aina_misa',compact(['title','data_misa']));
    }
}
