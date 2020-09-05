@extends("admin/layouts.master")
@section('title','Widgtes Setting | ')
@section("body")

            <div class="box" >
              <div class="box-header">
                <h3 class="box-title">Widgets settings</h2>
                  
                    <div class="box-body">
                        <table id="full_detail_table" class="width100 table table-bordered table-striped">
                      <thead>
                        <tr class="table-heading-row">
                          <th>ID</th>
                          <th>Widgets Name</th>
                          <th>Home Page</th>
                          <th>Shop Page</th>
						              
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i=1;?>
                        @foreach($widgets as $widget)
                        
                        <tr>
                        <td>{{$i++}}</td>
                        <td>{{ucfirst($widget->name)}}</td>
						            <td>
                          <form action="{{ route('widget.home.quick.update',$widget->id) }}" method="POST">
                              {{csrf_field()}}
                              <button type="submit" class="btn btn-xs {{ $widget->home==1 ? "btn-success" : "btn-danger" }}">
                                {{ $widget->home ==1 ? 'Active' : 'Deactive' }}
                              </button>
                          </form>      
                        </td>
                        <td>
                          <form action="{{ route('widget.shop.quick.update',$widget->id) }}" method="POST">
                              {{csrf_field()}}
                              <button @if(env("DEMO_LOCK") == 0) type="submit" @else title="This action is disabled in demo !" disabled="disabled" @endif class="btn btn-xs {{ $widget->shop==1 ? "btn-success" : "btn-danger" }}">
                                {{ $widget->shop ==1 ? 'Active' : 'Deactive' }}
                              </button>
                          </form>  
                        </td>
                       
						
                       
                        </tr>
                        @endforeach
                        
          </tbody>
        </table>
    
      </div>
      <!-- /.box-body -->
    </div>

@endsection