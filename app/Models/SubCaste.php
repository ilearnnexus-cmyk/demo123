<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCaste extends Model
{
    use SoftDeletes;

    public function caste()
    {
        return $this->belongsTo(Caste::class)->withTrashed();
    }

    public function spiritual_backgrounds()
    {
        return $this->hasmany(SpiritualBackground::class)->withTrashed();
    }
}
