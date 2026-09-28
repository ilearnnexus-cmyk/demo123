@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Social Media Comments')}}</h5>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Social Media Comment Activation')}}</label>
                            <div class="col-md-9">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" id="status" onchange="update_third_party_activation_status(this)" value="social_media_comment_activation" data-switch="success">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Facebook Page Id')}}</label>
                            <div class="col-md-9">
                                <select class="form-control aiz-selectpicker" data-live-search="true" name="comment_type" required>
                                    <option value="facebook">{{translate('Facebook')}}</option>
                                    <option value="discus">{{translate('Discus')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Discus Id')}}</label>
                            <div class="col-md-9">
                                <input type="text" name="discus_id" value="" class="form-control" placeholder="{{translate('Discus Id')}}" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Facebook Comment Id')}}</label>
                            <div class="col-md-9">
                                <input type="text" name="fb_comment_api"  value="" class="form-control" placeholder="{{translate('Facebook Comment Id')}}">
                            </div>
                        </div>

                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{translate('Update Settings')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
    </script>
@endsection
