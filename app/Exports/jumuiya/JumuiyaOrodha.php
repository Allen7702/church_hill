<?php

namespace App\Exports\jumuiya;

use App\Jumuiya;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class JumuiyaOrodha implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $title = "Orodha ya jumuiya";
        $data_jumuiya = Jumuiya::latest('jumuiyas.created_at')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->orderBy('jumuiyas.jina_la_jumuiya','ASC')
        ->groupBy('jumuiyas.jina_la_jumuiya')
        ->get(['jumuiyas.*','familias.id as familia_id']);
        
        return view('backend.reports.jumuiya_orodha',compact(['title','data_jumuiya']));
    }
}
