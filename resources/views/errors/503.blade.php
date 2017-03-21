@extends('layouts.master')

@section('above')
@endsection

@section('content')
	<div class="maintenanceBlock">
		<img src="/assets/img/under_construction.png" alt="Site under maintenance" class="maintenanceImg">
		<h1>SoulDKP is currently under maintenance.</h1>
		
		<div class="messageBlock">{{ $exception->getMessage() }}</div>
		
		<p>This message should be gone shortly. If this issue persists, please contact the site administrator.</p>
	</div>
@endsection