<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class MafundishoEnrollment extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected $guarded = [];

    public const TYPE_KOMUNIO = 'komunio';
    public const TYPE_KIPAIMARA = 'kipaimara';
    public const TYPE_NDOA = 'ndoa';

    public const STATUS_FRESHER = 'fresher';
    public const STATUS_GRADUATED = 'graduated';


    public function mwanafamilia()
    {
        return $this->belongsTo(Mwanafamilia::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['year'] ?? Carbon::now()->format('Y'),
            function ($query, $search) {
                $query->where('year', 'like', $search.'%');
            })->when($filters['type'] ?? null, function ($query, $search) {

            $query->where('type', 'like', '%'. $search.'%');
        });
    }

}
