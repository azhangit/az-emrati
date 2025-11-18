@extends('frontend.layouts.app')

@section('content')
<div class="container my-5">
    <h1>Order Confirmation</h1>
    <p>Thank you for your order! Your order ID is {{ $order->id }}.</p>
    <!-- Add more details as needed -->
</div>
@endsection
