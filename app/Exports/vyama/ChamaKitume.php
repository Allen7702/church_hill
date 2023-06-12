<?php

namespace App\Exports\vyama;

use App\Kikundi;
use App\Wanakikundi;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ChamaKitume implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        //getting a name of kikundi
        $data = Kikundi::findOrFail($this->id);

        $title = "Ripoti ya wanachama wa chama cha ($data->jina_la_kikundi)";

        $chama = $this->id;
        //getting wanakikundi based on id submitted
        $data_wanachama = Wanakikundi::where('wanakikundis.kikundi',$this->id)
        ->leftJoin('mwanafamilias','mwanafamilias.id','=','wanakikundis.mwanafamilia')
        ->leftJoin('kikundis','kikundis.id','=','wanakikundis.kikundi')
        ->leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->get(['wanakikundis.*','mwanafamilias.jina_kamili','mwanafamilias.mawasiliano','kikundis.jina_la_kikundi','familias.jina_la_jumuiya','familias.jina_la_familia']);

        return view('backend.reports.chama_cha_kitume',compact(['title','data_wanachama']));
    }
}
