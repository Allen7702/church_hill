<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon;
use Str;
use Excel;
use PDF;
use DB;
use App\SadakaJumuiya;
use App\SadakaKuu;
use App\Jumuiya;
use App\Exports\sadaka\SadakaTakwimu;
use App\Exports\sadaka\SadakaJumuiyaData;
use App\Exports\sadaka\SadakaJumuiyaHusikaData;
use App\Exports\sadaka\SadakaMisa;
use App\Exports\sadaka\SadakaMisaHusika;
use App\Exports\sadaka\SadakaKuuZaidi;
use App\Exports\sadaka\SadakaKuuMwezi;
use App\Exports\sadaka\SadakaJumuiyaZaidi;
use App\Exports\sadaka\SadakaJumuiyaMwezi;

class SadakaReportController extends Controller
{
    //
    public function sadaka_takwimu_pdf(){
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka $start_month - $current_month ($current_year)";

        //overall year sum of sadaka kuu
        $sadaka_kuu = SadakaKuu::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $sadaka_jumuiya = SadakaJumuiya::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        //overall total of sadaka kuu and jumuiya
        $sadaka_total = $sadaka_kuu + $sadaka_jumuiya;

        $pdf = PDF::loadView('backend.reports.sadaka_takwimu',compact(['title','sadaka_kuu','sadaka_jumuiya','sadaka_total']));

        return $pdf->stream('sadaka_takwimu.pdf');
    }

    //handling the sadaka for jumuiya in report
    public function sadaka_jumuiya_pdf(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka za jumuiya $start_month - $current_month ($current_year)";

        $data_sadaka_total = SadakaJumuiya::whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $data_sadaka = SadakaJumuiya::whereBetween('tarehe',[$begin_with,Carbon::now()])->select(DB::raw("SUM(kiasi) as kiasi"),'jina_la_jumuiya')
            ->orderBy('kiasi','DESC')
            ->whereYear('tarehe', date('Y'))
            ->groupBy('jina_la_jumuiya')
            ->get();
        $pdf = PDF::loadView('backend.reports.sadaka_jumuiya', compact(['data_sadaka','title','data_sadaka_total']));
        return $pdf->stream('sadaka_jumuiya.pdf');
    }

    //function to handle the report for jumuiya husika
    public function sadaka_jumuiya_husika_pdf($jumuiya){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "utoaji wa sadaka jumuiya ya $jumuiya $start_month - $current_month ($current_year)";

        $data_sadaka_total = SadakaJumuiya::where('jina_la_jumuiya',$jumuiya)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $jumuiyas = Jumuiya::latest()->get();
        $data_sadaka = SadakaJumuiya::where('jina_la_jumuiya',$jumuiya)->latest()->whereBetween('tarehe',[$begin_with,Carbon::now()])->orderBy('jina_la_jumuiya')->get();
        $pdf = PDF::loadView('backend.reports.sadaka_jumuiya_husika', compact(['jumuiyas','data_sadaka','title','data_sadaka_total']));
        return $pdf->stream("sadaka_jumuiya_$jumuiya.pdf");
    }

    //function to handle the sadaka kuu data
    public function sadaka_kuu_pdf(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka $start_month - $current_month ($current_year)";

        $data_sadaka = SadakaKuu::whereBetween('tarehe',[$begin_with,Carbon::now()])->select(DB::raw("SUM(kiasi) as kiasi"),'misa')
            ->orderBy('kiasi','DESC')
            ->whereYear('tarehe', date('Y'))
            ->groupBy('misa')
            ->get();
        
        //overall year sum of sadaka
        $sadaka_total = SadakaKuu::whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
        $pdf = PDF::loadView('backend.reports.sadaka_kuu',compact(['data_sadaka','sadaka_total','title']));
        return $pdf->stream("sadaka_za_misa.pdf");
    }

    //function to handle sadaka kuu misa husika
    public function sadaka_kuu_misa_husika_pdf($misa){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka $misa $start_month - $current_month ($current_year)";

        $data_sadaka = SadakaKuu::where('misa',$misa)->whereBetween('tarehe',[$begin_with,Carbon::now()])->get();
            
        //overall year sum of sadaka
        $sadaka_total = SadakaKuu::where('misa',$misa)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');
        $pdf = PDF::loadView('backend.reports.sadaka_kuu_misa_husika',compact(['data_sadaka','sadaka_total','title']));
        return $pdf->stream("sadaka_misa_$misa.pdf");
    }

    //function to handle the sadaka kuu zaidi
    public function sadaka_kuu_zaidi_pdf(){
        
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka $start_month - $current_month ($current_year)";

        //overall year sum of sadaka
        $sadaka_kuu = SadakaKuu::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $data_sadaka = SadakaKuu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereYear('tarehe', date('Y'))
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();

        $pdf = PDF::loadView('backend.reports.sadaka_kuu_zaidi',compact(['title','data_sadaka','sadaka_kuu']));
        return $pdf->stream("sadaka_kuu.pdf");
    
    }

    //function to handle the sadaka of specific month
    public function sadaka_kuu_mwezi_pdf($id){
        $title = "Sadaka za mwezi";
        $data_sadaka = SadakaKuu::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->get();
        $sadaka_mwezi = SadakaKuu::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->sum('kiasi');
        
        $pdf = PDF::loadView('backend.reports.sadaka_kuu_mwezi',compact(['data_sadaka','sadaka_mwezi','title']));
        return $pdf->stream("sadaka_mwezi_$id.pdf");
    }

    //function to handle the sadaka for jumuiya zaidi
    public function sadaka_jumuiya_zaidi_pdf(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "utoaji wa sadaka za jumuiya $start_month - $current_month ($current_year)";

        //overall year sum of sadaka za jumuiya
        $sadaka_jumuiya = SadakaJumuiya::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $data_sadaka_jumuiya = SadakaJumuiya::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereYear('tarehe', date('Y'))
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();

        $pdf = PDF::loadView('backend.reports.sadaka_jumuiya_zaidi',compact(['title','data_sadaka_jumuiya','sadaka_jumuiya']));
        return $pdf->stream("sadaka_jumuiya.pdf");
    }

    //function to handle the sadaka for jumuiya mwezi
    public function sadaka_jumuiya_mwezi_pdf($id){
        $title = "Sadaka za jumuiya mwezi";

        $data_sadaka_jumuiya = SadakaJumuiya::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"),'jina_la_jumuiya')
        ->whereMonth('tarehe',$id)
        ->whereYear('tarehe', date('Y'))
        ->orderBy('tarehe','asc')
        ->groupBy('jina_la_jumuiya')
        ->get();

        $sadaka_jumuiya_total = SadakaJumuiya::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->sum('kiasi');
        
        $pdf = PDF::loadView('backend.reports.sadaka_jumuiya_mwezi',compact(['sadaka_jumuiya_total','data_sadaka_jumuiya','title']));
        return $pdf->stream("sadaka_jumuiya_mwezi_$id.pdf");
    }

    //handling the report for sadaka takwimu
    public function sadaka_takwimu_excel(){
        return Excel::download(new SadakaTakwimu(),"sadaka_takwimu.xlsx");
    }

    //handling the sadaka for jumuiya in report
    public function sadaka_jumuiya_excel(){
        return Excel::download(new SadakaJumuiyaData(),"sadaka_jumuiya.xlsx");  
    }

    //function to handle the report for jumuiya husika
    public function sadaka_jumuiya_husika_excel($jumuiya){
        return Excel::download(new SadakaJumuiyaHusikaData($jumuiya),"sadaka_jumuiya_$jumuiya.xlsx");  
    }

    //function to handle the sadaka kuu data
    public function sadaka_kuu_excel(){
        return Excel::download(new SadakaMisa(),"sadaka_misa.xlsx");  
    }

    //function to handle sadaka kuu misa husika
    public function sadaka_kuu_misa_husika_excel($misa){
        return Excel::download(new SadakaMisaHusika($misa),"sadaka_misa_$misa.xlsx");  
    }

    //function to handle the sadaka kuu zaidi
    public function sadaka_kuu_zaidi_excel(){
        return Excel::download(new SadakaKuuZaidi(),"sadaka_zaidi.xlsx");  
    }

    //function to handle the sadaka of specific month
    public function sadaka_kuu_mwezi_excel($id){
        return Excel::download(new SadakaKuuMwezi($id),"sadaka_mwezi_$id.xlsx");  
    }

    //function to handle the sadaka for jumuiya zaidi
    public function sadaka_jumuiya_zaidi_excel(){
        return Excel::download(new SadakaJumuiyaZaidi(),"sadaka_jumuiya_zidi.xlsx");  
    }

    //function to handle the sadaka for jumuiya mwezi
    public function sadaka_jumuiya_mwezi_excel($id){
        return Excel::download(new SadakaJumuiyaMwezi($id),"sadaka_jumuiya_mwezi_$id.xlsx");  
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
