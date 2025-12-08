@extends('frontend.layouts.app')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">


@section ('content')
<!-- Bootstrap CSS -->
<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">-->

     <style>
        /* --- Root Variables & Global Styles --- */
        :root {
            --bg-color: #f2f2f2;
            --container-bg: #e9e9e9;
            --cell-bg: #ffffff;
            --header-bg: #e0e0e0;
            --primary-text: #333333;
            --secondary-text: #777777;
            --button-bg: #e0e0e0;
            --button-hover-bg: #d4d4d4;
            --border-color: #dcdcdc;
            --selected-border: #00a2e8;

            --event-sensory: #f7d154;
            --event-barista: #a95596;
            --event-home-barista: #8d4d4d;
            --event-brewing: #00a2e8;
            --event-text: #ffffff;
        }


        #academy{
            background-color: #efeef0;
        }
        
        .banner-1 .card{
            background: #efeef0;
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

        /* --- Main Calendar Container --- */
        .calendar-container {
            width: 100%;
            max-width: 1100px;
    background-color: #efeef0;            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin: 40px auto;
        }
        
        /* --- Header Section --- */
        .calendar-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .calendar-header h1 {
            margin: 0 0 0.25rem 0;
            font-size: 2rem;
            font-weight: 600;
        }

        .calendar-header h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 400;
            color: var(--secondary-text);
        }
        
        /* --- Filters & Controls --- */
        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .filter-buttons button, .nav-button {
            background-color: var(--button-bg);
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            color: var(--primary-text);
        }
        
        .filter-buttons button:hover, .nav-button:hover {
            background-color: var(--button-hover-bg);
        }
        
        .filter-buttons button .icon {
            margin-left: 0.5rem;
            opacity: 0.6;
        }

        .month-navigation {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .month-display {
            font-size: 1.25rem;
            font-weight: 500;
            width: 180px;
            text-align: center;
        }
        
        .nav-button {
            padding: 0.5rem 0.8rem;
        }
        
        /* --- Calendar Grid --- */
        .calendar-grid-wrapper {
            background-color: var(--cell-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .day-header {
            background-color: var(--header-bg);
            font-weight: 500;
            font-size: 0.9rem;
            text-align: center;
            padding: 0.75rem 0;
        }

        .day-cell {
            position: relative;
            border-top: 1px solid var(--border-color);
            border-left: 1px solid var(--border-color);
            min-height: 120px;
            padding: 0.5rem;
            transition: background-color 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            cursor: pointer;
            background-color: #efeef0;
        }

        /* Remove border from first row/col */
        .day-cell:nth-child(7n + 1) { border-left: none; }
        
        .day-cell.selected {
            box-shadow: inset 0 0 0 2px var(--selected-border);
            z-index: 10;
        }

        .day-number {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--secondary-text);
            margin-bottom: 0.25rem;
        }

        .day-cell.other-month {
            background-color: #fafafa;
            cursor: default;
        }
        
        .day-cell.other-month .day-number {
            opacity: 0.4;
        }
        
        /* --- Event Styling --- */
        .event {
            padding: 0.5rem 0.6rem;
            border-radius: 6px;
            color: var(--event-text);
            font-size: 0.75rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            background: #9d4877;
            margin-bottom: 0.25rem;
            cursor: pointer;
            transition: opacity 0.2s ease;
            line-height: 1.3;
        }
        
        .event:hover {
            opacity: 0.9;
        }

        .event .event-name {
            font-weight: 600;
            display: block;
            margin-bottom: 0.2rem;
        }
        
        .event .event-time {
            font-size: 0.65rem;
            opacity: 0.95;
            display: block;
        }
        
        .modal.show .modal-dialog{
            transform: translate(0%, 50%) !important;
        }
        
        /* Course type colors based on event type */
        .event[data-type="Barista"] {
            background: #a95596;
            color: white;
        }
        
        .event[data-type="Sensory"] {
            background: #f7d154;
            color: #333;
        }
        
        .event[data-type="Brewing"] {
            background: #00a2e8;
            color: white;
        }
        
        .event[data-type="Home Barista"] {
            background: #8d4d4d;
            color: white;
        }
        
        /* --- Responsive Design --- */
        @media (max-width: 768px) {

            .calendar-container {
                padding: 1rem;
            }
            .calendar-header h1 { font-size: 1.5rem; }
            .calendar-header h2 { font-size: 1rem; }
            
            .calendar-controls {
                flex-direction: column-reverse;
                align-items: stretch;
            }
            
            .month-navigation {
                justify-content: space-between;
                width: 100%;
            }

            .month-display {
                font-size: 1.1rem;
            }

            .day-cell {
                min-height: 90px;
                padding: 0.3rem;
                    background-color: #efeef0;
            }

            .event {
                font-size: 0.7rem;
                padding: 0.3rem 0.5rem;
            }
            
            .event .event-time {
                display: none; /* Hide time on small screens to save space */
            }
        }
        
        @media (max-width: 480px) {
            .day-header .long-name { display: none; }
            .day-header .short-name { display: inline; }
            
            .event .event-name {
                white-space: normal;
                line-height: 1.2;
            }
        }
        
        @media (min-width: 481px) {
            .day-header .short-name { display: none; }
        }
        .mydropdown-item{
            color:#000 !important;
            
        }
        .mydropdown-item:hover{  color:#000!important;}
        .btn-secondary:hover{color:#000;}
        .radius{
        border-radius: 12px;
        }
    
    .institute-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .institute-card-wrapper{
        display: flex;
        justify-content: center;
        align-items: center;
    }

    
.owl-carousel {
    width:100%;  /* Fixed width */
    margin: 0 auto; /* Center carousel */
    overflow: hidden; /* Ensure slides don’t break layout */
        margin-top: 4px;
}

.owl-carousel .item img {
    width: 100%;          /* Ensure images scale properly */
    max-height: 600;        /* Set a fixed height */
    object-fit: cover;    /* Prevent image distortion */
    border-radius:18px;
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
/*.owl-carousel .owl-item:not(.active) {*/
/*    filter: blur(1px);   */
/*    opacity: 0.6; */
/*    transition: all 0.6s ease-in-out;*/
/*}*/

/* Keep the center slide clear and slightly larger */
/*.owl-carousel .owl-item.active {*/
/*    filter: blur(0);*/
/*    opacity: 1;*/

/*}*/


    
    @media (max-width: 768px){
.owl-carousel .item img {
    height: 495px !important;        /* Set a fixed height */
}    }

    

    
    </style>


<div id="academy">

    <div class="container">
    <div    class="hero-section pt-3">
        
        <div class="top-heading mb-3 rtl-text">
          <h1>{{ translate('Coffee Academy') }}</h1>
       
        </div>
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
    <!--<div class="video-section">-->
    <!--              <video autoplay loop muted playsinline>-->
    <!--        <source src="{{ asset('/assets/img/home-page/race.mp4') }}" type="video/mp4">-->
    <!--        Your browser does not support the video tag.-->
    <!--    </video>        </div>-->
    
    </div>
    
    <div class="bottom-heading text-center py-3">
      <h3>{{ translate('Welcome to Emirati Coffee Academy') }}</h3>
    <span>{{ translate('Being a center of excellence and a prime player in the UAE coffee scene, it is only natural for Emirati Coffee to expand its reach into coffee educational programs. As the pool of talent grows across the region, coffee enthusiasts and professionals can count on us to help them expand their knowledge base and prepare for the real world. At  Emirati Coffee, we offer a wide range of training services, including SCA and CQI preparation, as well as custom courses offered through Emirati Coffee.') }}</span>
    </div>
    
    <!-- banner 1 -->
    
    <div class="banner-1 text-center justify-content-center institute-section  my-3 py-3">
      <div class="row justify-content-center">
    
        <div class="banner-1-heading text-center py-3">
          <h3>{{ translate('Kindly Select your Academic Preference') }}</h3>
        <span>{{ translate('Coffee Academy is your Gateway to Coffee Education andCertification') }}</span>
        </div>
        <div class="institute-card-wrapper">

        @foreach($institutes as $institute)
        <a href="{{ route('courses.by-institute', $institute->id) }}" style="text-decoration: none; color: inherit;">
        <div class="card institute-card" style="display: flex; justify-content: center; align-items: center; width: 10rem; margin:10px 10px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
          <img src="{{ uploaded_asset($institute->image) }}"  class="card-img-top" alt="{{ $institute->name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
          <div class="card-body align-content-center">
            <p class="card-text">{{ $institute->name }}</p>
          </div>
        </div>
        </a>
        @endforeach
        </div>

      </div>
    </div>
    <!-- banner-1 exit -->
    
    <main class="calendar-container">
        <header class="calendar-header">
            <h1>{{ translate('Book a Training Course') }}</h1>
            <h2>{{ translate('Course Calendar') }}</h2>
        </header>

        <section class="calendar-controls">
            <div class="filter-buttons">
               <div class="dropdown filter-dropdown d-inline-block">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="eventTypeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    EVENT TYPE 
  </button>
  <ul class="dropdown-menu" aria-labelledby="eventTypeDropdown">
    <li><a class="mydropdown-item dropdown-item" href="#" data-type="">{{ translate('All') }}</a></li>
    <li><a class="mydropdown-item dropdown-item" href="#" data-type="Barista">{{ translate('Barista') }}</a></li>
    <li><a class="mydropdown-item dropdown-item" href="#" data-type="Sensory">{{ translate('Sensory') }}</a></li>
    <li><a class="mydropdown-item dropdown-item" href="#" data-type="Brewing">{{ translate('Brewing') }}</a></li>
    <li><a class="mydropdown-item dropdown-item" href="#" data-type="Home Barista">{{ translate('Home Barista') }}</a></li>
  </ul>
</div>

               <div class="dropdown filter-dropdown d-inline-block ms-2">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="venueDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    VENUE 
  </button>
  <ul class="dropdown-menu" aria-labelledby="venueDropdown" id="venueDropdownList">
    <li><a class="mydropdown-item dropdown-item" href="#" data-location="">All</a></li>
    @foreach($locations as $loc)
        <li><a class="mydropdown-item dropdown-item" href="#" data-location="{{ $loc->name }}">{{ $loc->name }}</a></li>
    @endforeach
  </ul>
</div>

            </div>
            <div class="month-navigation">
                <button id="prev-month-btn" class="nav-button" aria-label="Previous Month"><</button>
                <h3 id="month-year-display" class="month-display"></h3>
                <button id="next-month-btn" class="nav-button" aria-label="Next Month">></button>
            </div>
        </section>

        <section class="calendar-grid-wrapper">
            <div class="calendar-grid day-headers">
                <div class="day-header"><span class="long-name">Sunday</span><span class="short-name">{{ translate('Sun') }}</span></div>
                <div class="day-header"><span class="long-name">Monday</span><span class="short-name">{{ translate('Mon') }}</span></div>
                <div class="day-header"><span class="long-name">Tuesday</span><span class="short-name">{{ translate('Tue') }}</span></div>
                <div class="day-header"><span class="long-name">Wednesday</span><span class="short-name">{{ translate('Wed') }}</span></div>
                <div class="day-header"><span class="long-name">Thursday</span><span class="short-name">{{ translate('Thu') }}</span></div>
                <div class="day-header"><span class="long-name">Friday</span><span class="short-name">{{ translate('Fri') }}</span></div>
                <div class="day-header"><span class="long-name">Saturday</span><span class="short-name">{{ translate('Sat') }}</span></div>
            </div>
            <div id="calendar-days-grid" class="calendar-grid">
                <!-- Day cells will be generated by JavaScript -->
            </div>
        </section>
    </main>   </div>
    
    </div>
    <!-- Event Details Modal -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventDetailModalLabel">Event Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-unstyled">
          <li><strong>{{ translate('Course') }}:</strong> <span id="modalEventName"></span></li>
          <li><strong>{{ translate('Course Type') }}:</strong> <span id="modalEventType"></span></li>
          <li><strong>{{ translate('Level') }}:</strong> <span id="modalEventLevel"></span></li>
          <li><strong>{{ translate('Location') }}:</strong> <span id="modalEventLocation"></span></li>
          <li><strong>{{ translate('Date') }}:</strong> <span id="modalEventDate"></span></li>
          <li><strong>{{ translate('Time') }}:</strong> <span id="modalEventTime"></span></li>
        </ul>
        <div class="mt-3">
          <a href="#" id="modalBookLink" class="btn btn-primary" style="display:none;">{{ translate('Book Now') }}</a>
        </div>
      </div>
    </div>
  </div>
</div>

@php
// Map course schedules to calendar events
$calendarEvents = $courseSchedules->map(function($schedule){
    $start = \Carbon\Carbon::parse($schedule->start_time)->format('h:i A');
    $end = \Carbon\Carbon::parse($schedule->end_time)->format('h:i A');
    $course = $schedule->course;
    
    // Determine event type from course module
    $eventType = 'Other';
    if (stripos($course->course_module, 'Barista') !== false) {
        $eventType = 'Barista';
    } elseif (stripos($course->course_module, 'Sensory') !== false) {
        $eventType = 'Sensory';
    } elseif (stripos($course->course_module, 'Brewing') !== false) {
        $eventType = 'Brewing';
    } elseif (stripos($course->course_module, 'Home Barista') !== false) {
        $eventType = 'Home Barista';
    }
    
    return [
        'date'       => $schedule->date->format('Y-m-d'),
        'name'       => $course->course_module . ' (' . substr($schedule->course_level, 0, 4) . '...)',
        'event_type' => $eventType,
        'course_level' => $schedule->course_level,
        'location'   => $course->institute ? $course->institute->name : '-',
        'time'       => $start . ' - ' . $end . ' UTC+5',
        'course_id'  => $course->id,
        'schedule_id' => $schedule->id,
    ];
})->toArray();
@endphp


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var events = @json($calendarEvents);
        let selectedEventType = "";
        let selectedVenue = "";

        // --- EVENT TYPE FILTER LOGIC ---
        document.querySelectorAll('.dropdown-item[data-type]').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                selectedEventType = this.getAttribute('data-type');
                renderCalendar();
                document.getElementById('eventTypeDropdown').innerHTML =
                    (selectedEventType ? selectedEventType : 'EVENT TYPE') + ' <span class="icon"></span>';
            });
        });

        // --- VENUE FILTER LOGIC ---
        document.querySelectorAll('.dropdown-item[data-location]').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                selectedVenue = this.getAttribute('data-location');
                renderCalendar();
                document.getElementById('venueDropdown').innerHTML =
                    (selectedVenue ? selectedVenue : 'VENUE') + ' <span class="icon"></span>';
            });
        });

        // --- DOM ELEMENTS ---
        const monthYearDisplay = document.getElementById('month-year-display');
        const prevMonthBtn = document.getElementById('prev-month-btn');
        const nextMonthBtn = document.getElementById('next-month-btn');
        const calendarDaysGrid = document.getElementById('calendar-days-grid');

        // --- STATE ---
        let currentDate = new Date();

        // --- FUNCTIONS ---
        function renderCalendar() {
            calendarDaysGrid.innerHTML = '';

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            // Update month/year display
            monthYearDisplay.textContent = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(currentDate);

            // Get calendar boundaries
            const firstDayOfMonth = new Date(year, month, 1).getDay();
            const lastDateOfMonth = new Date(year, month + 1, 0).getDate();
            const lastDateOfPrevMonth = new Date(year, month, 0).getDate();

            // 1. Add previous month's trailing days
            for (let i = firstDayOfMonth; i > 0; i--) {
                const day = lastDateOfPrevMonth - i + 1;
                createDayCell(day, true);
            }

            // 2. Add current month's days
            for (let i = 1; i <= lastDateOfMonth; i++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                const dayEvents = events.filter(e =>
                    e.date === dateStr &&
                    (selectedEventType === "" || e.event_type === selectedEventType) &&
                    (selectedVenue === "" || e.location === selectedVenue)
                );
                createDayCell(i, false, dayEvents);
            }

            // 3. Add next month's leading days
            const lastDayOfMonthIndex = new Date(year, month, lastDateOfMonth).getDay();
            const remainingCells = 6 - lastDayOfMonthIndex;
            for (let i = 1; i <= remainingCells; i++) {
                createDayCell(i, true);
            }
        }

        function limitChars(str, maxChars) {
            if (!str) return "";
            if (str.length <= maxChars) return str;
            return str.substring(0, maxChars) + " ...";
        }

        function createDayCell(day, isOtherMonth, dayEvents = []) {
            const cell = document.createElement('div');
            cell.classList.add('day-cell');

            const dayNumber = document.createElement('div');
            dayNumber.classList.add('day-number');
            dayNumber.textContent = day;
            cell.appendChild(dayNumber);

            if (isOtherMonth) {
                cell.classList.add('other-month');
            } else {
                cell.dataset.day = day;
                cell.addEventListener('click', () => {
                    const currentlySelected = calendarDaysGrid.querySelector('.selected');
                    if (currentlySelected) {
                        currentlySelected.classList.remove('selected');
                    }
                    cell.classList.add('selected');
                });
            }

            dayEvents.forEach(event => {
                const eventEl = document.createElement('div');
                eventEl.classList.add('event');
                eventEl.setAttribute('data-type', event.event_type || 'Other');
                
                // Format course name - show module and level
                const courseName = limitChars(event.name, 20);
                
                eventEl.innerHTML = `
                    <span class="event-name">${courseName}</span>
                    <span class="event-time">${event.time || ''}</span>
                `;
                
                // Click to redirect to booking page or show modal
                eventEl.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (event.course_id) {
                        // Redirect directly to booking page
                        window.location.href = '/course/' + event.course_id + '/booking';
                    } else {
                        // Fallback to modal for old events
                        document.getElementById('modalEventName').innerText = event.name || '-';
                        document.getElementById('modalEventType').innerText = event.event_type || '-';
                        const levelEl = document.getElementById('modalEventLevel');
                        if (levelEl) {
                            levelEl.innerText = event.course_level || event.trainer || '-';
                        }
                        document.getElementById('modalEventLocation').innerText = event.location || '-';
                        document.getElementById('modalEventDate').innerText = event.date || '-';
                        document.getElementById('modalEventTime').innerText = event.time || '-';
                        const bookLink = document.getElementById('modalBookLink');
                        if (bookLink && event.course_id) {
                            bookLink.href = '/course/' + event.course_id + '/booking';
                            bookLink.style.display = 'inline-block';
                        } else {
                            if (bookLink) bookLink.style.display = 'none';
                        }
                        var modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                        modal.show();
                    }
                });

                cell.appendChild(eventEl);
            });

            calendarDaysGrid.appendChild(cell);
        }

        // --- EVENT LISTENERS ---
        prevMonthBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        nextMonthBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        // --- INITIAL RENDER ---
        renderCalendar();

        // // --- DROPDOWN CLICK OUTSIDE HIDE ---
        // const dropdownBtn = document.getElementById("dropdownBtn");
        // const dropdownContent = document.getElementById("dropdownContent");

        // document.addEventListener("click", function (event) {
        //     if (!dropdownBtn || !dropdownContent) return;

        //     if (!dropdownBtn.contains(event.target) && !dropdownContent.contains(event.target)) {
        //         dropdownContent.style.display = "none";
        //     }
        // });
    });
</script>
    
<!-- Bootstrap JS with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
        
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
          nav: false,
          dots: false,
          autoplay: true,
          autoplayTimeout: 2000,
          autoplaySpeed: 1000,
          smartSpeed: 1000,
          animateOut: 'fadeOut',
          autoplayHoverPause: true,
          center: true,
          responsive: {
              0: { items: 1 },  
              600: { items: 1 },  
              1000: { items: 1 }  
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
<!--<style>-->
    <!-- .calendar {-->
    <!--        max-width: 800px;-->
    <!--        margin: 20px auto;-->
    <!--        padding: 10px;-->
    <!--        border: 1px solid #ddd;-->
    <!--        border-radius: 8px;-->
    <!--        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);-->
    <!--        background-color: #fff;-->
    <!--    }-->

    <!--    .calendar-header {-->
    <!--        display: flex;-->
    <!--        justify-content: space-between;-->
    <!--        align-items: center;-->
    <!--        margin-bottom: 20px;-->
    <!--    }-->

    <!--    .calendar-header button {-->
    <!--        background-color: #007BFF;-->
    <!--        color: white;-->
    <!--        border: none;-->
    <!--        padding: 10px 15px;-->
    <!--        border-radius: 5px;-->
    <!--        cursor: pointer;-->
    <!--    }-->

    <!--    .calendar-header button:hover {-->
    <!--        background-color: #0056b3;-->
    <!--    }-->

    <!--    .calendar-header h2 {-->
    <!--        margin: 0;-->
    <!--        font-size: 1.5em;-->
    <!--    }-->

    <!--    .calendar-grid {-->
    <!--        display: grid;-->
    <!--        grid-template-columns: repeat(7, 1fr);-->
    <!--        gap: 5px;-->
    <!--    }-->

    <!--    .calendar-grid div {-->
    <!--        text-align: center;-->
    <!--        padding: 10px;-->
    <!--        background-color: #f9f9f9;-->
    <!--        border: 1px solid #ddd;-->
    <!--        border-radius: 4px;-->
    <!--    }-->

    <!--    .calendar-grid .day {-->
    <!--        background-color: #007BFF;-->
    <!--        color: white;-->
    <!--        font-weight: bold;-->
    <!--    }-->

    <!--    .calendar-grid .today {-->
    <!--        background-color: #28a745;-->
    <!--        color: white;-->
    <!--        font-weight: bold;-->
    <!--    }-->

    <!--    @media (max-width: 600px) {-->
    <!--        .calendar-grid {-->
    <!--            grid-template-columns: repeat(3, 1fr);-->
    <!--        }-->
    <!--    }-->
    <!--</style>-->
    
    <!-- banner bottom heading -->
    
        <!--    <div class="banner-1-heading text-center py-3">-->
        <!--  <h3>{{ translate('Book Training Course') }}</h3>-->
        <!--<span>Course Calendar</span>-->
        <!--</div>-->
    
    <!--  <div class="calendar">-->
    <!--    <div class="calendar-header">-->
    <!--        <button id="prevMonth">&#8592; {{ translate('Previous') }}</button>-->
    <!--        <h2 id="monthYear">{{ translate('January 2025') }}</h2>-->
    <!--        <button id="nextMonth">{{ translate('Next') }} &#8594;</button>-->
    <!--    </div>-->
        <!--<div class="calendar-grid" id="calendarGrid">-->
        <!--    <div class="day">{{ translate('Sun') }}</div>-->
        <!--    <div class="day">{{ translate('Mon') }}</div>-->
        <!--    <div class="day">{{ translate('Tue') }}</div>-->
        <!--    <div class="day">{{ translate('Wed') }}</div>-->
        <!--    <div class="day">{{ translate('Thu') }}</div>-->
        <!--    <div class="day">{{ translate('Fri') }}</div>-->
        <!--    <div class="day">{{ translate('Sat') }}</div>-->
            <!-- Calendar days will be injected here dynamically -->
        <!--</div>-->
    <!--</div>-->

    <!--<script>-->
    <!--    const calendarGrid = document.getElementById('calendarGrid');-->
    <!--    const monthYear = document.getElementById('monthYear');-->
    <!--    const prevMonth = document.getElementById('prevMonth');-->
    <!--    const nextMonth = document.getElementById('nextMonth');-->

    <!--    let currentDate = new Date();-->

    <!--    function renderCalendar(date) {-->
    <!--        calendarGrid.innerHTML = `-->
    <!--            <div class="day">Sun</div>-->
    <!--            <div class="day">Mon</div>-->
    <!--            <div class="day">Tue</div>-->
    <!--            <div class="day">Wed</div>-->
    <!--            <div class="day">Thu</div>-->
    <!--            <div class="day">Fri</div>-->
    <!--            <div class="day">Sat</div>-->
    <!--        `;-->

    <!--        const year = date.getFullYear();-->
    <!--        const month = date.getMonth();-->

    <!--        monthYear.textContent = date.toLocaleDateString('en-US', {-->
    <!--            year: 'numeric',-->
    <!--            month: 'long',-->
    <!--        });-->

    <!--        const firstDayOfMonth = new Date(year, month, 1).getDay();-->
    <!--        const daysInMonth = new Date(year, month + 1, 0).getDate();-->

    <!--        for (let i = 0; i < firstDayOfMonth; i++) {-->
    <!--            const emptyCell = document.createElement('div');-->
    <!--            calendarGrid.appendChild(emptyCell);-->
    <!--        }-->

    <!--        for (let day = 1; day <= daysInMonth; day++) {-->
    <!--            const dayCell = document.createElement('div');-->
    <!--            dayCell.textContent = day;-->

    <!--            if (-->
    <!--                day === new Date().getDate() &&-->
    <!--                year === new Date().getFullYear() &&-->
    <!--                month === new Date().getMonth()-->
    <!--            ) {-->
    <!--                dayCell.classList.add('today');-->
    <!--            }-->

    <!--            calendarGrid.appendChild(dayCell);-->
    <!--        }-->
    <!--    }-->

    <!--    prevMonth.addEventListener('click', () => {-->
    <!--        currentDate.setMonth(currentDate.getMonth() - 1);-->
    <!--        renderCalendar(currentDate);-->
    <!--    });-->

    <!--    nextMonth.addEventListener('click', () => {-->
    <!--        currentDate.setMonth(currentDate.getMonth() + 1);-->
    <!--        renderCalendar(currentDate);-->
    <!--    });-->

    <!--    renderCalendar(currentDate);-->
    <!--</script>-->
    
    <!-- banner bottom heading exit -->

