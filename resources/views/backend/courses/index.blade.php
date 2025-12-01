@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<h1 class="h3">{{translate('All Courses')}}</h1>
	</div>
</div>

<div class="row">
	<div class="@if(auth()->user()->can('add_brand')) col-lg-7 @else col-lg-12 @endif">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('Courses') }}</h5>
				</div>
				<div class="col-md-4">
					<form class="" id="sort_courses" action="" method="GET">
						<div class="input-group input-group-sm">
					  		<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
						</div>
					</form>
				</div>
		    </div>
		    <div class="card-body">
		        <table class="table aiz-table mb-0">
		            <thead>
		                <tr>
		                    <th>#</th>
		                    <th>{{translate('Institute')}}</th>
		                    <th>{{translate('Course Module')}}</th>
		                    <th>{{translate('Schedules')}}</th>
		                    <th>{{translate('Price Range')}}</th>
		                    <th class="text-right">{{translate('Options')}}</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach($courses as $key => $course)
		                    <tr>
		                        <td>{{ ($key+1) + ($courses->currentPage() - 1)*$courses->perPage() }}</td>
		                        <td>{{ $course->institute->name ?? '-' }}</td>
		                        <td>{{ $course->course_module }}</td>
		                        <td>
		                            <small>
		                                <strong>{{translate('Total')}}:</strong> {{ $course->schedules->count() }}<br>
		                                <strong>{{translate('Available')}}:</strong> {{ $course->availableSchedules->count() }}
		                            </small>
		                        </td>
		                        <td>
		                            @if($course->availableSchedules->count() > 0)
		                                {{ single_price($course->availableSchedules->min('price')) }} - {{ single_price($course->availableSchedules->max('price')) }}
		                            @else
		                                -
		                            @endif
		                        </td>
		                        <td class="text-right">
									<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('courses.edit', $course->id)}}" title="{{ translate('Edit') }}">
										<i class="las la-edit"></i>
									</a>
									<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('courses.destroy', $course->id)}}" title="{{ translate('Delete') }}">
										<i class="las la-trash"></i>
									</a>
		                        </td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
		        <div class="aiz-pagination">
                	{{ $courses->appends(request()->input())->links() }}
            	</div>
		    </div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0 h6">{{ translate('Add New Course') }}</h5>
			</div>
			<div class="card-body">
				<form action="{{ route('courses.store') }}" method="POST">
					@csrf
					<div class="form-group mb-3">
						<label for="institute_id">{{translate('Institute')}} <span class="text-danger">*</span></label>
						<select class="form-control aiz-selectpicker" name="institute_id" id="institute_id" data-live-search="true" required>
							<option value="">{{ translate('Select Institute') }}</option>
							@foreach(\App\Models\Institute::orderBy('name', 'asc')->get() as $institute)
								<option value="{{ $institute->id }}">{{ $institute->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group mb-3">
						<label for="date">{{translate('Date')}} <span class="text-danger">*</span></label>
						<input type="date" name="date" class="form-control" required>
					</div>
					<div class="form-group mb-3">
						<label for="start_time">{{translate('Start Time')}} <span class="text-danger">*</span></label>
						<input type="time" name="start_time" class="form-control" required>
					</div>
					<div class="form-group mb-3">
						<label for="end_time">{{translate('End Time')}} <span class="text-danger">*</span></label>
						<input type="time" name="end_time" class="form-control" required>
					</div>
					<div class="form-group mb-3">
						<label for="course_module">{{translate('Course Module')}} <span class="text-danger">*</span></label>
						<input type="text" placeholder="{{translate('Course Module')}}" name="course_module" class="form-control" required>
					</div>
					<div class="form-group mb-3">
						<label for="course_level">{{translate('Course Level')}} <span class="text-danger">*</span></label>
						<input type="text" placeholder="{{translate('Course Level')}}" name="course_level" class="form-control" required>
					</div>
					<div class="form-group mb-3">
						<label for="price">{{translate('Price')}} <span class="text-danger">*</span></label>
						<input type="number" step="0.01" min="0" placeholder="{{translate('Price')}}" name="price" class="form-control" required>
					</div>
					<div class="form-group mb-3 text-right">
						<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function sort_courses(el){
        $('#sort_courses').submit();
    }
</script>
@endsection

