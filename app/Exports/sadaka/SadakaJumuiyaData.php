<?php

namespace App\Exports\sadaka;

use App\SadakaJumuiya;
use Carbon;
use PDF;
use Excel;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SadakaJumuiyaData implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka za jumuiya $start_month - $current_month ($current_year)";

        $data_sadaka_total = SadakaJumuiya::whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $data_sadaka = SadakaJumuiya::whereBetween('tarehe',[$begin_with,Carbon::now()])->select(DB::raw("SUM(kiasi) as kiasi"),'jina_la_jumuiya')
            ->orderBy('kiasi','DESC')
            ->whereYear('tarehe', date('Y'))
            ->groupBy('jina_la_jumuiya')
            ->get();
        
        return view('backend.reports.sadaka_jumuiya', compact(['data_sadaka','title','data_sadaka_total']));
        
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
