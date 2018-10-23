@extends('backpack::layout')

@section('after_styles')
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- include select2 css-->
<link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Rooms</span>
        <small>Calendar</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url('admin/dashboard') }}">Admin</a></li>
	    <li><a href="{{ backpack_url('room') }}" class="text-capitalize">Rooms</a></li>
	    <li class="active">Calendar</li>
	  </ol>
	</section>
@endsection

@section('content')

<div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="col-md-12">
        <div class="box">
        </div>

        <div class="box-body overflow-hidden">
			<div id="calendar">
				
			</div>
        </div>
      </div>
    </div>

@endsection

@section('after_scripts')

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<script>
    $(document).ready(function() {
        // page is now ready, initialize the calendar...
        $('#calendar').fullCalendar({
            // put your options and callbacks here
            height : 650,
            events : [
                @foreach($reservations as $reservation)
                {
                    title : '{{ "Customer: " .$reservation->customer_name. " (Reservation Code: " .$reservation->reservation_code. ")"}}',
                    start : '{{ $reservation->arrival }}',
                    end: '{{ date('Y-m-d', strtotime($reservation->departure . ' +1 day')) }}',
                    url : ''
                },
                @endforeach
            ]
        })
    });
</script>

@endsection