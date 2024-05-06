<?php

namespace App\Models\Satusehat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSatusehat extends Model
{
    use HasFactory;

    protected $connection = 'mysql_satusehat';

    protected $table = 'users';
}
