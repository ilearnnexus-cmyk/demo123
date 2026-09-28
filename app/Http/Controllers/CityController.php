<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Redirect;
use Validator;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_member_languages'])->only('index');
        $this->middleware(['permission:edit_member_language'])->only('edit');
        $this->middleware(['permission:delete_member_language'])->only('destroy');

        $this->city_rules = [
            'name' => ['required','max:255'],
            'state_id' => ['required'],
        ];

        $this->city_messages = [
            'name.required'             => translate('City Name is required'),
            'name.max'                  => translate('Max 255 characters'),
            'state_id.required'         => translate('State is required'),
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search   = null;
        $cities        = City::orderBy('id','asc');
        $state         = State::get();
        $countries     = Country::where('status',1)->get();

        if ($request->has('search')){
            $sort_search  = $request->search;
            $cities       = $cities->where('name', 'like', '%'.$sort_search.'%');
        }
        $cities = $cities->paginate(10);
        return view('admin.member_profile_attributes.cities.index', compact('cities','state','countries','sort_search'));

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
    public function store(Request $request)
    {
        $rules      = $this->city_rules;
        $messages   = $this->city_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $city              = new City;
        $city->name        = $request->name;
        $city->state_id    = $request->state_id;
        if($city->save())
        {
            flash(translate('New City has been added successfully'))->success();
            return redirect()->route('cities.index');
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
        $city        = city::findOrFail(decrypt($id));
        $countries   = Country::where('status',1)->get();
        return view('admin.member_profile_attributes.cities.edit', compact('city','countries'));
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
        $rules      = $this->city_rules;
        $messages   = $this->city_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $city              = City::findOrFail($id);
        $city->name        = $request->name;
        $city->state_id    = $request->state_id;
        if($city->save())
        {
            flash(translate('City info has been updated successfully'))->success();
            return redirect()->route('cities.index');
        }
        else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (City::destroy($id)) {
            flash(translate('City info has been deleted successfully'))->success();
            return redirect()->route('cities.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    // Get Cities by State
    public function get_cities_by_state(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->get();
        return $cities;
    }
}
