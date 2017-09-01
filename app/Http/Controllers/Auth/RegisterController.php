<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Passkey;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str; //Added
use Illuminate\Http\Request; //Added
use Illuminate\Auth\Events\Registered; //Added
use Mail; //Added
use App\Mail\ConfirmationEmail; //Added
use App\Speciality;//added
use App\Medicalcouncil;
use App\Registrationyear;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showRegistrationForm()
    {
        $specialities = Speciality::all();
        $medicalcouncils = Medicalcouncil::where('id','!=','1')->get();
        $registrationyears = Registrationyear::where('year','!=','1900')->orderBy('year','desc')->get();
        return view('auth.register')->withSpecialities($specialities)->withMedicalcouncils($medicalcouncils)->withRegistrationyears($registrationyears);
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if ($data['doctype']=="RECEPTIONIST") {
           return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|min:10|max:10|unique:users,phone',
            'pan' => 'min:10|max:10|unique:users,pan',
            'doctype' => 'required'
            ],[
            'pan.unique'=>'A User with this PAN Number already exists!',
            'phone.unique'=>'User with this phone number already exists'
            ]);
       }else{
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|min:10|max:10|unique:users,phone',
            'pan' => 'required|min:10|max:10|unique:users,pan',
            'doctype' => 'required',
            'speciality' => 'required',
            'medicalcouncil' => 'required',
            'registrationyear' => 'required',
            'registrationnumber'=>'required'
            ],[
            'pan.required' => 'PAN Number is required',
            'pan.unique'=>'A User with this PAN Number already exists!',
            'phone.unique'=>'User with this phone number already exists',
            'medicalcouncil.required'=>'The Medical Council name is required',
            'registrationyear.required'=>'The Registration Year is required',
            'registrationnumber.required'=>'Please provide Registration Number'
            ]);
    }



}

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
         if ($data['doctype']=="RECEPTIONIST") {
            return User::create([
            'name' => Str::upper($data['name']),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'],
            'pan' => Str::upper($data['pan']),
            'speciality_id' => "73",
            'doctype' => $data['doctype'],
            'medicalcouncil_id'=>"1",
            'registrationyear_id'=>"1"
            ]);
        }else{
            //dd($data);
            return User::create([
            'name' => Str::upper($data['name']),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'],
            'pan' => Str::upper($data['pan']),
            'speciality_id' => $data['speciality'],
            'doctype' => $data['doctype'] ,
            'medicalcouncil_id'=>$data['medicalcouncil'],
            'registrationyear_id'=>$data['registrationyear'],
            'registrationnumber'=>Str::upper($data['registrationnumber'])
            ]);
        }
        
        
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        Mail::to($user->email)->send(new ConfirmationEmail($user));

        // return back()->with('status','We have sent an account activation link to your email-id.');

        return redirect()->route('login')->withStatus('Please click on the activatation link we have sent to your e-mail id inorder to activate your account.');
    }

    public function confirmEmail($token){
        User::whereToken($token)->firstOrFail()->hasVerified();

        return redirect('login')->with('status','Your email is now verified, Please Login');
    }
}
