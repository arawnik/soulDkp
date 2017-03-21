@extends('layouts.master')

@section('content')

<h1>{{ trans('management.modify_raid') }}</h1>
<p>{{ trans('management.modify_note') }}</p>

@include('errors.flash-div')

<div class="row">
	@include('user.raids.modify_raid_data')
</div>

<div class="row">
	@include('user.raids.add_item')

	@include('user.raids.add_adjustment')
</div>

<div class="row">
	<div class="col-md-6">
		<h2>{{ trans('management.added_items') }}</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>{{ trans('data.character') }}</th>
					<th>{{ trans('data.price') }}</th>
					<th>{{ trans('data.item') }}</th>
					<th>{{ trans('management.delete') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($raid_items as $ri)
				<tr>
					<td><a style="color:#{{ $ri->class_color }}" class="class_name" href="/char/{{ $ri->char_id }}">{{ $ri->name }}</a></td>
					<td>{{ $ri->use_amount}}</td>
					<td>{{ $ri->use_desc}}</td>
					<td>
						<form action="{{ url('delete_raid/item') }}" accept-charset="UTF-8" method="post" class="form-horizontal deleteForm">
							{{ csrf_field() }}
							<input type="hidden" id="item_id" name="item_id" value="{{ $ri->use_id }}">
							<input type="hidden" id="raid_id" name="raid_id" value="{{ $raid_id }}">
							<button type="submit" class="deleteBtn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
						</form>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<h2>{{ trans('management.added_adjustments') }}</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>{{ trans('data.character') }}</th>
					<th>{{ trans('data.amount') }}</th>
					<th>{{ trans('data.reason') }}</th>
					<th>{{ trans('management.delete') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($raid_adjustments as $ra)
				<tr>
					<td><a style="color:#{{ $ra->class_color }}" class="class_name" href="/char/{{ $ra->char_id }}">{{ $ra->name }}</a></td>
					<td>{{ $ra->adjust_value}}</td>
					<td>{{ $ra->adjust_comment}}</td>
					<td>
						<form action="{{ url('delete_raid/adjustment') }}" accept-charset="UTF-8" method="post" class="form-horizontal deleteForm">
							{{ csrf_field() }}
							<input type="hidden" id="char_id" name="char_id" value="{{ $ra->char_id }}">
							<input type="hidden" id="raid_id" name="raid_id" value="{{ $raid_id }}">
							<button type="submit" class="deleteBtn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
						</form>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@include('user.raids.modify_attendance')

@endsection

@section('javascript')
<script>
$().ready(function() {  
	/**
	 * When #add button is clicked, all selected rows are moved from #characters to #selected_chars
	 */
	$('#add').click(function() {  
		var ret = !$('#characters option:selected').remove().appendTo('#selected_chars');  
		
		$("#selected_chars").val([]);
		return ret;
	});
	
	/**
	 * When #remove button is clicked all selected rows are moved from #selected_chars to #characters 
	 */
	$('#remove').click(function() {  
		var ret = !$('#selected_chars option:selected').remove().appendTo('#characters');  
		$("#characters").val([]);
		return ret;
	});  
});
</script>
@endsection