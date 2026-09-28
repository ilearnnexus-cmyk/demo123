<div class="modal-header">
    <h5 class="modal-title h6">{{translate('Wallet Payment Details')}}</h5>
    <button type="button" class="close" data-dismiss="modal"></button>
</div>
<div class="modal-body">
  <table class="table table-bordered">
      <tbody>
          <tr>
              <th>{{translate('Payment Method')}}</th>
              <td>{{ ucwords(str_replace("_"," ",$wallet_payment->payment_method)) }}</td>
          </tr>
          <tr>
              <th>{{translate('Transaction Id')}}</th>
              <td>{{ $wallet_payment->transaction_id }}</td>
          </tr>
          <tr>
              <th>{{translate('Payemnt Proof')}}</th>
              <td>
                <a href="{{ uploaded_asset($wallet_payment->reciept) }}" target="_blank" download="">
        					<span>{{ translate('Download') }}</span>
        				</a>
          </td>
          </tr>
          <tr>
              <th>{{translate('Details')}}</th>
              <td>{{ $wallet_payment->payment_details }}</td>
          </tr>
      </tbody>
  </table>
</div>
<div class="modal-footer">
    @if($wallet_payment->approval != 1)
      <a href="{{ route('wallet_manual_payment_accept', $wallet_payment->id) }}" class="btn btn-sm btn-success">{{translate('Accept')}}</a>
    @endif
    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">{{translate('Close')}}</button>
</div>
