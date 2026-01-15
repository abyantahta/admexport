<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dn extends Model
{
    /** @use HasFactory<\Database\Factories\DnFactory> */
    use HasFactory;
    protected $fillable = ['dn_no', 'cycle', 'truck_no', 'week', 'order_date', 'periode', 'etd', 'qty_casemark', 'count_casemark', 'isMatch'];
    public function casemarks()
    {
        return $this->hasMany(Casemark::class, 'dn_no');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'dn_no');
    }
    public function dnHistory()
    {
        // Link by dn_no (not the default id) so history loads correctly
        return $this->hasOne(DNHistory::class, 'dn_no', 'dn_no');
    }
}
