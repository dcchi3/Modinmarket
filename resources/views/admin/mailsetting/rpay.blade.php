@extends("admin/layouts.master")
@section('title',"Razorpay & Paytm Setting |")
@section("body")

<div class="box" >
  <div class="with-border box-header">
    <div class="box-title">Razorpay & Paytm Setting</div>
  </div>

  <div class="box-body">
  	<div class="panel panel-default">
        <div class="panel-heading">
           <label> RazorPay API Setting:</label>
           <div class="pull-right panel-title"><a target="__blank" title="Get Your Keys From here" href="https://razorpay.com/docs/"><i class="fa fa-key" aria-hidden="true"></i> Get Your Keys From here</a></div>
          </div>
		<form action="{{ route('post.rpay.setting') }}" method="POST">
				@csrf
        <div class="panel-body">
     
		<div class="form-group">
			<div class="eyeCy">
		     <label for="RAZOR_PAY_KEY"> RazorPay Key: <span class="required">*</span></label>
		    <input type="password" value="{{ $env_files['RAZOR_PAY_KEY'] }}" name="RAZOR_PAY_KEY" id="RAZOR_PAY_KEY" type="password" class="form-control">
		    <span toggle="#RAZOR_PAY_KEY" class="fa fa-fw fa-eye field-icon toggle-password"></span>
        <small class="text-muted"><i class="fa fa-question-circle"></i> Enter Razorpay API key</small>
		    </div>
		</div>
	
  		<div class="form-group">
  			  <div class="eyeCy">
			       <label for="RAZOR_PAY_SECRET"> RazorPay Secret Key: <span class="required">*</span></label>
			    <input type="password" value="{{ $env_files['RAZOR_PAY_SECRET'] }}" name="RAZOR_PAY_SECRET" id="RAZOR_PAY_SECRET" type="password" class="form-control">
			    <span toggle="#RAZOR_PAY_SECRET" class="fa fa-fw fa-eye field-icon toggle-password"></span>
           <small class="text-muted"><i class="fa fa-question-circle"></i> Enter Razorpay secret key</small>
			  </div>
  		</div>
    <p></p>

     <input name="rpaycheck" id="toggle-event4" {{ $config->razorpay == "1" ? "checked" : "" }} type="checkbox" class="tgl tgl-skewed">
    <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event4"></label>
    
     <small class="txt-desc">(Enable to activate Razorpay Payment gateway )</small>
     <br><br>
     <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i> Save Changes</button>
        </div>

    </form>
      </div>
      <br>
      <div class="panel panel-default">
        <div class="panel-heading">
           <label> Paytm API Setting:</label>
            <div class="pull-right panel-title"><a target="__blank" title="Get Your Keys From here" href="https://developer.paytm.com/docs/"><i class="fa fa-key" aria-hidden="true"></i> Get Your Keys From here</a></div>
          </div>
        
    <form action="{{ route('post.paytm.setting') }}" method="POST">
        @csrf
        <div class="panel-body">
     
        <div class="form-group">
              <label for="PAYTM_ENVIRONMENT"> PAYTM ENVIRONMENT: <span class="required">*</span></label>
              <input type="text" value="{{ $env_files['PAYTM_ENVIRONMENT'] }}" name="PAYTM_ENVIRONMENT" id="PAYTM_ENVIRONMENT" type="password" class="form-control">
               <small class="text-muted"><i class="fa fa-question-circle"></i> For Live use <b>production</b> and for Test use <b>local</b> as ENVIRONMENT</small>
        </div>
    
  
      <div class="form-group">
          <div class="eyeCy">
             <label for="PAYTM_MERCHANT_ID">PAYTM MERCHANT ID: <span class="required">*</span></label>
          <input type="password" value="{{ $env_files['PAYTM_MERCHANT_ID'] }}" name="PAYTM_MERCHANT_ID" id="PAYTM_MERCHANT_ID" type="password" class="form-control">
          <span toggle="#PAYTM_MERCHANT_ID" class="fa fa-fw fa-eye field-icon toggle-password"></span>
          <small class="text-muted"><i class="fa fa-question-circle"></i> Enter PAYTM MERCHANT ID</small>
        </div>
      </div>

      <div class="form-group">
          <div class="eyeCy">
             <label for="PAYTM_MERCHANT_KEY">PAYTM MERCHANT KEY: <span class="required">*</span></label>
          <input type="password" value="{{ $env_files['PAYTM_MERCHANT_KEY'] }}" name="PAYTM_MERCHANT_KEY" id="PAYTM_MERCHANT_KEY" type="password" class="form-control">
          <span toggle="#PAYTM_MERCHANT_KEY" class="fa fa-fw fa-eye field-icon toggle-password"></span>
          <small class="text-muted"><i class="fa fa-question-circle"></i> Enter PAYTM MERCHANT KEY</small>
        </div>
      </div>

    <p></p>

     <input name="paytmchk" id="paytmchk" {{ $config->paytm_enable == 1 ? "checked" : "" }} type="checkbox" class="tgl tgl-skewed">
    <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="paytmchk"></label>
    
     <small class="txt-desc">(Enable to activate Paytm Payment gateway )</small>
     <br><br>
     <button @if(env('DEMO_LOCK') == 0) type="submit" @else disabled title="This action is disabled in demo !" @endif class="btn btn-md btn-primary"><i class="fa fa-save"></i> Save Changes</button>
        </div>

    </form>
      </div>
  </div>
</div>

@endsection
