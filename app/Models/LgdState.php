<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LgdState extends Model
{
    protected $table = 'lgd_states';
    protected $primaryKey = 'state_code';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'serial_no',
        'state_code',
        'state_version',
        'state_name',
        'state_name_alt',
        'census_2001_code',
        'census_2011_code',
        'state_or_ut',
    ];
}
