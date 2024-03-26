<?php

namespace App\Models\Sphaira;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SphairaParamedic extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_Sphaira';
    protected $table = 'Paramedic';
    protected $guarded = ['ParamedicID'];
    protected $primaryKey = 'ParamedicID';
    public $timestamps = false;

    public function Specialty()
    {
        return $this->belongsTo(SphairaSpecialty::class, 'SpecialtyCode', 'SpecialtyCode');
    }
    public function sysGeneralCode()
    {
        return $this->belongsTo(SphairasysGeneralCode::class, 'GCParamedicType', 'GeneralCodeID');
    }
}
