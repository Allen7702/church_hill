<?php

namespace App\Exports\sadaka;

use App\SadakaKuu;
use App\SadakaJumuiya;
use Carbon;
use PDF;
use Excel;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SadakaTakwimu implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka $start_month - $current_month ($current_year)";

        //overall year sum of sadaka kuu
        $sadaka_kuu = SadakaKuu::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $sadaka_jumuiya = SadakaJumuiya::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        //overall total of sadaka kuu and jumuiya
        $sadaka_total = $sadaka_kuu + $sadaka_jumuiya;

        return view('backend.reports.sadaka_takwimu',compact(['title','sadaka_kuu','sadaka_jumuiya','sadaka_total']));

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
