<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Denomination extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected $fillable = ['noti_10000','noti_5000','noti_2000','noti_1000','noti_500','sarafu_500','sarafu_200','sarafu_100','sarafu_50','mwekaji','tarehe','status','aina_ya_toleo','nukushi'];

}
