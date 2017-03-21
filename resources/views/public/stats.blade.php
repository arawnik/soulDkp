@extends('layouts.master')

@section('content')
<h1>{{ trans('public.statistics') }}</h1>

<h2>{{ trans('data.attendance') }}</h2>
<table class="table table-hover">
	<thead>
		<tr>
			<th>{{ trans('data.name') }}</th>
			<th>{{ trans('data.class') }}</th>
			<th>{{ trans('data.role') }}</th>
			<th>{{ trans('public.lifetime_attendance') }}</th>
			<th>{{ trans('public.last_ten_attendance') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($attendances as $att)
		<tr>
			<td><a style="color:#{{ $att->class_color }}" class="class_name" href="/char/{{ $att->char_id }}">{{ $att->name }}</a></td>
			<td>{{ $att->class_name }}</td>
			<td>{{ $att->role_name }}</td>
			<td>{{ $att->attendance_lifetime }} / {{ $att->raids_lifetime }}</td>
			<td>{{ $att->attendance_last_ten }} / 10</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endsection