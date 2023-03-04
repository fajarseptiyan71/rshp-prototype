<?php

namespace App\Repositories;

use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationRepository
{

    public function get()
    {
        return Consultation::query()->with('user')->with('doctor');
    }

    public function getById(string $field, int $id)
    {
        return Consultation::query()->where($field, $id);
    }

    public function save(Request $request, Consultation $consultation = null)
    {
        $consultation = !is_null($consultation) ? $consultation : new Consultation()  ;
        $consultation->user_id = $request->user_id;
        $consultation->doctor_id = $request->doctor_id;
        $consultation->date = $request->date;
        $consultation->animal_type = $request->animal_type;
        $consultation->save();

        return $consultation;
    }

    public function delete(int $id)
    {
        return Consultation::query()->where('id', $id)->delete();
    }
}
