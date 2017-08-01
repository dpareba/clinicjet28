@extends('layouts.master')
@section('title')
| My Patients
@stop
@section('pageheading')
My Patients		
@stop
@section('subpageheading')
View/Search for My Patients 
@stop
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
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Full Name</th>
              <th>Primary Phone</th>
              <th>Alternate Phone</th>
              <th>Email</th>
              <th>Patient Code</th>
              <th>Registered On</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($visits as $visit)
              <?php $count = 1 ?>
                @foreach ($visit as $v)
                  @if ($count==1)
                    <tr>
                      <td><a href="{{route('patients.show',$v->patient->id)}}">{{$v->patient->name}} {{$v->patient->midname}} {{$v->patient->surname}}</a></td>
                      <td>{{$v->patient->phoneprimary}}</td>
                      <td>{{$v->patient->phonealternate}}</td>
                      <td>{{$v->patient->email}}</td>
                      {{-- <td>{{$v->patient->patientcode}}</td> --}}
                      <td>{{$v->patient->patcode}}</td>
                      <td>{{date('M j, Y',strtotime($v->patient->created_at))}}</td>
                    </tr>
                  @endif
                  <?php $count+=1; ?>
                @endforeach

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