<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmeServiceRoom extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_rs_rajal';
    protected $table = 'rs_m_service_room';
    protected $guarded = ['RoomID'];
    protected $primaryKey = 'RoomID';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
