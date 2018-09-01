{{-- regular object attribute --}}
<td>
	<?php $bathroomCtr = 1; ?>
	@foreach($entry->bathrooms as $bathroom)
		{{ str_limit(strip_tags($bathroom->name), 80, "[...]") }}
		@if($bathroomCtr == ($entry->bathrooms->count() - 1)) and @endif @if($bathroomCtr < ($entry->bathrooms->count() - 1)), @endif
		<?php $bathroomCtr++; ?>
	@endforeach
</td>