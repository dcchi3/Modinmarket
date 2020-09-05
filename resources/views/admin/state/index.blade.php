@extends("admin/layouts.master")
@section('title','States | ')
@section("body")

  <div class="col-xs-12">
    <div class="box" >
      <div class="box-header">
        <h3 class="box-title">State</h2>
         
           
            <div class="box-body">
        <table id="state_table" class="table table-hover table-responsive width100">
            
            <thead>
              <tr class="table-heading-row">
                <th>ID</th>
                <th>State </th>
                 <th>Country </th>
                 
              </tr>
            </thead>

              <tbody>
              

              </tbody>

        </table>
        
          </div>
          <!-- /.box-body -->
        </div>
      </div>


@endsection
@section('custom-script')
  <script>var url = {!!json_encode( route('state.index') )!!};</script>
  <script src="{{ url('js/state.js') }}"></script>
@endsection