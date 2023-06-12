<?php

namespace App\Exports\sadaka;

use App\SadakaKuu;
use Carbon;
use PDF;
use Excel;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SadakaKuuMwezi implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $id = $this->id;
        $title = "Sadaka za mwezi";
        $data_sadaka = SadakaKuu::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->get();
        $sadaka_mwezi = SadakaKuu::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->sum('kiasi');
        
        return view('backend.reports.sadaka_kuu_mwezi',compact(['data_sadaka','sadaka_mwezi','title']));
    
    }

    //internal class variables
    private function getVariables(){
        $data_variable = [
            'current_year' => Carbon::now()->format('Y'),
            'current_month' => Carbon::now()->format('M'),
            'start_month' => "Jan",
            'start_date' => "01-01-".Carbon::now()->format('Y'), //this to get the first date of the year and current year
        ];    
        
        return $data_variable;
    }
}
