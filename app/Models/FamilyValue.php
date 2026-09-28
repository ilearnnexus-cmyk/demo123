<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyValue extends Model
{
    use SoftDeletes;

    public function spiritual_backgrounds()
    {
        return $this->hasmany(SpiritualBackground::class)->withTrashed();
    }
}
