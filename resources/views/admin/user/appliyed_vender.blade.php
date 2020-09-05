@extends("admin/layouts.master")
@section('title','Store Requests | ')
@section("body")

@section('data-field')
User
@endsection

            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Store List</h2>
                      
                       <div class="box-body">
                            <table id="full_detail_table" class="table table-bordered table-striped">
                      <thead>
                        <tr class="table-heading-row">
                          <th>Id</th>
                          <th>User Name</th>
                          <th>Store name</th>
                          <th>Business Email</th>
                          <th>Address</th>
                          <th>Mobile</th>
                          <th>Store Logo</th>
                          <th>Country</th>
                          <th>Status</th>
                          <th>Store Verify</th>
                           <th>Delete Request</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                         <?php $i = 1;  ?>
                        @foreach($stores as $store)
                        <tr>
                          <td>{{$i++}}</td>
                          <td>{{$store->user['name']}}</td>
                          <td>{{$store->name}}</td>
                          <td>{{$store->email}}</td>
                          <td>{{$store->address}}</td>
                          
                          <td>{{$store->mobile}}</td>
                          <td> <img src=" {{url('images/store/'.$store->store_logo)}} " class="height-30"> </td>
                          <td>{{$store->country['country']}}</td>
                          
                          <td>
                            <form action="{{ route('store.quick.update',$store->id) }}" method="POST">
                              {{csrf_field()}}
                              <button type="submit" class="btn btn-xs {{ $store->status==1 ? "btn-success" : "btn-danger" }}">
                                {{ $store->status ==1 ? 'Active' : 'Deactive' }}
                              </button>
                            </form> 
                          </td>
                          <td>@if($store->verified_store==1)
                                 {{'Verified'}}
                                  @else
                                    {{'Not Verified'}}
                                @endif
                          </td>
                          <td>{{$store->rd=='0'?'No Recive':'Recive'}}
                          </td>
                          <td>

                            <div class="row">
                              <div class="col-md-2">
                                <a href=" {{url('admin/stores/'.$store->id.'/edit')}} " class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                              </div>
                              <div  class="col-md-2 margin-left-8">
                                 <button data-toggle="modal" data-target="#{{$store->id}}deletestore" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i>
                              </button>
                              </div>
                            </div>
                              
                             
                          </td>

                          <div id="{{ $store->id }}deletestore" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
              <h4 class="modal-heading">Are You Sure ?</h4>
              <p>Do you really want to delete this vendor? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
             <form method="post" action="{{url('admin/stores/'.$store->id)}}" class="pull-right">
                                {{csrf_field()}}
                                 {{method_field("DELETE")}}
                                  
            
                <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
              </form>
            </div>
          </div>
        </div>
      </div>

                        </tr>
                        @endforeach

                      </tbody>
                    </table>
                
                  </div>
                  <!-- /.box-body -->
                </div>
              </div>

@endsection