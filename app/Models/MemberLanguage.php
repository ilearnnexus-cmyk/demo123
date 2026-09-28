<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberLanguage extends Model
{
  use SoftDeletes;

  public function partner_expectations()
  {
      return $this->hasmany(PartnerExpectation::class)->withTrashed();
  }

}
