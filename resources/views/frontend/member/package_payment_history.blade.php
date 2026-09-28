@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Package History') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
              <thead>
                  <tr>
                      <th data-breakpoints="md">#</th>
                      <th>{{translate('Code')}}</th>
                      <th>{{translate('Package')}}</th>
                      <th data-breakpoints="md">{{translate('Payment Method')}}</th>
                      <th data-breakpoints="md">{{translate('Amount')}}</th>
                      <th data-breakpoints="md">{{translate('Payment Status')}}</th>
                      <th data-breakpoints="md">{{translate('Purchase Date')}}</th>
                      <th class="text-right">{{translate('Invoice')}}</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($package_payments as $key => $package_payment)
                      <tr>
                          <td>{{ ($key+1) + ($package_payments->currentPage() - 1)*$package_payments->perPage() }}</td>
                          <td>{{ $package_payment->payment_code }}</td>
                          <td>{{ $package_payment->package->name }}</td>
                          <td>
                            @if($package_payment->payment_method == "manual_payment")
                              {{ $package_payment->custom_payment_name }}
                            @else
                              {{ ucwords($package_payment->payment_method) }}
                            @endif
                          </td>
                          <td>{{ single_price($package_payment->amount) }}</td>
                          <td class="text-center">
                            @if ($package_payment->payment_status == 'Paid')
                                <span class="badge badge-inline badge-success">{{ translate('Paid')}}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{ translate('Unpaid')}}</span>
                            @endif
                          </td>
                          <td>{{ $package_payment->created_at }}</td>

                          <td class="text-right">
                              <a href="{{ route('package_payment.invoice', $package_payment->id) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Invoice') }}">
                                  <i class="las la-file-invoice"></i>
                              </a>
                          </td>
                      </tr>
                  @endforeach
              </tbody>
            </table>
            <div class="aiz-pagination">
                	{{ $package_payments->links() }}
          	</div>
        </div>
    </div>
@endsection
