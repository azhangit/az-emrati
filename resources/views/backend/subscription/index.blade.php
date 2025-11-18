@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{ translate('All Product Subscriptions') }}</h1>
        </div>
    </div>
</div>
<br>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('All Subscriptions') }}</h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('User Name') }}</th>
                    <th>{{ translate('User Email') }}</th>
                    <th>{{ translate('Product Name') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th class="text-right">{{ translate('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $key => $subscription)
<tr>
    <td>{{ $subscription->id }}</td>
    <td>
        <span title="{{ optional($subscription->user)->name }}">
            {{ \Illuminate\Support\Str::limit(optional($subscription->user)->name, 15, '...') ?? '-' }}
        </span>
    </td>
    <td>
        <span title="{{ optional($subscription->user)->email }}">
            {{ \Illuminate\Support\Str::limit(optional($subscription->user)->email, 15, '...') ?? '-' }}
        </span>
    </td>
    <td>
        <span title="{{ optional($subscription->product)->name }}">
            {{ \Illuminate\Support\Str::limit(optional($subscription->product)->name, 15, '...') ?? '-' }}
        </span>
    </td>
    <td>
        @if($subscription->status == 'active')
            <span class="badge badge-success w-100" title="{{ ucfirst($subscription->status) }}">
                {{ \Illuminate\Support\Str::limit(ucfirst($subscription->status), 15, '...') }}
            </span>
        @elseif($subscription->status == 'inactive')
            <span class="badge badge-danger w-100" title="{{ ucfirst($subscription->status) }}">
                {{ \Illuminate\Support\Str::limit(ucfirst($subscription->status), 15, '...') }}
            </span>
        @else
            <span class="badge badge-secondary w-100" title="{{ ucfirst($subscription->status) }}">
                {{ \Illuminate\Support\Str::limit(ucfirst($subscription->status), 15, '...') }}
            </span>
        @endif
    </td>
    <td class="text-right">
        <a class="btn btn-soft-success btn-icon btn-circle btn-sm" 
            href="{{ route('admin.subscription.show', $subscription->id) }}" 
            title="{{ translate('View') }}">
            <i class="las la-eye"></i>
        </a>
    </td>
</tr>
@endforeach

            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $subscriptions->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection
