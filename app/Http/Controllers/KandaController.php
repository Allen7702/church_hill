<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kanda;
use App\Mwanafamilia;
use App\Imports\KandaImport;
use App\Jumuiya;
use App\CentreDetail;
use App\SahihishaKanda;
use Auth;
use Validator;
use Str;
use DB;

class KandaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data_kanda = Kanda::latest()->get();

        return view('backend.kanda.kanda',compact(['data_kanda']));
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
        //validating data
        $must = [
            'jina_la_kanda' => 'required|unique:kandas',
            'herufi_ufupisho' => 'required|regex:/^[a-zA-Z]+$/u|unique:kandas',
        ];

        //validating errors
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Jina la kanda limeshatumika, herufi zimeshatumika hakikisha herufi pekee zinatumika na si tarakimu."]);
        }
        else{
            //uniqueness
            $uniqueness = time().\uniqid();

            //getting data
            $data_save = [
                'jina_la_kanda' => strtoupper($request->jina_la_kanda),
                'herufi_ufupisho' => strtoupper($request->herufi_ufupisho),
                'slug' => Str::slug($request->jina_la_kanda),
                'comment' => $request->comment,
                'uniqueness' => $uniqueness,
            ];

            //saving data
            $success = Kanda::create($data_save);

            if($success){

                $parokia = $this->getCentreDetails();

                //TODO
                // //parokia details should not be empty
                // if(!($parokia == "")){

                //     //checking if the kanda is not exists

                //     if(!(DB::connection('pgsql')->table('kanda')->where('jina_la_kanda',$request->jina_la_kanda)->where('jina_la_parokia',$parokia)->exists())){
                //         //external data
                //         $data_external = [
                //             'jina_la_kanda' => $request->jina_la_kanda,
                //             'uniqueness' => $uniqueness,
                //             'maelezo' => $request->comment,
                //             'jina_la_parokia' => $parokia,
                //         ];

                //         //creating kanda in external db
                //         DB::connection('pgsql')->table('kanda')->insert($data_external);
                //     }      
                // }

                $kanda = strtoupper($request->jina_la_kanda);
                //
                
                $message = "Ongezo";
                $data = "Ongezo la".get_kanda()->name." ".$kanda;
                $this->setActivity($message,$data);
                return response()->json(['success'=>[get_kanda()->name." imeongezwa kikamilifu kikamilifu."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kusajili".get_kanda()->name." jaribu tena."]]);
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
        $data = Kanda::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kanda $model)
    {
        //getting old data
        $existing = $model::findOrFail($request->hidden_id);

        //validating data
        $must = [
            'jina_la_kanda' => 'required|unique:kandas,jina_la_kanda,'.$existing->id,
            'herufi_ufupisho' => 'required|regex:/^[a-zA-Z]+$/u|unique:kandas,herufi_ufupisho,'.$existing->id,
        ];

        //validating errors
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Jina la ".get_kanda()->name." limeshatumika."]);
        }

        else{
            
            //getting data
            $data_update = [
                'jina_la_kanda' => strtoupper($request->jina_la_kanda),
                'herufi_ufupisho' => strtoupper($request->herufi_ufupisho),
                'slug' => Str::slug($request->jina_la_kanda),
                'comment' => $request->comment,
            ];

            //saving data
            $success = Kanda::whereId($request->hidden_id)->update($data_update);

            //================================== IMPORTANT ====================================
            if($success){

                //TODO
                // //updating the centralized database 
                // $data_external = [
                //     'jina_la_kanda' => ucfirst(strtolower($request->jina_la_kanda)),
                //     'maelezo' => $request->comment,
                //     'uniqueness' => $existing->uniqueness,
                //     'jina_la_parokia' => $existing->jina_la_parokia,
                // ];

                // //updating data now
                // $statue = DB::connection('pgsql')->table('kanda')->where('uniqueness',$existing->uniqueness)->update($data_external);

                //now updating the all the members who had the previous letters
                $old_letters = $existing->herufi_ufupisho;
                $new_letters = $request->herufi_ufupisho;

                if($old_letters != $new_letters){
                    //changing the details of members in mwanafamilia table
                    $all_members = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$old_letters.'%')->get();

                    if($all_members->isNotEmpty()){
                        //obtain the data first
                        foreach($all_members as $member){
                            $obtained_details[] = [
                                'muumini_id' => $member->id,
                                'namba_mpya' =>  "$new_letters".preg_replace("/[^0-9]/", "", $member->namba_utambulisho),
                            ];
                        }

                        //now updating all members
                        foreach($obtained_details as $row){
                            Mwanafamilia::whereId($row['muumini_id'])->update(['namba_utambulisho'=>$row['namba_mpya']]);
                        }
                    }
                }

                //============================================================================

                $kanda = strtoupper($request->jina_la_kanda);
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la ".get_kanda()->name." ". $kanda;
                $this->setActivity($message,$data);
                return response()->json(['success'=>[get_kanda()->name." imebadilishwa kikamilifu."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili ".get_kanda()->name." jaribu tena."]]);
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
        //getting data
        $data = Kanda::findOrFail($id);
        $kanda = $data->jina_la_kanda;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa" .get_kanda()->name." ".$kanda;
            $this->setActivity($message,$data);
            return response()->json(['success'=>[get_kanda()->name." imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>[get_kanda()->name." haijafutwa jaribu tena.."]]);
        }
    }

    //function to view all the jumuiya belongs to specific kanda
    public function jumuiya_kanda_husika($kanda){
        $title = "Orodha ya jumuiya $kanda";
        $data_jumuiya = Jumuiya::where('jina_la_kanda',$kanda)->get(); 
        return view('backend.kanda.jumuiya_kanda_husika',compact(['title','data_jumuiya','kanda']));
    }

    //importing kanda
    public function kanda_import(Request $request){
        $file = $request->file('file')->store('import');

        // Excel::import(new KandaImport, $file);
        $import = new KandaImport;
        $import->import($file);

        $errors = $import->errors();

        if($import->failures()->isNotEmpty()){
            return back()->withFailures($import->failures());
        }

        return back()->withStatus(get_kanda()->name.' zimeongezwa kikamilifu.',compact(['errors']));
    }


    //method for tracking activity
    private function setActivity($message,$data){
        $model = new Kanda; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }

    //getting the details of centre
    public function getCentreDetails(){
        $parokia = CentreDetail::value('centre_name');
        return $parokia;
    }
}
