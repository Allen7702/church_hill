<?php

namespace App\Imports;

use App\Familia;
use App\Mwanafamilia;
use App\Zaka;
use App\ZakaKilaMwezi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Str;
use Auth;
use Carbon\Carbon;

class ZakaImport implements ToModel,WithHeadingRow,SkipsOnError,WithValidation,SkipsOnFailure,WithBatchInserts,WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable,SkipsErrors,SkipsFailures;

    public function model(array $row)

    {


        $mwanafamilia=Mwanafamilia::where('jina_kamili',trim(str_replace("\\t",'',$row['jina_kamili'])))->first();

        $familia_id=$mwanafamilia->familia;
        $familia=Familia::find($familia_id);
        
       
        $data_save = [
            'jumuiya' => $familia->jina_la_jumuiya,
            'mwanajumuiya' =>$mwanafamilia->id,
            'kiasi' => trim(str_replace("\\t",'',$row['kiasi'])),
            'tarehe' =>$this->transformDate($row['tarehe']),
            'imewekwa' => auth()->user()->email,
        ];
    
        //saving data
        $success = Zaka::create($data_save);

        $mwezi=Carbon::parse($this->transformDate($row['tarehe']))->format('m');
              $zaka_za_mwezi_huo=Zaka::where('mwanajumuiya',$mwanafamilia->id)
              ->whereMonth('tarehe',$mwezi)
              ->sum('kiasi');
              
              $mwanafamilia_zaka=ZakaKilaMwezi::where('mwanafamilia_id',$mwanafamilia->id)
              ->where('mwezi',$mwezi)
              ->first();
            
              if(!is_null($mwanafamilia_zaka)){
                 $mwanafamilia_zaka->update([
                    'kiasi'=>($zaka_za_mwezi_huo+trim(str_replace("\\t",'',$row['kiasi']))),
                    'status'=>'kalipa'
                 ]);
              }else{
                //do we have to create new field in zaka_kila_mwezi with that month.
                ZakaKilaMwezi::create([
                    'mwanafamilia_id'=>$mwanafamilia->id,
                     'mwezi'=>$mwezi,
                     'kiasi'=>$zaka_za_mwezi_huo+trim(str_replace("\\t",'',$row['kiasi'])),
                     'status'=>'kalipa'
                 ]);
              }


        if($success){
            //tracking
            $message = "Upakiaji";
            $zaka = strtoupper(trim(str_replace("\\t",'',$row['jina_kamili'])));
            $data = "Ongezo la zaka $zaka";
            $this->setActivity($message,$data);
        }
    }

    public function rules(): array{
        return [
            '*.jina_kamili' => ['exists:App\Mwanafamilia,jina_kamili'],
            '*.namba_utambulisho' => ['required'],
            '*.tarehe'=>['required'],
            '*.kiasi'=>['required']
        ];
    }

    //insert 100 record in single
    public function batchSize(): int {
        return 100;
    }

    //split the excel into 100 rows
    public function chunkSize(): int{
        return 100;
    }

    //method for tracking activity
    private function setActivity($message,$data){
        
        $model = new Zaka; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }

    public function transformDate($value, $format = 'Y-m-d')
    {
        //checking for empty string
        if(!strlen($value)) return null;

        try {
            if($value == ""){

            }
            else{
                return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            }
        } 
        catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        } 
    }
}
