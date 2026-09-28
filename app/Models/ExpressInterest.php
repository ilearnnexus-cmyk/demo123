<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ExpressInterest extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function notifications()
    {
        return $this->hasmany(Notification::class);
    }
}
