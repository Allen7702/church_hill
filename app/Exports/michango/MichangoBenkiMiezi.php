<?php

namespace App\Exports\michango;

use App\MichangoBenki;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon;
use DB;

class MichangoBenkiMiezi implements FromView
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
        $title = "Utoaji wa michango benki $start_month - $current_month ($current_year)";

        //summing the monthly interval
        $michango_total = MichangoBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_benki_miezi = MichangoBenki::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();  
        
        return view('backend.reports.michango_benki_miezi',compact(['michango_benki_miezi','title','michango_total']));
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
