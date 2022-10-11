<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\FlagScope;

class Teams extends Model
{
    use HasFactory;
    protected $table = 'teams';
    public $timestamps = false;
    protected  $fillable = ['name','ins_id', 'upd_id', 'ins_datetime', 'upd_datetime', 'del_flag'];

    protected static function  booted()
    {
        static::addGlobalScope(new FlagScope);
    }

    public function employee(){
        return $this->hasMany(Employees::class);
    }
}
