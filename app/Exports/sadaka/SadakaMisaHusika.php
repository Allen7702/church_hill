<?php

namespace App\Exports\sadaka;

use App\SadakaKuu;
use Carbon;
use PDF;
use Excel;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SadakaMisaHusika implements FromView
{
    /**
    * @return \Illuminate\Contracts\View\View
    */

    public function __construct($misa)
    {
        $this->misa = $misa;
    }

    public function view(): View
    {
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        
        $misa = $this->misa;

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka $misa $start_month - $current_month ($current_year)";

        $data_sadaka = SadakaKuu::where('misa',$misa)->whereBetween('tarehe',[$begin_with,Carbon::now()])->get();
            
        //overall year sum of sadaka
        $sadaka_total = SadakaKuu::where('misa',$misa)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
        return view('backend.reports.sadaka_kuu_misa_husika',compact(['data_sadaka','sadaka_total','title']));
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
