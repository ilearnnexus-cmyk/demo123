<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    
    public function states()
    {
        return $this->hasmany(State::class)->withTrashed();
    }

    public function addresses()
    {
        return $this->hasmany(Address::class)->withTrashed();
    }
}
