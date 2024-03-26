<?php

namespace App\Models\Sphaira;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SphairaServiceRoom extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv_Sphaira';
    protected $table = 'ServiceRoom';
    protected $guarded = ['RoomID'];
    protected $primaryKey = 'RoomID';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
