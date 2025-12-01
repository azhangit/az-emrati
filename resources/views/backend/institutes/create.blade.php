@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<h1 class="h3">{{translate('Add New Institute')}}</h1>
	</div>
</div>

<div class="row">
	<div class="col-lg-10 mx-auto">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0 h6">{{ translate('Institute Information') }}</h5>
			</div>
			<div class="card-body">
				<form action="{{ route('institutes.store') }}" method="POST">
					@csrf
					<div class="form-group mb-3">
						<label for="name">{{translate('Name')}} <span class="text-danger">*</span></label>
						<input type="text" placeholder="{{translate('Name')}}" name="name" class="form-control" required>
					</div>
					<div class="form-group mb-3">
						<label for="image">{{translate('Image')}} <span class="text-danger">*</span></label>
						<div class="input-group" data-toggle="aizuploader" data-type="image">
							<div class="input-group-prepend">
									<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
							</div>
							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
							<input type="hidden" name="image" class="selected-files" required>
						</div>
						<div class="file-preview box sm">
						</div>
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

