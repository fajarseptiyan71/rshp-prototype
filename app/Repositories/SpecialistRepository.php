<?php

namespace App\Repositories;

use App\Models\Specialist;
use Illuminate\Http\Request;

class SpecialistRepository
{
    public function get()
    {
        return Specialist::query();
    }

    public function save(Request $request, Specialist $specialist = null): object
    {
        $specialist = !is_null($specialist) ? $specialist : new Specialist();
        $specialist->name = $request->name;
        $specialist->save();
        return $specialist;
    }

    public function delete(int $id)
    {
        return Specialist::query()->where('id', $id)->delete();
    }

}
