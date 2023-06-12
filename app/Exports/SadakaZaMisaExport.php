<?php

namespace App\Exports;


use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SadakaZaMisaExport implements FromView
{
    protected $sadaka_za_misa;
    protected $year;
    protected $sadaka;
    protected $misa;

    public function __construct(
     Collection $sadaka_za_misa, ?string $year, ?int $sadaka, ?int $misa)
    {
       $this->sadaka_za_misa = $sadaka_za_misa;
       $this->year = $year;
       $this->sadaka = $sadaka;
        $this->misa = $misa;
    }


    public function view():View
    {

        return view('backend.reports.sadaka_za_misa',
            ['title'=> 'Sadaka za misa mwaka '. $this->year, 'sadaka_za_misa' => $this->sadaka_za_misa]);
    }
}
