<?php

namespace App\Imports;

use App\Familia;
use App\Jumuiya;
use App\Mwanafamilia;
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
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FamiliaImport implements ToModel,WithHeadingRow,SkipsOnError,WithValidation,SkipsOnFailure,WithBatchInserts,WithChunkReading
{
    use Importable,SkipsErrors,SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)

    {

       try{
        $jumuiya_a=Jumuiya::where('jina_la_jumuiya',trim(str_replace("\\t",'',$row['jina_la_jumuiya'])))->firstorfail();
    
        $data_save = [
            'jina_la_familia' => strtoupper($row['jina_la_familia']),
            'jina_la_jumuiya' => strtoupper($row['jina_la_jumuiya']),
            'slug' => Str::slug($row['jina_la_familia']),
            'mawasiliano' => $row['mawasiliano'],
            'maoni' => strtolower($row['maoni']),
        ];

         $success = Familia::create($data_save);
      //  if($success){
            //tracking activities
            $message = "Upakiaji";
            $jina = strtoupper($row['jina_la_familia']);
            $jumuiya = strtoupper($row['jina_la_jumuiya']);
            $data = "Upakiaji wa familia $jina katika jumuiya ya $jumuiya";
            $this->setActivity($message,$data);
            
            $maoni = strtolower($row['maoni']);

            //adding the family name to mwanafamilia

            $jumuiya=$success->jina_la_jumuiya;
            $kanda_ufupisho = $this->getKandaKey($jumuiya);
  
        //set the utambulisho namba
          $namba = $this->setUtambulishoNamba($kanda_ufupisho);
            $data_mwanafamilia = [
                'jina_kamili' => strtoupper($row['jina_la_familia']),
                'familia' => $success->id,
                'mawasiliano' => $row['mawasiliano'],
                'jinsia' => $row['jinsia'],
                'namba_utambulisho'=>$namba,
                'cheo_familia' => $row['cheo_familia'],
                'ubatizo' => 'tayari',
                'maoni' => $maoni,
            ];
           
            Mwanafamilia::create($data_mwanafamilia);//added forgoten line to create mwanafamilia

            //confirm if we don't have the specific mwanafamilia with such details
          //  if(!(Mwanafamilia::where('jina_kamili',$row['jina_la_familia'])->where('familia',$success->id)->exists())){
              //  $sajili = Mwanafamilia::create($data_mwanafamilia);

                //incrementing the number of familia in jumuiya
                Jumuiya::where('jina_la_jumuiya',$row['jina_la_jumuiya'])->increment('idadi_ya_familia',1);

                Familia::whereId($success->id)->increment('wanafamilia',1);

                //tracking
                $message = "Upakiaji";
                $data = "Upakiaji wa mwanafamilia $jina katika jumuiya ya $jumuiya";
                $this->setActivity1($message,$data);
          ///  }
     //   }
    } catch (ModelNotFoundException $exception) {
        // return back()->withError($exception->getMessage())->withInput();
     }
    }

    public function rules(): array{
        return [
            '*.jina_la_jumuiya' => ['exists:App\Jumuiya,jina_la_jumuiya'],
            '*.jina_la_familia' => ['required'],
          //  '*.mawasiliano' => ['required'],
            // '*.cheo_familia' => ['required'],
            // '*.jinsia' => ['required'], 
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
        $model = new Familia; 
        activity()
        ->performedOn($model)
        ->causedBy(Auth::user())
        ->withProperties(["Taarifa" => "$data"])
        ->log($message);
    }

    //method for tracking activity
    private function setActivity1($message,$data){
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
