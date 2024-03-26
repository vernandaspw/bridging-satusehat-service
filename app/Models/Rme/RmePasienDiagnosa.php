<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmePasienDiagnosa extends Model
{
    use HasFactory;


    protected $connection = 'sqlsrv_rs_rajal';
    protected $table = 'rs_pasien_diagnosa';
    protected $guarded = ['pdiag_reg'];
    protected $primaryKey = 'pdiag_reg';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
