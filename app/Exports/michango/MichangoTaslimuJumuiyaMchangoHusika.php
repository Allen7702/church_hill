<?php

namespace App\Exports\michango;

use App\MichangoTaslimu;
use App\MichangoBenki;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon;
use DB;

class MichangoTaslimuJumuiyaMchangoHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($mchango,$jumuiya){
        $this->mchango = $mchango;
        $this->jumuiya = $jumuiya;
    }
    public function view(): View
    {
        $jumuiya = $this->jumuiya;
        $mchango = $this->mchango;
        
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango pesa taslimu $jumuiya $start_month - $current_month ($current_year)";

        //generate or populate the data interms of jumuiya
        $jumuiya_mchango_husika = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'michango_taslimus.aina_ya_mchango','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','mwanafamilias.id as mwanafamilia_id')
        ->whereBetween('michango_taslimus.tarehe',[$begin_with,Carbon::now()])
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_taslimus.aina_ya_mchango',$mchango)
        ->orderBy('kiasi','DESC')
        ->groupBy('mwanafamilias.jina_kamili')
        ->get();

        $michango_total = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_taslimus.aina_ya_mchango',$mchango)
        ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
        ->sum('michango_taslimus.kiasi');

        return view('backend.reports.michango_taslimu_jumuiya_mchango_husika',compact(['title','michango_total','jumuiya_mchango_husika','jumuiya','mchango']));
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
