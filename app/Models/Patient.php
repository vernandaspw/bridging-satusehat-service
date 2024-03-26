<?php

namespace App\Models;

use App\Models\Sphaira\SphairaPatient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'patients';
    protected $guarded = ['id'];
    public function SphairaPatient()
    {
        return $this->belongsTo(SphairaPatient::class, 'MedicalNo', 'MedicalNo');
    }
}
