<?php 
namespace App\Exports;
 
use App\Jumuiya;
use App\Mwanafamilia;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
 
class JumuiyaZakaIngiza implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 

    public function __construct($jumuiya){
        $this->jumuiya = $jumuiya;
    }

    public function headings():array{
        return[
            'jina_kamili',
            'namba_utambulisho',
            'kiasi',  
        ];
    } 
    public function collection()
    {
        $jumuiya=$this->jumuiya;
       $data_wanajumuiya = Mwanafamilia::leftJoin('familias','familias.id','=','mwanafamilias.familia')
        ->where('familias.jina_la_jumuiya',$jumuiya)
        ->orderBy('mwanafamilias.jina_kamili','ASC')
        ->get(['mwanafamilias.jina_kamili','mwanafamilias.namba_utambulisho']);
    }
}