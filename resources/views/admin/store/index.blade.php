@extends("admin/layouts.master")
@section('title',"All Stores |")
@section("body")

<div class="box" >
  <div class="box-header with-border">
    <h3 class="box-title">All Store Request</h3>
    <a href=" {{url('admin/stores/create')}} " class="btn btn-success pull-right">
      + Add new store
    </a>
  </div>

  <div class="box-body">
     <table id="store_table" class="width100 table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>
              Store Logo
            </th>

            <th>
              Store Details
            </th>

            <th>
              Owner
            </th>

            <th>
              Status
            </th>

            <th>Store Request Accepted?</th>
            <th>Request For Delete</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          
        </tbody>
     </table>
  </div>
</div>
@foreach($stores as $store)
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
              <p>Do you really want to delete this store? This process cannot be undone.</p>
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
@endforeach

@endsection
@section('custom-script')
<script>
  var url = {!! json_encode( route('stores.index') ) !!};
</script>
<script src="{{url('js/store.js')}}"></script>
@endsection