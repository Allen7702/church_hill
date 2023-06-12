<?php

namespace App\Exports\zaka;

use App\Zaka;
use Carbon;
use Excel;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ZakaJumuiyaMweziHusika implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($jumuiya,$mwezi){
        $this->jumuiya = $jumuiya;
        $this->mwezi = $mwezi;
    }

    public function view(): View
    {
        $jumuiya = $this->jumuiya;
        $mwezi = $this->mwezi;

        $title = "utoaji wa zaka jumuiya ya $jumuiya";
        
        $data_zaka = Zaka::leftJoin('mwanafamilias','mwanafamilias.id','=','zakas.mwanajumuiya')
        ->where('zakas.jumuiya',$jumuiya)
        ->select(DB::raw("SUM(zakas.kiasi) as kiasi"),'mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','mwanafamilias.id')
            ->whereMonth('zakas.tarehe',$mwezi)
            ->whereYear('tarehe', date('Y'))
            ->orderBy('zakas.kiasi','desc')
            ->groupBy('mwanafamilias.namba_utambulisho')
            ->get();
        
        //total for zaka for table footer
        $zaka_total = Zaka::where('jumuiya',$jumuiya)->whereMonth('tarehe',$mwezi)->whereYear('tarehe',date('Y'))->sum('kiasi');
        
        return view('backend.reports.zaka_jumuiya_mwezi_husika',compact(['data_zaka','zaka_total','title']));
    
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
