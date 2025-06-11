<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;
    protected $fillable = ['part_no_kanban', 'part_no_qc', 'seq_no_kanban', 'seq_no_qc', 'status', 'casemark_no'];
    public function casemark()
    {
        return $this->belongsTo(Casemark::class, 'casemark_no');
    }
}
