@extends('main')
    @section('title', '| Home')
@section('content')


    <!-- Icons Grid -->
        <div class="row">
          <div class="col-lg-3 center posright">
            <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
              <div class="features-icons-icon d-flex">
                <a href="#"><button class="button m-auto icons">RESERVATION</button></a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 center posleft">
            <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
              <div class="features-icons-icon d-flex">
                <a href="#"><button class="button m-auto icons">CLIENT</button></a>
              </div>
            </div>
          </div>
          
        </div>

        <div class="row">
            <div class="col-lg-3 center posright">
                <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                    <div class="features-icons-icon d-flex">
                        <a href="#"><button class="button m-auto icons">REPORTS</button></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 center posleft">
                <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                    <div class="features-icons-icon d-flex">
                        <a href="#"><button class="button m-auto icons">SETTINGS</button></a>
                    </div>
                </div>
            </div>
        </div>

      @endsection