<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casemark extends Model
{
    /** @use HasFactory<\Database\Factories\CasemarkFactory> */
    use HasFactory;
    protected $fillable = ['casemark_no', 'part_no', 'part_name', 'box_type', 'qty_per_box', 'qty_box', 'count_box', 'isMatched', 'dn_no'];

    public function dn()
    {
        return $this->belongsTo(Dn::class, 'dn_no');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'casemark_no');
    }
}
