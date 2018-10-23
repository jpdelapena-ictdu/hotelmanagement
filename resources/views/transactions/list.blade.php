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
	    <span class="text-capitalize">Transactions</span>
	    <small>All transactions in the database.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="/admin/dashboard" class="text-capitalize">Dashboard</a></li>
	    <li class="active">Transactions</li>
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

          <button class="btn btn-primary" data-toggle="modal" data-target="#transactionModal"><i class="fa fa-plus"></i> Add Transaction</button>

          <!-- Modal -->
          <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Add Transaction</h4>
                </div>
                <div class="modal-body">

                  <div style="padding-top: 10px; padding-bottom: 10px;">

                    <form method="POST" id="serviceForm">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label>Customer</label>
                        <select id="customer_id" name="customer_id" class="form-control">
                          <option> --- </option>
                          @foreach($customers as $row)
                          <option value="{{ $row->customer_id }}">{{ $row->name . ' ( Room ' . $row->room_name . ' )' }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div style="border-bottom: 1px solid #3c8dbc; margin-top: 20px; margin-bottom: 20px;"></div>

                      <div class="row" style="margin-bottom: 30px;">

                        @foreach($services as $row)
                        <div class="col-md-2" style="margin-top:10px; word-wrap: break-word">
                          <label>{{ $row->service }}</label>
                          <input type="text" name="{{ $row->id }}" class="form-control">
                        </div>
                        @endforeach

                      </div>
                    </form>

                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="serviceForm" formaction="{{ route('transaction-service.store') }}">Submit</button>
                  </div>

                </div>
                
              </div>
            </div>
          </div>

          <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
        </div>

        <div class="box-body overflow-hidden">

          <table class="table" id="transactionTable">
            <thead>
              <tr>
                <th>Customer Name</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($transactions as $row)
              <tr>
                <td>{{ $row->customer_name }}</td>
                <td>{{ $row->description }}</td>
                <td>{{ $row->amount }}</td>
                <td>{{ $row->date }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </div><!-- /.box-body -->

      </div><!-- /.box -->
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
        $('#transactionTable').DataTable();
    } );
  </script>
@endsection
