<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AinaZaMichango;
use App\AkauntiZaBenki;
use App\MichangoTaslimu;
use App\MichangoBenki;
use App\Mwanafamilia;
use App\Exports\michango\MichangoTakwimu;
use App\Exports\michango\MichangoTaslimuKijumuiya;
use App\Exports\michango\MichangoTaslimuJumuiyaHusika;
use App\Exports\michango\MichangoTaslimuJumuiyaMchangoHusika;
use App\Exports\michango\MichangoTaslimuYote;
use App\Exports\michango\MichangoTaslimuMiezi;
use App\Exports\michango\MichangoTaslimuMweziHusika;
use App\Exports\michango\MichangoTaslimuJumuiyaMweziHusika;
use App\Exports\michango\MichangoTaslimuJumuiyaMweziMchangoHusika;
use App\Exports\michango\MichangoBenkiKijumuiya;
use App\Exports\michango\MichangoBenkiJumuiyaHusika;
use App\Exports\michango\MichangoBenkiJumuiyaMchangoHusika;
use App\Exports\michango\MichangoBenkiMiezi;
use App\Exports\michango\MichangoBenkiYote;
use App\Exports\michango\MichangoBenkiMweziHusika;
use App\Exports\michango\MichangoBenkiJumuiyaMweziHusika;
use App\Exports\michango\MichangoBenkiJumuiyaMweziMchangoHusika;
use Carbon;
use Auth;
use Validator;
use DB;
use PDF;
use Excel;

class MichangoReportController extends Controller
{
    //michango takwimu pdf
    public function michango_takwimu_pdf(){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        
        $title = "Utoaji wa michango $start_month - $current_month ($current_year)";
        
        $michango_benki = MichangoBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
        
        $michango_taslimu = MichangoTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');
        
        $michango_total = $michango_benki + $michango_taslimu;
        
        $pdf = PDF::loadView('backend.reports.michango_takwimu',compact(['title','michango_benki','michango_taslimu','michango_total']));
        return $pdf->stream("michango_takwimu.pdf");
    }

    //function to handle michango kijumuiya pdf
    public function michango_taslimu_kijumuiya_pdf(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango kijumuiya $start_month - $current_month ($current_year)";

        //generate or populate the data interms of jumuiya
        $jumuiya_data = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya')
        ->whereBetween('michango_taslimus.tarehe',[$begin_with,Carbon::now()])
        ->orderBy('kiasi','DESC')
        ->groupBy('familias.jina_la_jumuiya')
        ->get();

        $michango_total = MichangoTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $pdf = PDF::loadView('backend.reports.michango_taslimu_kijumuiya',compact(['title','michango_total','jumuiya_data']));
        return $pdf->stream("michango_taslimu_kijumuiya.pdf");
    }

    //function to handle michango group by michango type within a specific jumuiya
    public function michango_taslimu_jumuiya_husika_pdf($jumuiya){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango pesa taslimu $jumuiya $start_month - $current_month ($current_year)";

        //generate or populate the data interms of jumuiya
        $jumuiya_michango = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'michango_taslimus.aina_ya_mchango')
        ->whereBetween('michango_taslimus.tarehe',[$begin_with,Carbon::now()])
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('kiasi','DESC')
        ->groupBy('michango_taslimus.aina_ya_mchango')
        ->get();

        $michango_total = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
        ->sum('michango_taslimus.kiasi');

        $pdf = PDF::loadView('backend.reports.michango_taslimu_jumuiya_husika',compact(['title','michango_total','jumuiya_michango','jumuiya']));
        return $pdf->stream("michango_taslimu_jumuiya.pdf");
    }

    //function to handle the mchango in specific jumuiya
    public function michango_taslimu_jumuiya_mchango_husika_pdf($mchango, $jumuiya){
        
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango pesa taslimu $jumuiya $start_month - $current_month ($current_year)";

        //generate or populate the data interms of jumuiya
        $jumuiya_mchango_husika = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'michango_taslimus.aina_ya_mchango','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','mwanafamilias.id as mwanafamilia_id')
        ->whereBetween('michango_taslimus.tarehe',[$begin_with,Carbon::now()])
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_taslimus.aina_ya_mchango',$mchango)
        ->orderBy('kiasi','DESC')
        ->groupBy('mwanafamilias.jina_kamili')
        ->get();

        $michango_total = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_taslimus.aina_ya_mchango',$mchango)
        ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
        ->sum('michango_taslimus.kiasi');

        $pdf = PDF::loadView('backend.reports.michango_taslimu_jumuiya_mchango_husika',compact(['title','michango_total','jumuiya_mchango_husika','jumuiya','mchango']));
        return $pdf->stream("michango_taslimu_jumuiya_mchango.pdf");
    }

    //the function to handle michango taslimu pdf
    public function michango_taslimu_pdf(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "Utoaji wa michango pesa taslimu $start_month - $current_month ($current_year)";

        $michango_total = MichangoTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_taslimu = MichangoTaslimu::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')->get(['michango_taslimus.*','mwanafamilias.jina_kamili']);
        $pdf = PDF::loadView('backend.reports.michango_taslimu',compact(['title','michango_taslimu','michango_total']));
        return $pdf->stream("michango_taslimus.pdf");
    }

    //function to handle the michango taslimu montly
    public function michango_taslimu_miezi_pdf(){
        
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();
        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "Utoaji wa michango taslimu $start_month - $current_month ($current_year)";

        //summing the monthly interval
        $michango_total = MichangoTaslimu::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_taslimu_miezi = MichangoTaslimu::select(DB::raw("SUM(kiasi) as kiasi"), DB::raw("MONTH(tarehe) as mwezi"))
            ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
            ->orderBy('tarehe','asc')
            ->groupBy(DB::raw("MONTH(tarehe)"))
            ->get();  
        
        $pdf = PDF::loadView('backend.reports.michango_taslimu_miezi',compact(['michango_taslimu_miezi','title','michango_total']));
        return $pdf->stream("michango_taslimu_miezi.pdf");
    }

    //function to handle the specific month
    public function michango_taslimu_mwezi_husika_pdf($id){
        
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

        $pdf = PDF::loadView('backend.reports.michango_taslimu_kijumuiya_mwezi_husika',compact(['title','michango_taslimu','michango_total','mwezi']));
        return $pdf->stream("michango_taslimu_kijumuiya_mwezi.pdf");
    }

    //function to handle the michango taslimu mwezi jumuiya husika group by michango
    public function michango_taslimu_jumuiya_mwezi_husika_pdf($mwezi,$jumuiya){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango jumuiya $jumuiya mwezi $mwezi/$current_year";

        $michango_total = MichangoTaslimu::whereMonth('michango_taslimus.tarehe',$mwezi)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->sum('michango_taslimus.kiasi');

        $michango_taslimu = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya','michango_taslimus.aina_ya_mchango')
        ->whereMonth('michango_taslimus.tarehe',$mwezi)
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->orderBy('kiasi','DESC')
        ->groupBy('michango_taslimus.aina_ya_mchango')
        ->get();

        $pdf = PDF::loadView('backend.reports.michango_taslimu_jumuiya_mwezi_husika',compact(['title','michango_taslimu','michango_total','mwezi','jumuiya']));
        return $pdf->stream("michango_taslimu_jumuiya_mwezi_husika.pdf");
    }

    //function to handle jumuiya mwezi mchango husika group by mtoaji pdf
    public function michango_taslimu_jumuiya_mwezi_mchango_husika_pdf($mwezi,$jumuiya,$mchango){
        
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "$mchango jumuiya $jumuiya mwezi $mwezi/$current_year";

        $michango_total = MichangoTaslimu::whereMonth('michango_taslimus.tarehe',$mwezi)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->where('michango_taslimus.aina_ya_mchango',$mchango)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->sum('michango_taslimus.kiasi');

        $michango_taslimu = MichangoTaslimu::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_taslimus.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_taslimus.kiasi) as kiasi"),'familias.jina_la_jumuiya','michango_taslimus.aina_ya_mchango','mwanafamilias.jina_kamili','mwanafamilias.id as mwanafamilia_id')
        ->whereMonth('michango_taslimus.tarehe',$mwezi)
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))
        ->where('michango_taslimus.aina_ya_mchango',$mchango)
        ->orderBy('kiasi','DESC')
        ->groupBy('mwanafamilias.id')
        ->get();

        $pdf = PDF::loadView('backend.reports.michango_taslimu_jumuiya_mwezi_mchango_husika',compact(['title','michango_taslimu','michango_total','mwezi','jumuiya','mchango']));
        return $pdf->stream("michango_taslimu_jumuiya_mwezi_mchango.pdf");
    }

    //===================================== BENKI =================================//
    
    //function to handle the michango benki kijumuiya
    public function michango_benki_kijumuiya_pdf(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango benki kijumuiya $start_month - $current_month ($current_year)";

        //generate or populate the data interms of jumuiya
        $jumuiya_data = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'familias.jina_la_jumuiya')
        ->whereBetween('michango_benkis.tarehe',[$begin_with,Carbon::now()])
        ->orderBy('kiasi','DESC')
        ->groupBy('familias.jina_la_jumuiya')
        ->get();

        $michango_total = MichangoBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_benki = MichangoBenki::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
        $pdf = PDF::loadView('backend.reports.michango_benki_kijumuiya',compact(['title','michango_benki','michango_total','jumuiya_data']));
        return $pdf->stream("michango_benki_kijumuiya.pdf");
    }

    //function to handle michango of benki within a specific jumuiya
    public function michango_benki_jumuiya_husika_pdf($jumuiya){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "michango benki jumuiya $jumuiya $start_month - $current_month ($current_year)";

        //generate or populate the data interms of jumuiya
        $jumuiya_michango = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'michango_benkis.aina_ya_mchango')
        ->whereBetween('michango_benkis.tarehe',[$begin_with,Carbon::now()])
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('kiasi','DESC')
        ->groupBy('michango_benkis.aina_ya_mchango')
        ->get();

        $michango_total = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])
        ->sum('michango_benkis.kiasi');

        $michango_benki = MichangoBenki::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
        
        $pdf = PDF::loadView('backend.reports.michango_benki_jumuiya_husika',compact(['title','michango_benki','michango_total','jumuiya_michango','jumuiya']));
        return $pdf->stream("michango_benki_jumuiya_husika.pdf");
    }


    //function to handle michango_benki_jumuiya_mchango_husika group by mwanajumuiya
    public function michango_benki_jumuiya_mchango_husika_pdf($mchango, $jumuiya){
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

        $pdf = PDF::loadView('backend.reports.michango_benki_jumuiya_mchango_husika',compact(['title','michango_total','jumuiya_michango_husika','jumuiya','mchango']));
        return $pdf->stream("michango_benki_jumuiya_mchango_husika.pdf");
    }

    //function to handle the michango benki montly
    public function michango_benki_miezi_pdf(){

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
        
        $pdf = PDF::loadView('backend.reports.michango_benki_miezi',compact(['michango_benki_miezi','title','michango_total']));
        return $pdf->stream("michango_benki_miezi.pdf");
    }

    //the index of michango benki pdf
    public function michango_benki_pdf(){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "Utoaji wa michango benki $start_month - $current_month ($current_year)";

        $michango_total = MichangoBenki::whereBetween('tarehe',[$begin_with,Carbon::now()->format('Y-m-d')])->sum('kiasi');

        $michango_benki = MichangoBenki::latest()->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')->get(['michango_benkis.*','mwanafamilias.jina_kamili']);
        $pdf = PDF::loadView('backend.reports.michango_benki',compact(['title','michango_benki','michango_total']));
        return $pdf->stream("michango_benki.pdf");
    }

    //function to handle the specific month
    public function michango_benki_mwezi_husika_pdf($id){
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

        $pdf = PDF::loadView('backend.reports.michango_benki_kijumuiya_mwezi_husika',compact(['title','michango_benki','michango_total','mwezi']));
        return $pdf->stream("michango_benki_kijumuiya_mwezi_husika.pdf");
    }


    //function to handle the michango taslimu mwezi jumuiya husika group by michango
    public function michango_benki_jumuiya_mwezi_husika_pdf($mwezi,$jumuiya){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "michango benki jumuiya $jumuiya mwezi $mwezi/$current_year";

        $michango_total = MichangoBenki::whereMonth('michango_benkis.tarehe',$mwezi)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))
        ->sum('michango_benkis.kiasi');

        $michango_benki = MichangoBenki::leftJoin('mwanafamilias','mwanafamilias.id','=','michango_benkis.mwanafamilia')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->select(DB::raw("SUM(michango_benkis.kiasi) as kiasi"),'familias.jina_la_jumuiya','michango_benkis.aina_ya_mchango')
        ->whereMonth('michango_benkis.tarehe',$mwezi)
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))
        ->orderBy('kiasi','DESC')
        ->groupBy('michango_benkis.aina_ya_mchango')
        ->get();

        $pdf = PDF::loadView('backend.reports.michango_benki_jumuiya_mwezi_husika',compact(['title','michango_benki','michango_total','mwezi','jumuiya']));
        return $pdf->stream("michango_benki_jumuiya_mwezi_husika.pdf");
    }

    //function to handle benki michango within a specific jumuiya, specific month grouped by mwanafamilia
    public function michango_benki_jumuiya_mwezi_mchango_husika_pdf($mwezi,$jumuiya,$mchango){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');
        $title = "$mchango benki jumuiya $jumuiya mwezi $mwezi/$current_year";

        $wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','mwanafamilias.familia')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id as mwanafamilia_id','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

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

        $pdf = PDF::loadView('backend.reports.michango_benki_jumuiya_mwezi_mchango_husika',compact(['title','michango_benki','michango_total','mwezi','jumuiya','mchango']));
        return $pdf->stream("michango_benki_jumuiya_mwezi_mchango.pdf");
    }


    //=============================================================================//

    //function to handle michango takwimu excel
    public function michango_takwimu_excel(){
        return Excel::download(new MichangoTakwimu(),"michango_takwimu.xlsx");
    }

    //function to handle michango kijumuiya excel
    public function michango_taslimu_kijumuiya_excel(){
        return Excel::download(new MichangoTaslimuKijumuiya(), "michango_taslimu_kijumuiya.xlsx");
    }

    //function to handle michango group by michango type within a specific jumuiya
    public function michango_taslimu_jumuiya_husika_excel($jumuiya){
        return Excel::download(new MichangoTaslimuJumuiyaHusika($jumuiya),"michango_taslimu_$jumuiya.xlsx");
    }

    //function to handle the mchango in specific jumuiya excel
    public function michango_taslimu_jumuiya_mchango_husika_excel($mchango, $jumuiya){
        return Excel::download(new MichangoTaslimuJumuiyaMchangoHusika($mchango,$jumuiya),"mchango_$jumuiya._mchango_$mchango.xlsx");
    }

    //the function to handle michango taslimu excel
    public function michango_taslimu_excel(){
        return Excel::download(new MichangoTaslimuYote(),"michango_taslimu.xlsx");
    }

    //function to handle the michango taslimu montly
    public function michango_taslimu_miezi_excel(){
        return Excel::download(new MichangoTaslimuMiezi(),"michango_taslimu.xlsx");
    }

    //function to handle the specific month
    public function michango_taslimu_mwezi_husika_excel($id){
        return Excel::download(new MichangoTaslimuMweziHusika($id),"michango_taslimu_mwezi.$id.xlsx");
    }

    //function to handle the michango taslimu mwezi jumuiya husika group by michango
    public function michango_taslimu_jumuiya_mwezi_husika_excel($mwezi,$jumuiya){
        return Excel::download(new MichangoTaslimuJumuiyaMweziHusika($mwezi,$jumuiya), "michango_taslimu_$jumuiya.$mwezi.xlsx");
    }

    //function to handle jumuiya mwezi mchango husika group by mtoaji excel
    public function michango_taslimu_jumuiya_mwezi_mchango_husika_excel($mwezi,$jumuiya,$mchango){
        return Excel::download(new MichangoTaslimuJumuiyaMweziMchangoHusika($mwezi,$jumuiya,$mchango),"mchango_taslimu_jumuiya_mwezi.xlsx");
    }

    //function to handle the michango benki kijumuiya excel
    public function michango_benki_kijumuiya_excel(){
        return Excel::download(new MichangoBenkiKijumuiya(),"michango_benki_kijumuiya.xlsx");
    }

    //function to handle michango of benki within a specific jumuiya
    public function michango_benki_jumuiya_husika_excel($jumuiya){
        return Excel::download(new MichangoBenkiJumuiyaHusika($jumuiya), "michango_benki_jumuiya_husika.xlsx");
    }

    //function to handle michango_benki_jumuiya_mchango_husika group by mwanajumuiya
    public function michango_benki_jumuiya_mchango_husika_excel($mchango,$jumuiya){
        return Excel::download(new MichangoBenkiJumuiyaMchangoHusika($mchango,$jumuiya),"mchango_benki_mchango_jumuiya_husika.xlsx");
    }

    //function to handle the michango benki montly
    public function michango_benki_miezi_excel(){
        return Excel::download(new MichangoBenkiMiezi(),"michango_benki_miezi.xlsx");
    }

    //the function to handle michango benki excel
    public function michango_benki_excel(){
        return Excel::download(new MichangoBenkiYote(),"michango_benki.xlsx");
    }

    //function to handle the specific month
    public function michango_benki_mwezi_husika_excel($id){
        return Excel::download(new MichangoBenkiMweziHusika($id),"michango_benki_mwezi_husika.xlsx");
    }

    //function to handle the michango taslimu mwezi jumuiya husika group by michango
    public function michango_benki_jumuiya_mwezi_husika_excel($mwezi,$jumuiya){
        return Excel::download(new MichangoBenkiJumuiyaMweziHusika($mwezi,$jumuiya),"michango_benki_jumuiya_mwezi_husika.xlsx");
    }

    //function to handle benki michango within a specific jumuiya, specific month grouped by mwanafamilia
    public function michango_benki_jumuiya_mwezi_mchango_husika_excel($mwezi,$jumuiya,$mchango){
        return Excel::download(new MichangoBenkiJumuiyaMweziMchangoHusika($mwezi,$jumuiya,$mchango),"michango_benki_jumuiya_mwezi_mchango_husika.xlsx");
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
