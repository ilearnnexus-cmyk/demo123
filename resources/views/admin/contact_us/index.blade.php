@extends('admin.layouts.app')
@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Contact Us Queries') }}</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('All Contact Us Queries') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Email') }}</th>
                                <th class="col-md-2">{{ translate('Subject') }}</th>
                                <th data-breakpoints="md">{{ translate('Created At') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-right" width="10%">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contact_us_queries as $key => $contact_us_query)
                                <tr>
                                    <td>{{ $key + 1 + ($contact_us_queries->currentPage() - 1) * $contact_us_queries->perPage() }}
                                    </td>
                                    <td> {{ $contact_us_query->name }} </td>
                                    <td>{{ $contact_us_query->email }}</td>
                                    <td>{{ $contact_us_query->subject }}</td>
                                    <td>{{ date('d-m-Y', strtotime($contact_us_query->created_at)) }}</td>
                                    <td>
                                        <span
                                            class="badge badge-inline {{ $contact_us_query->status == 0 ? 'badge-warning' : 'badge-success' }}">
                                            {{ $contact_us_query->status == 0 ? translate('Not Replied') : translate('Replied') }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('contact-us.show', $contact_us_query->id) }}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{ translate('view') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                        <a href="{{ route('contact-us.delete', $contact_us_query->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm" title="{{ translate('Delete') }}">
                                            <i class="las la-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $contact_us_queries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
