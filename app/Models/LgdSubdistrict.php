<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LgdSubdistrict extends Model
{
    protected $table = 'lgd_subdistricts';
    protected $primaryKey = 'subdistrict_code';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'serial_no',
        'state_code',
        'state_name',
        'district_code',
        'district_name',
        'subdistrict_code',
        'subdistrict_version',
        'subdistrict_name',
        'census_2001_code',
        'census_2011_code',
    ];
}
