<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Exports\viongozi\OrodhaIdadiViongozi;
use App\Exports\viongozi\OrodhaViongozi;
use App\Exports\viongozi\OrodhaViongoziHusika;
use PDF;
use DB;
use Excel;

class UserReportController extends Controller
{
    //handling the idadi ya viongozi report
    public function orodha_idadi_viongozi_pdf(){
        $title = "ORODHA YA IDADI YA VIONGOZI";
        
        $viongozi_total = User::where('ngazi','!=','administrator')->count();

        $data_viongozi = User::whereNotNull('cheo')->select(DB::raw("COUNT(cheo) as idadi"),'cheo')
            ->groupBy('cheo')
            ->get(['cheo','idadi']);
            
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.orodha_idadi_viongozi',compact(['title','data_viongozi','viongozi_total']));
        return $pdf->stream("idadi_ya_viongozi.pdf");
    }

    //function to handle the list of viongozi
    public function orodha_viongozi_pdf(){
        $title = "ORODHA YA VIONGOZI";
        $viongozi_total = User::where('ngazi','!=','administrator')->count();
        $data_viongozi = User::latest()->where('ngazi','!=','administrator')->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.orodha_viongozi',compact(['data_viongozi','title','viongozi_total']));
        return $pdf->stream("orodha_viongozi.pdf");
    }

    //function to handle the list of viongozi
    public function orodha_viongozi_husika_pdf($id){
        $title = "Orodha ya viongozi wa $id";
        $cheo = $id;
        $viongozi_total = User::latest()->where('ngazi','!=','administrator')->where('cheo',$cheo)->count();
        $data_viongozi = User::latest()->where('ngazi','!=','administrator')->where('cheo',$cheo)->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.orodha_viongozi_husika',compact(['data_viongozi','cheo','title','viongozi_total']));
        return $pdf->stream("orodha_viongozi_husika.pdf");
    }

    //handling the idadi ya viongozi excel pdf
    public function orodha_idadi_viongozi_excel(){
        return Excel::download(new OrodhaIdadiViongozi(),"orodha_idadi_viongozi.xlsx");
    }

    //function to handle the list of viongozi excel
    public function orodha_viongozi_excel(){
        return Excel::download(new OrodhaViongozi(),"orodha_viongozi.xlsx");
    }

    //function to handle the list of viongozi excel
    public function orodha_viongozi_husika_excel($id){
        return Excel::download(new OrodhaViongoziHusika($id),"orodha_viongozi_husika.xlsx");
    }
}
