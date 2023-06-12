<?php

namespace App\Exports\viongozi;

use App\User;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use DB;

class OrodhaViongozi implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "ORODHA YA VIONGOZI";
        $viongozi_total = User::where('ngazi','!=','administrator')->count();
        $data_viongozi = User::latest()->where('ngazi','!=','administrator')->get();
        return view('backend.reports.orodha_viongozi',compact(['data_viongozi','title','viongozi_total']));
    }
}
