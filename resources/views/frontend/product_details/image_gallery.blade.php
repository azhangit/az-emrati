<style>
    .slick-slide {
        opacity: 1 !important;
    }
    
    #main-image{
        width: 300px !important;
        height: 300px !important;
    }
</style>

<script>
    function handleImageError(img) {
        img.onerror = null;
        img.src = "{{ static_asset('assets/img/placeholder.jpg') }}";
    }

    $(document).ready(function () {
        // Thumbnail click event to change main image
        $('.product-gallery-thumb .carousel-box img').on('click', function () {
            let newSrc = $(this).attr('data-src'); // Get the full-size image from data-src

            if (newSrc) {
                // Update the main image manually
                $('.product-gallery .carousel-box:first-child img').attr('src', newSrc);
                $('.product-gallery .carousel-box:first-child img').attr('data-src', newSrc);
            }
        });
    });
</script>

@php
    $photos = $detailedProduct->photos ? explode(',', $detailedProduct->photos) : [];
    $isPhysicalProduct = ($detailedProduct->digital == 0);
@endphp

<div class="sticky-top z-3 row gutters-10">
    <!-- Main Gallery Images -->
    <div class="col-lg-11">
        <div class="product-gallery">
            <div class="carousel-box img-zoom rounded-0">
                @php
                    $firstImage = count($photos) > 0 ? $photos[0] : static_asset('assets/img/placeholder.jpg');
                @endphp
                <img id="main-image" class="img-fluid h-auto lazyload mx-auto"
                    src="{{ uploaded_asset($firstImage) }}"
                    data-src="{{ uploaded_asset($firstImage) }}"
                    loading="lazy"
                    onerror="handleImageError(this)">
            </div>
        </div>
    </div>

    <!-- Thumbnail Images -->
    <div class="col-12 mt-3">
        <div class="product-gallery-thumb d-flex">
            @if ($isPhysicalProduct)
                @foreach ($detailedProduct->stocks as $stock)
                    @if ($stock->image)
                        <div class="carousel-box c-pointer rounded-0">
                            <img class="lazyload mw-100 size-60px mx-auto border p-1"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($stock->image) }}"
                                loading="lazy"
                                onerror="handleImageError(this)">
                        </div>
                    @endif
                @endforeach
            @endif

            @foreach ($photos as $photo)
                <div class="carousel-box c-pointer rounded-0">
                    <img class="lazyload mw-100 size-60px mx-auto border p-1"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($photo) }}"
                        loading="lazy"
                        onerror="handleImageError(this)">
                </div>
            @endforeach
        </div>
    </div>
</div>
