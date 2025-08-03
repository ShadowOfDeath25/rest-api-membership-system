<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'expiration_date'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, "plan_id", "id");
    }


}
