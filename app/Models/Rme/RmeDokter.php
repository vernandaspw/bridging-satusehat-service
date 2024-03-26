<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmeDokter extends Model
{
    use HasFactory;


    protected $connection = 'sqlsrv_rs_rajal';
    protected $table = 'rs_m_paramedic';
    protected $guarded = ['ParamedicID'];
    protected $primaryKey = 'ParamedicID';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
