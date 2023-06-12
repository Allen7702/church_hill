<?php

namespace App\Imports;

use App\Jumuiya;
use Throwable;
use App\Kanda;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
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
use App\User;

class JumuiyaImport implements ToModel,WithHeadingRow,SkipsOnError,WithValidation,SkipsOnFailure,WithBatchInserts,WithChunkReading
{
    use Importable,SkipsErrors,SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $data_save = [
            'jina_la_jumuiya' => strtoupper($row['jina_la_jumuiya']),
            'jina_la_kanda' => strtoupper($row['jina_la_kanda']),
            'slug' => Str::slug($row['jina_la_jumuiya']),
            'comment' => strtolower($row['maelezo']),
        ];

        $success = Jumuiya::create($data_save);

        if($success){

            //tracking
            $message = "Upakiaji";
            $jina = $row['jina_la_jumuiya'];
            $data = "Upakiaji wa jumuiya ya $jina";
            $this->setActivity($message,$data);

            //incrementing the number of jumuiya in kanda
            $kanda = strtoupper($row['jina_la_kanda']);
            Kanda::where('jina_la_kanda',$kanda)->increment('idadi_ya_jumuiya',1);
        }
    }

    public function rules(): array{
        return [
            '*.jina_la_kanda' => ['exists:App\Kanda,jina_la_kanda'],
            '*.jina_la_jumuiya' => ['required','unique:jumuiyas'],
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
        $model = new Jumuiya; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }
}
