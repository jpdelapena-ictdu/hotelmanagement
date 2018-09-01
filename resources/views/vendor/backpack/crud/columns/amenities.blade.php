{{-- regular object attribute --}}
<td>
	<?php $amenityCtr = 1; ?>
	@foreach($entry->amenities as $amenity)
		{{ str_limit(strip_tags($amenity->name), 80, "[...]") }}
		@if($amenityCtr == ($entry->amenities->count() - 1)) and @endif @if($amenityCtr < ($entry->amenities->count() - 1)), @endif
		<?php $amenityCtr++; ?>
	@endforeach
</td>