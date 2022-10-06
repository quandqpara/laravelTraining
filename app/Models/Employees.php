<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    protected $table = 'employees';
    public $timestamps = false;
    protected $fillable = [ 'avatar','team_id','email','first_name','last_name',
                            'password', 'gender','birthday','address','salary',
                            'position','type_of_work','status','ins_id',
                            'upd_id','ins_datetime','upd_datetime','del_flag'];
}
