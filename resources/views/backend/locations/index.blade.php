@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{ translate('Locations') }}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('locations.create') }}" class="btn btn-circle btn-info">
                <span>{{ translate('Add Location') }}</span>
            </a>
        </div>
    </div>
</div>
<br>

<div class="card">
    <div class="card-body">
        @if(session('success')) 
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Name') }}</th>
                    <th width="140" class="text-right">{{ translate('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    <tr>
                        <td>{{ $location->id }}</td>
                        <td>{{ $location->name }}</td>
                        <td class="text-right">
                            <a href="{{ route('locations.edit', $location) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-id="{{ $location->id }}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                            <form id="delete-form-{{ $location->id }}" action="{{ route('locations.destroy', $location) }}" method="POST" style="display: none;">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">{{ translate('No locations found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
       
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert for Delete
    $(document).on('click', '.confirm-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: '{{ translate('Are you sure?') }}',
            text: '{{ translate('This location will be deleted permanently!') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: '{{ translate('Delete') }}',
            cancelButtonText: '{{ translate('Cancel') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-form-' + id).submit();
            }
        });
    });
</script>
@endsection
