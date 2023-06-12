<?php

namespace App\Http\Controllers;

use App\Familia;
use Illuminate\Http\Request;
use App\Zaka;
use App\Mwanafamilia;
use App\Jumuiya;
use App\Kanda;
use App\Exports\zaka\ZakaTakwimu;
use App\Exports\zaka\ZakaJumuiya;
use App\Exports\zaka\ZakaJumuiyaHusika;
use App\Exports\zaka\ZakaMweziHusika;
use App\Exports\zaka\ZakaKwaMweziWanaJumuiyaHusika;
use Validator;
use Alert;
use Carbon;
use Auth;
use DB;
use PDF;
use Excel;

class ZakaReportController extends Controller
{
    //handling the takwimu for zaka pdf
    public function zaka_takwimu_pdf(){
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

        $pdf = PDF::loadView('backend.reports.zaka_takwimu',compact(['title','data_zaka','zaka_total']));

        return $pdf->stream("zaka_takwimu.pdf");
    }

    //handling the zaka jumuiya pdf
    public function zaka_jumuiya_pdf(){

        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $title = "utoaji wa zaka jumuiya $start_month - $current_month ($current_year)";

        //data for select 2 (filtering jumuiyas)
        $jumuiya = Kanda::latest('jumuiyas.created_at')->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->get(['jumuiyas.jina_la_jumuiya','kandas.jina_la_kanda','jumuiyas.id']);

        //populate data for wanajumuiya
        $data_wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->get(['mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        //overall year sum of zaka za jumuiya
        $zaka_total = Zaka::whereYear('tarehe',$current_year)->whereBetween('tarehe',[$begin_with,Carbon::now()])->sum('kiasi');

        $data_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"),'jumuiya')
            ->whereYear('tarehe', date('Y'))
            ->orderBy('kiasi','desc')
            ->groupBy('jumuiya')
            ->get();

        $pdf = PDF::loadView('backend.reports.zaka_jumuiya',compact(['jumuiya','data_zaka','zaka_total','data_wanajumuiya','title']));
        return $pdf->stream("sadaka_jumuiya.pdf");
    }

    //function to handle the issue of zaka jumuiya husika
    public function zaka_jumuiya_husika_pdf($id){
        //setting the beginning of the month in a year and current month
        $data_variable = $this->getVariables();

        $current_month = $data_variable['current_month'];
        $current_year = $data_variable['current_year'];
        $start_month = $data_variable['start_month'];
        $start_date = $data_variable['start_date'];
        $begin_with = Carbon::parse($start_date)->format('Y-m-d');

        $jina_jumuiya = $id;

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

        $pdf = PDF::loadView('backend.reports.zaka_jumuiya_husika',compact(['data_zaka','zaka_total','title','jina_jumuiya']));

        return $pdf->stream("zaka_jumuiya_$id.pdf");

    }

    //handling the zaka mwezi husika pdf
    public function zaka_mwezi_husika_pdf($id){
        $title = "Zaka za mwezi ";

        $mwezi = $id;
        // $data_zaka = Zaka::whereMonth('tarehe',$id)->whereYear('tarehe',date('Y'))->get();

        $data_zaka = Zaka::select(DB::raw("SUM(kiasi) as kiasi"),'jumuiya')
            ->whereMonth('tarehe',$mwezi)
            ->whereYear('tarehe', date('Y'))
            ->orderBy('kiasi','desc')
            ->groupBy('jumuiya')
            ->get();

        $zaka_mwezi = Zaka::whereMonth('tarehe',$mwezi)->whereYear('tarehe',date('Y'))->sum('kiasi');

        $pdf = PDF::loadView('backend.reports.zaka_mwezi_husika',compact(['data_zaka','zaka_mwezi','title','mwezi']));
        return $pdf->stream("zaka_mwezi_$mwezi.pdf");
    }

    //handling the zaka jumuiya mwezi husika pdf
    public function zaka_jumuiya_mwezi_husika_pdf($jumuiya, $mwezi){
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

        $pdf = PDF::loadView('backend.reports.zaka_jumuiya_mwezi_husika',compact(['data_zaka','zaka_total','title']));
        return $pdf->stream("zaka_mwezi_jumuiya_ya_$jumuiya.mwezi_$mwezi.pdf");
    }

    //handling the takwimu for zaka excel
    public function zaka_takwimu_excel(){
        return Excel::download(new ZakaTakwimu(),"zaka_takwimu.xlsx");
    }

    //handling the zaka jumuiya excel
    public function zaka_jumuiya_excel(){
        return Excel::download(new ZakaJumuiya(),"zaka_jumuiya.xlsx");
    }

    //handling the zaka jumuiya husika excel
    public function zaka_jumuiya_husika_excel($id){
        return Excel::download(new ZakaJumuiyaHusika($id),"zaka_jumuiya_husika.xlsx");
    }

    //handling the zaka mwezi husika excel
    public function zaka_mwezi_husika_excel($id){
        return Excel::download(new ZakaMweziHusika($id),"zaka_mwezi_husika.xlsx");
    }

    //handling the zaka mwezi husika excel
    public function zaka_jumuiya_mwezi_husika_excel($jumuiya,$mwezi){
        return Excel::download(new ZakaKwaMweziWanaJumuiyaHusika($jumuiya,$mwezi),"zaka_jumuiya_$jumuiya.mwezi_$mwezi._husika.xlsx");
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

    public function zakaKwaMieziWanajumuiyaPDF(string $jumuiya, string $mwaka)
    {
        $all_month = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $familia = Familia::where('jina_la_jumuiya',$jumuiya)->with('wanafamilias')->get()->each(function ($query) use ($mwaka, $all_month){

            return $query->wanafamilias->each(function ($mwanafamila) use ($mwaka, $all_month){
                $zaka = Zaka::select('kiasi', DB::raw("MONTHNAME(tarehe) as mwezi"),DB::raw("YEAR(tarehe) as mwaka"), 'mwanafamilias.jina_kamili' )
                    ->where('mwanajumuiya',$mwanafamila->id)
                    ->where('tarehe','like', $mwaka.'%' )
                    ->join('mwanafamilias','zakas.mwanajumuiya', '=', 'mwanafamilias.id')
                    ->orderBy(DB::raw("MONTHNAME(tarehe)"),'asc')
//                    ->groupBy(DB::raw("MONTHNAME(tarehe)"))
                    ->get();

                $missing_month = array_diff($all_month, array_column($zaka->toArray(),'mwezi'));
                $index = 0;
                $unavilable_month = [];
                foreach ($missing_month as $month) {

                    $unavilable_month[$index] = [
                        'kiasi' => 0,
                        'mwezi' => $month,
                        'mwaka' => $mwaka,
                        'jina_kamili' => ''
                    ];


                    $index++;
                }

                $sorted_data = [];
                $merged_data = array_merge($zaka->toArray(), $unavilable_month);

                foreach($merged_data as $month){

                    if($month['mwezi'] == 'January') {
                        $sorted_data[0] = $month;
                    }

                    if($month['mwezi'] == 'February') {
                        $sorted_data[1] = $month;
                    }

                    if($month['mwezi'] == 'March') {
                        $sorted_data[2] = $month;
                    }

                    if($month['mwezi'] == 'April') {
                        $sorted_data[3] = $month;
                    }

                    if($month['mwezi'] == 'May') {
                        $sorted_data[4] = $month;
                    }

                    if($month['mwezi'] == 'June') {
                        $sorted_data[5] = $month;
                    }

                    if($month['mwezi'] == 'July') {
                        $sorted_data[6] = $month;
                    }

                    if($month['mwezi'] == 'August') {
                        $sorted_data[7] = $month;
                    }

                    if($month['mwezi'] == 'September') {
                        $sorted_data[8] = $month;
                    }

                    if($month['mwezi'] == 'October') {
                        $sorted_data[9] = $month;
                    }

                    if($month['mwezi'] == 'November') {
                        $sorted_data[10] = $month;
                    }

                    if($month['mwezi'] == 'December') {
                        $sorted_data[11] = $month;
                    }

                }
                $new = collect($sorted_data);

                return  $mwanafamila->zaka = $new;
            });
        });


        $jumuiya_data = Jumuiya::firstWhere('jina_la_jumuiya', $jumuiya);
//
        $pdf = PDF::loadView('backend.reports.zaka_kwa_miezi_wanajumuiya',['zaka_data' => array_column($familia->toArray(), 'wanafamilias'),  'jumuiya' => $jumuiya_data, 'meaka' => $mwaka, 'title' => 'Malipo ya zaka kwa jumuiya']);
        return $pdf->stream("zaka_kwa_mwezi_wanajumuiya_ya_$jumuiya.mwezi_$mwaka.pdf");

    }

    public function zakaKwaMieziWanajumuiyaExcel(string $jumuiya, string $mwaka)
    {
        $all_month = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $familia = Familia::where('jina_la_jumuiya',$jumuiya)->with('wanafamilias')->get()->each(function ($query) use ($mwaka, $all_month){

            return $query->wanafamilias->each(function ($mwanafamila) use ($mwaka, $all_month){
                $zaka = Zaka::select('kiasi', DB::raw("MONTHNAME(tarehe) as mwezi"),DB::raw("YEAR(tarehe) as mwaka"), 'mwanafamilias.jina_kamili' )
                    ->where('mwanajumuiya',$mwanafamila->id)
                    ->where('tarehe','like', $mwaka.'%' )
                    ->join('mwanafamilias','zakas.mwanajumuiya', '=', 'mwanafamilias.id')
                    ->orderBy(DB::raw("MONTHNAME(tarehe)"),'asc')
//                    ->groupBy(DB::raw("MONTHNAME(tarehe)"))
                    ->get();

                $missing_month = array_diff($all_month, array_column($zaka->toArray(),'mwezi'));
                $index = 0;
                $unavilable_month = [];
                foreach ($missing_month as $month) {

                    $unavilable_month[$index] = [
                        'kiasi' => 0,
                        'mwezi' => $month,
                        'mwaka' => $mwaka,
                        'jina_kamili' => ''
                    ];


                    $index++;
                }

                $sorted_data = [];
                $merged_data = array_merge($zaka->toArray(), $unavilable_month);

                foreach($merged_data as $month){

                    if($month['mwezi'] == 'January') {
                        $sorted_data[0] = $month;
                    }

                    if($month['mwezi'] == 'February') {
                        $sorted_data[1] = $month;
                    }

                    if($month['mwezi'] == 'March') {
                        $sorted_data[2] = $month;
                    }

                    if($month['mwezi'] == 'April') {
                        $sorted_data[3] = $month;
                    }

                    if($month['mwezi'] == 'May') {
                        $sorted_data[4] = $month;
                    }

                    if($month['mwezi'] == 'June') {
                        $sorted_data[5] = $month;
                    }

                    if($month['mwezi'] == 'July') {
                        $sorted_data[6] = $month;
                    }

                    if($month['mwezi'] == 'August') {
                        $sorted_data[7] = $month;
                    }

                    if($month['mwezi'] == 'September') {
                        $sorted_data[8] = $month;
                    }

                    if($month['mwezi'] == 'October') {
                        $sorted_data[9] = $month;
                    }

                    if($month['mwezi'] == 'November') {
                        $sorted_data[10] = $month;
                    }

                    if($month['mwezi'] == 'December') {
                        $sorted_data[11] = $month;
                    }

                }
                $new = collect($sorted_data);

                return  $mwanafamila->zaka = $new;
            });
        });


        $jumuiya_data = Jumuiya::firstWhere('jina_la_jumuiya', $jumuiya);
//        dd(array_column($familia->toArray(), 'wanafamilias'));

        return Excel::download(new ZakaKwaMweziWanaJumuiyaHusika($familia, $jumuiya_data,$mwaka),"zaka_kwa_mwezi_jumuiya_$jumuiya.mwezi_$mwaka._husika.xlsx");

    }
}
