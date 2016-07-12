@extends('master/index')
@section('custom')
   @include('user/rightsidebar')
   <div class="col-md-9">
      <h3 class="content-heading">{{ t('Users')}} </h3>
      @foreach($user->followers as $follower)
         <div class="row">
            <div class="col-md-6">
               <div class="row">
                  <div class="col-md-4 col-sm-3 pull-left">
                     <a href="{{ route('user', ['username' => $follower->user->username]) }}"><img class="thumbnail img-responsive" src="{{ Resize::avatar($follower->user,'listingAvatar') }}"></a>
                  </div>
                  <div class="col-md-8">
                     <h3 style="margin-top:0px">
                        <a href="{{ route('user', ['username' => $follower->user->username]) }}">{{ $follower->user->fullname }}</a>
                        <p>
                           <small><i class="glyphicon glyphicon-comment"></i> {{ $follower->user->comments->count() }} {{ t('comments') }} &middot; <i class="glyphicon glyphicon-picture"></i> {{ $follower->user->images->count() }} {{ t('images') }}</small>
                        </p>
                     </h3>
                     <p>{{ str_limit($follower->user->about_me,50) }}</p>
                  </div>
               </div>
            </div>
            @foreach($follower->user->latestImages->take(3) as $image)
               <div class="col-md-2 col-sm-3 col-xs-3">
                  <a href="{{ route('image', ['id' => $image->id, 'slug' => $image->slug]) }}"><img src="{{ Resize::image($image,'listingImage') }}" class="thumbnail img-responsive"></a>
               </div>
            @endforeach
            <div class="clearfix"></div>
            <hr/>
         </div>
      @endforeach
   </div>
@endsection
@section('sidebar')
@endsection