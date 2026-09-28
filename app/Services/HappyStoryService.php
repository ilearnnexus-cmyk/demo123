<?php

namespace App\Services;

use App\Models\HappyStory;
use Illuminate\Support\Facades\Auth;

class HappyStoryService
{
      public function store(array $data, $photos)
      {
            $collection = collect($data);
            $user_id         = auth()->user()->id;
            $title           = $data['title'];
            $details         = $data['details'];
            $partner_name    = $data['partner_name'];
            $photos          = $photos;
            $video_provider  = $data['video_provider'];
            $video_link      = $data['video_link'];

            $data = $collection->merge(compact(
                  'user_id',
                  'title',
                  'details',
                  'partner_name',
                  'photos',
                  'video_provider',
                  'video_link'
            ))->toArray();
            return HappyStory::create($data);
      }
}
