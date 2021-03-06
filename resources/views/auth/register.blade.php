@extends('layouts.app')
@section('title')
| Register
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New Doctor Registration</div>
                <div class="panel-body">
                   @include('partials.flash', ['some' => 'data'])
                   <form data-parsley-validate class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">Name</label>

                        <div class="col-md-6">

                         <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" event.preventDefault(); required="" autofocus style="text-transform: uppercase;" data-parsley-required-message="*Name is required" placeholder="Full Name (Do Not Suffix Dr.)"> 



                         @if ($errors->has('name'))
                         <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

               <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">


                            <div class="col-md-8 col-md-offset-4">
                            <label class="radio-inline"><input type="radio" name="doctype" value="DOCTOR" checked="" id="doc">DOCTOR</label>
                           <label class="radio-inline"><input type="radio" name="doctype" value="RECEPTIONIST" id="rep">RECEPTIONIST</label>
                            {{--  <label class="radio-inline"><input type="radio" name="doctype" value="JUNIOR DOCTOR" id="jrdoc">OTHERS</label> --}}
                             @if ($errors->has('name'))
                             <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


               <div class="form-group{{ $errors->has('speciality') ? ' has-error' : '' }}">
                <label for="speciality" class="col-md-4 control-label">Select Specialty</label>

                <div class="col-md-6">
                    <select required="" data-parsley-required-message="*Kindly Select a Specialty" name="speciality" id="speciality" class="js-example-basic-single form-control">
                        @foreach ($specialities as $speciality)
                        <option value="{{$speciality->id}}" {{$speciality->speciality == 'GENERAL MEDICINE' ? 'selected="selected"' : ''}}>{{$speciality->speciality}}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('speciality'))
                    <span class="help-block">
                        <strong>{{ $errors->first('speciality') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div id="medico">
            <div class="form-group{{ $errors->has('medicalcouncil') ? ' has-error' : '' }}">
                <label for="medicalcouncil" class="col-md-4 control-label">Select Medical Council</label>

                <div class="col-md-6">
                    <select required="" data-parsley-required-message="*Kindly Select a State Medical Council" name="medicalcouncil" id="medicalcouncil" class="js-example-basic-single form-control">
                        <option value=" " selected="selected">SELECT MEDICAL COUNCIL</option>
                        @foreach ($medicalcouncils as $medicalcouncil)
                        <option value="{{$medicalcouncil->id}}" >{{$medicalcouncil->name}}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('medicalcouncil'))
                    <span class="help-block">
                        <strong>{{ $errors->first('medicalcouncil') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

             <div class="form-group{{ $errors->has('registrationyear') ? ' has-error' : '' }}">
                <label for="registrationyear" class="col-md-4 control-label">Select Registration Year</label>

                <div class="col-md-6">
                    <select required="" data-parsley-required-message="*Kindly Select the Registration Year" name="registrationyear" id="registrationyear" class="js-example-basic-single form-control">
                        <option value=" " selected="selected">SELECT REGISTRATION YEAR</option>
                        @foreach ($registrationyears as $registrationyear)
                        <option value="{{$registrationyear->id}}" >{{$registrationyear->year}}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('registrationyear'))
                    <span class="help-block">
                        <strong>{{ $errors->first('registrationyear') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('registrationnumber') ? ' has-error' : '' }}">
                <label for="registrationnumber" class="col-md-4 control-label">Registration Number</label>

                <div class="col-md-6">
                    <input id="registrationnumber" type="text" style="text-transform: uppercase;" class="form-control" name="registrationnumber" value="{{ old('registrationnumber') }}"   placeholder="REGISTRATION NUMBER">

                    @if ($errors->has('registrationnumber'))
                    <span class="help-block">
                        <strong>{{ $errors->first('registrationnumber') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            </div>{{-- .medico --}}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required="" data-parsley-required-message="*A valid Email is required to register" placeholder="Email Address">

                    @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                <label for="phone" class="col-md-4 control-label">Contact Number</label>

                <div class="col-md-6">
                    <input id="phone" type="text" required=""  data-parsley-pattern="(7|8|9)\d{9}" class="form-control" name="phone" value="{{ old('phone') }}" data-parsley-required-message="*A valid phone is required to register" data-parsley-pattern-message="*Invalid Mobile Number" placeholder="Mobile Number" maxlength="10">

                    @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('pan') ? ' has-error' : '' }}">
                <label for="pan" class="col-md-4 control-label">PAN Number</label>

                <div class="col-md-6">
                    <input id="pan" type="text"   data-parsley-pattern="/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/" style="text-transform: uppercase;" class="form-control" name="pan" value="{{ old('pan') }}" maxlength="10" data-parsley-pattern-message="*Invalid PAN Number" placeholder="PAN Number">

                    @if ($errors->has('pan'))
                    <span class="help-block">
                        <strong>{{ $errors->first('pan') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

                         {{-- <div class="form-group{{ $errors->has('passkey') ? ' has-error' : '' }}">
                            <label for="passkey" class="col-md-4 control-label">Passkey</label>

                            <div class="col-md-6">
                                <input id="passkey" type="text" class="form-control" name="passkey" value="{{ old('passkey') }}" required data-parsley-required-message="*The Passkey value is required to be filled in!" placeholder="Passkey">

                                @if ($errors->has('passkey'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('passkey') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required="" minlength="6" data-parsley-required-message="*Please set your password" placeholder="Password">

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required="" data-parsley-equalto="#password" data-parsley-required-message="*Please confirm your password!" data-parsley-equalto-message="*This should match the password provided!" placeholder="Password Again">

                                @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>

                                Have an account? <a href="{{ url('/login') }}"><strong>LOGIN</strong></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function(){
        $('#name').focus();
    });
  $("#gp").click(function(){
        $("#speciality").attr("disabled",false);
        $("#speciality").val("22");
         $('#medicalcouncil').attr("disabled",false);
        $('#medicalcouncil').val(" ");
    });
      $("#ayush").click(function(){
        $("#speciality").attr("disabled",false);
        $("#speciality").val("22");
         $('#medicalcouncil').attr("disabled",false);
        $('#medicalcouncil').val(" ");
    });
    $("#doc").click(function(){
        $("#speciality").attr("disabled",false);
        $("#speciality").val("22");
        $('#medicalcouncil').attr("disabled",false);
        $('#medicalcouncil').val(" ");
        //$("#medicalcouncil option[value='1']").remove();
        $('#medico').show();
        $('#registrationyear').attr("disabled",false);
        $('#registrationyear').val(" ");
        $('#medico').show();
    });
    $("#jrdoc").click(function(){
        $("#speciality").attr("disabled",false);
        $("#speciality").val("22");
         $('#medicalcouncil').attr("disabled",false);
        $('#medicalcouncil').val(" ");
    });
      $("#others").click(function(){
        $("#speciality").attr("disabled",false);
        $("#speciality").val("22");
         $('#medicalcouncil').attr("disabled",false);
        $('#medicalcouncil').val(" ");
    });
    $("#rep").click(function(){
        $("#speciality").attr("disabled",true);
        $("#speciality").val("73");
        $('#medicalcouncil').attr("disabled",true);
        $('#medicalcouncil').val("2");
        $('#registrationyear').attr("disabled",true);
        $('#registrationyear').val("2");
        $('#medico').hide();
    });
</script>
@endsection
