<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Validator;
use Auth;
use App\VyeoKanisa;
use App\Jumuiya;
use App\Mwanafamilia;
use App\Http\Requests\PasswordRequest;
use Image;
use File;
use Alert;
use App\Kikundi;
use App\kiongoziKikundi;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $title = "ORODHA YA KAMATI TENDAJI";
        $viongozi_total = User::where('ngazi','!=','administrator')->get()->count();
        // $data_viongozi = User::whereNotNull('cheo')->select(DB::raw("COUNT(cheo) as idadi"),'cheo')
        //     ->groupBy('cheo')
        //     ->get(['cheo','idadi']);
        $kamati_tendaji_parokia = User::where('ngazi','Parokia')->get()->count();
        $kamati_tendaji_wengineo = User::where('ngazi','wengineo')->get()->count();
        $kamati_tendaji_jumuiya = User::where('ngazi','Jumuiya')->get()->count();
        $kamati_vyama_kitume=User::where('ngazi','Vyama vya kitume')->get()->count();
        $kamati_tendaji_kanda=User::where('ngazi','Kanda')->orWhere('ngazi','Kigango')->orWhere('ngazi','Vigango')->get()->count();

        return view('backend.viongozi.viongozi_takwimu',compact(['title','viongozi_total','kamati_tendaji_parokia','kamati_tendaji_jumuiya','kamati_vyama_kitume','kamati_tendaji_kanda','kamati_tendaji_wengineo']));
    }

    //the page which used to add users
    public function viongozi_zaidi(){
        $data_viongozi = User::latest()->where('ngazi','!=','administrator')->get();

        $wanajumuiya = Mwanafamilia::latest('mwanafamilias.created_at')->leftJoin('familias','familias.id','mwanafamilias.familia')->get(['mwanafamilias.id as mwanajumuiya_id','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','familias.jina_la_jumuiya']);
        $data_vyeo = VyeoKanisa::latest()->get();
        $data_jumuiya = Jumuiya::latest()->get();

        $roles = Role::all(['id','name']);
        $permissions = Permission::all(['id','name']);

        $data_vyama = Kikundi::orderBy('jina_la_kikundi')->get();

        return view('backend.viongozi.viongozi',compact(['data_vyeo','data_viongozi','data_jumuiya','wanajumuiya','roles', 'permissions','data_vyama']));
    }

    public function viongozi_husika($id){
        $title = "Orodha ya viongozi wa $id";
        $cheo = $id;

        $data_viongozi = User::latest()->where('ngazi','!=','administrator')->where('cheo',$cheo)->get();

        $data_vyeo = VyeoKanisa::latest()->get();
        $data_jumuiya = Jumuiya::latest()->get();

        return view('backend.viongozi.viongozi_husika',compact(['data_vyeo','data_viongozi','data_jumuiya','cheo','title']));
    }

    //function to handle viongozi in parokia level

   public function viongozi_kamati($aina_ya_kamati){
       
     $title = "Orodha ya kamati tendaji ".str_replace('_',' ',$aina_ya_kamati);
     $wanajumuiya = Mwanafamilia::latest('mwanafamilias.created_at')->leftJoin('familias','familias.id','mwanafamilias.familia')->get(['mwanafamilias.id as mwanajumuiya_id','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','familias.jina_la_jumuiya']);
     $data_vyeo = VyeoKanisa::latest()->get();
     $data_jumuiya = Jumuiya::latest()->get();

       if($aina_ya_kamati=='parokia'){
         $where_query='Parokia';
       }else if($aina_ya_kamati=='jumuiya'){
           $where_query='Jumuiya';
       }else if($aina_ya_kamati=='Kanda' || $aina_ya_kamati=='Kigango' || $aina_ya_kamati=='Vigango'){
        $where_query='Kanda';
       }elseif($aina_ya_kamati=='wengineo'){
        $where_query='Wengineo';
       }else{
        $where_query='';
       }
     if($aina_ya_kamati=='vyama_vya_kitume'){
        $data_kamati = User::where('ngazi','Vyama vya kitume')->get();
     }else{
     $data_kamati = User::leftJoin('mwanafamilias','mwanafamilias.id','=','users.mwanajumuiya_id')
        ->where('users.ngazi',$where_query)
        ->orderBy('users.cheo','ASC')
        ->get(['users.ruhusa','users.ngazi','users.id','users.jumuiya','users.cheo','mwanafamilias.jina_kamili','mwanafamilias.mawasiliano','mwanafamilias.id as mwanajumuiya_id']);
     }
     return view('backend.viongozi.kamati_tendaji',compact(['title','data_kamati','wanajumuiya','data_vyeo','data_jumuiya']));
   }

    public function viongozi_kamati_parokia(){
        $title = "Orodha ya kamati tendaji parokia";
        $data_kamati = User::leftJoin('mwanafamilias','mwanafamilias.id','=','users.mwanajumuiya_id')
        ->where('users.ngazi','Parokia')
        ->orderBy('users.cheo','ASC')
        ->get(['users.ruhusa','users.ngazi','users.id','users.jumuiya','users.cheo','mwanafamilias.jina_kamili','mwanafamilias.mawasiliano','mwanafamilias.id as mwanajumuiya_id']);

        $wanajumuiya = Mwanafamilia::latest('mwanafamilias.created_at')->leftJoin('familias','familias.id','mwanafamilias.familia')->get(['mwanafamilias.id as mwanajumuiya_id','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','familias.jina_la_jumuiya']);
        $data_vyeo = VyeoKanisa::latest()->get();
        $data_jumuiya = Jumuiya::latest()->get();

        return view('backend.viongozi.kamati_tendaji_parokia',compact(['title','data_kamati','wanajumuiya','data_vyeo','data_jumuiya']));
    }

    //function to handle viongozi in jumuiya level
    public function viongozi_kamati_jumuiya(){
        $title = "Orodha ya kamati tendaji jumuiya";
        $data_kamati = User::leftJoin('mwanafamilias','mwanafamilias.id','=','users.mwanajumuiya_id')
        ->where('users.ngazi','Jumuiya')
        ->orderBy('users.cheo','ASC')
        ->get(['users.ruhusa','users.ngazi','users.id','users.jumuiya','users.cheo','mwanafamilias.jina_kamili','mwanafamilias.mawasiliano','mwanafamilias.id as mwanajumuiya_id']);

        $wanajumuiya = Mwanafamilia::latest('mwanafamilias.created_at')->leftJoin('familias','familias.id','mwanafamilias.familia')->get(['mwanafamilias.id as mwanajumuiya_id','mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho','familias.jina_la_jumuiya']);
        $data_vyeo = VyeoKanisa::latest()->get();
        $data_jumuiya = Jumuiya::latest()->get();

        return view('backend.viongozi.kamati_tendaji_jumuiya',compact(['title','data_kamati','wanajumuiya','data_vyeo','data_jumuiya']));
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
        $rules = [
            'mwanajumuiya_id' => 'required',
            'mawasiliano' => 'required|unique:users',
            'cheo' => 'required',
            'ngazi' => 'required',
            // 'roles.*' => 'exists:roles,id',
            // 'permissions.*' => 'exists:permissions,id',
        ];

        $error = Validator::make($request->all(),$rules);

        //checking for any errors in validation
        if($error->fails()){

            return response()->json(['errors'=>"Hakikisha umejaza taarifa zote za muhimu..", ]);
        }

        else{

            //getting initials data
            $cheo = ucfirst(strtolower($request->cheo));
            $ngazi = ucfirst(strtolower($request->ngazi));
            $jumuiya = $request->jumuiya;
            $mwanajumuiya = $request->mwanajumuiya_id;
            $email = $request->email;

            if(!($email == "")){
                //checking if the email exists in table
                if(User::where('email',$email)->exists()){
                    return response()->json(['errors'=>"Barua pepe imeshatumika"]);
                }
            }
            else{
                $email = NULL;
            }

            $jina_kamili = Mwanafamilia::whereId($mwanajumuiya)->value('jina_kamili');

            if((gettype($request['roles']) === 'integer') ||
                (gettype($request['permissions']) === 'integer')) {

                $request['roles'] = [$request['roles']];
                $request['permissions'] = [$request['permissions']];
            }

            //getting the values


            if($ngazi=='Vyama_kitume'){
               $ngazi='Vyama vya kitume';
            }
           
            $data_save = [
                'jina_kamili' => $jina_kamili,
                'mawasiliano' => $request->mawasiliano,
                'ruhusa' => $request->ruhusa,
                'cheo' => $cheo,
                'email' => $email,
                'anwani' => $request->anwani,
                'password' => Hash::make($request->mawasiliano),
                'picha' => 'profile.png',
                'jumuiya' => $jumuiya,
                'ngazi' => $ngazi,
                'mwanajumuiya_id' => $mwanajumuiya,
            ];

            //creating data
            $data = User::create($data_save);
            if($data){

                if($ngazi=='Vyama vya kitume'){
                    kiongoziKikundi::create([
                     'user_id'=>$data->id,
                     'kikundi_id'=>$request->vyama,
                     'status'=>'active'
                    ]);
                }

            if(!empty($request['roles'])) {
                $data->syncRoles($request['roles']);
            }

            if(!empty($request['permissions'])) {
                $data->syncPermissions($request['permissions']);
            }
                 //tracking
                $message = "Ongezo";
                $data = "Ongezo la mtumiaji $jina_kamili";
                $this->setActivity($message,$data);
                return response()->json(['success'=>"Kiongozi ameongezwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kuongeza kiongozi."]]);
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


    public function edit($id)
    {
        //
        $data = User::leftJoin('vyeo_kanisas','vyeo_kanisas.jina_la_cheo','=','users.cheo')
        ->select(['users.*','vyeo_kanisas.jina_la_cheo'])
        ->findOrFail($id);

        $roles = $data->roles->map(function($role){
            $data['id'] = $role->id;
            $data['name'] = $role->name;

            return $data;
        });

        $permissions = $data->permissions->map(function($permission){
            $data['id'] = $permission->id;
            $data['name'] = $permission->name;

            return $data;
        });

        return response()->json(['result'=>$data, 'roles' => $roles, 'permissions' =>  $permissions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $model)
    {
        $existing = User::findOrFail($request->hidden_id);

        //validating data
        $rules = [
            'mawasiliano' => 'required|unique:users,mawasiliano,'.$existing->id,
            'mwanajumuiya_id_u' => 'required',
            'cheo' => 'required',
            'ngazi' => 'required',
            // 'roles.*' => 'exists:roles,id',
            // 'permissions.*' => 'exists:permissions,id',
        ];

      

        //validation capture
        $error = Validator::make($request->all(),$rules);

        //checking for any errors in validation
        if($error->fails()){
            return response()->json(['errors'=>["Hakikisha umejaza taarifa zote za muhimu..."]]);
        }
        else{

            //getting initials data
            $cheo = ucfirst(strtolower($request->cheo));
            $ngazi = ucfirst(strtolower($request->ngazi));
            $jumuiya = $request->jumuiya;
            $mwanajumuiya = $request->mwanajumuiya_id_u;
            $email = $request->email;

            if(!($email == "")){
                //checking if the email exists in table
                if(User::where('email',$email)->where('id','!=',$existing->id)->exists()){
                    return response()->json(['errors'=>"Barua pepe imeshatumika"]);
                }
            }
            else{
                $email = NULL;
            }

            $jina_kamili = Mwanafamilia::whereId($mwanajumuiya)->value('jina_kamili');

            //getting the values
            $data_update = [
                'jina_kamili' => $jina_kamili,
                'mawasiliano' => $request->mawasiliano,
                'ruhusa' => $request->ruhusa,
                'cheo' => $cheo,
                'email' => $email,
                'anwani' => $request->anwani,
                'jumuiya' => $jumuiya,
                'ngazi' => $ngazi,
                'mwanajumuiya_id' => $mwanajumuiya,
            ];

            if((gettype($request['roles']) === 'integer') ||
                (gettype($request['permissions']) === 'integer')) {

                $request['roles'] = [$request['roles']];
                $request['permissions'] = [$request['permissions']];
            }

            //creating data
            $data = User::whereId($request->hidden_id)->update($data_update);
            $user = User::find($request->hidden_id);
            if($data){
                // $user->roles->detach();
                // $user->permissions->detach();
                // if(!empty($request['roles'])) {

                   

                    $user->syncRoles($request['roles']);
               // }

                // if(!empty($request['permissions'])) {

                   
                    $user->syncPermissions($request['permissions']);

               // }
                //tracking
                $message = "Badiliko";
                $data = "Badiliko la mtumiaji $jina_kamili";
                $this->setActivity($message,$data);
                return response()->json(['success'=>"Taarifa zimebadilishwa kikamilifu."]);
            }
            else{
                return response()->json(['errors'=>["Imeshindikana kubadili taarifa."]]);
            }
        }
    }

    public function user_profile(){
        $personal_details = User::findOrFail(auth()->user()->id);

        if($personal_details == ""){
            Alert::info("Hakuna taarifa husika za wasifu wa mtumiaji");
            return redirect('home');
        }

        else{
            return view('backend.mengineyo.taarifa_binafsi', compact(['personal_details']));
        }
    }

    //updating profile details
    public function profile_update(Request $request){

        $existing = User::findOrFail(auth()->user()->id);
        $old_photo = $existing->picha;
        //checking if we have other changes from for other fields
        $rules = [
            'jina_kamili' => 'required',
            'mawasiliano' => 'required|unique:users,mawasiliano,'.$existing->id,
            'anwani' => 'required',
            'email' => 'required|unique:users,email,'.$existing->id,
            'cheo' => 'required',
        ];

        $error = Validator::make($request->all(),$rules);

        //checking for any errors in validation
        if($error->fails()){
            return response()->json(['errors'=>["Hakikisha umejaza taarifa zote za muhimu, barua pepe na namba visiwe vimetumika.."]]);
        }
        else{
            //checking if we have photo
            //getting the photo details
            $photo = $request->file('picha');

            if($photo != ""){

                //validating photo
                $photo_rules = [
                    'picha' => 'required|mimes:png,gif,jpeg,jpg|max:2048',
                ];

                $photo_errors = Validator::make($request->all(),$photo_rules);

                if($photo_errors->fails()){
                    Alert::info("Tafadhali aina ya picha inapaswa kuwa PNG,JPEG,JPG isiyozidi 2MB.");
                    return back();
                }

                $photo_image = Image::make($photo);
                $photo_destination = public_path('/uploads/images/');
                $photo_file = time().uniqid().".".$photo->getClientOriginalExtension();//to save in db
                $resized = $photo_image->resize(400,360);
                $resized->save($photo_destination.$photo_file);

                //deleting the old image
                $old_image = public_path('/uploads/images/').$old_photo;
                $profile_photo = public_path('/uploads/images/profile.png');

                //deleting the old image
                if(strcmp($old_image,$profile_photo) != 0){
                    @unlink($old_image);
                }
            }
            else{
                $photo_file = $old_photo;
            }
        }

        if($existing->cheo ==""){
            $cheo = NULL;
        }
        else{
            $cheo = $existing->cheo;
        }

        if($existing->ngazi ==""){
            $ngazi = NULL;
        }
        else{
            $ngazi = $existing->ngazi;
        }

        //validating
        $data_update = [
            'jina_kamili' => $request->jina_kamili,
            'jumuiya' => $existing->jumuiya,
            'ruhusa' => $existing->ruhusa,
            'cheo' => $existing->cheo,
            'email' => $request->email,
            'anwani' => $request->anwani,
            'mawasiliano' => $request->mawasiliano,
            'picha' => $photo_file,
            'ngazi' => $ngazi,
        ];

        //updating
        $success = User::whereId(auth()->user()->id)->update($data_update);

        if($success){
            Alert::toast("Taarifa zako zimebadilika kikamilifu","success");
            return redirect('users_profile');
        }
        else{
            Alert::toast("Imeshindikana kubadili taarifa jaribu tena","info");
            return back();
        }
    }

    //updating password
    public function password_update(PasswordRequest $request,User $user){
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);
        Alert::success("<h2 class='text-primary'>Taarifa</h2>","Umefanikiwa kubadili nywila yako kikamilifu, tafadhali ingia tena kwenye mfumo kwa kutumia neno la siri jipya ulilojaza hivi punde..")->autoclose(6000)->timerProgressBar(6000);
        Auth::logout();
        return redirect('/login');
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
        $data = User::findOrFail($id);
        $jina = $data->jina_kamili;

        if($data->delete()){
            //tracking
            $message = "Ufutaji";
            $data = "Ufutaji wa mtumiaji $jina";
            $this->setActivity($message,$data);
            return response()->json(['success'=>["mtumiaji amefutwa kikamilifu."]]);
        }
        else{
            return response()->json(['success'=>["mtumiaji hajafutwa jaribu tena.."]]);
        }
    }

    //populating data for inserting viongozi data
    public function getMwanajumuiyaData(Request $request){
        $id = $request->mwanajumuiya;

        $data = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('mwanafamilias.id', $id)
        ->select(['mwanafamilias.jina_kamili','mwanafamilias.mawasiliano','familias.jina_la_jumuiya'])
        ->findOrFail($id);

        return response()->json(['result'=>$data]);
    }

    //method for tracking activity
    private function setActivity($message,$data){
        $model = new User;
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
