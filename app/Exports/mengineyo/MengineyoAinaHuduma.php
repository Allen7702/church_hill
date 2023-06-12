<?php

namespace App\Exports\mengineyo;

use App\Huduma;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MengineyoAinaHuduma implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya aina za huduma";
        $data_huduma = Huduma::latest()->get();
        return view('backend.reports.mengineyo_aina_huduma',compact(['title','data_huduma']));
    }
}
