@extends('admin.layouts.app')
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Blog Categories')}}</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="@if(auth()->user()->can('add_blog_category')) col-lg-7 @else col-lg-12 @endif">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('All Categories') }}</h5>
                </div>
                <div class="col-md-4">
                    <form class="" id="sort_on_behalves" action="" method="GET">
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
                            <th width="5%">#</th>
                            <th>{{translate('Name')}}</th>
                            <th class="text-right">{{translate('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $key => $category)
                        <tr>
                            <td>{{ ($key+1) + ($categories->currentPage() - 1)*$categories->perPage() }}</td>
                            <td>{{ $category->category_name }}</td>
                            <td class="text-right">
                                @can('edit_blog_category')
                                <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{url('admin/blog-category/'.$category->id.'/edit')}}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                @endcan
                                @can('delete_blog_category')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('blog-category.destroy', $category->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
    @can('add_blog_category')
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Add New Blog Category')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('blog-category.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">{{translate('Name')}}</label>
                            <input type="text" id="category_name" name="category_name" placeholder="{{ translate('Name') }}" class="form-control" required>
                        </div>
                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
