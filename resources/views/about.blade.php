@extends('frontend.layouts.app')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

@section ('content')

     <link rel="stylesheet" href="{{ static_asset('assets/css/about-us.css') }}">
 
<section style="background-color:#F5F5F7;">
    <div class="container" >
    <style>
        

    
.owl-carousel {
    width:100%;  /* Fixed width */
    margin: 0 auto; /* Center carousel */
    overflow: hidden; /* Ensure slides don’t break layout */
        margin-top: 4px;
}

.owl-carousel .item img {
    width: 100%;          /* Ensure images scale properly */
    height: 467.25979px;        /* Set a fixed height */
    object-fit: cover;    /* Prevent image distortion */
}


    
    
    /* Position navigation arrows on both sides, full height */
.owl-nav {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* Style individual navigation buttons */
.owl-nav button {
    position: absolute;
    top: 0;
    height: 100%; /* Make them take full height */
    width: 120px;  /* Clickable area width */
    border: none;
  opacity:0;
    cursor: pointer;
}

/* Left Arrow */
.owl-nav .owl-prev {
    left: 0;
}

/* Right Arrow */
.owl-nav .owl-next {
    right: 0;
}

    
    .owl-theme .owl-nav [class*=owl-]:hover{
        background: transparent !important;
    }
    
    .owl-dots{
        margin-top:6px !important;
    }

/* Apply blur effect to all slides except the center one */
.owl-carousel .owl-item:not(.active) {
    filter: blur(1px);  /* Blur non-center slides */
    opacity: 0.6;  /* Reduce opacity */
    transition: all 0.6s ease-in-out;
}

/* Keep the center slide clear and slightly larger */
.owl-carousel .owl-item.active {
    filter: blur(0);
    opacity: 1;

}


    
    @media (max-width: 768px){
.owl-carousel .item img {
    height: 495px !important;        /* Set a fixed height */
}    }

    

    
    </style>

    <div class="section-content rtl-text">
    
    <div class="banner-1">
        
            <h2>{{ translate('About us') }}</h2>
      
    </div>
    
    <!-- /* banner-1 exit */ -->
    
    
    <!-- /* banner-2 */ -->
    <div class="banner-2">
        
        
        
        
        
        
        
    <div class="row m-0 p-0" id="card-1">
        <div class="banner-event-3 col-md-4 bg-white  order-md-2 col-12 order-2  ">
            <div class="mt-3">
            <div class="title-category">{{ translate('Emirati Coffee') }}</div>
            <div class="title-headline">
{{ translate(' Emirati Coffee products are carefully selected from the rarest micro-lots from around the world for the highest-grade specialty bean, roasted to perfection and packed in a state-of-the-art facility in the UK.') }}
</div>
    </div>
    </div>
    
    <div class="col-md-8 order-md-1 text-md-start col-12 order-1 text-center p-0 m-0"  >
<!--style="background-image: url('{{ asset('/assets/img/home-page/apple-logo_black.jpg.landing-regular_2x.png') }}')  ; border-radius:40px 0 0 40px; height: 360px; width: 100%;     background-size: cover; "-->
                                   <div class="main-card-image">
                                    <img src="{{asset('/assets/img/home-page/IMG_4724.jpg')}}" class="img-fluid" alt="...">
                                    </div>

    </div>
    </div>
    
        
        
     
    </div>
    </div>
    
    
    <div class="section-content ">
    <div class="card-boxes col-12 mb-3 p-0">
      <div class="row justify-content-center">
     
    
    
    <div class="col-md-6 d-flex justify-content-end">
      <div class="box-shadow">
          
                    <img src="{{asset('/assets/img/home-page/news-room-4.png')}}" class="img-fluid" alt="...">
           
        <div class="box-body">
         <div class="title-category">{{ translate('OUR HISTORY') }}</div>
          <div class="title-headline-2">
{{ translate('In the late 1930s, our founder’s grandfather loads his ships with spices. textiles and machinery, and bags of small green beans that would change the course of his life. It’s the first of its kind, and ode to a small green bean.') }}
            </p>
        </div>
      </div>
      </div>
      </div>
    
    
    <div class="col-md-6 d-flex  ">
        <div class="box-shadow">
                      <img src="{{ asset('/assets/img/home-page/news-room-3.png')}}" class="img-fluid" alt="...">

          <div class="box-body">
        <div class="title-category">{{ translate('OUR COFFEE') }}</div>
          <div class="title-headline-2">
              {{ translate('Coffee was then transported and traded throughout the Arabian Gulf.') }}
              <!--It was served at the ports when you arrived, in people’s homes and in their yards, amongst friends and strangers. The scent is familiar and resilient; it endured down through three generations, greeting Emirati Coffee, a local speciality coffee scene and restore the glory of their forefathers.-->
              </p>
          </div>
        </div>
      </div>
            </div>
    
    
    
      <div class="col-md-6 d-flex justify-content-end ">
        <div class="box-shadow">
        <img src="{{ asset('/assets/img/home-page/news-room-1.png')}}" class="img-fluid" alt="...">
          <div class="box-body">
        <div class="title-category">   {{ translate('OUR MISSION') }}</div>
          <div class="title-headline-2">
              
               {{ translate('To make the highest quality specialty coffee accessible through competitive pricing, high quality sourcing and vertical integration.') }}</p>
          </div>
        </div>
      </div>
          </div>
    
    
      <div class="col-md-6 d-flex ">  
      
        <div class="box-shadow">
          <img src="{{ asset('/assets/img/home-page/news-room-2.png')}}  " class="img-fluid" alt="...">
          <div class="box-body">
            <div class="title-category">{{ translate('OUR VISION') }}</div>
             <div class="title-headline-2">
              {{ translate('We envision a world where specialty coffee is widely available, inspiring everyone to live a richer life.') }}</p>
          </div>
        </div>
      </div>
    
    
      </div>
      </div>
    </div>
    </div>    
    <!-- /* banner-2 exit */ -->

    <div class="heading text-center mt-0">
      <h1 class="mb-5">{{ translate('OUR VALUES') }}</h1>
    </div>
    
    </div>
    <!-- slider -->
    
    <div class="owl-carousel owl-theme">
    <div class="item">
        <img src="/assets/img/home-page/Colombia2016_5.jpg" 

             alt="Slide 1">
    </div>
    <div class="item">
        <img src="/assets/img/home-page/image006.jpg" 

             alt="Slide 2">
    </div>
    <div class="item">
        <img src="/assets/img/home-page/IMG_0794.jpg" 

             alt="Slide 3">
    </div>
    <div class="item">
        <img src="/assets/img/home-page/IMG_1362.jpg" 

             alt="Slide 4">
    </div>

</div>

    </section>
    
    
        
<!-- jQuery (Required for Owl Carousel) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
  $(document).ready(function () {
      var owl = $(".owl-carousel");

      owl.owlCarousel({
          loop: true,
          margin: 10,
          nav: true,
          dots: false,
          autoplay: true,
          autoplayTimeout: 3000,
          autoplaySpeed: 1500,
          smartSpeed: 1500,
          autoplayHoverPause: true,
          center: true,
          stagePadding: 150,
          responsive: {
              0: { items: 1, stagePadding: 50 },  
              600: { items: 1, stagePadding: 100 },  
              1000: { items: 1, stagePadding: 300 }  
          }
      });
  });
    
     </script>
    <script>


  
  
  
  
  
  
  const slider = document.querySelector('.slider-5 .reel');

// Pause animation on hover
slider.addEventListener('mouseenter', () => {
  slider.style.animationPlayState = 'paused';
});

// Resume animation on mouse leave
slider.addEventListener('mouseleave', () => {
  slider.style.animationPlayState = 'running';
});
  
  
 </script>

@endsection