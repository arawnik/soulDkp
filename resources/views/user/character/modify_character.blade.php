@extends('layouts.master')

@section('content')

<h1>{{ trans('management.modify_character') }}</h1>

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">{{ trans('management.character_info') }}</div>
			<div class="panel-body">
				<form action="{{ url('update_character') }}" accept-charset="UTF-8" method="POST" class="form-horizontal">
					{{ csrf_field() }}
					<input type="hidden" id="char_id" name="char_id" value="{{ $char_id }}">
					
					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
						<label for="name" class="col-sm-3 control-label">{{ trans('data.name') }}</label>
						<div class="col-sm-6">
							<input id="name" type="text" name="name" value="{{ $character->char_name}}" class="form-control"  autofocus>
						</div>
						@if($errors->has('name'))
						<div class="col-sm-3">
							<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
						</div>
						@endif
					</div>
					
					<div class="form-group{{ $errors->has('class') ? ' has-error' : '' }}">
						<label for="class" class="col-sm-3 control-label">{{ trans('data.class') }}</label>
						<div class="col-sm-6">
							<select class="form-control" id="class" name="class">
								@foreach ($classes as $class)
									<option value="{{ $class->class_id}}" @if ($class->class_id == $character->char_class) selected @endif>{{ $class->class_name}}</option>
								@endforeach
							</select>
						</div>
						@if($errors->has('class'))
						<div class="col-sm-3">
							<span class="help-block"><strong>{{ $errors->first('class') }}</strong></span>
						</div>
						@endif
					</div>
					
					<div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
						<label for="role" class="col-sm-3 control-label">{{ trans('data.role') }}</label>
						<div class="col-sm-6">
							<select class="form-control" id="role" name="role">
								@foreach ($roles as $role)
									<option value="{{ $role->role_id}}" @if ($role->role_id == $character->char_role) selected @endif>{{ $role->role_name}}</option>
								@endforeach
							</select>
						</div>
						@if($errors->has('role'))
						<div class="col-sm-3">
							<span class="help-block"><strong>{{ $errors->first('role') }}</strong></span>
						</div>
						@endif
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<input class="btn btn-primary form-control" type="submit" value="{{ trans('management.update_character') }}">
						</div>
					</div>
					
					
					
					<!--div class="row">
						<label for="name" class="col-sm-3 control-label">Name</label>
						<input class="col-sm-6" type="text" name="name" id="name" class="form-control" value="{{ $character->char_name}}">
					</div>
					<div class="row">
						<label for="class" class="col-sm-3 control-label">Class</label>
						<select class="col-sm-6" id="class" name="class">
							@foreach ($classes as $class)
								@if ($class->class_id === $character->char_class)
									<option style="color:#{{ $class->class_color}}" value="{{ $class->class_id}}" selected>{{ $class->class_name}}</option>
								@else
									<option style="color:#{{ $class->class_color}}" value="{{ $class->class_id}}">{{ $class->class_name}}</option>
								@endif
							@endforeach
						</select>
					</div>
					<div class="row">
						<label for="role" class="col-sm-3 control-label">Role</label>
						<select class="col-sm-6" id="role" name="role">
							@foreach ($roles as $role)
								@if ($role->role_id === $character->char_role)
									<option value="{{ $role->role_id}}" selected>{{ $role->role_name}}</option>
								@else
									<option value="{{ $role->role_id}}">{{ $role->role_name}}</option>
								@endif
							@endforeach
						</select>
					</div>
					
					<div class="row">
						<input class="col-sm-offset-3 col-sm-6 btn btn-primary" type="submit" value="Update Character">
					</div-->
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@section('javascript')
<script>

</script>
@endsection