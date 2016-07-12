@extends('admin/master/index')
@section('content')
    <div class="box">
        <div class="box-body">
            {!! Form::open() !!}
            <div class="form-group">
                <label for="addnew">Number Of Images In Gallery</label>
                <select name="numberOfImages" class="form-control">
                    <option value="{{ perPage() }}">{{ perPage() }}</option>
                    <option>--------</option>
                    @for($i=1;$i<=100;$i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="addnew">Auto Approve Images</label>
                <select name="autoApprove" class="form-control">
                    @if(siteSettings('autoApprove') == 1)
                        <option value="1">Yes</option>
                    @else
                        <option value="0">No</option>
                    @endif
                    <option>--------</option>
                    <option value="1">Yes</option>
                    <option value="0">No ( admin is approval required )</option>
                </select>
            </div>

            <div class="form-group">
                <label for="addnew">Limit Per Day image upload by each user</label>
                <select name="limitPerDay" class="form-control">
                    <option value="{{ limitPerDay() }}">{{ limitPerDay() }}</option>
                    <option>--------</option>
                    <?php for ($l = 1; $l <= 500; $l++): ?>
                    <option value="{{ $l }}">{{ $l }}</option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="addnew">Tags Limit</label>
                <select name="tagsLimit" class="form-control">
                    <option value="{{ (int)siteSettings('tagsLimit') }}">{{ (int)siteSettings('tagsLimit') }}</option>
                    <option>--------</option>
                    <?php for ($l = 1; $l <= 30; $l++): ?>
                    <option value="{{ $l }}">{{ $l }}</option>
                    <?php endfor; ?>
                </select>
            </div>


            <div class="form-group">
                <label for="addnew">Max Image size allowed in MB</label>
                <select name="maxImageSize" class="form-control">
                    <option value="{{ siteSettings('maxImageSize') }}">{{ siteSettings('maxImageSize') }}</option>
                    <option>--------</option>
                    <?php for ($l = 1; $l <= maxUploadSize(); $l += .5): ?>
                    <option value="{{ $l }}">{{ $l }}</option>
                    <?php endfor; ?>
                </select>
            </div>


            <div class="form-group">
                {!! Form::submit('Update',array('class'=>'btn btn-success')) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

