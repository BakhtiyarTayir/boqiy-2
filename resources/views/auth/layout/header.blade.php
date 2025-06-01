<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @php
        $system_name = \App\Models\Setting::where('type', 'system_name')->value('description');
        $system_favicon = \App\Models\Setting::where('type', 'system_fav_icon')->value('description');
    @endphp
    <title>{{ $system_name }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ get_system_logo_favicon($system_favicon,'favicon') }}">
    
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/fontawesome/all.min.css') }}">
    <!-- CSS Library -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/venobox.min.css') }}">
    
    <!-- Style css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/own.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/custom.css') }}">


</head>

<body class="bg-white login">


@php $system_light_logo = \App\Models\Setting::where('type', 'system_light_logo')->value('description'); @endphp

<style>
    @media (max-width: 550px) {
        #only-big {
            display: none !important;
        }
    }
    
    @media (max-width: 510px) {
        .icon-toggle .btn-text {
            display: none;
        }
        
        .icon-toggle i {
            margin-right: 0 !important;
            font-size: 1.25rem;
        }
        
        .icon-toggle {
            justify-content: center;
            width: 48px;
            height: 48px;
            padding: 0;
        }
    }
</style>

<!-- header -->
<header class="header header-default py-3">
    <nav class="navigation">
        <div class="container">
            <div class="row">
                <div class="col-auto col-lg-6">
                    <div class="logo-branding mt-1">
                        <a class="navbar-brand mt-2" id="only-big" style="display: block;" href="@if (Auth::check()) {{route('timeline')}} @endif">
                            @if (1)
                                <img src="{{ get_system_logo_favicon($system_light_logo, 'light') }}"
                                     class="logo_height_width" alt="logo" />
                                <span class="logo-text -ml-3 d-block ml-2 text-uppercase"> Boqiy.uz</span>
                            @endif
                        </a>
                    </div>
                </div>
                
                <div class="col-auto col-lg-6 ms-auto">
                    <div class="login-btns ms-5 d-flex gap-2 flex-wrap">
                        <a href="{{ route('login') }}" class="btn  btn-lg d-flex align-items-center icon-toggle @if( Route::currentRouteName() == 'login') active @endif">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            <span class="btn-text">
                                {{  __('Login') }}
                            </span>
                        </a>
                        
                        @if(get_settings('public_signup') == 1)
                            <a href="{{ route('register') }}" class="btn btn-lgd-flex align-items-center icon-toggle @if( Route::currentRouteName() == 'register') active @endif">
                                <i class="fas fa-user-plus me-2"></i>
                                <span class="btn-text">{{ get_phrase('Register')  }}</span>
                            </a>
                        @endif
                        
                        
                        @if (empty(auth()->user()))
                            <a href="{{ route('no-login') }}"
                               class="btn btn-lg btn-secondary btn-secondary d-flex align-items-center icon-toggle @if (request()->url() == route('no-login')) active @endif ">
                                <i class="fas fa-eye me-2"></i>
                                <span class="btn-text">
                                     {{ get_phrase('View Site') }}
                                </span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
<!-- Header End -->