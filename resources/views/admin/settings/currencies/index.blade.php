@extends('admin.layouts.app')

@section('content')

@can('update_currency_settings')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('System Default Currency')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="system_default_currency">
                            <div class="col-lg-3">
                                <label class="control-label">{{translate('System Default Currency')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <select class="form-control aiz-selectpicker" name="system_default_currency" data-live-search="true">
                                    @foreach ($active_currencies as $key => $currency)
                                        <option value="{{ $currency->id }}" <?php if( get_setting('system_default_currency') == $currency->id) echo 'selected'?> >{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <button class="btn btn-sm btn-primary" type="submit">{{translate('Save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Set Currency Formats')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="symbol_format">
                            <div class="col-lg-4">
                                <label class="control-label">{{translate('Symbol Format')}}</label>
                            </div>
                            <div class="col-lg-8">
                                @php $symbol_format = get_setting('symbol_format'); @endphp
                                <select class="form-control aiz-selectpicker" name="symbol_format">
                                    <option value="1" @if($symbol_format == 1) selected @endif>[Symbol] [Amount]</option>
                                    <option value="2" @if($symbol_format == 2) selected @endif>[Amount] [Symbol]</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="decimal_separator">
                            <div class="col-lg-4">
                                <label class="control-label">{{translate('Decimal Separator')}}</label>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" name="decimal_separator">
                                    <option value="1" @if(get_setting('decimal_separator') == 1) selected @endif>1,23,456.70</option>
                                    <option value="2" @if(get_setting('decimal_separator') == 2) selected @endif>1.23.456,70</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="no_of_decimals">
                            <div class="col-lg-4">
                                <label class="control-label">{{translate('No of decimals')}}</label>
                            </div>
                            <div class="col-lg-8">
                                @php $no_of_decimals = get_setting('no_of_decimals'); @endphp
                                <select class="form-control aiz-selectpicker" name="no_of_decimals">
                                    <option value="0" @if($no_of_decimals == 0) selected @endif>12345</option>
                                    <option value="1" @if($no_of_decimals == 1) selected @endif>1234.5</option>
                                    <option value="2" @if($no_of_decimals == 2) selected @endif>123.45</option>
                                    <option value="3" @if($no_of_decimals == 3) selected @endif>12.345</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endcan

    <div class="aiz-titlebar text-left mt-2 mb-3">
    	<div class="row align-items-center">
    		<div class="col-md-6">
    			<h1 class="h3">{{translate('All Currencies')}}</h1>
    		</div>
            @can('add_currencies')
        		<div class="col-md-6 text-md-right">
        			<a onclick="currency_add_edit_modal('{{ route('currencies.create') }}')" href="javascript:void(0);"  class="btn btn-circle btn-primary">
        				<span>{{translate('Add New Currency')}}</span>
        			</a>
        		</div>
            @endcan
    	</div>
    </div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('All Currencies') }}</h5>
                </div>
                <div class="col-md-4">
                    <form class="" id="sort_currencies" action="" method="GET">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('Currency name')}}</th>
                            <th data-breakpoints="md">{{translate('Currency symbol')}}</th>
                            <th data-breakpoints="md">{{translate('Currency code')}}</th>
                            {{-- <th data-breakpoints="md">{{translate('Exchange rate')}}(1 USD = ?)</th>
                            <th>{{translate('Status')}}</th> --}}
                            <th class="text-center" width="10%">{{translate('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($currencies as $key => $currency)
                            <tr>
                                <td>{{ ($key+1) + ($currencies->currentPage() - 1)*$currencies->perPage() }}</td>
                                <td>{{ $currency->name }}</td>
                                <td>{{ $currency->symbol }}</td>
                                <td>{{ $currency->code }}</td>
                                {{-- <td>{{ $currency->exchange_rate }}</td>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" id="status.{{ $key }}" onchange="update_currency_activation_status(this)" value="{{ $currency->id }}" @if($currency->status == 1) checked @endif data-switch="success"/>
                                        <span></span>
                                    </label>
                                </td> --}}
                                <td class="text-right">
                                    @if($currency->id != 1 && auth()->user()->can('edit_currencies'))
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="currency_add_edit_modal('{{ route('currencies.edit', $currency->id) }}');" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                    @endif
                                    @can('delete_currencies')
                                        <a href="javascript:void(0);" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('currency.destroy', $currency->id)}}" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $currencies->appends(request()->input())->links() }}
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

        function sort_currencies(el){
            $('#sort_currencies').submit();
        }

        function currency_add_edit_modal(url){
           $.get(url,function(data){
               $('.create_edit_modal_content').html(data);
               $('.create_edit_modal').modal('show');
           });
       }

        function update_currency_activation_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('currency.update_currency_activation_status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function (data) {
                if (data == 1) {
                    location.reload();
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
