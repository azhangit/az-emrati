@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{ translate('Subscription Details') }}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('admin.subscription.index') }}" class="btn btn-primary">{{ translate('Back to List') }}</a>
            @if(($subscription->status ?? '') === 'pending_delivery')
                <form action="{{ route('admin.subscription.mark_delivered', $subscription->id) }}" method="POST" class="d-inline ml-2">
                    @csrf
                    <button type="submit" class="btn btn-success">{{ translate('Mark Delivered') }}</button>
                </form>
            @endif
        </div>
    </div>
</div>
<br>
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

<div class="row gutters-10 mb-3">
    <div class="col-md-3">
        <div class="card shadow-none border">
            <div class="card-body py-3">
                <div class="fs-12 text-muted">{{ translate('Planned') }}</div>
                <div class="h4 mb-0">{{ $scheduleTableExists ? $plannedDeliveries : '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-none border">
            <div class="card-body py-3">
                <div class="fs-12 text-muted">{{ translate('Delivered') }}</div>
                <div class="h4 mb-0 text-success">{{ $scheduleTableExists ? $deliveredCount : '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-none border">
            <div class="card-body py-3">
                <div class="fs-12 text-muted">{{ translate('Remaining') }}</div>
                <div class="h4 mb-0">{{ $scheduleTableExists ? $remainingCount : '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-none border">
            <div class="card-body py-3">
                <div class="fs-12 text-muted">{{ translate('Progress') }}</div>
                <div class="h4 mb-0">{{ $progressPercent }}%</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="progress mb-3" style="height: 8px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPercent }}%;"></div>
        </div>
        <table class="table table-bordered mb-0">
            <tbody>
                <tr class="table-light">
                    <th colspan="2">{{ translate('Customer Info') }}</th>
                </tr>
                <tr>
                    <th>{{ translate('User Name') }}</th>
                    <td>{{ optional($subscription->user)->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('User id') }}</th>
                    <td>{{ optional($subscription->user)->id ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('User Email') }}</th>
                    <td>{{ optional($subscription->user)->email ?? '-' }}</td>
                </tr>
            <tr>
    <th>Address</th>
    <td>{{ $address ? $address->address ?? '-' : '-' }}</td>
</tr>
<tr>
    <th>Country Name</th>
    <td>{{ $address ? optional($address->country)->name ?? '-' : '-' }}</td>
</tr>
<tr>
    <th>State Name</th>
    <td>{{ $address ? optional($address->state)->name ?? '-' : '-' }}</td>
</tr>
<tr>
    <th>City Name</th>
    <td>{{ $address ? optional($address->city)->name ?? '-' : '-' }}</td>
</tr>
<tr>
    <th>Postal Code</th>
    <td>{{ $address ? $address->postal_code ?? '-' : '-' }}</td>
</tr>
<tr>
    <th>Phone</th>
    <td>{{ $address ? $address->phone ?? '-' : '-' }}</td>
</tr>
                <tr class="table-light">
                    <th colspan="2">{{ translate('Subscription Info') }}</th>
                </tr>
                <tr>
                    <th>{{ translate('Product Name') }}</th>
                  <td>{{ optional($subscription->product)->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Size') }}</th>
                    <td>{{ $subscription->weight ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Grind Size') }}</th>
                    <td>{{ $subscription->grind_size ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Week') }}</th>
                    <td>{{ $subscription->week ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Month') }}</th>
                    <td>{{ $subscription->month ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Frequency') }}</th>
                    <td><span class="badge badge-light">{{ $scheduleTableExists ? $frequencyLabel : '-' }}</span></td>
                </tr>
                <tr>
                    <th>{{ translate('Total Planned Deliveries') }}</th>
                    <td>{{ $scheduleTableExists ? $plannedDeliveries : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Delivered Count') }}</th>
                    <td>{{ $scheduleTableExists ? $deliveredCount : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Remaining Deliveries') }}</th>
                    <td>{{ $scheduleTableExists ? $remainingCount : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Next Delivery Date') }}</th>
                    <td>
                        @if($scheduleTableExists && $schedule && !$isCompleted && $schedule->next_send_date)
                            {{ \Carbon\Carbon::parse($schedule->next_send_date)->format('d M Y, h:i A') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ translate('Started On') }}</th>
                    <td>{{ $subscription->created_at ? \Carbon\Carbon::parse($subscription->created_at)->format('d M Y, h:i A') : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Price') }}</th>
                    <td>{{ $subscription->price ?? '-' }} AED</td>
                </tr>
                <tr>
                    <th>{{ translate('Status') }}</th>
                    <td>
                        @if($displayStatus == 'completed')
                            <span class="badge badge-info w-25">Completed</span>
                        @elseif($displayStatus == 'pending_delivery')
                            <span class="badge badge-warning w-25">Pending Delivery</span>
                        @elseif($displayStatus == 'active')
                            <span class="badge badge-success w-25">{{ ucfirst($displayStatus) }}</span>
                        @elseif($displayStatus == 'inactive')
                            <span class="badge badge-danger w-25">{{ ucfirst($displayStatus) }}</span>
                        @else
                            <span class="badge badge-secondary w-25">{{ ucfirst($displayStatus) }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
