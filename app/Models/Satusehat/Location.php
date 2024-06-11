<?php

namespace App\Models\Satusehat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $connection = 'mysql_satusehat';
    protected $table = 'locations';
}
