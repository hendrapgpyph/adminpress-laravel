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
       <li class="{{ Request::is('transaction*') ? 'active' : '' }}">
        <a class="waves-effect waves-dark" href="{{url('/transaction')}}" aria-expanded="false">
          <i class="fa fa-exchange"></i>
          <span class="hide-menu">Transaction</span>
        </a>
      </li>
      @if(Auth::user()->role =='master')
      <li class="{{ Request::is('users*') ? 'active' : '' }}">
        <a class="waves-effect waves-dark" href="{{url('/users')}}" aria-expanded="false">
          <i class="fa fa-users"></i>
          <span class="hide-menu">Users
          </span>
        </a>
      </li>
      @endif
      <li class="{{ Request::is('doc*') ? 'active' : '' }}">
        <a class="waves-effect waves-dark" href="{{url('/doc')}}" aria-expanded="false">
          <i class="fa fa-file"></i>
          <span class="hide-menu">Documentation
          </span>
        </a>
      </li>
    </ul>
  </nav>
  