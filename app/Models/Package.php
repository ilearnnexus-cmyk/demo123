<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    public function payckage_payments()
    {
        return $this->hasmany(PackagePayment::class)->withTrashed();
    }

    public function member()
    {
        return $this->hasmany(Member::class, 'current_package_id')->withTrashed();
    }
}
