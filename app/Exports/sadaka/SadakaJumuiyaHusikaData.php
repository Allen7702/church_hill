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

class SadakaJumuiyaHusikaData implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $id;

    public function __construct($jumuiya)
    {
        $this->jumuiya = $jumuiya;
    }

    public function view(): View
    {
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        
        $jumuiya = $this->jumuiya;

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "utoaji wa sadaka jumuiya ya $jumuiya $start_month - $current_month ($current_year)";

        $data_sadaka_total = SadakaJumuiya::where('jina_la_jumuiya',$jumuiya)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $jumuiyas = Jumuiya::latest()->get();
        $data_sadaka = SadakaJumuiya::where('jina_la_jumuiya',$jumuiya)->latest()->whereBetween('tarehe',[$begin_with,Carbon::now()])->orderBy('jina_la_jumuiya')->get();
        return view('backend.reports.sadaka_jumuiya_husika', compact(['jumuiyas','data_sadaka','title','data_sadaka_total']));
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
