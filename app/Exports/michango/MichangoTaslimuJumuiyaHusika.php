<?php

namespace App\Exports\michango;
use App\MichangoTaslimu;
use App\MichangoBenki;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon;
use DB;

class MichangoTaslimuJumuiyaHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($jumuiya){
        $this->jumuiya = $jumuiya;
    }
    public function view(): View
    {
        $jumuiya = $this->jumuiya;

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango pesa taslimu $jumuiya $start_month - $current_month ($current_year)";

        //generate or populate the data interms of jumuiya
        $jumuiya_michango = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'michango_taslimus.aina_ya_mchango')
        ->whereBetween('michango_taslimus.tarehe',[$begin_with,Carbon::now()])
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('kiasi','DESC')
        ->groupBy('michango_taslimus.aina_ya_mchango')
        ->get();

        $michango_total = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
        ->sum('michango_taslimus.kiasi');

        return view('backend.reports.michango_taslimu_jumuiya_husika',compact(['title','michango_total','jumuiya_michango','jumuiya']));
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
