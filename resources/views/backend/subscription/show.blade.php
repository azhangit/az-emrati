@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{ translate('Subscription Details') }}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('admin.subscription.index') }}" class="btn btn-primary">{{ translate('Back to List') }}</a>
        </div>
    </div>
</div>
<br>
<div class="card">
    <div class="card-body">


<!--<div class="row">-->
<!--<div class="col-6">-->
<!--    <h2>User Details</h2>-->
<!--    <strong class="fs-18">User ID :</strong><span class="fs-16"> {{ optional($subscription->user)->id ?? '-' }}</span><br>-->
<!--    <strong class="fs-18">User Name :</strong><span class="fs-16"> {{ optional($subscription->user)->name ?? '-' }}</span><br>-->
<!--    <strong class="fs-18">User Email :</strong><span class="fs-16"> {{ optional($subscription->user)->email ?? '-' }}</span><br>-->
<!--    <strong class="fs-18">Address :</strong><span class="fs-16"> {{ $address ? $address->address ?? '-' : '-' }}</span><br>-->
<!--    <strong class="fs-18">Country Name :</strong><span class="fs-16"> {{ $address ? optional($address->country)->name ?? '-' : '-' }}</span><br>-->
<!--    <strong class="fs-18">State Name :</strong><span class="fs-16"> {{ $address ? optional($address->state)->name ?? '-' : '-' }}</span><br>-->
<!--    <strong class="fs-18">City Name :</strong><span class="fs-16"> {{ $address ? optional($address->city)->name ?? '-' : '-' }}</span><br>-->
<!--    <strong class="fs-18">Postal Code Email :</strong><span class="fs-16"> {{ $address ? $address->postal_code ?? '-' : '-' }}</span><br>-->
<!--    <strong class="fs-18">Phone Number :</strong><span class="fs-16"> {{ $address ? $address->phone ?? '-' : '-' }}</span><br>-->
    
<!--</div>-->
<!--<div class="col-6">-->
<!--     <h2>subscription Details</h2>-->
<!--    <strong class="fs-18">Product Name :</strong><span class="fs-16"> {{ optional($subscription->product)->name ?? '-' }}}</span><br>-->
<!--   <strong class="fs-18">Product Image:</strong>-->
<!--<span class="fs-16">-->
<!--    @if(optional($subscription->product)->thumbnail_img)-->
<!--        <img src="{{ uploaded_asset($subscription->product->thumbnail_img) }}" alt="{{ optional($subscription->product)->name }}" width="80">-->
<!--    @else-->
<!--        <span>No image</span>-->
<!--    @endif-->
<!--</span>-->
<!--<br>-->
<!--    <strong class="fs-18">User Email :</strong><span class="fs-16"> {{ optional($subscription->user)->email ?? '-' }}</span><br>-->
<!--    <strong class="fs-18">Address :</strong><span class="fs-16"> {{ $address ? $address->address ?? '-' : '-' }}</span><br>-->
<!--    <strong class="fs-18">Country Name :</strong><span class="fs-16"> {{ $address ? optional($address->country)->name ?? '-' : '-' }}</span><br>-->
<!--    <strong class="fs-18">State Name :</strong><span class="fs-16"> {{ $address ? optional($address->state)->name ?? '-' : '-' }}</span><br>-->
<!--    <strong class="fs-18">City Name :</strong><span class="fs-16"> {{ $address ? optional($address->city)->name ?? '-' : '-' }}</span><br>-->
<!--    <strong class="fs-18">Postal Code Email :</strong><span class="fs-16"> {{ $address ? $address->postal_code ?? '-' : '-' }}</span><br>-->
<!--    <strong class="fs-18">Phone Number :</strong><span class="fs-16"> {{ $address ? $address->phone ?? '-' : '-' }}</span><br>-->
<!--</div>-->
<!--<div class="col-6">-->
    
<!--</div>-->
<!--<div class="col-6">-->
    
<!--</div>-->
<!--</div>-->

        <table class="table table-bordered">
            <tbody>
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
                    <th>{{ translate('Price') }}</th>
                    <td>{{ $subscription->price ?? '-' }} AED</td>
                </tr>
                <tr>
                    <th>{{ translate('Status') }}</th>
                    <td>
                        @if($subscription->status == 'active')
                            <span class="badge badge-success w-25">{{ ucfirst($subscription->status) }}</span>
                        @elseif($subscription->status == 'inactive')
                            <span class="badge badge-danger w-25">{{ ucfirst($subscription->status) }}</span>
                        @else
                            <span class="badge badge-secondary w-25">{{ ucfirst($subscription->status) }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
