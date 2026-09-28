@extends('admin.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Edit Sub Caste Info')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('sub-castes.update', $sub_caste->id) }}" method="POST">
                        <input name="_method" type="hidden" value="PATCH">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">{{translate('Religion')}}</label>
                            <select class="form-control aiz-selectpicker" id="religion_id" data-live-search="true" name="religion_id" required>
                                @foreach($religions as $religion)
                                    <option value="{{$religion->id}}">{{ $religion->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">{{translate('Caste')}}</label>
                            <select class="form-control aiz-selectpicker" name="caste_id"  data-live-search="true"  id="caste_id"  required>

                            </select>
                            @error('caste_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group mb-3">
                            <label for="name">{{translate('Sub Caste Name')}}</label>
                            <input type="text" id="name" name="name" value="{{$sub_caste->name}}" class="form-control"
                                   required>
                           @error('name')
                               <small class="form-text text-danger">{{ $message }}</small>
                           @enderror
                        </div>

                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function get_caste_by_religion(){
            var religion_id = $('#religion_id').val();
            $.post('{{ route('castes.get_caste_by_religion') }}',{_token:'{{ csrf_token() }}', religion_id:religion_id}, function(data){
                $('#caste_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });

        }

        $(document).ready(function(){
            $("#religion_id > option").each(function() {
                if(this.value == '{{$sub_caste->caste->religion_id}}'){
                    $("#religion_id").val(this.value).change();
                }
            });

            get_caste_by_religion();
        });


        $('#religion_id').on('change', function() {
            get_caste_by_religion();
        });

    </script>
@endsection
