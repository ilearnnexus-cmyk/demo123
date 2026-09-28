<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use Redirect;
use Validator;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_currencies'])->only('index');
        $this->middleware(['permission:add_currencies'])->only('create');
        $this->middleware(['permission:edit_currencies'])->only('edit');
        $this->middleware(['permission:delete_currencies'])->only('destroy');

        $this->currency_rules = [
            'name'              => ['required','max:255',],
            'symbol'            => ['required','max:20',],
            'code'              => ['required','max:20',],
            // 'exchange_rate'     => ['required',],
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $currencies = Currency::latest();
        $active_currencies  = Currency::where('status', 1)->get();
        if ($request->has('search')){
            $sort_search = $request->search;
            $brands = $currencies->where('name', 'like', '%'.$sort_search.'%');
        }
        $currencies = $currencies->paginate(10);
        return view('admin.settings.currencies.index', compact('currencies','active_currencies','sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.settings.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules      = $this->currency_rules;
        $validator  = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back();
        }

        $currency                   = new Currency;
        $currency->name             = $request->name;
        $currency->symbol           = $request->symbol;
        $currency->code             = $request->code;
        // $currency->exchange_rate    = filter_min_value($request->exchange_rate);

        if($currency->save())
        {
            flash(translate('New Currency has been added successfully'))->success();
            return redirect()->route('currencies.index');
        }
        else {
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
        $currency = Currency::findOrFail($id);
        return view('admin.settings.currencies.edit', compact('currency'));
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
        $rules      = $this->currency_rules;
        $validator  = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back();
        }

        $currency                   = Currency::findOrFail($id);
        $currency->name             = $request->name;
        $currency->symbol           = $request->symbol;
        $currency->code             = $request->code;
        // $currency->exchange_rate    = filter_min_value($request->exchange_rate);

        if($currency->save())
        {
            flash(translate('Currency info has been updated successfully'))->success();
            return back();
        }
        else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    // Update Package Activate status
    public function update_currency_activation_status(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        $currency->status = $request->status;
        if ($currency->save()) {
            $msg = $currency->status == 1 ? translate('Enabled') : translate('Disabled');
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
        if(Currency::destroy($id))
        {
            flash(translate('Currency info has been deleted successfully'))->success();
            return back();
        }
        else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }
}
