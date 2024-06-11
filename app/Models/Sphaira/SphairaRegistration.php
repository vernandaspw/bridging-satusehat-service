<?php

namespace App\Models\Sphaira;

use App\Models\Rme\RmePasienDiagnosa;
use App\Models\Rme\RmeRegistration;
use App\Models\Satusehat\Location;
use App\Models\Satusehat\LogEncounter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SphairaRegistration extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_Sphaira';
    protected $table = 'Registration';
    protected $guarded = ['RegistrationNo'];
    // protected $primaryKey = 'RegistrationNo';
    protected $primaryKey = 'RegistrationNo';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    public function pasien()
    {
        return $this->belongsTo(SphairaPatient::class, 'MedicalNo', 'MedicalNo');
    }
    public function dokter()
    {
        return $this->belongsTo(SphairaParamedic::class, 'ParamedicID', 'ParamedicID');
    }
    public function service_room()
    {
        return $this->belongsTo(SphairaServiceRoom::class, 'RoomID', 'RoomID');
    }
    public function bisnisPartner()
    {
        return $this->belongsTo(SphairaBusinessPartner::class, 'BusinessPartnerID', 'BusinessPartnerID');
    }

    public function location()
    {
        return $this->belongsTo(Location::class,'ServiceUnitID','ServiceUnitID');
    }

    public function diagnosa()
    {
        return $this->hasMany(SphairaDiagnosa::class,'RegistrationNo');
    }

    public function getRmeDischargeDateTime($noreg)
    {
        $data = RmeRegistration::where('reg_no', $noreg)->first();
        if ($data) {
            return $data->reg_discharge_tanggal;
        }
        return null;
    }

    public function rmeRegistration()
    {
        // return $this->belongsTo(RmeRegistration::class, 'RegistrationNo', 'reg_no');
        return $this->belongsTo(RmeRegistration::class, 'RegistrationNo', 'reg_no');
        //  return $this->hasMany(RmeRegistration::class, 'reg_no', 'RegistrationNo');
    }

    public function getRmeDiagnosa($noreg)
    {
        // return $this->hasMany(RmePasienDiagnosa::class, 'pdiag_reg');
        $datas = RmePasienDiagnosa::where('pdiag_reg', $noreg)->get();
        if ($datas) {
            return $datas;
        } else {
            return [];
        }
    }


    public function rmeDiagnosa()
    {
        return $this->hasMany(RmePasienDiagnosa::class, 'pdiag_reg');
    }

    function getLogEncounter($noreg) {
        $log = LogEncounter::where('noreg', $noreg)->first();
        if ($log) {
            return $log;
        }else{
            return [];
        }
    }

}
