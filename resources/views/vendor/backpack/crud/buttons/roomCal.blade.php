 @if ($crud->hasAccess('room_cal'))

	<a href="#" class="btn btn-default btn-xs" id="showCalendarModal" data-id="{{ $entry->getKey() }}" ><i class="fa fa-search"></i> Availability</a>

@endif