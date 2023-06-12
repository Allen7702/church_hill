<?php

namespace App\Exports\michango;

use App\MichangoTaslimu;
use App\MichangoBenki;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon;
use DB;

class MichangoTaslimuMweziHusika implements FromView
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
        $title = "michango pesa taslimu mwezi $id/$current_year";

        $michango_total = MichangoTaslimu::whereMonth('tarehe',$id)->whereYear('tarehe',Carbon::now()->format('Y'))->sum('kiasi');

        $mwezi = $id;

        $michango_taslimu = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya')
        ->whereMonth('michango_taslimus.tarehe',$id)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->orderBy('kiasi','DESC')
        ->groupBy('familias.jina_la_jumuiya')
        ->get();

        return view('backend.reports.michango_taslimu_kijumuiya_mwezi_husika',compact(['title','michango_taslimu','michango_total','mwezi']));
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
