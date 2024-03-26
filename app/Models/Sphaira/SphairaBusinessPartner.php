<?php

namespace App\Models\Sphaira;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SphairaBusinessPartner extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv_Sphaira';
    protected $table = 'BusinessPartner';
    protected $guarded = ['BusinessPartnerID'];
    // protected $primaryKey = 'BusinessPartnerID';

}
