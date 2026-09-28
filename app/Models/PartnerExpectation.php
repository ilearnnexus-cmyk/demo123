<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerExpectation extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class)->withTrashed();
    }

    public function caste()
    {
        return $this->belongsTo(Caste::class)->withTrashed();
    }

    public function sub_caste()
    {
        return $this->belongsTo(SubCaste::class)->withTrashed();
    }
    public function family_value()
    {
        return $this->belongsTo(FamilyValue::class)->withTrashed();
    }
    public function member_language()
    {
        return $this->belongsTo(MemberLanguage::class, 'language_id')->withTrashed();
    }
    public function marital_status()
    {
        return $this->belongsTo(MaritalStatus::class)->withTrashed();
    }

}
