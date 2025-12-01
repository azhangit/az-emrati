@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<h1 class="h3">{{translate('Add New Course')}}</h1>
	</div>
</div>

<div class="row">
	<div class="col-lg-10 mx-auto">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0 h6">{{ translate('Course Information') }}</h5>
			</div>
			<div class="card-body">
				<form action="{{ route('courses.store') }}" method="POST" id="courseForm">
					@csrf
					<div class="form-group mb-3">
						<label for="institute_id">{{translate('Institute')}} <span class="text-danger">*</span></label>
						<select class="form-control aiz-selectpicker" name="institute_id" id="institute_id" data-live-search="true" required>
							<option value="">{{ translate('Select Institute') }}</option>
							@foreach($institutes as $institute)
								<option value="{{ $institute->id }}">{{ $institute->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group mb-3">
						<label for="course_module">{{translate('Course Module')}} <span class="text-danger">*</span></label>
						<input type="text" placeholder="{{translate('Course Module')}}" name="course_module" class="form-control" required>
					</div>
					<div class="form-group mb-3">
						<label for="image">{{translate('Course Image')}}</label>
						<div class="input-group" data-toggle="aizuploader" data-type="image">
							<div class="input-group-prepend">
								<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
							</div>
							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
							<input type="hidden" name="image" class="selected-files">
						</div>
						<div class="file-preview box sm">
						</div>
					</div>
					<div class="form-group mb-3">
						<label for="description">{{translate('Description')}}</label>
						<textarea class="aiz-text-editor" name="description" placeholder="{{translate('Course Description')}}"></textarea>
					</div>
					{{-- Price removed from course level, now set per schedule --}}

					<hr class="my-4">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<div>
							<h5 class="mb-1">{{ translate('Course Schedules') }} <span class="text-danger">*</span></h5>
							<p class="text-muted small mb-0">{{ translate('Add multiple dates, times, and levels for this course') }}</p>
						</div>
						<button type="button" class="btn btn-primary btn-sm" id="addSchedule">
							<i class="las la-plus-circle"></i> {{translate('Add Schedule')}}
						</button>
					</div>
					
					<div id="schedulesContainer">
						<div class="schedule-item border rounded p-3 mb-3 bg-light" data-index="0">
							<div class="d-flex justify-content-between align-items-start mb-3">
								<h6 class="mb-0 text-primary">
									<i class="las la-calendar-alt"></i> {{translate('Schedule')}} #1
								</h6>
								<button type="button" class="btn btn-sm btn-outline-danger remove-schedule" style="display:none;">
									<i class="las la-trash"></i> {{translate('Remove')}}
								</button>
							</div>
							<div class="row g-3">
								<div class="col-md-3">
									<div class="form-group mb-0">
										<label class="form-label small fw-bold">{{translate('Date')}} <span class="text-danger">*</span></label>
										<input type="date" name="schedules[0][date]" class="form-control form-control-sm schedule-date" required>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group mb-0">
										<label class="form-label small fw-bold">{{translate('Start Time')}} <span class="text-danger">*</span></label>
										<input type="time" name="schedules[0][start_time]" class="form-control form-control-sm" required>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group mb-0">
										<label class="form-label small fw-bold">{{translate('End Time')}} <span class="text-danger">*</span></label>
										<input type="time" name="schedules[0][end_time]" class="form-control form-control-sm" required>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group mb-0">
										<label class="form-label small fw-bold">{{translate('Level')}} <span class="text-danger">*</span></label>
										<input type="text" name="schedules[0][course_level]" class="form-control form-control-sm" placeholder="{{translate('e.g., Beginner')}}" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group mb-0">
										<label class="form-label small fw-bold">{{translate('Price')}} <span class="text-danger">*</span></label>
										<div class="input-group input-group-sm">
											<span class="input-group-text">Dhs.</span>
											<input type="number" step="0.01" min="0" name="schedules[0][price]" class="form-control" placeholder="0.00" required>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group mb-3 text-right">
						<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
						<a href="{{ route('courses.index') }}" class="btn btn-light">{{translate('Cancel')}}</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
<script>
let scheduleIndex = 1;

document.getElementById('addSchedule').addEventListener('click', function() {
    const container = document.getElementById('schedulesContainer');
    const template = document.querySelector('.schedule-item');
    const newSchedule = template.cloneNode(true);
    const scheduleNumber = container.querySelectorAll('.schedule-item').length + 1;
    
    newSchedule.setAttribute('data-index', scheduleIndex);
    
    // Update schedule number in header
    const scheduleHeader = newSchedule.querySelector('h6');
    if (scheduleHeader) {
        scheduleHeader.innerHTML = '<i class="las la-calendar-alt"></i> {{translate("Schedule")}} #' + scheduleNumber;
    }
    
    // Update input names
    newSchedule.querySelectorAll('input, select, textarea').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace(/\[0\]/, '[' + scheduleIndex + ']'));
            if (input.type !== 'hidden') {
                input.value = '';
            }
        }
    });
    
    // Show remove button
    const removeBtn = newSchedule.querySelector('.remove-schedule');
    if (removeBtn) {
        removeBtn.style.display = 'block';
    }
    
    container.appendChild(newSchedule);
    scheduleIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-schedule')) {
        const scheduleItem = e.target.closest('.schedule-item');
        if (document.querySelectorAll('.schedule-item').length > 1) {
            scheduleItem.remove();
        } else {
            alert('{{ translate("At least one schedule is required") }}');
        }
    }
});

// Validate at least one schedule
document.getElementById('courseForm').addEventListener('submit', function(e) {
    const schedules = document.querySelectorAll('.schedule-item');
    if (schedules.length === 0) {
        e.preventDefault();
        alert('{{ translate("Please add at least one schedule") }}');
        return false;
    }
});
</script>
@endsection
