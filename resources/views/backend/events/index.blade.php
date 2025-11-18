@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('All Events')}}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('events.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add Event')}}</span>
            </a>
        </div>
    </div>
</div>
<br>

<div class="card">
    <form id="sort_events" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Events') }}</h5>
            </div>
            <div class="col-md-2 ml-auto">
              
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Trainer') }}</th>
                        <th>{{ translate('Location') }}</th>
                        <th>{{ translate('Date') }}</th>
                        <th>{{ translate('Time') }}</th>
                        <th class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($events as $key => $event)
                    <tr>
                        <td>{{ ($key+1) + ($events->currentPage() - 1)*$events->perPage() }}</td>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->trainer }}</td>
                       <td>{{ ($event->location)->name ?? '-' }}</td>
                        <td>{{ $event->date }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</td>
                        <td class="text-right">
                            <a href="{{ route('events.edit', $event->id) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-id="{{ $event->id }}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                            <form id="delete-form-{{ $event->id }}" action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">{{ translate('No events found.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $events->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert Delete
    
 
</script>
@endsection
