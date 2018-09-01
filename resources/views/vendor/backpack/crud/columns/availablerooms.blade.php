{{-- regular object attribute --}}
<td>
	<?php 
		$statusCounter = 0;
		$output = '';
	 ?>
	@foreach($entry->room as $row)
		@if($row->status == 0)
			<?php $statusCounter++; ?>
		@endif
	@endforeach

	{{$statusCounter}}
</td>