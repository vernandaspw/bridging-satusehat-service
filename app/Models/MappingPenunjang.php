<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sphaira\SphairaPenunjang;

class MappingPenunjang extends Model
{
    use HasFactory;
    protected $connection = 'mysql_satusehat';
    protected $table = 'mapping_penunjang';
    protected $guarded = ['ItemIDMap'];

    public function SphairaPenunjang()
    {
        return $this->belongsTo(SphairaPenunjang::class, 'ItemID', 'ItemIDMap');
    }
}
