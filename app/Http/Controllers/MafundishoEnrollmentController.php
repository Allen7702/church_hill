<?php

namespace App\Http\Controllers;

use App\Exports\MafundishoExport;
use App\Exports\vyama\VyamaKitume;
use App\Imports\JumuiyaImport;
use App\Imports\MafundishoImport;
use App\MafundishoEnrollment;
use App\Mwanafamilia;
use App\SadakaZaMisa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as RequestFilter;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Excel;
use RealRashid\SweetAlert\Facades\Alert;

class MafundishoEnrollmentController extends Controller
{

    public function index(string $year, string $type)
    {
        $validation = Validator::make(['year' => $year, 'type' => $type],[
            'year' => ['required', 'date_format:Y'],
            'type' => ['required', Rule::in(['ndoa', 'komunio', 'kipaimara'])],
        ]);

        if($validation->fails()){
            Alert::toast("Hakikisha data zilizopitishawa ziko sahihi","info")->autoClose(6000);
            return back();
        }

        $filters= RequestFilter::only(['status']);

        $enrollments = MafundishoEnrollment::where(['year'=> $year, 'type'=> $type]);

            if(!empty($filters)) {
                $enrollments->where($filters);
            }


        $wanafamilia = Mwanafamilia::latest('mwanafamilias.created_at')->leftJoin('familias','mwanafamilias.familia','=','familias.id')->get(['mwanafamilias.id', 'mwanafamilias.namba_utambulisho','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        return view('backend.mafundisho_enrollment.index',
            ['title' => 'Mafundisho ya '. $type,
                'type' => $type,
                'year' => $year,
                'enrollements' => $enrollments->get(),
                'wanafamilia' => $wanafamilia
                ]);
    }

    public function store(Request $request)
    {


        $request->validate([
            'namba_utambulisho.*' => ['required','exists:mwanafamilias,namba_utambulisho'],
            'type' => ['required','string', Rule::in(['ndoa','komunio', 'kipaimara'])],
            'year_filter' => ['date_format:Y','required'],
            'status' => ['required', Rule::in(['fresher','graduated'])],
            'started_at' => ['required', 'date'],
            'ended_at' => ['sometimes'],
            'partner_name'=> ['sometimes', 'max:255'],
            'partner_jumuiya'=> ['sometimes', 'max:255'],
            'partner_phone'=> ['sometimes', 'max:255'],
        ]);

        foreach ($request['namba_utambulisho'] as $member_id){

            $mwanafamilia = Mwanafamilia::firstWhere('namba_utambulisho', $member_id);

            $request['status'] == 'fresher'? $mwanafamilia->update([$request['type'] => 'bado']): $mwanafamilia->update([$request['type'] => 'tayari']);

            MafundishoEnrollment::firstOrCreate([
                'mwanafamilia_id' => $mwanafamilia->id,
                'year' => $request['year_filter'],
                'type' => $request['type'],
            ],[
                'status' => $request['status'],
                'started_at' => $request['started_at'],
                'ended_at' => $request['ended_at'],
                'partner_name'=> $request['partner_name'],
                'partner_jumuiya'=> $request['partner_jumuiya'],
                'partner_phone'=> $request['partner_phone'],
            ]);
        }

        return back()->withStatus('Wanafunzi zimeongezwa kikamilifu kwenye mafunisho ya '. $request['type']);
    }


    public function downloadExcel(string $year, string $type)
    {
        $validation = Validator::make(['year' => $year, 'type' => $type],[
            'year' => ['required', 'date_format:Y'],
            'type' => ['required', Rule::in(['ndoa', 'komunio', 'kipaimara'])]
        ]);

        if($validation->fails()){
            Alert::toast("Hakikisha data zilizopitishawa ziko sahihi","info")->autoClose(6000);
            return back();
        }

        $enrollments = MafundishoEnrollment::where(['year'=> $year, 'type'=> $type])->get();

        return Excel::download(new MafundishoExport($enrollments, $year, $type),"mafundisho_ya_".$type."_mwaka_".$year.".xlsx");

    }


    public function downloadPDF(string $year, string $type)
    {
        $validation = Validator::make(['year' => $year, 'type' => $type],[
            'year' => ['required', 'date_format:Y'],
            'type' => ['required', Rule::in(['ndoa', 'komunio', 'kipaimara'])]
        ]);

        if($validation->fails()){
            Alert::toast("Hakikisha data zilizopitishawa ziko sahihi","info")->autoClose(6000);
            return back();
        }

        $enrollments = MafundishoEnrollment::where(['year'=> $year, 'type'=> $type])->get();

        $pdf = PDF::setPaper('a4')->loadView('backend.reports.mafundisho',
            ['title'=> 'Mafundisho ya '. $type. ' mwaka '.$year,'enrollments' => $enrollments]);

        return $pdf->stream("mafundisho_ya_".$type."_mwaka_".$year.".pdf");

    }


    public function update(Request $request, MafundishoEnrollment $mafundisho_enrollment)
    {
        $request->validate([
            'type' => ['required','string', Rule::in(['ndoa','komunio', 'kipaimara'])],
            'year_filter' => ['date_format:Y','required'],
            'status' => ['required', Rule::in(['fresher','graduated'])],
            'started_at' => ['required', 'date'],
            'ended_at' => ['sometimes'],
            'partner_name'=> ['sometimes', 'max:255'],
            'partner_jumuiya'=> ['sometimes', 'max:255'],
            'partner_phone'=> ['sometimes', 'max:255'],
        ]);

        $mafundisho_enrollment->update([
            'year' => $request['year_filter'],
            'type' => $request['type'],
            'status' => $request['status'],
            'started_at' => $request['started_at'],
            'ended_at' => $request['ended_at'],
            'partner_name'=> $request['partner_name'],
            'partner_jumuiya'=> $request['partner_jumuiya'],
            'partner_phone'=> $request['partner_phone'],
        ]);

        $mwanafamilia = Mwanafamilia::find($mafundisho_enrollment->mwanafamilia_id);

        $request['status'] == 'fresher'? $mwanafamilia->update([$request['type'] => 'bado']): $mwanafamilia->update([$request['type'] => 'tayari']);

        return back()->withStatus('Wanafunzi zimeongezwa kikamilifu kwenye mafunisho ya '. $request['type']);
    }


    public function destroy(MafundishoEnrollment $mafundisho_enrollment)
    {
        $mafundisho_enrollment->delete();

        return back()->withStatus('Mwanafunzi amefutwa kikamilifu.');
    }

    public function import(Request $request)
    {
        $file = $request->file('file')->store('import');

        // Excel::import(new JumuiyaImport, $file);
        $import = new MafundishoImport;
        $import->import($file);

        $errors = $import->errors();

        if(!$errors){
            Alert::info("<b class='text-primary'>Taarifa</b>","Wanafunzi hawajawekwa kwakua kuna tatizo kwenye taarifa ulizojaza..");
            return back();
        }

        if($import->failures()->isNotEmpty()){
            return back()->withFailures($import->failures());
        }

        return back()->withStatus('Wanafunzi zimeongezwa kikamilifu.',compact(['errors']));
    }

    public function takwimu(string $type)
    {
        $validation = Validator::make(['type' => $type],[
            'type' => ['required', Rule::in(['ndoa', 'komunio', 'kipaimara'])]
        ]);

        if($validation->fails()){
            Alert::toast("Hakikisha data zilizopitishawa ziko sahihi","info")->autoClose(6000);
            return back();
        }

        $filters= RequestFilter::only(['year', 'sadaka', 'misa', 'type']);

        $enrollments = MafundishoEnrollment::select(DB::raw("COUNT(*) as total"),'type', 'status',
            'year')->where('type',$type)->orderBy('year', 'DESC')->groupBy(['type','year', 'status'])->filter($filters)->get();
        $wanafamilia = Mwanafamilia::latest('mwanafamilias.created_at')->leftJoin('familias','mwanafamilias.familia','=','familias.id')->get(['mwanafamilias.id', 'mwanafamilias.namba_utambulisho','mwanafamilias.jina_kamili','familias.jina_la_jumuiya']);

        return view('backend.mafundisho_enrollment.mafundisho_takwimu',
            ['title' => 'Mafundisho ya '. $type,
                'year' => isset($filters['year'])?$filters['year']: null,
                'type' => $type,
                'enrollements' => $enrollments,
                'wanafamilia' => $wanafamilia
            ]);
    }

}
