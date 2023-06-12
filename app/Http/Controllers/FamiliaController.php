<?php

namespace App\Http\Controllers;

use App\Imports\FamilyMemberCorrectionImport;
use Illuminate\Http\Request;
use App\Jumuiya;
use App\Familia;
use App\Mwanafamilia;
use App\Kikundi;
use App\Kanda;
use App\VyeoKanisa;
use Validator;
use Auth;
use Str;
use Alert;
use App\Imports\FamiliaImport;
use App\Imports\WanafamiliaImport;
use Carbon;
use App\MakundiRika;

class FamiliaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        $user_ngazi = $user->ngazi;
 

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){

            $jumuiya=Jumuiya::latest();
            $data_familia=Familia::latest();
         }elseif($user_ngazi=='Kanda'){
            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            $jumuiya = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
            $data_familia=Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
         }elseif($user_ngazi=='Jumuiya'){

            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);
            $jumuiya = $jumuiya; 
            $data_familia = Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
         } 

        $jumuiya =$jumuiya->get();

        $title = "Orodha ya Familia";
        $data_familia =$data_familia->get();

        return view('backend.familia.familia',compact(['data_familia','title','jumuiya']));
    }

    public function familia_zote(){

        $title = "Orodha ya familia jumuiya zote";
        $data_familia = Familia::latest()->get();
        $jumuiya = Jumuiya::latest()->get();
        return view('backend.familia.familia',compact(['data_familia','jumuiya']));
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
    public function store(Request $request, Familia $model)
    {
        //validating data
        $must = [
            'jina_la_familia' => 'required',
            'jina_la_jumuiya' => 'required',
           // 'mawasiliano' => 'required|unique:familias',
        ];

        //validating errors
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza jina la familia na jumuiya.."]);
        }

        else{
            //name of jumuiya
            $jumuiya = $request->jina_la_jumuiya;

            //checking if the record exists
            if(Familia::where('jina_la_familia',$request->jina_la_familia)->where('jina_la_jumuiya',$request->jina_la_jumuiya)->exists()){
                return response()->json(['errors'=>"Jina la familia katika jumuiya hiyo limeshatumika.."]);
            }

            //getting the phone
            $mawasiliano = $request->mawasiliano;

            if($mawasiliano != ""){
                //checking if the mawasiliano already used
                if(Familia::where('mawasiliano',$request->mawasiliano)->exists()){
                    return response()->json(['errors'=>"Namba ya mawasiliano imeshatumika.."]);
                }
            }

            //getting data
            $data_save = [
                'jina_la_familia' => strtoupper($request->jina_la_familia),
                'jina_la_jumuiya' => $jumuiya,
                'mawasiliano' => $request->mawasiliano,
                'slug' => Str::slug($request->jina_la_familia),
                'maoni' => $request->maoni,
            ];

            //getting kanda where by using jina la jumuiya
            $kanda_ufupisho = $this->getKandaKey($jumuiya);

            //saving data
            $success = Familia::create($data_save);

          //  if($success){

                //set the utambulisho namba
                $namba = $this->setUtambulishoNamba($kanda_ufupisho);

                //adding the family name to mwanafamilia
                $data_mwanafamilia = [
                    'jina_kamili' => strtoupper($request->jina_la_familia),
                    'namba_utambulisho' => $namba,
                    'familia' => $success->id,
                    'mawasiliano' => $request->mawasiliano,
                    'maoni' => $request->maoni,
                ];

                $sajili = Mwanafamilia::create($data_mwanafamilia);

                //incrementing the number of familia in jumuiya
                Jumuiya::where('jina_la_jumuiya',$jumuiya)->increment('idadi_ya_familia',1);

                Familia::whereId($success->id)->increment('wanafamilia',1);

                //tracking
                $message = "Ongezo";
                $data = "Ongezo la familia katika jumuiya ya $jumuiya";
                $this->setActivity($message,$data);

                if($sajili){
                    //TODO implement the sending data to external database
                    return response()->json(['success'=>["Familia imesajiliwa kikamilifu.."]]);
                }
                else{
                    return response()->json(['success'=>["Familia imesajiliwa lakini mwanfamilia hajaongezwa kikamilifu.."]]);
                }
          //  }
          //  else{
             //   return response()->json(['errors'=>["Imeshindikana kusajili familia jaribu tena."]]);
          //  }
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
        $data = Familia::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Familia $model)
    {
        //getting old data first
        $existing = Familia::findOrFail($request->hidden_id);

        //validating data
        $must = [
            'jina_la_familia' => 'required',
            'jumuiya_update' => 'required',
            // 'mawasiliano' => 'required|unique:familias,mawasiliano,'.$existing->id,
        ];

        //validating errors
        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza jina la familia na jumuiya."]);
        }

        else{
            //name of jumuiya
            $jumuiya = $request->jumuiya_update;

            //getting data
            $data_update = [
                'jina_la_familia' => strtoupper($request->jina_la_familia),
                'jina_la_jumuiya' => $jumuiya,
                'slug' => Str::slug($request->jina_la_familia),
                'mawasiliano' => $request->mawasiliano,
                'maoni' => $request->maoni,
            ];

            //saving data
            $success = Familia::whereId($request->hidden_id)->update($data_update);

           // if($success){
                // incrementing/decrementing the number of familia in jumuiya based on changes

                //updating mawasiliano in mwanafamilia and jina la familia
                $data_other_update = [
                    'mawasiliano'=>$request->mawasiliano,
                    'jina_kamili' => $request->jina_la_familia,
                ];

                Mwanafamilia::where('mawasiliano',$request->mawasiliano)->where('jina_kamili',$request->jina_la_familia)->update($data_other_update);

                if($jumuiya != $existing->jina_la_jumuiya){

                    //incrementing to newly jumuiya
                    Jumuiya::where('jina_la_jumuiya',$jumuiya)->increment('idadi_ya_familia',1);

                    //decrementing to old jumuiya
                    Jumuiya::where('jina_la_jumuiya',$existing->jina_la_jumuiya)->decrement('idadi_ya_familia',1);
                }

                //tracking
                $message = "Badiliko";
                $data = "Badiliko la familia katika jumuiya ya $jumuiya";
                $this->setActivity($message,$data);
                return response()->json(['success'=>["Familia imerekebishwa kikamilifu.."]]);
           // }
           // else{
             //   return response()->json(['errors'=>["Imeshindikana kubadili familia jaribu tena."]]);
           // }
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
        $data = Familia::findOrFail($id);
        $familia_id = $data->id;
        $familia = $data->jina_la_familia;
        $jumuiya = $data->jina_la_jumuiya;

        if($data->delete()){
            //decrementing the number of familia in jumuiya
            Jumuiya::where('jina_la_jumuiya',$jumuiya)->where('idadi_ya_familia','!=',0)->decrement('idadi_ya_familia',1);

            //updating details for mwanafamilia
            Mwanafamilia::where('jina_kamili',$familia)->where('familia',$familia_id)->delete();

            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa familia $familia";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["Familia imefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["Familia haijafutwa jaribu tena.."]]);
        }
    }

    //function to delete family members
    public function wanafamilia_destroy($id){
        $data_delete = Mwanafamilia::findOrFail($id);

        //getting data to handle the vikundi and other incase we delete mwanafamilia
        $familia = $data_delete->familia;
        $mwanafamilia_id = $data_delete->id;
        $jina_kamili = $data_delete->jina_kamili;

        if($data_delete->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa mwanafamilia kutoka familia namba $familia";
            $this->setActivity1($message,$data);

            //decrement the members in specified family
            Familia::whereId($familia)->where('wanafamilia','!=',0)->decrement('wanafamilia',1);

            return response()->json(['success'=>"Mwanafamilia amefutwa kikamilifu.."]);
        }
        else{
            return response()->json(['errors'=>"Imeshindikana kufuta taarifa jaribu tena.."]);
        }
    }

    public function familia_jumuiya_husika($id){
        $title = "Orodha ya familia jumuiya ya $id";
        $jumuiya = Jumuiya::latest()->get();
        $jina_jumuiya = $id;
        $data_familia = Familia::latest()->where('familias.jina_la_jumuiya',$id)
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->get(['mwanafamilias.mawasiliano','familias.*']);

        return view('backend.familia.familia_jumuiya_husika',compact(['title','data_familia','jumuiya','jina_jumuiya']));
    }

    //the function for dealing with mwanafamilia
    public function wanafamilia_husika($id){

        $data = Familia::findOrFail($id);
        $data_familia = Mwanafamilia::where('familias.id',$id)
        ->leftJoin('familias','familias.id','mwanafamilias.familia')
        ->get(['mwanafamilias.*','familias.jina_la_familia','familias.jina_la_jumuiya']);

        if($data_familia->isEmpty()){
            Alert::toast("Hakuna wanafamilia kwa taarifa zilizochaguliwa.","info");
            return back();
        }
        else{

            $data_title = "FAMILIA YA $data->jina_la_familia";
            $title = strtoupper($data_title);
            $familia = Familia::latest()->get();
            $vyeo = VyeoKanisa::latest()->get();
            $familia_id = $id;
            return view('backend.familia.wanafamilia_husika',compact(['data_familia','familia','vyeo','title','familia_id']));
        }
    }

    //the function to handle whole list of family
    public function wanafamilia(){

        $user = Auth::user();
        $user_ngazi = $user->ngazi;

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $familia = Familia::latest();
            $data_familia = Mwanafamilia::latest()->leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->orderBy('familias.jina_la_jumuiya','asc');
    
         }elseif($user_ngazi=='Kanda'){
            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);

            $jumuiya = Jumuiya::where('jina_la_kanda',$kanda->first()->jina_la_kanda);
            $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
            $data_familia=Mwanafamilia::whereIn('familia',$familia->pluck('id'))->leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->orderBy('familias.jina_la_jumuiya','asc');
         }elseif($user_ngazi=='Jumuiya'){
            $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
            $familia = \App\Familia::find($mwanafamilia->familia);
            $jumuiya=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
            $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya->first()->jina_la_kanda);

             $jumuiya = $jumuiya; 
             $familia = Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
             $data_familia=Mwanafamilia::whereIn('familia',$familia->pluck('id'))->leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->orderBy('familias.jina_la_jumuiya','asc');
          
         } 

        $title = "ORODHA YA WANAFAMILIA";
        $vyeo = VyeoKanisa::latest()->get();
        $familia=$familia->get();
        $data_familia=$data_familia->get(['mwanafamilias.*','familias.jina_la_familia','familias.jina_la_jumuiya']);

        return view('backend.familia.wanafamilia',compact(['data_familia','familia','vyeo','title']));
    }

    //function to handle storing of mwanafamilia
    public function wanafamilia_store(Request $request){

        //getting the required field
        $must = [
            'jina_kamili' => 'required',
         //   'mawasiliano' => 'required',
            'jinsia' => 'required',
            // 'dob' => 'required',
            // 'taaluma' => 'required',
            'familia' => 'required',
            // 'komunio' => 'required',
            // 'ekaristi' => 'required',
            // 'kipaimara' => 'required',
            // 'ndoa' => 'required',
            // 'ubatizo' => 'required',
            'cheo_familia' => 'required',
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>["Hakikisha umejaza taarifa zote zinazohitajika."]]);
        }
        else{
            //getting data
            $ndoa = $request->ndoa;

            //confirming about ndoa
            if($ndoa == "tayari"){
                //getting the type of ndoa
                $aina = $request->aina_ya_ndoa;
                $dhehebu = $request->dhehebu;

                if(($aina == "") || ($dhehebu == "")){
                    return response()->json(['errors'=>"Kwakua uko kwenye ndoa jaza aina ya ndoa na dhehebu."]);
                }
            }
            else{
                $aina = NULL;
                $dhehebu = NULL;

                if($ndoa == ""){
                    $ndoa = NULL;
                }
            }

            //confirming about ubatizo
            $ubatizo = $request->ubatizo;

            if($ubatizo == "tayari"){
                //getting the required values
                $namba_ya_cheti = $request->namba_ya_cheti;

                if($namba_ya_cheti == ""){
                    return response()->json(['errors'=>"Kwakua umebatizwa jaza namba ya cheti."]);
                }

                //jimbo la ubatizo
                $jimbo_la_ubatizo = $request->jimbo_la_ubatizo;

                if($jimbo_la_ubatizo == ""){
                    return response()->json(['errors'=>"Kwakua umebatizwa jaza jimbo la ubatizo."]);
                }

                //parokia ya ubatizo
                $parokia_ya_ubatizo = $request->parokia_ya_ubatizo;

                if($parokia_ya_ubatizo == ""){
                    return response()->json(['errors'=>"Kwakua umebatizwa jaza parokia ya ubatizo."]);
                }
            }
            else{
                $namba_ya_cheti = NULL;
                $jimbo_la_ubatizo = NULL;
                $parokia_ya_ubatizo = NULL;
            }

            //validating date
            $date = Carbon::now()->addDays(1)->format('Y-m-d');

            if($request->dob > $date){
                return response()->json(['errors'=>["Tarehe ya kuzaliwa ambayo si sahihi."]]);
            }

            //before saving generate the obtain details of kanda and generate utambulisho namba
            $familia = $request->familia;

            $jumuiyas = Familia::whereId($familia)->get('jina_la_jumuiya');

            //validating that we have jumuiya
            if($jumuiyas->isNotEmpty()){
                foreach($jumuiyas as $jumu){
                    $jumuiya = $jumu->jina_la_jumuiya;
                }
            }
            else{
                return response()->json(['errors'=>"Mwanafamilia hana jumuiya hivyo namba haiwezi kuundwa.."]);
            }

            //getting kanda where by using jina la jumuiya
            $kanda_ufupisho = $this->getKandaKey($jumuiya);

            //set the utambulisho namba
            $namba = $this->setUtambulishoNamba($kanda_ufupisho);

            //setting rika
            $rika = MakundiRika::where('umri_kuanzia','<=',Carbon::parse($request->dob)->age)->where('umri_ukomo','>=',Carbon::parse($request->dob)->age)->value('rika');

            //since everything is correct saving data now
            $data_save = [
                'jina_kamili' => strtoupper($request->jina_kamili),
                'mawasiliano' => $request->mawasiliano,
                'jinsia' => $request->jinsia,
                'dob' => $request->dob,
                'taaluma' => $request->taaluma,
                'familia' => $familia,
                'komunio' => $request->komunio,
                'ekaristi' => $request->ekaristi,
                'kipaimara' => $request->kipaimara,
                'ndoa' => $ndoa,
                // 'cheo' => $request->cheo,
                'ubatizo' => $ubatizo==null?'Bado':$ubatizo,
                'aina_ya_ndoa' => $aina,
                'namba_ya_cheti' => $namba_ya_cheti,
                'parokia_ya_ubatizo' => $parokia_ya_ubatizo,
                'jimbo_la_ubatizo' => $jimbo_la_ubatizo,
                'cheo_familia' => ucfirst(strtolower($request->cheo_familia)),
                'namba_utambulisho' => $namba,
                'dhehebu' => $dhehebu,
                'rika' => $rika,
            ];

            
            $success = Mwanafamilia::create($data_save);
              
            if($success){
                //tracking
                $message = "Ongezo";
                $data = "Ongezo la mwanafamilia $request->jina_kamili";
                $this->setActivity($message,$data);

                //incrementing family members
                Familia::whereId($request->familia)->increment('wanafamilia',1);

                return response()->json(['success'=>["Mwanafamilia ameongezwa kikamilifu."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuongeza mwanafamilia"]]);
            }
        }
    }

    //displaying the data to edit modal
    public function wanafamilia_edit($id){
        $data = Mwanafamilia::leftJoin('familias','mwanafamilias.familia','=','familias.id')->select(['mwanafamilias.*','familias.jina_la_familia','familias.id as familia_id'])->findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //wanafamilia update
    public function wanafamilia_update(Request $request){

        //getting old data
        $existing = Mwanafamilia::find($request->hidden_id);

        //getting the required field
        $must = [
            'jina_kamili' => 'required',
         //   'mawasiliano' => 'required',
            'jinsia' => 'required',
            // 'dob' => 'required',
            // 'taaluma' => 'required',
            // 'komunio' => 'required',
            // 'ekaristi' => 'required',
            // 'kipaimara' => 'required',
            // 'ndoa' => 'required',
            // 'ubatizo' => 'required',
         //   'cheo_familia' => 'required',
            'namba_utambulisho' => 'required|unique:mwanafamilias,namba_utambulisho,'.$existing->id,
        ];

        $error = Validator::make($request->all(),$must);

        if($error->fails()){
            return response()->json(['errors'=>["Hakikisha umejaza taarifa zote zinazohitajika, namba ya utambulisho isijirudie."]]);
        }
        else{

            //checking the issue of familia
            $familia = $request->familia_update;

            if($familia == ""){
                $familia = $request->familia_id;
            }

            //getting data
            $ndoa = $request->ndoa;

            //confirming about ndoa
            if($ndoa == "tayari"){
                //getting the type of ndoa
                $aina = $request->aina_ya_ndoa;
                $dhehebu = $request->dhehebu;

                if(($aina == "") || ($dhehebu == "")){
                    return response()->json(['errors'=>"Kwakua uko kwenye ndoa jaza aina ya ndoa na dhehebu.."]);
                }
            }
            else{
                $aina = NULL;
                $dhehebu = NULL;

                if($ndoa == ""){
                    $ndoa = NULL;
                }
            }

            //confirming about ubatizo
            $ubatizo = $request->ubatizo;

            if($ubatizo == "tayari"){
                //getting the required values
                $namba_ya_cheti = $request->namba_ya_cheti;

                if($namba_ya_cheti == ""){
                    return response()->json(['errors'=>"Kwakua umebatizwa jaza namba ya cheti."]);
                }

                //jimbo la ubatizo
                $jimbo_la_ubatizo = $request->jimbo_la_ubatizo;

                if($jimbo_la_ubatizo == ""){
                    return response()->json(['errors'=>"Kwakua umebatizwa jaza jimbo la ubatizo."]);
                }

                //parokia ya ubatizo
                $parokia_ya_ubatizo = $request->parokia_ya_ubatizo;

                if($parokia_ya_ubatizo == ""){
                    return response()->json(['errors'=>"Kwakua umebatizwa jaza parokia ya ubatizo."]);
                }
            }
            else{
                $namba_ya_cheti = NULL;
                $jimbo_la_ubatizo = NULL;
                $parokia_ya_ubatizo = NULL;
            }

            //validating date
            $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');

            //setting rika
            $rika = MakundiRika::where('umri_kuanzia','<=',Carbon::parse($request->dob)->age)->where('umri_ukomo','>=',Carbon::parse($request->dob)->age)->value('rika');

            if($request->dob > $tomorrow){
                return response()->json(['errors'=>["Tarehe ya kuzaliwa ambayo si sahihi."]]);
            }

            //since everything is correct saving data now
            $data_update = [
                'jina_kamili' => strtoupper($request->jina_kamili),
                'mawasiliano' => $request->mawasiliano,
                'jinsia' => $request->jinsia,
                'dob' => $request->dob,
                'taaluma' => $request->taaluma,
                'familia' => $familia,
                'komunio' => $request->komunio,
                'ekaristi' => $request->ekaristi,
                'kipaimara' => $request->kipaimara,
                'ndoa' => $ndoa,
                // 'cheo' => $request->cheo,
                'ubatizo' => $ubatizo==null?'Bado':$ubatizo,
                'aina_ya_ndoa' => $aina,
                'namba_ya_cheti' => $namba_ya_cheti,
                'parokia_ya_ubatizo' => $parokia_ya_ubatizo,
                'jimbo_la_ubatizo' => $jimbo_la_ubatizo,
                'cheo_familia' => ucfirst(strtolower($request->cheo_familia)),
                'namba_utambulisho' => $request->namba_utambulisho,
                'dhehebu' => $dhehebu,
                'rika' => $rika,
            ];

            $success = Mwanafamilia::whereId($request->hidden_id)->update($data_update);

            if($success){
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la mwanafamilia $request->jina_kamili";
                $this->setActivity($message,$data);

                //incrementing/decrementing family members
                if($existing->familia !== $familia){
                    //incrementing
                    Familia::whereId($familia)->increment('wanafamilia',1);

                    //decrementing
                    Familia::whereId($existing->familia)->decrement('wanafamilia',1);
                }

                return response()->json(['success'=>["Taarifa za mwanafamilia zimebadilishwa kikamilifu."]]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili taarifa za mwanafamilia"]]);
            }
        }
    }

    public function familia_import(Request $request){
        
        $file = $request->file('file')->store('import');

        // Excel::import(new FamiliaImport, $file);

        $import = new FamiliaImport;
        $import->import($file);

        $errors = $import->errors();

        if($import->failures()->isNotEmpty()){
            return back()->withFailures($import->failures());
        }

        //processing the issue namba za utambulisho
        // $unassigned_numbers = Mwanafamilia::whereNull('mwanafamilias.namba_utambulisho')
        // ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        // ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
        // ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
        // ->get(['kandas.herufi_ufupisho','mwanafamilias.id']);

        // //checking the latest numbers
        // foreach($unassigned_numbers as $row){
        //     //latest numbers
        //     $latest_numbers = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$row->herufi_ufupisho.'%')->get();
        // }

        // if($latest_numbers->isEmpty()){

        //     foreach($unassigned_numbers as $unassigned){

        //         //checking results
        //         $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$unassigned->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

        //         if($results->isEmpty()){
        //             $generated_namba = "$unassigned->herufi_ufupisho".\sprintf('%04d',1);
        //             Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$generated_namba]);
        //         }
        //         else{

        //             foreach($results as $res){
        //                 $existing_namba = preg_replace("/[^0-9]/", "", $res->namba_utambulisho);
        //                 $namba_mpya = ltrim($existing_namba,0);
        //                 $namba_mpya = $existing_namba+1;
        //                 $namba_mpya = "$unassigned->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
        //             }

        //             Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$namba_mpya]);
        //         }
        //     }
        // }
        // else{

        //     //checking all the members with missed namba ya utambulisho
        //     foreach($unassigned_numbers as $query){
        //         //checking results
        //         $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$query->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

        //         if($results->isEmpty()){
        //             $generated_namba = "$query->herufi_ufupisho".\sprintf('%04d',1);
        //             Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$generated_namba]);
        //         }
        //         else{

        //             //getting data
        //             foreach($results as $digit){
        //                 $existing_namba = preg_replace("/[^0-9]/", "", $digit->namba_utambulisho);
        //                 $namba_mpya = ltrim($existing_namba,0);
        //                 $namba_mpya = $existing_namba+1;
        //                 $namba_mpya = "$query->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
        //             }

        //             Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$namba_mpya]);

        //         }
        //     }
        // }

        //checking if we are having errors
        if(!$errors){
            Alert::info("Taarifa","Nambari za wanafamilia hazijawekwa kwakua kuna makosa ya kiufundi");
            return back();
        }
        else{
            return back()->withStatus('Familia zimeongezwa kikamilifu.',compact(['errors']));
        }
    }

    public function wanafamilia_import(Request $request){
        
        $file = $request->file('file')->store('import');

        

        // Excel::import(new FamiliaImport, $file);
        $import = new WanafamiliaImport;
        $import->import($file);

        $errors = $import->errors();

        if($import->failures()->isNotEmpty()){
            return back()->withFailures($import->failures());
        }
        // $unassigned_numbers = Mwanafamilia::whereNull('mwanafamilias.namba_utambulisho')
        // ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        // ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
        // ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
        // ->get(['kandas.herufi_ufupisho','mwanafamilias.id']);

   
        // //checking the latest numbers
        // foreach($unassigned_numbers as $row){
        //     //latest numbers

        //     $latest_numbers = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$row->herufi_ufupisho.'%')->get();
         
        // }

        // if($latest_numbers->isEmpty()){
          

        //     foreach($unassigned_numbers as $unassigned){

        //         //checking results
        //         $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$unassigned->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

        //         if($results->isEmpty()){
        //             $generated_namba = "$unassigned->herufi_ufupisho".\sprintf('%04d',1);
        //             Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$generated_namba]);
        //         }
        //         else{

        //             foreach($results as $res){
        //                 $existing_namba = preg_replace("/[^0-9]/", "", $res->namba_utambulisho);
        //                 $namba_mpya = ltrim($existing_namba,0);
        //                 $namba_mpya = $existing_namba+1;
        //                 $namba_mpya = "$unassigned->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
        //             }

        //             Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$namba_mpya]);
        //         }
        //     }
        // }
        // else{
             
        //     //checking all the members with missed namba ya utambulisho
        //     foreach($unassigned_numbers as $query){
        //         //checking results
        //         $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$query->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

        //         dd($results);

        //         if($results->isEmpty()){
        //             $generated_namba = "$query->herufi_ufupisho".\sprintf('%04d',1);
        //             Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$generated_namba]);
        //         }
        //         else{
        //            // getting data
        //             foreach($results as $digit){
        //                 $existing_namba = preg_replace("/[^0-9]/", "", $digit->namba_utambulisho);
        //                 $namba_mpya = ltrim($existing_namba,0);
        //                 $namba_mpya = $existing_namba+1;
        //                 $namba_mpya = "$query->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
        //             }

        //             Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$namba_mpya]);

        //         }
        //     }
        // }

        if(!$errors){
            Alert::info("Taarifa","Nambari za wanafamilia hazijawekwa kwakua kuna makosa ya kiufundi");
            return back();
        }
        else{
            return back()->withStatus('Wanafamilia wameongezwa kikamilifu.',compact(['errors']));
        }

    }

    public function ImportFamilyMemberCorrections(Request $request){
        $file = $request->file('file')->store('import');

        // Excel::import(new FamiliaImport, $file);
        $import = new FamilyMemberCorrectionImport;
        $import->import($file);

        $errors = $import->errors();

        if($import->failures()->isNotEmpty()){
            return back()->withFailures($import->failures());
        }

        if(!$errors){
            Alert::info("Taarifa","Kuna marekebisho hayajafanikiwa kubadilika kikamilifu,Tafadhali angalia uyafanyie kazi.");
            return back();
        }
        else{
            return back()->withStatus('Marekebisho yamefanyika kikamilifu.',compact(['errors']));
        }

    }


    //function to handle mwanafamilia husika
    public function mwanafamilia_husika($id){
        // return $id;
        $details = Mwanafamilia::findOrFail($id);
        $binafsi = Mwanafamilia::whereId($id)->first();
        $taarifa_jumuiya = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('mwanafamilias.id',$id)->select(['familias.jina_la_familia','familias.jina_la_jumuiya'])
        ->first();

        //=============== VYAMA VYA KITUME =======
        $vyama_idadi = Mwanafamilia::leftJoin('wanakikundis','wanakikundis.mwanafamilia','mwanafamilias.id')
        ->whereNotNull('wanakikundis.mwanafamilia')
        ->where('mwanafamilias.id',$id)->count();

        $taarifa_vyama = Mwanafamilia::leftJoin('wanakikundis','wanakikundis.mwanafamilia','mwanafamilias.id')
        ->leftJoin('kikundis','kikundis.id','=','wanakikundis.kikundi')
        ->where('mwanafamilias.id',$id)->get('kikundis.jina_la_kikundi');

        //details of zaka, michango and sadaka
        $zaka_mwakahuu = Mwanafamilia::leftJoin('zakas','zakas.mwanajumuiya','=','mwanafamilias.id')
        ->where('mwanafamilias.id',$id)->whereYear('zakas.tarehe',Carbon::now()->format('Y'))->sum('zakas.kiasi');

        $zaka_mwakajana = Mwanafamilia::leftJoin('zakas','zakas.mwanajumuiya','=','mwanafamilias.id')
        ->where('mwanafamilias.id',$id)->whereYear('zakas.tarehe',Carbon::now()->subYear(1)->format('Y'))->sum('zakas.kiasi');

        //=========== TASLIMU =============//
        $michango_taslimu_mwakahuu = Mwanafamilia::leftJoin('michango_taslimus','michango_taslimus.mwanafamilia','=','mwanafamilias.id')
        ->where('mwanafamilias.id',$id)->whereYear('michango_taslimus.tarehe',Carbon::now()->format('Y'))->sum('michango_taslimus.kiasi');

        $michango_taslimu_mwakajana = Mwanafamilia::leftJoin('michango_taslimus','michango_taslimus.mwanafamilia','=','mwanafamilias.id')
        ->where('mwanafamilias.id',$id)->whereYear('michango_taslimus.tarehe',Carbon::now()->subYear(1)->format('Y'))->sum('michango_taslimus.kiasi');

        //============ BENKI ==========//
        $michango_benki_mwakahuu = Mwanafamilia::leftJoin('michango_benkis','michango_benkis.mwanafamilia','=','mwanafamilias.id')
        ->where('mwanafamilias.id',$id)->whereYear('michango_benkis.tarehe',Carbon::now()->format('Y'))->sum('michango_benkis.kiasi');

        $michango_benki_mwakajana = Mwanafamilia::leftJoin('michango_benkis','michango_benkis.mwanafamilia','=','mwanafamilias.id')
        ->where('mwanafamilias.id',$id)->whereYear('michango_benkis.tarehe',Carbon::now()->subYear(1)->format('Y'))->sum('michango_benkis.kiasi');

        //michango mwaka jana
        $michango_mwakajana = $michango_taslimu_mwakajana + $michango_benki_mwakajana;

        //michango mwaka huu
        $michango_mwakahuu = $michango_taslimu_mwakahuu + $michango_benki_mwakahuu;

        //jumla mwaka jana
        $jumla_mwakajana = $michango_mwakajana + $zaka_mwakajana;

        //jumla mwaka huu
        $jumla_mwakahuu = $michango_mwakahuu + $zaka_mwakahuu;

        $title = "Taarifa za Muumini";
        return view('backend.familia.mwanafamilia',compact(['title','binafsi','taarifa_jumuiya','zaka_mwakajana','zaka_mwakahuu','michango_mwakajana','michango_mwakahuu'
        ,'jumla_mwakahuu','jumla_mwakajana','vyama_idadi','taarifa_vyama']));
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new Familia;
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }

    //method for tracking activity
    private function setActivity1($message,$data){
        $model = new Mwanafamilia;
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }

    //getting kanda
    private function getKandaKey($jumuiya){
        //
        $jina_la_kanda = Jumuiya::where('jina_la_jumuiya',$jumuiya)->get('jina_la_kanda');

        if($jina_la_kanda->isNotEmpty()){

            foreach($jina_la_kanda as $jina){
                $kanda = $jina->jina_la_kanda;
            }

            //now getting details of kanda
            $herufi_ufupisho = Kanda::where('jina_la_kanda',$kanda)->value('herufi_ufupisho');

            return $herufi_ufupisho;
        }
        else{
            return response()->json(['errors'=>"Jumuiya $jumuiya haina kanda tafadhali jaza taarifa za kanda.."]);
        }
    }

    //processing the assigning of numbers for church members
    public function setUtambulishoNamba($herufi_ufupisho){

          
        //latest numbers
        $latest_numbers = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();
         
        //assign numbers
        if($latest_numbers->isNotEmpty()){

            foreach($latest_numbers as $row){
                $existing_namba = $row->namba_utambulisho;
            }

            $existing_namba = preg_replace("/[^0-9]/", "", $existing_namba);
          
            $generated_namba = ltrim($existing_namba,0);

            $generated_namba = $existing_namba+1;

            $namba_mpya = "$herufi_ufupisho".\sprintf('%04d',$generated_namba);
            return $namba_mpya;
        }
        //no results so generate new number
        else{
            $namba_mpya = "$herufi_ufupisho".\sprintf('%04d',1);
            return $namba_mpya;
        }
    }

    public function badilishaTaarifaMkupuo(Request $request)
    {
        $selected_jumuiya = $request->jumuiya;

        if($selected_jumuiya ==""){
            Alert::toast("Tafadhali chagua jumuiya husika","info");
            return back();
        }
        //getting details of members from selected jumuiya
        $jumuiya_title = $selected_jumuiya;

        $jumuiya = Kanda::latest('kandas.created_at')
            ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
            ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya']);

        //setting title
        $title = "Ubadilishaji wa Taarifa za wanajumuiya ya $jumuiya_title";

        //list of members
        $members = Mwanafamilia::where('familias.jina_la_jumuiya',$selected_jumuiya)
            ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->orderBy('mwanafamilias.jina_kamili','asc')
            ->get(['mwanafamilias.*']);

        //this handle the populatin of data to the table
        $action = "mkupuo";

        return view('backend.familia.badili_taarifa_mkupuo', compact(['jumuiya','title','members','action','selected_jumuiya']));
    }

    public function badilishaTaarifaMkupuoIndex(Request $request){

        $title = "Ubadilishaji wa Taarifa za wanajumuiya";
        $action = "mwanzo";

        $jumuiya = Kanda::latest('kandas.created_at')
            ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
            ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya']);

        return view('backend.familia.taarifa_mkupuo',compact(['jumuiya','title','action']));
    }

}
