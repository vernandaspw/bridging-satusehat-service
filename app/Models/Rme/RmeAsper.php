<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmeAsper extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_rs_rajal';
    protected $table = 'rs_pasien_asper';
    protected $guarded = ['asper_id'];
    protected $primaryKey = 'asper_id';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
