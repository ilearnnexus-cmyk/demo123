@extends('frontend.layouts.app')

@section('content')
    <div class="bg-white py-5">
        
    </div>
    <form action="{!!route('api.razorpay.payment')!!}" method="POST" id='rozer-pay' style="display: none;">
        <!-- Note that the amount is in paise = 50 INR -->
        <!--amount need to be in paisa-->
        <script src="https://checkout.razorpay.com/v1/checkout.js"
            data-key="{{ env('RAZORPAY_KEY') }}"
            data-amount="{{$amount * 100}}"
            data-buttontext=""
            data-name="{{ env('APP_NAME') }}"
            data-description="{{ translate(ucwords(str_replace('_', ' ', $payment_type)))  }}"
            data-image="{{ uploaded_asset(get_setting('header_logo')) }}"
            data-prefill.name= "{{ auth()->user()->first_name.' '.auth()->user()->last_name}}"
            data-prefill.email= "{{ auth()->user()->email}}"
            data-theme.color="#ff7529">
        </script>
        <input type="hidden" name="_token" value="{!!csrf_token()!!}">
    </form>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#rozer-pay').submit()
        });
    </script>
@endsection


