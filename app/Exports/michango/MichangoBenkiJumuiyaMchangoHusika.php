<?php

namespace App\Exports\michango;

use App\MichangoBenki;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon;
use DB;

class MichangoBenkiJumuiyaMchangoHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($mchango,$jumuiya){
        $this->jumuiya = $jumuiya;
        $this->mchango = $mchango;
    }

    public function view(): View
    {
        $mchango = $this->mchango;
        $jumuiya = $this->jumuiya;

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "michango benki jumuiya $jumuiya $start_month - $current_month ($current_year)";
        
        //generate or populate the data interms of jumuiya/mchango/mwanafamilia
        $jumuiya_michango_husika = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'michango_benkis.aina_ya_mchango','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','mwanafamilias.id as mwanafamilia_id')
        ->whereBetween('michango_benkis.tarehe',[$begin_with,Carbon::now()])
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_benkis.aina_ya_mchango',$mchango)
        ->orderBy('kiasi','DESC')
        ->groupBy('mwanafamilias.jina_kamili')
        ->get();

        $michango_total = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_benkis.aina_ya_mchango',$mchango)
        ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
        ->sum('michango_benkis.kiasi');

        return view('backend.reports.michango_benki_jumuiya_mchango_husika',compact(['title','michango_total','jumuiya_michango_husika','jumuiya','mchango']));
        
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
