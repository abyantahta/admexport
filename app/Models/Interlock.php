<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interlock extends Model
{
    /** @use HasFactory<\Database\Factories\InterlockFactory> */
    use HasFactory;
    protected $guarded = [];
}
