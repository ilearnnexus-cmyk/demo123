<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\SupportTicket\SupportTicketCategoryResource;
use App\Http\Resources\SupportTicketResource;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\SupportCategory;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    /**
     *
     * Active tickets 
     */
    public function my_ticket()
    {
        if (addon_activation('support_tickets')) {
            $my_tickets = SupportTicket::where('sender_user_id', auth()->id())->where('status', '0')->orderBy('created_at', 'desc')->paginate(10);
            if (count($my_tickets) == 0) {
                return $this->failure_message('No support ticket found!');
            } else {
                return SupportTicketResource::collection($my_tickets)->additional([
                    'result' => true
                ]);
            }
        }
        return $this->failure_message('You are not authorized to access!!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (addon_activation('support_tickets')) {
            $attachment = null;
            if ($request->hasFile('attachment')) {
                $attachment = upload_api_file($request->file('attachment'));
            }
            // if ($request->hasFile('attachments')) {
            //     foreach ($request->file('attachments') as $key => $file) {
            //         $attachment = upload_api_file($file);
            //         $attachments[] = $attachment;
            //     }
            // }
            // $attachments = implode(',', $attachments);
            $default_agent = get_setting('default_ticket_assigned_user');
            $support_ticket = new SupportTicket;
            $support_ticket->subject = $request->subject;
            $support_ticket->support_category_id = $request->support_category_id;
            $support_ticket->sender_user_id = auth()->id();
            if ($default_agent != null) {
                $support_ticket->assigned_user_id = $default_agent;
            }
            $support_ticket->ticket_id = date('Ymd-his');
            $support_ticket->description = $request->description;
            $support_ticket->attachments = $attachment;
            $support_ticket->save();
            $submit_id = $support_ticket->ticket_id;
            return $this->response_data($submit_id);
        }
        return $this->failure_message('You are not authorized to access!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (addon_activation('support_tickets')) {
            $support_ticket = SupportTicket::findOrFail($id);
            $support_ticket->seen = '1';
            $support_ticket->save();
            $support_replies    = SupportTicketReply::where('support_ticket_id', $support_ticket->id)->get();
            foreach ($support_replies as $support_replie) {
                if ($support_replie->replied_user_id != auth()->user()->id) {
                    $support_replie->seen = 1;
                    $support_replie->save();
                }
            }
            return new SupportTicketResource($support_ticket);
        }
        return $this->failure_message('You are not authorized to access!!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    public function ticket_reply(Request $request)
    {
        if (addon_activation('support_tickets')) {
            $attachments = [];
            if ($request->hasFile('attachment')) {
                $attachment = upload_api_file($request->file('attachment'));
            }
            // if ($request->hasFile('attachments')) {
            //     foreach ($request->file('attachments') as $key => $file) {
            //         $attachment = upload_api_file($file);
            //         $attachments[] = $attachment;
            //     }
            // }
            $attachments = implode(',', $attachments);
            $attachments;
            $support_ticket = SupportTicket::findOrFail($request->support_ticket_id);

            $ticket_reply                     = new SupportTicketReply;
            $ticket_reply->support_ticket_id  = $request->support_ticket_id;
            $ticket_reply->replied_user_id    = auth()->user()->id;
            $ticket_reply->reply              = $request->reply;
            $ticket_reply->attachments        = $attachment;
            if ($ticket_reply->save()) {
                if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                    $support_ticket->status   = $request->status;
                    $support_ticket->save();
                    return $this->success_message('Reply has been sent successfully');
                } else {
                    $support_ticket->status = "0";
                    $support_ticket->save();
                    return $this->success_message('Reply has been sent successfully');
                }
            } else {
                return $this->failure_message('Sorry! Something went wrong.');
            }
        }
        return $this->failure_message('You are not authorized to access!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function support_ticket_categories(){
        $support_categories = SupportCategory::orderBy('created_at','desc')->paginate(10);
        return SupportTicketCategoryResource::collection($support_categories);
    }
}
