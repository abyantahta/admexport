<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CasemarksTransactionsExport implements WithMultipleSheets
{
    protected $dn_no, $start_date, $end_date;

    public function __construct($dn_no, $start_date, $end_date)
    {
        $this->dn_no = $dn_no;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function sheets(): array
    {
        return [
            new CasemarksSheetExport($this->dn_no, $this->start_date, $this->end_date),
            new TransactionsSheetExport($this->dn_no, $this->start_date, $this->end_date),
        ];
    }
}
