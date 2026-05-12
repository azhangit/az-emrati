@extends('backend.layouts.app')

@section('content')

@php
    $totalSubscriptions = $subscriptions->total();
    $activeCount = 0;
    $pendingCount = 0;
    $completedCount = 0;
    foreach ($subscriptions as $item) {
        $s = $scheduleTableExists ? optional($item->schedule) : null;
        $planned = $s ? (int)($s->total_weeks ?? 0) : 0;
        $done = $s ? (int)($s->sent_count ?? 0) : 0;
        $remaining = max($planned - $done, 0);
        $isDone = $scheduleTableExists && $s && ($remaining === 0 || (isset($s->active) && !$s->active));
        $status = $isDone ? 'completed' : ($item->status ?? 'active');
        if ($status === 'active') $activeCount++;
        if ($status === 'pending_delivery') $pendingCount++;
        if ($status === 'completed') $completedCount++;
    }
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{ translate('All Product Subscriptions') }}</h1>
        </div>
    </div>
</div>
<br>

<div class="row gutters-10 mb-3">
    <div class="col-md-3">
        <div class="card shadow-none border">
            <div class="card-body py-3">
                <div class="fs-12 text-muted">{{ translate('Total') }}</div>
                <div class="h4 mb-0">{{ $totalSubscriptions }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-none border">
            <div class="card-body py-3">
                <div class="fs-12 text-muted">{{ translate('Active') }}</div>
                <div class="h4 mb-0 text-success">{{ $activeCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-none border">
            <div class="card-body py-3">
                <div class="fs-12 text-muted">{{ translate('Pending Delivery') }}</div>
                <div class="h4 mb-0 text-warning">{{ $pendingCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-none border">
            <div class="card-body py-3">
                <div class="fs-12 text-muted">{{ translate('Completed') }}</div>
                <div class="h4 mb-0 text-info">{{ $completedCount }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('All Subscriptions') }}</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table aiz-table mb-0 table-bordered">
            <thead>
                <tr>
                    <th>{{ translate('User Details') }}</th>
                    <th>{{ translate('Product Details') }}</th>
                    <th>{{ translate('Plan (Month/Week)') }}</th>
                    <th>{{ translate('Planned Deliveries') }}</th>
                    <th>{{ translate('Delivered') }}</th>
                    <th>{{ translate('Remaining') }}</th>
                    <th>{{ translate('Next Delivery') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th class="text-right">{{ translate('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $key => $subscription)
@php
    $schedule = $scheduleTableExists ? optional($subscription->schedule) : null;
    $plannedDeliveries = $schedule ? (int)($schedule->total_weeks ?? 0) : 0;
    $deliveredCount = $schedule ? (int)($schedule->sent_count ?? 0) : 0;
    $remainingCount = max($plannedDeliveries - $deliveredCount, 0);
    $isCompleted = $scheduleTableExists && $schedule && ($remainingCount === 0 || (isset($schedule->active) && !$schedule->active));
    $displayStatus = $isCompleted ? 'completed' : ($subscription->status ?? 'active');
    $freqWeeks = $schedule ? (int)($schedule->frequency_weeks ?? 0) : 0;
    $frequencyLabel = '-';
    if ($freqWeeks === 1) {
        $frequencyLabel = 'Every Week';
    } elseif ($freqWeeks === 2) {
        $frequencyLabel = 'Every 2 Weeks';
    } elseif ($freqWeeks === 4) {
        $frequencyLabel = 'Every 4 Weeks';
    }
    $progressPercent = $plannedDeliveries > 0 ? min(100, (int) round(($deliveredCount / $plannedDeliveries) * 100)) : 0;
@endphp
<tr>
    <td>
        <div class="fw-600">{{ optional($subscription->user)->name ?? '-' }}</div>
        <small class="text-muted d-block">{{ optional($subscription->user)->email ?? '-' }}</small>
    </td>
    <td>
        <div class="fw-600" title="{{ optional($subscription->product)->name }}">
            {{ \Illuminate\Support\Str::limit(optional($subscription->product)->name, 28, '...') ?? '-' }}
        </div>
        <small class="text-muted d-block">{{ translate('Grind') }}: {{ $subscription->grind_size ?? '-' }}</small>
        <small class="text-muted d-block">{{ translate('Weight') }}: {{ $subscription->weight ?? '-' }}</small>
    </td>
    <td>
        <div><span class="badge badge-light">{{ $subscription->month ?? '-' }}</span></div>
        <small class="text-muted d-block mt-1">{{ $frequencyLabel }}</small>
    </td>
    <td><strong>{{ $scheduleTableExists ? $plannedDeliveries : '-' }}</strong></td>
    <td>
        @if($scheduleTableExists)
            <strong>{{ $deliveredCount }}</strong>
            <div class="progress mt-1" style="height: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPercent }}%;"></div>
            </div>
        @else
            -
        @endif
    </td>
    <td><strong class="{{ ($scheduleTableExists && $remainingCount == 0) ? 'text-success' : 'text-dark' }}">{{ $scheduleTableExists ? $remainingCount : '-' }}</strong></td>
    <td>
        @if($scheduleTableExists && $schedule && !$isCompleted && $schedule->next_send_date)
            {{ \Carbon\Carbon::parse($schedule->next_send_date)->format('d M Y, h:i A') }}
        @else
            -
        @endif
    </td>
    <td>
        @if($displayStatus == 'completed')
            <span class="badge badge-info w-100" title="Completed">
                Completed
            </span>
        @elseif($displayStatus == 'pending_delivery')
            <span class="badge badge-warning w-100" title="Pending Delivery">
                Pending Delivery
            </span>
        @elseif($displayStatus == 'active')
            <span class="badge badge-success w-100" title="{{ ucfirst($displayStatus) }}">
                {{ \Illuminate\Support\Str::limit(ucfirst($displayStatus), 15, '...') }}
            </span>
        @elseif($displayStatus == 'inactive')
            <span class="badge badge-danger w-100" title="{{ ucfirst($displayStatus) }}">
                {{ \Illuminate\Support\Str::limit(ucfirst($displayStatus), 15, '...') }}
            </span>
        @else
            <span class="badge badge-secondary w-100" title="{{ ucfirst($displayStatus) }}">
                {{ \Illuminate\Support\Str::limit(ucfirst($displayStatus), 15, '...') }}
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
        </div>
        <div class="aiz-pagination">
            {{ $subscriptions->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection
