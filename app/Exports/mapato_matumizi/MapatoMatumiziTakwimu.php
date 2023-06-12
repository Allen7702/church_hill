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

class MapatoMatumiziTakwimu implements FromView
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

        $title = "Mapato na Matumizi $start_month - $current_month ($current_year)";

        //summing up all the mapatos
        $michango_taslimu = MichangoTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_benki = MichangoBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $sadaka = SadakaKuu::whereBetween('tarehe',[$begin_with, Carbon::now()->format('Y-m-d')])->sum('kiasi');
        
        $zaka = Zaka::whereBetween('tarehe',[$begin_with, Carbon::now()->format('Y-m-d')])->sum('kiasi');

        //summing up all the matumizi
        //specifically the cash
        $matumizi_taslimu = MatumiziTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        //specifically bank
        $matumizi_benki = MatumiziBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $matumizi_total = $matumizi_taslimu + $matumizi_benki; 

        $mapato_total = $michango_benki + $michango_taslimu + $sadaka + $zaka;

        $salio = $mapato_total - $matumizi_total;

        return view('backend.reports.mapato_matumizi_takwimu',compact(['title','mapato_total','matumizi_total','salio']));
        
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
