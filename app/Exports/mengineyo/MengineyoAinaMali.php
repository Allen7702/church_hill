<?php

namespace App\Exports\mengineyo;

use App\AinaZaMali;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MengineyoAinaMali implements FromView
{
    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
        $title = "Ripoti ya aina za mali";
        $data_aina = AinaZaMali::latest()->get();
        return view('backend.reports.mengineyo_aina_mali',compact(['title','data_aina']));
    }
}
