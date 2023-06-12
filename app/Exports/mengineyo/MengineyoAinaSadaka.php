<?php

namespace App\Exports\mengineyo;

use App\AinaZaMisa;
use App\AinaZaSadaka;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MengineyoAinaSadaka implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya aina za sadaka";
        $data_sadaka = AinaZaSadaka::latest()->get();
        return view('backend.reports.mengineyo_aina_sadaka',compact(['title','data_sadaka']));
    }
}
