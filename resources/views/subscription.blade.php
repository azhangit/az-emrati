@extends('frontend.layouts.app')
<link rel="stylesheet" href="{{ static_asset('assets/css/subscription-2.css') }}">
@section ('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS Styles -->
    <style>
    .list-style{
        list-style: none !important;
         font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;
    }
    
    
    
            .video-section {

            width: 100%;
            height: 600px;
            overflow: hidden;
            margin-bottom: 12px;
            border-radius: 40px;
        }

        /* Background Video */
        .video-section video {

            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    
  
        .subsMe2 {
            font-size: 16px;
            display: block;
            width: 80%;
            margin: 0 auto;
            text-align: center;
            padding-top: 13px;
            color: #000;
        }
        .optsList li a {
            font-size: 12px;
            float: left;
           
            text-align: left;
            padding-left: 40px;
        }
        .optsList li h3 {
            font-size: 18px;
            
            padding-top: 48px;
            padding-left: 40px;
           width:100%;
            text-align: left;
        }
        .optsList li {
           display: flex;
            width: 100%;
        }
        .optsList li img {
            float: left;
        }
        .optsList li h3 {
            font-size: 18px;
            float: left;
            padding-top: 48px;
            padding-left: 40px;
        }
        .subsMainBtn {
            border: 2px solid blue;
            color: #000;
            background: #fff;
            letter-spacing: 2px;
            margin: 2em 0 0 0;
            cursor: pointer;
            display: inline-block;
            font-size: 16px;
            line-height: 35px;
            padding: 0 50px;
            vertical-align: top;
            border-radius: 0px;
        }
        .totalBox dl {
            display: flex;
        }
        .totalBox dd em,
        .totalBox dt,
        .totalBox dd {
            display: inline-block;
            vertical-align: top;
            font-weight: normal;
            font-size: 24px;
            color: #000;
            font-style: normal;
        }
        .totalBox {
            display: table-cell;
            vertical-align: top;
            width: 90%;
            padding-left: 68px;
        }
        .totalBox dl dt {
            text-align: left;
            flex: 1;
            font-style: normal;
            font-weight: 600;
            font-size: 18px;
            text-transform: uppercase;
            line-height: 2;
        }
        .sumrry-manage {
            padding-left: 30px;
            padding-top: 114px;
            font-size: 35px;
        }
        .border-seting {
            border: 1px solid #e7dfd2;
            margin: 10px;
            /*border-bottom: 1px solid #e7dfd2;*/
        }
        .grid-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            background-color: white;
        }
        .grid-wrapper-week {
            display: grid;
            grid-template-columns: 4fr 4fr 4fr;

            background-color: #efeef0;
            gap: 20px;
        }
        .summry {
            display: grid;
            grid-template-columns: 6fr 6fr;
            margin: 3em 15em;
            background-color: white;
        }
        .grid-number {
            background-color: #fff;
            display: grid;
            justify-content: center;
            padding: 20px;
            font-size: 15px;
            color: black;
            text-align: center;
            border-radius: 12px;
        }
        .optsList li small {
            display: inline-block;
            vertical-align: top;
            line-height: 35px;
            padding: 0 50px;
            font-size: 16px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
              margin: 0 auto;
        }
        .optsList p strong {
         
            text-transform: uppercase;
            color: #000;
            font-weight: bold;
            font-size: 14px;
            margin: 25px 0;
            position: relative;
        }
        .numbertitle h1 li {
            font-style: normal;
            border: 2px solid blue;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: block;
            margin: 0 auto 25px auto;
            vertical-align: bottom;
            line-height: 55px;
            font-size: 24px;
            color: blue;
         
            list-style: none;
        }
        .bottom-line {
            border-bottom: 1px solid transparent;
        }
        .btn.product-form__cart-submit {}
        @media screen and (max-width: 915px) {
            .grid-wrapper {
                grid-template-columns: 2fr;
                margin: 3em 0em;
            }
            .grid-wrapper-week {
                grid-template-columns: 2fr;
            }
            .summry {
                grid-template-columns: 2fr;
                margin: 3em 0em;
            }
            .img-resize {
                height: auto;
                width: 280px;

            }
            .bottom-line {
                border-bottom: 1px solid #e7dfd2;
            }
        }
        @media screen and (max-width: 568px) and (min-width:320px) {
            .img-resize {
                height: auto;
                width: 280px;

            }
            
            .grid-wrapper-week img{
                max-width: 200px !important;
            }
            
            
            .heading-img img
 {
    width: 100%;
    height: 400px;
    border-radius: 16px;
    object-fit: cover;
}



            .optsList li h3 {
                width: auto;
                font-size: 18px;
                float: left;
                padding-top: 48px;
                padding-left: 10px !important;
                width: 100%;
            }
            .optsList li a {
                font-size: 12px;
                float: left;
                width: auto;
                text-align: left;
                padding-left: 7px !important;
            }
        }
        @media screen and (max-width: 1366px) and (min-width:1024px) {
            .grid-wrapper {
                margin: 3em 1em;
            }

        }
     small {
    border: 1px solid #007bff !important;
    color: #007bff;
    padding: 5px 10px; /* Thoda padding add karna behtar hoga */
    display: inline-block; /* Taake width adjust ho */
    transition: all 0.3s ease-in-out; /* Smooth transition effect */
    border-radius:10px;
}

 small:hover {
    background-color: #007bff;
    color: #fff !important;
}


    .grind-options, .product-size  {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
    .grind-option, .product-size {
padding: 14px 20px;
    border-radius: 10px;
    border: 1px solid #ccc;
    cursor: pointer;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
    max-width: 230px;
    width: 230px;
    text-align: center;
    align-content: center;
box-shadow: 1px 1px 1px 0.5px #D1D1D1;

    }
    .grind-option.active, .product-size.active {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }
    
    #section1, #section2, #section3, #section4, #section5{
        background: #F6F5F8;
        padding: 20px;
        text-align: center;
        border-radius: 40px;
    }
    
            @media screen and (max-width: 576px) {
    
    .grind-option, .product-size

 {
    padding: 11px 7px;
    border-radius: 10px;
    border: 1px solid #ccc;
    cursor: pointer;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
    max-width: 135px;
    /*width: 230px;*/
    text-align: center;
    align-content: center;
    box-shadow: 1px 1px 1px 0.5px #D1D1D1;
    font-size: 12px;
}


                
            }
            
            
</style>


<style>
        /* --- General Setup & Variables --- */
        :root {
            --primary-color: #007bff;
            --border-color: #dee2e6;
            --card-bg: #ffffff;
            --card-shadow: 0 4px 8px rgba(0,0,0,0.05);
            --card-hover-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            margin: 0;
        }

        h3 {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        /* --- Utility Class --- */
        .hidden {
            display: none !important;
        }
        
        /* --- Product Grid Layout --- */
        .product-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }

        /* --- Product Card Styling --- */
        .product-card {
            display: block;
            position: relative;
            background-color: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.25s ease-in-out;
            cursor: pointer;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
        }

        .product-card .product-radio {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .product-card img {
            display: block;
            width: 100%;
            height: auto;
            /*aspect-ratio: 4 / 3;*/
            object-fit: cover;
            /*border-bottom: 1px solid var(--border-color);*/
        }

        .product-card .card-body {
            padding: 1rem 1.5rem;
            text-align: center;
        }

        .product-card h5 {
            margin: 0 0 1rem 0;
            font-size: 1.2rem;
            font-weight: 500;
        }
        
        /* --- Quantity Selector Styling --- */
        .quantity-selector {
            display: flex; /* Changed from 'none' to 'flex' */
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
            max-height: 0; /* Animate height */
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease, margin-top 0.3s ease;
        }

        .quantity-btn {
            background-color: #e9ecef;
            border: none;
            color: #495057;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
            line-height: 1;
        }
        .quantity-btn:hover {
            background-color: #ced4da;
        }
        .quantity-input {
            width: 40px;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 500;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 0.25rem;
            /* Hide ugly number input arrows */
            -moz-appearance: textfield;
        }
        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        /* --- Styling for the SELECTED State --- */
        /*.product-card .product-radio:checked + .card-visuals {*/
        /*    border-color: var(--primary-color);*/
        /*    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);*/
        /*}*/
        
        /* Show the quantity selector for the selected card */
        .product-card .product-radio:checked + .card-visuals .quantity-selector {
            max-height: 50px; /* Animate height */
            opacity: 1;
            margin-top: 0.5rem;
        }

        .product-card .product-radio:checked + .card-visuals::after {
            content: '✔';
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--primary-color);
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
            visibility: hidden;
        }

        /* --- Loader / "Load More" Button Styling --- */
        .loader-container {
            display: flex;
            justify-content: center;
            padding: 2rem 0;
        }
        #load-more-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        #load-more-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }


        #section3{
            text-align: center;
        }
        
        #sizes-wrapper{
            justify-content: center;
            gap:1rem;
            margin-block: 30px;
        }


.product-card.active {
background: #007bff !important;
color: #fff;
}


        .product-size{
                display: flex;
    flex-direction: column;
    gap: 30px;
        }
        
        label{
            margin: 0;
        }
        
        .quantity-selector i{
            font-size: 13px;
    color: #000000
        }

        /* --- Responsive Breakpoints --- */
        @media (min-width: 600px) {
            .product-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (min-width: 992px) {
            .product-grid { grid-template-columns: repeat(4, 1fr); }
        }
    </style>
<div class="main-content ">
    <div class="container">
        <div class="top-heading py-3 rtl-text">
          <h1>{{ translate('Coffee Subscriptions') }}</h1>
          <p class="text-muted">{{ translate('Get A new Coffee Each Month With A Plan Personailzed To Your Prefrences') }}</p>
        </div>
    <div class="video-section">
          <!--<img src="{{ asset ('public/assets/img/home-page/Group 173.png')}}" alt="">-->
                  <video autoplay loop muted playsinline>
            <source src="{{ asset('public/assets/img/home-page/exclusive.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        </div>
        <div class="bottom-heading text-center py-4">
      <h3 class="fw-bolder">{{ translate('How I Drink My Coffee') }}</h3>
    <span>{{ translate('Follow Steps') }}</span>
    </div>
        </div>

    

   <form method="POST" action="{{ route('product.subscribe') }}">
    @csrf
<input type="hidden" name="price_per_pack" id="price_per_pack">
    <!-- SECTION 1: Grind Size (Dynamic Select Box) -->
          <div class="container my-4">
    <section id="section1"  class="step-section">

            <!--<label for="grind_size" class="fw-bold">Select Your Grind Size</label>-->
            <!--<select name="grind_size" id="grind_size" class="form-control mb-4" required>-->
            <!--    <option value="">Select...</option>-->
            <!--    @foreach($attributeValues as $val)-->
            <!--        <option value="{{ $val->value }}">{{ $val->value }}</option>-->
            <!--    @endforeach-->
            <!--</select>-->
            
            <label for="grind_size" class="fw-bold"><h3> {{ translate('Get A new Coffee Each Month With A Plan Personailzed To Your Prefrences') }}</h3></label>
<input type="hidden" name="grind_size" id="grind_size" required>
<div class="grind-options">
    @foreach($attributeValues as $val)
        <div class="grind-option" data-value="{{ $val->value }}">{{ $val->value }}</div>
    @endforeach
</div>

    </section>
        </div>
    <!-- SECTION 2: Products List (Dynamic) -->

  <!-- SECTION 2: Products List (Dynamic) -->
<div class="container my-4">
    <section id="section2"  class="step-section">
        <h3>{{ translate('Select Product') }}</h3>

        {{-- The responsive grid container --}}
        <div class="product-grid" id="product-grid">
            @foreach($products as $product)
                <label class="product-card @if($loop->index >= 8) hidden @endif">
                    <input type="radio" name="product_id" value="{{ $product->id }}" class="product-radio" id="product" >
                    <div class="card-visuals">
                        <img src="{{ get_image($product->thumbnail) }}" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5>{{ $product->getTranslation('name') }}</h5>
                            <div class="quantity-selector">
                                <button type="button" class="quantity-btn minus-btn" aria-label="Decrease quantity"><i class="fa-solid fa-minus"></i></button>
                               
                                <input type="number" class="quantity-input" value="1" min="1" readonly>
                                <button type="button" class="quantity-btn plus-btn" aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
                            </div>

                        </div>
                    </div>
                </label>
            @endforeach
             <input type="hidden" name="quantity" id="hidden-quantity" value="1">
        </div>
        @if(count($products) > 9)
            <div class="loader-container" id="loader-container">
                <button id="load-more-btn" type="button"> {{ translate('More Products') }}</button>
            </div>
        @endif

    </section>
</div>
   <!-- SECTION 3: HOW MUCH COFFEE DO YOU WANT? -->

<div class="container my-4">
<section id="section3"  class="step-section">
    <input type="hidden" name="weight" id="weight" required>
        <div class="">
        <label class="fw-bold">{{ translate('Select Size/Weight') }}</label>
        <div id="sizes-wrapper" class="row">
            {{-- Size options will be loaded here --}}
        </div>
        </div>
    </div>
</section>


<!--<input type="hidden" name="grind_size" id="grind_size" required>-->
<!--<div class="grind-options">-->
<!--    @foreach($attributeValues as $val)-->
<!--        <div class="grind-option" data-value="{{ $val->value }}">{{ $val->value }}</div>-->
<!--    @endforeach-->
<!--</div>-->



    <!-- SECTION 4: HOW OFTEN? -->
        <div class="container my-4">
    <section id="section4"  class="step-section">
        <center>
            <h2 class="mt-4"><li>{{ translate('HOW OFTEN?') }}</li></h2>
        </center>
        <div class="grid-wrapper-week">
            <input type="hidden" name="week" id="week">
            <div class="grid-number bottom-line" style="border: 1px solid #e7dfd2;">
                <img src="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/coffee-week1.png" class="img-fluid" title="EVERY WEEK" />
                <div class="optsList">
                    <p><strong> {{ translate('EVERY WEEK') }} </strong></p>
                    <li>
                        <small class="week_section"
                               data-name="{{ translate('EVERY WEEK') }} "
                               data-week="1"
                               data-asset="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/coffee-week1.png">
                            {{ translate('Select') }}
                        </small>
                    </li>
                </div>
            </div>
            <div class="grid-number bottom-line" style="border: 1px solid #e7dfd2;">
                <img src="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/coffee-week2.png" class="img-fluid" title="EVERY OTHER WEEK" />
                <div class="optsList">
                    <p><strong> {{ translate('EVERY TWO WEEK') }} </strong></p>
                    <li>
                        <small class="week_section"
                               data-name="{{ translate('EVERY TWO WEEK') }}"
                               data-week="2"
                               data-asset="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/coffee-week2.png">
                           {{ translate('Select') }}
                        </small>
                    </li>
                </div>
            </div>
            <div class="grid-number" style="border: 1px solid #e7dfd2;">
                <img src="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/coffee-week3.png" class="img-fluid" title="EVERY FOUR WEEKS" />
                <div class="optsList">
                    <p><strong>{{ translate('EVERY FOUR WEEKS') }}</strong></p>
                    <li>
                        <small class="week_section"
                               data-name="{{ translate('EVERY FOUR WEEKS') }}"
                               data-week="3"
                               data-asset="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/coffee-week3.png">
                            {{ translate('Select') }}
                        </small>
                    </li>
                </div>
            </div>
        </div>
    </section>
</div>
    <!-- SECTION 5: HOW LONG? -->
    
    <div class="container my-4">
    
    <section id="section5"  class="step-section">
         <input type="hidden" name="month" id="month" required>
        <center>
            <h2><li>{{ translate('HOW LONG?') }}</li></h2>
           
        </center>
        <div class="grid-wrapper-week">
            <div class="grid-number bottom-line" style="border: 1px solid #e7dfd2;">
                <img src="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/month-icons-3-black-simp.png" class="img-fluid" title="3 MONTH" />
                <div class="optsList">
                    <p><strong> {{ translate('3 MONTH ') }}</strong></p>
                    <li>
                        <small class="month_section"
                               data-name="{{ translate('3 MONTH ') }}"
                               data-month="3 months"
                               data-asset="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/month-icons-3-black-simp.png">
                            {{ translate('Select') }}
                        </small>
                    </li>
                </div>
            </div>
            <div class="grid-number bottom-line" style="border: 1px solid #e7dfd2;">
                <img src="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/month-icons-6-black-simp.png" class="img-fluid" title="6 MONTH" />
                <div class="optsList">
                    <p><strong> {{ translate('6 MONTH') }} </strong></p>
                    <li>
                        <small class="month_section"
                               data-name="{{ translate('6 MONTH') }}"
                               data-month="6 months"
                               data-asset="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/month-icons-6-black-simp.png">
                            {{ translate('Select') }}
                        </small>
                    </li>
                </div>
            </div>
            <div class="grid-number" style="border: 1px solid #e7dfd2;">
                <img src="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/month-icons-12-black-simp.png" class="img-fluid" title="12 MONTH" />
                <div class="optsList">
                    <p><strong> {{ translate('12 MONTH') }}</strong></p>
                    <li>
                        <small class="month_section"
                               data-name="{{ translate('12 MONTH') }}"
                               data-month="12 months"
                               data-asset="https://2bl8ukr5sgcwbizg-2533228643.shopifypreview.com/cdn/shop/t/18/assets/month-icons-12-black-simp.png">
                            {{ translate('Select') }}
                        </small>
                    </li>
                </div>
            </div>
        </div>
    </section>
    </div>
        <!-- SECTION 6: Remove, sirf Buy Now Button -->
   <!-- SECTION 6: Buy Now Button (Initially Hidden) -->
<section id="section6" class="step-section" style="display:none">
    <div class="container text-center my-5">
        <button type="submit" class="btn btn-primary rounded-pill w-50 fw-bold" onclick="buyNow();">{{ translate('Buy Now') }}</button>
    </div>
</section>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    </form>

    
    </div>
        </div>
        </div>
<script>
jQuery(document).ready(function() {
    // === 1. Set values to hidden fields on option click ===
    jQuery(".want_section").click(function() {
        jQuery('#weight').val(jQuery(this).data("name"));
        jQuery(".want_section").removeClass("selected")
            .css({"background-color": "", "color": "#000", "font-weight": "300"})
            .text("Select");
        jQuery(this).addClass("selected")
            .css({"background-color": "blue", "color": "#fff", "font-weight": "600"})
            .text("SELECTED");
    });

    jQuery(".week_section").click(function() {
        jQuery('#week').val(jQuery(this).data("name"));
        jQuery(".week_section").removeClass("selected")
            .css({"background-color": "", "color": "#000", "font-weight": "300"})
            .text("Select");
        jQuery(this).addClass("selected")
            .css({"background-color": "blue", "color": "#fff", "font-weight": "600"})
            .text("SELECTED");
    });

    jQuery(".month_section").click(function() {
        let monthVal = jQuery(this).data("name");
        jQuery('#month').val(monthVal);
        jQuery(".month_section").removeClass("selected")
            .css({"background-color": "", "color": "#000", "font-weight": "300"})
            .text("Select");
        jQuery(this).addClass("selected")
            .css({"background-color": "blue", "color": "#fff", "font-weight": "600"})
            .text("SELECTED");
    });

    // === 2. Product selection radio button highlight (optional for UI polish) ===
    jQuery('input[name="product_id"]').change(function() {
        jQuery('input[name="product_id"]').each(function() {
            jQuery(this).closest('.card').removeClass('border-primary');
        });
        jQuery(this).closest('.card').addClass('border-primary');
    });

    // === 3. On form submit, make sure all hidden values are set ===
    jQuery('form').on('submit', function(e) {
        // If any hidden value is empty, prevent submit and alert
        // if (!jQuery('#weight').val() || !jQuery('#week').val() || !jQuery('#month').val()) {
        //     alert('Please select all options (Weight, Frequency, and Duration) before submitting.');
        //     e.preventDefault();
        //     return false;
        // }
         const selectedRadio = document.querySelector('.product-radio:checked');
    if (selectedRadio) {
        const card = selectedRadio.closest('.product-card');
        const qtyInput = card.querySelector('.quantity-input');
        const hiddenQuantity = document.getElementById('hidden-quantity');
        if (qtyInput && hiddenQuantity) {
            hiddenQuantity.value = qtyInput.value;
        }
    }
    });
    $('form').on('submit', function(e){
    if (!$('#grind_size').val() || !$('#week').val() || !$('#product').val() || !$('#month').val() || !$('#weight').val()) {
        e.preventDefault();
        Swal.fire('Error', 'Please select all steps before submitting!', 'error');
        return false;
    }
});

   
});
jQuery(document).ready(function() {
    // Product select -> AJAX for sizes
    jQuery('input[name="product_id"]').on('change', function() {
        var productId = jQuery(this).val();
        var grind = jQuery('#grind_size').val(); 
       // console.log('Product ID:', productId, 'Grind:', grind);
        jQuery('#sizes-wrapper').html('<p>Loading sizes...</p>');
        jQuery('#weight').val('');

        jQuery.ajax({
            url: '/subscription/get-product-sizes/' + productId,
            data: { grind: grind },
            type: 'GET',
          
            success: function(sizes) {
             //    console.log('Received sizes:', sizes); 
    let html = '';
    if(sizes.length > 0){
        sizes.forEach(function(sz, idx) {
            html += `<div class="col-md-4 mb-2 product-size">
                <label class="w-100">
                    <input type="radio" name="weight_radio" value="${sz.size}" data-price="${sz.price}" class="size-radio" >
                    <span>${sz.size} - ${sz.price} PKR</span>
                </label>
            </div>`;
        });
        jQuery('#weight').val(sizes[0].size); // First auto-select
        jQuery('#price_per_pack').val(sizes[0].price); // First price auto-select
    } else {
        html = '<p>No sizes found for this product.</p>';
        jQuery('#price_per_pack').val('');
    }
    jQuery('#sizes-wrapper').html(html);
}
        });
    });

    // Radio click -> hidden field set
   jQuery(document).on('change', 'input[name="weight_radio"]', function() {
    jQuery('#weight').val(jQuery(this).val());
    jQuery('#price_per_pack').val(jQuery(this).data('price')); // <-- Ye add karni hai
});
    jQuery('#grind_size').on('change', function() {
    jQuery('input[name="product_id"]:checked').trigger('change');
});
});

</script>

<script>
    document.querySelectorAll('.grind-option').forEach(option => {
        option.addEventListener('click', () => {
            document.querySelectorAll('.grind-option').forEach(el => el.classList.remove('active'));
            option.classList.add('active');
            document.getElementById('grind_size').value = option.getAttribute('data-value');
            
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
isLoggedIn = (isLoggedIn === true || isLoggedIn === 'true'); // ab yeh JS boolean hai
var loginUrl = "{{ route('login') }}";

$(document).ready(function(){
    $('form').on('submit', function(e){
        if (!isLoggedIn) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Login Required',
                html: 'Please <a href="' + loginUrl + '" style="color:#3085d6; text-decoration:underline;">login</a> to buy this product.',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
            return false;
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // --- STATE & CONFIG ---
    const productsPerPage = 8;
    
    // --- DOM ELEMENTS ---
    const productGrid = document.getElementById('product-grid');
    const loadMoreBtn = document.getElementById('load-more-btn');
    const loaderContainer = document.getElementById('loader-container');
    const allProductCards = productGrid.querySelectorAll('.product-card');
    
    // Calculate how many are currently visible (should be 9 or less)
    let visibleProductCount = productGrid.querySelectorAll('.product-card:not(.hidden)').length;

    // --- FUNCTIONS ---
    function showMoreProducts() {
        const totalProducts = allProductCards.length;
        const newVisibleCount = Math.min(visibleProductCount + productsPerPage, totalProducts);

        for (let i = visibleProductCount; i < newVisibleCount; i++) {
            if (allProductCards[i]) {
                allProductCards[i].classList.remove('hidden');
            }
        }
        
        visibleProductCount = newVisibleCount;

        if (visibleProductCount >= totalProducts) {
            if(loaderContainer) loaderContainer.classList.add('hidden');
        }
    }

   function handleQuantityChange(btn) {
  const card = btn.closest('.product-card');
  if (!card) return;

  const input = card.querySelector('.quantity-input');
  if (!input) return;

  if (btn.classList.contains('plus-btn')) {
    input.value = parseInt(input.value || '1', 10) + 1;
  } else if (btn.classList.contains('minus-btn')) {
    const curr = parseInt(input.value || '1', 10);
    input.value = Math.max(1, curr - 1);
  }

  // Hidden input sync
  const radio = card.querySelector('.product-radio');
  const hiddenQuantity = document.getElementById('hidden-quantity');
  if (radio && radio.checked && hiddenQuantity) {
    hiddenQuantity.value = input.value;
  }
}

// Single delegated listener
productGrid.addEventListener('click', (e) => {
  const btn = e.target.closest('.quantity-btn');
  if (!btn) return;

  e.preventDefault();
  e.stopPropagation();

  handleQuantityChange(btn);
});

    // --- EVENT LISTENERS ---
    if(loadMoreBtn) {
        loadMoreBtn.addEventListener('click', showMoreProducts);
    }
    
    productGrid.addEventListener('click', (e) => {
        if (e.target.classList.contains('quantity-btn')) {
            e.preventDefault();
            handleQuantityChange(e);
        }
    });

    // --- INITIALIZATION ---
    // Hide the loader initially if all products are already visible
    if (loaderContainer && visibleProductCount >= allProductCards.length) {
        loaderContainer.classList.add('hidden');
    }

});
</script>

<script>
    document.querySelectorAll('.product-card').forEach(function(card) {
    card.addEventListener('click', function() {
        // Remove 'active' class from all cards
        document.querySelectorAll('.product-card').forEach(function(c) {
            c.classList.remove('active');
        });

        // Add 'active' class to the clicked card
        this.classList.add('active');
    });
});

</script>
<script>
    $('.step-section').hide();
    $('#section1').show();

    function scrollToSectionIfHidden(id) {
        const section = $(id);
        if (section.is(':hidden')) {
            section.slideDown(function () {
                $('html, body').animate({
                    scrollTop: section.offset().top
                }, 300);
            });
        } else {
            section.show();
        }
    }

    // Step 1: Grind Option (show section2, NO scroll)
    $('.grind-option').click(function () {
        $('#section3, #section4, #section5, #section6').hide();
        if ($('#section2').is(':hidden')) {
            $('#section2').slideDown();
        }
    });

    // Step 2: Product Select (show section3, NO scroll)
    $(document).on('change', 'input[name="product_id"]', function () {
        $('#section4, #section5, #section6').hide();
        if ($('#section3').is(':hidden')) {
            $('#section3').slideDown();
        }
    });

    // Step 3: Weight Select (show section4, SCROLL)
    $(document).on('change', 'input[name="weight_radio"]', function () {
        $('#section5, #section6').hide();
        scrollToSectionIfHidden('#section4');
    });

    // Step 4: Week/Month Select (show section5, then section6 with scroll)
    $(document).on('click', '.week_section', function () {
        scrollToSectionIfHidden('#section5');
        setTimeout(function() {
            scrollToSectionIfHidden('#section6');
        }, 200); // optional delay for better UX
    });
</script>



@endsection