<header class="app-header" >
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
      
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row @if (Config::get('app.locale')=='en' ) ms-auto @else me-auto  @endif align-items-center justify-content-end">
            
              <li class="nav-item dropdown">
                <a class="nav-link " href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="{{asset('assets/images/profile/user-1.jpg')}}" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu @if (Config::get('app.locale')=='en' ) dropdown-menu-end @else dropdown-menu-start @endif dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">{{__('main.profile')}}</p>
                    </a>
              
                    @if(Config::get('app.locale')=='en' )
                    <a el="alternate" hreflang="ar"
                    href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-settings fs-6"></i>
                      <p class="mb-0 fs-3"> اللغة العربية  </p>
                    </a>
                    @else
                    <a el="alternate" hreflang="en"
                    href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" class="d-flex align-items-center gap-2 dropdown-item">
                    <i class="ti ti-settings fs-6"></i>
                      <p class="mb-0 fs-3"> English</p>
                    </a>
                    @endif



                    <a href="{{route('logout')}}"  class="btn btn-outline-primary mx-3 mt-2 d-block" 
                         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                         {{__('main.logout')}}</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>