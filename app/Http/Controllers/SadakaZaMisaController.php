<?php

namespace App\Http\Controllers;

use App\CentreDetail;
use App\Exports\MafundishoExport;
use App\Exports\SadakaZaMisaExport;
use App\Imports\MafundishoImport;
use App\Jumuiya;
use App\Kanda;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Excel;
use App\SadakaZaMisa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Request as RequestFilter;

class SadakaZaMisaController extends Controller
{
    public function index()
    {
       $filters= RequestFilter::only(['year', 'sadaka', 'misa', 'type']);

        return view('backend.sadaka.sadaka_za_misa',
            ['title' => 'Sadaka Za Misa ',
            'filters' => RequestFilter::all(['year', 'sadaka', 'misa', 'type']),
            'sadaka_za_misa' => SadakaZaMisa::orderBy('ilifanyika', 'DESC')
                ->filter($filters)
                ->get(),
                'year' => isset($filters['year'])?$filters['year']: null,
                'sadaka' => isset($filters['sadaka'])?$filters['sadaka']: null,
                'misa' => isset($filters['misa'])?$filters['misa']: null,
                'type' => isset($filters['type'])?$filters['type']:null,
                ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'description' => ['sometimes', 'required',],
            'sadaka' => ['required','numeric', 'exists:aina_za_sadakas,id'],
            'misa' => ['required','numeric', 'exists:aina_za_misas,id'],
            'ilifanyika' => ['required', 'date'],
            'kiasi' => ['required', 'numeric'],
            'eneo' => ['required', 'string', Rule::in(['kanda', 'jumuiya', 'parokia'])],
            'jumuiya' => ['required_if:eneo,jumuiya', 'numeric','exists:jumuiyas,id'],
            'kanda' => ['required_if:eneo,kanda', 'numeric','exists:kandas,id']
        ]);

        $misaable_tpye = null;
        $misaable_id = null;

        if($request['eneo'] == 'jumuiya'){
            $misaable_tpye = Jumuiya::class;
            $misaable_id = $request['jumuiya'];
        }

        if($request['eneo'] == 'kanda'){
            $misaable_tpye = Kanda::class;
            $misaable_id = $request['kanda'];
        }

        if($request['eneo'] == 'parokia'){
            $misaable_tpye = CentreDetail::class;
            $misaable_id = (CentreDetail::first())->id;
        }

   SadakaZaMisa::create([
            'description' => $request['description'],
            'kiasi' => $request['kiasi'],
            'ilifanyika' => $request['ilifanyika'],
            'aina_za_misa_id' => $request['misa'],
            'aina_za_sadaka_id' => $request['sadaka'],
            'misaable_type' => $misaable_tpye,
            'misaable_id' => $misaable_id,
        ]);

        return back()->withStatus('Sadaka za misa zimeongezwa kikamilifu.');
    }


    public function downloadExcel()
    {

        $filters= RequestFilter::only(['year', 'sadaka', 'misa', 'type']);
        $sadaka_za_misa = SadakaZaMisa::orderBy('ilifanyika', 'DESC')
            ->filter($filters)
            ->get();

        $year = isset($filters['year'])?$filters['year']: Carbon::now()->format('Y');

        return Excel::download(new SadakaZaMisaExport($sadaka_za_misa,
           isset($filters['year'])?$filters['year']: null,
                 isset($filters['sadaka'])?$filters['sadaka']: null,
               isset($filters['misa'])?$filters['misa']: null,),
            "sadaka_za_misa_mwaka_".$year.".xlsx");

    }


    public function downloadPDF()
    {
        $filters= RequestFilter::only(['year', 'sadaka', 'misa', 'type']);
        $sadaka_za_misa = SadakaZaMisa::orderBy('ilifanyika', 'DESC')
            ->filter($filters)
            ->get();

        $year = isset($filters['year'])?$filters['year']: Carbon::now()->format('Y');

        $pdf = PDF::setPaper('a4')->loadView('backend.reports.sadaka_za_misa',
            ['title'=> 'Sadaka za Misa  mwaka '.$year,'sadaka_za_misa' => $sadaka_za_misa]);

        return $pdf->stream("sadaka_za_misa_mwaka_".$year.".pdf");

    }


    public function update(Request $request, SadakaZaMisa $sadaka_za_misa)
    {
        $request->validate([
            'description' => ['sometimes', 'required',],
            'sadaka' => ['required','numeric', 'exists:aina_za_sadakas,id'],
            'misa' => ['required','numeric', 'exists:aina_za_misas,id'],
            'ilifanyika' => ['required', 'date'],
            'kiasi' => ['required', 'numeric'],
            'eneo' => ['required', 'string', Rule::in(['kanda', 'jumuiya', 'parokia'])],
            'jumuiya' => ['required_if:eneo,jumuiya', 'numeric','exists:jumuiyas,id'],
            'kanda' => ['required_if:eneo,kanda', 'numeric','exists:kandas,id']
        ]);

        $misaable_tpye = null;
        $misaable_id = null;

        if($request['eneo'] == 'jumuiya'){
            $misaable_tpye = Jumuiya::class;
            $misaable_id = $request['jumuiya'];
        }

        if($request['eneo'] == 'kanda'){
            $misaable_tpye = Kanda::class;
            $misaable_id = $request['kanda'];
        }

        $sadaka_za_misa->update([
            'description' => $request['description'],
            'kiasi' => $request['kiasi'],
            'ilifanyika' => $request['ilifanyika'],
            'aina_za_misa_id' => $request['misa'],
            'aina_za_sadaka_id' => $request['sadaka'],
            'misaable_type' => $misaable_tpye,
            'misaable_id' => $misaable_id,
        ]);


        return back()->withStatus('Sadaka za misa zimeongezwa kikamilifu.');
    }


    public function destroy(SadakaZaMisa $sadaka_za_misa)
    {
        $sadaka_za_misa->delete();

        return back()->withStatus('Sadaka ya misa imefutwa kikamilifu.');
    }


    public function takwimu()
    {
        $sadaka_za_misa = SadakaZaMisa::select(DB::raw("SUM(kiasi) as kiasi"),'misaable_type', 'misaable_id',
            DB::raw("MONTH(ilifanyika) as mwezi"),DB::raw("MONTH(ilifanyika) as mwaka")  )
                        ->orderBy(DB::raw("YEAR(ilifanyika)"),'DESC')
                        ->groupBy('misaable_type')->get();

        $total = SadakaZaMisa::select(DB::raw("SUM(kiasi) as kiasi"))
            ->where(DB::raw("YEAR(ilifanyika)"), Carbon::now()->format('Y'))
            ->first();

        return view('backend.sadaka.sadaka_za_misa_takwimu',
            ['title' => 'Sadaka Za Misa Mwaka '. Carbon::now()->format('Y'),
                'type' => null,
                'year' => Carbon::now()->format('Y'),
                'sadaka' => null,
                'misa' => null,
                'total' => $total->kiasi,
                'sadaka_za_misa' => $sadaka_za_misa]);
    }

}
