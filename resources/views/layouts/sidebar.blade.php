<nav class="sidebar-nav">
    <ul id="sidebarnav">
      <li class="nav-devider"></li>
      <li class="nav-small-cap">MENU</li>
      <li class="{{ Request::is('home*') ? 'active' : '' }}">
        <a class="waves-effect waves-dark" href="{{url('/home')}}" aria-expanded="false">
          <i class="fa fa-dashboard"></i>
          <span class="hide-menu">Dashboard</span>
        </a>
      </li>
      <li class="{{ Request::is('users*') ? 'active' : '' }}">
        <a class="waves-effect waves-dark" href="{{url('/users')}}" aria-expanded="false">
          <i class="fa fa-user"></i>
          <span class="hide-menu">Users
          </span>
        </a>
      </li>
    </ul>
  </nav>
  