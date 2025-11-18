<div class="border p-3 mb-3" id="order_summary">
 
  <h5>Order Summary</h5>

 
  @if($cartItems->isNotEmpty())
    @php $running = 0; @endphp
    @foreach($cartItems as $c)
      @php
        $unitPrice = optional($c->product)->unit_price ?? 0;
        $line      = $unitPrice * $c->quantity;
        $running  += $line;
      @endphp
      <div class="d-flex mb-2">
          <img   src="{{ asset('/public/' . ltrim($c->product->thumbnail->file_name, '/')) }}" 
             width="50" class="me-2" style="object-fit:cover;"
             alt="{{ $c->product->name }}">
        <div>
          <p>{{ $c->product->name }} x {{ $c->quantity }}</p>
          <p>Price: AED {{ number_format($line,2) }}</p>
        </div>
      </div>
    @endforeach

    @php
      $discount       = session('coupon_discount', 0);
      $subtotalBefore = $running;
      $subtotal       = max(0, $running - $discount);
      $vat            = $subtotal * 0.05;
      $total          = $subtotal + $shipping + $vat;
    @endphp

    <hr>
    <p><strong>Product price:</strong> {{ number_format($subtotalBefore,2) }} AED </p>
    @if($discount > 0)
      <p><strong>Discount:</strong> – {{ number_format($discount,2) }} AED</p>
      <p><strong>Subtotal after discount:</strong>  {{ number_format($subtotal,2) }} AED</p>
    @endif
    <p><strong>Shipping:</strong>  {{ number_format($shipping,2) }} AED</p>
    <p><strong>VAT (5%):</strong>  {{ number_format($vat,2) }} AED</p>
    <hr>
    <p><strong>Total:</strong>  {{ number_format($total,2) }} AED</p>
  @else
 <p>Your cart is empty.</p>
  @endif
   @php
  $dt = session('delivery_type','ship');
@endphp

@if($dt === 'pickup' && session('pickup_point_id'))
  @php
    $pp = \App\Models\PickupPoint::find(session('pickup_point_id'));
  @endphp
  @if($pp)
    <p><small>📍 Pick up at: <strong>{{ $pp->name }}</strong><br>{{ $pp->address }}</small></p>
  @endif
@endif
   <div class="coupon-section">
    <input type="text" id="coupon_code" value="{{ session('coupon_code','') }}" class="mb-2" placeholder="Enter Coupon Code">
    <button id="apply_coupon" class="btn btn-dark" style="padding:10px 25px; margin-bottom:3px;">Apply Coupon</button>
    <button id="remove_coupon" class="btn btn-secondary w-100 "
            style="{{ session('coupon_applied') ? '' : 'display:none;' }}">
      Remove Coupon
    </button>
  </div>
</div>
