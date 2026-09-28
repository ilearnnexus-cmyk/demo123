@extends('frontend.layouts.app')
@section('content')
    <div class="container">
        <div class="card z-depth-2-top mt-4 mb-4">
            <div class="card-body">
                <div class="print" id="printableArea">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row gutters-5">
                                <div class="col text-center text-md-left">
                                    <h2>{{ translate('Invoice') }}</h2>
                                </div>
                                <div class="col-md-7 text-right">
                                    <h3>{{ translate('Purchase Code')}} : {{ $payment->payment_code }}</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="col-sm-12">
                                <strong>{{translate('Purchase Date:')}}</strong> {{ $payment->created_at }}
                            </div>
                            <br>
                            <div class="col-sm-12">
                                <strong>{{ translate('Billed To,') }}</strong><br>
                                <b>{{ translate('Name:') }}</b> {{$payment->user->first_name.' '.$payment->user->last_name}}
                                <br>
                                <b>{{ translate('Email:') }}</b> {{$payment->user->email}}
                                <br>
                                <b>{{ translate('Phone:')}}</b> {{$payment->user->phone}}
                            </div>
                            <br>
                            <div class="col-sm-6">
                            <strong>{{ translate('Payment Method: ') }}</strong>
                                @if($payment->payment_method == "manual_payment")
                                {{ $payment->custom_payment_name }}
                                @else
                                {{ ucwords($payment->payment_method) }}
                                @endif
                                <br>
                            <strong>{{ translate('Payment Status: ') }}</strong>{{ translate($payment->payment_status) }}
                            </div>
                            <br>
                        </div>
                    </div>
                    <div class="row pt-5">
                    	<div class="col-md-12">
                        <h5><b>{{translate('Purchase Summary')}}</b></h5>
                    		<div class="card">
                    			<div class="card-body">
                    				<div class="table-responsive">
                                        <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                            <td><strong>{{ translate('Item') }}</strong></td>
                                            <td class="text-center"><strong>{{ translate('Price') }}</strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                        <td>{{ translate('Package Name: ').' '.$payment->package->name }}</td>
                                        <td class="text-center">{{single_price($payment->amount)}}</td>
                                        </tr>
                                            <tr>
                                            <td class="text-right"><strong>{{ translate('Total')}}</td>
                                            <td class="text-center">{{single_price($payment->amount)}}</td>
                                            </tr>
                                        </tbody>
                    					</table>
                    				</div>
                    			</div>
                    		</div>
                    	</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-center mt-5 mb-3">
                            <button type="button" class="btn btn-primary btn-sm" onclick="printDiv('printableArea')"><i class="las la-print"></i> <?=translate('Print')?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
    }
</script>
@endsection
