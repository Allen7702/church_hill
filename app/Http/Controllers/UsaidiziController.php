<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Familia;
use App\Mwanafamilia;
use App\Jumuiya;
use App\MajinaUsaidizi;
use App\FamiliaUsaidizi;
use App\WanafamiliaUsaidizi;
use App\Imports\MajinaUsaidiziImport;
use Validator;
use Str;
use Auth;

class UsaidiziController extends Controller
{
    //function to call the page for usaidizi
    public function usaidizi_index(){
        return view('backend.system.usaidizi');
    }

    //function to call the index of majina
    public function orodha_majina_index(){
        $data_majina = MajinaUsaidizi::orderBy('jina_kamili','ASC')->get();
        return view('backend.system.orodha_majina',compact(['data_majina']));
    }

    //function to handle the import of majina
    public function orodha_majina_import(Request $request){
        $file = $request->file('file')->store('import');

        // Excel::import(new MajinaUsaidiziImport, $file);
        $import = new MajinaUsaidiziImport;
        $import->import($file);

        $errors = $import->errors();

        if($import->failures()->isNotEmpty()){
            return back()->withFailures($import->failures());
        }

        return back()->withStatus('Majina yameongezwa kikamilifu.',compact(['errors']));
    }

    //function to handle the edition of majina
    public function orodha_majina_edit($id){
        $data = MajinaUsaidizi::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //function to handle the updating of data
    public function orodha_majina_update(Request $request){
        //getting the required data

        $hidden_id = $request->hidden_id;

        $rules = [
            'jina_kamili' => 'required',
            'jinsia' => 'required',
            'mawasiliano' => 'required',
            'cheo_familia' => 'required',
            'jumuiya' => 'required',
        ];

        $error = Validator::make($request->all(),$rules);

        //function to validate
        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umejaza taarifa zote muhimu.."]);
        }

        //update variable
        $data_update = [
            'jina_kamili' => strtoupper($request->jina_kamili),
            'jinsia' => $request->jinsia,
            'mawasiliano' => $request->mawasiliano,
            'cheo_familia' => ucfirst($request->cheo_familia),
            'jumuiya' => strtoupper($request->jumuiya),
        ];

        //updating data
        $success = MajinaUsaidizi::whereId($hidden_id)->update($data_update);

        if($success){
            return response()->json(['success'=>"Taarifa zimebadilishwa kikamilifu.."]);
        }
        else{
            return response()->json(['errors'=>"Imeshindikana kubadili taarifa.."]);
        }
    }
    
    //function to reset table initializing everything
    public function orodha_majina_truncate(Request $request){
        $success = MajinaUsaidizi::truncate();

        if($success){
            return response()->json(['success'=>"Majina yote yamefutwa kikamilifu pakia upya.."]);
        }
        else{
            return response()->json(['errors'=>"Imeshindikana kufuta taarifa.. jaribu tena.."]);
        }
    }

    //function to handle the deletion of majina
    public function orodha_majina_destroy($id){
        $data = MajinaUsaidizi::findOrFail($id);

        if($data->delete()){
            return response()->json(['success'=>"Taarifa imefutwa kikamilifu.."]);
        }
        else{
            return response()->json(['errors'=>"imeshindikana kufuta taarifa jaribu tena baadae.."]);
        }
    }

    //function to handle the familia orodha usaidizi
    public function orodha_familia_index(){
        $data_majina = MajinaUsaidizi::latest()->orderBy('jina_kamili','ASC')->get();
        $data_familia = FamiliaUsaidizi::latest()->orderBy('jina_la_familia','ASC')->get();
        return view('backend.system.orodha_familia',compact(['data_majina','data_familia']));
    }

    //function to handle the storing of data
    public function orodha_familia_store(Request $request){
        //rules
        $rules = [
            'jina_la_familia' => 'required|unique:familia_usaidizis',
        ];

        $error = Validator::make($request->all(),$rules);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umechajaza jina la familia, pia jina la familia lisijirudie.."]);
        }
        else{

            // getting data to save
            $majina_id = $request->jina_la_familia;

            foreach($majina_id as $key => $row){
                $details[] = MajinaUsaidizi::whereId($row)->get();
            }

            //counts
            $counts = 0;

            $total_members = count($details);

            foreach($details as $key => $data){
                
                //dealing with extra
                foreach($data as $rec){
                    if(!(FamiliaUsaidizi::where('jina_la_familia',$rec->jina_kamili)->where('mawasiliano',$rec->mawasiliano)->exists())){
                        
                        $data_save = [
                            'jina_la_familia' => $rec->jina_kamili,
                            'mawasiliano' => $rec->mawasiliano,
                            'jina_la_jumuiya' => $rec->jumuiya,
                            'slug' => Str::slug($rec->jina_kamili),
                            'jinsia' => ucfirst(strtolower($rec->jinsia)),
                            'cheo_familia' => ucfirst(strtolower($rec->cheo_familia)),
                            'maoni' => $request->maoni,
                        ];

                        $success = FamiliaUsaidizi::create($data_save);

                        if($success){
                            $counts = $counts + 1;
                            MajinaUsaidizi::whereId($rec->id)->delete();

                            //returning response
                        }
                    }
                }
            }

            //checking number of families created
            if($counts == $total_members){
                return response()->json(['success'=>"Jumla ya majina $counts yameandaliwa kwa ajili ya utengenezwaji wa familia.."]);
            }

            else{
                $gap = $total_members - $counts;
                return response()->json(['success'=>"Jumla ya majina $counts yameandaliwa kwa ajili ya utengenezaji wa familia, majina $gap hayajafanikiwa kuandaliwa.."]);
            }
        }
    }

    //function to handle the edition of familia
    public function orodha_familia_edit($id){
        $data = FamiliaUsaidizi::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //function to handle the updating of familia usaidizi data
    public function orodha_familia_update(Request $request){
        //getting the required data

        $hidden_id = $request->hidden_id_update;

        $existing = FamiliaUsaidizi::findOrFail($hidden_id);

        $rules = [
            'jina_familia' => 'required',
            'jinsia' => 'required',
            'mawasiliano' => 'required|unique:familia_usaidizis,mawasiliano,'.$existing->id,
            'cheo_familia' => 'required',
            'jumuiya' => 'required',
        ];

        $error = Validator::make($request->all(),$rules);

        //function to validate
        if($error->fails()){
            // return response()->json(['errors'=>$error->errors()->all()]);
            return response()->json(['errors'=>"Hakikisha umejaza taarifa zote muhimu, namba ya simu isijirudie.."]);
        }

        //update variable
        $data_update = [
            'jina_la_familia' => strtoupper($request->jina_familia),
            'jinsia' => $request->jinsia,
            'mawasiliano' => $request->mawasiliano,
            'cheo_familia' => ucfirst($request->cheo_familia),
            'jina_la_jumuiya' => strtoupper($request->jumuiya),
        ];

        //updating data
        $success = FamiliaUsaidizi::whereId($hidden_id)->update($data_update);

        if($success){
            return response()->json(['success'=>"Taarifa zimebadilishwa kikamilifu.."]);
        }
        else{
            return response()->json(['errors'=>"Imeshindikana kubadili taarifa.."]);
        }
    }

    //function to handle the deletion of familia orodha
    public function orodha_familia_destroy($id){

        //deleting data
        $data = FamiliaUsaidizi::findOrFail($id);

        //before delete getting data
        $data_save = [
            'jina_kamili' => $data->jina_la_familia,
            'jinsia' => $data->jinsia,
            'mawasiliano' => $data->mawasiliano,
            'cheo_familia' => $data->cheo_familia,
            'jumuiya' => $data->jina_la_jumuiya,
        ];

        if($data->delete()){
            
            //restoring names
            MajinaUsaidizi::create($data_save);

            return response()->json(['success'=>"Taarifa imefutwa kikamilifu.."]);
        }
        else{
            return response()->json(['errors'=>"imeshindikana kufuta taarifa jaribu tena baadae.."]);
        }
    }

    //function to truncate all the familia names
    public function orodha_familia_truncate(){
        //getting the names
        $familia_names = FamiliaUsaidizi::get();

        if($familia_names->isEmpty()){
            return response()->json(['info'=>"Hakuna taarifa zozote kuhusu majina.."]);
        }
        else{
            //getting data to restore
            $data_save = [];
            foreach($familia_names as $name){

                array_push($data_save,
                    [
                        'jina_kamili' => $name->jina_la_familia,
                        'jinsia' => $name->jinsia,
                        'mawasiliano' => $name->mawasiliano,
                        'cheo_familia' => $name->cheo_familia,
                        'jumuiya' => $name->jina_la_jumuiya,
                    ]
                );

            }

            //now creating the data back to majina orodha
            $success = MajinaUsaidizi::insert($data_save);

            if($success){
                $process = FamiliaUsaidizi::truncate();

                if($process){
                    return response()->json(['success'=>"Familia zimefutwa kikamilifu.."]);
                }
                else{
                    return response()->json(['success'=>"Majina yamerudishwa ila familia hazijafutwa.."]);
                }
            }
            else{
                return response()->json(['errors'=>"Imeshindikana kufuta na kurudisha orodha ya majina.."]);
            }
        }
    }

    //function to handle the utengenezwaji wa familia
    public function orodha_familia_tengeneza(){
        //checking if we have familia names
        $familia_real_names = FamiliaUsaidizi::get();

        if($familia_real_names->isEmpty()){
            return response()->json(['info'=>"Hakuna taarifa zozote kuhusu majina ya familia hivyo haziwezi kutengenezwa.."]);
        }
        else{
            $familia_counts = 0;
            $total_familias = count($familia_real_names);
            
            //creating familia
            foreach($familia_real_names as $real){

                if(!(Familia::where('jina_la_familia',$real->jina_la_familia)->where('jina_la_jumuiya',$real->jina_la_jumuiya)->where('mawasiliano',$real->mawasiliano)->exists())){
                    
                    $data_familia_save = [
                        'jina_la_familia' => strtoupper($real->jina_la_familia),
                        'jina_la_jumuiya' => strtoupper($real->jina_la_jumuiya),
                        'slug' => Str::slug($real->jina_la_familia),
                        'mawasiliano' => $real->mawasiliano,
                    ];

                    $real_process = Familia::create($data_familia_save);

                    if($real_process){

                        $familia_counts = $familia_counts + 1;

                        //adding the family name to mwanafamilia
                        $data_mwanafamilia = [
                            'jina_kamili' => strtoupper($real->jina_la_familia),
                            'familia' => $real_process->id,
                            'mawasiliano' => $real->mawasiliano,
                            'jinsia' => $real->jinsia,
                            'cheo_familia' => ucfirst(strtolower($real->cheo_familia)),
                        ];

                        $sajili = Mwanafamilia::create($data_mwanafamilia);

                        //incrementing the number of familia in jumuiya
                        Jumuiya::where('jina_la_jumuiya',$real->jina_la_jumuiya)->increment('idadi_ya_familia',1);

                        Familia::whereId($real_process->id)->increment('wanafamilia',1);

                        //deleting the records
                        FamiliaUsaidizi::whereId($real->id)->delete();

                    }
                }
            }

            //successfully migrating all the families
            if($familia_counts == $total_familias){

                //processing the issue namba za utambulisho
                $unassigned_numbers = Mwanafamilia::whereNull('mwanafamilias.namba_utambulisho')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
                    ->get(['kandas.herufi_ufupisho','mwanafamilias.id']);

                //checking the latest numbers
                foreach($unassigned_numbers as $row){
                    //latest numbers
                    $latest_numbers = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$row->herufi_ufupisho.'%')->get();
                }

                if($latest_numbers->isEmpty()){

                    foreach($unassigned_numbers as $unassigned){

                        //checking results
                        $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$unassigned->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

                        if($results->isEmpty()){
                            $generated_namba = "$unassigned->herufi_ufupisho".\sprintf('%04d',1);
                            Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$generated_namba]);
                        }
                        else{

                            foreach($results as $res){
                                $existing_namba = preg_replace("/[^0-9]/", "", $res->namba_utambulisho);
                                $namba_mpya = ltrim($existing_namba,0);
                                $namba_mpya = $existing_namba+1;
                                $namba_mpya = "$unassigned->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
                            }

                            Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$namba_mpya]);                    
                        }
                    }
                }
                else{

                    //checking all the members with missed namba ya utambulisho
                    foreach($unassigned_numbers as $query){
                        //checking results
                        $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$query->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

                        if($results->isEmpty()){
                            $generated_namba = "$query->herufi_ufupisho".\sprintf('%04d',1);
                            Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$generated_namba]);
                        }
                        else{

                            //getting data
                            foreach($results as $digit){
                                $existing_namba = preg_replace("/[^0-9]/", "", $digit->namba_utambulisho);
                                $namba_mpya = ltrim($existing_namba,0);
                                $namba_mpya = $existing_namba+1;
                                $namba_mpya = "$query->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
                            }

                            Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$namba_mpya]);
                        }
                    }
                }

                //tracking
                $message = "Ongezo";
                $data = "Ongezo la familia $familia_counts";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Jumla ya familia $familia_counts zimeundwa kikamilifu"]);
            }
            
            else{
                $gap = $total_familias - $familia_counts;

                //processing the issue namba za utambulisho
                $unassigned_numbers = Mwanafamilia::whereNull('mwanafamilias.namba_utambulisho')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
                    ->get(['kandas.herufi_ufupisho','mwanafamilias.id']);

                //checking the latest numbers
                foreach($unassigned_numbers as $row){
                    //latest numbers
                    $latest_numbers = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$row->herufi_ufupisho.'%')->get();
                }

                if($latest_numbers->isEmpty()){

                    foreach($unassigned_numbers as $unassigned){

                        //checking results
                        $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$unassigned->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

                        if($results->isEmpty()){
                            $generated_namba = "$unassigned->herufi_ufupisho".\sprintf('%04d',1);
                            Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$generated_namba]);
                        }
                        else{

                            foreach($results as $res){
                                $existing_namba = preg_replace("/[^0-9]/", "", $res->namba_utambulisho);
                                $namba_mpya = ltrim($existing_namba,0);
                                $namba_mpya = $existing_namba+1;
                                $namba_mpya = "$unassigned->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
                            }

                            Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$namba_mpya]);                    
                        }
                    }
                }

                else{

                    //checking all the members with missed namba ya utambulisho
                    foreach($unassigned_numbers as $query){
                        //checking results
                        $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$query->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

                        if($results->isEmpty()){
                            $generated_namba = "$query->herufi_ufupisho".\sprintf('%04d',1);
                            Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$generated_namba]);
                        }
                        else{

                            //getting data
                            foreach($results as $digit){
                                $existing_namba = preg_replace("/[^0-9]/", "", $digit->namba_utambulisho);
                                $namba_mpya = ltrim($existing_namba,0);
                                $namba_mpya = $existing_namba+1;
                                $namba_mpya = "$query->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
                            }

                            Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$namba_mpya]);

                        }
                    }
                }

                //tracking
                $message = "Ongezo";
                $data = "Ongezo la familia $familia_counts";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Jumla ya familia $familia_counts zimeundwa kati ya $total_familias"]);
            }
        }
    }

    //function to handle the wanafamilia index
    public function orodha_wanafamilia_index(){
        $data_majina = MajinaUsaidizi::latest()->orderBy('jina_kamili','ASC')->get();
        $data_wanafamilia = WanafamiliaUsaidizi::latest('wanafamilia_usaidizis.created_at')
            ->leftJoin('familias','familias.id','=','wanafamilia_usaidizis.familia_id')
            ->orderBy('wanafamilia_usaidizis.jina_kamili','ASC')
            ->select('wanafamilia_usaidizis.*','familias.jina_la_familia')
            ->get();
        
        // return $data_wanafamilia;
        $data_familia = Familia::latest()->orderBy('jina_la_familia','ASC')
        ->select('id as familia_id','mawasiliano','jina_la_familia','jina_la_jumuiya')
        ->get();

        return view('backend.system.orodha_wanafamilia',compact(['data_majina','data_familia','data_wanafamilia']));
    }

    //function to handle the storing of wanafamilia
    public function orodha_wanafamilia_store(Request $request){
        
        $rules = [
            'jina_la_familia' => 'required',
            'jina_kamili' => 'required',
        ];

        $error = Validator::make($request->all(),$rules);

        if($error->fails()){
            return response()->json(['errors'=>"Hakikisha umechagua kiongozi wa familia na wanafamilia.."]);
        }
        else{

            // getting data to save
            $familia_id = $request->jina_la_familia;

            // $data_familia = Familia::find($familia_id);

            // if($data_familia == ""){
            //     return response()->json(['errors'=>"Hakuna taarifa za ziada kwa kiongozi wa familia.."]);
            // }
            // else{
            //     $jumuiya = $data_familia->jina_la_jumuiya;
            // }

            //getting wanafamilia
            $wanafamilia_ids = $request->jina_kamili;

            //getting the details from majina usaidizi
            $majina_details = [];

            foreach($wanafamilia_ids as $key => $row){
                $majina_details[] = MajinaUsaidizi::whereId($row)->get();
            }

            //tracking members
            $wanafamilia_members = 0;
            $wanafamilia_total = count($majina_details);

            //now getting data
            foreach($majina_details as $key => $rec){

                foreach($rec as $data){

                        $data_save = [
                        'jina_kamili' => $data->jina_kamili,
                        'mawasiliano' => $data->mawasiliano,
                        'cheo_familia' => $data->cheo_familia,
                        'jina_la_jumuiya' =>$data->jumuiya,
                        'jinsia' => $data->jinsia,
                        'familia_id' => $familia_id,
                        'slug' => Str::slug($data->jina_kamili),
                    ];

                    $success = WanafamiliaUsaidizi::create($data_save);

                    if($success){
                        $wanafamilia_members = $wanafamilia_members + 1;
                        MajinaUsaidizi::whereId($data->id)->delete();
                    }
                }
            }

            //checking if all names have been created
            if($wanafamilia_members == $wanafamilia_total){
                return response()->json(['success'=>"Majina $wanafamilia_members ya wanafamilia yameandaliwa kikamilifu.."]);
            }
            else{
                return response()->json(['success'=>"Majina $wanafamilia_members kati ya $wanafamilia_total yameandaliwa kikamilifu.."]);
            }   
        }  
    }

    //function to handle the edition of wanafamilia
    public function orodha_wanafamilia_edit($id){
        $data = WanafamiliaUsaidizi::findOrFail($id);
        return response()->json(['result'=>$data]);
    }

    //function to handle the updating of wanafamilia usaidizi data
    public function orodha_wanafamilia_update(Request $request){
        
        //getting the required data
        $hidden_id = $request->hidden_id_update;

        $existing = WanafamiliaUsaidizi::findOrFail($hidden_id);

        $rules = [
            'jina_kamili' => 'required',
            'jinsia' => 'required',
            'mawasiliano' => 'required',
            'cheo_familia' => 'required',
            'jumuiya' => 'required',
        ];

        $error = Validator::make($request->all(),$rules);

        //function to validate
        if($error->fails()){
            // return response()->json(['errors'=>$error->errors()->all()]);
            return response()->json(['errors'=>"Hakikisha umejaza taarifa zote muhimu.."]);
        }

        else{
            //update variable
            $data_update = [
                'jina_kamili' => strtoupper($request->jina_kamili),
                'jinsia' => $request->jinsia,
                'mawasiliano' => $request->mawasiliano,
                'cheo_familia' => ucfirst($request->cheo_familia),
                'jina_la_jumuiya' => strtoupper($request->jumuiya),
                'familia_id' => $existing->familia_id,
            ];

            //updating data
            $success = WanafamiliaUsaidizi::whereId($hidden_id)->update($data_update);

            if($success){
                return response()->json(['success'=>"Taarifa zimebadilishwa kikamilifu.."]);
            }
            else{
                return response()->json(['errors'=>"Imeshindikana kubadili taarifa.."]);
            }
        }
    }

    //function to handle the deletion of wanafamilia
    public function orodha_wanafamilia_destroy($id){

        //deleting data
        $data = WanafamiliaUsaidizi::findOrFail($id);

        //before delete getting data
        $data_save = [
            'jina_kamili' => $data->jina_kamili,
            'jinsia' => $data->jinsia,
            'mawasiliano' => $data->mawasiliano,
            'cheo_familia' => $data->cheo_familia,
            'jumuiya' => $data->jina_la_jumuiya,
        ];

        if($data->delete()){
            
            //restoring names
            MajinaUsaidizi::create($data_save);

            return response()->json(['success'=>"Taarifa imefutwa kikamilifu.."]);
        }
        else{
            return response()->json(['errors'=>"imeshindikana kufuta taarifa jaribu tena baadae.."]);
        }
    }

    //function to truncate all the wanafamilia names
    public function orodha_wanafamilia_truncate(){
        
        //getting the names
        $wanafamilia_names = WanafamiliaUsaidizi::get();

        if($wanafamilia_names->isEmpty()){
            return response()->json(['info'=>"Hakuna taarifa zozote kuhusu majina ya wanafamilia.."]);
        }
        else{
            //getting data to restore
            $data_save = [];
            foreach($wanafamilia_names as $name){

                array_push($data_save,
                    [
                        'jina_kamili' => $name->jina_kamili,
                        'jinsia' => $name->jinsia,
                        'mawasiliano' => $name->mawasiliano,
                        'cheo_familia' => $name->cheo_familia,
                        'jumuiya' => $name->jina_la_jumuiya,
                    ]
                );

            }

            //now creating the data back to majina orodha
            $success = MajinaUsaidizi::insert($data_save);

            if($success){
                $process = WanafamiliaUsaidizi::truncate();

                if($process){
                    return response()->json(['success'=>"Wanafamilia wamefutwa kikamilifu.."]);
                }
                else{
                    return response()->json(['success'=>"Majina yamerudishwa ila wanafamilia hawajafutwa.."]);
                }
            }
            else{
                return response()->json(['errors'=>"Imeshindikana kufuta na kurudisha orodha ya majina.."]);
            }
        }
    }

    //function to handle the utengenezwaji wa wanafamilia
    public function orodha_wanafamilia_tengeneza(){
        //checking if we have wanafamilia names
        $wanafamilia_real_names = WanafamiliaUsaidizi::get();

        if($wanafamilia_real_names->isEmpty()){
            return response()->json(['info'=>"Hakuna taarifa zozote kuhusu majina ya wanafamilia hivyo zoezi limesitishwa.."]);
        }
        else{
            $wanafamilia_counts = 0;
            $total_wanafamilias = count($wanafamilia_real_names);

            foreach($wanafamilia_real_names as $real){
                //tracking wanafamiali
                $wanafamilia_counts = $wanafamilia_counts + 1;

                //checking if the mwanajumuiya is not exists
                if(!(Mwanafamilia::where('jina_kamili',$real->jina_kamili)->where('familia',$real->familia_id)->where('mawasiliano',$real->mawasiliano)->exists())){
                    $data_wanafamilia_save = [
                        'jina_kamili' => strtoupper($real->jina_kamili),
                        'mawasiliano' => $real->mawasiliano,
                        'jinsia' => $real->jinsia,
                        'familia' => $real->familia_id,
                        'cheo_familia' => ucfirst(strtolower($real->cheo_familia)),
                    ];

                    //real process
                    $real_process = Mwanafamilia::create($data_wanafamilia_save);

                    //incrementing wanafamilia
                    Familia::whereId($real->familia_id)->increment('wanafamilia',1);

                    //deleting the record from wanafamilia usaidizi
                    WanafamiliaUsaidizi::whereId($real->id)->delete();
                }
            }

            //here since i dont have to track real process
            if($wanafamilia_counts == $total_wanafamilias){
                //processing the issue of namba of utambulisho

                //dealing with assigning of numbers of wanafamilia and set the namba of utambulisho
                $unassigned_numbers = Mwanafamilia::whereNull('mwanafamilias.namba_utambulisho')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
                    ->get(['kandas.herufi_ufupisho','mwanafamilias.id']);

                //checking the latest numbers
                foreach($unassigned_numbers as $row){
                    //latest numbers
                    $latest_numbers = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$row->herufi_ufupisho.'%')->get();
                }

                if($latest_numbers->isEmpty()){
                    foreach($unassigned_numbers as $unassigned){
                         //checking results
                        $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$unassigned->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

                        if($results->isEmpty()){
                            $generated_namba = "$unassigned->herufi_ufupisho".\sprintf('%04d',1);
                            Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$generated_namba]);
                        }
                        else{

                            foreach($results as $res){
                                $existing_namba = preg_replace("/[^0-9]/", "", $res->namba_utambulisho);
                                $namba_mpya = ltrim($existing_namba,0);
                                $namba_mpya = $existing_namba+1;
                                $namba_mpya = "$unassigned->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
                            }

                            Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$namba_mpya]);
                        }
                    }
                }

                else{
                    //checking all the members with missed namba ya utambulisho
                    foreach($unassigned_numbers as $query){
                        //checking results
                        $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$query->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

                        if($results->isEmpty()){
                            $generated_namba = "$query->herufi_ufupisho".\sprintf('%04d',1);
                            Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$generated_namba]);
                        }
                        else{
                            //getting data
                            foreach($results as $digit){
                                $existing_namba = preg_replace("/[^0-9]/", "", $digit->namba_utambulisho);
                                $namba_mpya = ltrim($existing_namba,0);
                                $namba_mpya = $existing_namba+1;
                                $namba_mpya = "$query->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
                            }

                            Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$namba_mpya]);
                        }
                    }
                }

                //tracking
                $message = "Ongezo";
                $data = "Ongezo la wanafamilia $wanafamilia_counts";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Jumla ya wanafamilia $wanafamilia_counts wameongezwa kikamilifu.."]);
            
            }
            //they are not equal
            else{
                //those who were not created
                $gap = $total_wanafamilias - $wanafamilia_counts;

                //process here
                //processing the issue of namba of utambulisho

                //dealing with assigning of numbers of wanafamilia and set the namba of utambulisho
                $unassigned_numbers = Mwanafamilia::whereNull('mwanafamilias.namba_utambulisho')
                    ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
                    ->leftJoin('jumuiyas','jumuiyas.jina_la_jumuiya','=','familias.jina_la_jumuiya')
                    ->leftJoin('kandas','kandas.jina_la_kanda','=','jumuiyas.jina_la_kanda')
                    ->get(['kandas.herufi_ufupisho','mwanafamilias.id']);

                //checking the latest numbers
                foreach($unassigned_numbers as $row){
                    //latest numbers
                    $latest_numbers = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$row->herufi_ufupisho.'%')->get();
                }

                if($latest_numbers->isEmpty()){
                    foreach($unassigned_numbers as $unassigned){
                         //checking results
                        $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$unassigned->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

                        if($results->isEmpty()){
                            $generated_namba = "$unassigned->herufi_ufupisho".\sprintf('%04d',1);
                            Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$generated_namba]);
                        }
                        else{

                            foreach($results as $res){
                                $existing_namba = preg_replace("/[^0-9]/", "", $res->namba_utambulisho);
                                $namba_mpya = ltrim($existing_namba,0);
                                $namba_mpya = $existing_namba+1;
                                $namba_mpya = "$unassigned->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
                            }

                            Mwanafamilia::whereId($unassigned->id)->update(['namba_utambulisho'=>$namba_mpya]);
                        }
                    }
                }

                else{
                    //checking all the members with missed namba ya utambulisho
                    foreach($unassigned_numbers as $query){
                        //checking results
                        $results = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$query->herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();

                        if($results->isEmpty()){
                            $generated_namba = "$query->herufi_ufupisho".\sprintf('%04d',1);
                            Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$generated_namba]);
                        }
                        else{
                            //getting data
                            foreach($results as $digit){
                                $existing_namba = preg_replace("/[^0-9]/", "", $digit->namba_utambulisho);
                                $namba_mpya = ltrim($existing_namba,0);
                                $namba_mpya = $existing_namba+1;
                                $namba_mpya = "$query->herufi_ufupisho".\sprintf('%04d',$namba_mpya);
                            }

                            Mwanafamilia::whereId($query->id)->update(['namba_utambulisho'=>$namba_mpya]);
                        }
                    }
                }

                //tracking
                $message = "Ongezo";
                $data = "Ongezo la wanafamilia $wanafamilia_counts";
                $this->setActivity($message,$data);

                return response()->json(['success'=>"Jumla ya wanafamilia $wanafamilia_counts wameongezwa kati ya $total_wanafamilias"]);
            }

        }

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

}

