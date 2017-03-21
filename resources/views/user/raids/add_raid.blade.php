<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">{{ trans('management.add_raid') }}</div>
			<div class="panel-body">
				<form action="{{ url('raid_management') }}" accept-charset="UTF-8" method="POST" class="form-horizontal">
					{{ csrf_field() }}
					
					<div class="form-group{{ $errors->has('value') ? ' has-error' : '' }}">
						<label for="value" class="col-sm-3 control-label">{{ trans('common.currency') }} {{ trans('data.value') }}</label>
						<div class="col-sm-6">
							<input id="value" type="text" name="value" value="{{ old('value') }}" class="form-control"  autofocus>
						</div>
						@if($errors->has('value'))
						<div class="col-sm-3">
							<span class="help-block"><strong>{{ $errors->first('value') }}</strong></span>
						</div>
						@endif
					</div>
					
					<div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
						<label for="comment" class="col-sm-3 control-label">{{ trans('data.comment') }}</label>
						<div class="col-sm-6">
							<input id="comment" type="text" name="comment" value="{{ old('comment') }}" class="form-control" >
						</div>
						@if($errors->has('comment'))
						<div class="col-sm-3">
							<span class="help-block"><strong>{{ $errors->first('comment') }}</strong></span>
						</div>
						@endif
					</div>
					
					<div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
						<label for="date" class="col-sm-3 control-label">{{ trans('data.date') }}</label>
						<div class="col-sm-6">
							<input id="date" type="date" name="date" value="{{ old('date') }}" class="form-control" >
						</div>
						@if($errors->has('date'))
						<div class="col-sm-3">
							<span class="help-block"><strong>{{ $errors->first('date') }}</strong></span>
						</div>
						@endif
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<input class="btn btn-primary form-control" type="submit" value="{{ trans('management.add_raid') }}">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>