@extends('frontend.layouts.app')

@section('content')

<style>
    .courses-hero {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        color: white;
        padding: 50px 0 40px;
        text-align: center;
    }
    
    .courses-hero h1 {
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }
    
    .courses-hero p {
        font-size: 1rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .courses-container {
        background: #f8f9fa;
        padding: 40px 0;
        min-height: 60vh;
    }
    
    .module-section {
        margin-bottom: 40px;
    }
    
    .module-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .module-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    
    .module-header p {
        font-size: 0.95rem;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .course-module-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }
    
    .course-module-card {
        background: white;
        padding: 0;
        transition: all 0.3s ease;
        position: relative;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
        overflow: hidden;
        max-width:300px;
    }
    
    .course-module-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: inherit;
    }
    
    .card-image-section {
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: #f0f0f0;
        position: relative;
    }
    
    .card-image-section img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .card-header-section {
        display: flex;
        align-items: center;
        padding: 20px;
        position: relative;
        min-height: 100px;
    }
    
    .module-icon-circle {
        width: 70px;
        height: 70px;
        background: #000;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }
    
    .module-icon-circle i {
        font-size: 2rem;
        color: white;
        font-weight: 300;
    }
    
    .diagonal-line {
        position: absolute;
        left: 70px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #000;
        transform: rotate(15deg);
        transform-origin: top left;
        z-index: 1;
    }
    
    .card-title-section {
        flex: 1;
        padding-left: 30px;
        position: relative;
        z-index: 2;
    }
    
    .card-title-main {
        font-size: 0.95rem;
        font-weight: 700;
        color: #000;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.3;
        margin: 0;
    }
    
    .card-body-section {
        padding: 16px 20px 20px;
        border-top: 1px solid #e5e5e5;
    }
    
    .card-subtitle {
        font-size: 0.9rem;
        color: #000;
        font-weight: 400;
        margin: 0;
    }
    
    .no-courses {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }
    
    .no-courses i {
        font-size: 5rem;
        margin-bottom: 20px;
        color: #ccc;
    }
    
    .no-courses h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 10px;
    }
    
    .no-courses p {
        color: #666;
        font-size: 1.1rem;
    }
    
    .back-button {
        text-align: center;
        margin-top: 40px;
    }
    
    .btn-back {
        background: white;
        color: #1a1a1a;
        padding: 12px 30px;
        border-radius: 8px;
        border: 2px solid #e5e5e5;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        text-decoration: none;
    }
    
    @media (max-width: 768px) {
        .courses-hero h1 {
            font-size: 2rem;
        }
        
        .courses-hero p {
            font-size: 1rem;
        }
        
        .module-header h2 {
            font-size: 1.8rem;
        }
        
        .course-module-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
</style>

<div class="courses-hero">
    <div class="container">
        <h1>{{ $institute->name }}</h1>
        <p>{{ translate('Select a course module to continue your coffee education journey') }}</p>
    </div>
</div>

<div class="courses-container">
    <div class="container">
        @php
            $totalCourses = 0;
            foreach($courses as $moduleCourses) {
                $totalCourses += $moduleCourses->count();
            }
        @endphp
        
        @if($courses && $totalCourses > 0)
            @foreach($courses as $module => $moduleCourses)
                <div class="module-section">
                    <div class="module-header">
                        <h2>{{ $module }}</h2>
                        <p>{{ translate('Choose your level and start learning') }}</p>
                    </div>
                    
                    <div class="course-module-grid">
                        @foreach($moduleCourses as $course)
                            @php
                                $levels = $course->availableSchedules->groupBy('course_level');
                                $minPrice = $course->availableSchedules->min('price');
                                $maxPrice = $course->availableSchedules->max('price');
                            @endphp
                            
                            <a href="{{ route('course.booking', $course->id) }}" class="course-module-card">
                                <div class="card-image-section">
                                    @if($course->image)
                                        <img src="{{ uploaded_asset($course->image) }}" alt="{{ $course->course_module }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    @else
                                        <div style="width: 100%; height: 100%; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                            <i class="las la-image" style="font-size: 3rem; color: #ccc;"></i>
                                        </div>
                                    @endif
                                </div>
                                

                                
                                <div class="card-body-section">
                                    <p class="card-subtitle">{{ $course->course_module }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-courses">
                <i class="las la-book-open"></i>
                <h3>{{ translate('No courses available') }}</h3>
                <p>{{ translate('There are no courses available for this institute at the moment.') }}</p>
            </div>
        @endif
        
        <div class="back-button">
            <a href="{{ route('academy') }}" class="btn-back">
                <i class="las la-arrow-left"></i> {{ translate('Back to Academy') }}
            </a>
        </div>
    </div>
</div>

@endsection
