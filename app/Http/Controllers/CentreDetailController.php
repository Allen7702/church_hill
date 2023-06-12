<?php

namespace App\Http\Controllers;

use App\Denomination;
use App\User;
use App\Zaka;
use Illuminate\Http\Request;
use App\CentreDetail;
use App\Mwanafamilia;
use Spatie\Activitylog\Models\Activity;
use Validator;
use Alert;

use App\Exports\JumuiyaZakaIngiza as ExportsJumuiyaZakaIngiza;
use App\Exports\JumuiyaMwanafamilia;
use App\Jumuiya;
use File;
use Image;
use DB;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;


class CentreDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    
    {
        //
        $details = CentreDetail::latest()->limit(1)->get();
        return view('backend.system.centre_detail',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //data validation
        $rules = [
            'centre_name' => 'required|unique:centre_details',
            'address' => 'required',
            'region' => 'required',
            'country' => 'required',
            'telephone1' => 'required',
            'jimbo' => 'required',
        ];

        $error = Validator::make($request->all(),$rules);

        if($error->fails()){
            Alert::toast("Unapaswa kujaza taarifa muhimu katika fomu. Pitia upya","info");
            return back();
        }

        else{

            //getting the photo
            $photo = $request->file('photo');

            if($photo != ""){

                //validating photo
                $photo_rules = [
                    'photo' => 'required|mimes:png,gif,jpeg,jpg|max:4096',
                ];

                $photo_errors = Validator::make($request->all(),$photo_rules);

                if($photo_errors->fails()){
                    Alert::info("Tafadhali aina ya picha inapaswa kuwa PNG,JPEG,JPG isiyozidi 4MB.");
                    return back();
                }

                $photo_image = Image::make($photo);
                $photo_destination = public_path('/uploads/images/');
                $photo_file = time().uniqid().".".$photo->getClientOriginalExtension();//to save in db
                $photo_file_report = "report_logo".time().uniqid().".".$photo->getClientOriginalExtension();//to save in db
                // $resized = $photo_image->resize(406,145);
                // $resized->save($photo_destination.$photo_file);
                $photo_image->save($photo_destination.$photo_file);
                // $resized_report = $photo_image->resize(406,145);
                $photo_image->save($photo_destination.$photo_file_report);
            }
            else{
                $photo_file = "atlais_logo.png";
            }

            $unique = time().uniqid();

            $data_save = [
                'centre_name' => strtoupper($request->centre_name),
                'address' => $request->address,
                'email' => $request->email,
                'region' => $request->region,
                'country' => $request->country,
                'telephone1' => $request->telephone1,
                'telephone2' => $request->telephone2,
                'jimbo' => strtoupper($request->jimbo),
                'photo' => $photo_file,
                'uniqueness' => $unique,
            ];

            //saving data now
            $success = CentreDetail::create($data_save);

            if($success){

                //creating data to external database
                $data_external = [
                    'jina_la_parokia' => strtoupper($request->centre_name),
                    'mkoa' => $request->region,
                    'mawasiliano' => $request->telephone1,
                    'uniqueness' => $unique,
                    'jimbo' => strtoupper($request->jimbo),
                ];

                //TODO
                //checking if existing
                // if(!(DB::connection('pgsql')->table("parokia")->where('jina_la_parokia',$request->jina_la_parokia)->exists())){
                //     DB::connection('pgsql')->table("parokia")->insert($data_external);
                // }

                Alert::toast("Taarifa za parokia zimeongezwa kikamilifu.","success");
                return redirect('/home');
            }
            else{
                Alert::toast("Taarifa za parokia hazijawekwa jaribu tena.","info");
                return back();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CentreDetail $model)
    {
        //getting old data
        $existing = $model::findOrFail($request->hidden_id);

        //getting old photo
        $old_photo = $existing->photo;

         //data validation
        $rules = [
            'centre_name' => 'sometimes|unique:centre_details,centre_name,'.$existing->id,
            'address' => 'required',
            'region' => 'required',
            'country' => 'required',
            'telephone1' => 'required',
            'jimbo' => 'required',
        ];

        $error = Validator::make($request->all(),$rules);

        if($error->fails()){
            Alert::toast("Unapaswa kujaza taarifa muhimu katika fomu. Pitia upya","info");
            return back();
        }

        else{

            //getting the photo details
            $photo = $request->file('photo');

            if($photo != ""){

                //validating photo
                $photo_rules = [
                    'photo' => 'required|mimes:png,gif,jpeg,jpg|max:4096',
                ];

                $photo_errors = Validator::make($request->all(),$photo_rules);

                if($photo_errors->fails()){
                    Alert::info("Tafadhali aina ya picha inapaswa kuwa PNG,JPEG,JPG isiyozidi 4MB.");
                    return back();
                }

                $photo_image = Image::make($photo);
                $photo_destination = public_path('/uploads/images/');
                $photo_file = time().uniqid().".".$photo->getClientOriginalExtension();//to save in db
                // $resized = $photo_image->resize(406,145);
                $photo_image->save($photo_destination.$photo_file);
                // $resized->save($photo_destination.$photo_file);
                // $resized->save($photo_destination.$photo_file);

                //deleting the old image
                $old_image = public_path('/uploads/images/').$old_photo;
                $church_photo = public_path('/uploads/images/atlais_logo.png');

                //deleting the old image
                if(strcmp($old_image,$church_photo) != 0){
                    @unlink($old_image);
                }
            }
            else{
                $photo_file = $old_photo;
            }

            $data_update = [
                'centre_name' => strtoupper($request->centre_name),
                'address' => $request->address,
                'email' => $request->email,
                'region' => $request->region,
                'country' => $request->country,
                'telephone1' => $request->telephone1,
                'telephone2' => $request->telephone2,
                'jimbo' => strtoupper($request->jimbo),
                'photo' => $photo_file,
            ];

            //saving data now
            $success = CentreDetail::whereId($request->hidden_id)->update($data_update);

            if($success){
                //updating the remote db

                $external_update = [
                    'jina_la_parokia' => strtoupper($request->centre_name),
                    'mkoa' => $request->country,
                    'mawasiliano' => $request->telephone1,
                    'uniqueness' => $existing->uniqueness,
                    'mkoa' => $request->region,
                    'jimbo' => strtoupper($request->jimbo),
                ];

                //TODO
                //DB::connection('pgsql')->table("parokia")->where('uniqueness',$existing->uniqueness)->where('jina_la_parokia',$existing->centre_name)->update($external_update);

                Alert::toast("Taarifa za parokia zimebadilishwa kikamilifu.","success");
                return back();
            }
            else{
                Alert::info("Taarifa za parokia hazijabadilishwa jaribu tena.","info");
                return back();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function documents(){


        $jumuiyas = Jumuiya::all();
        return view('backend.mengineyo.nyaraka',compact('jumuiyas'));
    }


    public function pakua_jumuiya(Request $request) 
    {
        $jumuiya=$request->jina_la_jumuiya;

        $jumuiya=trim(str_replace("\\t",'',$jumuiya));

        return Excel::download(new ExportsJumuiyaZakaIngiza($jumuiya), $jumuiya.'.xlsx', ExcelExcel::XLSX);
    }


    public function pakua_jumuiya_mwanafamilia(Request $request)
    {
        
        $jumuiya=$request->jina_la_jumuiya;

        $jumuiya=trim(str_replace("\\t",'',$jumuiya));

        return Excel::download(new JumuiyaMwanafamilia($jumuiya), $jumuiya.'.xlsx', ExcelExcel::XLSX);
    }

    public function tafuta(Request $request){
        $key = trim($request->get('q'));

        $data_tafuta = Mwanafamilia::query()->latest()
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('mwanafamilias.id','like',"%{$key}%")
        ->orWhere('mwanafamilias.jina_kamili','like',"%{$key}%")
        ->orWhere('mwanafamilias.mawasiliano','like',"%{$key}%")
        ->orWhere('familias.jina_la_familia','like',"%{$key}%")
        ->orWhere('familias.jina_la_jumuiya','like',"%{$key}%")
        ->orWhere('mwanafamilias.ndoa','like',"%{$key}%")
        ->orWhere('mwanafamilias.jinsia','like',"%{$key}%")
        ->orWhere('mwanafamilias.ubatizo','like',"%{$key}%")
        ->orWhere('mwanafamilias.ekaristi','like',"%{$key}%")
        ->orWhere('mwanafamilias.cheo_familia','like',"%{$key}%")
        ->orWhere('mwanafamilias.kipaimara','like',"%{$key}%")
        ->orWhere('mwanafamilias.komunio','like',"%{$key}%")
        ->orWhere('mwanafamilias.aina_ya_ndoa','like',"%{$key}%")
        ->orWhere('mwanafamilias.taaluma','like',"%{$key}%")
        ->orWhere('mwanafamilias.namba_ya_cheti','like',"%{$key}%")
        ->orWhere('mwanafamilias.parokia_ya_ubatizo','like',"%{$key}%")
        ->orWhere('mwanafamilias.jimbo_la_ubatizo','like',"%{$key}%")
        ->orWhere('mwanafamilias.namba_utambulisho','like',"%{$key}%")
        ->orWhere('mwanafamilias.cheo_familia','like',"%{$key}%")
        ->orWhere('mwanafamilias.created_at','like',"%{$key}%")
        ->get(['mwanafamilias.*','familias.jina_la_familia','familias.jina_la_jumuiya']);

        if($data_tafuta->isEmpty()){
            Alert::info("<b class='text-info'>Taarifa!</b>","Hakuna taarifa husika kulingana na utafutaji..");
            return back();
        }

        else{
            $title = "Matokeo ya utafutaji..";
            return view('backend.mengineyo.utafutaji',compact(['data_tafuta','title']));
        }

    }

    public function activityLogs()
    {
        $activities = Activity::all();
        return view('backend.activity_logs',['activities' => $activities,'title' => 'Matukio yaliyo tokea katika mfumo']);
    }

    public function activityLogsZaka()
    {
        $activities = Activity::select('*', DB::raw('count(*) as total'))->where('subject_type', 'App\Zaka')->orderBy('created_at', 'asc')->groupBy('created_at')->get();

        return view('backend.activity_logs_zaka',['activities' => $activities,'title' => 'Matukio yaliyo tokea katika mfumo (Zaka)']);
    }

    public function activityLogsZakaDetails(int $id, string $date)
    {


       $denominations = Denomination::where('mwekaji',$user->jina_kamili)->where('created_at','like', '%'. date("Y-m-d",strtotime($date)).'%')->get();

        return view('backend.activity_logs_zaka_details',['activities' => $denominations,'title' => 'Matukio yaliyo tokea katika mfumo (Zaka)']);
    }

    public function activityLogsZakaDetailsJumuiya(string $date, string $id)
    {

        $user = User::firstWhere('jina_kamili', $id);

        $denominations = Zaka::select('zakas.*', 'mwanafamilias.jina_kamili as jina_la_mwanajumuiya')
            ->where('imewekwa',$user->email)
            ->where('zakas.created_at','like', date("Y-m-d",strtotime($date)) .'%')
            ->join('mwanafamilias','zakas.mwanajumuiya', '=', 'mwanafamilias.id')
            ->get();

        return view('backend.activity_logs_zaka_details_wanajumuiya',['activities' => $denominations,'title' => 'Matukio yaliyo tokea katika mfumo (Zaka)']);
    }
}
