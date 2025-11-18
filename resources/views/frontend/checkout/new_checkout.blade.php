@extends('frontend.layouts.app')

@section('content')


<!-- Include Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<style>
  /* Optional custom styles */
  .form-section {
    border: 1px solid #ddd;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 5px;
  }
  .section-title {
    font-weight: 600;
    margin-bottom: 1rem;
  }
  /* Hide the billing address form initially */
  #billing-address-form {
    display: none;
  }
  .coupon-section {
    margin-bottom: 20px;
  }
  .coupon-section input {
    width: 50%;
    padding: 8px;
    margin-right: 5px;
  }
</style>

<div class="container my-5">
  <!-- The checkout form -->
  <form id="payment-form" action="{{ route('newcheckout.submit') }}" method="POST">
    @csrf
    <!-- Hidden field for payment method -->
    <input type="hidden" name="payment_method" value="creditCard">
    <div class="row">
      <!-- Left column: Form Fields -->
      <div class="col-12 col-lg-8">
        <!-- Delivery Section -->
        <div class="form-section">
          <h4 class="section-title">Delivery</h4>
          <!-- radio buttons -->
  <div class="mb-3">
    <label class="form-check-label me-3">
      <input type="radio" name="delivery_type" value="ship" checked> Ship
    </label>
    <label class="form-check-label">
      <input type="radio" name="delivery_type" value="pickup"> Pickup in store
    </label>
  </div>
   <!-- Shipping address fields -->
  <div id="shipping-fields">
          <!-- Delivery Address Fields -->
          <div class="row g-3">
            <div class="col-md-6">
              <label for="delivery_country" class="form-label">Country/Region</label>
              <select class="form-select" name="delivery_country" id="delivery_country">
                <option value="United Arab Emirates" selected>United Arab Emirates</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Oman">Oman</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="delivery_phone" class="form-label">Phone</label>
              <input type="tel" name="delivery_phone" class="form-control" id="delivery_phone" placeholder="Phone number">
            </div>
            <div class="col-md-6">
              <label for="delivery_first_name" class="form-label">First name</label>
              <input type="text" name="delivery_first_name" class="form-control" id="delivery_first_name" placeholder="First name">
            </div>
            <div class="col-md-6">
              <label for="delivery_last_name" class="form-label">Last name</label>
              <input type="text" name="delivery_last_name" class="form-control" id="delivery_last_name" placeholder="Last name">
            </div>
            <div class="col-12">
              <label for="delivery_address" class="form-label">Address</label>
              <input type="text" name="delivery_address" class="form-control" id="delivery_address" placeholder="Street address">
            </div>
            <div class="col-12">
              <label for="delivery_apartment" class="form-label">Apartment, suite, etc.</label>
              <input type="text" name="delivery_apartment" class="form-control" id="delivery_apartment" placeholder="Apartment, suite, etc.">
            </div>
            <div class="col-md-6">
              <label for="delivery_city" class="form-label">City</label>
              <input type="text" name="delivery_city" class="form-control" id="delivery_city" placeholder="City">
            </div>
          </div>
        </div>
          </div>
        
<!-- Pickup point selector -->
  <div id="pickup-fields" style="display:none;">
    <label for="pickup_point_id" class="form-label">Choose pickup location</label>
    <select name="pickup_point_id" id="pickup_point_id" class="form-select mb-4">
      <option value=""> Select a store </option>
      @foreach($pickup_points as $pp)
        <option value="{{ $pp->id }}">
          {{ $pp->name }} — {{ $pp->address }}
        </option>
      @endforeach
    </select>
  </div>
        <!-- Payment Section -->
        <div class="form-section">
          <h4 class="section-title">Payment</h4>
          <!-- Stripe Card Element -->
          <div id="card-element"><!-- Stripe Element will render here --></div>
          <!-- Used to display form errors -->
          <div id="card-errors" role="alert" style="color:red;"></div>
          <!-- Hidden input to store the Stripe token -->
          <input type="hidden" name="stripe_token" id="stripe_token">
        </div>

      

        <!-- Billing Address Section -->
        <div class="form-section">
          <h4 class="section-title">Billing Address</h4>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="differentBilling">
            <label class="form-check-label" for="differentBilling">
              Use a different billing address
            </label>
          </div>
          <!-- Billing address form (hidden initially) -->
          <div id="billing-address-form">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="billing_country" class="form-label">Country/Region</label>
                <select class="form-select" name="billing_country" id="billing_country">
                  <option value="United Arab Emirates" selected>United Arab Emirates</option>
                  <option value="Saudi Arabia">Saudi Arabia</option>
                  <option value="Kuwait">Kuwait</option>
                  <option value="Bahrain">Bahrain</option>
                  <option value="Oman">Oman</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="billing_phone" class="form-label">Phone</label>
                <input type="tel" name="billing_phone" class="form-control" id="billing_phone" placeholder="Phone number">
              </div>
              <div class="col-md-6">
                <label for="billing_first_name" class="form-label">First name</label>
                <input type="text" name="billing_first_name" class="form-control" id="billing_first_name" placeholder="First name">
              </div>
              <div class="col-md-6">
                <label for="billing_last_name" class="form-label">Last name</label>
                <input type="text" name="billing_last_name" class="form-control" id="billing_last_name" placeholder="Last name">
              </div>
              <div class="col-12">
                <label for="billing_address" class="form-label">Address</label>
                <input type="text" name="billing_address" class="form-control" id="billing_address" placeholder="Street address">
              </div>
              <div class="col-12">
                <label for="billing_apartment" class="form-label">Apartment, suite, etc.</label>
                <input type="text" name="billing_apartment" class="form-control" id="billing_apartment" placeholder="Apartment, suite, etc.">
              </div>
              <div class="col-md-6">
                <label for="billing_city" class="form-label">City</label>
                <input type="text" name="billing_city" class="form-control" id="billing_city" placeholder="City">
              </div>
            </div>
          </div>
        </div>
        
        <!-- Complete Order Button -->
        <button type="submit" class="btn btn-primary w-100">Complete order</button>
      </div>

      <!-- Right column: Order Summary with Coupon Section -->
     <div class="col-12 col-lg-4">
  @include('frontend.checkout.partials.order_summary', compact(
    'cartItems','subtotal','shipping','shipping_info'
  ))
</div>
    </div>
  </form>
</div>

<!-- jQuery (for toggling billing address, coupon apply/remove, and AJAX submission) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    // Toggle Billing Address Form
    $('#differentBilling').on('change', function() {
      $('#billing-address-form').slideToggle(this.checked);
    });

    // Initialize Stripe
    var stripe = Stripe("{{ env('STRIPE_KEY') }}");
    var elements = stripe.elements();
    var style = {
      base: {
        color: "#32325d",
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: "antialiased",
        fontSize: "16px",
        "::placeholder": {
          color: "#aab7c4"
        }
      },
      invalid: {
        color: "#fa755a",
        iconColor: "#fa755a"
      }
    };
    var card = elements.create("card", { style: style });
    card.mount("#card-element");

    // Real-time validation errors
    card.on("change", function(event) {
      var displayError = document.getElementById("card-errors");
      displayError.textContent = event.error ? event.error.message : "";
    });

    // AJAX-based payment form submission
    $('#payment-form').on('submit', function(event) {
      event.preventDefault();
      var form = $(this);
      stripe.createToken(card).then(function(result) {
        if (result.error) {
          $('#card-errors').text(result.error.message);
        } else {
          $('#stripe_token').val(result.token.id);
          $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
              if (response.redirect_url) {
                window.location.href = response.redirect_url;
              } else {
                alert('Order placed successfully!');
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.log(jqXHR.responseText);
              alert('Payment failed: ' + errorThrown);
            }
          });
        }
      });
    });
    $('input[name="delivery_type"]').on('change', function(){
  var mode = $(this).val();
  // store into a hidden input (in case you need it server-side)
  $('#delivery_type_input').val(mode);

  if(mode === 'pickup'){
    $('#shipping-fields').hide();
    $('#pickup-fields').slideDown();
    // you may also want to recalc shipping via AJAX:
    $.post(
      '{{ route("newcheckout.calculate_shipping") }}',
      { _token:'{{ csrf_token() }}', delivery_type: 'pickup' },
      function(res){
        $('#order_summary').replaceWith(res.html);
      }
    );
  }
  else {
    $('#pickup-fields').hide();
    $('#shipping-fields').slideDown();
    $.post(
      '{{ route("newcheckout.calculate_shipping") }}',
      { _token:'{{ csrf_token() }}', delivery_type: 'ship' },
      function(res){
        $('#order_summary').replaceWith(res.html);
      }
    );
  }
});

    // Apply Coupon
    $('#apply_coupon').on('click', function(e) {
      e.preventDefault();
      var code = $('#coupon_code').val().trim();
      if (!code) return alert('Please enter a coupon code.');
      $.post(
        '{{ route("newcheckout.apply_coupon") }}',
        { _token: '{{ csrf_token() }}', code: code },
        function(res) {
          $('#order_summary').replaceWith(res.html);
        }
      ).fail(function(xhr) {
        alert(xhr.responseJSON.error || 'Coupon apply failed');
      });
    });

    // Remove Coupon
    $('#remove_coupon').on('click', function(e) {
      e.preventDefault();
      $.post(
        '{{ route("newcheckout.remove_coupon") }}',
        { _token: '{{ csrf_token() }}' },
        function(res) {
          $('#order_summary').replaceWith(res.html);
        }
      );
    });
  });
</script>

@endsection
