<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ asset('dist/img/default-160x160.jpg') }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{{ Sentinel::getUser()->first_name }}</p>
        <a href="" onclick="return false"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- search form -->
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat" style="min-width: auto;"><i class="fa fa-search"></i></button>
        </span>
      </div>
    </form>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li>
      <li class="{{ Request::is('/') ? 'active' : '' }}{{ Request::is('dashboard') ? 'active' : '' }}">
        <a href="{{ url('/') }}">
          <i class="fa fa-home"></i> 
          <span>Home</span>
        </a>
      </li>
      <li class="treeview {{ Request::is('news') ? 'active' : '' }}">
        <a href="{{ route('news') }}">
          <i class="fa fa-newspaper-o" aria-hidden="true"></i>
          <span>News</span>
        </a>
      </li>
      <li class="treeview {{ Request::is('articles') ? 'active' : '' }}{{ Request::is('articles/*') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-file-text" aria-hidden="true"></i>
          <span>Articles</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="{{ Request::is('articles/create') ? 'active' : '' }}">
            <a href="{{ route('articles.create') }}"><i class="fa fa-circle-o"></i> Create Article</a>
          </li>
          <li class="{{ Request::is('articles') ? 'active' : '' }}">
            <a href="{{ route('articles.index') }}"><i class="fa fa-circle-o"></i> View Articles</a>
          </li>

        </ul>
      </li>
      <li class="treeview {{ Request::is('modules') ? 'active' : '' }}{{ Request::is('modules/*') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-book" aria-hidden="true"></i>
          <span>Modules</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          @for($i=0; $i<$course->semester; $i++)
              <li class="{{ Request::is('modules/') ? 'active' : '' }}">

                <a href="{{ route('modules.index', $i+1) }}"><i class="fa fa-circle-o"></i>{{'Semester '.($i+1) }}</a>

              </li>
          @endfor   
        </ul>
      </li>
      <li class="{{ Request::is('messages') ? 'active' : '' }}{{ Request::is('messages/*') ? 'active' : '' }}">
        <a href="{{ route('messages.index') }}">
          @if($count != 0)
            <i class="fa fa-envelope"></i> 
            <span>Mailbox</span>
            <small class="label pull-right bg-yellow">{{ $count }}</small>
          @else
            <i class="fa fa-envelope"></i> 
            <span>Mailbox</span>
          @endif
        </a>
      </li>
      <li class="{{ Request::is('courseInfo') ? 'active' : '' }}">
        <a href="{{ route('courseInfo.index') }}">
            <i class="fa fa-graduation-cap"></i> 
            <span>Course Info</span>
        </a>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>