@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{ translate('Edit Location') }}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('locations.index') }}" class="btn btn-circle btn-secondary">
                <span>{{ translate('Back to Locations') }}</span>
            </a>
        </div>
    </div>
</div>
<br>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <form action="{{ route('locations.update', $location) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label">{{ translate('Location Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name', $location->name) }}">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-info">{{ translate('Update Location') }}</button>
                    <a href="{{ route('locations.index') }}" class="btn btn-light">{{ translate('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
