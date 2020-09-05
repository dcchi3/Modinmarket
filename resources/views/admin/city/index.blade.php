@extends("admin/layouts.master")
@section('title','Cities | ')
@section("body")


    <div class="box">
      <div class="box-header">
        <div class="box-title">City</div>
      </div>
      
      <div class="box-body">
        <table id="citytable" class="table table-hover table-responsive">
          <thead>
            <tr class="table-heading-row">
              
              <th>ID</th>
              <th>City </th>
              <th>State </th>
              <th>Country</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>
    </div>
  </div>



        <!-- /page content -->
@endsection
@section('custom-script')
   <script>var url = {!!json_encode(route('city.index'))!!};</script>
  <script src="{{ url('js/city.js') }}"></script>
@endsection