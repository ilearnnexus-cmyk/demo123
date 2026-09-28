<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Artisan;
use MehediIitdu\CoreComponentRepository\CoreComponentRepository;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage_profile_sections'])->only('member_profile_sections_configuration');
        $this->middleware(['permission:header'])->only('website_header_settings');
        $this->middleware(['permission:footer'])->only('website_footer_settings');
        $this->middleware(['permission:appearances'])->only('website_appearances');
        $this->middleware(['permission:general_settings'])->only('general_settings');
        $this->middleware(['permission:payment_method_settings'])->only('payment_method_settings');
        $this->middleware(['permission:smtp_settings'])->only('smtp_settings');
        $this->middleware(['permission:third_party_settings'])->only('third_party_settings');
        $this->middleware(['permission:social_media_login_settings'])->only('social_media_login_settings');
        $this->middleware(['permission:system_update'])->only('system_update');
        $this->middleware(['permission:server_status'])->only('system_server');
        $this->middleware(['permission:firebase_push_notification'])->only('fcm_settings');
      
    }

    public function general_settings()
    {
        return view('admin.settings.general_settings');
    }

    public function smtp_settings()
    {
        return view('admin.settings.smtp_settings');
    }

    public function payment_method_settings()
    {
        return view('admin.settings.payment_method_settings');
    }

    public function third_party_settings(){
        return view('admin.settings.third_party_settings');
    }

    public function member_profile_sections_configuration ()
    {
        return view('admin.member_profile_attributes.member_profile_sections.index');
    }

    public function social_media_login_settings(){
        return view('admin.settings.social_media_login');
    }

    public function website_header_settings()
    {
        return view('admin.website_settings.header');
    }

    public function website_footer_settings()
    {
      return view('admin.website_settings.footer');
    }

    public function website_appearances()
    {
      return view('admin.website_settings.appearances');
    }

    public function system_update()
    {
      return view('admin.system.update');
    }
    public function system_server()
    {
      return view('admin.system.server_status');
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request)
     {
        foreach ($request->types as $key => $type) {
           if($type == 'site_name'){
                $this->overWriteEnvFile('APP_NAME', $request[$type]);
            }

            if($type == 'timezone'){
                $this->overWriteEnvFile('APP_TIMEZONE', $request[$type]);
            }
            else {
                $settings = Setting::where('type', $type)->first();
                if($settings != null){
                    if(gettype($request[$type]) == 'array'){
                        $settings->value = json_encode($request[$type]);
                    }
                    else {
                        $settings->value = $request[$type];
                    }
                    $settings->save();
                }
                else{
                    $settings = new Setting;
                    $settings->type = $type;
                    if(gettype($request[$type]) == 'array'){
                        $settings->value = json_encode($request[$type]);
                    }
                    else {
                        $settings->value = $request[$type];
                    }
                    $settings->save();
                }
            }
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
     }


    public function payment_method_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $payemnt_sandbox = Setting::where('type', $request->payment_method.'_sandbox')->first();
        if($payemnt_sandbox != null){
            if ($request->has($request->payment_method.'_sandbox')) {
                $payemnt_sandbox->value = 1;
                $payemnt_sandbox->save();
            }
            else{
                $payemnt_sandbox->value = 0;
                $payemnt_sandbox->save();
            }
        }

        $payemnt_activation = Setting::where('type', $request->payment_method.'_payment_activation')->first();
        if( $payemnt_activation == null){
            $payemnt_activation =  new Setting;
            $payemnt_activation->type = $request->payment_method.'_payment_activation';
            $payemnt_activation->save();
        }

        if ($request->has($request->payment_method.'_payment_activation'))
        {
            $payemnt_activation->value = 1;
            $payemnt_activation->save();
        }
        else
        {
            $payemnt_activation->value = 0;
            $payemnt_activation->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function third_party_settings_update(Request $request)
    {
      foreach ($request->types as $key => $type) {
          $this->overWriteEnvFile($type, $request[$type]);
      }

      $activation = Setting::where('type', $request->setting_type.'_activation')->first();
      if($activation != null){
          if ($request->has($request->setting_type.'_activation')) {
              $activation->value = 1;
              $activation->save();
          }
          else{
              $activation->value = 0;
              $activation->save();
          }
      }

      Artisan::call('cache:clear');

      flash(translate("Settings updated successfully"))->success();
      return back();
    }


     public function env_key_update(Request $request)
     {
         foreach ($request->types as $key => $type) {
             $this->overWriteEnvFile($type, $request[$type]);
         }
         flash(translate("Settings has been updated successfully"))->success();
         return back();
     }

     public function overWriteEnvFile($type, $val)
     {  
        if(env('DEMO_MODE') != 'On'){
            $path = base_path('.env');
            if (file_exists($path)) {
                $val = '"' . trim($val) . '"';
                if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
                    file_put_contents($path, str_replace(
                        $type . '="' . env($type) . '"', $type . '=' . $val, file_get_contents($path)
                    ));
                } else {
                    file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
                }
            }
        }
     }

    public function updateActivationSettings(Request $request)
    {
        $env_changes = ['FORCE_HTTPS'];
        if (in_array($request->type, $env_changes)) {

            return $this->updateActivationSettingsInEnv($request);
        }

        $settings = Setting::where('type', $request->type)->first();
        if($settings!=null){

            if ($request->type == 'maintenance_mode' && $request->value == '1') {
                if(env('DEMO_MODE') != 'On'){
                    Artisan::call('down');
                }
            }
            elseif ($request->type == 'maintenance_mode' && $request->value == '0') {
                if(env('DEMO_MODE') != 'On') {
                    Artisan::call('up');
                }
            }

            $settings->value = $request->value;
            $settings->save();
            
            Artisan::call('cache:clear');

            return '1';
        }
        else {
            return '0';
        }
    }


    public function updateActivationSettingsInEnv($request)
    {
        if ($request->type == 'FORCE_HTTPS' && $request->value == '1') {
            $this->overWriteEnvFile($request->type, 'On');

            if(strpos(env('APP_URL'), 'http:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("http:", "https:", env('APP_URL')));
            }

        }
        elseif ($request->type == 'FORCE_HTTPS' && $request->value == '0') {
            $this->overWriteEnvFile($request->type, 'Off');
            if(strpos(env('APP_URL'), 'https:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("https:", "http:", env('APP_URL')));
            }

        }

        return '1';
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

    public function fcm_settings(){
        return view('admin.settings.google_configurations.fcm');
    }
    
    public function fcm_settings_update(Request $request){
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }
        $settings = Setting::where('type', 'firebase_push_notification')->first();
        if($settings){
            if ($request->has('firebase_push_notification')) {
                $settings->value = 1;
                $settings->save();
            }
            else{
                $settings->value = 0;
                $settings->save();
            }
        }else{
            $settings = new Setting();
            $settings->type = 'firebase_push_notification';
            $settings->value = 1;
            $settings->save();
        }
        
        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }
}
