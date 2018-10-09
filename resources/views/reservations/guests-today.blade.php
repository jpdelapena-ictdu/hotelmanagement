@extends('backpack::layout')

@section('after_styles')
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- include select2 css-->
<link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
 <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">
@endsection

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Guests Today</span>
        <small>Guests checked in as of today</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url('admin/dashboard') }}">Admin</a></li>
	    <li><a href="{{ backpack_url('reservations') }}" class="text-capitalize">Reservations</a></li>
	    <li class="active">Expected Guests</li>
	  </ol>
	</section>
@endsection

@section('content')

<div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="col-md-12">
      <div class="box">
        <div class="box-header hidden-print with-border">
        
          
        	  		  			  	
          <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
        </div>

        <div class="box-body overflow-hidden">
			
            <table id="reservationTable" class="table">
                <thead>
                    <tr>
                        <th>Reservation Code</th>
                        <th>Customer Name</th>
                        <th>Room Type</th>
                        <th>Room</th>
                        <th>Rate</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Check in</th>
                        <th>Check out</th>
                        <th>Payment</th>
                        <th>Additional Information</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>                    
                </thead>
                <tbody>
                	@foreach($reservations as $row)
                  	<tr>
                  		<td>{{ $row->reservation_code }}</td>
                  		<td>{{ $row->customer_name }}</td>
                  		<td>{{ $row->roomtypecode }}</td>
                  		<td>{{ $row->roomcode }}</td>
                  		<td>{{ $row->ratecode }}</td>
                  		<td>{{ $row->arrival }}</td>
                  		<td>{{ $row->departure }}</td>
                  		<td>{{ $row->check_in }}</td>
                  		<td>{{ $row->check_out }}</td>
                  		<td>{{ $row->payment }}</td>
                  		<td>{{ $row->notes }}</td>
                  		<td>{{ $row->additional_information }}</td>
                  		<td><a href="#" id="checkOutBtn" data-id="{{ $row->id }}" class="btn btn-xs btn-success"><i class="fa fa-sign-out"></i> Check out</a>

						</form></td>
                  	</tr>
                  	@endforeach
                </tbody>
            </table>

        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">TITLE</h4>
          </div>
          <div class="modal-body">
            <div class="container">
              <form method="POST" id="billOutForm">
                {{ csrf_field() }}
                <div id="checkoutDiv">
                  <table id="billTable">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                  </table>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" form="billOutForm" formaction="{{ route('check-out') }}">Bill Out</button>
          </div>
        </div>
      </div>
    </div>

@endsection

@section('after_scripts')

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<!-- DATA TABLES SCRIPT -->
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js"></script>

<script>


    $(document).ready( function () {
        $('#reservationTable').DataTable({
        	"iDisplayLength": 50,
			"aLengthMenu": [[10, 25, 50, 100,500,1000,-1], [10, 25, 50,100,500,1000, "All"]],
        });
    } );

    $('body').delegate('#checkOutBtn', 'click', function(e) {
      var id = $(this).data('id');

      $.ajax({
        url: '{{ url('admin/checkoutcustomer') }}/'+id,
        type: 'GET',
        dataType: "JSON",
        success: function (data)
        {
          var dataLength = data.length;
          var html = '';
          var total = 0;
          for(var i = 0; i < dataLength; i++) {
            console.log(data[i]['description']);
            html += '<tr><th style="width: 90%;">'+data[i]['description']+'</th><td style="width: 10%;">'+data[i]['amount']+'</td></tr><input type="hidden" name="transaction'+data[i]['id']+'" value="'+data[i]['id']+'">';
            total = total + data[i]['amount'];
          }

          html += '<tr><th style="width: 90%;">Total</th><th style="width: 10%;">'+total+'</th><input type="hidden" value="'+id+'" name="reservation_id"></tr>';
           console.log(html);
           jQuery.noConflict();
           $('#myModal').modal('show')
           $('#billTable tbody').append(html);
        }
      });

      // $('#billTable tbody').empty();

    });

</script>
@endsection