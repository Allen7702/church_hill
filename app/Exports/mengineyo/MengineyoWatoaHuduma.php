<?php

namespace App\Exports\mengineyo;

use App\WatoaHuduma;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MengineyoWatoaHuduma implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya orodha ya watoa huduma";
        $data_watoahuduma = WatoaHuduma::latest()->get();
        return view('backend.reports.mengineyo_watoa_huduma',compact(['title','data_watoahuduma']));
    }
}
