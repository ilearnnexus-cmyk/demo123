<?php
namespace App\Utility;
use App\Models\User;
use App\Models\Address;
use App\Models\Shortlist;
use App\Models\ReportedUser;
use App\Models\MemberLanguage;
use App\Models\ExpressInterest;
use Illuminate\Support\Facades\Cache;

class MemberUtility
{
    public static function member_religion($user_id = '')
    {
        $user = User::find($user_id);
        $religion = (get_setting('member_spiritual_and_social_background_section') == 'on' && 
                    !empty($user->spiritual_backgrounds->religion_id)) ?
                    $user->spiritual_backgrounds->religion->name : '';
        return $religion;
    }

    public static function member_country($user_id = '')
    {
        $present_address = Address::where('type','present')->where('user_id', $user_id)->first();
        $country = get_setting('member_present_address_section') == 'on' &&
                    !empty($present_address->country_id) ?
                    $present_address->country->name : '';
        return $country;
    }

    public static function member_mothere_tongue($user_id = '')
    {
        $user = User::find($user_id);
        $mothere_tongue = (get_setting('member_language_section') == 'on' &&
                    $user->member->mothere_tongue != null) ?
                    MemberLanguage::where('id',$user->member->mothere_tongue)->first()->name : '';
        return $mothere_tongue;
    
    }
    
    public static function member_interest_info($user_id = '')
    {
        $do_expressed_interest = ExpressInterest::where('user_id', $user_id)
            ->where('interested_by', (auth()->check() && auth()->user()->id))
            ->first();
        $received_expressed_interest = ExpressInterest::where('user_id', (auth()->check() && auth()->user()->id))
            ->where('interested_by', $user_id)
            ->first();
        if (empty($do_expressed_interest) && empty($received_expressed_interest)) {
            $data['interest_status'] = 1;
            $data['interest_text'] = translate('Interest');
        }
        elseif (!empty($received_expressed_interest)) {
            $data['interest_status'] = 'do_response';
            $data['interest_text'] = $received_expressed_interest->status == 0 ? translate('Response to Interest') : translate('You Accepted Interest');
        }
        else {
            $data['interest_status'] = 0;
            $data['interest_text'] = $do_expressed_interest->status == 0 ? translate('Interest Expressed') : translate('Interest Accepted');
        }
        return $data;
    }

    public static function member_shortlist_info($user_id = '')
    {
        $shortlist = Shortlist::where('user_id', $user_id)
            ->where('shortlisted_by', (auth()->check() && auth()->user()->id))
            ->first();
        if (empty($shortlist)) {
            $data['shortlist_status'] = 1;
            $data['shortlist_text'] = translate('Shortlist');
        } else {
            $data['shortlist_status'] = 0;
            $data['shortlist_text'] = translate('Shortlisted');
        }
        return $data;
    }

    public static function member_report_status($user_id = '')
    {
        $profile_reported = ReportedUser::where('user_id', $user_id)
            ->where('reported_by', (auth()->check() && auth()->user()->id))
            ->first();
        return $profile_reported;
    }

    public static function member_check($key)
    {
        if ($key == "") {
            return false;
        }

        if(Cache::get('app-activation', 'no') == 'no'){
            try {
                $gate = "https://activeitzone.com/activation/check/matrimonial_flutter/".$key;
    
                $stream = curl_init();
                curl_setopt($stream, CURLOPT_URL, $gate);
                curl_setopt($stream, CURLOPT_HEADER, 0);
                curl_setopt($stream, CURLOPT_RETURNTRANSFER, 1);
                $rn = curl_exec($stream);
                curl_close($stream);
    
                if($rn == 'no') {
                    return false;
                }
            } catch (\Exception $e) {
    
            }
        }

        Cache::rememberForever('app-activation', function () {
            return 'yes';
        });

        return true;
    }
}
