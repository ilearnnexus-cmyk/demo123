<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaritalStatus extends Model
{
    use SoftDeletes;

    public function member()
    {
        return $this->hasmany(Member::class);
    }

    public function partner_expectations()
    {
        return $this->hasmany(PartnerExpectation::class)->withTrashed();
    }
}
