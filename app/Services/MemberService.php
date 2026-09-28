<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Package;

class MemberService
{
     public function store(array $data, $package)
     {
           $collection = collect($data);
           $package = collect($package);

           $current_package_id                 = $package['id'];
           $remaining_interest                 = $package['express_interest'];
           $remaining_photo_gallery            = $package['photo_gallery'];
           $remaining_contact_view             = $package['contact'];
           $remaining_profile_image_view       = $package['profile_image_view'];
           $remaining_gallery_image_view       = $package['gallery_image_view'];
           $auto_profile_match                 = $package['auto_profile_match'];
           $package_validity                   = Date('Y-m-d', strtotime($package['validity'] . " days"));

           $data = $collection->merge(compact(
            'current_package_id',
            'remaining_interest',
            'remaining_photo_gallery',
            'remaining_contact_view',
            'remaining_profile_image_view',
            'remaining_gallery_image_view',
            'auto_profile_match',
            'package_validity',
           ))->toArray();

           return Member::create($data);
     }
}
