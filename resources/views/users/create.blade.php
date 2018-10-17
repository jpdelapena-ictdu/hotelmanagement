@extends('backpack::layout')

@section('after_styles')
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">
  <style>
  	.form-group.required label:not(:empty)::after {
	    content: ' *';
	    color: #ff0000;
	}
  </style>
@endsection

@section('header')
	<section class="content-header">
	  <h1>
	    <span class="text-capitalize">Users</span>
	    <small>Add user.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="/admin/dashboard">Admin</a></li>
	    <li><a href="{{ route('users.index') }}" class="active">Users</a></li>
	    <li class="active">Add</li>
	  </ol>
	</section>
@endsection

@section('content')

	<!-- Main content -->
    <section class="content">

        <div class="row">
			<div class="col-md-8 col-md-offset-2">
			<!-- Default box -->
			<a href="{{ route('users.index') }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> Back to all  <span>users</span></a><br><br>
			
			@if(count($errors) > 0)
			<div class="callout callout-danger">
        		<h4>Please fix the following errors:</h4>
        		<ul>
        			@foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
    		</div>
    		@endif

		  	<form method="post" action="{{ route('users.store') }}" >
		  		{{ csrf_field() }}
		  		<input type="hidden" name="_token" value="Sx55uhy4MGQHd54MqX9OzcjmyhHlv2p5nV7zNeBU">
		  		<div class="box">
		    		<div class="box-header with-border">
		      			<h3 class="box-title">Add a new  user</h3>
		    		</div>
			    	<div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
	    
		    			<!-- text input -->
						<div class="form-group col-xs-12 required">
		    				<label>Name</label>
		    				<input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
		    			</div>

		    			<!-- text input -->
						<div class="form-group col-xs-12 required">
		    				<label>Email</label>
		    				<input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
		    			</div>

			      	</div><!-- /.box-body -->
			    	<div class="box-footer">
						
						<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
			    		<a href="{{ route('users.index') }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>

			    	</div><!-- /.box-footer-->
		  		</div><!-- /.box -->
		  	</form>
			</div>
		</div>

	</section>
    <!-- /.content -->

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

@endsection