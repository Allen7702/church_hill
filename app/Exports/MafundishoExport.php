<?php

namespace App\Exports;


use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MafundishoExport implements FromView
{
    protected $enrollments;
    protected $year;
    protected $type;

    public function __construct(
     Collection $enrollments, string $year, string $type)
    {
       $this->enrollments = $enrollments;
       $this->year = $year;
       $this->type = $type;
    }


    public function view():View
    {

        return view('backend.reports.mafundisho',
            ['title'=> 'Mafundisho ya '. $this->type. ' mwaka '. $this->year, 'enrollments' => $this->enrollments]);
    }
}
