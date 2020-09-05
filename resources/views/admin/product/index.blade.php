@extends("admin/layouts.master")
@section('title','All Products |')
@section("body")


 <div class="box">
  

      <div class="box-header">
        <h3 class="box-title">All Products</h3>
        <br><br>
        <a title="Import products" href="{{ route('import.page') }}" class="btn btn-md bg-olive">Import Products</a>
        <a href="{{ url('admin/products/create') }}" class="btn btn-md btn-success">+ Add Product</a>

         <a type="button" class="btn btn-danger btn-md z-depth-0" data-toggle="modal" data-target="#bulk_delete"><i class="fa fa-trash"></i> Delete Selected</a> 

   
        
      </div>




      <div class="box-body">

        <table id="full_detail_table" class="width100 table table-bordered table-hover">
          <thead>
            <tr class="header">
            <th>
                <div class="inline">
                <input id="checkboxAll" type="checkbox" class="filled-in" name="checked[]" value="all"/>
                <label for="checkboxAll" class="material-checkbox"></label>
              </div>
              
              </th>

            <th>
              #
            </th>
            <th>
              Image
            </th>

            <th>
              Product Detail
            </th>

            <th>
              Price ({{ $defCurrency->currency->code }})
            </th>

            <th>
              Categories & More
            </th>

            <th>
              Featured
            </th>

            <th>
              Status
            </th>
            <th>
              Add / Update on
            </th>

            <th>
              # Actions
            </th>
          </tr>
          </thead>

          <tbody>
            
                      @foreach($products as $key=> $product)
                        <tr>
                           <td>
                            <div class="inline">
                              <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="{{$product->id}}" id="checkbox{{$product->id}}">
                              <label for="checkbox{{$product->id}}" class="material-checkbox"></label>
                            </div>
                           </td>
                          <td>{{$key+1}}</td>
                          <td align="center">
                              
                         @if(count($product->subvariants)>0)

                            @foreach($product->subvariants as $sub)
                              @if($sub->def == 1)
                                @if(isset($sub->variantimages['image2']))
                                  
                                  <img class="pro-img" title="{{ $product->name }}" src="{{ url('variantimages/'.$sub->variantimages['image2']) }}" alt="{{ $sub->variantimages['image2'] }}">
                                
                                @endif
                              @endif
                            @endforeach
                          @else
                         
                          <img title="Make a variant first !" src="{{ Avatar::create($product->name)->toBase64() }}" />
                          @endif
                          </td>
                          <td width="20%">
                            <p><b>{{$product->name}}</b></p>
                            <p><b>Store: </b>
                              <span class="font-weight500">{{ !$product->store ? 'Store Not Found !' :$product->store->name }}</span></p>
                            <p>
                              <b>Brand:</b>
                              <span class="font-weight500">
                                {{ !$product->brand ? 'Brand Not found !': $product->brand->name }}
                              </span>
                            </p>
                            
                          </td>

                          <td valign="middle">
                            <p><b>Selling Price: </b> <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i> {{ $product->price }}</p>
                            @if($product->vender_offer_price !='')
                            <p><b>Selling Offer Price: </b> <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i> {{ $product->offer_price }}</p>
                            @endif

                            <small><a data-target="#priceDetail{{ $product->id }}" data-toggle="modal">Additional Price Detail</a></small>
                          </td>

                          <td>
                           
                            <p><i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            {{ $product->category->title }} </p>

                            <p class="font-weight600"><i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            {{ $product->subcategory->title }}</p>
                            
                            <p class="font-weight400"> 
                            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            {{ $product->childcat['title'] }}
                            </p>
                            
                          </td>

                           <td>
                           <form action="{{ route('product.featured.quick.update',$product->id) }}" method="POST">
                              {{csrf_field()}}
                              <button type="submit" class="btn btn-xs {{ $product->featured==1 ? "btn-success" : "btn-danger" }}">
                                {{ $product->featured ==1 ? 'Yes' : 'No' }}
                              </button>
                            </form>
                         </td>

                         <td>
                            <form action="{{ route('product.quick.update',$product->id) }}" method="POST">
                              {{csrf_field()}}
                              <button type="submit" class="btn btn-xs {{ $product->status==1 ? "btn-success" : "btn-danger" }}">
                                {{ $product->status ==1 ? 'Active' : 'Deactive' }}
                              </button>
                            </form> 
                          </td>

                          <td width="15%">
                            <p> <i class="fa fa-calendar-plus-o" aria-hidden="true"></i> 
                              <span class="font-weight500" >{{ date('M jS Y',strtotime($product->created_at)) }},</span></p>
                            <p ><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="font-weight500" >{{ date('h:i A',strtotime($product->created_at)) }}</span></p>
                            
                            <p class="border-grey"></p>
                            
                            <p>
                               <i class="fa fa-calendar-check-o" aria-hidden="true"></i> 
                               <span class="font-weight500">{{ date('M jS Y',strtotime($product->updated_at)) }}</span>
                            </p>
                           
                            <p><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="font-weight500">{{ date('h:i A',strtotime($product->updated_at)) }}</span></p>
                            
                          </td>

                         <td>

                            <ul class="nav table-nav">
                              <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                  Action <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                   @foreach($product->subvariants as $key=> $orivar)
                                    @if($orivar->def ==1)
                                    
                                   <li role="presentation">
                                    
                                   
                                  @php 
                                      $var_name_count = count($orivar['main_attr_id']);
                                     
                                      $name;
                                      $var_name;
                                      $newarr = array();

                                      for($i = 0; $i<$var_name_count; $i++){
                                        $var_id =$orivar['main_attr_id'][$i];
                                        $var_name[$i] = $orivar['main_attr_value'][$var_id];
                                          
                                          $name[$i] = App\ProductAttributes::where('id',$var_id)->first();
                                         
                                      }


                                    try{
                                      $url = '../details/'.$product->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                                    }catch(Exception $e)
                                    {
                                      $url = '../details/'.$product->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                                    }

                                 @endphp

                                    <a target="_blank" role="menuitem" tabindex="-1" href="{{ $url }}">
                                    
                                      <i class="fa fa-eye" aria-hidden="true"></i>View Product

                                    </a>
                                    

                                  </li>
                                   <li role="presentation" class="divider"></li>
                                  
                                    <li>
                                      <a href="{{ route('pro.vars.all',$product->id) }}" class=""><i class="fa fa-external-link-square" aria-hidden="true"></i>
                                      View All Variants</a>
                                    </li>

                                      <li role="presentation" class="divider"></li>
                                    @endif
                                    @endforeach

                                    
                                     <li role="presentation">
                                      <a href="{{ route('add.var',$product->id) }}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>Add Variant
                                      </a>
                                    </li>
                                      <li role="presentation" class="divider"></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/products/'.$product->id.'/edit')}}">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit Product</a></li>
                                    
                                    
                                    <li role="presentation" class="divider"></li>
                                    <li role="presentation">
                                      <a data-toggle="modal" href="#{{ $product->id }}pro">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>Delete
                                      </a>
                                    </li>
                                </ul>
                              </li>
                            </ul>

                         
                         
                            
                          </td>
                          
                          <!-- Modal -->
                        <div class="modal fade" id="priceDetail{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                          <div class="modal-dialog {{ $product->vender_offer_price !='' ? "modal-lg" : "modal-md" }}" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Summary of Pricing for {{ $product->name }}</h4>
                              </div>
                              <div class="modal-body">
                                  
                                  

                                <div class="row">

                                  <div class="{{ $product->vender_offer_price !='' ? "col-md-6" : "col-md-12" }}">
                                    <h4>Pricing Summary </h4>
                                    <hr>

                                    <div class="row">
                                      <div align="left" class="left-col col-md-6">
                                        <b>Product Price:</b>
                                      </div>
                                      <div align="right" class="right-col col-md-6">
                                        {{ sprintf("%.2f",$product->vender_price) }} <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i> 
                                        <br>
                                        <small> @if($product->tax_r !='') (Incl. of tax) @else (Excl. of Tax) @endif </small>
                                      </div>
                                    </div>
                                    
                                    
                                    <hr>
                                      <h4>Commission Summary</h4>
                                    <hr>

                                    @php

                                      $commision_setting = App\CommissionSetting::first();
                                      $commissionRate = 0;
                                      $mpc = 0;
                                      $show_price = 0;
                                      $convert_price = 0;

                              if($commision_setting->type == "flat"){

                                $commission_amount = $commision_setting->rate;

                                $commissionRate = $commission_amount;

                                if($commision_setting->p_type == 'f'){
                                
                                  $totalprice = $product->vender_price+$commission_amount;
                                  $totalsaleprice = $product->vender_offer_price + $commission_amount;

                                  if($product->vender_offer_price == 0){
                                     $show_price = $totalprice;
                                  }else{
                                    $totalsaleprice;
                                    $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                    $show_price = $totalprice;
                                  }

                                  
                                   
                                }else{

                                  $totalprice = ($product->vender_price)*$commission_amount;

                                  $totalsaleprice = ($product->vender_offer_price)*$commission_amount;

                                  
                                     $commissionRate = $totalprice/100;
                                     $mpc = $totalprice/100;
                                  
                                  $buyerprice = ($product->vender_price)+($totalprice/100);

                                  $buyersaleprice = ($product->vender_offer_price)+($totalsaleprice/100);

                                 
                                    if($product->vender_offer_price == NULL){
                                      $show_price =  round($buyerprice,2);
                                    }else{
                                       round($buyersaleprice,2);
                                     
                                      $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                      $show_price = $buyerprice;
                                    }
                                 

                                }
                              }else{

                              
                              $comm = \DB::table('commissions')->where('category_id',$product->category_id)->first();
                            if(isset($comm)){

                                  if($comm->type=='f'){

                                     $commissionRate = $comm->rate;

                                     $price = $product->vender_price + $comm->rate;

                                      if($product->vender_offer_price != null){
                                        $offer =  $product->vender_offer_price + $comm->rate;
                                      }

                                      if($product->vender_offer_price == 0 || $product->vender_offer_price == null){
                                          $show_price = $price;
                                      }else{
                                       
                                        $convert_price = $offer;
                                        $show_price = $price;
                                      }

                                    }else{

                                        $commissionRate = $comm->rate;

                                        $commission_amount = $comm->rate;

                                        $totalprice = ($product->vender_price)*$commission_amount;

                                        $totalsaleprice = ($product->vender_offer_price)*$commission_amount;

                                       
                                         $commissionRate = $totalprice/100;
                                         $mpc = $totalprice/100;
                                        

                                        $buyerprice = ($product->vender_price)+($totalprice/100);

                                        $buyersaleprice = ($product->vender_offer_price)+($totalsaleprice/100);

                                       
                                        if($product->vender_offer_price == 0){
                                           $show_price = round($buyerprice,2);
                                        }else{
                                          $convert_price =  round($buyersaleprice,2);
                                          
                                          $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                          $show_price = round($buyerprice,2);
                                        }
                                       
                                       
                                        
                                  }

                                 }
                                    }
                                    @endphp

                                    @php
                                      $ctax = 0;
                                      if($product->tax_r !=''){
                                        $ctax = $commissionRate*$product->tax_r/100;
                                      }
                                    @endphp

                                    <div class="row">
                                      <div align="left" class="left-col col-md-6">
                                        <b>Net Commission</b>:
                                      </div>
                                      <div align="right" class="right-col col-md-6">
                                        @if($product->tax_r =='')  {{ sprintf("%.2f",$commissionRate) }} @else  {{ sprintf("%.2f", $commissionRate-$ctax) }} @endif <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i>
                                      </div>
                                    </div>

                                   

                                    @if($product->tax_r !='')
                                    <div class="row">

                                      <div align="left" class="left-col col-md-6">
                                        <b>Commission Tax:</b>
                                      </div>

                                      <div align="right" class="right-col col-md-6">
                                        {{ sprintf("%.2f", $ctax) }} <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i>
                                      </div>

                                    </div>
                                     
                                    <div class="row">
                                      <div align="left" class="left-col col-md-6">
                                        <b>Gross Commission:</b>
                                      </div>

                                      <div align="right" class="right-col col-md-6"> {{ sprintf("%.2f", $commissionRate) }} <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i> <br> <small>@if($product->tax_r !='') (Incl. of Tax) @endif</small></div>
                                    </div>
                                    
                                    @endif
                                  </div>
                                @if($product->vender_offer_price != '')
                                  <div class="col-md-6">
                                    <h4>Offer Pricing Summary</h4>
                                    <hr>
                                    <div class="row">
                                      <div align="left" class="left-col col-md-6">
                                        <b>Product Offer Price:</b>
                                      </div>
                                      <div align="right" class="right-col col-md-6">
                                        {{ sprintf("%.2f", $product->vender_offer_price) }} <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i>
                                        <br>
                                        <small>@if($product->tax_r !='') (Incl. of tax) @else (Excl. of Tax) @endif</small>
                                      </div>
                                    </div>
                                    
                                    <hr>
                                      <h4>Commission Summary</h4>
                                    <hr>

                                    @php

                                      $commision_setting = App\CommissionSetting::first();
                                      $commissionRate = 0;
                                      $mpc = 0;

                              if($commision_setting->type == "flat"){

                                $commission_amount = $commision_setting->rate;

                                $commissionRate = $commission_amount;


                                if($commision_setting->p_type == 'f'){
                                
                                  $totalprice = $product->vender_price+$commission_amount;
                                  $totalsaleprice = $product->vender_offer_price + $commission_amount;

                                  if($product->vender_offer_price == 0){
                                     $show_price = $totalprice;
                                  }else{
                                    $totalsaleprice;
                                    $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                    $show_price = $totalprice;
                                  }

                                  
                                   
                                }else{

                                  $totalprice = ($product->vender_price)*$commission_amount;

                                  $totalsaleprice = ($product->vender_offer_price)*$commission_amount;

                                  if(!($totalsaleprice)){
                                    $commissionRate = $totalprice/100;
                                    $mpc = $totalprice/100;
                                  }else{
                                     $commissionRate = $totalsaleprice/100;
                                     $mpc = $totalprice/100;
                                  }

                                  $buyerprice = ($product->vender_price)+($totalprice/100);

                                  $buyersaleprice = ($product->vender_offer_price)+($totalsaleprice/100);

                                 
                                    if($product->vender_offer_price == NULL){
                                      $show_price =  round($buyerprice,2);
                                    }else{
                                       round($buyersaleprice,2);
                                     
                                      $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                      $show_price = $buyerprice;
                                    }
                                 

                                }
                              }else{
                                
                              $comm = App\Commission::where('category_id',$product->category_id)->first();

                                if(isset($comm)){

                                  if($comm->type=='f'){

                                     $commissionRate = $comm->rate;

                                     $mpc = $comm->rate;

                                      $price = $product->vender_price + $comm->rate;

                                      if($product->vender_offer_price != null){
                                        $offer =  $product->vender_offer_price + $comm->rate;
                                      }

                                      if($product->vender_offer_price == 0 || $product->vender_offer_price == null){
                                          $show_price = $price;
                                      }else{
                                       
                                        $convert_price = $offer;
                                        $show_price = $price;
                                      }

                                    }else{

                                        $commissionRate = $comm->rate;

                                        $commission_amount = $comm->rate;

                                        $totalprice = $product->vender_price*$commission_amount;

                                        $totalsaleprice = $product->vender_offer_price*$commission_amount;

                                        if(!($totalsaleprice)){
                                          $commissionRate = $totalprice/100;
                                          $mpc = $totalprice/100;
                                        }else{
                                           $commissionRate = $totalsaleprice/100;
                                            $mpc = $totalprice/100;
                                        }

                                        $buyerprice = ($product->vender_price)+($totalprice/100);

                                        $buyersaleprice = ($product->vender_offer_price)+($totalsaleprice/100);

                                       
                                        if($product->vender_offer_price == 0){
                                           $show_price = round($buyerprice,2);
                                        }else{
                                          $convert_price =  round($buyersaleprice,2);
                                          
                                          $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                          $show_price = round($buyerprice,2);
                                        }
                                       
                                       
                                        
                                  }

                                 }
                                    }
                                    @endphp

                                    @php
                                      $ctax = 0;
                                      if($product->tax_r !=''){
                                        $ctax = $commissionRate*$product->tax_r/100;
                                      }
                                    @endphp
                                    <div class="row">
                                      <div align="left" class="left-col col-md-6">
                                        <b>Net Commission:</b>
                                      </div>

                                      <div align="right" class="right-col col-md-6">
                                         @if($product->tax_r =='')  {{ sprintf("%.2f", $commissionRate) }} @else {{   sprintf("%.2f",$commissionRate-$ctax) }} @endif <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i>
                                      </div>
                                    </div>
                                    

                                    @if($product->tax_r !='')

                                    <div class="row">

                                      <div align="left" class="left-col col-md-6">
                                        <b>Commission Tax:</b> 
                                      </div>

                                      <div align="right" class="right-col col-md-6">
                                         {{ sprintf("%.2f", $ctax) }} <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i>
                                      </div>

                                    </div>

                                    <div class="row">
                                      <div align="left" class="left-col col-md-6">
                                        <b>Gross Commission:</b>
                                      </div>
                                      <div align="right" class="right-col col-md-6">
                                         {{ sprintf("%.2f", $commissionRate) }} <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i>
                                        <br>
                                        <small>@if($product->tax_r !='') (Incl. of Tax) @endif</small>
                                      </div>
                                    </div>

                                    @endif
                                  </div>
                              @endif
                                      

                                </div>
                                 <div class="row">
                                   <div class="{{ $product->vender_offer_price !='' ? "col-md-6" : "col-md-12" }}">
                                    <hr>
                                      <h4>Final Selling Price</h4>
                                    <hr>
                                     @if($product->tax_r !='')
                                     <div class="row">
                                       <div align="left" class="left-col col-md-6">
                                         <b>Selling Price:</b>
                                       </div>
                                       <div align="right" class="right-col col-md-6">

                                         {{ sprintf("%.2f", $product->vender_price+$mpc ) }} <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i> <br> <small>(Incl. of Tax)</small>
                                        
                                       </div>
                                     </div>
                                      
                                    
                                      @else
                                        <div class="row">
                                          <div align="left" class="left-col col-md-6">
                                            <b>Selling Priced:</b>
                                          </div>
                                          <div align="right" class="right-col col-md-6">
                                             {{ sprintf("%.2f", $show_price) }} <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i> <br> <small>(Excl. of Tax)</small>
                                          </div>
                                        </div>
                                        
                                      

                                      @endif
                                   </div>
                                @if($product->vender_offer_price != '')
                                   <div class="col-md-6">
                                    <hr>
                                      <h4>Final Selling Offer Price</h4>
                                    <hr>
                                     @if($product->tax_r !='')

                                     <div class="row">
                                      <div align="left" class="left-col col-md-6">
                                        <b>Selling Offer Price:</b>
                                      </div>
                                      <div align="right" class="right-col col-md-6">
                                        {{ sprintf("%.2f", $product->vender_offer_price+$commissionRate) }} <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i> <br> <small>(Incl. of Tax)</small>
                                      </div>
                                      
                                    </div>
                                     
                                      
                                    @else
                                    <div class="row">
                                      <div align="left" class="left-col col-md-6">
                                        <b>Selling Offer Price:</b>
                                      </div>
                                      <div align="right" class="right-col col-md-6">
                                         {{ sprintf("%.2f", $convert_price) }} <i class="cur_sym fa {{ $defCurrency->currency_symbol }}"></i> <br> <small>(Excl. of Tax)</small>
                                      </div>
                                    </div>

                                    @endif
                                      
                                      
                                     
                                      
                                   </div>
                                @endif
                                 </div>
                              </div>
                              <div class="modal-footer">
                                
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                
                              </div>
                            </div>
                          </div>
                        </div>

                         

                        </tr>
                      @endforeach
          </tbody>
        </table>
      </div>
</div>


      <!-- Bulk Delete Modal -->
      <div id="bulk_delete" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
              <h4 class="modal-heading">Are You Sure ?</h4>
              <p>Do you really want to delete these products? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
             <form id="bulk_delete_form" method="post" action="{{ route('pro.bulk.delete') }}">
              @csrf
              {{ method_field('DELETE') }}
                <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
              </form>
            </div>
          </div>
        </div>
      </div>

@foreach($products as $key=> $product)
    <div id="{{ $product->id }}pro" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
              <h4 class="modal-heading">Are You Sure ?</h4>
              <p>Do you really want to delete this product? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
               <form method="post" action="{{url('admin/products/'.$product->id)}}" class="pull-right">
                             {{csrf_field()}}
                             {{method_field("DELETE")}}
                <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
              </form>
            </div>
          </div>
        </div>
      </div>
  @endforeach

@endsection
