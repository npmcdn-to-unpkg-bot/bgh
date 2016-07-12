@extends('admin/master/index')
@section('content')
    <div class="box">
        <div class="box-body">
            @if($report->type == 'user')
                <p>Reported {{ $report->type }}: <a href="{{ route('user', ['username' => $report->report]) }}" target="_blank">{{ $report->report }} <i class="fa fa-external-link"></i></a></p>
            @elseif($report->type == 'image')
                <p>Reported {{ $report->type }}: With id {{ $report->report }} <a href="{{ url('image/'.$report->report)}}" target="_blank">View Image <i class="fa fa-external-link"></i></a></p>
            @endif
            <p>Reported By: <a href="{{ route('user',['username' => $report->user->username]) }}">{{ $report->user->username }}</a> <small>( {{ $report->user->fullname }} )</small> </p>
            <p>Reported: <abbr class="timeago" title="{{ $report->updated_at->toDateTimeString() }}">{{ $report->updated_at->toDateTimeString() }}</abbr> </p>
            <hr>
            <h4>Description</h4>
            <hr>
            <p>{{ $report->description }}</p>
        </div>
    </div>

@endsection
