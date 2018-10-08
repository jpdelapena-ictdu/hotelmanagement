@extends('backpack::layout')

@section('after_styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- include select2 css-->
<link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
        <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.add') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<!-- Default box -->
		@if ($crud->hasAccess('list'))
			<a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
		@endif

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
		  <div class="box">

		    <div class="box-header with-border">
		      <h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} {{ $crud->entity_name }}</h3>
		    </div>
		    <div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      {{-- @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @else
		      	@include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @endif --}}

          		<div class="form-group col-xs-12">
    				<label>Customer</label>
    				<select name="customer_id" style="width: 100%" class="form-control select2_from_array">
    					@foreach($customers as $row)
						<option value="{{$row->customer_id}}">{{$row->customer_id. '. ' .$row->firstname. ' ' .$row->lastname}}</option>
						@endforeach
                    </select>
				</div>

				<div class="form-group col-xs-6">
					<label>Arrival</label>
					<input type='text' class='arrival_date form-control' id="arrival_date" name="arrival">
				</div>

				<div class="form-group col-xs-6">
					<label>Departure</label>
					<input type='text' class='departure_date form-control' id="departure_date" name="departure" disabled>
				</div>

          		<div class="form-group col-xs-6" id="roomtype_content">
    				<label id="rtype_label">Roomtype</label>
        			<select id="rtype_select" name="roomtype_id" class="form-control" disabled>

                    </select>
                </div>

                <div class="form-group col-xs-6" id="room_content">
                	<label id="remove_l">Room</label>
                	<select id="remove_s" name="room_id" class="form-control" disabled>

                	</select>
                </div>

                <div class="form-group col-xs-3">
                	<label id="rate_label">Rate</label>
                	<select id="rate_select" name="rate_id" class="form-control" disabled>
                		
                	</select>
                </div>

                <div class="form-group col-xs-3" id="price_div">
   					<label id="price_label">Room Price</label>
   					<input type="text" name="price" id="price_input" class="form-control" disabled>
          		</div>

            	<div class="form-group col-xs-2">
   					<label>Payment</label>
   					<input type="text" name="payment" value="" class="form-control">
          		</div>	

          		<div id="discount_div" class="form-group col-xs-2">
   					<label>Discount</label>
   					<div class="input-group">
				      <input type="text" class="form-control" id="discount" name="discount">
				      <div class="input-group-addon" id="discountBtn">%</div>
				    </div>
          		</div>

          		<div class="col-xs-2">
          			<label>Total</label>
          			<input type="text" name="total" id="total" class="form-control" disabled>
          		</div>	

          		<div class="form-group col-xs-12">
   					<label>Notes</label>
   					<textarea name="notes" value="" class="form-control"></textarea>
          		</div>

          		<div class="form-group col-xs-12">
   					<label>Additional Information</label>
   					<textarea name="notes" value="" class="form-control"></textarea>
          		</div>		

		    </div><!-- /.box-body -->
		    <div class="box-footer">

                @include('crud::inc.form_save_buttons')

		    </div><!-- /.box-footer-->

		  </div><!-- /.box -->
		  </form>
	</div>
</div>

{{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> --}}

@endsection

@section('after_scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
<script>
    jQuery(document).ready(function($) {
        // trigger select2 for each untriggered select2 box
        $('.select2_from_array').each(function (i, obj) {
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {
                $(obj).select2({
                    theme: "bootstrap"
                });
            }
        });
    });
</script>

{{-- roomtype onchange --}}
<script>


$("#discount").on('change keyup paste', function() {
    var price = $('#price_input').val();
    var discount = $('#discount').val();
    var discountedPrice = (price * discount) / 100;
    discountedPrice = price - discountedPrice;

    $('#total').val(discountedPrice);
});

/*$('#discountBtn').on('click', function() {
	$("#discount_div").toggleClass('col-xs-3 col-xs-2');
	$('#discount_div').after('<div class="col-xs-1"><label>Total</label><input type="text" class="form-control" disabled></div>');
});*/

$("select[name='roomtype_id']").change(function(e)
{
 	e.preventDefault();

	var departure = $('#departure_date').val();
	var arrival = $('#arrival_date').val();
	$y = $(this).val();
  	
	$("#remove_s").attr("disabled", false);
  	$("#remove_s").find('option').remove();
  	$("#rate_select").attr("disabled", false);
  	$("#rate_select").find('option').remove();

  	$.ajax
 		({
 			url: '{{ url('admin/getroom') }}/'+$y+'/'+departure+'/'+arrival,
 			type: 'GET',
 			dataType: 'html',
 			success: function(data)
 			{
 				$("#remove_s").append(data);
 			}
 		});

 	$.ajax
 		({
 			url: '{{ url('admin/getrate') }}/'+$y,
 			type: 'GET',
 			dataType: 'html',
 			success: function(data)
 			{
 				$("#rate_select").append(data);
 			}
 		});
  	
});

</script>{{-- roomtype onchange --}}

<script>
	{{-- arrival --}}
	$('.arrival_date').each(function(){
        $(this).datepicker({
	    	onSelect: function(dateText) {
	      		
	      		$("input[name='departure']").attr("disabled", false);
	      		var departure = $('#departure_date').val();
	      		$("#rtype_select").find('option').remove();

	      		if(departure === ''){
	      			// do nothing
	      		} else {
	      			$("input").remove( "#night" );
	  				$("label").remove( "#night_label" );

		      		$.ajax
				 		({
				 			url: '{{ url('admin/getroomtype') }}/'+departure+'/'+this.value,
				 			type: 'GET',
				 			dataType: 'html',
				 			success: function(data)
				 			{
				 				$("#rtype_select").append(data);
				 			}
				 		});
	      		}
	      	}
	   	})
    });{{-- arrival --}}

	{{-- departure --}}
    $('.departure_date').each(function(){
        $(this).datepicker({
	    	onSelect: function(dateText) {

	      		var arrival = $('#arrival_date').val();

				$("#remove_s").attr("disabled", true);
				$("#rtype_select").attr("disabled", false);
  				$("#rtype_select").find('option').remove();

	      		$.ajax
			 		({
			 			url: '{{ url('admin/getroomtype') }}/'+this.value+'/'+arrival,
			 			type: 'GET',
			 			dataType: 'html',
			 			success: function(data)
			 			{
			 				$("#rtype_select").append(data);
			 				// console.log(data);
			 			}
			 		});
	      	}
	   	});
    });{{-- departure --}}

   // rate on change
   	$("select[name='rate_id']").change(function(e)
	{
	 	e.preventDefault();

		$rate_id = $(this).val();
		$roomtype_id = $("select[name='roomtype_id']").val();

		$("input").remove( "#price_input" );
	  	$("label").remove( "#price_label" );

	  	// console.log($rate_id + " " + $roomtype_id);
	  	$.ajax
 		({
 			url: '{{ url('admin/getprice') }}/'+$rate_id+'/'+$roomtype_id,
 			type: 'GET',
 			dataType: 'html',
 			success: function(data)
 			{
 				$("#price_div").append(data);
 			}
 		});

 		setTimeout(function () {
            var t = $('#price_input').val();
			$('#total').val(t);
          }, 1000);
	  	
	});

</script>


@endsection

