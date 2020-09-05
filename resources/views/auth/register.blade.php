@extends("front/layout.master") 
@section('title','Register |') 
@section("body")
@php
    require_once(base_path().'/app/Http/Controllers/price.php');
@endphp
<div class="body-content">
    <div class="container">
        <div class="sign-in-page">
            <h4 class="checkout-subtitle">{{ __('staticwords.Createanewaccount') }}</h4>
            <form class="register-form outer-top-xs" role="form" method="POST" action="{{ route('register') }}">
                @csrf
                <!-- create a new account -->
               
               <div class="row">

                  <div class="offset-md-3 col-md-6">
                       <p class="text-success">{{ __('Quick Sign up with') }} :</p>
                       <div class="social-sign-in outer-top-xs">
                        @if($config->fb_login_enable=='1')
                        <a href="{{url('login/facebook')}}" title="{{__('staticwords.SignUpwithFacebook')}}" class="btn btn-outline-primary"><i class="fa fa-facebook"></i> {{__('staticwords.SignUpwithFacebook')}}</a> @endif @if($config->google_login_enable=='1')
                        <a title="{{__('staticwords.SignUpwithGoogle')}}" href="{{url('login/google')}}" class="btn btn-outline-dark"><i class="fa fa-google"></i> {{__('staticwords.SignUpwithGoogle')}}</a> @endif @if(env('ENABLE_GITLAB') == 1 )
                        <a title="{{__('staticwords.SignUpwithGitLab')}}" href="{{url('login/gitlab')}}" class="btn btn-outline-info"><i class="fa fa-gitlab"></i> {{__('staticwords.SignUpwithGitLab')}}</a> @endif
                    </div>
                    <hr>
                  </div>
                    
                  
                   <div class="offset-md-3 col-md-6">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.Name') }}<span>*</span></label>
                            <input name="name" type="text" value="{{ old('name') }}" class="form-control unicase-form-control text-input{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"> @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span> @endif
                        </div>
                   </div>

                    <div class="offset-md-3 col-md-6">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputEmail2">{{ __('staticwords.eaddress') }} <span>*</span></label>
                            <input value="{{ old('email') }}" type="email" class="form-control unicase-form-control text-input {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" required autofocus> 
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('email') }}</strong></span> 
                            @endif
                        </div>
                   </div>

                   <div class="offset-md-3 col-md-6">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.MobileNo') }} <span>*</span></label>
                            <input pattern = "[0-9]+" title="{{ __('Please enter valid mobile no') }}." value="{{ old('mobile') }}" type="text" class="form-control unicase-form-control text-input{{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" id="phone" value="{{ old('address') }}" required> 
                            @if ($errors->has('mobile'))
                              <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('mobile') }}</strong>
                              </span> 
                            @endif
                        </div>
                   </div>

                   <div class="offset-md-3 col-md-6">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.EnterPassword') }} <span>*</span></label>
                            <input type="password" id="password" class="form-control unicase-form-control text-input{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required> @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span> @endif
                        </div>
                   </div>

                   <div class="offset-md-3 col-md-6">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.ConfirmPassword') }} <span>*</span></label>
                            <input type="password" name="password_confirmation" id="password-confirm" class="form-control unicase-form-control text-input" required/>
                        </div>
                   </div>

                <div class="offset-md-3 col-md-6">
                    <button type="submit" class="btn-upper btn btn-primary checkout-page-button">{{ __('staticwords.Register') }}</button>
                    <a class="float-right" href="{{ route('login') }}">{{ __('Already have account login here?') }}</a>
                </div>


               </div>
                
            </form>

        </div>
    </div>
</div>
<!-- /.body-content -->
@endsection 
@section('script')
<script>
    var baseurl = "<?= url('/') ?>";
</script>
<script src="{{ url('js/ajaxlocationlist.js') }}"></script>

@endsection