@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Gallery Images') }}</h1>
        </div>
      </div>
    </div>
    <div class="row gutters-10">
        <div class="col-md-5 mx-auto mb-3" >
          <div class="bg-grad-1 text-white rounded-lg overflow-hidden">
            <span class="size-30px rounded-circle mx-auto bg-soft-primary d-flex align-items-center justify-content-center mt-3">
                <i class="las la-image la-2x text-white"></i>
            </span>
            <div class="px-3 pt-3 pb-3">
                <div class="h4 fw-700 text-center">{{ get_remaining_package_value(Auth::user()->id,'remaining_photo_gallery') }}</div>
                <div class="opacity-50 text-center">{{ translate('Remaining Gallery Image Upload') }}</div>
            </div>
          </div>
        </div>
        <div class="col-md-5 mx-auto mb-3" >
            <a href="{{ route('gallery-image.create')}}">
                <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                    <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                        <i class="las la-plus la-3x text-white"></i>
                    </span>
                    <div class="fs-18 text-primary">{{ translate('Add New Image') }}</div>
                </div>
            </a>
        </div>
    </div>
    <div class="card-columns">
        @foreach ($gallery_images as $key => $gallery_image)
          <div class="card hov-overlay">
            <img src="{{ uploaded_asset($gallery_image->image) }}" class="card-img" alt="{{ translate('Image') }}">
            <div class="overlay">
                <div class="absolute-center">
                    <a target="_blank" href="{{ uploaded_asset($gallery_image->image) }}" class="btn btn-light btn-icon btn-circle btn-sm" title="{{ translate('View') }}">
                        <i class="las la-search"></i>
                    </a>
                    <a onclick="remove_shortlist('{{ route('gallery_image.destroy', $gallery_image->id) }}')" class="btn btn-light btn-icon btn-circle btn-sm" title="{{ translate('Remove') }}">
                        <i class="las la-trash-alt"></i>
                    </a>
                </div>
            </div>
          </div>
        @endforeach
    </div>
@endsection

@section('modal')

<div class="modal fade report_modal" id="image_delete_modal">
	<div class="modal-dialog modal-dialog-centered modal-dialog-zoom">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">{{translate('Confirm Delete')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mt-1">{{translate('Are You Sure That You Want To Delete This Image?')}}</p>
                <small class="text-danger">{{ translate('**N.B. Deleting An Image Will Not Refund Your Remaining Gallery Capacity**') }}</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Close') }}</button>
                <a id="delete_link" class="btn btn-primary">{{translate('Delete')}}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    function remove_shortlist(url) {
        $("#image_delete_modal").modal("show");
        $("#delete_link").attr("href", url);
    }
</script>
@endsection
