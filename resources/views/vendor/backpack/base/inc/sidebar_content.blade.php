<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li class="treeview">
    <a href="#"><i class="fa fa-indent"></i> <span>Hotel Management</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
			<li><a href="{{ backpack_url('room') }}"><i class="fa fa-key"></i> <span>Rooms</span></a></li>
			<li><a href="{{ backpack_url('roomtype') }}"><i class="fa fa-bed"></i> <span>Room Types</span></a></li>
			<li><a href="{{ backpack_url('building') }}"><i class="fa fa-building"></i> <span>Buildings</span></a></li>
			<li><a href="{{ backpack_url('rate') }}"><i class="fa fa-cc-mastercard"></i> <span>Rates</span></a></li>
			<li><a href="{{ backpack_url('board') }}"><i class="fa fa-coffee"></i> <span>Boards</span></a></li>
			<li><a href="{{ backpack_url('roomrate') }}"><i class="fa fa-credit-card"></i> <span>Room Rates</span></a></li>
		</ul>
</li>

