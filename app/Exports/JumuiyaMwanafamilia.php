<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Familia;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class JumuiyaMwanafamilia implements FromCollection,WithHeadings,WithEvents,WithColumnFormatting
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
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(20);
     
            },
        ];
    }

   

    public function headings():array{
        return[
            'jina_familia',
            'jina_mwanafamilia',
            'mawasiliano',
            'jinsia',
            'dob',
            'taaluma',
            'ndoa',
            'ekaristi',
            'kipaimara',
            'ubatizo',
            'komunio',
            'aina_ya_ndoa',
            'namba_ya_cheti',
            'parokia_ya_ubatizo',
            'jimbo_la_ubatizo',
            'cheo_familia'
        ];
    } 
   
    public function collection()
    {
        $jumuiya=$this->jumuiya;
      return  Familia::where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('familias.jina_la_familia','ASC')
        ->get(['familias.jina_la_familia']);
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
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
          
        ];
    }
}
