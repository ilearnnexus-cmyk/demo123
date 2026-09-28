@extends('admin.layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Offline Wallet Recharge Requests')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Amount')}}</th>
                    <th>{{translate('Method')}}</th>
                    <th>{{translate('Approval')}}</th>
                    <th>{{translate('Date')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wallets as $key => $wallet)
                  <tr>
                      <td>{{ ($key+1) }}</td>
                      <td>
                        @if ($wallet->user != null)
                          {{ $wallet->user->first_name.' '.$wallet->user->last_name }}
                        @endif
                      </td>
                      <td>{{ single_price($wallet->amount) }}</td>
                      <td>{{ ucwords(str_replace("_"," ",$wallet->payment_method)) }}</td>
                      <td>
                        @if ($wallet->approval == 1)
                            <span class="badge badge-inline badge-success text-center">{{ translate('Approved')}}</span>
                        @else
                            <span class="badge badge-inline badge-info text-center">{{ translate('Pending')}}</span>
                        @endif
                      </td>
                      <td>{{ $wallet->created_at }}</td>
                      <td class="text-right">
                        <a href="javascript:void(0);" onclick="wallet_payment_details('{{ route('wallet_payment_details', $wallet->id )}}')" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{ translate('View Details') }}">
                            <i class="las la-eye"></i>
                        </a>
                      </td>
                  </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $wallets->links() }}
        </div>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.create_edit_modal')
@endsection

@section('script')
<script>
  function wallet_payment_details(url){
      $.get(url, function(data){
          $('.create_edit_modal_content').html(data);
          $('.create_edit_modal').modal('show');
      });
  }
</script>
@endsection
