<?php

namespace App\Services;

use App\Models\Chat;

class ChatService
{
      public function store(array $data, $attachments)
      {
            $collection = collect($data);
            $attachment = null;
            $chat_thread_id = $data['chat_thread_id'];
            $sender_user_id = auth()->user()->id;
            $message = $data['message'];
            if ($attachments != null) {
                  $attachment = implode(',', $attachments);
            }
            $data = $collection->merge(compact(
                  'chat_thread_id',
                  'sender_user_id',
                  'message',
                  'attachment'
            ))->toArray();

            return Chat::create($data);
      }
}
