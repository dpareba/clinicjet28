@extends('layouts.master')
@section('title')
	| Today's Appointments
@stop
@section('pageheading')
	Today's Appointments		
@stop
@section('subpageheading')
	View/Search for Patients with appointments today
@stop
@section('refresh')
  <meta http-equiv="Refresh" content="300">
@endsection
@section('content')
	{{-- {{$patients}} --}}
	<div class="row">
        <div class="col-xs-12">
 			<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Registered Patients</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                <th>Token Number</th>
                  <th>Full Name</th>
                  <th>Primary Phone</th>
                  <th>Alternate Phone</th>
                  <th>Email</th>
                  <th>Patient Code</th>
                   <th>Registered On</th>
                   {{-- <th>Status</th> --}}
                </tr>
                </thead>
                <tbody>
                {{-- @foreach ($patients as $patient)
                	<tr>
	                  <td><a href="{{route('patients.show',$patient->id)}}">{{$patient->name}} {{$patient->midname}} {{$patient->surname}}</a></td>
	                  <td>{{$patient->phoneprimary}}</td>
	                  <td>{{$patient->phonealternate}}</td>
	                  <td>{{$patient->email}}</td>
	                  <td>{{$patient->patientcode}}</td>
	                   <td>{{date('M j, Y',strtotime($patient->created_at))}}</td>
                	</tr>
                @endforeach --}}

                @foreach ($slots as $slot)
                  <tr>
                    <td><span class="label label-success">{{$slot->token}}</span></td>
                    <td><a href="{{route('patients.show',$slot->patient->id)}}">{{$slot->patient->name}} {{$slot->patient->midname}} {{$slot->patient->surname}}</a></td>
                    <td>{{$slot->patient->phoneprimary}}</td>
                    <td>{{$slot->patient->phonealternate}}</td>
                    <td>{{$slot->patient->email}}</td>
                   {{--  <td>{{$slot->patient->patientcode}}</td> --}}
                    <td>{{$slot->patient->patcode}}</td>
                     <td>{{date('M j, Y',strtotime($slot->patient->created_at))}}</td>
                   {{--  <td><span class="label label-primary">{{$slot->slotstatus->slotstatus}}</span></td> --}}
                  </tr>
                @endforeach
                
                  
                </tbody>
                <tfoot>
                <tr>
                  <th>Full Name</th>
                  <th>Primary Phone</th>
                  <th>Alternate Phone</th>
                  <th>Email</th>
                  <th>Patient Code</th>
                   <th>Registered On</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
@stop