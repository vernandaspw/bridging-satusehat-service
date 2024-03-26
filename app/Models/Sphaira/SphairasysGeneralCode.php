<?php

namespace App\Models\Sphaira;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SphairasysGeneralCode extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_Sphaira';
    protected $table = 'sysGeneralCode';
    protected $guarded = ['GeneralCodeID'];
    protected $primaryKey = 'GeneralCodeID';
}
