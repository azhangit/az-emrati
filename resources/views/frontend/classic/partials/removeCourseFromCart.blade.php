<div class="modal-body px-4 py-5 c-scrollbar-light">
    <div class="text-center">
        <i class="las la-exclamation-triangle la-3x text-warning mb-3"></i>
        <h3 class="fs-18 fw-600 mb-3">{{ translate('Cannot Add Product') }}</h3>
        <p class="fs-14 mb-4">
            {{ translate('Your cart contains courses. Please complete your course purchase first or remove courses from cart before adding physical products.') }}
        </p>
        <a href="{{ route('cart') }}" class="btn btn-primary rounded-0">
            {{ translate('Go to Cart') }}
        </a>
    </div>
</div>

