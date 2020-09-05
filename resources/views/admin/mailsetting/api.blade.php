@extends("admin.layouts.master")
@section('title',"Stripe & PayPal Setting |")
@section("body")
  <div class="box">
    <div class="box-header with-border">
      <div class="box-title">
        Paypal and Stripe Settings
      </div>
    </div>

    <div class="box-body">
          <form action="{{ route('api.change') }}" method="POST">
    {{ csrf_field() }}
    
   
       <div class="panel panel-default">
      <div class="panel-heading">
        <label>Stripe Payment Settings</label>
        <div class="pull-right panel-title"><a target="__blank" title="Get Your Keys From here" href="https://stripe.com/docs/development"><i class="fa fa-key" aria-hidden="true"></i> Get Your Keys From here</a></div>
      </div>

      <div class="panel-body">
        
        <div id="skey" class="form-group {{ $config->stripe_enable==0 ? 'display-none' : ''}}">
           <label for="MAIL_FROM_NAME">STRIPE KEY :</label>
           <input type="text" name="STRIPE_KEY" value="{{ $env_files['STRIPE_KEY'] }}"  class="form-control">
           <small class="text-muted"><i class="fa fa-question-circle"></i> Enter your Stripe Key</small>
        </div>
        

          <div id="sst" class="form-group eyeCy {{ $config->stripe_enable==0 ? 'display-none' : ''}}">
              <label for="MAIL_FROM_ADDRESS">STRIPE SECRET :</label>
               <input type="password" name="STRIPE_SECRET" value="{{ $env_files['STRIPE_SECRET'] }}" class="form-control" id="strip_sec">
             <span toggle="#strip_sec" class="eye fa fa-fw fa-eye field-icon toggle-password"></span>
             <small class="text-muted"><i class="fa fa-question-circle"></i> Enter your Stripe Secret Key</small>
          </div>

             <input {{ $config->stripe_enable==1 ? "checked" :"" }} name="strip_check" id="toggle1" type="checkbox" class="tgl tgl-skewed">
        <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle1"></label>
        <small class="help-block">(Enable it For Strip Payment Gateway )</small>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <label>Paypal Payment Settings</label>
        <div class="pull-right panel-title"><a target="__blank" title="Get Your Keys From here" href="https://developer.paypal.com/home/"><i class="fa fa-key" aria-hidden="true"></i> Get Your Keys From here</a></div>
      </div>

      <div class="panel-body">
        <div id="pkey" class="form-group {{ $config->paypal_enable==0 ? 'display-none' : ""}}">
           <label for="PAYPAL_CLIENT_ID">PAYPAL CLIENT ID :</label>
            <input type="text" name="PAYPAL_CLIENT_ID" value="{{ $env_files['PAYPAL_CLIENT_ID'] }}" class="form-control">
            <small class="text-muted"><i class="fa fa-question-circle"></i> Enter your PAYPAL CLIENT ID</small>
        </div>

       <div id="psec" class="form-group eyeCy {{ $config->paypal_enable==0 ? 'display-none' : ""}}">
             <label for="PAYPAL_SECRET">PAYPAL SECRET ID :</label>
          <input type="password" value="{{ $env_files['PAYPAL_SECRET'] }}" name="PAYPAL_SECRET" id="pps" class="form-control" id="paypl_secret">
         
            <span toggle="#pps" class="eye fa fa-fw fa-eye field-icon toggle-password"></span>
            <small class="text-muted"><i class="fa fa-question-circle"></i> Enter your PAYPAL SECRET ID</small>
          </div>

          <div id="pmode" class="form-group {{ $config->paypal_enable==0 ? 'display-none' : ""}}">
            <label for="MAIL_ENCRYPTION">PAYPAL MODE :</label>
            <input type="text" value="{{ $env_files['PAYPAL_MODE'] }}" name="PAYPAL_MODE" class="form-control">
             <small class="text-muted"><i class="fa fa-question-circle"></i> For Live use <b>live</b> and for Test use <b>test</b> as mode</small>
          </div>

          <input {{ $config->paypal_enable==1 ? "checked" : "" }} name="paypal_check" id="toggle" type="checkbox" class="tgl tgl-skewed">
        <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle"></label>
  
      
        <small class="txt-desc">(Please Enable For Paypal Payment Gateway  )</small>
      </div>
    </div>
     <button @if(env('DEMO_LOCK') == 0) type="submit" @else disabled title="This action is disabled in demo !" @endif class="btn btn-primary btn-md">
           <i class="fa fa-save"></i> Save Setting
         </button>

  </form>
    </div>
  </div>
@endsection
