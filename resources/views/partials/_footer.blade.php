<!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 h-100 text-center text-lg-left my-auto">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a href="#" class="footertxt">About</a>
              </li>
              <li class="list-inline-item">&sdot;</li>
              <li class="list-inline-item">
                <a href="#" class="footertxt">Contact</a>
              </li>
              <li class="list-inline-item">&sdot;</li>
              <li class="list-inline-item">
                <a href="#" class="footertxt">Terms of Use</a>
              </li>
              <li class="list-inline-item">&sdot;</li>
              <li class="list-inline-item">
                <a href="#" class="footertxt">Privacy Policy</a>
              </li>
            </ul>
            <p class="small mb-4 mb-lg-0 footertxt">&copy; SPCF-ICTDU</p>
          </div>
          <div class="col-lg-6 h-100 text-center text-lg-right my-auto">
            
              @if(Auth::check())
              <div class="dropdown">
                <span>{{ Auth::user()->name }}</span>
              <div>
                  <a class="dropdown-content" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Log Out') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                </div>
            </div>
              @else
              <a href="{{ route('login') }}" class="btn btn-default"> Login </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </footer>