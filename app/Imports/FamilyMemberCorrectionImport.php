<?php

namespace App\Imports;

use App\Familia;
use App\MakundiRika;
use App\Mwanafamilia;
use Illuminate\Support\Carbon;
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
use Maatwebsite\Excel\Validators\Failure;

use Throwable;

class FamilyMemberCorrectionImport implements ToModel,WithHeadingRow,SkipsOnError,WithValidation,SkipsOnFailure,WithBatchInserts,WithChunkReading
{
    use Importable,SkipsErrors,SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        //setting rika
        $rika = null;

        $mwanafamila = Mwanafamilia::firstWhere('namba_utambulisho',$row['namba_ya_utambulisho']);

        if(!empty($mwanafamila)) {

            if(!empty($row['dob'])){
                $rika = MakundiRika::where('umri_kuanzia','<=',Carbon::parse($this->transformDate($row['dob']))->age)->where('umri_ukomo','>=',Carbon::parse($this->transformDate($row['dob']))->age)->value('rika');
            }

            $data_save = [
                'jina_kamili' => !is_null($row['jina_kamili'])?strtoupper($row['jina_kamili']) : $mwanafamila->jina_kamili ,
                'mawasiliano' => !is_null($row['mawasiliano'])? $row['mawasiliano'] : $mwanafamila->mawasiliano ,
                'jinsia' => !is_null($row['jinsia'])? ucfirst(strtolower($row['jinsia'])) : $mwanafamila->jinsia ,
                'dob' =>  !is_null($row['dob'])? $this->transformDate($row['dob']) : $mwanafamila->dob ,
                'taaluma' =>  !is_null($row['taaluma'])? $row['taaluma'] : $mwanafamila->dob ,
                'komunio' =>  !is_null($row['komunio'])? $row['komunio'] : $mwanafamila->komunio ,
                'ekaristi' => !is_null($row['ekaristi'])? $row['ekaristi'] : $mwanafamila->ekaristi ,
                'kipaimara' => !is_null($row['kipaimara'])? $row['kipaimara'] : $mwanafamila->kipaimara ,
                'ndoa' => !is_null($row['ndoa'])? strtolower($row['ndoa']) : $mwanafamila->ndoa ,
                // 'cheo' => $row['cheo'],
                'ubatizo' => !is_null($row['ubatizo'])? $row['ubatizo'] : $mwanafamila->ubatizo ,
                'aina_ya_ndoa' => !is_null($row['aina_ya_ndoa'])? $row['aina_ya_ndoa'] : $mwanafamila->aina_ya_ndoa ,
                'namba_ya_cheti' => !is_null($row['namba_ya_cheti'])? $row['namba_ya_cheti'] : $mwanafamila->namba_ya_cheti ,
                'parokia_ya_ubatizo' => !is_null($row['parokia_ya_ubatizo'])? $row['parokia_ya_ubatizo'] : $mwanafamila->parokia_ya_ubatizo ,
                'jimbo_la_ubatizo' => !is_null($row['jimbo_la_ubatizo'])? $row['jimbo_la_ubatizo'] : $mwanafamila->jimbo_la_ubatizo ,
                'cheo_familia' => !is_null($row['cheo_cha_familia'])? ucfirst(strtolower($row['cheo_cha_familia'])) : $mwanafamila->cheo_familia ,
                'rika' => !is_null($rika)? $rika : $mwanafamila->rika ,
            ];

            $mwanafamila->update($data_save);
        }

    }

    public function rules(): array
    {
        return [
            '*.namba_ya_utambulisho' => ['required','exists:mwanafamilias,namba_utambulisho'],
            '*.jina_kamili' => ['required','string'],
             '*.mawasiliano' => ['sometimes','required'],
             '*.jinsia' => ['sometimes','required', Rule::in(['mwanaume','mwanamke'])],
             '*.dob' => ['sometimes', 'required'],
//            // '*.taaluma' => ['required'],
             '*.komunio' => ['sometimes','required', Rule::in(['tayari', 'bado'])],
             '*.ekaristi' => ['sometimes','required', Rule::in(['hapokei', 'anapokea'])],
             '*.kipaimara' => ['sometimes','required', Rule::in(['tayari', 'bado'])],
             '*.ndoa' => ['sometimes','required', Rule::in(['tayari', 'bado'])],
             '*.aina_ya_ndoa' => ['sometimes','required', 'string'],
             '*.ubatizo' => ['sometimes','required', Rule::in(['tayari', 'bado'])],
             '*.cheo_cha_familia' => ['sometimes','required', Rule::in(['baba', 'mama', 'mtoto', 'ndungu', 'mlezi'])],
        ];
    }

    //insert 100 record in single
    public function batchSize(): int
    {
        return 100;
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

    //split the excel into 100 rows
    public function chunkSize(): int
    {
        return 100;
    }

    //method for tracking activity
    private function setActivity($message,$data)
    {
        $model = new Mwanafamilia;
        activity()
            ->performedOn($model)
            ->causedBy(Auth::user())
            ->withProperties(["Taarifa" => "$data"])
            ->log($message);
    }

    public function onError(Throwable $e)
    {
        // TODO: Implement onError() method.
    }

    public function onFailure(Failure ...$failures)
    {
        // TODO: Implement onFailure() method.
    }
}
