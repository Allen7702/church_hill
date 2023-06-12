<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SadakaZaMisa extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected $guarded = [];

    public function misaable()
    {
        return $this->morphTo();
    }

    public function aina_za_sadaka()
    {
        return $this->belongsTo(AinaZaSadaka::class,'aina_za_sadaka_id');
    }

    public function aina_za_misa()
    {
        return $this->belongsTo(AinaZaMisa::class, 'aina_za_misa_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['year'] ?? Carbon::now()->format('Y'),
            function ($query, $search) {
            $query->where('ilifanyika', 'like', $search.'%');
        })->when($filters['sadaka'] ?? null, function ($query, $search) {

            $query->where('aina_za_sadaka_id', $search);
        })->when($filters['misa'] ?? null, function ($query, $search) {

            $query->where('aina_za_misa_id', $search);
        })->when($filters['type'] ?? null, function ($query, $search) {

            $query->where('misaable_type', 'like', '%'. $search.'%');
        });
    }
}
