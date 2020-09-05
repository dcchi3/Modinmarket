@extends("admin/layouts.master")
@section('title',"PayU Money & Instamojo Setting |")
@section("body")
  <div class="box">
    <div class="box-header with-border">
      <div class="box-title">
        PayU Money & Instamojo Setting
      </div>
    </div>

    <div class="box-body">
      <form action="{{ route('store.other.keys') }}" method="POST">
    {{ csrf_field() }}
    <div class="panel panel-default">
      <div class="panel-heading">
         <label for="MAIL_FROM_NAME"> PayU Money API Setting (Indian Payment gateway) :</label>
         <div class="pull-right panel-title"><a target="__blank" title="Get Your Keys From here" href="https://developer.payumoney.com/"><i class="fa fa-key" aria-hidden="true"></i> Get Your Keys From here</a></div>
      </div>

      <div class="panel-body">

        <div class="row">

          <div class="form-group col-md-6">
            <label for="">PayU Default:</label>

            <input value="{{ $env_files['PAYU_DEFAULT'] }}" type="text" name="PAYU_DEFAULT" class="form-control" placeholder="PAYU DEFAULT MODE">
            <small class="text-muted"><i class="fa fa-question-circle"></i> For Live use <b>secure</b> and for Test use <b>test</b> as mode</small>
          </div>

          <div class="form-group col-md-6">
            <label>PayU Method:</label>

            <input value="{{ $env_files['PAYU_METHOD'] }}" type="text" name="PAYU_METHOD" class="form-control" placeholder="PAYU DEFAULT METHOD">
            <small class="text-muted"><i class="fa fa-question-circle"></i> If your account on PayUMoney use <b>payumoney</b> else use <b>payubiz</b></small>
          </div>

          <div class="form-group col-md-6">
              <div class="eyeCy">
          
                <label for="PAYU_MERCHANT_KEY"> PayU Merchant Key:</label>   
                <input type="password" value="{{ $env_files['PAYU_MERCHANT_KEY'] }}" name="PAYU_MERCHANT_KEY" id="PAYU_MERCHANT_KEY" type="password" class="form-control">
                <span toggle="#PAYU_MERCHANT_KEY" class="fa fa-fw fa-eye field-icon toggle-password"></span>
       
              </div>
              <small class="text-muted"><i class="fa fa-question-circle"></i> Enter Payu merchant key</small>
          </div>

         <div class="form-group col-md-6">
              <div class="eyeCy">
      
                <label for="PAYU_MERCHANT_SALT"> PayU MERCHANT SALT:</label>   
                <input type="password" value="{{ $env_files['PAYU_MERCHANT_SALT'] }}" name="PAYU_MERCHANT_SALT" id="PAYU_MERCHANT_SALT" type="password" class="form-control">
                <span toggle="#PAYU_MERCHANT_SALT" class="fa fa-fw fa-eye field-icon toggle-password"></span>
               
              </div>
              <small class="text-muted"><i class="fa fa-question-circle"></i> Enter Payu merchant salt</small>
         </div>

         <div class="form-group col-md-12">
            <label for="">PayU Auth Header:</label>
            <input type="text" class="form-control" name="PAYU_AUTH_HEADER" value="{{ env('PAYU_AUTH_HEADER') }}">
            <small class="text-muted"><i class="fa fa-question-circle"></i> Enter payu auth header require only if your account is on payumoney</small>
         </div>

         <div class="form-group col-md-12">
              <label for="PAY_U_MONEY_ACC">Is it a PayUMoney account?</label>
              <input name="PAY_U_MONEY_ACC" id="PAY_U_MONEY_ACC" {{ env('PAY_U_MONEY_ACC')== true ? "checked" : "" }} type="checkbox" class="tgl tgl-skewed">
              <label class="tgl-btn" data-tg-off="No" data-tg-on="Yes" for="PAY_U_MONEY_ACC"></label>
         </div>

        </div>
      <label for="PAYU_REFUND_URL"> PayU API REFUND URL:</label>
      <input type="text" value="{{ $env_files['PAYU_REFUND_URL'] }}" name="PAYU_REFUND_URL" id="PAYU_REFUND_URL" class="form-control">
    
    <small class="text-muted">
      • For <b>Live</b> : https://payumoney.com/treasury/merchant/refundPayment
      <br>
      • For <b>Test</b> : https://test.payumoney.com/treasury/merchant/refundPayment
    </small>
    <p></p>

    <input name="payu_chk" id="toggle-event3" {{ $config->payu_enable == "1" ? "checked"  :"" }} type="checkbox" class="tgl tgl-skewed">
     <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event3"></label>

    
     <small class="txt-desc">(Enable it to active Paytm Payment gateway) </small>
   
      </div>
    </div>
    
      <div class="panel panel-default">
        <div class="panel-heading">
           <label for="MAIL_FROM_NAME"> Instamojo API Setting:</label>
           <div class="pull-right panel-title"><a target="__blank" title="Get Your Keys From here" href="https://www.instamojo.com/developers/"><i class="fa fa-key" aria-hidden="true"></i> Get Your Keys From here</a></div>
        </div>

        <div class="panel-body">
          
      <label for="INSTAMOJO_URL"> Instamojo API URL:</label>
    <input type="text" value="{{ $env_files['IM_URL'] }}" name="IM_URL" id="INSTAMOJO_URL" class="form-control">
    
    <small class="text-muted">
      • For <b>Live</b> use <a href="#">https://instamojo.com/api/1.1/</a>
      <br>
      • For <b>Test</b> use <a href="">https://test.instamojo.com/api/1.1/</a>
    </small>
    <p></p>

    <label for="IM_REFUND_URL"> Instamojo API REFUND URL:</label>
    <input type="text" value="{{ $env_files['IM_REFUND_URL'] }}" name="IM_REFUND_URL" id="IM_REFUND_URL" class="form-control">
    
    <small class="text-muted">
      • For <b>Live</b> use <a href="#">https://instamojo.com/api/1.1/refunds/</a>
      <br>
      • For <b>Test</b> use <a href="">https://test.instamojo.com/api/1.1/refunds/</a>
    </small>
    <p></p>

    <div class="eyeCy">
       <label for="IM_API_KEY"> Private API Key:</label>
    <input type="password" value="{{ $env_files['IM_API_KEY'] }}" name="IM_API_KEY" id="INSTAMOJO_AUTH_KEY" type="password" class="form-control">
    <span toggle="#INSTAMOJO_AUTH_KEY" class="fa fa-fw fa-eye field-icon toggle-password"></span>
    </div>
   
    <small class="text-muted"><i class="fa fa-question-circle"></i> Please Enter Instamojo Private API Key </small>
    <p></p>

    <div class="eyeCy">
       <label for="IM_AUTH_TOKEN"> Private Auth Token:</label>
    <input type="password" value="{{ $env_files['IM_AUTH_TOKEN'] }}" name="IM_AUTH_TOKEN" id="INSTAMOJO_AUTH_TOKEN" type="password" class="form-control">
    <span toggle="#INSTAMOJO_AUTH_TOKEN" class="fa fa-fw fa-eye field-icon toggle-password"></span>
    </div>
   
    <small class="text-muted"><i class="fa fa-question-circle"></i> Please Enter Instamojo Auth Token </small>
    <p></p>

     <input name="instam_check" id="toggle-event4" {{ $config->instamojo_enable== "1" ? "checked" : "" }} type="checkbox" class="tgl tgl-skewed">
    <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event4"></label>
    
     <small class="txt-desc">(Enable it to active Instamojo Payment gateway )</small>
        </div>
      </div>
    
    <button @if(env('DEMO_LOCK') == 0) type="submit" @else disabled title="This action is disabled in demo !" @endif class="btn col-md-4 btn-md btn-primary"><i class="fa fa-save"></i> Save Setting</button>
  
   
  </form>
    </div>
  </div>
@endsection
