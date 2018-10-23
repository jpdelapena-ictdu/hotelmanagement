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
	    <span class="text-capitalize">Logs</span>
	    <small>All logs in the database.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="/admin/dashboard" class="text-capitalize">Dashboard</a></li>
	    <li class="active">Logs</li>
	  </ol>
	</section>
@endsection

@section('content')

<!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="col-md-12">
      <div class="box">
        <div class="box-header hidden-print with-border">

        </div>

        <div class="box-body overflow-hidden">

          <table class="table" id="logsTable">
            <thead>
              <tr>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Room</th>
                <th>Checked In</th>
                <th>Checked Out</th>
              </tr>
            </thead>
            <tbody>
              @foreach($reservations as $row)
              <tr>
                <td>{{ $row->customer_id }}</td>
                <td>{{ $row->customer_name }}</td>
                <td>{{ $row->room_name }}</td>
                <td>{{ $row->check_in }}</td>
                <td>{{ $row->check_out }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </div><!-- /.box-body -->

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
    $(document).ready( function () {
        $('#logsTable').DataTable({
        	"iDisplayLength": 50,
			"aLengthMenu": [[10, 25, 50, 100,500,1000,-1], [10, 25, 50,100,500,1000, "All"]],
        });
    } );
  </script>
@endsection
