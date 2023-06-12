<?php

namespace App\Imports;

use App\MajinaUsaidizi;
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

class MajinaUsaidiziImport implements ToModel,WithHeadingRow,SkipsOnError,WithValidation,SkipsOnFailure,WithBatchInserts,WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable,SkipsErrors,SkipsFailures;

    public function __construct(){
        ini_set('max_execution_time', 540);    
    }

    public function model(array $row)
    {

        $data_save = [
            'jina_kamili' => strtoupper($row['jina_kamili']),
            'jinsia' => ucfirst(strtolower($row['jinsia'])),
            'cheo_familia' => ucfirst(strtolower($row['cheo_familia'])), 
            'mawasiliano' => $row['mawasiliano'],
            'jumuiya' => strtoupper($row['jumuiya']),
        ];

        $success = MajinaUsaidizi::create($data_save);

    }

    public function rules(): array{
        return [
            '*.jina_kamili' => ['required'],
            // '*.jinsia' => ['required'],
            // '*.cheo_familia' => ['required'],
            // '*.mawasiliano' => ['required'],
            '*.jumuiya' => ['required','exists:App\Jumuiya,jina_la_jumuiya'],
        ];
    }

    //insert 100 record in single
    public function batchSize(): int {
        return 20;
    }

    //split the excel into 100 rows
    public function chunkSize(): int{
        return 20;
    }
}
