<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembersImport;
use App\Models\OnBehalf;
use App\Models\Package;
use PDF;
use Excel;

class MemberBulkAddController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:bulk_member_add'])->only('index');
    }

    public function index()
    {
        return view('admin.members.bulk_member.index');
    }

    public function pdf_download_on_behalf()
    {
        $on_behalves = OnBehalf::all();

        return PDF::loadView('admin.members.bulk_member.on_behalf_download',[
            'on_behalves' => $on_behalves,
        ], [], [])->download('on-behalf.pdf');
    }

    public function pdf_download_package()
    {
        $packages = Package::where('active', 1)->get();

        return PDF::loadView('admin.members.bulk_member.package_download',[
            'packages' => $packages,
        ], [], [])->download('packages.pdf');
    }

    public function bulk_upload(Request $request)
    {
        if($request->hasFile('bulk_file')){
            Excel::import(new MembersImport, request()->file('bulk_file'));
        }
        flash(translate('Members imported successfully'))->success();
        return back();
    }

}
