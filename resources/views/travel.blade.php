@extends('frontend.layouts.app')
<style>


.coffee-section {
    background-color: #EFEEF0;
    padding: 60px 0;
    overflow-x: hidden; /* Prevents unwanted horizontal scroll on the page */
}

/* Image Gallery Styles */
.image-gallery {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-bottom: 60px;
    /* On smaller screens, allow horizontal scrolling */
    flex-wrap: nowrap;
    overflow-x: auto;
    padding: 10px;
    /* Hide scrollbar for a cleaner look */
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}


.image-card {

    height: 480px;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
    padding: 0 !important;

}



.image-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Content Section Styles */
.content-row {
        padding-block: 30px;
    margin: 0 auto;
}



.btn-find-destination {
    background-color: #0d6efd;
    border-color: #0d6efd;
    border-radius: 50px; /* Pill shape */
    padding: 12px 30px;
    font-weight: 600;
    font-size: 1rem;
    transition: background-color 0.2s ease-in-out, transform 0.2s ease;
}

.btn-find-destination:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
    transform: scale(1.05);
}

.description-text {
    font-size: 1.1rem;
    color: #495057;
    line-height: 1.6;
    max-width: 550px; /* Limits line length for better readability */
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    /* For tablets, align gallery to the left for scrolling */
    .image-gallery {
        justify-content: flex-start;
    }

    .description-text {
        max-width: 100%; /* Allow text to fill the column */
    }
}

@media (max-width: 767.98px) {
    /* For mobile phones */
    .coffee-section {
        padding: 40px 0;
    }

}


/*grid*/

/* Styling for the card itself (re-using from the first example) */
.image-card {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    height: 480px; /* You can adjust this as needed */
    min-width: 100%;
}

.image-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block; /* Removes any extra space below the image */
}


/*
  CSS GRID IMPLEMENTATION
  This replaces the Bootstrap .row and .col-* classes.
*/
.image-gallery-grid {
    display: grid;
    gap: 12px; /* This sets the space between all grid items */
margin-bottom: 18px;
    /* Default (Mobile): 1 column (like col-12) */
    grid-template-columns: 1fr;
    justify-items: center;
}



 .destination-section {
    background: #E8E7E8;
  }


  .map-container {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
  }

  .map-container iframe {
    border: 0;
    width: 100%;
    height: 450px;
    filter: grayscale(100%) contrast(1.2);
  }

  .map-search-dropdown {
    position: absolute;
    top: 10px;
    left: 10px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 300px;
  }

  .search-input {
    width: 100%;
    padding: 10px 15px;
    border: none;
    border-bottom: 1px solid #ccc;
    border-radius: 8px 8px 0 0;
    font-size: 14px;
    outline: none;
  }

  .map-dropdown-options {
    padding: 10px 15px;
  }

  .map-dropdown-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    color: #212529 !important;
  }

  .map-dropdown-item:last-child {
    margin-bottom: 0;
  }

  .color-box {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 2px;
    margin-right: 10px;
  }

  .color-box.blue {
    background-color: #007bff;
  }

  .color-box.black {
    background-color: #000;
  }
  
  
  .social-stories-section {
    padding: 80px 0;
    
    background-color: #EFEEF0;
}

/* Header Typography */
.section-header {
    margin-bottom: 40px;
}



.main-heading {
    font-size: 2.5rem;
    font-weight: 700;
    color: #212529;
    margin: 0;
    margin-bottom: 18px;
    line-height: 1.2;
}

/* CSS Grid for the Stories */
.stories-grid {
    display: grid;
    /* Desktop: 4 columns */
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    justify-items: center;
}

/* Individual Story Card Styling */
.story-card a {
    text-decoration: none;
}

.story-card img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 20px;
    display: block;
    margin-bottom: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}



.story-caption p {
    font-size: 12px;
    line-height: 1.5;
    color: #212529;
    margin: 0;
    font-weight: 700;
}

.story-caption strong {
    color: #212529;
}


/* --- Responsive Design --- */

/* Tablet view */
@media (max-width: 992px) {
    .stories-grid {
        /* Switch to 2 columns */
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Mobile view */
@media (max-width: 576px) {
    .social-stories-section {
        padding: 50px 0;
    }
    
    .content-row {
text-align: center;
    
}

.main-heading {
    text-align: center;
}
.stories-grid {
        /* Switch to 1 column */
        grid-template-columns: 1fr;
    }

    .main-heading {
        font-size: 2rem;
    }

    .sub-heading {
        font-size: 1.2rem;
    }
    
    .image-card , .story-card{
        max-width: 360px !important;

        min-width: 345px;
    }
    
        .image-card , .story-card img{

        max-height: 400px !important;

    }
}
/* Small screens and up (>= 576px): 2 columns (like col-sm-6) */
@media (min-width: 576px) {
    .image-gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Medium screens and up (>= 768px): 4 columns (like col-md-3) */
@media (min-width: 992px) {
    .image-gallery-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* A little extra styling for the body/container to center it */
body {
    background-color: #f4f4f4;
}
</style>
@section ('content')



<section class="coffee-section">
    <div class="container">
        <!-- Image Gallery (using Flexbox) -->

    <!-- The container for our grid -->
    <div class="image-gallery-grid">
        <!-- The grid items -->
        <div class="image-card">
            <img src="{{asset('/assets/img/home-page/108_MSF_IMG_6692.png')}}" alt="Man harvesting coffee beans from a lush green bush">
        </div>
        <div class="image-card">
            <img src="{{asset('/assets/img/home-page/AquiaresEstate_Diego&Pickers.png')}}" alt="Three coffee farmers sitting with a large pile of red coffee cherries">
        </div>
        <div class="image-card">
            <img src="{{asset('/assets/img/home-page/IMG_6520-min-scaled.png')}}" alt="Woman standing in a coffee plantation with green mountains in the background">
        </div>
        <div class="image-card">
            <img src="{{asset('/assets/img/home-page/Cofinet-3.png')}}" alt="Man in a white t-shirt smiling next to a coffee plant">
        </div>
    </div>


        <!-- Content Section (using Bootstrap Grid) -->
        <div class="row align-items-center content-row">
            <div class="col-lg-5 col-md-12 text-lg-start mb-4 mb-lg-0">
                <h1 class="main-heading">{{ translate('Welcome to Emirati Coffee') }}</h1>
                <a href="#" class="btn btn-primary rounded-pill btn-find-destination">{{ translate('Find Your Destination') }}</a>
            </div>
            <div class="col-lg-7 col-md-12">
                <p class="description-text">
                    {{ translate('In the late 1930s, our founder grandfather loads his ships with spices, textiles and machinery, and bags of small green beans that would change the course of his life.') }}
                </p>
                <p class="description-text fw-bold">
                    {{ translate('It is the first of its kind, and ode to a small green bean.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<section class="destination-section ">
    <div class="container py-5">
  <h2 class="main-heading">{{ translate('Choose your destination') }}</h2>

  <div class="map-container">
    <!-- Google Map Embed -->


<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3612.1580444308925!2d55.21843848042806!3d25.13034712039437!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f6bd832fc6cb3%3A0xc7d0bf8591df2c94!2sEmirati%20Coffee%20Roastery!5e0!3m2!1sen!2s!4v1751016599127!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    <!-- Search Dropdown Overlay -->
    <div class="map-search-dropdown">
      <input type="text" placeholder="{{ translate('Search') }}" class="search-input">
      <div class="map-dropdown-options">
        <div class="dropdown-item map-dropdown-item">
          <span class="color-box blue"></span>
          <span>{{ translate('National Organizations Farm') }}</span>
        </div>
        <div class="dropdown-item map-dropdown-item">
          <span class="color-box black"></span>
          <span>{{ translate('Date Farm') }}</span>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>

<section class="social-stories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="main-heading">{{ translate('Social media Emirati Coffee stories') }}</h2>
        </div>

        <div class="stories-grid">
            <!-- Story Card 1 -->
            <div class="story-card">
                <a href="#" class="story-image-link">
                    <img src="{{asset('/assets/img/home-page/lady.png')}}" alt="A woman smiling in a greenhouse on a farm.">
                </a>
                <div class="story-caption">
                    <p>{{ translate('The Éco Ferme Fructueux wants to say a HUGE THANK YOU for her incredible time with us! You were much more than a guest... a true farm superhero! 💚') }}</p>
                </div>
            </div>

            <!-- Story Card 2 -->
            <div class="story-card">
                <a href="#" class="story-image-link">
                    <img src="{{asset('/assets/img/home-page/image-2222.png')}}" alt="Two women embracing in front of a wall with 'Eco Ferme' graffiti.">
                </a>
                <div class="story-caption">
                    <p>{{ translate('The Éco Ferme Fructueux wants to say a HUGE THANK YOU for her incredible time with us! You were much more than a guest... a true farm superhero! 💚') }}</p>
                </div>
            </div>

            <!-- Story Card 3 -->
            <div class="story-card">
                 <a href="#" class="story-image-link">
                    <img src="{{asset('/assets/img/home-page/fermentaiton.png')}}" alt="Farmers sorting freshly harvested red coffee cherries.">
                </a>
                <div class="story-caption">
                    <p>{{ translate('The Éco Ferme Fructueux wants to say a HUGE THANK YOU for her incredible time with us! You were much more than a guest... a true farm superhero! 💚') }}</p>
                </div>
            </div>

            <!-- Story Card 4 -->
            <div class="story-card">
                 <a href="#" class="story-image-link">
                    <img src="{{asset('/assets/img/home-page/Renovated-Farmhouse.png')}}" alt="A farmhouse with a red roof on a lush green hillside coffee plantation.">
                </a>
                <div class="story-caption">
                    <p>{{ translate('The Éco Ferme Fructueux wants to say a HUGE THANK YOU for her incredible time with us! You were much more than a guest... a true farm superhero! 💚') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection