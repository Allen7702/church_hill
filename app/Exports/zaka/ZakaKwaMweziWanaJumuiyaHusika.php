<?php

namespace App\Exports\zaka;

use App\Zaka;
use Carbon;
use Excel;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ZakaKwaMweziWanaJumuiyaHusika implements FromView
{

    public function __construct($familia, $jumuiya_data, $mwaka){
        $this->familia = $familia;
        $this->jumuiya_data = $jumuiya_data;
        $this->mwaka = $mwaka;
    }

    public function view(): View
    {

        return view('backend.reports.zaka_kwa_miezi_wanajumuiya',['zaka_data' => array_column($this->familia->toArray(), 'wanafamilias'),  'jumuiya' => $this->jumuiya_data, 'meaka' => $this->mwaka, 'title' => 'Malipo ya zaka kwa jumuiya']);

    }


}
