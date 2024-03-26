<?php

namespace App\Models\Sphaira;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SphairaSpecialty extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_Sphaira';
    protected $table = 'Specialty';
    protected $guarded = ['SpecialtyCode'];
    protected $primaryKey = 'SpecialtyCode';
}
