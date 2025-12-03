@extends('frontend.layouts.app')

@section('content')

<style>
    .booking-page {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 60px 0;
    }
    
    .booking-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .booking-wrapper {
        display: flex;
        gap: 40px;
        align-items: flex-start;
        border-radius: 16px;
        padding: 40px;
    }
    
    /* Left Section - Logo */
    .logo-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: 40px 0;
    }
    
    /* Main Image Styles */
    .course-main-image-wrapper {
        width: 100%;
        max-width: 500px;
        margin: 0 auto 24px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        background: #f8f9fa;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .course-main-image {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: cover;
        display: block;
    }
    
    /* Thumbnail Carousel Styles */
    .course-thumbnail-carousel-wrapper {
        width: 100%;
        max-width: 500px;
        margin: 0 auto 30px;
        padding: 10px 0;
    }
    
    .course-thumbnail-carousel {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        overflow-x: auto;
        padding: 10px 0;
        scrollbar-width: thin;
        scrollbar-color: #3b82f6 #f0f0f0;
    }
    
    .course-thumbnail-carousel::-webkit-scrollbar {
        height: 6px;
    }
    
    .course-thumbnail-carousel::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 10px;
    }
    
    .course-thumbnail-carousel::-webkit-scrollbar-thumb {
        background: #9d4877;
        border-radius: 10px;
    }
    
    .thumbnail-item {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid #e0e0e0;
        transition: all 0.2s ease;
        flex-shrink: 0;
        background: #f8f9fa;
    }
    
    .thumbnail-item:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.2);
    }
    
    .thumbnail-item.active {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .thumbnail-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    .description-heading {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
        margin-top: 0;
    }
    
    .course-description {
        margin-bottom: 32px;
        text-align: left;
        color: #4a4a4a;
        font-size: 0.95rem;
        line-height: 1.7;
    }
    
    .course-description h1,
    .course-description h2,
    .course-description h3,
    .course-description h4,
    .course-description h5,
    .course-description h6 {
        color: #1a1a1a;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }
    
    .course-description p {
        margin-bottom: 1rem;
    }
    
    .course-description ul,
    .course-description ol {
        margin-left: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .course-description a {
        color: #3b82f6;
        text-decoration: none;
    }
    
    .course-description a:hover {
        text-decoration: underline;
    }
    

    
    /* Right Section - Booking Form */
    .booking-section {
        flex: 1;
        max-width: 500px;
    }
    
    .booking-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 32px;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #4a4a4a;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-select {
        width: 100%;
        padding: 14px 16px;
        border: 1.5px solid #e5e5e5;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: white;
        color: #333;
        cursor: pointer;
    }
    
    .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-select:hover {
        border-color: #d0d0d0;
    }
    
    /* Calendar Styles */
    .calendar-widget {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .calendar-month {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
        letter-spacing: 0.3px;
    }
    
    .calendar-nav {
        display: flex;
        gap: 4px;
    }
    
    .calendar-nav-btn {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.85rem;
        color: #6b7280;
    }
    
    .calendar-nav-btn:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
    }
    
    .calendar-day-header {
        text-align: center;
        font-size: 0.7rem;
        font-weight: 600;
        color: #9ca3af;
        padding: 8px 0 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.15s ease;
        background: transparent;
        border: 1px solid transparent;
        color: #374151;
        font-weight: 500;
    }
    
    .calendar-day:hover:not(.other-month):not(.selected) {
        background: #eff6ff;
        color: #3b82f6;
    }
    
    .calendar-day.other-month {
        color: #d1d5db;
        cursor: default;
        background: transparent;
    }
    
    .calendar-day.selected {
        background: #3b82f6;
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }
    
    .calendar-day.today {
        background: #eff6ff;
        color: #3b82f6;
        font-weight: 600;
        border: 1px solid #bfdbfe;
    }
    
    .calendar-day.today.selected {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    /* Price Section */
    .price-section {
        margin: 24px 0;
        padding: 20px 24px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 1px solid #e5e5e5;
        display: flex;
        gap: 12px;
    }
    
    .price-label {
        font-size: 0.75rem;
        color: #888;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .price-value {
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1.2;
    }
    
    /* Buttons */
    .btn-select-date {
        width: 100%;
        padding: 16px 24px;
        border-radius: 10px;
        border: none;        
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 20px;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
    
    .btn-select-date:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .btn-select-date:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* Location Section */
 
    
    /* Thumbnail Logo */
    .logo-thumbnail {
        position: fixed;
        bottom: 20px;
        left: 20px;
        width: 80px;
        height: 80px;
        background: #9d4877;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 100;
        overflow: hidden;
    }
    
    .logo-thumbnail svg {
        width: 50px;
        height: 50px;
        color: white;
    }
    
    .logo-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
    }
    
    @media (max-width: 968px) {
        .booking-page {
            padding: 30px 0;
        }
        
        .booking-wrapper {
            flex-direction: column;
            padding: 30px 20px;
            gap: 30px;
        }
        
        .logo-section {
            padding: 0;
        }
        
        .booking-section {
            max-width: 100%;
        }
        
        .course-main-image-wrapper {
            max-width: 100%;
        }
        
        .course-thumbnail-carousel-wrapper {
            max-width: 100%;
        }
    }
</style>

<div class="booking-page">
    <div class="booking-container">
        <div class="booking-wrapper">
            <!-- Left Section - Course Image Gallery -->
            <div class="logo-section">
                <!-- Main Image -->
                <div class="course-main-image-wrapper">
                    <img id="main-course-image" src="{{ $course->image ? uploaded_asset($course->image) : static_asset('assets/img/placeholder.jpg') }}" alt="{{ $course->course_module }}" class="course-main-image" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div>
                
                <!-- Thumbnail Carousel -->
                <div class="course-thumbnail-carousel-wrapper">
                    <div class="course-thumbnail-carousel" id="courseThumbnailCarousel">
                        @if($course->image)
                            <div class="thumbnail-item active" data-image="{{ uploaded_asset($course->image) }}">
                                <img src="{{ uploaded_asset($course->image) }}" alt="{{ $course->course_module }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </div>
                        @else
                            <div class="thumbnail-item active" data-image="{{ static_asset('assets/img/placeholder.jpg') }}">
                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $course->course_module }}">
                            </div>
                        @endif
                        <!-- Schedule images will be dynamically added here -->
                    </div>
                </div>
                


            </div>
            
            <!-- Right Section - Booking Form -->
            <div class="booking-section">
                <h2 class="description-heading">{{ $course->course_module }}</h2>
                @if($course->description)
                    <div class="course-description">
                        {!! $course->description !!}
                    </div>
                @endif
                <!-- Course Level Dropdown -->
                <div class="form-group">
                    <label class="form-label">{{ translate('Course Level') }}</label>
                    <select class="form-select" id="courseLevel" required>
                        <option value="">{{ translate('Select Course Level') }}</option>
                        @foreach($availableLevels as $level)
                            <option value="{{ $level }}">{{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Calendar Widget -->
                <div class="calendar-widget">
                    <div class="calendar-header">
                        <div class="calendar-month" id="calendarMonth"></div>
                        <div class="calendar-nav">
                            <button class="calendar-nav-btn" id="prevMonth">‹</button>
                            <button class="calendar-nav-btn" id="nextMonth">›</button>
                        </div>
                    </div>
                    <div class="calendar-grid" id="calendarGrid"></div>
                </div>
                
                <!-- Time Selection (will be dynamically added here) -->
                <div id="timeSelectGroup"></div>
                
                <!-- Price Section -->
                <div class="price-section">
                    <div class="price-label">{{ translate('Price') }}:</div>
                    <div class="price-value" id="coursePrice">{{ translate('Select level to see price') }}</div>
                </div>
                
                <!-- Select Date Button -->
                <button class="btn-select-date btn-primary" id="selectDateBtn" disabled>
                    {{ translate('Add to Cart') }}
                    {{ translate('Select date') }}
                </button>
                

            </div>
        </div>
    </div>
    

</div>

<script>
let selectedDate = null;
let selectedLevel = null;
let selectedTime = null;
let currentDate = new Date();

// Available schedules data from backend
const availableDates = @json($availableDatesArray);
const schedulesByDate = @json($schedulesByDate);

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                       'July', 'August', 'September', 'October', 'November', 'December'];
    
    document.getElementById('calendarMonth').textContent = `${monthNames[month]} ${year}`;
    
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    
    const grid = document.getElementById('calendarGrid');
    grid.innerHTML = '';
    
    // Day headers
    const dayHeaders = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
    dayHeaders.forEach(day => {
        const header = document.createElement('div');
        header.className = 'calendar-day-header';
        header.textContent = day;
        grid.appendChild(header);
    });
    
    // Previous month days
    for (let i = firstDay - 1; i >= 0; i--) {
        const day = document.createElement('div');
        day.className = 'calendar-day other-month';
        day.textContent = daysInPrevMonth - i;
        grid.appendChild(day);
    }
    
    // Current month days
    for (let i = 1; i <= daysInMonth; i++) {
        const day = document.createElement('div');
        day.className = 'calendar-day';
        day.textContent = i;
        
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const cellDate = new Date(year, month, i);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        cellDate.setHours(0, 0, 0, 0);
        
        // Check if date is available
        const isAvailable = availableDates.includes(dateStr);
        
        if (!isAvailable) {
            day.classList.add('other-month');
            day.style.opacity = '0.3';
            day.style.cursor = 'not-allowed';
        } else {
            // Highlight today
            if (cellDate.getTime() === today.getTime()) {
                day.classList.add('today');
            }
            
            day.addEventListener('click', () => {
                if (isAvailable) {
                    document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                    day.classList.add('selected');
                    selectedDate = dateStr;
                    updateTimeOptions(dateStr);
                    checkBookingReady();
                }
            });
        }
        
        grid.appendChild(day);
    }
    
    // Next month days
    const totalCells = grid.children.length;
    const remainingCells = 42 - totalCells;
    for (let i = 1; i <= remainingCells; i++) {
        const day = document.createElement('div');
        day.className = 'calendar-day other-month';
        day.textContent = i;
        grid.appendChild(day);
    }
}

function updateTimeOptions(dateStr) {
    const dateSchedule = schedulesByDate.find(s => s.date === dateStr);
    let timeGroup = document.getElementById('timeSelectGroup');
    
    // Create or update time select group
    if (!timeGroup) {
        timeGroup = document.createElement('div');
        timeGroup.className = 'form-group mb-3';
        timeGroup.id = 'timeSelectGroup';
        // Insert after calendar widget
        document.querySelector('.calendar-widget').after(timeGroup);
    }
    
    // Set the HTML content
    timeGroup.innerHTML = `
        <label class="form-label">{{ translate('Select Time') }} <span class="text-danger">*</span></label>
        <select class="form-select" id="timeSelect" required>
            <option value="">{{ translate('Select Time') }}</option>
        </select>
    `;
    
    const timeSelect = document.getElementById('timeSelect');
    selectedTime = null;
    
    if (dateSchedule && dateSchedule.schedules && dateSchedule.schedules.length > 0) {
        // Filter by selected level if any
        const selectedLevelValue = document.getElementById('courseLevel').value;
        const filteredSchedules = selectedLevelValue 
            ? dateSchedule.schedules.filter(s => s.course_level === selectedLevelValue)
            : dateSchedule.schedules;
        
        if (filteredSchedules.length > 0) {
            filteredSchedules.forEach(schedule => {
                const option = document.createElement('option');
                option.value = schedule.id;
                // Format time for display
                const startTime = formatTime(schedule.start_time);
                const endTime = formatTime(schedule.end_time);
                option.textContent = startTime + ' - ' + endTime;
                option.dataset.level = schedule.course_level;
                option.dataset.price = schedule.price;
                timeSelect.appendChild(option);
            });
            
        // Add event listener for time selection
        timeSelect.addEventListener('change', function() {
            selectedTime = this.value;
            
            // Update level if time is selected and level not selected
            if (this.selectedOptions[0]) {
                const level = this.selectedOptions[0].dataset.level;
                const price = this.selectedOptions[0].dataset.price;
                
                if (!selectedLevel && level) {
                    document.getElementById('courseLevel').value = level;
                    selectedLevel = level;
                }
                
                // Update price display
                if (price) {
                    const priceValue = parseFloat(price);
                    document.getElementById('coursePrice').textContent = 'Dhs. ' + priceValue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            }
            checkBookingReady();
        });
        } else {
            timeSelect.innerHTML = '<option value="">{{ translate("No times available for selected level") }}</option>';
        }
    } else {
        timeSelect.innerHTML = '<option value="">{{ translate("No times available for this date") }}</option>';
    }
}

document.getElementById('courseLevel').addEventListener('change', function() {
    selectedLevel = this.value;
    updatePrice();
    if (selectedDate) {
        updateTimeOptions(selectedDate);
    }
    checkBookingReady();
});

function updatePrice() {
    const priceElement = document.getElementById('coursePrice');
    
    // If time is selected, use that price (most accurate)
    if (selectedTime) {
        const timeSelect = document.getElementById('timeSelect');
        if (timeSelect && timeSelect.selectedOptions[0] && timeSelect.selectedOptions[0].dataset.price) {
            const price = parseFloat(timeSelect.selectedOptions[0].dataset.price);
            priceElement.textContent = 'Dhs. ' + price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            return;
        }
    }
    
    // Otherwise, find price for selected level from any available schedule
    if (!selectedLevel) {
        priceElement.textContent = '{{ translate("Select level to see price") }}';
        return;
    }
    
    let price = null;
    schedulesByDate.forEach(dateGroup => {
        if (!price) {
            const schedule = dateGroup.schedules.find(s => s.course_level === selectedLevel);
            if (schedule && schedule.price) {
                price = schedule.price;
            }
        }
    });
    
    if (price) {
        priceElement.textContent = 'Dhs. ' + parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    } else {
        priceElement.textContent = '{{ translate("Price not available") }}';
    }
}

function formatTime(timeStr) {
    // Convert 24-hour format to 12-hour format
    const [hours, minutes] = timeStr.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return hour12 + ':' + minutes + ' ' + ampm;
}

function checkBookingReady() {
    const btn = document.getElementById('selectDateBtn');
    if (selectedDate && selectedLevel && selectedTime) {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}

document.getElementById('prevMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

document.getElementById('nextMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

document.getElementById('selectDateBtn').addEventListener('click', () => {
    if (selectedDate && selectedLevel && selectedTime) {
        // Add course to cart via AJAX
        const btn = document.getElementById('selectDateBtn');
        btn.disabled = true;
        btn.textContent = '{{ translate("Adding to cart...") }}';
        
        $.ajax({
            url: '{{ route("cart.addCourseToCart") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                course_id: {{ $course->id }},
                course_schedule_id: selectedTime,
                selected_date: selectedDate,
                selected_time: document.getElementById('timeSelect').selectedOptions[0].textContent,
                selected_level: selectedLevel
            },
            success: function(response) {
                if (response.status == 1) {
                    // Show success message and redirect to cart
                    alert(response.message || '{{ translate("Course added to cart successfully") }}');
                    window.location.href = '{{ route("cart") }}';
                } else {
                    if (response.redirect) {
                        // Redirect to login
                        window.location.href = response.redirect;
                    } else {
                        alert(response.message || '{{ translate("Failed to add course to cart") }}');
                        btn.disabled = false;
                        btn.textContent = '{{ translate("Add to Cart") }}';
                    }
                }
            },
            error: function(xhr) {
                let message = '{{ translate("Failed to add course to cart") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
                btn.disabled = false;
                btn.textContent = '{{ translate("Add to Cart") }}';
            }
        });
    } else {
        alert('{{ translate("Please select date, level, and time") }}');
    }
});

function shareCourse() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $course->course_module }}',
            text: '{{ translate("Check out this course") }}',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('{{ translate("Link copied to clipboard") }}');
    }
}

// Initialize calendar
renderCalendar();

// Gallery now only shows course image, not schedule images

// Thumbnail carousel functionality
document.addEventListener('DOMContentLoaded', function() {
    const thumbnailCarousel = document.getElementById('courseThumbnailCarousel');
    const mainImage = document.getElementById('main-course-image');
    
    // Delegate click events to thumbnails
    thumbnailCarousel.addEventListener('click', function(e) {
        const thumbnailItem = e.target.closest('.thumbnail-item');
        if (thumbnailItem) {
            // Remove active class from all thumbnails
            thumbnailCarousel.querySelectorAll('.thumbnail-item').forEach(thumb => thumb.classList.remove('active'));
            
            // Add active class to clicked thumbnail
            thumbnailItem.classList.add('active');
            
            // Update main image
            const newImageSrc = thumbnailItem.getAttribute('data-image');
            if (newImageSrc && mainImage) {
                mainImage.src = newImageSrc;
            }
        }
    });
});
</script>

@endsection
