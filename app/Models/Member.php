<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id','gender','birthday','on_behalves_id','current_package_id','remaining_interest','remaining_contact_view','remaining_photo_gallery','auto_profile_match','package_validity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function on_behalves(){
        return $this->belongsTo(OnBehalf::class)->withTrashed();
    }

    public function marital_status(){
        return $this->belongsTo(MaritalStatus::class)->withTrashed();
    }

    public function package()
    {
        return $this->belongsTo(Package::class,'current_package_id')->withTrashed();
    }

    public function mothereTongue()
    {
        return $this->belongsTo(MemberLanguage::class,'mothere_tongue')->withTrashed();
    }

}
