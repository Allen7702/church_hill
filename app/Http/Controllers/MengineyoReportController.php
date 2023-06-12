<?php

namespace App\Http\Controllers;

use App\AinaZaSadaka;
use App\Exports\mengineyo\MengineyoAinaSadaka;
use Illuminate\Http\Request;
use Excel;
use PDF;
use App\MaliZaKanisa;
use App\AinaZaMali;
use App\AinaZaMisa;
use App\Huduma;
use App\WatoaHuduma;
use App\AkauntiZaBenki;
use App\Exports\mengineyo\MengineyoMaliKanisa;
use App\Exports\mengineyo\MengineyoAinaMali;
use App\Exports\mengineyo\MengineyoAinaHuduma;
use App\Exports\mengineyo\MengineyoWatoaHuduma;
use App\Exports\mengineyo\MengineyoBenki;
use App\Exports\mengineyo\MengineyoAinaMisa;

class MengineyoReportController extends Controller
{
    //function to handle mali za kanisa pdf
    public function mali_za_kanisa_pdf(){
        $title = "Ripoti ya mali za kanisa";
        $data_mali = MaliZaKanisa::latest()->get();
        $mali_total = MaliZaKanisa::sum('thamani');
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.mengineyo_mali_kanisa',compact(['title','data_mali','mali_total']));
        return $pdf->stream("mali_za_kanisa.pdf");
    }

    //function to handle aina za mali pdf
    public function aina_za_mali_pdf(){
        $title = "Ripoti ya aina za mali";
        $data_aina = AinaZaMali::latest()->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.mengineyo_aina_mali',compact(['title','data_aina']));
        return $pdf->stream("aina_za_mali.pdf");
    }

    //function to handle aina za huduma pdf
    public function aina_za_huduma_pdf(){
        $title = "Ripoti ya aina za huduma";
        $data_huduma = Huduma::latest()->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.mengineyo_aina_huduma',compact(['title','data_huduma']));
        return $pdf->stream("aina_za_huduma.pdf");
    }

    //function to handle watoa huduma pdf
    public function watoahuduma_pdf(){
        $title = "Ripoti ya orodha ya watoa huduma";
        $data_watoahuduma = WatoaHuduma::latest()->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.mengineyo_watoa_huduma',compact(['title','data_watoahuduma']));
        return $pdf->stream("watoa_huduma.pdf");
    }

    //function to handle akaunti za benki pdf
    public function akaunti_za_benki_pdf(){
        $title = "Ripoti ya orodha ya akaunti za benki";
        $data_akaunti_benki = AkauntiZaBenki::latest()->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.mengineyo_akaunti_benki',compact(['title','data_akaunti_benki']));
        return $pdf->stream("akaunti_za_benki.pdf");
    }

    public function aina_za_misa_pdf(){
        $title = "Ripoti ya aina za misa";
        $data_misa = AinaZaMisa::latest()->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.mengineyo_aina_misa',compact(['title','data_misa']));
        return $pdf->stream("aina_za_misa.pdf");
    }

    public function aina_za_sadaka_pdf(){
        $title = "Ripoti ya aina za sadaka";
        $data_sadaka = AinaZaSadaka::latest()->get();
        $pdf = PDF::setPaper('a4')->loadView('backend.reports.mengineyo_aina_sadaka',compact(['title','data_sadaka']));
        return $pdf->stream("aina_za_sadaka.pdf");
    }

    //function to handle mali za kanisa excel
    public function mali_za_kanisa_excel(){
        return Excel::download(new MengineyoMaliKanisa(), "mali_za_kanisa.xlsx");
    }

    //function to handle aina za mali excel
    public function aina_za_mali_excel(){
        return Excel::download(new MengineyoAinaMali(), "aina_za_mali.xlsx");
    }

    //function to handle aina za huduma excel
    public function aina_za_huduma_excel(){
        return Excel::download(new MengineyoAinaHuduma(), "aina_za_huduma.xlsx");
    }

    //function to handle watoahuduma excel
    public function watoahuduma_excel(){
        return Excel::download(new MengineyoWatoaHuduma(), "watoa_huduma.xlsx");
    }

    //function to handle akaunti za benki excel
    public function akaunti_za_benki_excel(){
        return Excel::download(new MengineyoBenki(), "akaunti_za_benki.xlsx");
    }

    //function to handle aina za misa excel
    public function aina_za_misa_excel(){
        return Excel::download(new MengineyoAinaMisa(), "aina_za_misa.xlsx");
    }

    public function aina_za_sadaka_excel(){
        return Excel::download(new MengineyoAinaSadaka(), "aina_za_sadaka.xlsx");
    }
}
