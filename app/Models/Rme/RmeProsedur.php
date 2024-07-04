<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmeProsedur extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_rs_rajal';
    protected $table = 'rs_pasien_prosedur';
    protected $guarded = ['pprosedur_id'];
    protected $primaryKey = 'pprosedur_id';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
