<?php

namespace App\Imports;

use App\MafundishoEnrollment;
use App\Mwanafamilia;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MafundishoImport implements ToModel,WithHeadingRow,SkipsOnError,WithValidation,SkipsOnFailure,WithBatchInserts,WithChunkReading
{
    use Importable,SkipsErrors,SkipsFailures;

    public function model(array $row)
    {
        $mwanafamilia = Mwanafamilia::firstWhere('namba_utambulisho', $row['namba_ya_utambulisho']);

       $row['hali'] == 'anasoma'?$mwanafamilia->update([$row['aina_ya_mafundisho'] => 'bado']): $mwanafamilia->update([$row['aina_ya_mafundisho'] => 'tayari']);

        if(!empty($mwanafamilia)){
            MafundishoEnrollment::firstOrCreate([
            'mwanafamilia_id' => $mwanafamilia->id,
            'type' => $row['aina_ya_mafundisho'],
            'year' => $row['mwaka']
            ],[
                'status' => $row['hali']=='anasoma'?'fresher': 'graduated',
                'started_at' => $this->transformDate($row['tarehe_ya_kuanza']),
                'ended_at' => $this->transformDate($row['tarehe_ya_kumaliza'])
            ]);
        }

    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function rules(): array
    {
        return [
            '*.namba_ya_utambulisho' => ['required','exists:mwanafamilias,namba_utambulisho'],
            '*.aina_ya_mafundisho' => ['required','string', Rule::in(['ndoa','komunio', 'kipaimara'])],
            '*.mwaka' => ['date_format:Y','required'],
            '*.hali' => ['required', Rule::in(['anasoma','amemaliza'])],
            '*.tarehe_ya_kuanza' => ['required'],
            '*.tarehe_ya_kumaliza' => ['sometimes'],
        ];
    }

    public function transformDate($value, $format = 'Y-m-d')
    {
        //checking for empty string
        if(!strlen($value)) return null;

        try {
            if($value == ""){

            }
            else{
                return Carbon::instance(Date::excelToDateTimeObject($value));
            }
        }
        catch (\ErrorException $e) {
            return Carbon::createFromFormat($format, $value);
        }
    }

}
