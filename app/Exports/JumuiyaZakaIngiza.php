<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Mwanafamilia;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class JumuiyaZakaIngiza implements FromCollection,WithHeadings,WithEvents,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($jumuiya){
        $this->jumuiya = $jumuiya;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
   
           
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
              
     
            },
        ];
    }

   

    public function headings():array{
        return[
            'jina_kamili',
            'namba_utambulisho',
            'kiasi',
            'tarehe'
        ];
    } 
   
    public function collection()
    {
        $jumuiya=$this->jumuiya;
      return  Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('mwanafamilias.jina_kamili','ASC')
        ->get(['mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho']);

       
       
    }

    public function map($data): array
    {
        return [
           
            Date::dateTimeToExcel($data->tarehe),
           
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
