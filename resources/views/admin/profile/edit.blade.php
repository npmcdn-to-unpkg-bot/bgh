@extends('admin/master/index')

@section('content')
     <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <a href="#" class="list-group-item disabled">
                    Statics
                </a>
                <li class="list-group-item"><strong>Users</strong> {{ $profile->users->count() }}</li>
                <li class="list-group-item"><strong>Uploaded At</strong> {{ $profile->created_at->diffForHumans() }} </li>
                <li class="list-group-item"><strong>Last Updated</strong> {{ $profile->updated_at->diffForHumans() }} </li>
            </ul>
        </div>
        <div class="col-md-9">

            {!! Form::open(['method' => 'PATCH']) !!}

            <div class="form-group">
                <label for="addnew">Name</label>
                {!! Form::text('name',$profile->name,['class'=>'form-control','placeholder'=>'Name','required'=>'required']) !!}
            </div>

            <div class="form-group">
                <label for="addnew">Title</label>
                {!! Form::text('title',$profile->title,['class'=>'form-control','placeholder'=>'Title','required'=>'required']) !!}
            </div>

            @if($profile->id != 1 && $profile->name != 'admin')
                <div class="form-group">
                    <label for="addnew">Delete
                    </label><br/>
                    {!! Form::checkbox('delete',true,false,['rel' => 'delete']) !!}
                </div>
                <div class="form-group">
                    <p><strong>Shift images to new</strong></p>
                    <select name="shiftCategory" class="form-control" disabled rel="shiftToCategory">
                        @foreach(\App\Models\Profile::whereNotIn('id', [$profile->id])->get() as $profile)
                            <option value="{{ $profile->id }}">{{ $profile->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="form-group">
                {!! Form::label('users', 'Users:') !!}
                <select id="users" class="form-control input-lg tagging" multiple="multiple" name="users[]">
                    @foreach($field_users as $p)
                        <option value="{{ $p['id'] }}" {{ $p['value'] ? "selected=\"selected\"" : "" }}>{{ $p['fullname'] }}</option>
                    @endforeach
                </select>
            </div>

            {!! Form::submit('Update',['class'=>'btn btn-success']) !!}
            <button type="button" class="btn btn-default" rel="close">Close</button>

            {!! Form::close() !!}
        </div>

    </div>


@endsection

@section('extra-js')
    <script type="text/javascript">

        (function($){
            $.fn.extend({
                select2_sortable: function(){
                    var select = $(this);
                    $(select).select2();
                    var ul = $(select).prev('.select2-container').first('ul');
                    ul.sortable({
                        placeholder : 'ui-state-highlight',
                        items       : 'li:not(.select2-search-field)',
                        tolerance   : 'pointer',
                        stop: function() {
                            $($(ul).find('.select2-search-choice').get().reverse()).each(function() {
                                var id = $(this).data('select2Data').id;
                                var option = select.find('option[value="' + id + '"]')[0];
                                $(select).prepend(option);
                            });
                        }
                    });
                }
            });
        }(jQuery));



        $(function () {

            $("[rel=delete]").click(function () {
                $("[rel=shiftToCategory]").attr("disabled", false);
            });

            $("[rel=close]").click(function () {
                location.href='{{ url('admin/profiles/') }}';
            });

            $('#users').select2({
                placeholder: 'name..'
            });


        });

    </script>
@endsection
