@section('content')
    <section class="mb-4 pt-3">
        <div class="container">
            <div class="row">
           
              
                    <!-- Product Image Gallery -->
                    <div class="col-12 col-lg-5 p-0">
                        @include('frontend.product_details.image_gallery')
                    </div>

                    <!-- Product Details -->
                    <div class="col-12 col-lg-7 p-0">
                        @include('frontend.product_details.details')
                    </div>
                
        </div>
    </div>
    </section>

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
        });

       
        function showImage(photo) {
            $('#image_modal img').attr('src', photo);
            $('#image_modal img').attr('data-src', photo);
            $('#image_modal').modal('show');
        }

        
    </script>
@endsection
