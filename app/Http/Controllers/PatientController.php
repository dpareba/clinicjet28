<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Auth;
use App\Patient;
use App\Clinic;
use App\Pathology;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Str; //Added
use App\State;
use Charts;
use App\Visit;
use App\Template;
use App\Slot;


class PatientController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function docspatients(){
        $visits = Visit::where('user_id','=',Auth::user()->id)->get();
        $visits = $visits->groupBy('patient_id');
        //dd($visits);
        return view('patients.docspatients')->withVisits($visits);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$roles = User::find(Auth::user()->id)->roles()->get();

            // $clinicid = Clinic::where(['cliniccode'=>Session::get('cliniccode')])->first()->id;
            // $patients = Clinic::find($clinicid)->patients;
        if (Auth::user()->doctype == "RECEPTIONIST") {
            $patients = Patient::all();
            return view('patients.index')->withPatients($patients);
        }else{
            return redirect()->route('slots.appointmentstoday');

        }
        
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::all();
        return view('patients.create1')->withStates($states);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        if ($request->cbage == "on") {
            $this->validate($request,[
                'approxage'=>'required',
                'name'=>'required|max:255',
            //'midname'=>'required|max:255',
                'midname'=>'max:255',
                'surname'=>'required|max:255',
                'dob'=>'date_format:d/m/Y|before:tomorrow',
                'gender'=>'required|max:6',
                'bloodgroup'=>'required|max:10',
                'allergies'=>'required',
                'address'=>'required',
                'patientstate'=>'required',
                'patientcity'=>'required',
                'patientpin'=>'required|min:6|max:6',
            // 'phoneprimary'=>'required|digits:10|unique:patients,phoneprimary',
            // 'phonealternate'=>'required|digits:10|unique:patients,phonealternate',
                'idproof' => 'digits:12|unique:patients,idproof',
                'phoneprimary'=>'required|digits:10|unique:patients,phoneprimary',
                'phonealternate'=>'required|digits:10',
                'email'=>'email'
                ],[
                'approxage.required'=>'Approximate age of patient not entered',
                'name.required'=>'First Name is required to be entered',
            //'midname.required'=>'Middle Name is required to be entered',
                'surname.required'=>'Surname is required to be entered',
                'name.alpha'=>'The Name may only contain alphabets',
                'allergies.required'=>'Please enter know allergies.Enter Not known otherwise.',
                'phoneprimary.required'=>'Primary Phone Number is compulsory',
                'phoneprimary.digits'=>'Phone number needs to contain 10 digits',
                'phoneprimary.unique'=>'Patient with this phone number is already registered',
                'phonealternate.required'=>'Emergency Phone Number is compulsory',
                'phonealternate.digits'=>'Phone number needs to contain 10 digits',
                'idproof.digits'=>'Aadhar number needs to contain 12 digits',
            // 'phonealternate.unique'=>'Patient with this phone number is already registered',
                'dob.date'=>'The Date of Birth should be in mm/dd/yyyy format.',
                'dob.before'=>'The Date of Birth cannot be later than the date today.',
                'idproof.digits'=>'Invalid Aadhar Number',
                'idproof.unique'=>'Aadhar number already exists'
                ]);
        }else{
            $this->validate($request,[
                'name'=>'required|max:255',
                'midname'=>'max:255',
                'surname'=>'required|max:255',
                'dob'=>'date_format:d/m/Y|before:tomorrow',
                'gender'=>'required|max:6',
                'bloodgroup'=>'required|max:10',
                'allergies'=>'required',
                'address'=>'required',
                'patientstate'=>'required',
                'patientcity'=>'required',
                'patientpin'=>'required|min:6|max:6',
            // 'phoneprimary'=>'required|digits:10|unique:patients,phoneprimary',
            // 'phonealternate'=>'required|digits:10|unique:patients,phonealternate',
                'idproof' => 'digits:12|unique:patients,idproof',
                'phoneprimary'=>'required|digits:10|unique:patients,phoneprimary',
                'phonealternate'=>'required|digits:10',
                'email'=>'email'
                ],[
                'name.required'=>'First Name is required to be entered',
            //'midname.required'=>'Middle Name is required to be entered',
                'surname.required'=>'Surname is required to be entered',
                'name.alpha'=>'The Name may only contain alphabets',
                'allergies.required'=>'Please enter know allergies.Enter Not known otherwise.',
                'phoneprimary.required'=>'Primary Phone Number is compulsory',
                'phoneprimary.digits'=>'Phone number needs to contain 10 digits',
                'phoneprimary.unique'=>'Patient with this phone number is already registered',
                'dob.date'=>'The Date of Birth should be in mm/dd/yyyy format.',
                'dob.before'=>'The Date of Birth cannot be later than the date today.',
                'idproof.digits'=>'Invalid Aadhar Number',
                'idproof.unique'=>'Aadhar number already exists'
                ]);
        }

        //$cliniccode = Session::get('cliniccode');
        $clinic = Clinic::where(['cliniccode'=>Session::get('cliniccode')])->first();
        //dd($clinicid);
        //$clinic = Clinic::find($clinicid);
       // dd($clinic);
        $patient = new Patient;
        $patient->name = trim(Str::upper($request->name));
        $patient->midname = trim(Str::upper($request->midname));
        $patient->surname = trim(Str::upper($request->surname));
        $patient->namemidsur = $request->namemidsur;
        $patient->namesur = $request->namesur;
        if ($request->dob == "") {
            $input = '01/01/1900';
        }else{
            $input = $request->dob;
        }
        if($request->cbage == "on"){
            $patient->isapproxage = true;
            $approxdobinput = $request->approxdob;
            $patient->approxage = $request->approxage;
        }else{
            $patient->isapproxage = false;
            $approxdobinput = '01/01/1900';
            $patient->approxage = '';
        }
        //$format = 'm/d/Y';
        $format = 'd/m/Y';
        $date = Carbon::createFromFormat($format,$input);
        $patient->dob = $date;
        $approxdate = Carbon::createFromFormat($format,$approxdobinput);
        $patient->approxdob = $approxdate;
        $patient->gender = Str::upper($request->gender);
        $patient->phoneprimary = $request->phoneprimary;
        $patient->phonealternate = $request->phonealternate;
        $patient->email = $request->email;
        $patient->address = Str::upper($request->address);
        $patient->patientstate = Str::upper($request->patientstate);
        $patient->patientcity = Str::upper($request->patientcity);
        $patient->patientpin = Str::upper($request->patientpin);
        $patient->allergies = Str::upper($request->allergies);
        $patient->bloodgroup = $request->bloodgroup;
        $maxpatid = Patient::orderBy('id','desc')->first();
        //dd($maxpatid);
        $maxpatid = Patient::orderBy('patientcode','desc')->first();
        //dd($maxpatid);
        //$patient->patientcode = rand(1000,9999);
        if ($maxpatid == null) {
            $patient->patientcode = 1000;
        }else{
             $patient->patientcode = $maxpatid->patientcode + 1;
        }
        

        $maxpatcode = Patient::orderBy('id','desc')->first();
        $patcode = 'CLI' . ($maxpatcode->id + 1);
        //dd($patcode);
        $patient->patcode = $patcode;
       
        $patient->idproof = $request->idproof;
        $patient->created_by = Auth::user()->id;
        $patient->save();
        $patient->clinics()->attach($clinic);

        Session::flash('message','Success!!');
        Session::flash('text','New Patient Added to Clinic successfully!!');
        Session::flash('type','success');
        Session::flash('timer','5000');

        return redirect()->route('patients.index');
    }

    public function showVisits(Request $request){
        $patient = Patient::findOrFail($request->patient_id);
        //dd($patient);
        // $dt1 = Carbon::create($patient->created_at);
        // $dt = Carbon::toDateString($dt1);
        //$dt = $patient->created_at->diffForHumans();
        $user = User::find($patient->created_by);
        //$visits = $patient->visits;
        return view('visits.show')->withPatient($patient)->withUser($user);
    }

    public function createconsult($id,$repeatvisitid,$editconsult){
        //dd($editconsult);
        $patient = Patient::findOrFail($id);
        if ($repeatvisitid!=0) {
            $repeatid = $repeatvisitid;
        }else{
            $repeatid = 0;
        }
                
        $user = User::find($patient->created_by);
        //$pathologies = Pathology::all();
        $pathologies = Pathology::where('user_id','=','1')->orWhere('user_id','=',Auth::user()->id)->get();
        $templates = Template::where('user_id','=',Auth::user()->id)->get();
        $bpdata = Visit::where('patient_id','=',$patient->id)->where('systolic','!=','')->where('diastolic','!=','')->get();
        $randombsdata = Visit::where('patient_id','=',$patient->id)->where('randombs','!=','')->get();
        $pulsedata = Visit::where('patient_id','=',$patient->id)->where('pulse','!=','')->get();
        $respratedata = Visit::where('patient_id','=',$patient->id)->where('resprate','!=','')->get();
        $spodata = Visit::where('patient_id','=',$patient->id)->where('spo','!=','')->get();
        $weightdata = Visit::where('patient_id','=',$patient->id)->where('weight','!=','')->get();
        $heightdata = Visit::where('patient_id','=',$patient->id)->where('height','!=','')->get();
        $bmidata = Visit::where('patient_id','=',$patient->id)->where('bmi','!=','')->get();

        $bpchart = Charts::multi('areaspline','highcharts')
        ->height(250)
                        //->colors(['#58355E','#7AE7C7'])
                        //->colors(['#6E0D25','#FFFFB3'])
                        //->colors(['#7EB19F','#0C8282'])
        ->colors(['#72DDF7','#2F4858'])
        ->title('Blood Pressure (mmHg)')
        ->elementLabel('mmHg')
        ->labels($bpdata->pluck('created_at'))
        ->dataset('Systolic',$bpdata->pluck('systolic'))
        ->dataset('Diastolic',$bpdata->pluck('diastolic'))
        ->responsive(false)
        ;

        $randombschart = Charts::multi('line','highcharts')
        ->height(300)
        ->colors(['#2F4858'])
        ->title('Random Blood Sugar (mg/dl)')
        ->elementLabel('mg/dl')
        ->labels($randombsdata->pluck('created_at'))
        ->dataset('Random Blood Sugar',$randombsdata->pluck('randombs'))
        ->responsive(false);

        $pulsechart = Charts::multi('line','highcharts')
        ->height(300)
        ->colors(['#2F4858'])
        ->title('Pulse (beats per minute)')
        ->elementLabel('beats per minute')
        ->labels($pulsedata->pluck('created_at'))
        ->dataset('Pulse',$pulsedata->pluck('pulse'))
        ->responsive(false);

        $respratechart = Charts::multi('area','highcharts')
        ->height(300)
        ->colors(['#2F4858'])
        ->title('Respiratory Rate (breaths per minute)')
        ->elementLabel('breaths per minute')
        ->labels($respratedata->pluck('created_at'))
        ->dataset('Respiratory Rate',$respratedata->pluck('resprate'))
        ->responsive(false);

        $spochart = Charts::multi('bar','highcharts')
        ->height(300)
        ->colors(['#2F4858'])
        ->title('SPO2 (%)')
        ->elementLabel('%')
        ->labels($spodata->pluck('created_at'))
        ->dataset('SPO2',$spodata->pluck('spo'))
        ->responsive(false);

        $weightchart = Charts::multi('areaspline','highcharts')
        ->height(300)
        ->colors(['#2F4858'])
        ->title('Weight (in kgs)')
        ->elementLabel('kgs')
        ->labels($weightdata->pluck('created_at'))
        ->dataset('Weight',$weightdata->pluck('weight'))
        ->responsive(false);

        $heightchart = Charts::multi('bar','highcharts')
        ->height(300)
        ->colors(['#2F4858'])
        ->title('height (in cms)')
        ->elementLabel('cms')
        ->labels($heightdata->pluck('created_at'))
        ->dataset('Height',$heightdata->pluck('height'))
        ->responsive(false);

        $bmichart = Charts::multi('line','highcharts')
        ->height(300)
        ->colors(['#2F4858'])
        ->title('BMI')
        ->elementLabel('BMI')
        ->labels($bmidata->pluck('created_at'))
        ->dataset('BMI',$bmidata->pluck('bmi'))
        ->responsive(false);

        return view('patients.createconsult')->withPatient($patient)->withUser($user)->withPathologies($pathologies)->withBpchart($bpchart)->withRandombschart($randombschart)->withPulsechart($pulsechart)->withRespratechart($respratechart)->withSpochart($spochart)->withWeightchart($weightchart)->withHeightchart($heightchart)->withBmichart($bmichart)->withTemplates($templates)->withRepeatid($repeatid)->withEditconsult($editconsult);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patient = Patient::find($id);
        
        // $dt = Carbon::now();
        // $clinicid = Clinic::where(['cliniccode'=>Session::get('cliniccode')])->first()->id;
        // $slot = Slot::where('patient_id','=',$id)->where('clinic_id',$clinicid)->where('user_id','=',Auth::user()->id)->where('slotdate','=',$dt->toDateString())->first();
        
        //dd($patient);
        // $slot->slotstatus_id = 2;
        // $slot->save();
        return view('patients.show')->withPatient($patient);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = Patient::find($id);
        return view('patients.edit')->withPatient($patient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
     $this->validate($request,[
        'name'=>'required|max:255',
        'midname'=>'max:255',
        'surname'=>'required|max:255',
        'gender'=>'required|max:6',
        'bloodgroup'=>'required|max:10',
        'phoneprimary'=>'max:15',
        'phonealternate'=>'max:15',
        'email'=>'email'
        ],[
        'name.required'=>'Full Name is required to be entered',
        'name.alpha'=>'The Name may only contain alphabets'
        ]);

     $patient = Patient::find($id);
      $patient->name = trim(Str::upper($request->name));
        $patient->midname = trim(Str::upper($request->midname));
        $patient->surname = trim(Str::upper($request->surname));
     $patient->namemidsur = $request->namemidsur;
     $patient->namesur = $request->namesur;
     $patient->gender = Str::upper($request->gender);
     $patient->phoneprimary = $request->phoneprimary;
     $patient->phonealternate = $request->phonealternate;
     $patient->email = $request->email;
     $patient->address = Str::upper($request->address);
     $patient->allergies = Str::upper($request->allergies);
     $patient->bloodgroup = $request->bloodgroup;
     $patient->idproof = $request->idproof;
     $patient->save();

     Session::flash('message','Success!!');
     Session::flash('text','Patient Details updated successfully!!');
     Session::flash('type','success');
     Session::flash('timer','5000');

     return redirect()->route('patients.show',$patient->id);

 }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
