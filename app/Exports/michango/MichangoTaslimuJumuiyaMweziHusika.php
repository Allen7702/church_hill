<?php

namespace App\Exports\michango;

use App\MichangoTaslimu;
use App\MichangoBenki;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon;
use DB;

class MichangoTaslimuJumuiyaMweziHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($mwezi,$jumuiya){
        $this->jumuiya = $jumuiya;
        $this->mwezi = $mwezi;
    }
    public function view(): View
    {
        //declaring variables
        $jumuiya = $this->jumuiya;
        $mwezi = $this->mwezi;

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango jumuiya $jumuiya mwezi $mwezi/$current_year";

        $michango_total = MichangoTaslimu::whereMonth('michango_taslimus.tarehe',$mwezi)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->sum('michango_taslimus.kiasi');

        $michango_taslimu = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya','michango_taslimus.aina_ya_mchango')
        ->whereMonth('michango_taslimus.tarehe',$mwezi)
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->orderBy('kiasi','DESC')
        ->groupBy('michango_taslimus.aina_ya_mchango')
        ->get();

        return view('backend.reports.michango_taslimu_jumuiya_mwezi_husika',compact(['title','michango_taslimu','michango_total','mwezi','jumuiya']));
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
