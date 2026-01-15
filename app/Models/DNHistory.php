<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DNHistory extends Model
{
    use HasFactory;
    
    protected $table = 'dn_histories';
    
    protected $fillable = ['dn_no', 'pic', 'remarks', 'is_verified'];
    
    public function dn()
    {
        // Link back to DN by dn_no (not the default id)
        return $this->belongsTo(Dn::class, 'dn_no', 'dn_no');
    }
}
