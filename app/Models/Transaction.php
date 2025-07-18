<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;
    protected $fillable = ['part_no_kanban', 'part_no_label','kanban_barcode','label_barcode', 'seq_no_kanban', 'seq_no_label', 'status', 'casemark_no','dn_no','lot_no'];
    public function casemark()
    {
        return $this->belongsTo(Casemark::class, 'casemark_no');
    }
    public function dn()
    {
        return $this->belongsTo(Dn::class, 'dn_no');
    }
}
