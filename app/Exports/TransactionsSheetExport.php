<?php

namespace App\Exports;

use App\Models\Dn;
use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsSheetExport implements FromView, WithHeadings
{
    protected $dn_no, $start_date, $end_date;

    public function __construct($dn_no, $start_date, $end_date)
    {
        $this->dn_no = $dn_no;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        $query = Transaction::query();
        if ($this->start_date && $this->end_date) {
            $dnNos = Dn::whereBetween('order_date', [$this->start_date, $this->end_date])
            ->pluck('dn_no');
            $query->whereIn('dn_no', $dnNos);
        }
        if ($this->dn_no) $query->where('dn_no', $this->dn_no);

        $transactions = $query->get();
        // dd($transactions);
        return view('pages.export_transactions', [
            'transactions' => $transactions
        ]);
    }

    public function headings(): array
    {
        return [
            'No',
            'Status',
            'DN Number',
            'Count Casemark',
            'Qty Casemark',
            'Cycle',
            'Truck No',
            'Week',
            'Order Date',
            'Periode',
            'ETD'
        ];
    }
}
