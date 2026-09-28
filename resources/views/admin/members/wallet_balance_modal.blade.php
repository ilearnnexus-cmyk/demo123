<div class="modal-header">
    <h5 class="modal-title h6">{{translate('Wallet Balance Update')}}</h5>
    <button type="button" class="close" data-dismiss="modal">
    </button>
</div>
<div class="modal-body">
  <div class="row">
      <div class="col-md-4">
          <label>{{ translate('Current Balance')}} <span class="text-danger">*</span></label>
      </div>
      <div class="col-md-8 ">
          <input type="number" lang="en" class="form-control mb-3" value="{{ $user->balance }}">
      </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <label>{{ translate('Balance Update Type')}} <span class="text-danger">*</span></label>
      </div>
      <div class="col-md-8">
          <select class="form-control selectpicker" name="payment_option" data-live-search="true">
            <option value="add">{{translate('Add')}}</option>
            <option value="deduct">{{translate('Deduct')}}</option>
          </select>
      </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <label>{{ translate('Amount')}} <span class="text-danger">*</span></label>
      </div>
      <div class="col-md-8">
          <input type="number" lang="en" class="form-control mb-3" name="amount" placeholder="{{ translate('Amount')}}" required>
      </div>
  </div>
</div>
<div class="modal-footer">
</div>
