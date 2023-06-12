<?php

namespace App\Exports\zaka;

use App\Zaka;
use Carbon;
use Excel;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ZakaMweziHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($id){
        $this->id = $id;
    }
    
    public function view(): View
    {
        $title = "Zaka za mwezi ";

        $mwezi = $this->id;
        // $data_zaka = Zaka::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->get();

        $data_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"),'jumuiya')
            ->whereMonth('tarehe',$mwezi)
            ->whereYear('tarehe', date('Y'))
            ->orderBy('kiasi','desc')
            ->groupBy('jumuiya')
            ->get();
        
        $zaka_mwezi = Zaka::whereMonth('tarehe',$mwezi)->whereYear('tarehe',date('Y'))->sum('kiasi');
        
        return view('backend.reports.zaka_mwezi_husika',compact(['data_zaka','zaka_mwezi','title','mwezi']));
        
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
