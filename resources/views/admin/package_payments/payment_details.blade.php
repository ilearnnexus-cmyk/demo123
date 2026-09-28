<div class="modal-header">
    <h5 class="modal-title h6">{{translate('Payment Details')}}</h5>
    <button type="button" class="close" data-dismiss="modal"></button>
</div>
<div class="modal-body">
  <table class="table table-bordered">
      <tbody>
          <tr>
              <th>{{translate('Payment Method')}}</th>
              <td>{{ $package_payment->custom_payment_name }}</td>
          </tr>
          <tr>
              <th>{{translate('Transaction Id')}}</th>
              <td>{{ $package_payment->custom_payment_transaction_id }}</td>
          </tr>
          <tr>
              <th>{{translate('Payemnt Proof')}}</th>
              <td>
                <a href="{{ uploaded_asset($package_payment->custom_payment_proof) }}" target="_blank" download="">
					<span>{{ translate('Download') }}</span>
				</a>
              </td>
          </tr>
          <tr>
              <th>{{translate('Details')}}</th>
              <td>{{ $package_payment->custom_payment_details }}</td>
          </tr>
      </tbody>
  </table>
</div>
<div class="modal-footer">
    @if($package_payment->payment_status != 'Paid')
      <a href="{{ route('manual_payment_accept', $package_payment->id) }}" class="btn btn-sm btn-success">{{translate('Accept')}}</a>
    @endif
    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">{{translate('Close')}}</button>
</div>
