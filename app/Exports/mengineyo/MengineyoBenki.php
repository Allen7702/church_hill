<?php

namespace App\Exports\mengineyo;

use App\AkauntiZaBenki;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MengineyoBenki implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya orodha ya akaunti za benki";
        $data_akaunti_benki = AkauntiZaBenki::latest()->get();
        return view('backend.reports.mengineyo_akaunti_benki',compact(['title','data_akaunti_benki']));
    }
}
