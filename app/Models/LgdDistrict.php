<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LgdDistrict extends Model
{
    protected $table = 'lgd_districts';
    protected $primaryKey = 'district_code';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'state_code',
        'state_name',
        'district_code',
        'district_name',
        'census_2001_code',
        'census_2011_code',
    ];
}
