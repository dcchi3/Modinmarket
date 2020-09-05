<ul class="nav table-nav">
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        Action <span class="caret"></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">
          @php
            $cryptid = Crypt::encrypt($id);
          @endphp
          <li role="presentation"><a title="IT will expire your session and run this user session in same window !" role="menuitem" tabindex="-1" href="{{route('login.as',$cryptid)}}"><i class="fa fa-lock" aria-hidden="true"></i> Login as this user</a></li>

          <li role="presentation" class="divider"></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/users/'.$id.'/edit')}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit User</a></li>
          
          
          <li role="presentation" class="divider"></li>
          <li role="presentation">
            <a @if(env('DEMO_LOCK') == 0) data-toggle="modal" href="#{{ $id }}deleteuser" @else title="This action is disabled in demo !" disabled="disabled" @endif>
               <i class="fa fa-trash-o" aria-hidden="true"></i>Delete
            </a>
          </li>
      </ul>
    </li>
</ul>
  