 @if ($crud->hasAccess('check_in'))
 <button form="resetStudent{{ $entry->getKey() }}" class="btn btn-xs btn-success"><i class="fa fa-sign-in"></i> Check in</button>

	<form onsubmit="return confirmReset()" id="resetStudent{{ $entry->getKey() }}" method="POST" action="#">
		{{ csrf_field() }}

	</form>
@endif

<script>
	function confirmReset()
	{
	var x = confirm("Are you sure you want to reset this Student's password?");
	if (x)
	return true;
	else
	return false;
	}   
</script>