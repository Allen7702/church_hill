<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Jumuiya extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected $fillable = ['jina_la_jumuiya','slug','comment','jina_la_kanda','idadi_ya_familia'];


    public function sadaka_za_misa()
    {
        return $this->morphMany(SadakaZaMisa::class, 'misaable');
    }

    public function mchango_gawia(){

        return $this->hasMany(AhadiMichangoGawaJumuiya::class);
    }



}
