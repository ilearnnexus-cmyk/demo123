<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\ChatRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\ChatThreadResource;
use App\Http\Resources\MatchedProfileResource;
use App\Models\Chat;
use App\Models\ChatThread;
use App\Models\IgnoredUser;
use App\Models\ProfileMatch;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function chat_list()
    {
        $chat_threads = ChatThread::where('sender_user_id', auth()->user()->id)->orWhere('receiver_user_id', auth()->user()->id)->get();
        return  ChatThreadResource::collection($chat_threads)->additional([
            'result' => true,
        ]);
    }

    public function chat_view($id)
    {
        $chat_thread = ChatThread::findOrFail($id);
        foreach ($chat_thread->chats as $key => $chat) {
            if ($chat->sender_user_id != auth()->user()->id) {
                $chat->seen = 1;
                $chat->save();
            }
        }
        return (new ChatResource($chat_thread))->additional([
                'result' => true
            ]);
    }

    public function get_old_messages(Request $request)
    {
        $chat = Chat::findOrFail($request->first_message_id);
        $chats = Chat::where('id', '<', $chat->id)->where('chat_thread_id', $chat->chat_thread_id)->latest()->limit(20)->get();
        if(count($chats) > 0){
            return response()->json([
                'result' => true,
                'messages' => $chats,
                'first_message_id' => $chats->last()->id
            ]);            
        }
        else {
            return response()->json([
                'result' => false,
                'messages' => "",
                'first_message_id' => 0
            ]);            
        }
    }

    public function chat_reply(ChatRequest $request)
    {
        // image upload
        $attachments = [];
        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                $attachment = upload_api_file($file);
                $attachments[] = $attachment;
            }
        }      

        $chat = new ChatService();
        $new_chat = $chat->store($request->except(['_token']), $attachments);
        return $this->success_message('Data inserted successfully!');
    }
}
