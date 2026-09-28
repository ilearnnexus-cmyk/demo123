@extends('admin.layouts.app')

@section('content')

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Package Payment List')}}</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('All Payments')}}</h5>
            </div>
            <div class="card-body">
              <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Member Name')}}</th>
                        <th data-breakpoints="md">{{translate('Package')}}</th>
                        <th data-breakpoints="md">{{translate('Payment Method')}}</th>
                        <th data-breakpoints="md">{{translate('Amount')}}</th>
                        <th data-breakpoints="md">{{translate('Payment Status')}}</th>
                        <th>{{translate('Payment Code')}}</th>
                        <th data-breakpoints="md">{{translate('Purchase Date')}}</th>
                        <th class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($package_payments as $key => $package_payment)
                        <tr>
                            <td>{{ ($key+1) + ($package_payments->currentPage() - 1)*$package_payments->perPage() }}</td>
                            <td>
                              @if($package_payment->user != null)
                                {{ $package_payment->user->first_name.' '.$package_payment->user->last_name }}
                              @endif
                            </td>
                            <td>
                              @if($package_payment->package != null)
                                {{ $package_payment->package->name }}
                              @endif
                            </td>
                            <td>
                              @if($package_payment->payment_method == "manual_payment")
                                {{ $package_payment->custom_payment_name }}
                              @else
                                {{ ucwords($package_payment->payment_method) }}
                              @endif
                            </td>
                            <td>{{ single_price($package_payment->amount) }}</td>
                            <td>
                              @if ($package_payment->payment_status == 'Paid')
                                  <span class="badge badge-inline badge-success text-center">{{ translate('Paid')}}</span>
                              @else
                                  <span class="badge badge-inline badge-danger text-center">{{ translate('Unpaid')}}</span>
                              @endif
                            </td>
                            <td>{{ $package_payment->payment_code }}</td>
                            <td>{{ $package_payment->created_at }}</td>
                            <td class="text-right">
                                @if($package_payment->payment_method == "manual_payment" && auth()->user()->can('manage_package_manual_payemnts'))
                                    <a href="javascript:void(0);" onclick="package_payment_details('{{ route('package-payments.show', $package_payment->id )}}')" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{ translate('View Details') }}">
                                        <i class="las la-eye"></i>
                                    </a>
                                @endif
                                @can('view_package_payment_invoice')
                                    <a href="{{ route('package_payment.invoice_admin', $package_payment->id) }}" target="_blank" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{ translate('Invoice') }}">
                                        <i class="las la-file-invoice"></i>
                                    </a>
                                @endcan
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
    </div>
</div>

@endsection
@section('modal')
    @include('modals.create_edit_modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script>
      function package_payment_details(url){
          $.get(url, function(data){
              $('.create_edit_modal_content').html(data);
              $('.create_edit_modal').modal('show');
          });
      }
    </script>
@endsection
