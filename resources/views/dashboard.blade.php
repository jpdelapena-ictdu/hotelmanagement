@extends('backpack::layout')

@section('after_styles')
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">
@endsection

@section('header')
	<section class="content-header">
	  <h1>
	    <span class="text-capitalize">Dashboard</span>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="/admin/dashboard" class="active">Dashboard</a></li>
	  </ol>
	</section>
@endsection

@section('content')

	<div class="row">
		<div class="col-xs-8">
			<div class="box">
		        <div class="box-header hidden-print with-border">
					<h3><i class="fa fa-calendar"></i> Calendar</h3>
		        </div>

		        <div class="box-body overflow-hidden">
					<div id="calendar_div">
						
					</div>
		        </div>
		    </div>
		</div>

		<div class="col-xs-4">
			<div class="col-xs-12">
				<div class="box">
			        <div class="box-header hidden-print with-border">
						<h4 style="display: inline;"><i class="fa fa-sign-in"></i> Expected Guests</h4>
						<a href="{{ route('expected-guests') }}" class="btn btn-primary btn-sm pull-right" style="display: inline;">View all</a>
			        </div>
			        <div class="table-responsive" style="padding-left: 15px; padding-right: 15px;">
						@if(empty($expectedguests))
							<p style="margin-top: 10px;" class="text-center">No data available</p>
				        @endif
				        <table class="table table-hover">
				        	<tbody>
				        		@foreach($expectedguests as $row)
				        		<tr>
				        			<td><i class="fa fa-user"></i> {{ ' '. $row->customer_name . ' | Res. Code: ' . $row->reservation_code . ' | Room: ' .$row->room_name}}</td>
				        		</tr>
				        		@endforeach
				        	</tbody>
				        </table>
			        </div>
			    </div>
			</div>
			<div class="col-xs-12">
				<div class="box">
			        <div class="box-header hidden-print with-border">
						<h4 style="display: inline;"><i class="fa fa-users"></i> Guests Today</h4>
						<a href="{{ route('guests-today') }}" class="btn btn-primary btn-sm pull-right" style="display: inline;">View all</a>
			        </div>
			        <div class="table-responsive" style="padding-left: 15px; padding-right: 15px;">
						@if(empty($gueststoday))
							<p style="margin-top: 10px;" class="text-center">No data available</p>
				        @endif
				        <table class="table table-hover">
				        	<tbody>
				        		@foreach($gueststoday as $row)
				        		<tr>
				        			<td><i class="fa fa-user"></i> {{ ' '. $row->customer_name . ' | Res. Code: ' . $row->reservation_code . ' | Room: ' .$row->room_name}}</td>
				        		</tr>
				        		@endforeach
				        	</tbody>
				        </table>
			        </div>
			    </div>
			</div>
		</div>

		

	</div>

@endsection

@section('after_styles')

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

@endsection

@section('after_scripts')

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>

  <!-- DATA TABLES SCRIPT -->
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js"></script>

  <script>
    $(document).ready(function() {
        // page is now ready, initialize the calendar...
        $('#calendar_div').fullCalendar({
            // put your options and callbacks here
            height : 650,
            events : [
                @foreach($reservations as $reservation)
                {
                    title : '{{ "Customer: " .$reservation->customer_name. " (Reservation Code: " .$reservation->reservation_code. "), Room: " .$reservation->room_name}}',
                    start : '{{ $reservation->arrival }}',
                    end: '{{ $reservation->departure }}',
                    url : ''
                },
                @endforeach
            ]
        })
    });
  </script>

@endsection