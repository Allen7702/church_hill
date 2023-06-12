<?php

namespace App\Exports\viongozi;

use App\User;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use DB;

class OrodhaIdadiViongozi implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "ORODHA YA IDADI YA VIONGOZI";
        $viongozi_total = User::where('ngazi','!=','administrator')->count();
        $data_viongozi = User::whereNotNull('cheo')->select(DB::raw("COUNT(cheo) as idadi"),'cheo')
            ->groupBy('cheo')
            ->get(['cheo','idadi']);
            
        return view('backend.reports.orodha_idadi_viongozi',compact(['title','data_viongozi','viongozi_total']));
    }
}
