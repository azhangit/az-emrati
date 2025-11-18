@extends('backend.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Event Details</h2>
    <div class="card">
        <div class="card-body">
            <h5>{{ $event->name }}</h5>
            <p><strong>Trainer:</strong> {{ $event->trainer }}</p>
            <p><strong>Location:</strong> {{ $event->location }}</p>
            <p><strong>Date:</strong> {{ $event->date }}</p>
            <p><strong>Time:</strong> {{ $event->time }}</p>
        </div>
    </div>
    <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
