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
        <span class="text-capitalize">{{ $customer->firstname. ' ' .$customer->lastname }}</span>
        <small>Transaction</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url('admin/dashboard') }}">Admin</a></li>
	    <li><a href="{{ backpack_url('customer') }}" class="text-capitalize">Customers</a></li>
	    <li class="active">Transaction</li>
	  </ol>
	</section>
@endsection

@section('content')

<div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="col-md-12">
      <div class="box">
        <div class="box-header hidden-print with-border">
        
          <a href="{{ route('customer.paid', $customer->customer_id) }}" class="btn btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-eye"></i> View Paid Transactions</span></a>
        	  		  			  	
          <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
        </div>

        <div class="box-body overflow-hidden">
			
            <table id="customerTable" class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>                    
                </thead>
                <tbody>
                  @foreach($unpaidTransactions as $row)
                    <tr>
                        <td style="width: 50%;">{{ $row->description }}</td>
                        <td style="width: 30%;">{{ $row->created_at->toDayDateTimeString() }}</td>
                        <td id="amount">{{ $row->amount }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table">
              <tbody>
                <tr>
                  <th style="width: 80%;">Total</td>
                  <th id="total"></th>
                </tr>
              </tbody>
            </table>

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

<!-- <script>
    $(document).ready( function () {
        $('#customerTable').DataTable();
    } );
</script> -->

<script>
  $( document ).ready(function() {
    var table = $('#customerTable tbody');
    var total = 0;

    table.find('tr').each(function() {
      var $tds = $(this).find('td'),
          amount = $tds.eq(2).text();
          amount = parseInt(amount);

          total = total + amount;
          console.log(amount);
    });

    console.log(total);
    $('#total').text(total);

    
  });
</script>
@endsection