<?php

namespace App\Exports\viongozi;

use App\User;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use DB;

class OrodhaViongoziHusika implements FromView
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
        $title = "Orodha ya viongozi wa $this->id";
        $cheo = $this->id;
        $viongozi_total = User::latest()->where('ngazi','!=','administrator')->where('cheo',$cheo)->count();
        $data_viongozi = User::latest()->where('ngazi','!=','administrator')->where('cheo',$cheo)->get();
        return view('backend.reports.orodha_viongozi_husika',compact(['data_viongozi','cheo','title','viongozi_total']));
    }
}
