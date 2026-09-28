<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\ProfileMatchController;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\CareerResource;
use App\Http\Resources\EducationResource;
use App\Http\Resources\GalleryImage\RequestedGalleryImage;
use App\Http\Resources\GalleryImageResource;
use App\Http\Resources\MatchedProfileResource;
use App\Http\Resources\Profile\LanguageResource;
use App\Http\Resources\PublicProfile\AboutUser;
use App\Http\Resources\PublicProfile\AddressResource;
use App\Http\Resources\PublicProfile\AstronomicInformation;
use App\Http\Resources\PublicProfile\AttitudesBehaviors;
use App\Http\Resources\PublicProfile\BasicInformation;
use App\Http\Resources\PublicProfile\FamilyInformation;
use App\Http\Resources\PublicProfile\HobbiesInterests;
use App\Http\Resources\PublicProfile\LifeStyle as PublicProfileLifeStyle;
use App\Http\Resources\PublicProfile\LifeStyleResource;
use App\Http\Resources\PublicProfile\PartnerExpectationResource;
use App\Http\Resources\PublicProfile\PhysicalAttributes;
use App\Http\Resources\PublicProfile\PresentAddress;
use App\Http\Resources\PublicProfile\ResidenceInformation;
use App\Http\Resources\PublicProfile\SpiritualSocialBackground;
use App\Models\Address;
use App\Models\Astrology;
use App\Models\Attitude;
use App\Models\Career;
use App\Models\Caste;
use App\Models\ChatThread;
use App\Models\City;
use App\Models\Country;
use App\Models\Education;
use App\Models\ExpressInterest;
use App\Models\Family;
use App\Models\FamilyValue;
use App\Models\GalleryImage;
use App\Models\HappyStory;
use App\Models\Hobby;
use App\Models\IgnoredUser;
use App\Models\Lifestyle;
use App\Models\MaritalStatus;
use App\Models\Member;
use App\Models\MemberLanguage;
use App\Models\OnBehalf;
use App\Models\PackagePayment;
use App\Models\PartnerExpectation;
use App\Models\PhysicalAttribute;
use App\Models\ProfileMatch;
use App\Models\Recidency;
use App\Models\Religion;
use App\Models\ReportedUser;
use App\Models\Setting;
use App\Models\Shortlist;
use App\Models\SpiritualBackground;
use App\Models\Staff;
use App\Models\State;
use App\Models\SubCaste;
use App\Models\ViewContact;
use App\Models\ViewGalleryImage;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ProfileController extends Controller
{
    public function profile_settings()
    {
        $member             = User::findOrFail(auth()->user()->id);
        $countries          = Country::where('status', 1)->get();
        $states             = State::all();
        $cities             = City::all();
        $religions          = Religion::all();
        $castes             = Caste::all();
        $sub_castes         = SubCaste::all();
        $family_values      = FamilyValue::all();
        $marital_statuses   = MaritalStatus::all();
        $on_behalves        = OnBehalf::all();
        $languages          = MemberLanguage::all();

        return response()->json([
            'result' => true,
            'member' => $member, 'countries' => $countries, 'states' => $states, 'cities' => $cities,
            'religions' => $religions, 'castes' => $castes, 'sub_castes' => $sub_castes, 'family_values' => $family_values, 'marital_statuses' => $marital_statuses, 'on_behalves' => $on_behalves, 'languages' => $languages,
        ]);
    }

    public function get_introduction()
    {
        return (new AboutUser(auth()->user()))->additional([
            'result' => true
        ]);
    }

    public function get_email()
    {
        $data['email'] = auth()->user()->email;
        return $this->response_data($data);
    }

    public function introduction_update(Request $request)
    {
        $member = Member::where('user_id', auth()->id())->first();
        $member->introduction = $request->introduction;
        $member->save();
        return $this->success_message('Introduction updated successfully!');
    }

    public function get_basic_info()
    {
        return (new BasicInformation(auth()->user()))->additional([
            'result' => true
        ]);;
    }

    public function basic_info_update(ProfileRequest $request)
    {
        if ($request->email == null && $request->phone == null) {
            return response()->json('Email and Phone number both can not be null. ');
        }

        // image upload
        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = upload_api_file($request->file('photo'));
        }

        $user               = User::findOrFail(auth()->id());
        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        if (Setting::where('type', 'profile_picture_approval_by_admin')->first()->value && $request->photo != $user->photo && auth()->user()->user_type == 'member') {
            $user->photo_approved = 0;
        }
        $user->photo        = $photo;
        $user->phone        = $request->phone;
        $user->save();
        $member                     = Member::where('user_id', $user->id)->first();
        $member->gender             = $request->gender;
        $member->on_behalves_id     = $request->on_behalf;
        $member->birthday           = date('Y-m-d', strtotime($request->date_of_birth));
        $member->marital_status_id  = $request->marital_status;
        $member->children           = $request->children;
        $member->save();
        return $this->success_message('Member basic info  has been updated successfully.');
    }

    public function present_address()
    {
        $present_address = Address::where('user_id', auth()->id())->where('type', 'present')->first();
        if ($present_address) {
            return (new AddressResource($present_address))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }
    public function permanent_address()
    {
        $present_address = Address::where('user_id', auth()->id())->where('type', 'permanent')->first();
        if ($present_address) {
            return (new AddressResource($present_address))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }

    public function address_update(Request $request)
    {
        $this->validate($request, [
            'country_id'   => ['required'],
            'state_id'     => ['required'],
            'city_id'      => ['required'],
            'postal_code'  => ['required', 'numeric'],
        ]);
        $address = Address::where('user_id', auth()->id())->where('type', $request->address_type)->first();
        if (empty($address)) {
            $address = new Address();
            $address->user_id = auth()->id();
        }
        $address->country_id   = $request->country_id;
        $address->state_id     = $request->state_id;
        $address->city_id      = $request->city_id;
        $address->postal_code  = $request->postal_code;
        $address->type         = $request->address_type;
        $address->save();
        return $this->success_message('Address info has been updated successfully');
    }

    public function physical_attributes()
    {
        if (auth()->user()->physical_attributes) {
            return (new PhysicalAttributes(auth()->user()->physical_attributes))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }
    public function physical_attributes_update(Request $request)
    {
        $this->validate($request, [
            'height'       => ['required', 'numeric'],
            'weight'       => ['required', 'numeric'],
            'eye_color'    => ['required', 'max:50'],
            'hair_color'   => ['required', 'max:50'],
            'complexion'   => ['required', 'max:50'],
            'blood_group'  => ['required', 'max:3'],
            'body_type'    => ['required', 'max:50'],
            'body_art'     => ['required', 'max:50'],
            'disability'   => ['max:255'],
        ]);

        $physical_attribute = PhysicalAttribute::where('user_id', auth()->id())->first();
        if (empty($physical_attribute)) {
            $physical_attribute = new PhysicalAttribute;
            $physical_attribute->user_id = auth()->id();
        }
        $physical_attribute->height        = $request->height;
        $physical_attribute->weight        = $request->weight;
        $physical_attribute->eye_color     = $request->eye_color;
        $physical_attribute->hair_color    = $request->hair_color;
        $physical_attribute->complexion    = $request->complexion;
        $physical_attribute->blood_group   = $request->blood_group;
        $physical_attribute->body_type     = $request->body_type;
        $physical_attribute->body_art      = $request->body_art;
        $physical_attribute->disability    = $request->disability;
        $physical_attribute->save();
        return $this->success_message('Physical Attribute Info has been updated successfully');
    }
    public function member_language()
    {
        $member_known_languages = null;
        $member_mother_tongue = null;
        $known_languages = json_decode(auth()->user()->member->known_languages);
        $mother_tongue = auth()->user()->member->mothere_tongue;
        if ($known_languages != null) {
            $member_known_languages = LanguageResource::collection(MemberLanguage::whereIn('id', $known_languages)->get());
        }
        if ($mother_tongue != null) {
            $member_mother_tongue =  new LanguageResource(MemberLanguage::where('id', $mother_tongue)->first());
        }
        $data['mother_tongue'] = $member_mother_tongue;
        $data['known_languages'] = $member_known_languages;
        return $this->response_data($data);
    }

    public function member_language_update(Request $request)
    {
        $member  = Member::where('user_id', auth()->id())->first();
        if ($member) {
            $member->mothere_tongue     = $request->mothere_tongue;
            $member->known_languages    = $request->known_languages;
            $member->save();
            return $this->success_message('Member language info has been updated successfully');
        }

        return $this->failure_message('You are not authorized');
    }
    public function hobbies_interest()
    {
        if (auth()->user()->hobbies) {
            return (new HobbiesInterests(auth()->user()->hobbies))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }
    public function hobbies_interest_update(Request $request)
    {
        $hobbies = Hobby::where('user_id', auth()->id())->first();
        if (empty($hobbies)) {
            $hobbies = new Hobby;
            $hobbies->user_id = auth()->id();
        }
        $hobbies->hobbies              = $request->hobbies;
        $hobbies->interests            = $request->interests;
        $hobbies->music                = $request->music;
        $hobbies->books                = $request->books;
        $hobbies->movies               = $request->movies;
        $hobbies->tv_shows             = $request->tv_shows;
        $hobbies->sports               = $request->sports;
        $hobbies->fitness_activities   = $request->fitness_activities;
        $hobbies->cuisines             = $request->cuisines;
        $hobbies->dress_styles         = $request->dress_styles;
        $hobbies->save();
        return $this->success_message('Hobby and Interests info has been updated successfully');
    }
    public function attitude_behavior()
    {
        if (auth()->user()->attitude) {
            return (new AttitudesBehaviors(auth()->user()->attitude))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }
    public function attitude_behavior_update(Request $request)
    {
        $attitude = Attitude::where('user_id', auth()->id())->first();
        if (empty($attitude)) {
            $attitude = new Attitude;
            $attitude->user_id = auth()->id();
        }
        $attitude->affection           = $request->affection;
        $attitude->humor               = $request->humor;
        $attitude->political_views     = $request->political_views;
        $attitude->religious_service   = $request->religious_service;
        $attitude->save();
        return $this->success_message('Personal Attitude and Behavior Info has been updated successfully');
    }
    public function residency_info()
    {
        if (auth()->user()->recidency) {
            return (new ResidenceInformation(auth()->user()->recidency))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }
    public function residency_info_update(Request $request)
    {
        $recidencies = Recidency::where('user_id', auth()->id())->first();
        if (empty($recidencies)) {
            $recidencies = new Recidency;
            $recidencies->user_id = auth()->id();
        }
        $recidencies->birth_country_id         = $request->birth_country_id;
        $recidencies->recidency_country_id     = $request->recidency_country_id;
        $recidencies->growup_country_id        = $request->growup_country_id;
        $recidencies->immigration_status       = $request->immigration_status;
        $recidencies->save();
        return $this->success_message('Residency Info has been updated successfully');
    }
    public function spiritual_background()
    {
        if (auth()->user()->spiritual_backgrounds) {
            return (new SpiritualSocialBackground(auth()->user()->spiritual_backgrounds))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }

    public function spiritual_background_update(Request $request)
    {
        $spiritual_backgrounds = SpiritualBackground::where('user_id', auth()->id())->first();
        if (empty($spiritual_backgrounds)) {
            $spiritual_backgrounds          = new SpiritualBackground;
            $spiritual_backgrounds->user_id = auth()->id();
        }
        $spiritual_backgrounds->religion_id        = $request->member_religion_id;
        $spiritual_backgrounds->caste_id           = $request->member_caste_id;
        $spiritual_backgrounds->sub_caste_id       = $request->member_sub_caste_id;
        $spiritual_backgrounds->ethnicity           = $request->ethnicity;
        $spiritual_backgrounds->personal_value       = $request->personal_value;
        $spiritual_backgrounds->family_value_id       = $request->family_value_id;
        $spiritual_backgrounds->community_value       = $request->community_value;
        $spiritual_backgrounds->save();
        return $this->success_message('Spiritual Background info has been updated successfully');
    }
    public function life_style()
    {
        if (auth()->user()->lifestyles) {
            return (new LifeStyleResource(auth()->user()->lifestyles))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }
    public function life_style_update(Request $request)
    {
        $lifestyle = Lifestyle::where('user_id', auth()->id())->first();
        if (empty($lifestyle)) {
            $lifestyle             = new Lifestyle;
            $lifestyle->user_id    = auth()->id();
        }
        $lifestyle->diet          = $request->diet;
        $lifestyle->drink         = $request->drink;
        $lifestyle->smoke         = $request->smoke;
        $lifestyle->living_with   = $request->living_with;
        $lifestyle->save();
        return $this->success_message('Lifestyle info has been updated successfully');
    }
    public function astronomic_info()
    {
        if (auth()->user()->astrologies) {
            return (new AstronomicInformation(auth()->user()->astrologies))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }
    public function astronomic_info_update(Request $request)
    {
        $astrologies = Astrology::where('user_id', auth()->id())->first();
        if (empty($astrologies)) {
            $astrologies           = new Astrology;
            $astrologies->user_id  = auth()->id();
        }
        $astrologies->sun_sign         = $request->sun_sign;
        $astrologies->moon_sign        = $request->moon_sign;
        $astrologies->time_of_birth    = $request->time_of_birth;
        $astrologies->city_of_birth    = $request->city_of_birth;

        $astrologies->save();
        return $this->success_message('Astronomic Info has been updated successfully');
    }
    public function family_info()
    {
        if (auth()->user()->families) {
            return (new FamilyInformation(auth()->user()->families))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }
    public function family_info_update(Request $request)
    {
        $family = Family::where('user_id', auth()->id())->first();
        if (empty($family)) {
            $family           = new Family;
            $family->user_id  = auth()->id();
        }
        $family->father    = $request->father;
        $family->mother    = $request->mother;
        $family->sibling   = $request->sibling;
        $family->save();
        return $this->success_message('Family Info has been updated successfully');
    }
    public function partner_expectation()
    {
        if (auth()->user()->partner_expectations) {
            return (new PartnerExpectationResource(auth()->user()->partner_expectations))->additional([
                'result' => true
            ]);
        } else {
            return $this->failure_message('No Data Found!!');
        }
    }
    public function partner_expectation_update(Request $request)
    {
        $user  = User::where('id', auth()->id())->first();
        $partner_expectations = PartnerExpectation::where('user_id', auth()->id())->first();
        if (empty($partner_expectations)) {
            $partner_expectations           = new PartnerExpectation;
            $partner_expectations->user_id  = auth()->id();
        }
        $partner_expectations->general                   = $request->general;
        $partner_expectations->height                    = $request->partner_height;
        $partner_expectations->weight                    = $request->partner_weight;
        $partner_expectations->marital_status_id         = $request->partner_marital_status;
        $partner_expectations->children_acceptable       = $request->partner_children_acceptable;
        $partner_expectations->residence_country_id      = $request->residence_country_id;
        $partner_expectations->religion_id               = $request->partner_religion_id;
        $partner_expectations->caste_id                  = $request->partner_caste_id;
        $partner_expectations->sub_caste_id              = $request->partner_sub_caste_id;
        $partner_expectations->education                 = $request->pertner_education;
        $partner_expectations->profession                = $request->partner_profession;
        $partner_expectations->smoking_acceptable        = $request->smoking_acceptable;
        $partner_expectations->drinking_acceptable       = $request->drinking_acceptable;
        $partner_expectations->diet                      = $request->partner_diet;
        $partner_expectations->body_type                 = $request->partner_body_type;
        $partner_expectations->personal_value            = $request->partner_personal_value;
        $partner_expectations->manglik                   = $request->partner_manglik;
        $partner_expectations->language_id               = $request->language_id;
        $partner_expectations->family_value_id           = $request->family_value_id;
        $partner_expectations->preferred_country_id      = $request->partner_country_id;
        $partner_expectations->preferred_state_id        = $request->partner_state_id;
        $partner_expectations->complexion                = $request->pertner_complexion;

        if ($partner_expectations->save()) {
            if ($user->member->auto_profile_match ==  1) {
                $ProfileMatchController = new ProfileMatchController;
                $ProfileMatchController->match_profiles($user->id);
            }
        }
        return $this->success_message('Partner Expectations Info has been updated successfully');
    }
    /**
     * Verify current password
     * insert new password
     */
    public function password_update(Request $request)
    {

        $this->validate($request, [
            'old_password'  => ['required'],
            'password'      => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user = User::findOrFail(auth()->id());

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->success_message('Passwoed Updated successfully.');
        }

        return $this->failure_message('Old password do not matched.');
    }

    public function account_deactivation(Request $request)
    {
        $user = auth()->user();
        $user->deactivated = $request->deacticvation_status;
        $user->save();
        $msg = $request->deacticvation_status == 1 ? 'deactivated' : 'reactivated';
        return $this->success_message(translate('Your account ' . $msg . ' successfully!'));
    }
    public function public_profile($id)
    {
        $user = User::where('id', $id)->first();
        $auth_user = auth()->user();
        if ($user) {
            $member_known_languages = null;
            $member_mother_tongue = null;
            $known_languages = json_decode($user->member->known_languages);
            $mother_tongue = json_decode($user->member->mothere_tongue);
            if ($known_languages != null) {
                $member_known_languages = LanguageResource::collection(MemberLanguage::whereIn('id', $known_languages)->get());
            }
            if ($mother_tongue != null) {
                $member_mother_tongue = new LanguageResource(MemberLanguage::where('id', $mother_tongue)->first());
            }
            $data['intoduction'] = new AboutUser($user);
            $data['basic_info'] = new BasicInformation($user);
            $data['present_address'] = Address::where('user_id', $id)->where('type', 'present')->first() ? new AddressResource(Address::where('user_id', $id)->where('type', 'present')->first()) : null;
            $data['contact_details']['email'] = $user->email;
            $data['contact_details']['phone'] = $user->phone;
            $data['education'] = $user->education ? EducationResource::collection($user->education) : null;
            $data['career'] = $user->career ? CareerResource::collection($user->career) : null;
            $data['physical_attributes'] = $user->physical_attributes ? new PhysicalAttributes($user->physical_attributes) : null;
            $data['known_languages'] = $member_known_languages;
            $data['mother_tongue'] = $member_mother_tongue;
            $data['hobbies_interest'] = $user->hobbies ? new HobbiesInterests($user->hobbies) : null;
            $data['attitude_behavior'] = $user->attitude ? new AttitudesBehaviors($user->attitude) : null;
            $data['residence_info'] = $user->recidency ? new ResidenceInformation($user->recidency) : null;
            $data['spiritual_backgrounds'] = $user->spiritual_backgrounds ? new SpiritualSocialBackground($user->spiritual_backgrounds) : null;
            $data['lifestyles'] = $user->lifestyles ? new LifeStyleResource($user->lifestyles) : null;
            $data['astrologies'] = $user->astrologies ? new AstronomicInformation($user->astrologies) : null;
            $data['permanent_address'] = Address::where('user_id', $id)->where('type', 'permanent')->first() ? new AddressResource(Address::where('user_id', $id)->where('type', 'permanent')->first()) : null;
            $data['families_information'] = $user->families ? new FamilyInformation($user->families) : null;
            $data['partner_expectation'] = $user->partner_expectations ? new PartnerExpectationResource($user->partner_expectations) : null;

            $gallery_images = GalleryImage::where('user_id', $user->id)->get();
            $gallery_image_privacy = get_setting('gallery_image_privacy');
            $gallery_image_request = ViewGalleryImage::where('user_id', $user->id)->where('requested_by', $auth_user->id)->first();
            if ($gallery_image_privacy == 'only_me') {
                if ($gallery_image_request !== null && $gallery_image_request->status == 1) {
                    $data['photo_gallery'] = GalleryImageResource::collection($gallery_images);
                } else {
                    $data['photo_gallery'] = RequestedGalleryImage::collection($gallery_images);
                }
            } elseif ($gallery_image_privacy == "all") {
                $data['photo_gallery'] = GalleryImageResource::collection($gallery_images);
            } else {
                if (package_validity($auth_user->id)) {
                    $data['photo_gallery'] = GalleryImageResource::collection($gallery_images);
                } else {
                    $data['photo_gallery'] = RequestedGalleryImage::collection($gallery_images);
                }
            }

            $data['profile_match'] = null;
            $profile_match = ProfileMatch::where('user_id', auth()->user()->id)
                ->where('match_id', $user->id)
                ->first();
            if (!empty($profile_match) && auth()->user()->member->auto_profile_match == 1) {
                $data['profile_match'] = $profile_match->match_percentage;
            }
            $data['view_contact_check'] = ViewContact::where('user_id', $user->id)->where('viewed_by', auth()->id())->first() ? true : false;

            return $this->response_data($data);
        } else {
            return $this->failure_message("User Not Found");
        }
    }
    public function contact_info_update(Request $request)
    {
        $user = User::where('id', auth()->id())->first();
        $user->email = $request->email;
        $user->phone = $request->phone;
        if ($user->save()) {
            return $this->success_message('Contact Info has been updated successfully');
        } else {
            return $this->failure_message('Something went wrong');
        }
    }

    public function store_view_contact(Request $request)
    {
        $contact_view_check = ViewContact::where('user_id', $request->id)->where('viewed_by', auth()->id())->first();
        if (!$contact_view_check) {
            $view_contact_by_user = auth()->user();
            $view_contact_by_member = $view_contact_by_user->member;

            if ($view_contact_by_member->remaining_contact_view > 0) {

                // Store view contact data
                $view_contact             = new ViewContact;
                $view_contact->user_id    = $request->id;
                $view_contact->viewed_by  = $view_contact_by_user->id;
                if ($view_contact->save()) {

                    // Deduct View Contact by user's remaining contact views
                    $view_contact_by_member->remaining_contact_view -= 1;
                    $view_contact_by_member->save();
                    return $this->success_message('Request sent successfully!');
                } else {
                    return $this->failure_message('Request failed to be sent!');
                }
            } else {
                return $this->failure_message('You do not have enough request to send');
            }
        } else {
            return $this->failure_message('You already sent an request');
        }
    }

    public function matched_profile()
    {
        $matched_profiles = [];
        $user = auth()->user();
        if ($user->member->auto_profile_match == 1) {
            $matched_profiles = ProfileMatch::orderBy('match_percentage', 'desc')
                ->where('user_id', $user->id)
                ->where('match_percentage', '>=', 50);
            $ignored_to = IgnoredUser::where('ignored_by', $user->id)->pluck('user_id')->toArray();
            if (count($ignored_to) > 0) {
                $matched_profiles = $matched_profiles->whereNotIn('match_id', $ignored_to);
            }
            $ignored_by_ids = IgnoredUser::where('user_id', $user->id)->pluck('ignored_by')->toArray();
            if (count($ignored_by_ids) > 0) {
                $matched_profiles = $matched_profiles->whereNotIn('match_id', $ignored_by_ids);
            }
            $matched_profiles = $matched_profiles->limit(20)->get();
        }
        return MatchedProfileResource::collection($matched_profiles);
    }

    public function account_delete(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->member ?  $user->member->delete() : '';
            Address::where('user_id', $user->id)->delete();
            Education::where('user_id', $user->id)->delete();
            Career::where('user_id', $user->id)->delete();
            PhysicalAttribute::where('user_id', $user->id)->delete();
            Hobby::where('user_id', $user->id)->delete();
            Attitude::where('user_id', $user->id)->delete();
            Recidency::where('user_id', $user->id)->delete();
            Lifestyle::where('user_id', $user->id)->delete();
            Astrology::where('user_id', $user->id)->delete();
            Family::where('user_id', $user->id)->delete();
            PartnerExpectation::where('user_id', $user->id)->delete();
            SpiritualBackground::where('user_id', $user->id)->delete();
            PackagePayment::where('user_id', $user->id)->delete();
            HappyStory::where('user_id', $user->id)->delete();
            Staff::where('user_id', $user->id)->delete();
            Shortlist::where('user_id', $user->id)->delete();
            IgnoredUser::where('user_id', $user->id)->delete();
            ReportedUser::where('user_id', $user->id)->delete();
            GalleryImage::where('user_id', $user->id)->delete();
            ExpressInterest::where('user_id', $user->id)->delete();
            ProfileMatch::where('user_id', $user->id)->delete();
            ChatThread::where('sender_user_id', auth()->user()->id)->orWhere('receiver_user_id', auth()->user()->id)->delete();
            User::destroy(auth()->user()->id);
            $user->tokens()
            ->where('id', $user->currentAccessToken()->id)
            ->delete();
            return $this->success_message('Your account has deleted successfully!');
        }
        return $this->failure_message('Something Went Wrong!');
    }
}
