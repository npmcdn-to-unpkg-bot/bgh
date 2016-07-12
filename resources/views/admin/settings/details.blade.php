@extends('admin/master/index')
@section('content')
    <div class="box">
        <div class="box-body">
            {!! Form::open(['files'=> true]) !!}
            <div class="form-group">
                <label for="sitename">Site Name</label>
                {!! Form::text('sitename',siteSettings('siteName'),array('class'=>'form-control')) !!}
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                {!! Form::text('description',siteSettings('description'),['class'=>'form-control']) !!}
            </div>

            <div class="form-group">
                <label for="favicon">Favorite.ico File.</label>
                {!! Form::file('fav_icon',['accept'=>'image/*']) !!}
            </div>

            <div class="form-group">
                <label for="tos">Terms Of Services</label>
                {!! Form::textarea('tos',htmlspecialchars(siteSettings('tos')),array('class'=>'form-control ckeditor')) !!}
            </div>

            <div class="form-group">
                <label for="privacy">Privacy Policy</label>
                {!! Form::textarea('privacy',htmlspecialchars(siteSettings('privacy')),array('class'=>'form-control ckeditor')) !!}
            </div>

            <div class="form-group">
                <label for="faq">Frequently Asked Questions</label>
                {!! Form::textarea('faq',htmlspecialchars(siteSettings('faq')),['class'=>'form-control ckeditor']) !!}
            </div>

            <div class="form-group">
                <label for="about">About Us</label>
                {!! Form::textarea('about',htmlspecialchars(siteSettings('about')),['class'=>'form-control ckeditor']) !!}
            </div>

            {!! Form::submit('Update',['class'=>'btn btn-success']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('extra-js')
    <script src="//cdn.ckeditor.com/4.5.4/standard/ckeditor.js"></script>
@endsection
