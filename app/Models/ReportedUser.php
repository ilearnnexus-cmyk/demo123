<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class ReportedUser extends Model
{
  protected $guarded = [];

  public function user()
  {
      return $this->belongsTo(User::class)->withTrashed();
  }

  public function reportedBy(){
      return $this->belongsTo(User::class, 'reported_by')->withTrashed();
  }

}
