<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
  protected $fillable = [
    'name',
    'email',
    'phone',
    'state_code',
    'district_code',
    'subdistrict_code',
    'block_code',
    'message',
    'photo',
  ];
}
