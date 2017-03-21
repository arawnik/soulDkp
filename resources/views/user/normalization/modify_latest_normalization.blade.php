@extends('layouts.master')

@section('content')
<h1>{{ trans('management.modify_latest_normalization') }}</h1>

@include('errors.flash-div')

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">{{ trans('management.latest_normalization') }}</div>
			<div class="panel-body">
				<div class="form-horizontal">
					<div class="form-group">
						<label for="percent" class="col-sm-3 control-label">{{ trans('management.percent') }}</label>
						<div class="col-sm-3">
							<input id="percent" type="text" name="percent" value="{{ $normalization->normalization_percent }}" class="form-control" readonly>
						</div>
					</div>
					
					<div class="form-group">
						<label for="comment" class="col-sm-3 control-label">{{ trans('management.comment') }}</label>
						<div class="col-sm-6">
							<input id="comment" type="text" name="comment" value="{{ $normalization->normalization_comment }}" class="form-control" readonly>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-warning">
			<div class="panel-heading">Notification</div>
			<div class="panel-body">
				<p>Please note that due to the nature of normalization you can only recalculate the dkp values for your latest normalization.</p>
				<p>This option is there only to allow recalculate for specific characters within the normalization.</p>
				<p>If you want to change the percentage or other info on the normalization, remove and remake it.</p>
			</div>
		</div>
	</div>
</div>


<table class="table table-hover">
	<thead>
		<tr>
			<th>{{ trans('data.character') }}</th>
			<th>{{ trans('management.normalized') }}</th>
			<th>{{ trans('management.recalculate') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($normalization_points as $point)
		<tr>
			<td><a style="color:#{{ $point->class_color }}" class="class_name" href="/char/{{ $point->char_id }}">{{ $point->name }}</a></td>
			<td>{{ $point->normalization_amount }}</td>
			<td>
				<form action="{{ url('update_normalization_points') }}" accept-charset="UTF-8" method="post" class="form-horizontal updateForm">
					{{ csrf_field() }}
					<input type="hidden" id="normalization_id" name="normalization_id" value="{{ $point->normalization_id }}">
					<input type="hidden" id="char_id" name="char_id" value="{{ $point->char_id }}">
					<button type="submit" class="updateBtn"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button>
				</form>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

@endsection

@section('javascript')
<script>

</script>
@endsection