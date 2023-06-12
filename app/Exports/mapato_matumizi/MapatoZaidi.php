<?php

namespace App\Exports\mapato_matumizi;

use App\MichangoTaslimu;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon;
use App\MichangoBenki;
use App\MatumiziTaslimu;
use App\MatumiziBenki;
use App\SadakaKuu;
use App\Zaka;
use DB;

class MapatoZaidi implements FromView
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

        $title = "Mapato mwezi $start_month - $current_month ($current_year)";

        $sadaka_total = SadakaKuu::whereBetween('tarehe',[$begin_with, Carbon::now()->format('Y-m-d')])->sum('kiasi');
        
        $zaka_total = Zaka::whereBetween('tarehe',[$begin_with, Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_benki = MichangoBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_taslimu = MichangoTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_total = $michango_taslimu + $michango_benki;

        $mapato_total = $michango_total + $zaka_total + $sadaka_total;

        return view('backend.reports.mapato_zaidi',compact(['title','sadaka_total','zaka_total','michango_total','mapato_total']));
        
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
