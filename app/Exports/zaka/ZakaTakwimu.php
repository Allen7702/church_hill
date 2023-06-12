<?php

namespace App\Exports\zaka;

use App\Zaka;
use Carbon;
use Excel;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ZakaTakwimu implements FromView
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
        $title = "utoaji wa zaka $start_month - $current_month ($current_year)";

        $data_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
        ->whereYear('tarehe', date('Y'))
        ->orderBy('tarehe','asc')
        ->groupBy(DB::raw("MONTH(tarehe)"))
        ->get();  

        //overall year sum of zaka
        $zaka_total = Zaka::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
        
        return view('backend.reports.zaka_takwimu',compact(['title','data_zaka','zaka_total']));

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
