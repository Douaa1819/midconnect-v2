<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ReservationsController extends Controller
{
    
    public function index($doctorID)
    {
        $result = DB::select("SHOW COLUMNS FROM reservations WHERE Field = 'booking_time'");
        preg_match("/^enum((.*))$/", $result[0]->Type, $matches);
        
        $appointments = array();
        foreach (explode(',', $matches[1]) as $value) {
            $appointments[] = trim($value, "('')");
           
        }
       
        $appointments_reserved = Reservation::where('doctor_id', $doctorID)->pluck('booking_time');
    
        $doctorID = $doctorID;
        // $appointements= Reservation::with('patient')->get();
        return view('patient\reservation', compact('appointments','appointments_reserved', 'doctorID'));   
    }

    public function create()
    {

    }


    public function urgent(){

        $appointments_reserved = Reservation::with(['doctor.user', 'doctor.specialite'])
        ->whereHas('doctor.specialite', function ($query) {
            $query->where('nom', '=', 'Médecine générale');
        })
        ->get();
    
    
        $result = DB::select("SHOW COLUMNS FROM reservations WHERE Field = 'booking_time'");
        preg_match("/^enum((.*))$/", $result[0]->Type, $matches);
        
        $appointments = array();
        foreach (explode(',', $matches[1]) as $value) {
            $appointments[] = trim($value, "('')");
           
        }

     return view('patient.urgent',compact('appointments','appointments_reserved'));

    }

    public function store(Request $request)

        {
            $validatedData = $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'patient_id' => 'required|exists:patients,id',
                'booking_time' => 'required',
    
            ]);
    
    
            $appointment = new Reservation();
            $appointment->doctor_id = $validatedData['doctor_id'];
            $appointment->patient_id = $validatedData['patient_id'];
            $appointment->booking_time = $validatedData['booking_time'];
    
            $appointment->save();
            return redirect()->back()->with('success', 'Appointment booked successfully!');
        }
    public function reservationUrgent(Request $request){
        
        $validatedData = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'booking_time' => 'required',

        ]);


        $appointment = new Reservation();
        $appointment->doctor_id = $validatedData['doctor_id'];
        $appointment->patient_id = $validatedData['patient_id'];
        $appointment->booking_time = $validatedData['booking_time'];

        $appointment->save();
        return redirect()->back()->with('success', 'Appointment booked successfully!');

    }
    
   public function doctorProfile($doctorID){
    $doctor= Doctor::findOrFail($doctorID);
    return view('patient.doctorProfile',compact('doctor'));
   }
    public function update(Request $request)
    
    
    {

    }

   
    public function destroy(string $id)
    {
        //
    }
}
