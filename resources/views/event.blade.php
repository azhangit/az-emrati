@extends('frontend.layouts.app')

@php
    $navBaseUrl = rtrim(config('app.nav_base_url', env('NAV_BASE_URL', 'http://localhost:8000')), '/');
@endphp

<link rel="stylesheet" href="{{ static_asset('assets/css/events.css') }}">
@section ('content')

<section>

    <div class="banner-1 text-center"style="background-image: url('{{ asset('public/assets/img/home-page/events-background.png') }}')";>
       
            <h1>{{ translate('Emirati Coffee') }}</h1>
      <h1>{{ translate('Events is here') }}</h1>
    </div>
    
    
    <!-- /* banner-1 exit */ -->
    
    
    <!-- /* banner-2 */ -->
    <div class="container">
    <div class="heading-bottom text-center m-5">
     
    
        
    <h1>{{ translate('Here is what we announced.') }}</h1>
    
    </div>
    
    <div class="banner-box">
    <div class="row jutify-self-center justify-content-center">
    
    
      


    <div class="row m-0 py-5 p-0">
        <div class="banner-event-2 col-md-6 align-content-center  order-md-1 col-12 text-center  order-2  ">
        <h3>{{ translate('Corporate Catering') }}</h3>
    
    
           <li>Phone        <a href="+97143395814">+97143395814</a>  </li> 
        <li>WhatsApp<a href="+971568886034">+971568886034</a></li>
       <li>Email<a href="mailto:info@emirati.com.com">info@emirati.com</a></li>    
    
        <a href="{{ url($navBaseUrl . '/contact') }}" class="btn btn-primary rounded-pill">{{ translate('Learn More') }}</a>
    </div>
    
    <div class="banner-event-2-img col-md-6 order-md-2 text-md-start col-12 order-1 text-center p-0 m-0">
    <img src="{{asset('public/assets/img/image-11-1.png')}}" alt="" class="img-fluid">
    
    </div>
    </div>
  
    
    
    
    <div class="row m-0 p-0" id="card-1">
        <div class="banner-event-3 col-md-6 align-content-center  order-md-2 col-12 text-center  order-2  ">
        <h3>{{ translate('Corporate Catering') }}</h3>
    
    
           <li>Phone        <a href="+97143395814">+97143395814</a>  </li> 
        <li>WhatsApp<a href="+971568886034">+971568886034</a></li>
       <li>Email<a href="mailto:info@emirati.com.com">info@emirati.com</a></li>    
    
        <a href="{{ url($navBaseUrl . '/contact') }}" class="btn btn-primary rounded-pill">{{ translate('Learn More') }}</a>
    </div>
    
    <div class="banner-event-3-img col-md-6 order-md-1 text-md-start col-12 order-1 text-center p-0 m-0" style="border-radius:40px 0 0 40px;">
    <img src="{{asset('public/assets/img/image-12-1.png')}}" alt="" class="img-fluid">
    
    </div>
    </div>
    
    
    <div class="row m-0 p-0 py-5">
        <div class="banner-event-4 col-md-6 align-content-center  order-md-1 col-12 text-center  order-2  ">
        <h3>{{ translate('Corporate Catering') }}</h3>
    
    
           <li>Phone        <a href="+97143395814">+97143395814</a>  </li> 
        <li>WhatsApp<a href="+971568886034">+971568886034</a></li>
       <li>Email<a href="mailto:info@emirati.com.com">info@emirati.com</a></li>    
    
        <a href="{{ url($navBaseUrl . '/contact') }}" class="btn btn-primary rounded-pill">{{ translate('Learn More') }}</a>
    </div>
    
    <div class="banner-event-4-img  col-md-6 order-md-2 text-md-start col-12 order-1 text-center p-0 m-0">
    <img src="{{asset('public/assets/img/image-13-1.png')}}" alt="" class="img-fluid">
    
    </div>
    </div>    
    <!-- /* banners exit */ -->
    </div>
    </div>
    
    </section>

@endsection