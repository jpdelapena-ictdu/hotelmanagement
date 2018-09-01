{{-- regular object attribute --}}
<td>
	<?php $foodCtr = 1; ?>
	@foreach($entry->foods as $food)
		{{ str_limit(strip_tags($food->name), 80, "[...]") }}
		@if($foodCtr == ($entry->foods->count() - 1)) and @endif @if($foodCtr < ($entry->foods->count() - 1)), @endif
		<?php $foodCtr++; ?>
	@endforeach
</td>