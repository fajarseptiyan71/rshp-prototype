<?php

namespace App\Repositories;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorRepository
{
    public function get()
    {
        return Doctor::query()->with('specialist');
    }

    public function save(Request $request, Doctor $doctor = null)
    {
        $doctor = !is_null($doctor) ? $doctor : new Doctor();
        $doctor->name = $request->name;
        $doctor->specialist_id = $request->specialist_id;
        $doctor->birth_date = $request->birth_date;
        $doctor->birth_place = $request->birth_place;
        $doctor->gender = $request->gender;
        $doctor->address = $request->address;
        $doctor->save();

        return $doctor;
    }

    public function delete(int $id)
    {
        return Doctor::query()->where('id', $id)->delete();
    }
}
