@extends('master/index')
@section('meta')
   <meta name="description" content="{{ strlen($user->about_me) > 2 ? $user->about_me : $user->fullname.' '.siteSettings('description') }}">
   <meta name="keywords" content="{{ $user->fullname. ' ' .$user->username }}">
   <meta property="og:title" content="{{ ucfirst($user->fullname) }}'s {{ t('profile') }} - {{ siteSettings('siteName') }}"/>
   <meta property="og:type" content="article"/>
   <meta property="og:url" content="{{ route('user', ['id' => $user->username]) }}"/>
   <meta property="og:description" content="{{ strlen($user->about_me) > 2 ? $user->about_me : $user->fullname.' '.siteSettings('description') }}"/>
   <meta property="og:image" content="{{ Resize::avatar($user,'mainAvatar') }}"/>
@endsection
@section('custom')
   @include('user/rightsidebar')
   <div class="col-md-9">

   </div>
@endsection
@section('sidebar')
@endsection
@section('pagination')
@endsection