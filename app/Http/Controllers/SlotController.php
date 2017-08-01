<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\User;
use App\Clinic;
use Session;
use App\Slot;
use Carbon\Carbon;
use DB;
use Auth;

class SlotController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $clinicid = Clinic::where(['cliniccode'=>Session::get('cliniccode')])->first()->id;
        $dt = Carbon::now();
        // $dtdate = $dt->toDateString();
        // dd($dtdate);
        // $usercount = Slot::where('clinic_id',$clinicid)->where('slotdate','=',$dt->toDateString())->count(DB::raw('DISTINCT user_id'));
        $slots = Slot::where('clinic_id',$clinicid)->where('slotdate','=',$dt->toDateString())->get();

        //dd($slots);
        //$usercount = $slots->groupBy('user_id')->count();
        $slots = $slots->groupBy('user_id');
        //$slots->toArray();
        //dd($slots);
        //dd($slotsgrp);
        //$docname->toArray();
        //$docname->implode('token');
        //dd($usercount);
        //$docname = $slots->keyBy('user_id');
        //dd($docname);
        //return view('slots.index')->withDocname($docname);
        //$s = Slot::all();
        return view('slots.index')->withSlots($slots);
    }

    public function appointmentstoday(){
       $dt = Carbon::now();
       $clinicid = Clinic::where(['cliniccode'=>Session::get('cliniccode')])->first()->id;
       $slots = Slot::where('clinic_id',$clinicid)->where('user_id','=',Auth::user()->id)->where('slotdate','=',$dt->toDateString())->orderBy('token','asc')->get();
            //dd($slots);
       return view('patients.today')->withSlots($slots);
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $this->validate($request,[
            'user'=>'required',
            'slotdate'=>'required'
            ]);

        $format = 'd/m/Y';
        $input = $request->slotdate;
        $date = Carbon::createFromFormat($format,$input);
        $clinicid = Clinic::where(['cliniccode'=>Session::get('cliniccode')])->first()->id;

        $patientdup = Slot::where('user_id','=',$request->user)->where('slotdate','=',$date->toDateString())->where('clinic_id','=',$clinicid)->where('patient_id','=',$request->patient_id)->get();
        //dd($patientdup);
        if($patientdup->isEmpty()){
            $slot = new Slot;

        //$dateadd = Carbon::createFromFormat($format,$input)->addDay(1);
        //dd($input . ' ' . $date);

            $slot->slotdate = $date;
            $slot->user_id = $request->user;
            $slot->patient_id = $request->patient_id;

            $slot->clinic_id = $clinicid;
            $slot->slotstatus_id = 1;
        //dd($date->toDateString());
        //$slots = Slot::where('user_id','=',$request->user)->where('slotdate','>',$date)->where('slotdate','<',$date)->orderBy('token','DESC')->first();
            $slots = Slot::where('user_id','=',$request->user)->where('slotdate','=',$date->toDateString())->where('clinic_id','=',$clinicid)->orderBy('token','DESC')->first();
        // $slodt = Carbon::createFromFormat($format,$input);
        //$slots = Slot::all();
       //dd($slots);
       //$slotdate = Carbon::createFromTimestamp($)
            if ($slots == null) {
                $slot->token = 1;
            }else{
                $slot->token = $slots->token + 1;
            }
        //return $dt;
            $slot->save();

            Session::flash('message','Success!!');
            Session::flash('text','New Token Number generated successfully!!');
            Session::flash('type','success');
            Session::flash('timer','5000');
        }else{
            Session::flash('message','Failed!!');
            Session::flash('text','Patient has already been issued token for this doctor on this day!!');
            Session::flash('type','error');
        }
        

        return redirect()->route('patients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function assigntoken(Request $request){
        //dd($request);
        $patient = Patient::findOrFail($request->patient_id);
        $clinicid = Clinic::where(['cliniccode'=>Session::get('cliniccode')])->first()->id;
        $users = Clinic::find($clinicid)->users->where('doctype','!=','RECEPTIONIST');
        return view('slots.assigntoken')->withPatient($patient)->withUsers($users);
    }
}
