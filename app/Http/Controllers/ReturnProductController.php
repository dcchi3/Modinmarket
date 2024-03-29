<?php

namespace App\Http\Controllers;

use App\Address;
use App\AddSubVariant;
use App\admin_return_product;
use App\CanceledOrders;
use App\Currencey;
use App\Invoice;
use App\InvoiceDownload;
use App\Mail\OrderStatus;
use App\Notifications\AdminOrderNotification;
use App\Notifications\SellerNotification;
use App\Notifications\SendOrderStatus;
use App\Order;
use App\OrderActivityLog;
use App\Product;
use App\ProductAttributes;
use App\ProductValues;
use App\Return_Product;
use App\User;
use Auth;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Mail;
use PayPal\Api\Amount;
use PayPal\Api\Refund;
use PayPal\Api\Sale;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Session;
use View;
use App\multiCurrency;
use App\CurrencyList;
use App\Genral;
use App\Coupan;
use Razorpay\Api\Api;
use PaytmWallet;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class ReturnProductController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	private $_api_context;

	public function __construct() {
		/** PayPal api context **/
		$paypal_conf = \Config::get('paypal');
		$this->_api_context = new ApiContext(new OAuthTokenCredential(
			$paypal_conf['client_id'],
			$paypal_conf['secret'])
		);
		$this->_api_context->setConfig($paypal_conf['settings']);
	}

	public function index() {
		$pro_return = admin_return_product::get();
		return view('admin.return_policy.index', compact('pro_return'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return view('admin.return_policy.add');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$this->validate($request, [
			'des' => 'required|string|',
			'days' => 'required',
			'amount' => 'required',
		]);

		$input = $request->all();

		$input['created_by'] = Auth::user()->id;

		$data = admin_return_product::create($input);

		$data->save();

		return back()->with("added", "Return Policy Has Been Created !");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Return_Product  $return_Product
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Return_Product  $return_Product
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$policy = admin_return_product::findOrFail($id);
		return view("admin.return_policy.edit", compact("policy"));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Return_Product  $return_Product
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$brand = admin_return_product::findOrFail($id);
		$input = $request->all();
		$brand->update($input);

		return redirect('admin/return_policy')->with('updated', 'Return Policy has been updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Return_Product  $return_Product
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$daa = new admin_return_product;
		$obj = $daa->findorFail($id);

		$value = $obj->delete();
		if ($value) {
			session()->flash("deleted", "Return Policy Has Been deleted");
			return redirect("admin/return_policy");
		}
	}

	

	public function update_return_product(Request $request, $id) {


		$status = $request->payment_status;
		if ($status == '') {
			$status = 0;
		}

		$sub = new Return_Product;
		$obj = $sub->find($id);
		$obj->payment_status = $status;
		$obj->created_at = date('Y-m-d H:i:s');
		$obj->updated_at = date('Y-m-d H:i:s');
		$obj->save();

		return redirect('admin/return_products_show')->with('updated', 'Return Product is update.');
	}

	public function cancel_product(Request $request, $id) {

		 $did = Crypt::decrypt($id);
		$status = 0; //initiate next proccessing if response comes true

		if(!isset($request->source)){
			notify()->error('Please add bank account first !');
			return back();
		}	

		if (Auth::check()) {

			$findorder = InvoiceDownload::find($did);

			if($findorder->discount != 0){

				$finalAmount = round($findorder->qty * $findorder->price + $findorder->tax_amount + $findorder->handlingcharge + $findorder->shipping-$findorder->discount, 2);
		
			}else{

				$finalAmount = round($findorder->qty * $findorder->price + $findorder->tax_amount + $findorder->handlingcharge + $findorder->shipping, 2);
				
			}

			 $finalAmount = round($finalAmount,2);

			
			if (!empty($findorder)) {

				if (Auth::user()->id == $findorder->order->user_id || Auth::user()->role_id == 'a' || $findorder->vender_id == Auth::user()->id ) {

					//Cancel Order Here
					if ($findorder->order->payment_method == 'COD') {

						$cancelorderlog = new CanceledOrders();

						$cancelorderlog->order_id = $findorder->order->id;
						$cancelorderlog->inv_id = $findorder->id;
						$cancelorderlog->user_id = $findorder->order->user_id;
						$cancelorderlog->comment = $request->comment;
						$cancelorderlog->method_choosen = $request->source;
						$cancelorderlog->amount = $finalAmount;
						$cancelorderlog->is_refunded = 'completed';
						$cancelorderlog->transaction_id = 'CODCAN' . str_random(10);
						$cancelorderlog->txn_fee = NULL;
						$cancelorderlog->save();

						$status = 1;
					}

					if ($findorder->order->payment_method == 'BankTransfer') {



						if ($request->source == 'bank') {

							$cancelorderlog = new CanceledOrders();

							$cancelorderlog->order_id = $findorder->order->id;
							$cancelorderlog->inv_id = $findorder->id;
							$cancelorderlog->user_id = $findorder->order->user_id;
							$cancelorderlog->comment = $request->comment;
							$cancelorderlog->method_choosen = $request->source;
							$cancelorderlog->amount = $finalAmount;
							$cancelorderlog->bank_id = $request->bank_id;
							$cancelorderlog->is_refunded = 'pending';
							$cancelorderlog->transaction_id = 'TXNBNK' . str_random(10);
							$cancelorderlog->txn_fee = NULL;
							$cancelorderlog->save();

							$status = 1;
						}
					}

					if ($request->source == 'bank') {

						$cancelorderlog = new CanceledOrders();

						$cancelorderlog->order_id = $findorder->order->id;
						$cancelorderlog->inv_id = $findorder->id;
						$cancelorderlog->user_id = $findorder->order->user_id;
						$cancelorderlog->comment = $request->comment;
						$cancelorderlog->method_choosen = $request->source;
						$cancelorderlog->amount = $finalAmount;
						$cancelorderlog->bank_id = $request->bank_id;
						$cancelorderlog->is_refunded = 'pending';
						$cancelorderlog->transaction_id = 'TXNBNK' . str_random(10);
						$cancelorderlog->txn_fee = NULL;
						$cancelorderlog->save();

						$status = 1;

					} elseif ($request->source == 'orignal') {

						if ($findorder->order->payment_method == 'PayPal') {

							$fCurrency = multiCurrency::where('currency_symbol', '=', $findorder->order->paid_in)->first();

							$setCurrency = CurrencyList::findOrFail($fCurrency->currency_id)->code;

							$amt = new Amount();
							$amt->setTotal($finalAmount)
								->setCurrency($setCurrency);
							$saleId = $findorder->order->sale_id;
							$refund = new Refund();
							$refund->setAmount($amt);
							$sale = new Sale();
							$sale->setId($saleId);

							try {

								$refundedSale = $sale->refund($refund, $this->_api_context);

								$cancelorderlog = new CanceledOrders();

								$cancelorderlog->order_id = $findorder->order->id;
								$cancelorderlog->inv_id = $findorder->id;
								$cancelorderlog->user_id = $findorder->order->user_id;
								$cancelorderlog->comment = $request->comment;
								$cancelorderlog->method_choosen = $request->source;
								$cancelorderlog->amount = $refundedSale->total_refunded_amount['value'];
								$cancelorderlog->is_refunded = $refundedSale->state;
								$cancelorderlog->transaction_id = $refundedSale->id;
								$cancelorderlog->txn_fee = $refundedSale->refund_from_transaction_fee['value'];
								$cancelorderlog->save();

								$status = 1;

							} catch (\Exception $ex) {

								return $ex->getData();
								

							}
						} elseif ($findorder->order->payment_method == 'Stripe') {
							$stripe = new Stripe();
							$stripe = Stripe::make(env('STRIPE_SECRET'));

							$charge_id = $findorder->order->transaction_id;
							$amount = $finalAmount;

							try {

								$refund = $stripe->refunds()->create($charge_id, $amount, [

									'metadata' => [

										'reason' => $request->comment,

									],

								]);




								$cancelorderlog = new CanceledOrders();

								$cancelorderlog->order_id = $findorder->order->id;
								$cancelorderlog->inv_id = $findorder->id;
								$cancelorderlog->user_id = $findorder->order->user_id;
								$cancelorderlog->comment = $request->comment;
								$cancelorderlog->method_choosen = $request->source;
								$cancelorderlog->amount = $finalAmount;
								$cancelorderlog->is_refunded = 'completed';
								$cancelorderlog->transaction_id = $refund['id'];
								$cancelorderlog->txn_fee = NULL;
								$cancelorderlog->save();

								$status = 1;

							} catch (\Exception $e) {
								$error = $e->getMessage();
								Session::flash('warning', $error);
								notify()->error($error);
								return back();
							}

						} elseif($findorder->order->payment_method == 'Instamojo') {
							
							try {

								$ch = curl_init();
								$api_key = env('IM_API_KEY');
								$auth_token = env('IM_AUTH_TOKEN');
								curl_setopt($ch, CURLOPT_URL, env('IM_REFUND_URL'));
								curl_setopt($ch, CURLOPT_HEADER, FALSE);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
								curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
								curl_setopt($ch, CURLOPT_HTTPHEADER,

								array("X-Api-Key:$api_key",
										"X-Auth-Token:$auth_token"));

								$payload = array(

									'transaction_id' => 'RFD_IM_' . str_random(10),
									'payment_id' => $findorder->order->transaction_id,
									'type' => 'QFL',
									'refund_amount' => $finalAmount,
									'body' => $request->comment,

								);

								curl_setopt($ch, CURLOPT_POST, true);
								curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
								$response = curl_exec($ch);
								curl_close($ch);

								$result = json_decode($response, true);

								if (isset($result['refund'])) {

									$cancelorderlog = new CanceledOrders();

									$cancelorderlog->order_id = $findorder->order->id;
									$cancelorderlog->inv_id = $findorder->id;
									$cancelorderlog->user_id = $findorder->order->user_id;
									$cancelorderlog->comment = $request->comment;
									$cancelorderlog->method_choosen = $request->source;
									$cancelorderlog->amount = $result['refund']['refund_amount'];
									$cancelorderlog->is_refunded = 'completed';
									$cancelorderlog->transaction_id = $result['refund']['id'];
									$cancelorderlog->txn_fee = NULL;
									$cancelorderlog->save();

									$status = 1;

								}else{
									notify()->error($result['message']);
									return back();
								}

							} catch (\Exception $e) {
								$error = $e->getMessage();
								Session::flash('warning', $error);
								notify()->error($error);
								return back();
							}

						} elseif ($findorder->order->payment_method == 'PayU') {
							Session::flash('warning', 'Error In PAYU SIDE Will added soon when PAYU solve it, use bank transfer method till that');
							notify()->error('Error In PAYU SIDE Will added soon when PAYU solve it use bank transfer method till that');
							return back();

						}
						elseif ($findorder->order->payment_method == 'Razorpay') {
							 //get API Configuration 
        					$api = new Api(env('RAZOR_PAY_KEY'), env('RAZOR_PAY_SECRET'));
							$payment = $api->payment->fetch($findorder->order->transaction_id);
							

							try{
								$refund = $payment->refund(array('amount' => $finalAmount*100)); 
								
								$cancelorderlog = new CanceledOrders();

								$cancelorderlog->order_id = $findorder->order->id;
								$cancelorderlog->inv_id = $findorder->id;
								$cancelorderlog->user_id = $findorder->order->user_id;
								$cancelorderlog->comment = $request->comment;
								$cancelorderlog->method_choosen = $request->source;
								$cancelorderlog->amount = $refund->amount/100;
								$cancelorderlog->is_refunded = 'completed';
								$cancelorderlog->transaction_id = $refund->id;
								$cancelorderlog->txn_fee = NULL;
								$cancelorderlog->save();

								$status = 1;

							}catch(\Exception $e){
								$error = $e->getMessage();
								Session::flash('warning', $error);
								notify()->error($error);
								return back();
							}
						}elseif($findorder->order->payment_method == 'Paytm'){

								$refund = PaytmWallet::with('refund');

						        $refund->prepare([
						            'order' => $findorder->order['order_id'],
						            'reference' => 'refund-order-'.$findorder->order['order_id'],
						            'amount' => $finalAmount, 
						            'transaction' => $findorder->order->transaction_id 
						        ]);

						        $refund->initiate();
							    $response = $refund->response();
			
							        if($refund->isSuccessful()){
							          	
							          	$cancelorderlog = new CanceledOrders();

										$cancelorderlog->order_id = $findorder->order->id;
										$cancelorderlog->inv_id = $findorder->id;
										$cancelorderlog->user_id = $findorder->order->user_id;
										$cancelorderlog->comment = $request->comment;
										$cancelorderlog->method_choosen = $request->source;
										$cancelorderlog->amount = $response['REFUNDAMOUNT'];
										$cancelorderlog->is_refunded = 'completed';
										$cancelorderlog->transaction_id = $response['REFUNDID'];
										$cancelorderlog->txn_fee = NULL;
										$cancelorderlog->save();
										$status = 1;

							        }else if($refund->isFailed()){

							          if($response['STATUS'] == 'TXN_FAILURE'){

							          		$status = 0;
							          		notify()->error($response['RESPMSG']);
							          		return back();

							          }

							        }else if($refund->isOpen()){
							          	#nocode
							        }else if($refund->isPending()){
							         	#nocode
							        }

						}

					}

					if ($status == 1) {

						if ($findorder->order->payment_method != 'COD' && $request->source != 'bank') {

							InvoiceDownload::where('id', '=', $did)->update(['status' => 'refunded']);

						}

						if ($request->source == 'bank') {

							InvoiceDownload::where('id', '=', $did)->update(['status' => 'Refund Pending']);
						}

						if ($findorder->order->payment_method == 'COD') {
							InvoiceDownload::where('id', '=', $did)->update(['status' => 'canceled']);
						}

						$orivar = AddSubVariant::findorfail($findorder->variant_id);

						/*Return back Qty*/
								/*Adding Stock Back*/
								$stock = $orivar->stock+$findorder->qty;

								/*Updating Stock*/
								$orivar->stock = $stock;
								$orivar->save();
								
						/*Returned*/

						$i = 0;
						$varcount = count($orivar->main_attr_value);
						$productname = $orivar->products->name;
						

						foreach ($orivar->main_attr_value as $key => $orivars) {

							$i++;

							$getattrname = ProductAttributes::where('id', $key)->first()->attr_name;
							$getvarvalue = ProductValues::where('id', $orivars)->first();

							if ($i < $varcount) {
								if (strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null) {
									if ($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour") {

										$var_main = $getvarvalue->values;

									} else {
										$var_main = $getvarvalue->values . $getvarvalue->unit_value;
									}
								} else {
									$var_main = $getvarvalue->values;
								}

							} else {

								if (strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null) {

									if ($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour") {

										$var_main = $getvarvalue->values;
									} else {
										$var_main = $getvarvalue->values . $getvarvalue->unit_value;
									}

								} else {
									$var_main = $getvarvalue->values;
								}

							}
						}

						

						$get_admins = User::where('role_id', '=', 'a')->get();
						$status = ucfirst('cancelled');

						$create_activity = new OrderActivityLog();

						$create_activity->order_id = $findorder->order->id;
						$create_activity->inv_id = $findorder->id;
						$create_activity->user_id = Auth::user()->id;
						$create_activity->variant_id = $findorder->variant_id;
						$create_activity->log = $status;

						$create_activity->save();

						$inv = $findorder;
						$inv_cus = Invoice::first();

						/*Send Mail to User*/
						$e = Address::findOrFail($findorder->order->delivery_address)->email;

						if (isset($e)) {
							try{
								Mail::to($e)->send(new OrderStatus($inv_cus, $findorder, $status));
							}catch(\Swift_TransportException $e){

							}
						}
						/*End*/

						/*Sending Notification to user*/
						User::find($findorder->order->user_id)->notify(new SendOrderStatus($productname, $var_main, $status, $findorder->order->order_id));
						/*End*/

						/*Sending notification to all admin*/
						\Notification::send($get_admins, new AdminOrderNotification($inv_cus, $productname, $var_main, $status, $findorder->order->order_id));
						/*End*/

						/*Sending Notification to vender*/
						$vender_system = Genral::first()->vendor_enable;
						if($vender_system == 1){
							$o = $findorder->order->order_id;
							$msg = "For order $inv_cus->order_prefix$o Item $productname ($var_main) has been $status";
							$url = route('seller.canceled.orders');
							$v = User::find($findorder->vender_id);
							if($v->role_id == 'v'){
								$v->notify(new SellerNotification($url,$msg));
							}
						}
						/*END*/
					}

					if ($findorder->order->payment_method == 'COD') {
						Session::flash('updated', 'Following Item has been cancelled successfully !'); //for admin
						notify()->success('Following Item has been cancelled successfully !');
						return back();

				} 
					Session::flash('added', 'Following Item has been cancelled successfully !');
					notify()->success('Following Item has been cancelled successfully !');
					return back();
				

			} else {
				Session::flash('deleted', '404 No order found regarding your query ! !');
				notify()->error('404 No order found regarding your query !');
				return back();
			}

		} else {
			Session::flash('warning', '401 Unauthorized Action !');
			notify()->error('401 Unauthorized Action !');
			return back();
		}

	}

}

}
