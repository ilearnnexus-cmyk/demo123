<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailNotification;
use App\Models\User;

class ContactUsController extends Controller
{
    /**
     * Spatie Permission
     */
    public function __construct()
    {
        $this->middleware(['permission:show_contact_us_queries'])->only('index');
        $this->middleware(['permission:view_contact_us_query'])->only('show');
        $this->middleware(['permission:update_contact_us_query'])->only('update');
        $this->middleware(['permission:delete_contact_us_query'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contact_us_queries = ContactUs::latest()->paginate(10);
        return view('admin.contact_us.index', compact('contact_us_queries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactUsRequest $request)
    {
        ContactUs::create($request->validated());
        $user = User::where('user_type', 'admin')->first();
        Notification::send($user, new EmailNotification($request->subject, $request->description));
        flash(translate('Your query has been sent successfully'))->success();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function show(ContactUs $contactUs, $id)
    {
        $contactUs = ContactUs::findOrFail($id);
        return view('admin.contact_us.view', compact('contactUs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */

    public function edit(ContactUs $contactUs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */

    public function update(ContactUsRequest $request, $id)
    {
        $contactUs = ContactUs::find($id);
        $contactUs->update($request->validated());

        Notification::route('mail', $contactUs->email)->notify(new EmailNotification($contactUs->subject, $contactUs->reply));
        flash(translate('Reply has been sent successfully!'))->success();
        return redirect()->route('contact-us.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactUs $contactUs, $id)
    {
        $contactUs = ContactUs::findOrFail($id);
        $contactUs->delete();
        flash(translate('Data deleted successfully!'))->success();
        return back();
    }

    /**
     * Display Contact us page
     */

    public function show_contact_us_page()
    {
        return view('frontend.contact_us');
    }
}
