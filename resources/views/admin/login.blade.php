<!DOCTYPE html>
<html lang="{{ Session::get('changed_language') }}">
<head>
<meta charset="UTF-8">
<title>{{ config('app.name') }} | {{ __('Admin') }} {{ __('staticwords.Login') }}</title>
<link rel="icon" href="{{url('images/genral/'.$fevicon)}}" type="image/png" sizes="16x16">
<link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
  <!-- Ionicons -->
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,600,700">
<link rel="stylesheet" href="{{url('admin/vendor/Ionicons/css/ionicons.min.css')}}">
<link rel="stylesheet" href="{{url('css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{url('front/css/main.css')}}">
<link rel="stylesheet" href="{{url('front/css/blue.css')}}">
<link rel="stylesheet" href="{{url('admin/vendor/dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{ url('vendor/mckenziearts/laravel-notify/css/notify.css') }}">
</head>
<body>
    <!-- ================================= -->
  <!-- ===== Login block ===== -->
<!--  ================================= -->
    <section id="login-block" class="login-main-block-one">
      <div class="container-fluid">
        <div class="row">
          <div class="offset-xl-4 col-xl-4 offset-lg-1 col-lg-10 offset-1 col-10 offset-md-1 col-md-10">
            <div class="login-dtl-block">
              <div class="logo-icon">
              @if($image = @file_get_contents('images/genral/'.$fevicon))
                <img src="{{url('images/genral/'.$fevicon)}}" alt="Favicon"/>
              @else
                <img src="{{ Avatar::create(config('app.name'))->toBase64()}}" alt="No Image"/>
              @endif
              </div>
              <div class="login-heading">
                {{ __('Admin Login') }}
              </div>
              <div class="login-heading-dtl"> {{ __('staticwords.Signintostartyoursession') }}</div>
            <form action="{{ route('admin.login') }}" method="post">
                @csrf
              <div class="login-form">
                    <label for="uname">{{ __('staticwords.Email') }}</label>
                     <input value="{{ old('email') }}" type="email" name="email" class="@error('email') is-invalid @enderror form-control" placeholder="{{ __('staticwords.Email') }}">
                     <div class="user-icon"><i class="fa fa-user"></i>
                      </div>
                      @error('email')
                        <span class="invalid-feedback text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                </div>  
                <div class="login-form">
                    <label for="psw">{{ __('staticwords.Password') }}</label>
                    <input name="password" type="password" class="form-control" placeholder="{{ __('staticwords.Password') }}">
                  <div class="user-icon"><i class="fa fa-lock"></i></div>
              </div>
                  <div class="row login-block-one">
                    <div class="col-md-6 login-checkbox">
                    <label class="switch">
                      <input name="remember" {{ old('remember') ? 'checked' : '' }} type="checkbox" checked>
                      <span class="slider round"></span>
                  </label>
                  <div class="remember-switch">{{ __('staticwords.Rememberme') }} </div>
                  </div>
                  <div class="col-md-6">
                     <div class="forgot-password-block"><a href="{{ route('password.request') }}" title="forgot password"><i class="fa fa-lock"></i> {{ __('staticwords.ForgetPassword') }}</a></div>
                    </div> 
                </div>
                <button type="submit" class="btn btn-primary">{{ __('staticwords.Signin') }} <i class="fa fa-sign-in"></i></button>
                </form>
              </div>
          </div>
        </div>
      </div>
    </section>
<!--  ================================= -->
  <!-- ===== Login block end ===== -->
<!--  ================================= -->
@include('notify::messages')
</body>


<script src="{{ url('vendor/mckenziearts/laravel-notify/js/notify.js') }}"></script>

</html>