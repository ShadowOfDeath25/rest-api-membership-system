<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'duration',
        'price',
        'duration_in_months'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, "plan_id", "id")??false;
    }
}
