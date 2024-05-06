<?php

namespace App\Models\Satusehat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LogEncounter extends Model
{
    use HasFactory;

    protected $connection = 'mysql_satusehat';
    protected $table = 'log_encounter';

    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }

    public function user()
    {
        return $this->belongsTo(UserSatusehat::class, 'updated_by', 'id');
    }
}
