<?php

namespace App\Exports\mengineyo;

use App\MaliZaKanisa;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MengineyoMaliKanisa implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Ripoti ya mali za kanisa";
        $data_mali = MaliZaKanisa::latest()->get();
        $mali_total = MaliZaKanisa::sum('thamani');
        return view('backend.reports.mengineyo_mali_kanisa',compact(['title','data_mali','mali_total']));
    }
}
