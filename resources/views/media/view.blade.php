@extends('master/index')

@section('meta')
   <meta name="description" content="{{ strlen($media->description) > 2 ? $media->description : $media->title.' '.siteSettings('description') }}">
   <meta name="keywords" content="{{ strlen($media->tags) > 2 ? $media->tags : $media->title }}">
   <meta property="og:title" content="{{ $media->title }} - {{ siteSettings('siteName') }}"/>
   <meta property="og:type" content="article"/>
   <meta property="og:url" content="{{ route('media', ['id' => $media->id, 'slug' => $media->slug]) }}"/>
   <meta property="og:description" content="{{ strlen($media->description) > 2 ? $media->description : $media->title.' '.siteSettings('description') }}"/>
   <meta property="og:media" content="{{ Resize::img($media->name,'mainMedia') }}"/>
   <meta name="author" content="{{ $media->user->fullname }}">
@endsection

@section('content')

   <h1 class="content-heading">{{ $media->title }}</h1>
   <div class="main-media">
      <p>
      <a href="{{ Resize::img($media->name,'mainMedia') }}" class="media">
         <img src="{{ Resize::img($media->name,'mainMedia') }}" alt="{{ $media->title }}" class="mainMedia img-thumbnail"/>
      </a>
      </p>
   </div>
   <!--.main-media-->
   <div class="clearfix">
      <div class="row">
         <div class="col-md-8">
            <h3 class="content-heading">
               {{ t('Description') }}
               <span class="pull-right">
               <div class="btn-group  btn-group-xs">

                  <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                     <i class="fa fa-plus fa-fw"></i> {{ t('More') }}
                     <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                     <li>
                        <a href="{{ route('media.download', ['any' => Crypt::encrypt($media->id)]) }}"><i class="fa fa-download fa-fw"></i> {{ t('Download Original') }}</a>
                     </li>
                  </ul>
                  <!-- end of dropdown menu-->
               </div>
            </span>
            </h3>
            <p>{!! nl2br(\App\Helpers\Smilies::parse($media->description))  !!}</p>
         </div>
         <div class="col-md-4">
            <h3 class="content-heading">{{ t('Details') }}</h3>
            <div class="media-status">
               <ul class="list-inline">
                  <li><i class="fa fa-eye"></i> {{ $media->views }}</li>
               </ul>
            </div>
         </div>
      </div>
   </div>
   <!--.clearfix-->

@endsection
