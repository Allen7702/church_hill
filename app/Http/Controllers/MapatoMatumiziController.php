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

class MapatoMatumiziController extends Controller
{
    //
    public function mapato_matumizi_takwimu(Request $request){
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

        return view('backend.mapato_matumizi.mapato_matumizi_takwimu',compact(['title','mapato_total','matumizi_total','salio']));
    }

    //function to handle the mapatos zaidi
    public function mapato_zaidi(){

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

        return view('backend.mapato_matumizi.mapato_zaidi',compact(['title','sadaka_total','zaka_total','michango_total','mapato_total']));
    }

    //function to handle matumizi zaidi 
    public function matumizi_zaidi(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "Matumizi mwezi $start_month - $current_month ($current_year)";

        $matumizi_benki = MatumiziBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $matumizi_taslimu = MatumiziTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $matumizi_total = $matumizi_taslimu + $matumizi_benki;

        //data for chart of matumizi benki
        $takwimu_benki = MatumiziBenki::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
        ->whereYear('tarehe', date('Y'))
        ->orderBy('tarehe','asc')
        ->groupBy(DB::raw("MONTH(tarehe)"))
        ->get();  

         //data for chart of matumizi taslimu
        $takwimu_taslimu = MatumiziTaslimu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
        ->whereYear('tarehe', date('Y'))
        ->orderBy('tarehe','asc')
        ->groupBy(DB::raw("MONTH(tarehe)"))
        ->get();  

        return view('backend.mapato_matumizi.matumizi_zaidi',compact(['title','matumizi_benki','matumizi_taslimu','matumizi_total','takwimu_taslimu','takwimu_benki']));
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
