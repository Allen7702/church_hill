<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon;
use App\MichangoTaslimu;
use App\MichangoBenki;
use App\MatumiziTaslimu;
use App\MatumiziBenki;
use App\SadakaKuu;
use App\Zaka;
use DB;
use Excel;
use PDF;
use App\Exports\mapato_matumizi\MapatoMatumiziTakwimu;
use App\Exports\mapato_matumizi\MapatoZaidi;

class MapatoMatumiziReportController extends Controller
{
    public function mapato_matumizi_takwimu_pdf(){
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

        $pdf = PDF::loadView('backend.reports.mapato_matumizi_takwimu',compact(['title','mapato_total','matumizi_total','salio']));
        return $pdf->stream("mapato_matumizi_takwimu.pdf");
    }

    //function to handle the mapato zaidi in pdf
    public function mapato_zaidi_pdf(){
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

        $pdf = PDF::loadView('backend.reports.mapato_zaidi',compact(['title','sadaka_total','zaka_total','michango_total','mapato_total']));
        
        return $pdf->stream("mapato_zaidi.pdf");
    }

    //the excel files for mapato matumizi takwimu
    public function mapato_matumizi_takwimu_excel(){
        return Excel::download(new MapatoMatumiziTakwimu(), "mapato_matumizi.xlsx");
    }

    //function to handle the mapato zaidi in excel
    public function mapato_zaidi_excel(){
        return Excel::download(new MapatoZaidi(), "mapato_zaidi.xlsx");
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
