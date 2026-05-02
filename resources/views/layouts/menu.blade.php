<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ url('/') }}">CharityHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background-color: white;">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/campaigns') }}">Campaigns</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/volunteer') }}">Volunteer</a>
        </li>
      </ul>
      <ul class="navbar-nav">
        @auth
          @can('manage_users')
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/users') }}">Manage Users</a>
          </li>
          @endcan
          @can('manage_campaigns')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('campaigns_create') ? 'active' : '' }}" href="{{ route('campaigns_create') }}">Create Campaign</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports_impact') ? 'active' : '' }}" href="{{ route('reports_impact') }}">Impact Reports</a>
                </li>
          @endcan
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/profile') }}">{{ auth()->user()->name }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/logout') }}">Logout</a>
          </li>
        @else
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/login') }}">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/register') }}">Register</a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
