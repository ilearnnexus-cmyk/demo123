@extends('admin.layouts.blank')
@section('content')
    <div class="container pt-5">
        <div class="row">
            <div class="col-xl-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="mar-ver pad-btm text-center">
                            <h1 class="h3">Active Matrimonial CMS Settings</h1>
                            <p>Fill this form with basic information & admin login credentials</p>
                        </div>
                        <p class="text-muted font-13">
                            <form method="POST" action="{{ route('system_settings') }}">
                                @csrf
                                <div class="row">
                                  <div class="col-lg-6">
                                      <div class="form-group mb-3">
                                        <label class="form-label" for="name">Admin First Name</label>
                                        <input type="text" class="form-control" id="admin_first_name" name="admin_first_name" required>
                                      </div>
                                  </div>
                                  <div class="col-lg-6">
                                      <div class="form-group mb-3">
                                          <label class="form-label" for="name">Admin Last Name</label>
                                          <input type="text" class="form-control" id="admin_last_name" name="admin_last_name" required>
                                      </div>
                                  </div>
                                </div>

                                <div class="form-group">
                                    <label for="admin_email">Admin Email</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                                </div>

                                <div class="form-group">
                                    <label for="admin_password">Admin Password (At least 8 characters)</label>
                                    <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                                </div>

                                <div class="form-group">
                                    <label for="admin_name">System Currency</label>
                                    <select class="form-control aiz-selectpicker" data-live-search="true" name="system_default_currency" required>
                                        @foreach (\App\Models\Currency::all() as $key => $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Continue</button>
                                </div>
                            </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
