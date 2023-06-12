<?php

namespace App\Imports;

use App\Mwanafamilia;
use App\Familia;
use App\Jumuiya;
use App\Kanda;
use App\User;
use Throwable;
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
use Carbon;
use App\MakundiRika;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WanafamiliaImport implements ToModel,WithHeadingRow,SkipsOnError,WithValidation,SkipsOnFailure,WithBatchInserts,WithChunkReading
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
        try {

        $rika = MakundiRika::where('umri_kuanzia','<=',Carbon::parse($this->transformDate($row['dob']))->age)->where('umri_ukomo','>=',Carbon::parse($this->transformDate($row['dob']))->age)->value('rika');
        $familia=Familia::where('jina_la_familia',trim(str_replace("\\t",'',$row['jina_familia'])))->firstorfail();

        $jumuiya=$familia->jina_la_jumuiya;
        $kanda_ufupisho = $this->getKandaKey($jumuiya);
  
        //set the utambulisho namba
        $namba = $this->setUtambulishoNamba($kanda_ufupisho);

        $data_save = [
            'jina_kamili' => strtoupper($row['jina_mwanafamilia']),
            'mawasiliano' => $row['mawasiliano'],
            'jinsia' => ucfirst(strtolower($row['jinsia'])),
            'dob' => $this->transformDate($row['dob']),
            'taaluma' => $row['taaluma'],
            'familia' => $familia->id,
            'komunio' => $row['komunio'],
            'ekaristi' => $row['ekaristi'],
            'kipaimara' => $row['kipaimara'],
            'ndoa' => strtolower($row['ndoa']),
            'ubatizo' => empty($row['ubatizo'])
            ?'bado':'tayari',
            'aina_ya_ndoa' => $row['aina_ya_ndoa'],
            'namba_ya_cheti' => $row['namba_ya_cheti'],
            'parokia_ya_ubatizo' => $row['parokia_ya_ubatizo'],
            'jimbo_la_ubatizo' => $row['jimbo_la_ubatizo'],
            'namba_utambulisho'=>$namba,
            'cheo_familia' => ucfirst(strtolower($row['cheo_familia'])), 
            'rika' => $rika,
        ];

        $success = Mwanafamilia::create($data_save);

       // if($success){
            //incrementing the number of familia
            //tracking
            $jina = strtoupper($row['jina_mwanafamilia']);
            $message = "Upakiaji";
            $data = "Upakiaji wa mwanafamilia $jina";
            $this->setActivity($message,$data);

            Familia::whereId($familia->id)->increment('wanafamilia',1);

      //  }


    } catch (ModelNotFoundException $exception) {
       // return back()->withError($exception->getMessage())->withInput();
    }
     

    }

    public function rules(): array{
        return [
            '*.familia' => ['exists:App\Familia,id'],
            '*.jina_familia' => ['required'],
            // '*.mawasiliano' => ['required'],
            // '*.jinsia' => ['required'],
            // '*.dob' => ['required'],
            // '*.taaluma' => ['required'],
            // '*.komunio' => ['required'],
            // '*.ekaristi' => ['required'],
            // '*.kipaimara' => ['required'],
            // '*.ndoa' => ['required'],
            // '*.ubatizo' => ['required'],
            // '*.cheo_familia' => ['required'],
        ];
    }

    //insert 100 record in single
    public function batchSize(): int {
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
    public function chunkSize(): int{
        return 100;
    }

    //method for tracking activity
    private function setActivity($message,$data){
        
        $model = new Mwanafamilia; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }

    private function getKandaKey($jumuiya){
        //
        $jina_la_kanda = Jumuiya::where('jina_la_jumuiya',$jumuiya)->get('jina_la_kanda');

        if($jina_la_kanda->isNotEmpty()){

            foreach($jina_la_kanda as $jina){
                $kanda = $jina->jina_la_kanda;
            }

            //now getting details of kanda
            $herufi_ufupisho = Kanda::where('jina_la_kanda',$kanda)->value('herufi_ufupisho');

            return $herufi_ufupisho;
        }
        else{
            return response()->json(['errors'=>"Jumuiya $jumuiya haina kanda tafadhali jaza taarifa za kanda.."]);
        }
    }

    //processing the assigning of numbers for church members
    public function setUtambulishoNamba($herufi_ufupisho){

          
        //latest numbers
        $latest_numbers = Mwanafamilia::latest()->where('namba_utambulisho','like','%'.$herufi_ufupisho.'%')->orderBy('id','DESC')->limit(1)->get();
         
        //assign numbers
        if($latest_numbers->isNotEmpty()){

            foreach($latest_numbers as $row){
                $existing_namba = $row->namba_utambulisho;
            }

            $existing_namba = preg_replace("/[^0-9]/", "", $existing_namba);
          
            $generated_namba = ltrim($existing_namba,0);

            $generated_namba = $existing_namba+1;

            $namba_mpya = "$herufi_ufupisho".\sprintf('%04d',$generated_namba);
            return $namba_mpya;
        }
        //no results so generate new number
        else{
            $namba_mpya = "$herufi_ufupisho".\sprintf('%04d',1);
            return $namba_mpya;
        }
    }
}
