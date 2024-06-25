<?php

namespace App\Models\Sphaira;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SphairaPenunjang extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_Sphaira';
    protected $table = 'Item';
    protected $guarded = ['ItemID'];
    protected $primaryKey = 'ItemID';

    public $incrementing = false;
    public $timestamps = false;
}
