@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{ translate('Edit Event') }}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('events.index') }}" class="btn btn-circle btn-secondary">
                <span>{{ translate('Back to Events') }}</span>
            </a>
        </div>
    </div>
</div>
<br>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <form action="{{ route('events.update', $event->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label">{{ translate('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name', $event->name) }}">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{ translate('Event Type') }} <span class="text-danger">*</span></label>
                        <select name="event_type" class="form-control aiz-selectpicker" required>
                            <option value="">{{ translate('Select Type') }}</option>
                            <option value="Barista" {{ old('event_type', $event->event_type) == 'Barista' ? 'selected' : '' }}>Barista</option>
                            <option value="Sensory" {{ old('event_type', $event->event_type) == 'Sensory' ? 'selected' : '' }}>Sensory</option>
                            <option value="Brewing" {{ old('event_type', $event->event_type) == 'Brewing' ? 'selected' : '' }}>Brewing</option>
                            <option value="Home Barista" {{ old('event_type', $event->event_type) == 'Home Barista' ? 'selected' : '' }}>Home Barista</option>
                            <!-- Add more if needed -->
                        </select>
                        @error('event_type') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{ translate('Trainer') }} <span class="text-danger">*</span></label>
                        <input type="text" name="trainer" class="form-control" required value="{{ old('trainer', $event->trainer) }}">
                        @error('trainer') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                   <div class="form-group mb-3">
    <label class="form-label">{{ translate('Location') }} <span class="text-danger">*</span></label>
    <select name="location_id" class="form-control aiz-selectpicker" required>
        <option value="">{{ translate('Select Location') }}</option>
        @foreach($locations as $location)
            <option value="{{ $location->id }}"
                {{ old('location_id', $event->location_id) == $location->id ? 'selected' : '' }}>
                {{ $location->name }}
            </option>
        @endforeach
    </select>
    @error('location_id') <small class="text-danger">{{ $message }}</small> @enderror
</div>

                    <div class="form-group mb-3">
                        <label class="form-label">{{ translate('Date') }} <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" required value="{{ old('date', $event->date) }}">
                        @error('date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{ translate('Start Time') }} <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" class="form-control" required value="{{ old('start_time', $event->start_time) }}">
                        @error('start_time') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{ translate('End Time') }} <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" class="form-control" required value="{{ old('end_time', $event->end_time) }}">
                        @error('end_time') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-info">{{ translate('Update Event') }}</button>
                    <a href="{{ route('events.index') }}" class="btn btn-light">{{ translate('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
