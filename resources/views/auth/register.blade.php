@include('auth.layout.header')


<!-- Main Start -->
<main class="main my-4 p-5">
    <div class="container register-page">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="login-img">
                    <img class="img-fluid" src="{{ asset('assets/frontend/images/login.png') }} " alt="">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="login-txt ms-0 ms-lg-5">
                    <h3>{{get_phrase('Register')}}</h3>
                    
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-group form-name">
                            <label for="name">{{ get_phrase('Full Name') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="{{get_phrase('Your full name')}}">
                        </div>
                        <p class="text-danger">{{ $errors->first('name') }}</p>
                        
                        @if (0)
                            <div class="form-group form-email">
                                <label for="email">{{get_phrase('Email')}}</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="{{get_phrase('Enter your email address')}}">
                            </div>
                            <p class="text-danger">{{ $errors->first('email') }}</p>
                        @endif
                        
                        <div class="form-group form-phone">
                            <label for="phone">{{ get_phrase('Phone') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+998</span>
                                </div>
                                <input type="tel" class="form-control" id="phone" name="phone" maxlength="9" placeholder="XX XXX XX XX" autocomplete="off" required>
                            </div>
                        </div>
                        <p class="text-danger">{{ $errors->first('phone') }}</p>
                        
                        @if (!empty(session('errorType')) && session('errorType') == 'hasPhone')
                            <p class="text-danger">{{ session('errorText') }}</p>
                        @endif
                        
                        <div class="form-group form-pass">
                            <label for="password">{{ get_phrase('Password') }}</label>
                            <input type="password" id="password" autocomplete="new-password" name="password" placeholder="********">
                        </div>
                        <p class="text-danger">{{ $errors->first('password') }}</p>
                        
                        @if (0)
                            <input type="hidden" name="timezone" id="timezone" value="">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="check1" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">{{get_phrase('I accept the')}}
                                    <a href="{{ route('term.view') }}">{{get_phrase('Terms and Conditions')}}</a></label>
                            </div>
                        @endif
                        
                        <input class="btn btn-primary my-3" type="submit" name="submit" id="submit" value="{{ get_phrase('Register') }}">
                    </form>
                
                </div>
            </div>
        </div>
    
    </div> <!-- container end -->
</main>
<!-- Main End -->



@include('auth.layout.footer')