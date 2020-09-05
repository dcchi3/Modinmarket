<ul class="nav table-nav">
  <li class="dropdown">
  <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
    Action <span class="caret"></span>
  </a>
  <ul class="dropdown-menu dropdown-menu-right">
      <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/stores/'.$id.'/edit')}}">
      <i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit Store</a></li>
      
      
      <li role="presentation" class="divider"></li>
      <li role="presentation">
        <a @if(env('DEMO_LOCK') == 0) data-toggle="modal" href="#{{$id}}deletestore" @else disabled title="This action is disabled in demo !" @endif>
          <i class="fa fa-trash-o" aria-hidden="true"></i>Delete
        </a>
      </li>
  </ul>
  </li>
</ul>
