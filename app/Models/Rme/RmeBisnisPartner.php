<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmeBisnisPartner extends Model
{
    use HasFactory;


    protected $connection = 'sqlsrv_rs_rajal';
    protected $table = 'rs_m_business_partner';
    protected $guarded = ['BusinessPartnerID'];
    protected $primaryKey = 'BusinessPartnerID';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
