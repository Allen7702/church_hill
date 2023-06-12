<?php

namespace App\Http\Controllers;

use Alert;
use App\AhadiAinayamichango;
use App\AhadiMichangoGawaJumuiya;
use App\AhadiMichangoGawaMwanafamilia;
use App\AinaZaMichango;
use App\AwamuMichango;
use App\Jumuiya;
use App\Kanda;
use App\MichangoTaslimu;
use App\Mwanafamilia;
use Auth;
use Illuminate\Http\Request;

class AhadiAinayamichangoController extends Controller
{
    //
    public function aina_mchango($id)
    {

        $mchango = AinaZaMichango::find($id);
        $jumuiyas = Jumuiya::all();

        $sum_michango_iliyorecodiwa=MichangoTaslimu::where('aina_ya_mchango',$mchango->aina_ya_mchango)->sum('kiasi');
          return view('backend.michango.ahadi_aina_za_mchango', compact('mchango', 'jumuiyas','sum_michango_iliyorecodiwa'));
    }

    public function ahadi_michango_gawia_jumuiya(Request $request)
    {

        $sum = 0;
        $idadi = 0;

      $mchango=AinaZaMichango::find($request->ahadi_michango_id);

      
        
     //  dd($mchango->ahadi_ya_aina_za_mchango->kiasi);
       
        foreach((array) $request->record as $key=>$row)
        {
            $sum = $sum + (float)str_replace(',','',$row['kiasi']);
            
        }
         if($sum<$mchango->ahadi_ya_aina_za_mchango->kiasi){

        foreach ((array) $request->record as $key => $row) {

            //creating record for saving into zaka table
           
            if (($row['jumuiya'] && $row['kiasi']) != "") {
                $data_save = [
                    'ahadi_michango_id' => $request->ahadi_michango_id,
                    'jumuiya_id' => $row['jumuiya'],
                    'kiasi' =>str_replace( ',', '',$row['kiasi']) ,
                    'imewekwa' => auth()->user()->id,
                ];

          $jumuiya=Jumuiya::find( $row['jumuiya']);
          $jumuiya_exist=null;

          if($jumuiya){
          
          $jumuiya_exist=$jumuiya->mchango_gawia->where('ahadi_michango_id',$request->ahadi_michango_id)->first();
          }

          if(is_null($jumuiya_exist)){
            AhadiMichangoGawaJumuiya::create($data_save);
            }else{
                
                $jumuiya_exist->update([
                    'kiasi' =>str_replace( ',', '',$row['kiasi']),
                     'imewekwa'=>auth()->user()->id
                ]);
            }
               
                $sum = $sum + (float)str_replace(',','',$row['kiasi']);
                $idadi = $idadi + 1;
            }
        }
        $message = "Ongezo";
        $data = "Umefanikiwa kugawa mchago kwa jumuiya $idadi kiasi ".number_format($sum,2);
        $this->setActivity($message, $data);
        return response()->json(['success'=>$data]);
    }else{

      return response()->json(['errors'=>'Tafadhali hakiki tena kiasi kilichojazwa  kimezidi  jumla ya gawio..']);
    }

       
    
    }

    private function setActivity($message, $data)
    {
        $model = new AhadiMichangoGawaJumuiya;
        activity()
            ->performedOn($model)
            ->causedBy(Auth::user())
            ->withProperties(["Taarifa" => "$data"])
            ->log($message);
    }
    
    public function ahadi_michango_jumuiya_jumla($mchango_id)

    {
        $mchango=AinaZaMichango::find($mchango_id);
        $jumuiyas=Jumuiya::all();
        $title="Utoaji wa $mchango->aina_ya_mchango kwa jumuiya zote";
        return view('backend.michango.ahadi_michango_jumuiya_jumla',compact('mchango','jumuiyas','title'));
    }

    public function ahadi_michango_jumuiya_mwanajumuiya($mchango_id,$jumuiya_id)

    {
       
        $mchango=AinaZaMichango::find($mchango_id);
        $jumuiya=Jumuiya::find($jumuiya_id);
        $title="Utoaji wamchango wa $mchango->aina_ya_mchango kwa jumuiya ya $jumuiya->jina_la_jumuiya";

        $awamu_michangos=AwamuMichango::where('jumuiya_id',$jumuiya_id)->where('ahadi_ainayamichango_id',$mchango->ahadi_ya_aina_za_mchango->id)
          ->get();
          $mwanafamilias = Mwanafamilia::where('familias.jina_la_jumuiya',$jumuiya->jina_la_jumuiya)
          ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
          ->orderBy('mwanafamilias.jina_kamili','asc')
          ->get(['mwanafamilias.jina_kamili as jina_kamili','mwanafamilias.id as mwanajumuiya_id']);
   
        return view('backend.michango.ahadi_michango_jumuiya_mwanajumuiya',compact('mchango','jumuiya','mwanafamilias','title'));
    }

    public function weka_ahadi_mwanajumuiya($mchango_id)
    {
        $mchango=AinaZaMichango::find($mchango_id);
        $title = "Uwekaji ahadi ya mchango wa $mchango->aina_ya_mchango  kwa jumuiya";

        $action = "mwanzo";

        $jumuiya = Kanda::latest('kandas.created_at')
        ->leftJoin('jumuiyas','jumuiyas.jina_la_kanda','=','kandas.jina_la_kanda')
        ->get(['kandas.jina_la_kanda','jumuiyas.jina_la_jumuiya']);

       return view('backend.michango.weka_ahadi_mwanajumuiya',compact('title','mchango','jumuiya','jumuiya','action'));
    }

    public function ahadi_mkupuo_vuta_wanajumuiya(Request $request)
    {

        $selected_jumuiya = $request->jumuiya;

        $mchango=AinaZaMichango::find($request->mchango_id);

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
        $title = "Uwekaji  $mchango->aina_ya_mchango jumuiya ya $jumuiya_title";

        //list of members
        $members = Mwanafamilia::where('familias.jina_la_jumuiya',$selected_jumuiya)
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->orderBy('mwanafamilias.jina_kamili','asc')
        ->get(['mwanafamilias.jina_kamili','mwanafamilias.id as mwanajumuiya_id']);

        //this handle the populatin of data to the table
        $action = "mkupuo";
        return view('backend.michango.weka_ahadi_mwanajumuiya', compact(['jumuiya','title','members','action','selected_jumuiya','mchango']));
    }



    public function ahadi_michango_gawia_wanafamilia(Request $request)
    {

        $sum = 0;
        $idadi = 0;

      $mchango=AinaZaMichango::find($request->ahadi_michango_id);
        
     //  dd($mchango->ahadi_ya_aina_za_mchango->kiasi);
       
      
        foreach ((array) $request->record as $key => $row) {

            //creating record for saving into zaka table
           
            if (($row['mwanajumuiya'] && $row['kiasi']) != "") {
                $data_save = [
                    'ahadi_michango_id' => $request->ahadi_michango_id,
                    'mwanafamilia_id' => $row['mwanajumuiya'],
                    'kiasi' =>str_replace( ',', '',$row['kiasi']) ,
                    'imewekwa' => auth()->user()->id,
                ];

          $mwanafamilia=Mwanafamilia::find( $row['mwanajumuiya']);
          $mwanafamilia_exist=null;

          if($mwanafamilia){
          
          $mwanafamilia_exist=$mwanafamilia->mchango_gawia->where('ahadi_michango_id',$request->ahadi_michango_id)->first();
          }

          if(is_null($mwanafamilia_exist)){
            AhadiMichangoGawaMwanafamilia::create($data_save);
            }else{
                
                $mwanafamilia_exist->update([
                    'kiasi' =>str_replace( ',', '',$row['kiasi']),
                    'imewekwa'=>auth()->user()->id
                ]);
            }
               
                $sum = $sum + (float)str_replace(',','',$row['kiasi']);
                $idadi = $idadi + 1;
            }
        }
        $message = "Ongezo";
        $data = "Umefanikiwa kugawa mchago kwa wanafamilia $idadi kiasi ".number_format($sum,2);
        $this->setActivity($message, $data);
        return response()->json(['success'=>$data]);


       
    
    }
}
