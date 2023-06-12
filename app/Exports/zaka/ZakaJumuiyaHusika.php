<?php

namespace App\Exports\zaka;

use App\Zaka;
use Carbon;
use Excel;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ZakaJumuiyaHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($id){
        $this->id = $id;
    }

    public function view(): View
    {
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $jina_jumuiya = $this->id;
        
        $title = "utoaji wa zaka jumuiya ya $jina_jumuiya $start_month - $current_month ($current_year)";
        
        $data_zaka = Zaka::leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
        ->where('zakas.jumuiya',$jina_jumuiya)
        ->select(DB::raw("SUM(zakas.kiasi) as kiasi"),'mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','mwanafamilias.id')
            ->whereBetween('zakas.tarehe', [$begin_with,Carbon::now()])
            ->orderBy('zakas.kiasi','desc')
            ->groupBy('mwanafamilias.namba_utambulisho')
            ->get();

        //total for zaka for table footer
        $zaka_total = Zaka::where('jumuiya',$jina_jumuiya)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
        
        return view('backend.reports.zaka_jumuiya_husika',compact(['data_zaka','zaka_total','title','jina_jumuiya']));
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
