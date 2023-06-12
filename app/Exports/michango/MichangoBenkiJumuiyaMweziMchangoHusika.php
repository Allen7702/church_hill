<?php

namespace App\Exports\michango;

use App\MichangoBenki;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon;
use DB;

class MichangoBenkiJumuiyaMweziMchangoHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($mwezi,$jumuiya,$mchango){
        $this->jumuiya = $jumuiya;
        $this->mwezi = $mwezi;
        $this->mchango = $mchango;
    }

    public function view(): View
    {
        $jumuiya = $this->jumuiya;
        $mwezi = $this->mwezi;
        $mchango = $this->mchango;

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "$mchango benki jumuiya $jumuiya mwezi $mwezi/$current_year";

        $michango_total = MichangoBenki::whereMonth('michango_benkis.tarehe',$mwezi)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_benkis.aina_ya_mchango',$mchango)
        ->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))
        ->sum('michango_benkis.kiasi');

        $michango_benki = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'familias.jina_la_jumuiya','michango_benkis.aina_ya_mchango','mwanafamilias.jina_kamili','mwanafamilias.id as mwanafamilia_id')
        ->whereMonth('michango_benkis.tarehe',$mwezi)
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))
        ->where('michango_benkis.aina_ya_mchango',$mchango)
        ->orderBy('kiasi','DESC')
        ->groupBy('mwanafamilias.id')
        ->get();

        return view('backend.reports.michango_benki_jumuiya_mwezi_mchango_husika',compact(['title','michango_benki','michango_total','mwezi','jumuiya','mchango']));
        
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
