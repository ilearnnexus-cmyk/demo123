<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Member;
use Redirect;
use Validator;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_packages'])->only('index');
        $this->middleware(['permission:add_package'])->only('create');
        $this->middleware(['permission:edit_package'])->only('edit');
        $this->middleware(['permission:delete_package'])->only('destroy');

        $this->package_rules = [
            'name'              => ['required','max:255'],
            'price'             => ['required'],
            'express_interest'  => ['required'],
            'photo_gallery'     => ['required'],
            'contact'           => ['required'],
            'profile_image_view'=> ['required'],
            'gallery_image_view'=> ['required'],
            'validity'          => ['required'],
        ];

        $this->package_messages = [
            'name.required'              => translate('Name is required'),
            'name.max'                   => translate('Max 255 characters'),
            'price.required'             => translate('Package Price is required'),
            'express_interest.required'  => translate('No. of Express Interest is required'),
            'photo_gallery.required'     => translate('No. of Photo gallery is required'),
            'contact.required'           => translate('No. of Contact View is required'),
            'profile_image_view.required'=> translate('No. of Profile Image View is required'),
            'gallery_image_view.required'=> translate('No. of Gallery Image View View is required'),
            'validity.required'          => translate('Package Validity day is required'),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $packages      = Package::paginate(10);
      return view('admin.premium_packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.premium_packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules      = $this->package_rules;
        $messages   = $this->package_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $package                    = new Package;
        $package->name              = $request->name;
        $package->price             = filter_min_value($request->price);
        $package->express_interest  = filter_min_value($request->express_interest);
        $package->photo_gallery     = filter_min_value($request->photo_gallery);
        $package->contact           = filter_min_value($request->contact);
        $package->profile_image_view= filter_min_value($request->profile_image_view);
        $package->gallery_image_view= filter_min_value($request->gallery_image_view);
        if ($request->auto_profile_match != null) {
            $package->auto_profile_match = 1;
        }
        else {
            $package->auto_profile_match = 0;
        }
        $package->validity          = filter_min_value($request->validity);
        $package->image             = $request->package_image;

        if($package->save()){
            flash(translate('New Package has been added successfully'))->success();
            return redirect()->route('packages.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
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
        $package = Package::findOrFail(decrypt($id));
        return view('admin.premium_packages.edit', compact('package'));
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
        $rules      = $this->package_rules;
        $messages   = $this->package_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $package                    = Package::findOrFail($id);
        $package->name              = $request->name;
        $package->price             = filter_min_value($request->price);
        $package->express_interest  = filter_min_value($request->express_interest);
        $package->photo_gallery     = filter_min_value($request->photo_gallery);
        $package->contact           = filter_min_value($request->contact);
        $package->profile_image_view= filter_min_value($request->profile_image_view);
        $package->gallery_image_view= filter_min_value($request->gallery_image_view);
        if ($request->auto_profile_match != null) {
            $package->auto_profile_match = 1;
        }
        else {
            $package->auto_profile_match = 0;
        }
        $package->validity          = filter_min_value($request->validity);
        $package->image             = $request->package_image;
        if($package->save()){
            flash(translate('Package info has been updated successfully'))->success();
            return redirect()->route('packages.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    // Update Package Activate status
    public function update_package_activation_status(Request $request)
    {
        $package = Package::findOrFail($request->id);
        $package->active = $request->status;
        if ($package->save()) {
            $msg = $package->status == 1 ? translate('Enabled') : translate('Disabled');
            flash(translate($msg))->success();
            return 1;
        }
        return 0;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Package::destroy($id)){
            flash(translate('Package info has been deleted successfully'))->success();
            return redirect()->route('packages.index');
        }
        else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    public function select_package()
    {
        $packages = Package::where('active', '1')->get();
        return view('frontend.package.packages', compact('packages'));

    }

    public function package_payemnt_methods($id)
    {
        $package = Package::where('id',decrypt($id))->first();
        return view('frontend.package.select_payemnt_method', compact('package'));
    }

    function check_for_package_invalid(){
        $members = Member::all();
        foreach($members as $member){
            $package_expire_date = $member->package_validity;
            if($package_expire_date < date('Y-m-d')){
                $member->package_validity = null;
                $member->save();
            }
        }
    }

}
