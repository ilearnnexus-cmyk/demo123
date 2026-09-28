<div class="modal-header">
    <h5 class="modal-title h6">{{translate('Running Package Information')}}</h5>
    <button type="button" class="close" data-dismiss="modal">
    </button>
</div>
<div class="modal-body">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>{{translate('Package Name')}}</th>
                <td>{{ $member->package->name }}</td>
            </tr>
            <tr>
                <th>{{translate('Remaining Interests')}}</th>
                <td>{{ $member->remaining_interest }}</td>
            </tr>
            <tr>
                <th>{{translate('Remaining Photo Gallery')}}</th>
                <td>{{ $member->remaining_photo_gallery }}</td>
            </tr>
            <tr>
                <th>{{translate('Remaining Contact View')}}</th>
                <td>{{ $member->remaining_contact_view }}</td>
            </tr>
            <tr>
                <th>{{translate('Remaining Profile Image View')}}</th>
                <td>{{ $member->remaining_profile_image_view }}</td>
            </tr>
            <tr>
                <th>{{translate('Remaining Gallery Image View')}}</th>
                <td>{{ $member->remaining_gallery_image_view }}</td>
            </tr>
            <tr>
                <th>{{translate('Auto Profile Match Show')}}</th>
                <td>
                  @if($member->auto_profile_match == 1)
                      <span class="badge badge-inline badge-success">{{translate('On')}}</span>
                  @else
                      <span class="badge badge-inline badge-danger">{{translate('Off')}}</span>
                  @endif
                </td>
            </tr>
            <tr>
                <th>{{translate('Validity')}}</th>
                <td>
                  @if(package_validity($member->user_id))
                    {{ $member->package_validity }}
                  @else
                      <span class="badge badge-inline badge-danger">{{translate('Expired')}}</span>
                  @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button class="btn btn-success" onclick="get_package({{ $member->id }});">{{translate('Upgrade Package')}}</button>
</div>
