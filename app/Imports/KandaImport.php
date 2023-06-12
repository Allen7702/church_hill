<?php

namespace App\Imports;

use App\Kanda;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Jumuiya;
use Throwable;
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

class KandaImport implements ToModel,WithHeadingRow,SkipsOnError,WithValidation,SkipsOnFailure,WithBatchInserts,WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable,SkipsErrors,SkipsFailures;

    public function model(array $row)
    {
        $data_save = [
            'jina_la_kanda' => strtoupper($row['jina_la_kanda']),
            'herufi_ufupisho' => strtoupper($row['herufi_ufupisho']),
            'slug' => Str::slug($row['jina_la_kanda']),
            'uniqueness' => time().\uniqid(),
            'comment' => $row['maoni'],
        ];

        //saving data
        $success = Kanda::create($data_save);

        if($success){
            //tracking
            $message = "Upakiaji";
            $kanda = strtoupper($row['jina_la_kanda']);
            $data = "Ongezo la kanda $kanda";
            $this->setActivity($message,$data);
        }
    }

    public function rules(): array{
        return [
            '*.jina_la_kanda' => ['required','unique:kandas'],
            '*.herufi_ufupisho' => ['required','regex:/^[a-zA-Z]+$/u','unique:kandas'],
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
        $model = new Kanda; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
