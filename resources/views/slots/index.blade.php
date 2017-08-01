@extends('layouts.master')
@section('title')
| Today's Appointments
@stop
@section('pageheading')
Today's Tokens		
@stop
@section('subpageheading')
View/Search Patients with Token Numbers
@stop

@section('content')
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<?php $isactive = 1; ?>
				@foreach ($slots as $slot)
					<?php $count = 1 ?>
					@foreach ($slot as $s)

						@if ($count == 1)
							<li class="{{$isactive==1?'active':''}}"><a href="#{{$s->user_id}}" data-toggle="tab">DR. {{$s->user->name}}</a></li>
						@endif
					<?php $count+=1; ?>
					@endforeach
					<?php $isactive+=1; ?>
				@endforeach
			</ul>
			
			<div class="tab-content">
				<?php $isactive = 1; ?>
				@foreach ($slots as $slot)
					<?php $count = 1 ?>
					@foreach ($slot as $s)
						@if ($count == 1)
							<div class="{{$isactive==1?'active':''}} tab-pane" id="{{$s->user_id}}">
              <div class="box box-gray">
              <div class="box-header with-border">
              <h3 class="box-title">Today's Appointments -- Dr. {{$s->user->name}}</h3>
              <div class="box-body">
              <div class="table-responsive">
              <table class="table no-margin text-center">
                <thead>
                  <tr>
                  	<th>Token Number</th>
                    <th>Patient Name</th>
                    <th>Primary Phone</th>
                    <th>Patient Code</th>
                   {{--  <th>Status</th> --}}
                  </tr>
                  </thead>
                 <tbody>
						@endif
            <tr>
            <td><span class="label label-success">{{$s->token}}</span></td>
            <td>{{$s->patient->name}} {{$s->patient->midname}} {{$s->patient->surname}}</td>
            <td>{{$s->patient->phoneprimary}}</td>
            {{-- <td>{{$s->patient->patientcode}}</td> --}}
            <td>{{$s->patient->patcode}}</td>
            {{-- <td><span class="label label-primary">{{$s->slotstatus->slotstatus}}</span></td> --}}
            </tr>
						<?php $count+=1; ?>
					@endforeach
					<?php $isactive+=1; ?>
            </tbody>
            </table>{{-- .table --}}
            </div>{{-- .table-responsive --}}
            </div>{{-- .box-body --}}
            </div>{{-- .box-header with-border --}}
            </div>{{-- .box box-info --}}
						</div>{{-- .tab-pane --}}
				@endforeach
				
					
				
				{{-- <div class="tab-pane" id="3">Hi</div> --}}
			</div>
		</div>
	</div>
</div>



<div class="row">
	{{-- @foreach ($slots as $slot)
		{{$slot->token}} <br>
		@endforeach --}}

		{{-- {{$s->groupBy('user_id')}}; --}}
		{{-- {{$s-}} --}}
	{{-- @foreach ($s as $se)
		{{$se->user->name}} <br>
		{{$se}}
		@endforeach --}}

	{{-- @foreach ($slots as $s)
		{{$s[0]['user_id']}}
		{{$s}}
		@endforeach --}}
	{{-- @foreach ($slots->groupBy('user_id') as $e)
		{{$e[0]['token']}}
		@endforeach --}}
	{{-- @foreach ($slots->groupBy('user_id') as $slot)
		{{$slots->user->name}} 
		@foreach ($slot as $s)
			uid: {{$s->user_id}}
			token: {{$s->token}}
			<br>
		@endforeach
		<br>
		@endforeach --}}
		{{-- @foreach ($slots as $slot)
		@foreach ($slot as $s)
		name: {{$s->user->name}}
		patient: {{$s->patient->name}} {{$s->patient->midname}} {{$s->patient->surname}}
		uid: {{$s->user_id}}
		token: {{$s->token}}
		<br>
		@endforeach
		<br>
		@endforeach --}}
		{{-- {{$slots->groupBy('user_id')}} --}}
	</div>
	@stop
	{{-- .row --}}
