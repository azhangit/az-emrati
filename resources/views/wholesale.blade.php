@extends('frontend.layouts.app')

@php
    $navBaseUrl = rtrim(config('app.nav_base_url', env('NAV_BASE_URL', 'http://localhost:8000')), '/');
@endphp

@section ('content')
<div id="work">
    <link rel="stylesheet" href="{{asset('/assets/css/wholesale.css') }}">
    <div class="container">
        <div class="top-heading pt-5 d-flex text-center">
            <div class="row">
          <h4 >{{ translate('Emirati at Work') }}</h4>
    <h3>{{ translate('Work As One Or One Thousand') }}</h3>
    <p>{{ translate('In the late 1930s, our founder’s grandfather loads his ships with spices, textiles and machinery, and bags of small green beans that would change the course of his life.') }}</p>
    
    <h6 class="my-2">{{ translate('It’s the first of its kind, and ode to a small green bean.') }}</h6>
        </div>
    
    </div>
    
    
    
    <!-- banner 1 -->
     <div class="banner-1">
     <div class="row justify-content-center m-5">
    
    <div class="card">
        <img src="{{asset('/assets/img/home-page/image-7.png')}}" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-text m-0">{{ translate('Your Café') }}</h5><h5 class="card-text mb-2"> {{ translate('superPowered') }} </h5>
              
            <a class="btn btn-primary rounded-pill" href="{{ url($navBaseUrl . '/contact') }}">{{ translate('Learn More') }} </a>
        </div>
      </div>
    
    
      <div class="card">
        <img src="{{ asset('/assets/img/home-page/image-8.png')}}" class=" card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-text mb-2">{{ translate('Coffee at your office like never before') }}
            </h5>
            <a class="btn btn-primary rounded-pill"  href="{{ url($navBaseUrl . '/contact') }}" >{{ translate('Learn More') }}</a>
        </div>
      </div>
    
    </div>
    </div>
    <!-- banner 1 exit -->
    
    <div class="bottom-heading text-center  justify-content-center">
     <div>
      <h3 class="py-2">{{ translate('TAKE THE NEXT STEP') }}</h3>
      <li>{{ translate('Talk to an coffee expert to find the best solution for your business') }}</li>
    </div>
    <div>
    <a class="btn btn-primary rounded-pill mt-2 mb-5" href="{{ url($navBaseUrl . '/contact') }}">{{ translate('Get In Touch') }}</a>
    </div>
      </div>
    
    </div>
    </div>


@endsection