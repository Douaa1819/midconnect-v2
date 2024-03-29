<?php

namespace App\Http\Controllers;

use App\Models\Favoris;
use Illuminate\Http\Request;
use App\Models\Specialite; 
use Illuminate\Support\Facades\Auth;

class SpecialiteController extends Controller


{

    public function Show()
    {
        $specialites = Specialite::all();
        return view('admine.géreSpécialiter', compact('specialites')); 
    }


    public function showForm() 
{
    $specialites = Specialite::all();
    return view('doctor.Médicament', compact('specialites'));
}

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        Specialite::create([
            'nom' => $request->input('nom'),
        ]);

        return redirect()->back()->with('success', 'Spécialité ajoutée avec succès.');
    }


    public function update(Request $request, $id)
{
    $request->validate([
        'nom' => 'required|string|max:255',
    ]);

    $specialite = Specialite::findOrFail($id);
    $specialite->update([
        'nom' => $request->nom,
    ]);

    return redirect()->back()->with('success', 'Spécialité modifiée avec succès.');
}

    public function showDoctors($id)
{
    $specialite = Specialite::with('doctors')->findOrFail($id);
       return view('patient.doctors', compact('specialite'));
}


public function delete($id)
{
    try {
        $data = Specialite::findOrFail($id); // Use findOrFail to automatically handle the model not found exception
        $data->delete();
        return redirect()->back()->with('success', 'Spécialité supprimée avec succès.');
    } catch (\Exception $e) {
        // Handle the error accordingly
        return redirect()->back()->with('error', 'Erreur lors de la suppression de la spécialité.');
    }
}


    public function manageSpeciality()
    {
       
        return view('admin.gereSpecialiter'); 
    }

    public function manageMedicament()
    {
       
        return view('admin.gereMedicament');
    }
}
