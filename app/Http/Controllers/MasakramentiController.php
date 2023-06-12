<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Familia;
use App\Kanda;
use App\Jumuiya;
use App\Mwanafamilia;
use App\Kikundi;
use App\User;
use Alert;
use App\SahihishaKanda;
use DB;
use Auth;

class MasakramentiController extends Controller
{
    //
    public function masakramenti_kijumla(){

        $title = "Taarifa za masakramenti kijumuiya";
        $data_summary = $this->getSummary();
        $kanda = $data_summary['kanda'];
        $jumuiya = $data_summary['jumuiya'];
        $familia = $data_summary['familia'];
        $vyakitume = $data_summary['vyakitume'];

        // $takwimu_za_kiroho = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')->get();

        $takwimu_za_kiroho = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->groupBy('familias.jina_la_jumuiya')
        ->get(['mwanafamilias.id','mwanafamilias.familia as familia_id','familias.jina_la_jumuiya']);

        // other statics to be used in bottom of the table as table footer;
        $wanajumuiya = Mwanafamilia::count();
        $waliobatizwa = Mwanafamilia::where('ubatizo','tayari')->count();
        $kipaimara = Mwanafamilia::where('kipaimara','tayari')->count();
        $ndoa = Mwanafamilia::where('ndoa','tayari')->count();
        $ekaristi = Mwanafamilia::where('ekaristi','anapokea')->count();
        $komunio = Mwanafamilia::where('komunio','tayari')->count();
        $jumuiya_zote = Jumuiya::count(); 

        //cash income details for chart
        return view('backend.masakramenti.masakramenti_jumla',compact(['title','vyakitume','kanda','jumuiya','familia','takwimu_za_kiroho','wanajumuiya','waliobatizwa','kipaimara','ndoa','ekaristi','komunio'])); view('backend.masakramenti.masakramenti_jumla',compact(['title','vyakitume','kanda','jumuiya','familia','takwimu_za_kiroho','wanajumuiya','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
    }

    //overall kanda
    public function masakramenti_kikanda(){
    

        $sahihisha_kanda=SahihishaKanda::first();
        $title = "Ripoti ya taarifa za masakramenti $sahihisha_kanda->name";

        $data_summary = $this->getSummary();
        $kanda = $data_summary['kanda'];
        $jumuiya = $data_summary['jumuiya'];
        $familia = $data_summary['familia'];
        $wanafamilia = $data_summary['wanafamilia'];

        //takwimu za kiroho kikanda
        $takwimu_za_kiroho = Kanda::get();

        //other summaries to be used in tbale footer
        $wanajumuiya = Mwanafamilia::count();
        $waliobatizwa = Mwanafamilia::where('ubatizo','tayari')->count();
        $kipaimara = Mwanafamilia::where('kipaimara','tayari')->count();
        $ndoa = Mwanafamilia::where('ndoa','tayari')->count();
        $ekaristi = Mwanafamilia::where('ekaristi','anapokea')->count();
        $komunio = Mwanafamilia::where('komunio','tayari')->count();
        $jumuiya_zote = Jumuiya::count(); 

        return view('backend.masakramenti.masakramenti_kikanda',compact(['title','kanda','familia','wanafamilia','jumuiya','takwimu_za_kiroho','jumuiya_zote','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
    }

    //overall specific kanda
    public function masakramenti_kanda_husika($id){
        //getting kandas
        $kanda_data = Kanda::findOrFail($id);
        
        if($kanda_data == ""){
            Alert::toast("Hakuna taarifa kwa kanda ilochaguliwa","info");
            return back();
        }

        //setting title
        $jina_la_kanda = $kanda_data->jina_la_kanda;
        $title = "Taarifa za masakramenti $jina_la_kanda";

        $takwimu_za_kiroho = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->groupBy('jumuiyas.jina_la_jumuiya')
        ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya','jumuiyas.idadi_ya_familia','familias.jina_la_familia','jumuiyas.id as jumuiya_id']);

        // return $takwimu_za_kiroho;

        //processing the footer details for the kanda husika
        $jumuiya_zote = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->count();

        $wanajumuiya_wote = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $waliobatizwa = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->where('mwanafamilias.ubatizo','tayari')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $kipaimara = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->where('mwanafamilias.kipaimara','tayari')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $ndoa = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->where('mwanafamilias.ndoa','tayari')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $komunio = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->where('mwanafamilias.komunio','tayari')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $ekaristi = Kanda::where('kandas.jina_la_kanda',$jina_la_kanda)
        ->where('mwanafamilias.ekaristi','anapokea')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','kandas.jina_la_kanda')
        ->leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
        ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
        ->count();

        $kanda = $jina_la_kanda;

        return view('backend.masakramenti.masakramenti_kanda_husika',compact(['title','takwimu_za_kiroho','jumuiya_zote','wanajumuiya_wote','waliobatizwa','kipaimara','ndoa','ekaristi','komunio','kanda']));
    }

    //function to handle masakramenti jumuiya
    public function masakramenti_jumuiya(){
        //setting the title
        $title = "Taarifa za masakramenti kijumuiya";

        //calling the summary
        $data_summary = $this->getSummary();
        
        $jumuiya = $data_summary['jumuiya'];
        $familia = $data_summary['familia'];
        $wanafamilia = $data_summary['wanafamilia'];
        $viongozi = $data_summary['viongozi'];

        $user = Auth::user();
        $user_ngazi = $user->ngazi;
        $mwanafamilia= \App\Mwanafamilia::find(Auth::user()->mwanajumuiya_id);
        $familia = \App\Familia::find($mwanafamilia->familia);
        $jumuiya_d=\App\Jumuiya::where('jina_la_jumuiya',$familia->jina_la_jumuiya);
        $kanda=\App\Kanda::where('jina_la_kanda',$jumuiya_d->first()->jina_la_kanda);

         if($user_ngazi=='administrator' || $user_ngazi=='Parokia' ){
            $jumuiya=Jumuiya::latest();
            $data_familia=Familia::latest();
               // other statics to be used in bottom of the table as table footer;
            $familia_zote=Familia::count();
        $wanajumuiya = Mwanafamilia::count();
        $waliobatizwa = Mwanafamilia::where('ubatizo','tayari')->count();
        $kipaimara = Mwanafamilia::where('kipaimara','tayari')->count();
        $ndoa = Mwanafamilia::where('ndoa','tayari')->count();
        $ekaristi = Mwanafamilia::where('ekaristi','anapokea')->count();
        $komunio = Mwanafamilia::where('komunio','tayari')->count();
        $jumuiya_zote = Jumuiya::count(); 
            
        $takwimu_za_kiroho = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->groupBy('familias.jina_la_jumuiya');

         }elseif($user_ngazi=='Kanda'){
            $jumuiya = Jumuiya::where('jina_la_kanda',$kanda->jina_la_kanda);
            $familia=Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
            $familia_zote=$familia->count();
            $takwimu_za_kiroho=Jumuiya::leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
            ->where('familias.jina_la_jumuiya',$jumuiya->first()->jina_la_jumuiya)
            ->groupBy('jumuiyas.jina_la_jumuiya')
             ->orderBy('familias.jina_la_jumuiya','asc');

            $wanajumuiya = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->count();
            $waliobatizwa = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->where('ubatizo','tayari')->count();
            $kipaimara = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->where('kipaimara','tayari')->count();
            $ndoa = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->where('ndoa','tayari')->count();
            $ekaristi = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->where('ekaristi','anapokea')->count();
            $komunio = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->where('komunio','tayari')->count();
            $jumuiya_zote = $jumuiya->count(); 
            

         }elseif($user_ngazi=='Jumuiya'){

             $jumuiya = $jumuiya_d; 
             
             $familia = Familia::whereIn('jina_la_jumuiya',$jumuiya->pluck('jina_la_jumuiya'));
             $familia_zote=$familia->count();
             $takwimu_za_kiroho=Jumuiya::leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
             ->where('familias.jina_la_jumuiya',$jumuiya->first()->jina_la_jumuiya)
             ->groupBy('jumuiyas.jina_la_jumuiya')
              ->orderBy('familias.jina_la_jumuiya','asc');
         
            $wanajumuiya = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->count();
            $wanafamilia =$wanajumuiya;
            $waliobatizwa = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->where('ubatizo','tayari')->count();
            $kipaimara = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->where('kipaimara','tayari')->count();
            $ndoa = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->where('ndoa','tayari')->count();
            $ekaristi = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->where('ekaristi','anapokea')->count();
            $komunio = Mwanafamilia::whereIn('familia',$familia->pluck('id'))->where('komunio','tayari')->count();
            $jumuiya_zote = $jumuiya->count(); 
         } 



        //cash income details for chart
        $jumuiya=$jumuiya->count();

        $familia=$familia->count();

        $takwimu_za_kiroho = $takwimu_za_kiroho->get(['jumuiyas.jina_la_jumuiya','familias.id as familia_id','jumuiyas.id','jumuiyas.idadi_ya_familia']); 

        return view('backend.masakramenti.masakramenti_jumuiya',compact(['title','takwimu_za_kiroho','jumuiya','familia','wanafamilia','viongozi','familia_zote','waliobatizwa','kipaimara','ndoa','ekaristi','komunio']));
    }

    //getting the specific masakramenti for specific jumuiya
    public function masakramenti_jumuiya_husika($id){
        $data_jumuiya = Jumuiya::findOrFail($id);

        if($data_jumuiya == ""){
            Alert::toast("Hakuna taarifa kwa jumuiya ilochaguliwa","info");
            return redirect()->back();
        }

        else{
            //getting details
            $jina_la_jumuiya = $data_jumuiya->jina_la_jumuiya;
            
            $title = "Taarifa za masakramenti jumuiya $jina_la_jumuiya";

            $takwimu_za_kiroho = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
            ->get(['mwanafamilias.jina_kamili','mwanafamilias.kipaimara','mwanafamilias.mawasiliano','mwanafamilias.id','mwanafamilias.ndoa','mwanafamilias.ubatizo','mwanafamilias.komunio','mwanafamilias.ekaristi']);

            if($takwimu_za_kiroho->isEmpty()){
                Alert::toast("Hakuna taarifa za kiroho kwa jumuiya ya $jina_la_jumuiya","info");
                return redirect()->back();
            }
            
            $jumuiya = $jina_la_jumuiya;

            //handling the details for table footer
            $wanajumuiya_wote = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
            ->count();

            $ndoa = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
            ->where('mwanafamilias.ndoa','tayari')
            ->count();

            $ubatizo = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
            ->where('mwanafamilias.ubatizo','tayari')
            ->count();

            $kipaimara = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
            ->where('mwanafamilias.kipaimara','tayari')
            ->count();

            $komunio = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
            ->where('mwanafamilias.komunio','tayari')
            ->count();

            $ekaristi = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
            ->where('familias.jina_la_jumuiya',$jina_la_jumuiya)
            ->where('mwanafamilias.ekaristi','anapokea')
            ->count();

            return view('backend.masakramenti.masakramenti_jumuiya_husika',compact(['title','takwimu_za_kiroho','jumuiya','wanajumuiya_wote','ndoa','ubatizo','kipaimara','ekaristi','komunio']));
        }
        
    }

    private function getSummary(){
        //to be used by most methods
        $kanda = Kanda::count();
        $jumuiya = Jumuiya::count();
        $familia = Familia::count();
        $wanafamilia = Mwanafamilia::count();
        $viongozi = User::whereNotNull('cheo')->count();
        $vyakitume = Kikundi::count();

        $data_summary = [
            'kanda' => $kanda,
            'jumuiya' => $jumuiya,
            'familia' => $familia,
            'wanafamilia' => $wanafamilia,
            'viongozi' => $viongozi,
            'vyakitume' => $vyakitume,
        ];

        return $data_summary;
    }
}
