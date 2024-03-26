<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmeServiceUnit extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_rs_rajal';
    protected $table = 'rs_m_service_unit';
    protected $guarded = ['reg_no'];
    protected $primaryKey = 'ServiceUnitCode';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
