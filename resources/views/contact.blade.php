@extends('frontend.layouts.app')
  <link rel="stylesheet" href="https://emiraticoffee.ae/public/assets/css/contact-us.css">
  <style>/* Wrap everything in a .contact-section if you want to scope styles */


/* Contact card container */
.contact__address {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Location title (e.g., "ROASTERY (Dubai)") */
.contact__address h4 {
  font-size: 1.25rem;
  font-weight: 700;
  text-transform: uppercase;
  margin-bottom: 1rem;
}

/* Address text */
.contact__address address {
  font-style: normal; /* Remove italic style */
  line-height: 1.5;
  margin-bottom: 1rem;
}

/* Map styling */
.contact__iframe {
  width: 100%;
  height: 400px;
  border: 0;
  border-radius:30px ;
  -webkit-filter: grayscale(100%);
  filter: grayscale(100%);
}

/* Directions link */
.contact__direction a {
  display: inline-block;
  margin-top: 0.5rem;
  color: #000; /* Adjust to match brand color */
  text-decoration: underline;
  font-weight: 600;
}

.contact__direction a:hover {
  text-decoration: none;
}

/* Working hours & contact info */
.contact__hours h5 {
  margin-top: 1rem;
  margin-bottom: 0.25rem;
  font-size: 1.125rem;
  font-weight: 600;
}

.contact__hours span,
.contact__hours a {
  display: block;
  font-size: 0.95rem;
  margin-bottom: 0.25rem;
  color: #333; /* Adjust text color */
}

/* Line separator */
.line-black {
  width: 100%;
  height: 1px;
  background-color: #000;
  margin: 1.5rem 0;
}


    /* Example gradient text styling */
    .text-gradient {
      background: linear-gradient(90deg, #ee0979, #ff6a00);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    /* Upload box styling */
    .upload-box {
      display: inline-block;
      padding: 1.2rem 2rem;
      border: 2px dashed #ccc;
      border-radius: 8px;
      cursor: pointer;
      transition: border-color 0.3s ease;
      text-align: center;
    }
    .upload-box:hover {
      border-color: #555;
    }

    /* Hide the actual file input */
    .upload-input {
      display: none;
    }
.career-options {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  justify-content: center;
  margin-top: 1rem;
}

/* Each option with fixed width and left-aligned content */
.career-option {
  display: flex;
  align-items: center;
  justify-content: flex-start;  /* Align items to the left */
  width: 140px;
  padding: 0.5rem 0.75rem; /* Reduced horizontal padding */
  border: 1px solid #ccc;
  border-radius: 0; /* Square corners */
  cursor: pointer;
  transition: background-color 0.3s ease, border-color 0.3s ease;
  user-select: none;
}

/* Visible radio button with minimal spacing */
.career-option input[type="radio"] {
  margin-right: 0.25rem; /* Reduced gap between radio and text */
}


</style>


<style>
        /* --- General Setup & Variables --- */
        :root {
            --primary-font: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            --text-color-dark: #333;
            --text-color-light: #666;
            --border-color-light: #e0e0e0;
            --border-color-medium: #cccccc;
            --background-color: #ffffff;
            --accent-color-focus: #5c5edc;
            --gradient-1-start: #b96ac9;
            --gradient-1-end: #de6fa4;
            --gradient-2-start: #5c5edc;
            --gradient-2-end: #8f62d7;
        }



        /* --- Main Career Section --- */
        .career-section {
            max-width: 800px;
            width: 100%;
            text-align: center;
            padding: 2rem;
            margin-inline: auto;
        }

        /* --- Typography --- */
        .career-section__subtitle {
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--text-color-dark);
            margin: 0 0 0.5rem 0;
        }

        .career-section__title {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin: 0 0 1rem 0;
        }

        .career-section__title .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .career-section__title .gradient-text--1 {
            background-image: linear-gradient(180deg, #955312 0%, #A259B6 46.9%, #1E4B7D 100%);
        }

        .career-section__title .gradient-text--2 {
background-image: linear-gradient(180deg,  #A259B6 46.9%, #1E4B7D 100%);
        }
        
        .career-section__tagline {
            font-size: 1.1rem;
            color: var(--text-color-light);
            margin: 0 0 2.5rem 0;
        }

        /* --- File Upload Area --- */
        .career-section__upload-area {
            border: 2px dashed var(--border-color-medium);
            border-radius: 12px;
            padding: 2rem;
            cursor: pointer;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        .career-section__upload-area:hover {
            border-color: var(--accent-color-focus);
            background-color: #f9f9f9;
        }
        
        .upload-label {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.1rem;
            color: var(--text-color-light);
            cursor: pointer;
        }

        .upload-label svg {
            width: 24px;
            height: 24px;
        }
        
        /* Visually hide the file input but keep it accessible */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* --- Career Path Form --- */
        .career-path__title {
            font-size: 1.1rem;
            color: var(--text-color-light);
            margin: 2.5rem 0 1.5rem 0;
        }

        .career-path__grid {
            display: grid;
            gap: 1rem;
            /* Responsive grid: 3 columns on desktop, 2 on tablet/mobile */
            grid-template-columns: repeat(3, 1fr);
        }

        /* --- Custom Radio Buttons --- */
        .radio-option {
            display: block;
            border: 1px solid var(--border-color-light);
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            text-align: left;
        }

        .radio-option:hover {
            border-color: var(--border-color-medium);
        }

        .radio-option:focus-within {
        background: #0077ED;
            box-shadow: 0 0 0 2px rgba(92, 94, 220, 0.2);
            color: #fff;
        }

        .radio-option-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            font-size: 1rem;
        }

        /* --- Responsive Design --- */
        @media (max-width: 768px) {
            .career-section__title {
                font-size: 2rem;
            }
            .career-path__grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {

            .career-section {
                padding: 1rem 1rem;
            }
            .career-section__title {
                font-size: 1.75rem;
            }
            .career-section__upload-area {
                padding: 1.5rem;
            }
        }
    </style>



@section ('content')

<section>
    <div class="container-fluid position-relative">
      <div class="container">
        <div class="row justify-content-center align-items-center my-2 my-md-5">
          <div class="col-12 text-center mt-5">
            <h2 class="fw-bold fs-1">{{ translate('We would love to hear from you, please be in touch!') }}</h2>
            <!--<h2 class="fw-bold fs-1">-->
            <!--  Corporate Catering, Weddings Or Other Events.-->
            <!--</h2>-->
          </div>
          <div class="col-12 col-md-6">
            <form action="#" class="mt-5 text-center">
              <div class="form-group mb-3">
                <input
                  type="text"
                  class="form-control shadow"
                  placeholder="{{ translate('Name') }}"
                  id="name"
                />
              </div>
              <div class="form-group mb-3">
                <input
                  type="email"
                  class="form-control shadow"
                  placeholder="{{ translate('Email') }}"
                  id="email"
                />
              </div>
              <div class="form-group mb-3">
                <input
                  type="tel"
                  class="form-control shadow"
                  placeholder="{{ translate('Phone') }}"
                  id="phone"
                />
              </div>
              <div class="mb-3">
                <textarea
                  class="shadow w-100"
                  name="message"
                  id="message"
                  rows="4"
                  placeholder="{{ translate('Message') }}"
                ></textarea>
              </div>
              <button
                class="btn btn-info text-white z-3 rounded-pill py-2 w-75 mt-3">{{ translate('Submit') }}</button>
            </form>
            <div class="contact-details text-center mt-4">
              <h4>{{ translate('For Other Details') }}</h4>
              <p class="z-3">
                Phone: +971 4 339 5814 <br />
                WhatsApp: +971 56 888 6034 <br />
                Email: info@emiraticoffee.com
              </p>
            </div>
          </div>
        </div>
      </div>


    </div>
    
    
<!--    <div class="container py-5 d-none">-->
  <!-- Headline & Subtitle -->
<!--  <div class="text-center mb-4 ">-->
<!--    <h2 class="fw-bold">{{ translate('Career Opportunities') }}</h2>-->
<!--    <h4 class="text-gradient fw-bold mb-2">-->
<!--      Upload Your CV for your Next Career Opportunity in Coffee-->
<!--    </h4>-->
<!--    <p>Your work is meaningful to us as it is to you.</p>-->
<!--  </div>-->

  <!-- Upload Resume Section -->
<!--  <div class="text-center mb-4">-->
<!--    <label for="resumeUpload" class="upload-box">-->
<!--              <span class="d-block fw-bold"><i class="fa-regular fa-square-plus"></i></span>-->
<!--      <span class="d-block fw-bold">Upload Resume</span>-->
<!--    </label>-->
<!--    <input-->
<!--      type="file"-->
<!--      id="resumeUpload"-->
<!--      class="upload-input"-->
<!--      name="resume"-->
<!--      accept=".pdf,.doc,.docx"-->
<!--    />-->
<!--  </div>-->

  <!-- Desired Career Path -->
<!--  <div class="text-center mb-4">-->
<!--    <h5 class="fw-bold">{{ translate('Your Desired Career Path') }}</h5>-->
<!-- Dropdown for Desired Career Path -->
<!--<div class="career-options w-md-50 mx-auto">-->
<!--  <label class="career-option">-->
<!--    <input type="radio" name="careerPath" value="Waiter">-->
<!--    {{ translate('Waiter') }}-->
<!--  </label>-->

<!--  <label class="career-option">-->
<!--    <input type="radio" name="careerPath" value="Barista">-->
<!--    {{ translate('Barista') }}-->
<!--  </label>-->

<!--  <label class="career-option">-->
<!--    <input type="radio" name="careerPath" value="Kitchen">-->
<!--    {{ translate('Kitchen') }}-->
<!--  </label>-->

<!--  <label class="career-option">-->
<!--    <input type="radio" name="careerPath" value="Roastery">-->
<!--    {{ translate('Roastery') }}-->
<!--  </label>-->

<!--  <label class="career-option">-->
<!--    <input type="radio" name="careerPath" value="Sales">-->
<!--    {{ translate('Roastery') }}-->
<!--  </label>-->

<!--  <label class="career-option">-->
<!--    <input type="radio" name="careerPath" value="Other">-->
<!--    {{ translate('Other') }}-->
<!--  </label>-->
<!--</div>-->

<!--  </div>-->
<!--</div>-->
    
    
    <section class="career-section">
        <h3 class="career-section__subtitle">{{ translate('Career Opportunities') }}</h3>
        <h2 class="career-section__title">
            <span class="gradient-text gradient-text--1">{{ translate('Upload Your CV for your Next') }}</span>
            
            <span class="gradient-text gradient-text--2">{{ translate('Career Opportunity in Coffee') }}</span>
        </h2>
        <p class="career-section__tagline">{{ translate('Your work is as meaningful to us as it is to you.') }}</p>

        <label for="resume-upload" class="career-section__upload-area">
            <div class="upload-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span id="upload-text">{{ translate('Upload Resume') }}</span>
            </div>
        </label>
        <input type="file" id="resume-upload" class="sr-only" accept=".pdf,.doc,.docx">

        <form class="career-section__form">
            <p class="career-path__title">{{ translate('Your Desired Career Path') }}</p>
            <div class="career-path__grid">
                
                <div class="radio-option">
                    <label class="radio-option-label">
                        <input type="radio" name="career-path" value="waiter" class="sr-only">
                        <span>{{ translate('Waiter') }}</span>
                    </label>
                </div>

                <div class="radio-option">
                    <label class="radio-option-label">
                        <input type="radio" name="career-path" value="barista" class="sr-only">
                        <span>{{ translate('Barista') }}</span>
                    </label>
                </div>

                <div class="radio-option">
                    <label class="radio-option-label">
                        <input type="radio" name="career-path" value="kitchen" class="sr-only">
                        <span>{{ translate('Kitchen') }}</span>
                    </label>
                </div>

                <div class="radio-option">
                    <label class="radio-option-label">
                        <input type="radio" name="career-path" value="roastery" class="sr-only">
                        <span>{{ translate('Roastery') }}</span>
                    </label>
                </div>

                <div class="radio-option">
                    <label class="radio-option-label">
                        <input type="radio" name="career-path" value="sales" class="sr-only">
                        <span>{{ translate('Sales') }}</span>
                    </label>
                </div>

                <div class="radio-option">
                    <label class="radio-option-label">
                        <input type="radio" name="career-path" value="other" class="sr-only">
                        <span>{{ translate('Other') }}</span>
                    </label>
                </div>

            </div>
        </form>
    </section>
    
    
<div class="container my-5">
  <!-- Row for Dubai -->
  <div class="row mt-3 align-items-start mb-5">
    <!-- Text Column -->


    <!-- Map Column -->
    <div class="col-md-6">
      <div class="map-wrapper">
        <iframe
          class="contact__iframe"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4295.60700223012!2d55.22079091947103!3d25.130263087304915!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f6bd832fc6cb3%3A0xc7d0bf8591df2c94!2sEmirati%20Coffee%20Roastery!5e0!3m2!1sen!2s!4v1576260094374!5m2!1sen!2s"
          allowfullscreen=""
          loading="lazy"
        ></iframe>
      </div>
    </div>
    
        <div class="col-md-6 my-3">
<h2 class="fw-bold mb-2">{{ translate('ROASTERY (Dubai)') }}</h2>
      <address>
{{ translate('14 9B Street, Warehouse 1 Al Quoz Industrial Area Three, Dubai') }}
      </address>
      <div class="contact__hours">
<h5 class="fw-bold">{{ translate('Our Working Hours') }}</h5>
        <span>Monday to Friday 9am to 5pm</span>
        <span>Saturday 9am to 3pm</span>
        <span>Sunday closed</span>
      </div>

      <h5 class="fw-bold mt-4">{{ translate('Contact Us') }}</h5>
      <p class="mb-1">Phone: <a href="tel:+97143395814">+ 971 4 339 5814</a></p>
      <p class="mb-1">WhatsApp: <a href="tel:+971568886034">+971 56 888 6034</a></p>
      <p class="mb-1">Email: <a href="mailto:info@emiraticoffee.com">info@emiraticoffee.com</a></p>

      <!-- Directions button -->
      <div class="mt-3">
        <a 
          class="btn btn-dark" 
          href="https://goo.gl/maps/XwFh9s4ydyZ3ruin7" 
          target="_blank" 
          rel="noopener"
        >
          {{ translate('GET DIRECTIONS') }}
        </a>
      </div>
    </div>
  </div>

  <!-- Row for Abu Dhabi -->
  <div class="row mt-3 align-items-start">
    <!-- Text Column -->
    <div class="col-md-6 order-2 order-md-1 my-3">
     <h2 class="fw-bold mb-2">{{ translate('Coffee Shop (Abu Dhabi)') }}</h2>
      <address>
{{ translate('Yas Mall, First Floor Abu Dhabi') }}
      </address>
      <div class="contact__hours">
<h5 class="fw-bold">{{ translate('Our Working Hours') }}</h5>
        <span>{{ translate('Sunday to Thursday 10am to 11pm') }}</span>
        <span>{{ translate('Friday to Saturday 10am to 12am') }}</span>
      </div>

      <h5 class="fw-bold mt-4">{{ translate('Contact Us') }}</h5>
      <p class="mb-1">Phone: <a href="tel:+971504908137">+ 971 50 490 8137</a></p>
      <p class="mb-1">Email: <a href="mailto:yasmall@emiraticoffee.com">yasmall@emiraticoffee.com</a></p>

      <!-- Directions button -->
      <div class="mt-3">
        <a 
          class="btn btn-dark" 
          href="https://maps.app.goo.gl/uWFGsX5iNRjVNsjp6" 
          target="_blank" 
          rel="noopener"
        >
          {{ translate('GET DIRECTIONS') }}
        </a>
      </div>
    </div>

    <!-- Map Column -->
    <div class="col-md-6 order-1 order-md-2">
      <div class="map-wrapper">
        <iframe
          class="contact__iframe"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1526.6010222362615!2d54.609075543944314!3d24.489281948365363!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x853856babeb0a5e2!2zMjTCsDI5JzE5LjciTiA1NMKwMzYnMzQuNiJF!5e0!3m2!1sen!2s!4v1644321266144!5m2!1sen!2s"
          allowfullscreen=""
          loading="lazy"
        ></iframe>
      </div>
    </div>
  </div>


  <div class="row align-items-start mt-5">
    <!-- Text Column -->


    <!-- Map Column -->
<div class="col-md-6">
  <div class="map-wrapper">
    <iframe
      class="contact__iframe"
      width="100%"
      height="450"
      style="border:0;"
      loading="lazy"
      allowfullscreen
      referrerpolicy="no-referrer-when-downgrade"
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3595.6419417788607!2d55.77678008055616!3d25.683161519132184!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ef60b007d4c815d%3A0x2f24fe46e73a2d06!2sEmirati%20Coffee%20-%20Al%20Hamra%20Mall!5e0!3m2!1sen!2s!4v1751044069034!5m2!1sen!2s">
    </iframe>
    
  </div>
</div>
     <div class="col-md-6">
   
<div class="container">
  <!-- Main Heading -->
  <h2 class="fw-bold mb-2">{{ translate('Al Hamra Mall (Ras Al Khaimah, UAE)') }}</h2>
  
  <!-- Address -->
  <address class="mb-4">
{{ translate('Sheikh Mohamed Bin Salem Rd - Al Jazeera Al Hamra-Qaryat Al Hamra - Ras Al Khaimah - United Arab Emirates') }}
  </address>


      <h5 class="fw-bold">{{ translate('Our Working Hours') }}</h5>
      <p class="mb-1">{{ translate('Monday to Friday 9am to 5pm') }}</p>
      <p class="mb-1">{{ translate('Saturday 9am to 3pm') }}</p>
      <p class="mb-1">{{ translate('Sunday closed') }}</p>


    <!-- Contact Us -->
    <div class="mb-3">
      <h5 class="fw-bold">{{ translate('Contact Us') }}</h5>
      <p class="mb-1">
        <strong>Phone:</strong> 
        <a href="tel:+97143395814">+971 4 339 5814</a>
      </p>
      <p class="mb-1">
        <strong>WhatsApp:</strong> 
        <a href="tel:+971568886034">+971 56 888 6034</a>
      </p>
      <p class="mb-1">
        <strong>Email:</strong> 
        <a href="mailto:info@emiraticoffee.com">info@emiraticoffee.com</a>
      </p>
      
      <a 
  href="https://www.google.com/maps?q=25.683171,55.781653" 
  target="_blank" 
  class="btn btn-dark mt-3"
>
{{ translate('Get Directions') }}
</a>

    </div>
  </div>
</div>
  </div>

</div>
    
</section>


<script>
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('resume-upload');
            const uploadText = document.getElementById('upload-text');
            const originalUploadText = uploadText.textContent;

            fileInput.addEventListener('change', () => {
                // Check if a file was selected
                if (fileInput.files.length > 0) {
                    // Display the name of the first file
                    uploadText.textContent = fileInput.files[0].name;
                } else {
                    // If the user cancelled the file dialog, reset the text
                    uploadText.textContent = originalUploadText;
                }
            });
            
            // Note: The radio buttons are standard HTML and will work out of the box.
            // A :checked state can be styled further in CSS if desired, e.g.,
            // .radio-option:has(input:checked) { border-color: var(--accent-color-focus); }
            // For broader browser support, the current focus-within approach is robust.
        });
    </script>
@endsection