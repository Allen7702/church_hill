<?php

namespace App\Exports\michango;

use App\MichangoBenki;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon;
use DB;

class MichangoBenkiMweziHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($id){
        $this->id = $id;
    }

    public function view(): View
    {
        $id = $this->id;

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango benki mwezi $id/$current_year";

        $michango_total = MichangoBenki::whereMonth('tarehe',$id)->whereYear('tarehe',Carbon::now()->format('Y'))->sum('kiasi');

        $michango_benki = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'familias.jina_la_jumuiya')
        ->whereMonth('michango_benkis.tarehe',$id)
        ->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))
        ->orderBy('kiasi','DESC')
        ->groupBy('familias.jina_la_jumuiya')
        ->get();

        $mwezi = $id;

        return view('backend.reports.michango_benki_kijumuiya_mwezi_husika',compact(['title','michango_benki','michango_total','mwezi']));
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
