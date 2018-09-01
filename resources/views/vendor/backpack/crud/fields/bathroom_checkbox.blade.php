<div class="form-group col-xs-12">
    <div class="checkbox">
    	<label>
    	  <input type="hidden" name="{{$field['name']}}" value="0">
    	  <input type="checkbox" value="1" name="{{$field['name']}}" 
    	  @foreach($entry->bathrooms as $bathroom) @if($bathroom->name == $field['label']) checked @endif @endforeach
    	  > {{$field['label']}}
    	</label>
    </div>
</div>