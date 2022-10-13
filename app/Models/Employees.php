<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\FlagScope;
use Illuminate\Support\Facades\DB;

class Employees extends Model
{
    use HasFactory;

    protected $table = 'employees';
    public $timestamps = false;

    protected $fillable = ['avatar',
        'team_id',
        'email',
        'first_name',
        'last_name',
        'password',
        'gender',
        'birthday',
        'address',
        'salary',
        'position',
        'type_of_work',
        'status',
        'ins_id',
        'upd_id',
        'ins_datetime',
        'upd_datetime',
        'del_flag'];

    protected $hidden = ['password'];

    protected static function booted()
    {
        static::addGlobalScope(new FlagScope);
    }

    public function team(){
        return $this->belongsTo(Teams::class);
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucfirst($value),
        );
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucfirst($value),
        );
    }
}
