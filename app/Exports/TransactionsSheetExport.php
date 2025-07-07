<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsSheetExport implements FromCollection, WithHeadings
{
    protected $dn_no, $start_date, $end_date;

    public function __construct($dn_no, $start_date, $end_date)
    {
        $this->dn_no = $dn_no;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Transaction::query();
        if ($this->dn_no) $query->where('dn_no', $this->dn_no);
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Status', 'DN Number', 'Count Casemark', 'Qty Casemark', 'Cycle', 'Truck No', 'Week', 'Order Date', 'Periode', 'ETD'
        ];
    }
}
