<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Doctor extends Model
{
    use HasFactory;
    protected $table = 'doctors';
    public $timestamps = false;

    public function specialist(): HasOne
    {
        return $this->hasOne(Specialist::class, 'id', 'specialist_id');
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class, 'doctor_id', 'id');
    }
}
