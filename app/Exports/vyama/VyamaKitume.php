<?php

namespace App\Exports\vyama;

use App\Kikundi;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class VyamaKitume implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya orodha ya vyama vya kitume";
        $data_vyama = Kikundi::orderBy('jina_la_kikundi')->get();
        return view('backend.reports.vyama_vya_kitume',compact(['title','data_vyama']));
    }
}
