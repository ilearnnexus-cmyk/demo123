<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Role;
use App\Models\User;
use Hash;
use App\Utility\EmailUtility;
use App\Utility\SmsUtility;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_staffs'])->only('index');
        $this->middleware(['permission:add_staffs'])->only('create');
        $this->middleware(['permission:edit_staffs'])->only('edit');
        $this->middleware(['permission:delete_staffs'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staffs = Staff::latest()->paginate(10);
        return view('admin.staff.staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::latest()->get();
        return view('admin.staff.staffs.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(User::where('email', $request->email)->first() == null){
            $user             = new User;
            $user->first_name = $request->first_name;
            $user->last_name  = $request->last_name;
            $user->email      = $request->email;
            $user->phone      = $request->mobile;
            $user->user_type  = "staff";
            $user->password   = Hash::make($request->password);
            if($user->save()){
                $staff          = new Staff;
                $staff->user_id = $user->id;
                $staff->role_id = $request->role_id;
                $user->assignRole(Role::findOrFail($request->role_id)->name);
                if($staff->save()){
                    $role_name  = Role::where('id',$staff->role_id)->first()->name;

                    // Account approval email send to staff
                    if($user->email != null && get_email_template('staff_account_opening_email','status'))
                    {
                        EmailUtility::staff_account_opening_email($user, $request->password, $role_name);
                    }

                    // Account Approval SMS send to staff
                    if($user->phone != null && addon_activation('otp_system') && (get_sms_template('staff_account_opening','status') == 1))
                    {
                        SmsUtility::staff_account_opening($user, $request->password, $role_name);
                    }

                    flash(translate('Staff has been inserted successfully'))->success();
                    return redirect()->route('staffs.index');
                }
            }
        }

        flash(translate('Email already used'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staff = Staff::findOrFail(decrypt($id));
        $roles = Role::latest()->get();
        return view('admin.staff.staffs.edit', compact('staff','roles'));
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
        $staff            = Staff::findOrFail($id);
        $user             = $staff->user;
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->phone      = $request->mobile;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        if($user->save()){
            $staff->role_id = $request->role_id;
            $user->syncRoles(Role::findOrFail($request->role_id)->name);
            if($staff->save()){
                flash(translate('Staff has been updated successfully'))->success();
                return redirect()->route('staffs.index');
            }
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy(Staff::findOrFail($id)->user->id);
        if(Staff::destroy($id)){
            flash(translate('Staff has been deleted successfully'))->success();
            return redirect()->route('staffs.index');
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }
}
