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
	    <span class="text-capitalize">Users</span>
	    <small>All users in the database.</small>
	  </h1>
	  <ol class="breadcrumb">
	    {{-- <li><a href="#">tttt</a></li> --}}
	    <li><a href="/admin/dashboard" class="active">Dashboard</a></li>
	    <li class="active">Users</li>
	  </ol>
	</section>
@endsection

@section('content')

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
		        <div class="box-header hidden-print with-border">
					<a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</a>
		        </div>

		        <div class="box-body overflow-hidden">
					<table class="table" id="usersTable">
						<thead>
							<tr>
								<th>Id</th>
								<th>Name</th>
								<th>Email</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach($users as $row)
							<tr>
								<td>{{ $row->id }}</td>
								<td>{{ $row->name }}</td>
								<td>{{ $row->email }}</td>
								<td><a href="{{ route('users.edit', $row->id) }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> Edit</a>
								<button form="deleteUser{{ $row->id }}" class="btn btn-xs btn-default"><i class="fa fa-trash"></i> Delete</button> <button form="resetUser{{ $row->id }}" class="btn btn-xs btn-danger"><i class="fa fa-lock"></i> Reset Password</button>

								<form onsubmit="return confirmDelete()" id="deleteUser{{ $row->id }}" method="POST" action="{{ route('users.destroy', $row->id) }}">
									<input type="hidden" name="_token" value="{{ Session::token() }}">
                                    {{ method_field('DELETE') }}

								</form>

								<form onsubmit="return confirmReset()" id="resetUser{{ $row->id }}" method="POST" action="{{ route('user.reset.password', $row->id) }}">
									{{ csrf_field() }}

								</form>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
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
    $(document).ready( function () {
        $('#usersTable').DataTable({
        	"iDisplayLength": 25,
			"aLengthMenu": [[10, 25, 50, 100,500,1000,-1], [10, 25, 50,100,500,1000, "All"]],
        });
    } );
  </script>
  <script>
	function confirmDelete()
	{
	var x = confirm("Are you sure you want to delete this student?");
	if (x)
	return true;
	else
	return false;
	}   
	</script>

	<script>
	function confirmReset()
	{
	var x = confirm("Are you sure you want to reset this User's password?");
	if (x)
	return true;
	else
	return false;
	}   
</script>
@endsection