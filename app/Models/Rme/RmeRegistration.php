<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmeRegistration extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_rs_rajal';
    protected $table = 'rs_registration';
    protected $guarded = ['reg_no'];
    protected $primaryKey = 'reg_no';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function service_room()
    {
        return $this->belongsTo(RmeServiceRoom::class, 'reg_poli', 'RoomCode');
    }

    public function pasien()
    {
        return $this->belongsTo(RmePasien::class, 'reg_medrec', 'MedicalNo');
    }
    public function dokter()
    {
        return $this->belongsTo(RmeDokter::class, 'reg_dokter', 'ParamedicCode');
    }
    public function bisnis_partner()
    {
        return $this->belongsTo(RmeBisnisPartner::class, 'reg_corporate', 'BusinessPartnerID');
    }
}
