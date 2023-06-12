<?php

namespace App\Exports\sadaka;

use App\SadakaJumuiya;
use App\Jumuiya;
use Carbon;
use PDF;
use Excel;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SadakaJumuiyaMwezi implements FromView
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
        $title = "Sadaka za jumuiya mwezi";

        $id = $this->id;

        $data_sadaka_jumuiya = SadakaJumuiya::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"),'jina_la_jumuiya')
        ->whereMonth('tarehe',$id)
        ->whereYear('tarehe', date('Y'))
        ->orderBy('tarehe','asc')
        ->groupBy('jina_la_jumuiya')
        ->get();

        $sadaka_jumuiya_total = SadakaJumuiya::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->sum('kiasi');
        
        return view('backend.reports.sadaka_jumuiya_mwezi',compact(['sadaka_jumuiya_total','data_sadaka_jumuiya','title']));

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
