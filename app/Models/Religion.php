<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Religion extends Model
{
    use SoftDeletes;

    public function castes()
    {
        return $this->hasmany(Caste::class)->withTrashed();
    }

    public function spiritual_backgrounds()
    {
        return $this->hasmany(SpiritualBackground::class)->withTrashed();
    }
}
