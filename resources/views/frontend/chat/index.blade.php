<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    
    @php
        $system_name = \App\Models\Setting::where('type', 'system_name')->value('description');
        $system_favicon = \App\Models\Setting::where('type', 'system_fav_icon')->value('description');
    @endphp
    <title>{{ $system_name }}</title>

    <!-- CSRF Token for ajax for submission -->
    <meta name="csrf_token" content="{{ csrf_token() }}" />

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ get_system_logo_favicon($system_favicon,'favicon') }}" />

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}">
    <!-- CSS Library -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/venobox.min.css')}}">

    <!-- Style css -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/plyr/plyr.css')}}">
    <link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
    <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet">
    <link href="{{asset('assets/frontend/uploader/jquery.uploader.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('assets/frontend/css/own.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/fundraiser/css/custom_new.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/fundraiser/css/emojionearea.css') }}" />

    <script src="{{asset('assets/frontend/js/jquery-3.6.0.min.js')}}"></script>
    


</head>
    @if(Session::get('theme_color'))
    @php
        $theme_color = Session::get('theme_color');
        if ($theme_color === 'dark') {
            $image = asset('assets/frontend/images/white_sun.svg');
        } else {
        
            $image = asset('assets/frontend/images/white_moon.svg');
        }
    @endphp
    @else
    @php
        $theme_color = 'default';
        $image = asset('assets/frontend/images/white_moon.svg');
    @endphp
    @endif

@php
    $themeColor = App\Models\Setting::where('type', 'theme_color')->value('description');
@endphp
<body class="{{$themeColor}}">
    @php $user_info = Auth()->user() @endphp
    
 	@include('frontend.header')

    <!-- Main Start -->
    <main class="main my-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    @include('frontend.chat.chated')
                </div>
                <div class="col-lg-8">
                    @include('frontend.chat.chat')
                </div>
            </div> <!-- row end -->

        </div> <!-- container end -->
    </main>
    <!-- Main End -->

    <!-- Common modals -->
    @include('frontend.modal')

    <!--Javascript
    ========================================================-->
    <script src="{{asset('assets/frontend/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/venobox.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/timepicker.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/jquery.datepicker.min.js')}}"></script>

   
    <script src="{{asset('assets/frontend/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{asset('assets/frontend/plyr/plyr.js')}}"></script>
    <script src="{{asset('assets/frontend/jquery-form/jquery.form.min.js')}}"></script>

    <script src="{{asset('assets/frontend/leafletjs/leaflet.js')}}"></script>
    <script src="{{asset('assets/frontend/leafletjs/leaflet-search.js')}}"></script>
    <script src="{{asset('assets/frontend/toaster/toaster.js')}}"></script>

    <script src="{{asset('assets/frontend/emojionarea/emojionearea.min.js')}}"></script>

    <script src="{{ asset('js/share.js') }}"></script>

    <script src="{{asset('assets/frontend/uploader/jquery.uploader.min.js')}}"></script>


    
    <script src="{{asset('assets/frontend/js/initialize.js')}}"></script>

    <script src="{{asset('assets/frontend/js/custom.js')}}"></script>
    

   

    @include('frontend.common_scripts')
    
    @include('frontend.toaster')

    @include('frontend.initialize')
    


    <script>
        $("document").ready(function(){
            var dark = document.getElementById('dark');
            var storedThemeColor = sessionStorage.getItem('theme_color'); 
            if (storedThemeColor) {
                document.body.classList.add(storedThemeColor); 
            }
    
            dark.onclick = function(){
                document.body.classList.toggle('dark');
                var themeColor = document.body.classList.contains('dark') ? 'dark' : 'default';
                var url = "<?php echo route('update-theme-color') ?>";
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: { 
                        themeColor: themeColor
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        sessionStorage.setItem('theme_color', themeColor);
                        if (themeColor === 'dark') {
                            $('#dark').attr('src', '{{ asset("assets/frontend/images/white_sun.svg") }}');
                    } else {
                        
                        $('#dark').attr('src', '{{ asset("assets/frontend/images/white_moon.svg") }}');
                    }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating theme color:', error);
                    }
                });
                return false;
            }
        });
    </script>
    
</body>

</html>