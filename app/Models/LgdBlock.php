<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LgdBlock extends Model
{
    protected $table = 'lgd_blocks';

    protected $fillable = [
        'id',
        'serial_no',
        'state_code',
        'state_name',
        'district_code',
        'district_name',
        'block_code',
        'block_version',
        'block_name',
        'block_name_alt',
    ];
}
